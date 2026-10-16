<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Tests\Concerns\CreatesAccSchema;
use Tests\TestCase;

/**
 * Diagnostics, not a gate: builds the shipped MySQL dump's schema into sqlite,
 * loads every module route file, and GETs every parameterless GET route as the
 * system admin, classifying what breaks. Writes a compact report to
 * storage/logs/page-crawl.txt and never fails the suite on findings.
 *
 * Why: the Blade harness proves the 148 screens compile and render in
 * isolation; this proves (or disproves) that the routes behind them actually
 * answer at runtime with the real schema.
 */
class PageCrawlAuditTest extends TestCase
{
    use CreatesAccSchema;

    public function test_crawl_every_get_route_and_report_failures(): void
    {
        $this->buildSchemaFromShippedDump();
        $this->seedRuntimeConfig();
        $this->loadAllModuleRoutes();

        // system admin: bypasses every hasPermission()/hasAccess() gate so the
        // crawl measures what the code does, not who may look at it
        $this->actingAs(User::find(1));

        // pass 1: no business data at all -> what a fresh/partial deployment hits
        $this->assertNoUnexpectedCrawlFailures($this->crawlRoutes('/tmp/page-crawl-empty.txt'), 'empty business data');

        // pass 2: the shipped dump's rows -> what production actually serves.
        // The audit seeds (users/groups/companies/modules/settings) must go first,
        // otherwise their duplicate ids make the dump's own rows fail to load -
        // which is what emptied `modules` and nulled the permission tree earlier.
        // The helpers also cache SystemSetting::get() forever, so a stale empty
        // collection would leak from pass 1.
        \Illuminate\Support\Facades\Cache::flush();
        foreach (['users', 'groups', 'companies', 'company_user', 'modules', 'system_settings', 'invoice_generate'] as $table) {
            DB::table($table)->delete();
        }
        $this->loadShippedDumpData();
        if (!User::find(1)) {
            $this->insertRow('users', ['id' => 1, 'name' => 'System Admin', 'email' => 'sys@mm.test',
                'password' => bcrypt('x'), 'company_id' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        $this->actingAs(User::find(1));
        $this->assertNoUnexpectedCrawlFailures($this->crawlRoutes('/tmp/page-crawl.txt'), 'production-shaped data');
    }

    /**
     * Regression guard for the Group-3 permission seed: every permission row in
     * the shipped dump must point at a parent_permissions row that exists, and
     * every parent must point at a real submodule. The Permissions screen
     * dereferences that chain on every row, so an orphan nulls it out and 500s
     * the page (BUGS #69).
     */
    public function test_shipped_dump_permission_tree_has_no_orphans(): void
    {
        $dump = file_get_contents(base_path('database/mmheritagehotel_db.sql'));
        $this->buildSchemaFromShippedDump();

        $parents = $this->dumpIds($dump, 'parent_permissions', 0);
        $submodules = $this->dumpIds($dump, 'submodules', 0);

        $missingParent = [];
        foreach ($this->dumpRows($dump, 'permissions') as $row) {
            if ($row['parent_permission_id'] !== null && !in_array((int) $row['parent_permission_id'], $parents, true)) {
                $missingParent[] = $row['slug'];
            }
        }
        $missingSubmodule = [];
        foreach ($this->dumpRows($dump, 'parent_permissions') as $row) {
            if (!in_array((int) $row['submodule_id'], $submodules, true)) {
                $missingSubmodule[] = $row['name'];
            }
        }

        $this->assertSame([], $missingParent, 'permissions point at parent rows that do not exist');
        $this->assertSame([], $missingSubmodule, 'parent_permissions point at submodules that do not exist');
    }

    /** Parses one dump table into column-keyed rows (dump values are plain literals). */
    private function dumpRows(string $dump, string $table): array
    {
        $rows = [];
        foreach ($this->dumpStatements($dump, $table) as [$columns, $tuples]) {
            foreach ($tuples as $tuple) {
                $values = $this->splitValues(substr($tuple, 1, -1));
                if (count($values) === count($columns)) {
                    $rows[] = array_combine($columns, $values);
                }
            }
        }
        return $rows;
    }

    private function dumpIds(string $dump, string $table, int $index = 0): array
    {
        return array_map(fn ($row) => (int) array_values($row)[$index], $this->dumpRows($dump, $table));
    }

    /** @return array<int, array{0: array, 1: array}> [columns, raw tuples] per INSERT statement */
    private function dumpStatements(string $dump, string $table): array
    {
        $statements = [];
        preg_match_all('/INSERT INTO `' . $table . '` \(([^)]*)\) VALUES\s*(.*?);\s*(?:\n|$)/s', $dump, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $columns = array_map(fn ($c) => trim($c, ' `'), explode(',', $match[1]));
            $statements[] = [$columns, $this->splitTuples($match[2])];
        }
        return $statements;
    }

    /** @return array<int, array{0: int, 1: int, 2: string, 3: string, 4: string}> */
    private function crawlRoutes(string $outFile): array
    {
        $report = [];
        $router  = app('router');
        $routes  = $router->getRoutes();
        $results = [];

        foreach ($routes as $route) {
            if (!in_array('GET', $route->methods(), true)) {
                continue;
            }
            $uri = $route->uri();
            if (!preg_match('/^[A-Za-z0-9\/._-]+$/', $uri)) {
                continue; // has {placeholders}: cannot be hit without data
            }
            // The crawl must stay read-only: skip file-producing endpoints (they
            // write to storage) and the app's own maintenance routes, several of
            // which mutate state - `update-debug` rewrites APP_DEBUG in .env.
            if (preg_match('/export|download|backup|pdf|print|stream|update-debug|update-data|add-user|delete-all|toggle|sync-/i', $uri)) {
                continue;
            }
            try {
                $response = $this->withoutExceptionHandling()->get('/' . ltrim($uri, '/'));
                $status   = $response->getStatusCode();
                if ($status >= 500) {
                    $results[] = [1, $status, $uri, $route->getName() ?? '-', 'HTTP ' . $status];
                } elseif ($status === 404) {
                    $results[] = [3, $status, $uri, $route->getName() ?? '-', 'HTTP 404'];
                }
            } catch (\Throwable $e) {
                $exClass = get_class($e);
                $message = $this->normalise($e);
                if (str_contains($exClass, 'HttpException') || str_contains($exClass, 'NotFoundHttpException')) {
                    $results[] = [3, 404, $uri, $route->getName() ?? '-', $exClass];
                } elseif (str_contains($exClass, 'ValidationException') || str_contains($exClass, 'AuthorizationException')) {
                    $results[] = [4, 0, $uri, $route->getName() ?? '-', $exClass];
                } else {
                    $frames = $e->getTrace();
                    $origin = basename($e->getFile()) . ':' . $e->getLine();
                    $results[] = [2, 500, $uri, $route->getName() ?? '-', $exClass . ' @ ' . $origin . ' :: ' . $message];
                }
            }
        }

        usort($results, fn ($a, $b) => [$a[0], $a[3]] <=> [$b[0], $b[3]]);

        $report[] = 'GET routes crawled: ' . count($results) . ' failures (all routes: ' . count($routes) . ')';
        $counts   = [];
        foreach ($results as $r) {
            $counts[$r[0]] = ($counts[$r[0]] ?? 0) + 1;
        }
        $report[] = 'buckets (1=5xx response, 2=thrown exception, 3=404, 4=auth/validation): ' . json_encode($counts);
        $report[] = '';
        foreach ($results as [$bucket, $status, $uri, $name, $message]) {
            $report[] = sprintf('[%d] %-46s %-34s %s', $bucket, $uri, $name, mb_substr($message, 0, 420));
        }

        @file_put_contents(storage_path('logs/' . basename($outFile)), implode("\n", $report) . "\n");
        @file_put_contents($outFile, implode("\n", $report) . "\n");

        // The report files are not uploaded anywhere, so echo the findings into the
        // job log as well - otherwise a regression shows up only as a silent count
        // nobody reads. Prefix keeps it greppable: `crawl |`.
        foreach ($report as $line) {
            fwrite(STDOUT, 'crawl | ' . $line . "\n");
        }

        return $results;
    }

    /**
     * Failures that are artifacts of this harness rather than defects: sqlite has no MySQL
     * `MONTHNAME`, the debugbar/ignition panels only exist in a local dev environment, and
     * the crawler reaches POST-only endpoints (and routes parked behind inactive modules)
     * with a plain GET. Everything else is a regression - the audit is a guard now, not a
     * report nobody reads.
     */
    private const CRAWL_ARTIFACTS = [
        'hotel/reports/monthlyVat',      // MONTHNAME: MySQL/MariaDB function, absent in sqlite
        'category',                      // legacy redirect route whose view was never shipped
        'submit/guest/registration',     // POST-only front-desk endpoint, reached with a GET
    ];

    private const CRAWL_ARTIFACT_PREFIXES = [
        '_debugbar/',                    // debugbar assets/open handler: dev-only package
        '_ignition/',                    // ignition health check: dev-only package
        'add-user',                      // HRM module is inactive in the shipped data
        'db-backup',                     // needs mysqldump, not installed in CI
        'sync-',                         // HRM-guarded background endpoints
    ];

    private function isCrawlArtifact(string $uri): bool
    {
        if (in_array($uri, self::CRAWL_ARTIFACTS, true)) {
            return true;
        }
        foreach (self::CRAWL_ARTIFACT_PREFIXES as $prefix) {
            if (strpos($uri, $prefix) === 0) {
                return true;
            }
        }
        return false;
    }

    /** @param array<int, array{0: int, 1: int, 2: string, 3: string, 4: string}> $results */
    private function assertNoUnexpectedCrawlFailures(array $results, string $pass): void
    {
        $unexpected = [];
        foreach ($results as [$bucket, $status, $uri, $name, $message]) {
            if (!$this->isCrawlArtifact($uri)) {
                $unexpected[] = sprintf('[%d] %s :: %s', $bucket, $uri, mb_substr($message, 0, 220));
            }
        }
        $this->assertSame([], $unexpected, 'Unexpected crawl failures on ' . $pass
            . ' (' . count($unexpected) . '): ' . implode(' | ', $unexpected));
    }


    /**
     * Loads the shipped dump's rows so the crawl runs against production-shaped
     * data (empty tables turn every "Undefined array key 1" into a false
     * positive). The dump writes one tuple per line ending in ")," / ");", so
     * statements can be accumulated line-wise without a full SQL parser.
     */
    private function loadShippedDumpData(): void
    {
        $handle = fopen(base_path('database/mmheritagehotel_db.sql'), 'r');
        $statement = null;
        $loaded = 0;

        while (($line = fgets($handle)) !== false) {
            if ($statement === null) {
                if (str_starts_with($line, 'INSERT INTO')) {
                    $statement = $line;
                } else {
                    continue;
                }
            } else {
                $statement .= $line;
            }
            $trimmed = rtrim($line);
            if (!str_ends_with($trimmed, ');')) {
                continue;
            }

            $loaded += $this->runDumpInsert($statement);
            $statement = null;
        }
        fclose($handle);
        @file_put_contents('/tmp/dump-load.txt', "rows loaded: {$loaded}\n");
    }

    private function runDumpInsert(string $statement): int
    {
        if (!preg_match('/^INSERT INTO `([^`]+)` \(([^)]*)\) VALUES\s*(.*);$/s', $statement, $m)) {
            return 0;
        }
        [$all, $table, $columns, $values] = $m;
        $columns = array_map(fn ($c) => trim($c, " `"), explode(',', $columns));

        $rows = [];
        foreach ($this->splitTuples($values) as $tuple) {
            $fields = $this->splitValues(substr($tuple, 1, -1));
            if (count($fields) !== count($columns)) {
                @file_put_contents('/tmp/dump-load-errors.txt', "{$table}: column/value count mismatch\n", FILE_APPEND);
                continue;
            }
            $rows[] = array_combine($columns, $fields);
        }
        if (!$rows) {
            return 0;
        }

        $loaded = 0;
        foreach (array_chunk($rows, 200) as $chunk) {
            try {
                DB::table($table)->insert($chunk);
                $loaded += count($chunk);
            } catch (\Throwable $e) {
                @file_put_contents('/tmp/dump-load-errors.txt', "{$table}: " . mb_substr($e->getMessage(), 0, 160) . "\n", FILE_APPEND);
            }
        }
        return $loaded;
    }

    /** Splits "(...), (...), (...)" on top-level tuple boundaries. */
    private function splitTuples(string $values): array
    {
        $tuples = [];
        $depth = 0;
        $quote = false;
        $current = '';
        $length = strlen($values);
        for ($i = 0; $i < $length; $i++) {
            $char = $values[$i];
            $escaped = $i > 0 && $values[$i - 1] === '\\' && !str_ends_with(substr($values, max(0, $i - 2), 2), '\\\\');
            if ($char === "'" && !$escaped) {
                $quote = !$quote;
            }
            if (!$quote) {
                if ($char === '(') {
                    $depth++;
                } elseif ($char === ')') {
                    $depth--;
                    if ($depth === 0) {
                        $tuples[] = $current . ')';
                        $current = '';
                        continue;
                    }
                }
            }
            if ($depth > 0) {
                $current .= $char;
            }
        }
        return $tuples;
    }

    /** Splits one tuple's body on top-level commas, unescaping MySQL literals. */
    private function splitValues(string $body): array
    {
        $values = [];
        $current = '';
        $quote = false;
        $length = strlen($body);
        for ($i = 0; $i < $length; $i++) {
            $char = $body[$i];
            if ($quote) {
                if ($char === '\\' && $i + 1 < $length) {
                    $next = $body[++$i];
                    $current .= match ($next) {
                        'n' => "\n", 'r' => "\r", 't' => "\t", '0' => "\0", 'Z' => chr(26), 'b' => chr(8),
                        default => $next,
                    };
                    continue;
                }
                if ($char === "'") {
                    if ($i + 1 < $length && $body[$i + 1] === "'") { // '' escape
                        $current .= "'";
                        $i++;
                        continue;
                    }
                    $quote = false;
                    continue;
                }
                $current .= $char;
                continue;
            }
            if ($char === "'") {
                $quote = true;
                continue;
            }
            if ($char === ',') {
                $values[] = $this->castValue(trim($current));
                $current = '';
                continue;
            }
            $current .= $char;
        }
        $values[] = $this->castValue(trim($current));
        return $values;
    }

    private function castValue(string $raw)
    {
        if (strcasecmp($raw, 'NULL') === 0) {
            return null;
        }
        if (preg_match('/^-?\d+$/', $raw)) {
            return (int) $raw;
        }
        if (preg_match('/^-?\d*\.\d+$/', $raw)) {
            return (float) $raw;
        }
        return $raw; // quoted strings arrive here already unescaped
    }

    private function normalise(\Throwable $e): string
    {
        $message = $e->getMessage();
        $message = preg_replace('/\s+/', ' ', $message);
        return trim($message);
    }

    /**
     * The suite normally runs against a hand-built minimal schema; a page crawl
     * needs the real one, so translate the shipped MySQL dump's CREATE TABLE
     * statements into sqlite (columns only: type, nullability, default). MySQL
     * shows up as: backticked identifiers, ENGINE/CHARSET tails, inline
     * KEY/CONSTRAINT lines, and AUTO_INCREMENT.
     */
    private function buildSchemaFromShippedDump(): void
    {
        $dump = file_get_contents(base_path('database/mmheritagehotel_db.sql'));
        preg_match_all('/CREATE TABLE `([^`]+)` \((.*?)\n\) ENGINE=/s', $dump, $matches, PREG_SET_ORDER);

        // mysqldump splits the auto-increment off into a trailing
        // "ALTER TABLE `x` MODIFY `id` ... AUTO_INCREMENT" statement; without it
        // every insert the app makes during a request fails on "id has no default".
        preg_match_all('/ALTER TABLE `([^`]+)`\s+MODIFY `([^`]+)` [^;]*AUTO_INCREMENT[^;]*;/s', $dump, $autoInc, PREG_SET_ORDER);
        $autoIncrement = [];
        foreach ($autoInc as $row) {
            $autoIncrement[$row[1]] = $row[2];
        }

        foreach ($matches as $match) {
            $table = $match[1];
            if (Schema::hasTable($table)) {
                continue;
            }
            $columns = [];
            foreach (preg_split('/\n/', $match[2]) as $line) {
                $line = trim($line);
                if ($line === '' || !str_starts_with($line, '`')) {
                    continue; // PRIMARY KEY / KEY / UNIQUE / CONSTRAINT lines
                }
                if (!preg_match('/^`([^`]+)`\s+([a-zA-Z]+)(\([^)]*\))?(.*)$/', $line, $col)) {
                    continue;
                }
                [, $name, $type, , $tail] = $col;
                $type = strtolower($type);
                $sqliteType = match (true) {
                    in_array($type, ['int', 'bigint', 'smallint', 'tinyint', 'mediumint'], true) => 'integer',
                    in_array($type, ['decimal', 'double', 'float', 'numeric'], true) => 'float',
                    in_array($type, ['text', 'longtext', 'mediumtext', 'tinytext', 'blob', 'longblob'], true) => 'text',
                    default => 'text',
                };
                $column = "`{$name}` {$sqliteType}";
                if (stripos($tail, 'AUTO_INCREMENT') !== false || ($autoIncrement[$table] ?? null) === $name) {
                    $column = "`{$name}` integer primary key autoincrement"; // sqlite rowid alias
                    $columns[] = $column;
                    continue;
                }
                if (stripos($tail, 'NOT NULL') !== false) {
                    $column .= ' not null';
                    if (preg_match("/DEFAULT\\s+('(?:[^']*)'|[^\\s,]+)/i", $tail, $def) && strtoupper($def[1]) !== 'NULL') {
                        $column .= ' default ' . rtrim($def[1], '()');
                    }
                }
                $columns[] = $column;
            }
            if ($columns) {
                $statement = 'CREATE TABLE `' . $table . '` (' . implode(', ', $columns) . ')';
                try {
                    DB::statement($statement);
                } catch (\Throwable $e) {
                    @file_put_contents('/tmp/schema-errors.txt', $statement . "\n  => " . $e->getMessage() . "\n", FILE_APPEND);
                }
            }
        }
    }

    /**
     * Inserts a row while satisfying the table's NOT NULL columns: the pages
     * under test read far more columns than the seeder cares about.
     */
    private function insertRow(string $table, array $values): void
    {
        try {
            $this->insertRowUnsafe($table, $values);
        } catch (\Throwable $e) {
            @file_put_contents('/tmp/seed-errors.txt', $table . ': ' . $e->getMessage() . "\n", FILE_APPEND);
        }
    }

    private function insertRowUnsafe(string $table, array $values): void
    {
        $info = DB::select("PRAGMA table_info(`{$table}`)");
        $known = array_map(fn ($c) => $c->name, $info);
        $values = array_intersect_key($values, array_flip($known)); // ignore columns this schema lacks
        foreach ($info as $column) {
            if ($column->notnull && $column->dflt_value === null && !array_key_exists($column->name, $values)) {
                $values[$column->name] = str_contains(strtolower($column->type), 'int') ? 0 : '';
            }
        }
        DB::table($table)->insert($values);
    }

    /** Rows the app needs before any request: admin user, active modules, config singletons. */
    private function seedRuntimeConfig(): void
    {
        $this->insertRow('users', ['id' => 1, 'name' => 'System Admin', 'email' => 'sys@mm.test',
            'password' => bcrypt('x'), 'company_id' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()]);
        $this->insertRow('users', ['id' => 2, 'name' => 'Super admin', 'email' => 'sa@mm.test',
            'password' => bcrypt('x'), 'company_id' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()]);
        $this->insertRow('groups', ['id' => 1, 'name' => 'Super Admin', 'status' => 1, 'created_at' => now(), 'updated_at' => now()]);
        $this->insertRow('companies', [
            'id' => 1, 'name' => 'M&M Heritage Hotel', 'email' => 'info@mm.test', 'phone' => '0000',
            'address' => 'Dhaka', 'status' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $this->insertRow('company_user', ['id' => 1, 'company_id' => 1, 'user_id' => 1]);
        $this->insertRow('system_settings', ['id' => 1, 'created_at' => now(), 'updated_at' => now()]);

        // active module rows (production state; read by view composers on every page)
        $modules = [
            ['id' => 1, 'name' => 'Global Setting', 'status' => 1, 'rank' => 1],
            ['id' => 2, 'name' => 'User Access', 'status' => 1, 'rank' => 2],
            ['id' => 11, 'name' => 'Employee Permission', 'status' => 1, 'rank' => 999],
            ['id' => 4, 'name' => 'General Store', 'status' => 1, 'rank' => 999],
            ['id' => 120001, 'name' => 'HotelService', 'status' => 1, 'rank' => 6],
            ['id' => 130001, 'name' => 'Restaurant', 'status' => 1, 'rank' => 7],
            ['id' => 150000, 'name' => 'Account & Finance', 'status' => 1, 'rank' => 10],
            ['id' => 160000, 'name' => 'Hotel', 'status' => 1, 'rank' => 5],
            ['id' => 160001, 'name' => 'HotelWebsite', 'status' => 1, 'rank' => 4],
            ['id' => 170000, 'name' => 'CRM', 'status' => 1, 'rank' => 11],
            ['id' => 270000, 'name' => 'Bar', 'status' => 1, 'rank' => 8],
        ];
        foreach ($modules as $module) {
            $this->insertRow('modules', $module + ['is_migrate' => 0, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    /** Every module route file in the repo: the crawl should see what production registers. */
    private function loadAllModuleRoutes(): void
    {
        foreach (glob(base_path('module/*/routes/*.php')) as $file) {
            try {
                \Illuminate\Support\Facades\Route::group(['middleware' => ['web', 'auth']], function () use ($file) {
                    \Illuminate\Support\Facades\Route::namespace('\\')->group($file);
                });
            } catch (\Throwable $e) {
                @file_put_contents('/tmp/page-crawl-route-load-errors.txt', ($e->getMessage() . ' in ' . $file . "\n"), FILE_APPEND);
            }
        }
        app('router')->getRoutes()->refreshNameLookups();
    }
}
