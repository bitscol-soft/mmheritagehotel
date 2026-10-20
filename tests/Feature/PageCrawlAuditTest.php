<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Tests\Concerns\BuildsShippedSchema;
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
    use BuildsShippedSchema;

    public function test_crawl_every_get_route_and_report_failures(): void
    {
        $this->buildSchemaFromShippedDump();
        $this->seedRuntimeConfig();
        $this->loadAllModuleRoutes();

        // system admin: bypasses every hasPermission()/hasAccess() gate so the
        // crawl measures what the code does, not who may look at it
        $this->actingAs(User::find(1));

        // pass 1: no business data at all -> what a fresh/partial deployment hits
        $this->assertNoUnexpectedCrawlFailures($this->crawlRoutes('/tmp/page-crawl-empty.txt', 'empty'), 'empty business data');

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
        $this->assertNoUnexpectedCrawlFailures($this->crawlRoutes('/tmp/page-crawl.txt', 'production'), 'production-shaped data');
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



    /** @return array<int, array{0: int, 1: int, 2: string, 3: string, 4: string}> */
    private function crawlRoutes(string $outFile, string $pass = ''): array
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

        // And one compact line per pass: CI turns these into the check annotation, and the
        // detailed report above is far too long for that channel (it gets truncated mid-list).
        fwrite(STDOUT, sprintf("crawl | pass=%s routes=%d failures=%d buckets=%s\n",
            $pass, count($routes), count($results), json_encode($counts)));

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





    private function normalise(\Throwable $e): string
    {
        $message = $e->getMessage();
        $message = preg_replace('/\s+/', ' ', $message);
        return trim($message);
    }
}
