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
}
