# Bug Register — MM Heritage Hotel ERP

Branch `arena/01a0f37d-mmheritagehotel` (PR #4). Every bug below was reproduced from the full
crawl of the live app (251 screens) and traced to a concrete cause in this repository.
**Fixed** = patched on this branch (commit `bug-fixes`); **Open** = needs a decision, deploy, or a feature
from the modernization plan.

Legend — Severity: 🔴 breaks a core flow · 🟠 breaks a secondary page/action · 🟡 cosmetic/hardening · 🔵 security.

## A. Fixed on this branch

| # | Sev | Route / Screen | Symptom | Root cause | Fix |
|---|-----|----------------|---------|------------|-----|
| 1 | 🔴 | `/hotel/booking/create`, `booking/next-step`, `booking-collection`, `/hotel/guests/create`, `guests/{id}/edit`, `booking-note/create`, `guest-registration-terms/create`, `booking-adjusts`, `remove_booking_next`, banquet/restaurant sale screens | HTTP 500 `Class "Module\CRM\Models\CRMCustomer" not found` | `module/CRM` shipped only as an empty submodule gitlink; `Guest`, `BookingController`, `AjaxController`, `BanquetBookingController`, restaurant `SaleController` and `HelperMethods` all import it | Added `module/CRM` with `CRMCustomer`, `CrmCustomer`, `CrmBillGenerate`, `MailTemplate`, `ProjectBillingService` (no-op), seeder stub — tables `c_r_m_customers` / `mail_templates` exist in the schema |
| 2 | 🔴 | Booking success / invoice PDFs | 500 when a booking's `company_id` is null or has no CRM row | `getCrmCompany()` / `getCrmCompanyAddress()` dereferenced `find()` result | `optional()` + null-id guard in `app/Helpers/HelperMethods.php` |
| 3 | 🟠 | `/setting/parent-permissions/create`, `/{id}/edit` | 500 (method missing; edit pointed at non-existent `setting.parent_permission` view) | `ParentPermissionController` lacked `create()`, wrong view namespace | Added `create()`; `edit()` now renders `parent_permission` (form is `isset($model)`-driven in the shared view) |
| 4 | 🟠 | `/setting/submodules/create`, `/{id}/edit` | same as #3 | same pattern in `SubmoduleController` | Same fix (`submodule` view) |
| 5 | 🟠 | `/setting/permission-access` | 500 `ArgumentCountError` — resource `index` mapped to `index($id)` (needs a user id) | route/controller arity mismatch | Resource `->except(['index','show','destroy'])`; plain URL now redirects to `permitted.users` grid; deep-link `permission-access/create/{id}` unchanged |
| 6 | 🟠 | `/setting/select/employee/list`, `/setting/permitted/employee/list` | 500 `Method … does not exist` (AJAX select2 sources) | routes pointed to methods `UserPermissionController` never had | Implemented `getEmployeeList` / `getPermittedEmployeeList`, select2 JSON shape, hard-guarded with `class_exists(\Module\HRM\Models\Employee::class)` → empty result when HRM absent |
| 7 | 🟠 | `/setup/account-setups` | 500 `View [setup.account-setups.index] not found` | view file never committed | Added `module/Account/views/setup/account-setups/index.blade.php` (status table) |
| 8 | 🟠 | `/setup/account-opening-balances` (+ index/show/edit routes) | 500 when resource routes hit missing methods | controller implements only `create()/store()` | Resource restricted to `->only(['create','store'])` |
| 9 | 🟠 | `/sale/acc_collections/create|edit`, `/purchase/acc-payments/create|edit` | 500: `create()` pointed at wrong namespace (`purchase.collections.create`) and none of those views exist | stub controllers | `create/edit` redirect to index with an explanatory flash (flows are recorded via the voucher module); marked TODO with real tables `acc_collections`/`acc_payments` noted for full implementation |
| 10 | 🔴 | `/reports/revenue-analysis`, `/reports/ratio-analysis`, `/reports/nominal-account-ledger`, `/reports/received-payment-statement` | 500 `Method …does not exist` — routes reference 4 controller methods that were never implemented | half-ported report suite | Implemented all 4 in `AccountReportController` (revenue analysis mirrors expense-analysis over group 4/Credit; nominal ledger = combined revenue+expense ledger with debit/credit totals; ratio analysis from period group totals; received payments from `acc_collections`) + 4 new views with date filter, print link, pagination |
| 11 | 🟠 | `/hotel/booking-search-by-date` | 500 `Undefined variable $account_types` | `getSearch()` rendered `booking/index` but omitted vars that `index()` provides | Passes `account_types` now |
| 12 | 🟠 | `/sale/acc-returnable-sale-items`, `/purchase/acc-returnable-purchase-items` | 500 `Attempt to read property "details" on null` when id missing/invalid | no guard before `->render()` of view that dereferences `$sale->details` | Guard: render `response('')` when record missing |
| 13 | 🟠 | `/gs/item-export` | 500 `Class ExportItemCSV not found` (no import, no class anywhere) | export class deleted/never committed | Added `Module\GeneralStore\Services\Export\ExportItemCSV` (FromQuery+headings over real `items` columns) + import in `ItemController` |
| 14 | 🟡 | Public room-category page (`/search-room`, `room-category/{id}`) | amenities list always empty; invalid category id → 500 | `array_push($aminities, $name[0])` (indexing an Eloquent model → null) + `if ($name != null \|\| $name != '')` always true + missing `abort` | Push the model itself; `if ($name !== null)`; `abort_if(..., 404)` |
| 15 | 🟡 | Public `/room/{url_slug}` | 500 instead of 404 on unknown slug | `viewRoom()` dereferenced possibly-null `$room` | `abort_if` 404 + null-safe explode |
| 16 | 🔵 | `GET /optimize-clear` | **Unauthenticated** cache flush (DoS-ish, breaks perf-cached deploys) | route defined outside the auth group with no middleware | `->middleware(['auth','super-admin'])` |
| 17 | 🔵 | `GET /update-debug` | toggles `APP_DEBUG` with only `super-admin` but **no `auth`** (middleware chain aborted on null user) | missing middleware | `->middleware(['auth','super-admin'])` |
| 18 | 🔵 | `/db-backup`, `/db-backup-to-drive` | any logged-in staff could dump the full DB (spatie dumper, download + Google Drive) | auth group but no role gate | `->middleware('super-admin')` |
| 19 | 🔵 | `GET /hotel/delete-all-booking-by-query` | destructive mass-delete of bookings by date range, GET-accessible to every hotel user | dev helper left in routes | `->middleware('super-admin')` (GET kept for compatibility; convert to POST when UI updated) |
| 20 | 🟡 | `/hotel/room-management/vat/create|show…`, `/account-type/create|show`, `/hotel_service/services/create|edit|show` | 500 on routes the resources auto-registered but controllers don't implement | over-broad `Route::resource`/`Route::resources` | vat → `only(['index','update'])`; account-type → `except(['create','show'])`; HotelService `services` → `only(['index','store','update','destroy'])` |
| 21 | 🟠 | `/sync-data`, `/sync-data-v2`, `/sync-data-process` (global settings) | 500 `Class Module\HRM\… not found` (HRM module absent) | unconditional HRM imports at call sites | clean `abort(404,'HRM module is not installed.')` guard |
| 22 | 🟡 | repo hygiene | no `.gitignore` (vendor, `.env`, backups, node_modules all at risk) | — | added standard Laravel `.gitignore` (tracked files untouched) |

## B. Open — needs deploy / decision / feature work

| # | Sev | Item | Notes & recommendation |
|---|-----|------|------------------------|
| 23 | 🔴 | **Live deploy is behind the repo** | `/api/dashboard` + `/api/all-users` 500 on the live site although `App\Http\Controllers\Api\ApiDashboardController` exists here; several other live 500s (`/reports/expense-analysis`, permitted-users grid) come from files present in repo but absent in the live build. **Action: deploy this branch (after review) to staging and re-verify.** |
| 24 | 🔵 | **Secrets committed** | `.env` (APP_KEY + DB credentials) and a full production SQL dump (`database/mmheritagehotel_db.sql`, 172 tables incl. user hashes) are tracked in git. Files are now covered by `.gitignore` but history still exposes them. **Action: rotate DB/APP_KEY/Google-drive creds, purge history (`git filter-repo`) or treat repo as internal-only.** |
| 25 | 🟠 | Collections/Payments are stubs | `CollectionController::store()` only redirects (no `acc_collections` write). Full receipt flow = planned feature (see PLAN-FEATURES.md §Payments). |
| 26 | 🟠 | HRM module missing but referenced | menus, `smart-soft-alerts`, `UpdateEmployeeIdFromSmartSoft` artisan command, employee-permission tabs all assume it. Either vendor the module in or hide its surfaces (menus/commands). |
| 27 | 🟠 | CRM module UI absent | We restored the **models** to stop the 500 cascade; the CRM is active in `modules` table (rank 11) but has no sidebar/routes in this repo. Rebuild minimal CRM UI or deactivate the module (DB flag). |
| 28 | 🟠 | `.gitmodules` still points `module/CRM` at a submodule | We committed real files; a fresh `git submodule update --init` could clobber them. **Action: remove the gitlink/.gitmodules entry at merge time** (kept here to avoid rewriting gitlinks in this PR). |
| 29 | 🟡 | `/reports/expense-analysis` relies on permission slug `account.expense.analysis.reports` | Users without that row get a denial; super admin unaffected. Seed the slug for role 2 alongside the new reports if the menus are opened up. |
| 30 | 🟡 | Booking flow still trusts the `booking_info` cookie cart (1000-min, client-mutable) | Addressed in PLAN-MODERNIZATION (server-side cart) — not fixed here. |
| 31 | 🟡 | Laravel 8 + PHP 8.1 are EOL, `vendor/` committed | Upgrade path in PLAN-MODERNIZATION §1. |

## C. Verification

1. **Static:** every changed PHP file passes a real PHP-7 grammar parse (`php-parser` AST); only pre-existing
   unparseable constructs (unrelated to these fixes) remain.
- All route/controller pairs in section A were traced from the live 500 page titles to code in this repo.
2. **Runtime:** deploy this branch to staging, then run `tools/verify_fixes.sh`
   (`BASE=… EMAIL=… PASS=… ./tools/verify_fixes.sh`). It logs in, re-checks all 30 previously-broken
   URLs, asserts the intentionally-removed routes now 404 (not 500), and verifies the unauthenticated
   security routes are locked. Exit code 0 = all good; it refuses to run against the live production
   host unless `ALLOW_LIVE=1`.
3. **Logs:** while clicking the fixed screens, watch `storage/logs/laravel.log` — it should stay
   silent. After deploy run `php artisan optimize:clear` once (config/route caches are the #1 cause of
   "the fix is not working" reports).
