<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tests\Concerns\BuildsShippedSchema;
use Tests\Concerns\CreatesAccSchema;

/**
 * The crawl's blind spot, closed.
 *
 * PageCrawlAuditTest deliberately skips every URI containing a placeholder, so
 * no `/{id}` screen is ever exercised: show/edit pages, detail partials, the lot.
 * That is not a small gap - the first CI run of the voucher work (#49) rendered
 * a receive-voucher edit screen whose controller handed the view `company`
 * while the view read `$companies`, and nothing here could have seen it.
 *
 * This test walks the parameterised GET routes, substitutes a row id that really
 * exists in the shipped dump, and asserts the screen answers instead of throwing.
 * A 404 is fine (that route wants a different kind of id); a 302 is fine (auth
 * or redirect). A 500 is the bug.
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

        \Illuminate\Support\Facades\Cache::flush();
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

        foreach (app('router')->getRoutes() as $route) {
            if (!in_array('GET', $route->methods(), true)) {
                continue;
            }

            $uri = $route->uri();
            if (!str_contains($uri, '{')) {
                continue;
            }
            if (preg_match(self::SKIP, $uri)) {
                continue;
            }
            // only single-placeholder routes: a two-placeholder URI needs a real
            // parent/child id pair, which this sweep cannot guess
            if (preg_match_all('/\{[^}]+\}/', $uri) !== 1) {
                continue;
            }

            $tested++;
            $concrete = preg_replace('/\{[^}]+\}/', (string) self::CANDIDATE_ID, $uri);

            try {
                $status = $this->get('/' . ltrim($concrete, '/'))->getStatusCode();
            } catch (\Throwable $e) {
                // redirects, validation and auth surface as framework exceptions
                if (str_contains(get_class($e), 'HttpException')
                    || str_contains(get_class($e), 'NotFoundException')
                    || str_contains(get_class($e), 'ValidationException')
                    || str_contains(get_class($e), 'AuthorizationException')) {
                    continue;
                }
                $failures[$uri] = $this->shortException($e);
                continue;
            }

            if ($status >= 500) {
                $failures[$uri] = 'HTTP' . $status;
            }
        }

        $this->assertGreaterThan(0, $tested, 'no parameterised routes were found - the sweep is not wired up');

        // A CI annotation keeps only the FIRST line of a failure, so neither the
        // test name nor a diff can carry 40 diagnoses. Workflow commands written
        // to stdout become individual annotations instead - one per route, each
        // with its exception in full - and the annotations API returns them all.
        foreach ($failures as $uri => $reason) {
            fwrite(STDOUT, '::warning title=parameterised-crawl::' . $uri . ' :: ' . $reason . "\n");
        }

        $this->assertSame([], array_keys($failures));
    }

    private function shortException(\Throwable $e): string
    {
        $message = preg_replace('/\s+/', ' ', $e->getMessage());

        return class_basename($e) . '@' . basename($e->getFile()) . ':' . $e->getLine()
            . ' ' . mb_substr($message, 0, 200);
    }
}
