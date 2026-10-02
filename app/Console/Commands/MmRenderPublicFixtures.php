<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

/**
 * Render the migrated public-site views (frontend.*) against the seeded database and write the
 * resulting HTML into tools/fixtures/frontend/*.html. This is the live-Laravel equivalent of
 * MM_WRITE_FIXTURE=1 in tools/ui-blade-check.php for the public-site views — the standalone
 * harness cannot reach these views because they depend on DB-backed helpers (`pages()`,
 * `getBanner()`, `websiteInfo()`).
 *
 * Usage:
 *   php artisan mm:render-public-fixtures [--check] [--dry-run]
 *
 *   --check   : if the rendered HTML differs from the on-disk fixture, exit with code 1. The CI
 *               workflow uses --check to surface a "regenerate fixtures" failure; the dev runs
 *               without --check to actually update tools/fixtures/frontend/*.html and commit.
 *   --dry-run : render the views against the seeded DB and print a summary (byte counts, view
 *               name, URL) without writing any files. Useful for sanity-checking the setup before
 *               committing fixture changes.
 *
 * This command is intentionally idempotent — it does not mutate the database.
 */
class MmRenderPublicFixtures extends Command
{
    protected $signature = 'mm:render-public-fixtures {--check : Exit non-zero if fixtures are stale} {--dry-run : Print a summary of what would be rendered without writing fixture files}';

    protected $description = 'Render the migrated public-site views and write tools/fixtures/frontend/*.html';

    // The fixtures must be byte-stable so the stale-fixture check is meaningful. We freeze time the
    // same way the standalone harness does, so `date('Y')` and similar calls return a known value.
    private const FROZEN_NOW = '2026-10-01 09:30:00';
    private const FROZEN_TZ = 'Asia/Dhaka';

    /** @return array<string, array{view:string, url:string, data:array}> */
    private function cases(): array
    {
        return [
            'home' => [
                'view' => 'frontend.home',
                'url' => '/',
                'data' => [],
            ],
            'room_view' => [
                'view' => 'frontend.room_view',
                'url' => '/room/sample-suite',
                'data' => [],
            ],
            'booking_cart' => [
                'view' => 'frontend.booking-cart',
                'url' => '/booking-cart',
                'data' => [],
            ],
            'search_all_room' => [
                'view' => 'frontend.search_all_room',
                'url' => '/search-all-room',
                'data' => [],
            ],
            'search_room' => [
                'view' => 'frontend.search_room',
                'url' => '/search-room',
                'data' => [],
            ],
            'guest_register' => [
                'view' => 'frontend.guest-register',
                'url' => '/guest-register',
                'data' => [],
            ],
            'booking_register' => [
                'view' => 'frontend.booking-register',
                'url' => '/booking-register',
                'data' => [],
            ],
            'terms' => [
                'view' => 'frontend.terms',
                'url' => '/terms-condition',
                'data' => [],
            ],
            'privacy_policy' => [
                'view' => 'frontend.privacy_policy',
                'url' => '/privacy-policy',
                'data' => [],
            ],
            'single_page_view' => [
                'view' => 'frontend.single-page-view',
                'url' => '/pages/sample',
                'data' => [],
            ],
        ];
    }

    public function handle(): int
    {
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse(self::FROZEN_NOW, self::FROZEN_TZ));
        date_default_timezone_set(self::FROZEN_TZ);

        // The cookie contract on /booking-cart is `Cookie::get('booking_cart')` -> JSON decode.
        // A fresh Request::create() has no cookies, so the cart view renders its "empty cart" branch
        // without us having to do anything; that matches the legacy UX for a first-time visitor.

        $root = base_path();
        $fixtureDir = $root . '/tools/fixtures/frontend';
        if (!is_dir($fixtureDir)) {
            mkdir($fixtureDir, 0755, true);
        }

        $checkOnly = (bool) $this->option('check');
        $dryRun = (bool) $this->option('dry-run');
        $stale = [];

        if ($dryRun) {
            $this->info('Dry run: rendering the 10 migrated public-site views against the seeded DB.');
            $this->info('No files will be written.');
            $this->newLine();
        }

        foreach ($this->cases() as $name => $case) {
            $req = Request::create($case['url'], 'GET');
            app()->instance('request', $req);

            try {
                $html = View::make($case['view'], array_merge([
                    'errors' => new \Illuminate\Support\ViewErrorBag(),
                ], $case['data']))->render();
            } catch (\Throwable $e) {
                $this->error(sprintf('FAIL %s: %s', $name, $e->getMessage()));
                if (!$checkOnly) {
                    return self::FAILURE;
                }
                $stale[] = $name;
                continue;
            }

            // Normalise: strip CSRF tokens and asset hostnames so the bytes are deterministic across
            // hosts. The same normalisations live in tools/ui-blade-check.php for the admin suite.
            $html = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', $html);
            $html = str_replace(['http://localhost/assets', 'http://mm-heritage-hotel.dizihotel.com/'], '/assets', $html);

            // No PHP warnings should leak into fixtures.
            if (strpos($html, '<b>Warning</b>') !== false || strpos($html, '<b>Notice</b>') !== false) {
                $this->error(sprintf('FAIL %s: PHP warning/notice in rendered HTML', $name));
                if (!$checkOnly) {
                    return self::FAILURE;
                }
                $stale[] = $name;
                continue;
            }

            $file = $fixtureDir . '/' . $name . '.html';
            $existing = is_file($file) ? file_get_contents($file) : false;

            if ($dryRun) {
                $existingByteSize = $existing === false ? 0 : strlen($existing);
                $this->line(sprintf('  %-22s view=%-32s url=%-30s bytes=%6d vs committed=%6d', $name, $case['view'], $case['url'], strlen($html), $existingByteSize));
                continue;
            }

            if ($existing === $html) {
                $this->line(sprintf('  ok    %s', $name));
                continue;
            }

            if ($checkOnly) {
                $this->warn(sprintf('STALE   %s (run without --check to regenerate)', $name));
                $stale[] = $name;
                continue;
            }

            file_put_contents($file, $html);
            $this->info(sprintf('  wrote %s', $name));
        }

        if ($dryRun) {
            $this->newLine();
            $this->info('Dry run finished. Re-run without --dry-run to actually write the fixtures.');
            return self::SUCCESS;
        }

        if ($checkOnly && $stale) {
            $this->error(sprintf('%d fixture(s) are stale: %s. Regenerate with: php artisan mm:render-public-fixtures', count($stale), implode(', ', $stale)));
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}