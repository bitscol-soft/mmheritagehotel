<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Builds the real application schema in sqlite from the shipped MySQL dump, and
 * seeds the rows the framework needs before it will serve a request.
 *
 * Extracted from PageCrawlAuditTest so that tests which need more than the
 * hand-built minimal schema (the POS/AJAX endpoints touch rst_sales, rst_stocks,
 * booking_dates, ...) can use the same translation instead of guessing tables.
 */
trait BuildsShippedSchema
{
    /**
     * The suite normally runs against a hand-built minimal schema; a page crawl
     * needs the real one, so translate the shipped MySQL dump's CREATE TABLE
     * statements into sqlite (columns only: type, nullability, default). MySQL
     * shows up as: backticked identifiers, ENGINE/CHARSET tails, inline
     * KEY/CONSTRAINT lines, and AUTO_INCREMENT.
     */
    protected function buildSchemaFromShippedDump(): void
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
    protected function insertRow(string $table, array $values): void
    {
        try {
            $this->insertRowUnsafe($table, $values);
        } catch (\Throwable $e) {
            @file_put_contents('/tmp/seed-errors.txt', $table . ': ' . $e->getMessage() . "\n", FILE_APPEND);
        }
    }

    protected function insertRowUnsafe(string $table, array $values): void
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
    /**
     * One coherent business row per module master, so the parameterised sweep can
     * open show/edit screens instead of skipping them for want of data.
     *
     * The shipped dump deliberately carries masters only (no sales, items or
     * bookings), so a sweep over production data exercises 24 screens out of 150.
     * These rows are NOT added to the dump - they exist only in the test, and they
     * are the minimum a screen needs to resolve: the row itself, and the parents
     * it joins to (unit -> item -> product -> sale).
     *
     * Kept deliberately small. If a screen needs more than this to render, that is
     * a finding in its own right.
     */
    protected function seedBusinessData(): void
    {
        $now = ['created_at' => now(), 'updated_at' => now()];

        // --- masters -------------------------------------------------------
        $this->insertRow('units', ['id' => 1, 'name' => 'Pcs', 'company_id' => 1] + $now);
        $this->insertRow('acc_categories', ['id' => 1, 'name' => 'Beverage', 'company_id' => 1] + $now);
        $this->insertRow('item_units', [
            'id' => 1, 'group_id' => 1, 'name' => 'Pcs', 'conversion' => 1, 'status' => 1,
        ] + $now);
        $this->insertRow('items', [
            'id' => 1, 'company_id' => 1, 'item_unit_id' => 1, 'name' => 'Tissue Box',
            'opening_balance' => 10, 'rate' => 100, 'remaining_quantity' => 10,
            'current_stock' => 10, 'average_rate' => 100,
        ] + $now);
        $this->insertRow('rst_products', [
            'id' => 1, 'company_id' => 1, 'name' => 'Mineral Water', 'status' => 1,
            'stock_limit' => 5, 'category_id' => 1, 'unit_id' => 1, 'unit_cost' => 20,
            'sale_price' => 30, 'opening_quantity' => 10, 'available_quantity' => 10, 'is_bar' => 0,
        ] + $now);

        // --- vouchers / transfers ------------------------------------------
        $this->insertRow('fund_transfers', [
            'id' => 1, 'invoice_no' => 'FT-2026-000001', 'from_account_id' => 55,
            'to_account_id' => 26, 'amount' => 100, 'date' => '2026-10-01',
            'description' => 'Cash to bank', 'is_approved' => 0, 'company_id' => 1,
        ] + $now);

        // --- purchasing -----------------------------------------------------
        $this->insertRow('purchases', [
            'id' => 1, 'company_id' => 1, 'purchase_date' => '2026-10-01',
            'purchase_reference' => 'PR-1', 'is_approved' => 0, 'total' => 1000,
        ] + $now);
        $this->insertRow('goods_requisitions', [
            'id' => 1, 'goods_requisition_date' => '2026-10-01', 'goods_requisition_reference' => 'GR-1',
            'total_quantity' => 2, 'is_approved' => 0, 'company_id' => 1,
        ] + $now);

        // --- money (adapters already have services from #25/#62) ------------
        $this->insertRow('acc_payments', [
            'id' => 1, 'supplier_id' => 1, 'date' => '2026-10-01', 'invoice_no' => 'PV-2026-000001',
            'amount' => 250, 'company_id' => 1,
        ] + $now);
        $this->insertRow('acc_collections', [
            'id' => 1, 'customer_id' => 1, 'date' => '2026-10-01', 'invoice_no' => 'CV-2026-000001',
            'amount' => 250, 'company_id' => 1,
        ] + $now);

        // --- trading --------------------------------------------------------
        $this->insertRow('rst_sales', [
            'id' => 1, 'company_id' => 1, 'invoice_no' => 'RS-2026-000001', 'date' => '2026-10-01',
            'guest_name' => 'Walk in', 'subtotal' => 30, 'payable_amount' => 30,
            'paid_amount' => 30, 'due_amount' => 0, 'is_bar' => 0, 'payment_status' => 1,
        ] + $now);
        $this->insertRow('hotel_service_sales', [
            'id' => 1, 'company_id' => 1, 'guest_name' => 'Walk in', 'invoice_no' => 'HSS-2026-000001',
            'invoice_date' => '2026-10-01', 'subtotal' => 100, 'payable_amount' => 100,
            'discount' => 0, 'paid_amount' => 100,
        ] + $now);
    }

    protected function seedRuntimeConfig(): void
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

    /**
     * The module directory -> the name of its row in `modules`. Kept as a method, not a
     * class constant: trait constants need PHP 8.2 and this app runs on 8.1.
     */
    protected function moduleRows(): array
    {
        return [
            'GeneralStore'  => 'General Store',
            'Account'       => 'Account & Finance',
            'PosErp'        => 'POS',
            'Production'    => 'Production',
            'HotelService'  => 'HotelService',
            'BanquetHall'   => 'BanquetHall',
            'Hotel'         => 'Hotel',
            'HotelWebsite'  => 'HotelWebsite',
            'Restaurant'    => 'Restaurant',
            'Bar'           => 'Bar',
            'Hospital'      => 'Hospital',
            'Pharmacy'      => 'Pharmacy',
            'CRM'           => 'CRM',
        ];
    }

    /** Every module route file the application would register: what production actually serves. */
    protected function loadAllModuleRoutes(): void
    {
        $files = glob(base_path('module/*/routes/*.php'));

        // module/Hotel/routes/web_hotel_ajax.php is never mounted by the provider:
        // web_hotel.php pulls it in with `require_once` from INSIDE
        // `prefix => 'hotel'`, so all of its routes carry that prefix.
        //
        // require_once is process-wide, so only the FIRST route load in a phpunit
        // process registers them - every later test silently loses the whole file
        // (which is what produced 11 unexpected crawl failures: the views call
        // route('update-room-status'), route('get-booking-member-details'), ...).
        // Load it explicitly under the same prefix instead of globbing it, so
        // every test sees the routes, with the prefix, and only once.
        $nested = [
            'web_hotel_ajax.php' => 'hotel',
        ];

        foreach ($files as $file) {
            if (isset($nested[basename($file)])) {
                continue; // loaded below, inside its parent's prefix
            }
            $module = basename(dirname(dirname($file)));
            $row = isset($this->moduleRows()[$module]) ? $this->moduleRows()[$module] : null;
            $registered = $row === null
                ? null
                : \Illuminate\Support\Facades\DB::table('modules')->where('name', $row)->first();

            if ($row !== null) {
                if (!$registered) {
                    // no module row at all -> RouteServiceProvider skips the file
                    continue;
                }
                if ($module === 'BanquetHall' && (int) $registered->status !== 1) {
                    // the one module that is gated on being active (docs/BUGS.md #72)
                    continue;
                }
            }

            try {
                \Illuminate\Support\Facades\Route::group(['middleware' => ['web', 'auth']], function () use ($file) {
                    \Illuminate\Support\Facades\Route::namespace('\\')->group($file);
                });
            } catch (\Throwable $e) {
                @file_put_contents('/tmp/page-crawl-route-load-errors.txt', ($e->getMessage() . ' in ' . $file . "\n"), FILE_APPEND);
            }
        }
        foreach ($nested as $basename => $prefix) {
            foreach (glob(base_path('module/*/routes/' . $basename)) as $file) {
                \Illuminate\Support\Facades\Route::group(
                    ['middleware' => ['web', 'auth'], 'prefix' => $prefix],
                    function () use ($file) {
                        \Illuminate\Support\Facades\Route::namespace('\\')->group($file);
                    }
                );
            }
        }

        app('router')->getRoutes()->refreshNameLookups();
    }

    /** Parses one dump table into column-keyed rows (dump values are plain literals). */
    protected function dumpRows(string $dump, string $table): array
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


    protected function dumpIds(string $dump, string $table, int $index = 0): array
    {
        return array_map(fn ($row) => (int) array_values($row)[$index], $this->dumpRows($dump, $table));
    }

    /** @return array<int, array{0: array, 1: array}> [columns, raw tuples] per INSERT statement */
    protected function dumpStatements(string $dump, string $table): array
    {
        $statements = [];
        preg_match_all('/INSERT INTO `' . $table . '` \(([^)]*)\) VALUES\s*(.*?);\s*(?:\n|$)/s', $dump, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $columns = array_map(fn ($c) => trim($c, ' `'), explode(',', $match[1]));
            $statements[] = [$columns, $this->splitTuples($match[2])];
        }
        return $statements;
    }

    /**
     * Loads the shipped dump's rows so the crawl runs against production-shaped
     * data (empty tables turn every "Undefined array key 1" into a false
     * positive). The dump writes one tuple per line ending in ")," / ");", so
     * statements can be accumulated line-wise without a full SQL parser.
     */
    protected function loadShippedDumpData(): void
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


    protected function runDumpInsert(string $statement): int
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
    protected function splitTuples(string $values): array
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
    protected function splitValues(string $body): array
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


    protected function castValue(string $raw)
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
}
