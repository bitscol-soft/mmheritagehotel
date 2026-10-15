# Laravel 8 → 11 upgrade runbook (BUGS #31)

**Status:** step 0 (repo prep) landed on this branch; steps 1–5 need a machine with
PHP 8.2+, Composer and Packagist access (this sandbox has neither — `vendor/` is a
**tracked** tree and CI installs from `composer.lock`).

Current state: `laravel/framework ^8.75`, `php ^7.3|^8.0`, runtime PHP 8.1.
Laravel 8 and PHP 8.1 are both EOL. The goal is Laravel 11 on PHP 8.2/8.3.

## Why this is a runbook and not a blind bump

- `composer.lock` + the committed `vendor/` tree are the deploy artifact
  (`composer install` runs in `.github/workflows/frontend-fixtures.yml`; the deploy
  workflows ship the working tree). A `composer.json` change **without** a matching
  lock + vendor refresh changes nothing at runtime and would desync the three.
- Laravel majors are sequential: 8 → 9 → 10 → 11. Each step has its own deprecation
  surface; jumping straight to 11 hides which step broke what.

## Blocker / package inventory

| Package (current) | Problem | Target |
|---|---|---|
| `laravel/framework ^8.75` | EOL | `^11.0` (via `^9.0` → `^10.0`) |
| `php ^7.3\|^8.0` | EOL floor | `^8.2` (L11 floor) |
| `laravel/sanctum ^2.11` | L11 needs v3 | `^3.3` |
| `laravel/tinker ^2.5` | old minor | `^2.9` |
| `laravel/ui ^3.4` | L11 needs v4 | `^4.5` |
| `fruitcake/laravel-cors ^2.0` | **abandoned**; CORS is in the framework since L9 | **drop** (config `cors.php` moves to framework middleware) |
| `facade/ignition` (dev) | abandoned for L9+ | **`spatie/laravel-ignition ^2.0`** |
| `nunomaduro/collision` (dev) | L11 needs v8 | `^8.1` (via `^6` → `^7` → `^8`) |
| `phpunit/phpunit` (dev) | L11 needs 10/11 | `^10.5` (via `^9.5` → `^10.5`) |
| `beyondcode/laravel-dump-server` (dev) | stale | keep at first, drop if it blocks |
| `barryvdh/laravel-debugbar` (dev) | old majors block L11 | latest `^3.10` |
| `carlos-meneses/laravel-mpdf ^2.1` | check L11 support | latest; swap wrapper only if abandoned |
| `rajibbinalam/laravel-slug-generator ^0.2` | unknown L11 support | audit at step 2; replace with `Str::`-based slug if dead |
| `skycoder/laravel-filesaver 2.x-dev` | dev-pinned | audit at step 2; vendored fallback is already in-tree |
| `laravel/sail`, `fakerphp/faker`, `mockery/mockery` (dev) | minor | bump with the step |

## Step 0 — repo prep (DONE on this branch)

- [x] **Removed Laravel-9-removed global helpers** (they fatal on L9+):
  `str_plural` → `Str::plural`, `snake_case` → `Str::snake`, `str_limit` → `Str::limit`
  (`app/Console/Commands/ModelWithCompanyTrait.php`, `app/Exports/HrmExportData.php`).
- [x] **`TrustProxies` already extends `Illuminate\Http\Middleware\TrustProxies`**
  (no `fideloper/proxy` dependency — one of the classic 8→9 blockers is absent).
- [x] **`$dates` model properties:** none in the tree (nothing to migrate to `$casts`).
- [x] **`tools/laravel11-check.sh`** — CI guard that keeps the L9-removed helper
  classes at zero and reports the remaining step-gated items. Runs as a step of
  the `Tailwind UI checks` workflow (every push / PR).

Still fine as-is (checked): `Str::`/`Arr::` usage is already facade-based in the
touched surfaces; `optional()`, `tap()`, `retry()`, `rescue()` helpers survive to L11.

## Step 1 — Laravel 8 → 9 (PHP ≥ 8.0)

1. `composer.json`:
   - `"php": "^8.0.2"`
   - `"laravel/framework": "^9.0"`, `"laravel/ui": "^4.0"`, `"laravel/sanctum": "^2.14"` (stays v2 on L9)
   - dev: `"facade/ignition"` → `"spatie/laravel-ignition": "^1.0"`, `"nunomaduro/collision": "^6.1"`, `"phpunit/phpunit": "^9.5"`
   - **remove** `fruitcake/laravel-cors` (framework CORS middleware takes over; keep
     `config/cors.php`, it is read by the framework in L9+).
2. App-code work at this step:
   - `app/Http/Kernel.php`: `protected $routeMiddleware` → `protected $middlewareAliases`
     (L8 reads `$routeMiddleware` only — do **not** rename before this bump).
   - `app/Exceptions/Handler.php`: `$dontReport`/`$dontFlash` property shapes still work;
     verify the `register()` closure style compiles on 9.
   - `str_*`/`array_*` helpers: none left after step 0.
3. `composer update` (full, this is a major), run the suite set in step 5.

## Step 2 — Laravel 9 → 10 (PHP ≥ 8.1)

1. `"laravel/framework": "^10.0"`, `"laravel/sanctum": "^3.2"`, `"laravel/ui": "^4.2"`,
   dev: `"nunomaduro/collision": "^7.0"`, `"phpunit/phpunit": "^10.0"`,
   `"spatie/laravel-ignition": "^2.0"`.
2. App-code work:
   - `Route::controller(...)` / missing closure return types: deprecations only.
   - `database/migrations` `Schema::` legacy signatures still fine.
   - Audit `rajibbinalam/laravel-slug-generator` + `skycoder/laravel-filesaver` for
     L10 support; replace if dead (the filesaver vendored copy covers the emergency).
3. `composer update`, step 5.

## Step 3 — Laravel 10 → 11 (PHP ≥ 8.2)

1. `"laravel/framework": "^11.0"`, `"php": "^8.2"`, `"laravel/sanctum": "^3.3"`,
   `"laravel/tinker": "^2.9"`, `"laravel/ui": "^4.5"`, `"laravel/sail": "^1.26"`,
   dev: `"nunomaduro/collision": "^8.1"`, `"phpunit/phpunit": "^10.5|^11.0"`,
   `"barryvdh/laravel-debugbar": "^3.10"`.
2. App-code work:
   - Slim-skeleton shape is optional (the L8-era `Kernel`/`Handler`/`RouteServiceProvider`
     still boot on L11 with deprecation notices); migrate when touched:
     middleware registration → `bootstrap/app.php` ApplicationBuilder style.
   - `Carbon 3` comes with L11: audit `diffIn*()` signedness + `->format()` locale
     strings (the accounting service date math must be re-checked here).
   - `Model::unguarded()`/mass-assignment defaults unchanged; verify `Auth::routes()`
     (from `laravel/ui`) still registers — keep `laravel/ui` v4 for that.
3. `composer update`, step 5.

## Step 4 — lock + vendor refresh (mandatory commit)

On the networked machine, after the chosen step's `composer update`:

```
composer install --no-dev --optimize-autoloader   # sanity from the fresh lock
```

Commit **`composer.json` + `composer.lock` + `vendor/`** together (this repo deploys
the tracked vendor tree; the CI `frontend-fixtures` workflow also runs
`composer install` and will fail if the lock is stale).

## Step 5 — verification checklist (every step)

1. `php artisan route:list` boots and the route-count is stable (route names frozen).
2. `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
3. `vendor/bin/phpunit` (once a test suite exists — until then the suites below are
   the net).
4. `bash tools/laravel11-check.sh` (guard green).
5. `node tools/ui-check.cjs && bash tools/ui-guard.sh && php tools/ui-blade-check.php`
   (the preservation suites must stay green — no view/JS/route churn may appear in
   an upgrade step).
6. Deploy to staging and walk the smoke list from `docs/PLAN-TESTING.md` appendix B.

## Guard

`tools/laravel11-check.sh` fails when a Laravel-9-removed global helper is
reintroduced (step 0's class of bug) and prints the remaining step-gated items
(`Kernel::$routeMiddleware`, `fruitcake/laravel-cors`, `facade/ignition`) so the
bump steps have a mechanical checklist. It is a step of the `Tailwind UI checks`
workflow.
