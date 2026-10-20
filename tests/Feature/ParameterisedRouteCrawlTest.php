<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tests\Concerns\BuildsShippedSchema;
use Tests\Concerns\CreatesAccSchema;
use Illuminate\Support\Facades\DB;

/**
 * The crawl's blind spot, closed.
 *
 * PageCrawlAuditTest deliberately skips every URI containing a placeholder, so
 * no `/{id}` screen is ever exercised: show/edit pages, detail partials, the lot.
 * That is not a small gap - the first CI run of the voucher work (#49) rendered a
 * receive-voucher edit screen whose controller handed the view `company` while
 * the view read `$companies`, and nothing here could have seen it.
 *
 * The hard part is picking an id. The shipped dump carries master data but no
 * business rows at all (no sales, items, products or bookings), so a fixed
 * `id = 1` mostly measures null-dereferences in views that were handed a
 * non-existent row - 49 of them on the first run, every one an artifact of the
 * fixture rather than a defect. So the sweep resolves the table the route
 * actually binds - from the controller method's model type-hint when there is
 * one, otherwise from the parameter/URI name - looks up an id that EXISTS, and
 * skips the route when that table is empty. What is left is the screens a
 * master-data row can really open, which is exactly where #49's bug lived.
 *
 * A 404 is fine (the route wants a different kind of id); a 302 is fine (auth or
 * redirect). A 500 is the bug.
 */
class ParameterisedRouteCrawlTest extends TestCase
{
    use CreatesAccSchema;
    use BuildsShippedSchema;

    /** Routes that mutate state or shell out, even behind GET. */
    private const SKIP = '/export|download|backup|pdf|print|stream|update-debug|update-data|add-user|delete-all|toggle|sync-|logout|night-audit-transaction|update-transaction-ledger|remove_booking|assign|store|approve|destroy|delete/i';

    protected function setUp(): void
    {
        parent::setUp();
        $this->buildSchemaFromShippedDump();
        $this->seedRuntimeConfig();
        $this->loadAllModuleRoutes();

        // The runtime seed and the dump both claim the same ids (users 1-2,
        // modules ...), so a plain load would reject the dump's rows as duplicate
        // keys and leave half the fixture behind - which is how the first green
        // run produced a submodule whose `module` relation was null. Drop the
        // seeded rows first, exactly as the page crawl does before its
        // production-data pass.
        \Illuminate\Support\Facades\Cache::flush();
        foreach (['users', 'groups', 'companies', 'company_user', 'modules', 'system_settings', 'invoice_generate'] as $table) {
            DB::table($table)->delete();
        }
        $this->loadShippedDumpData();

        if (!User::find(1)) {
            $this->insertRow('users', ['id' => 1, 'name' => 'System Admin', 'email' => 'sys@mm.test',
                'password' => bcrypt('x'), 'company_id' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->actingAs(User::find(1));
    }

    /**
     * The id to try for a placeholder. The shipped dump's ids start at 1, so a
     * placeholder bound to a master table (`{receive}`, `{payment}`) usually
     * resolves. When it does not, Laravel 404s - and a 404 is accepted: the point
     * of the sweep is that screens which CAN resolve do not explode.
     */
    private const CANDIDATE_ID = 1;

    public function test_parameterised_get_routes_answer_instead_of_throwing(): void
    {
        $failures = [];
        $tested = 0;
        $skipped = 0;

        foreach (app('router')->getRoutes() as $route) {
            if (!in_array('GET', $route->methods(), true)) {
                continue;
            }

            $uri = $route->uri();
            if (!str_contains($uri, '{') || preg_match(self::SKIP, $uri)) {
                continue;
            }
            // Single placeholder only: a two-placeholder URI needs a parent/child
            // pair, which this sweep cannot guess from the schema alone.
            if (preg_match_all('/\{[^}]+\}/', $uri) !== 1) {
                continue;
            }

            preg_match('/\{([^}]+)\}/', $uri, $m);
            $id = $this->existingIdFor($route, $m[1], $uri);

            if ($id === null) {
                $skipped++;
                continue;
            }

            $tested++;
            $concrete = preg_replace('/\{[^}]+\}/', (string) $id, $uri);

            try {
                $response = $this->withoutExceptionHandling()->get('/' . ltrim($concrete, '/'));
                $status = $response->getStatusCode();
            } catch (\Throwable $e) {
                if ($this->isBenign($e)) {
                    continue;
                }
                $failures[$uri] = $this->shortException($e);
                continue;
            }

            if ($status >= 500) {
                $failures[$uri] = 'HTTP' . $status;
            }
        }

        // A CI annotation keeps only the FIRST line of a failure, and this
        // workflow pipes phpunit into a log file before printing it, so a diff or
        // a test name cannot carry the diagnosis. Print one greppable line per
        // failing route instead; ui-check.yml lifts those into the annotation.
        fwrite(STDOUT, sprintf("PARAMCRAWL routes=%d skipped_no_data=%d failures=%d\n",
            $tested, $skipped, count($failures)));
        foreach ($failures as $uri => $reason) {
            fwrite(STDOUT, 'PARAMCRAWL ' . $uri . ' :: ' . $reason . "\n");
        }

        $this->assertGreaterThan(0, $tested, 'no parameterised route resolved to a real row - the sweep is not wired up');
        $this->assertSame([], $failures);
    }

    /**
     * An id that exists for whatever this route binds, or null when the fixture
     * has no rows to show.
     */
    private function existingIdFor($route, string $parameter, string $uri): ?int
    {
        foreach ($this->candidateTables($route, $parameter, $uri) as $table) {
            if (!\Illuminate\Support\Facades\Schema::hasTable($table)) {
                continue;
            }
            $id = \Illuminate\Support\Facades\DB::table($table)->min('id');
            if ($id !== null) {
                return (int) $id;
            }
        }

        return null;
    }

    /** @return string[] most specific first */
    private function candidateTables($route, string $parameter, string $uri): array
    {
        $tables = [];

        // 1. a model the controller type-hints (implicit binding)
        $controller = $route->getAction('controller');
        if (is_string($controller) && str_contains($controller, '@')) {
            [$class, $method] = explode('@', $controller);
            try {
                foreach ((new \ReflectionMethod($class, $method))->getParameters() as $param) {
                    $type = $param->getType();
                    $name = $type && !$type->isBuiltin() ? $type->getName() : null;
                    if ($name && is_subclass_of($name, \Illuminate\Database\Eloquent\Model::class)) {
                        $tables[] = (new $name)->getTable();
                    }
                }
            } catch (\Throwable $e) {
                // unmountable controller/method: fall through to the heuristics
            }
        }

        // 2. the parameter name, singular or plural (sale -> sales)
        $base = \Illuminate\Support\Str::snake($parameter);
        $tables[] = $base;
        $tables[] = \Illuminate\Support\Str::plural($base);

        // 3. the nearest path segment ({id} says nothing, "rooms/{id}" does)
        $segments = explode('/', $uri);
        foreach ($segments as $index => $segment) {
            if (str_contains($segment, '{') && $index > 0) {
                $tables[] = $segments[$index - 1];
                break;
            }
        }

        // 4. module-prefixed spellings (rooms -> rst_rooms, hotel_rooms)
        $extra = [];
        foreach ($tables as $table) {
            $extra[] = 'rst_' . $table;
            $extra[] = 'hotel_' . $table;
            $extra[] = 'acc_' . $table;
        }

        return array_values(array_unique(array_merge($tables, $extra)));
    }

    private function isBenign(\Throwable $e): bool
    {
        foreach (['HttpException', 'NotFoundException', 'ValidationException', 'AuthorizationException'] as $class) {
            if (str_contains(get_class($e), $class)) {
                return true;
            }
        }

        return false;
    }

    private function shortException(\Throwable $e): string
    {
        $message = preg_replace('/\s+/', ' ', $e->getMessage());

        return class_basename($e) . '@' . basename($e->getFile()) . ':' . $e->getLine()
            . ' ' . mb_substr($message, 0, 200);
    }
}
