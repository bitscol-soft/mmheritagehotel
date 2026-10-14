# Laravel 11 Upgrade Runbook

Prepared 2026-10-15 · Branch `arena/35ad811a-mmheritagehotel` · Companion to
`docs/PLAN-MODERNIZATION.md` Phase 1 (which is still **plan-only** — `main` runs
Laravel 8.75 / PHP 7.3–8.0 / Laravel Mix).

---

## 0. Status summary

| Item | State |
|---|---|
| UI redesign (PR #15: `x-mm` components, Tailwind layer, 360+ screens) | ✅ merged to `main` |
| L11-prep cleanup (this branch) | ✅ done — see §1 |
| Framework upgrade 8 → 9 → 10 → 11 | ⬜ **not started** — follow §2–§5 below |
| Vite migration (replace Laravel Mix) | ⬜ optional, §7 |
| PR #16 (CRM model refactor) | ⚠️ open, unrelated — review separately |

## 1. L11-prep cleanup already applied (this branch)

All changes are syntax-verified (`php -l` pass) and keep behavior identical on
Laravel 8, so they are safe to merge before the framework bumps:

1. **Removed laravelcollective/html usages** — 12 Blade views (`Form::select` /
   `Form::number`) rewritten to plain HTML with the same names, ids, classes,
   old-input handling and placeholder options Collective produced.
2. **Removed `unique_with` validation** (6 rules in 5 controllers) → native
   `Rule::unique()->where()->ignore()`. `felixkiss/uniquewith-validator` dropped.
3. **String route actions converted** to `[Controller::class, 'method']` and
   `Route::resource('x', 'XController')` → `XController::class` in
   `module/GeneralStore/routes/web_generalstore.php` and
   `module/Permission/routes/web_permission.php` (55 declarations), with proper
   `use` imports (namespace-group resolution no longer needed).
4. **`$dates` → `$casts`** in both `HotelServiceSaleItem` models (removed in L11).
5. **Deleted unused Livewire 2 leftovers** (`App\Http\Livewire\SalesRightSide`,
   `resources/views/livewire/`) — the component was never mounted.
6. **composer.json/lock** — dropped `doctrine/dbal` (no `->change()` migrations
   exist), `felixkiss/uniquewith-validator`, `laravelcollective/html`,
   `livewire/livewire`. `composer.lock` entries removed and `content-hash`
   recomputed (verified against Composer's algorithm before rewriting).

Re-run the transformation logic (for reference): `python3 tools/l11-prep-cleanup.py`
(one-shot; it will refuse patterns it no longer finds).

### Known pre-existing bugs (NOT touched — out of scope)

8 Blade templates call `action('…Controller@method')` for controllers that
**do not exist and have no routes** (`ServiceSalesDueReceiveController`,
`InventoryPurchasePaymentController`, `InventorySalePaymentController` in
BanquetHall / HotelService / Bar / Restaurant). Those pages already throw
`Action … not defined` today (see `docs/BUGS.md`). Implement or remove them in a
separate bugfix; when implemented, register routes with `::class` syntax.

## 2. Prerequisites

- **PHP 8.2+** (recommend 8.3) on dev machines and the build box
- **Composer 2.7+**, Node 18+ (only needed for the Tailwind `ui:build` layer)
- A green smoke baseline **before** touching framework constraints:
  `php artisan route:list` works, login + guest list + booking create + one
  report render pass manually or via Playwright (`npm run ui:browser`).
- Work on a dedicated branch per major bump (§2.1) so each step is bisectable.

> **Important:** `composer update` must run on a machine with Packagist access.
> Each step below ends with: `composer install` sanity, `php artisan config:clear
> route:clear view:clear`, full smoke, then commit **both** `composer.json` and
> `composer.lock`.

## 3. Step A — Laravel 8 → 9 (PHP 8.1+)

```sh
composer require laravel/framework:^9.52 laravel/ui:^4.5 laravel/sanctum:^3.3 \
  laravel/tinker:^2.8 nunomaduro/collision:^6.4 --with-all-dependencies
composer require facade/ignition spatie/laravel-ignition:^2.0 --dev --with-all-dependencies
```

Framework-side changes to apply:

- **CORS** — `fruitcake/laravel-cors` is merged into L9. In `app/Http/Kernel.php`
  replace `\Fruitcake\Cors\HandleCors::class` with
  `\Illuminate\Http\Middleware\HandleCors::class`, then
  `composer remove fruitcake/laravel-cors` (keep `config/cors.php` — compatible).
- **Swift Mailer → Symfony Mailer**: re-test every mail flow (password reset
  `PasswordResetMailable`, `mail_templates`, `CrmSendMail`). Update
  `config/mail.php` if custom transports are used.
- **Flysystem 3**: custom disks (`nao-pon/flysystem-google-drive` — verify a
  Flysystem-3-compatible release or replace with a maintained Google Drive
  adapter) and `Storage::` calls using legacy APIs (`putFileAs` fine;
  `put($path, $resource)` stream handling changed).
- Move `app/Http/Middleware` untouched (fine), but re-test `TrustProxies`
  (Symfony HttpFoundation 6 signatures).
- Validation: re-test all `unique:` rules with the new `DatabaseRule::where()`.

## 4. Step B — Laravel 9 → 10 (PHP 8.1+)

```sh
composer require laravel/framework:^10.48 laravel/ui:^4.5 laravel/sanctum:^3.3 \
  nunomaduro/collision:^7.0 phpunit/phpunit:^10.5 --with-all-dependencies --dev
```

- Update `phpunit.xml` to the PHPUnit 10 schema (`<coverage/>` element changed).
- `laravel-mpdf` / `maatwebsite/excel` / `intervention/image` (^2.7 is fine) —
  bump to latest minors with L10 support (all three have them).
- Re-verify the whole `module/Account` report/PDF suite (mPDF) and Excel
  import/export (Bar/Restaurant/Hotel `Imports`/`Exports`).

## 5. Step C — Laravel 10 → 11 (PHP 8.2+)

```sh
composer require php:^8.2 laravel/framework:^11.0 laravel/ui:^4.6 laravel/sanctum:^4.0 \
  laravel/tinker:^2.9 nunomaduro/collision:^8.1 --with-all-dependencies
composer require phpunit/phpunit:^11.0 --dev --with-all-dependencies
```

- **Keep the existing `app/Http/Kernel.php` / `app/Console/Kernel.php` /
  `app/Exceptions/Handler.php`** — the slim L11 skeleton is optional for
  upgraded apps. Adopting `bootstrap/app.php` style can be a later refactor.
- `$routeMiddleware` in `Kernel` still works; optionally rename to
  `$middlewareAliases`.
- Carbon: L11 allows Carbon 2 **or** 3 — stay on Carbon 2 (`nesbot/carbon`
  current constraint) for now; Carbon 3 has minor diff/edge changes.
- `skycoder/invoice-number-generator`, `skycoder/laravel-filesaver`,
  `skycoder/query-shorter` (`*-dev`, used in 5 controllers: BookingNote,
  Guest, GuestRegistrationTerms, HotelGallery, OurService): verify they install
  on L11; if not, pin a fork or inline them (each is small).
- `rajibbinalam/laravel-slug-generator` — verify L11 support; else swap to
  `spatie/laravel-sluggable` (`config/SlugGenerator.php` + `app/Traits`).
- `milon/barcode`, `simple-qrcode`, `staudenmeir/belongs-to-through`,
  `spatie/db-dumper`, `doctrine` leftovers — bump to latest (all L11-ready).
- Re-run the full smoke list (§6) and the UI gates (§7).

## 6. Verification checklist (every step)

```sh
php artisan route:list | wc -l          # ~same count as before the bump
php artisan config:clear && php artisan route:clear && php artisan view:clear
./vendor/bin/phpunit                    # add smoke feature tests first if missing!
php tools/ui-blade-check.php
npm ci && npm run ui:build && npm run ui:check
npm run ui:browser                      # Playwright e2e
php artisan mm:render-public-fixtures --check
```

Manual smoke (staging data): login · front-desk dashboard · guest list filters ·
create booking (3-step wizard) · assign room · folio payment · checkout ·
night audit preview · restaurant POS sale · one Account ledger + PDF ·
one Excel export · website homepage + booking cart.

## 7. After Laravel 11 (parallel track)

- **Mix → Vite**: the Tailwind layer already builds through its own CLI
  (`ui:build`), so Laravel Mix can be replaced lazily via `laravel-vite-plugin`.
- Move the remaining `public/assets` jQuery plugins into npm or freeze as-is.
- Optional: slim skeleton migration (`bootstrap/app.php`), Pest, PHPStan/Larastan.
- Replace mPDF with Browsershot/Chrome print when the A4 layout suite is
  regenerated (per PLAN-MODERNIZATION 1.1).

## 8. Rollback

Each step is its own commit/PR containing `composer.json` + `composer.lock` +
code diffs. Rollback = `git revert` of that PR and `composer install`. Do not
run `php artisan migrate` during 8→11 (no schema changes); a DB snapshot is
still recommended before smoke testing step A.
