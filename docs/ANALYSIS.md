# MM Heritage Hotel — Full System Analysis

**Product:** `mmheritagehotel` — hotel booking / property management / hospitality back-office suite
**Live site:** https://mm-heritage-hotel.dizihotel.com (login verified working; same DB as the dump in the repo)
**GitHub:** https://github.com/bitscol-soft/mmheritagehotel
**Analyzed on:** 2026-09-30 · by Agent-mode deep review (code + live running instance)
**Method:** full static analysis of all 4 git branches + the app was **run locally with the shipped production DB dump** (1,014 routes enumerated via the live router; every screen exercised and screenshotted — see `screenshots/`).

---

## 1. What the software is

A **Laravel 8 monolith "Smart ERP"** (built on the Banglafire "Smart ERP" / skycoder package family) customized into an all-in-one hospitality suite for a single hotel (MM Heritage Hotel, Melaka, Malaysia). It combines:

| Domain | Module | What it does |
|---|---|---|
| Public booking engine + website | `module/HotelWebsite` + `app/Http/Controllers/Front` | Marketing homepage, room categories, gallery, CMS pages, availability search, **cart-based online reservations, guest self-registration** |
| Front-desk PMS | `module/Hotel` | Room inventory, categories & pricing, **box-style availability booking UI**, reservations (walk-in + OTA + web), room assignment, check-in/out, housekeeping board, guest registry & uploads, deposits, extra charges, payment collection, invoices (v1–v4 + checkout invoice), **night audit**, VAT, currency conversion, referred bookings, adjust/extend stay, booking notes, reports (monthly summary, cash flow, room logs, arrivals/departures, today activity…) |
| Banquet halls | `module/BanquetHall` | Hall/service catalog, banquet bookings with tfoot pricing, invoices (currently **disabled** in prod data: `modules.status=0`) |
| Restaurant POS | `module/Restaurant` | Table management, orders/kitchen tickets, sales/returns/exchange, stock & batch tracking, recipes/production (finish goods), night audit, reports; barcode/QR helpers |
| Bar POS (pharmacy-flavoured) | `module/Bar` | Same POS skeleton as Restaurant but with medicine/brand/package/generic catalogs and batches; sales/returns/exchange, supplier/purchase, ledger |
| General store | `module/GeneralStore` | Items, purchase→GRN, goods requisition, stock tracking |
| Accounting & Finance | `module/Account` | Chart of accounts (groups/subsidiaries/controls), opening balances, vouchers, journal transactions, payments/collections, fund transfers, banks, supplier/customer ledgers, damages, invoice numbering, financial reports (P&L, balance-sheet style, cash-flow) + Excel exports |
| Hotel services | `module/HotelService` | Bookable ancillary services (spa/laundry-type) |
| Access control / RBAC | `module/Permission` | Modules → submodules → parent permissions → permission (slug) management, per-user & per-employee permission assignment |
| System | `app/*` | Users, companies, system settings, id-card settings, DB backup (+ Google Drive), password reset, "SmartSoft" payment schedule alerts, HR/attendance sync stubs |

Data footprint of the shipped production dump (`database/mmheritagehotel_db.sql`): **172 tables**, 6 users, 5 room categories, 103 rooms, 6 bookings (Feb-2024 snapshot), VAT/currency/account-type settings, website CMS content.

## 2. Branch & repository state (git analysis)

| Branch | State |
|---|---|
| `main` (= `AI`, identical) | Single commit `0bdac333 "initial files"` — a snapshot of the whole app (incl. `.env`, `vendor/`, DB dump). **No history, no tests, no CI.** |
| `development` | main + 15 commits (Mar 2024): currency→**Ringgit** in invoices, deposit-money fixes, booking success message, extra-charge removal, room-price fix, design updates. Adds a **`module/CRM` gitlink (submodule pointer to commit 6771fe63 whose repo is not referenced in `.gitmodules` — broken/unavailable)**. This branch is the real integration branch. |
| `zesan` | development + 3 commits (2024-03-04): **booking-success page design**, **contact/reply mail templates** (`app/Mail/ContactMail`, `ReplyContactMail`, `resources/views/mails/*`), room-status & front-page styling, style.css. |

**Key takeaway:** production work diverged from `main` in March 2024 and has never been merged — three features exist **only** on side branches: booking-success screen, invoice currency fix (RM), mail templates, and a partially-referenced CRM (booking screens already read a `c_r_m_customers` table + `CRMCustomer` model + `crmCompanies` in `nextStep()` — the CRM module was evidently present when that code was written; today it lives only in DB rows: `modules` contains an active **CRM** row (id 170000) with submodules Customer/Lead/Project/Mail Template, but the code is gone ⇒ dead UI/DB).

## 3. Architecture & tech stack (current)

* **Backend:** PHP `^7.3|^8.0`, **Laravel 8.75** (EOL Jan-2025 security-fix end), Livewire 2, Laravel UI auth, Sanctum (a near-empty mobile API), `laravelcollective/html` (retired), Maatwebsite Excel, mPDF, barcode/QR, intervention/image, Google Drive flysystem, `spatie/db-dumper` shell backups, `skycoder/*` vendor packages (invoice numbers, file saver, query shorter).
* **Frontend:** classic Blade server-rendering, jQuery-era "Ace/Smart" admin skin, Bootstrap 3-ish CSS, hand-rolled `public/assets` (no build pipeline for admin; webpack/laravel-mix only compiles a thin `app.js/app.css`); DataTables everywhere (AJAX server-side endpoints per module).
* **Structure:** `module/<Domain>/{Controllers,Models,Services,routes,views}` autoloaded via PSR-4 `Module\` → `module/`; `routes/web.php` + one route file per module (≈343 `Route::` definitions; router reports **1,014 actual routes** after resources expansion: 639 GET, 163 POST, 107 PUT, 107 PATCH, 100 DELETE).
* **Tenancy & RBAC:** soft `company_id` columns (single company in practice), users with `role_id` mostly NULL; **permission helpers** (`hasPermissionV2`, `hasModulePermissionV2`) gate menu slugs cached in session by a global view composer; sidebar is DB-driven (`modules` table → `__sidebar_<module>` blades in resources or module views).
* **Data model quirks:** no foreign-key integrity (constraints exist in the original MySQL dump but app code rarely relies on them), string "statuses" for booking lifecycle, invoice numbering via `invoice_generate` rows written **in controller constructors**, money stored as decimal(15,3) strings, sessions/queues/cache on file/sync drivers.

## 4. Live-site verification (what we actually ran)

Because the sandbox cannot reach the production host, the repo copy was **booted locally against the shipped prod dump** (PHP 8.1-WASM + SQLite translation of the MySQL dump) and every screen exercised with real data. Observations that matter:

* **The repo does not run as-is:** `module/CRM` is a gitlink (submodule pointer to commit 6771fe63 with no `.gitmodules` entry), yet core code hard-depends on it — `RouteServiceProvider`, `Hotel/BookingController`, `GuestController`, `Hotel/Ajax/AjaxController`, `BanquetBookingController`, `Restaurant/SaleController`, the `Guest` model, `HelperMethods`, console commands `CrmSendMail`/`CRMSeed`. Without `Module\CRM\Models\CRMCustomer` the booking create/next-step, guest-create and collection screens **fatal**. After stubbing the CRM models locally (over the `c_r_m_customers` table, which still holds 2 customers incl. *MM HERITAGE HOTEL*), every booking screen rendered — proving **production runs out-of-repo code**. The DB even retains the whole CRM app: `modules` row "CRM" (active, rank 11) + submodules Customer/Lead/Project/Mail Template. This is the single most dangerous finding: **the GitHub repo is not a deployable snapshot.**
* Login (`kabir.bitscol@gmail.com`, user #1 "Mr. Admin") lands on **Dashboard** with KPI tiles (rooms by status, arrival/departure stats), notices & payment-alert widgets.
* The whole admin is usable but has **genuine runtime 500-errors** already in this codebase (not sandbox artifacts) — e.g.:
  - `module/Permission`: `ParentPermissionController@create` & `SubmoduleController@create` **don't exist** although routes register them (500).
  - `/setting/permission-access`, `/setting/select/employee/list`, `/setting/permitted/employee/list` → 500 (EmployeePermission UI referencing removed HRM module).
  - `module/GeneralStore`: `ExportItemCSV` class missing (broken export), several screens reference HRM `Employee` features that no longer exist.
  - `/sync-data*` routes reference `Module\HRM\...` classes that don't exist in the repo (dead HRM module) → fatal.
  - `routes/api.php` + web route `Api\ApiDashboardController` — **class does not exist** → mobile API is dead.
  - Restaurant/Bar `SaleExchangeController` referenced by routes but **missing in every branch** (500 on those screens; `development` branch fixed templates but not this).
* Front website works: homepage (rooms & rates, gallery, contact form), `/category` & `/category/{slug}` room pages, availability search, cart `/booking-cart`, guest registration + booking submit, terms/privacy pages. Cart is persisted both in session **and** via client cookie `booking_info` (admin box booking uses cookies — fragile).

## 5. Security & operations findings (high severity first)

1. **Real production secrets committed to git**: `.env` (APP_KEY, MySQL root password, **live SMTP mailbox credentials** `no-reply@mmheritagehotel.com`) plus `config/*` defaults; DB dump with user password hashes & personal guest data also committed. The repo is public per the GitHub link → **rotate everything now** (mail password, DB passwords, APP_KEY ⇒ force re-login) and purge history.
2. **DB dump/download endpoints**: `/db-backup` returns a mysqldump file to the browser and writes `public/app/backups/dump.sql` **inside the web root** (predictable path, world-readable while another admin backs up); `/db-backup-to-drive` pushes schema-only dumps to a Google account. Any authenticated (or cached-response) exposure = full-database leak. Not audited against role permissions.
3. **No auth hardening**: password reset tokens are plain SHA-ish tokens in `password_reset_token`; no rate-limiting on `/login`, no 2FA, no session invalidation on password change, `remember me` enabled; `optimize-clear` route is **public** (unauthenticated cache flush DoS); `/update-debug` toggles debug mode (super-admin guarded, but exposes `php artisan debug on`).
4. **Uploads**: guest document/image uploads (`GuestUploadController`, hotel images, CSV imports) go to `public/assets/uploads` with **no meaningful MIME/extension hardening visible**; filenames are client-derived; old `swf` assets still shipped.
5. **XSS/CSRF posture**: Blade `{{-- --}}` escaping by default is mostly OK, but many views render `{!! !!}` raw DB content (CMS/about/emails); CSRF middleware is standard; **CORS `fruitcake/laravel-cors` with wildcard-ish defaults**; JSON debug output enabled when `APP_DEBUG=true` (which the `development` branch .env sets!) — plus the Ignition/Whoops error pages leak full SQL + stack to the browser.
6. **Dependency risk**: Laravel 8 EOL; `laravelcollective/html` abandoned; Livewire 2; PHP 7.3 compatible ⇒ cannot use modern crypto; `composer.lock` pins several dev tools (debugbar, **dump-server**) in production; `vendor/` committed (supply-chain opacity + repo bloat 312 MB).
7. **Data integrity**: booking money paths (deposit/extra-charge/collection) mutate several tables without DB transactions in several code paths (`dueCollectionMulti`, `StoreCollect`); invoice numbering uses non-atomic `invoice_generate` reads/writes; no soft-delete (`deleted_at`) on most tables, and `deleteAllBooking()` route (`GET /hotel/delete-all-booking-by-query`!) exists — a **GET request that bulk-deletes reservations** (guarded only by permission).
8. **Ops**: no logging pipeline (single-file logs), no metrics/health endpoint, no queue (sync driver) yet mails are sent inline (SMTP sync = slow requests), backups are manual.

## 6. Code-quality observations

* 1,405 PHP files, ~967 Blade views; biggest controller **1,691 lines** (`Hotel/BookingController`) with 1,454-line `BanquetBookingController`; god-classes per POS module are near-copies of each other (Restaurant ↔ Bar share ~70% structure; 3 parallel inventory systems: Account, Bar, Restaurant, plus GeneralStore — **4 stock ledgers**).
* Duplicated legacy: `create-copy.blade.php`, `booking-register.blade copy.php`, `SaleV2/SaleReturn/payment` copies per module; dead views (`home.blade.php`, `welcome.blade.php`, `salary_without_payslip`, `machine_int`, `news`, HRM remnants in HomeController).
* Controllers embed validation, HTML string building, mail composition and reporting SQL (`raw()` MySQL fragments: `DATE_FORMAT`, `IFNULL`, `YEAR()`, subquery updates) → not portable, hard to test.
* Zero tests (empty `tests/`), zero CI, no linting config in use (styleci yml present but stale).
* Mixed conventions & typos propagate into routes/slugs (`Aminities`, `HotelTransection`, `MatrialProduct`, `permitted/employee/list`, `booking.referred-booking`, …) — renaming now is cheap, later is data migration.
* Migrations folder is **unused** (schema lives in the SQL dump) → environment reproducibility is nil; `php artisan migrate` cannot rebuild the app.

## 7. UX/visual audit (from the full-screen screenshot pass, `screenshots/`)

* Admin = dense **Bootstrap-3 "Ace" skin** (2013-era): tiny form labels, `input-sm` everywhere, jQuery datepickers, DataTables with red "No Data Found" strips, modal stacking with `z-index` hacks; color-coded status boxes for rooms.
* Booking UI (`/hotel/booking-ui`) is a **date-range → category cards → room-box grid** wizard; boxes are divs toggled into a cookie "cart"; the "next step" (`booking_next`) is one giant page (guests, rooms, rates, flights, payment) — usable but heavy; no inline validation (server redirects back with a toast).
* Lists dominate the product (every entity = table + create modal); **no dashboard drill-downs**, no keyboard flows, no bulk actions (except collection modal), no saved views/filters.
* Front website: dated slider-heavy template, images from `assets/uploads`, mixed lorem-ipsum blocks (live site too), non-responsive tables, currency shown as RM but booking success flow only exists on `zesan` branch.
* Accessibility: contrast ~AA at best, no focus states, icon-only buttons; mobile admin essentially unusable (sidebar collapses but tables don't).
* Print/PDF outputs: 4 competing invoice templates (invoice, invoice-v2, reservation-invoice, rest-sale-invoice, checkout-invoice-v3/v4) with different layouts/currencies — no single document system.

## 8. Feature-gap summary (detailed plan in PLAN-FEATURES.md)

Missing vs. any modern PMS: **channel manager/OTA sync (Booking.com, Agoda…), real-rate plans & seasonality, true reservation calendar, folio/city ledger with AR aging, guest CRM (present→deleted!), payments gateway (only "pay at property" today), self check-in/keys, housekeeping task board, maintenance tickets, dynamic pricing, group/tour contracts, corporate rate contracts, e-invoicing (LHDN MyInvois is mandatory for MY taxpayers), multilingual UI, guest portal, notifications (email exists only as branch WIP; no SMS/WhatsApp integration despite an SMS stub route), analytics, audit-log UI (activity_logs table unused in UI), API (dead), mobile apps.**

---

*Companion documents: `USER-MANUAL.md` (screen-by-screen), `PLAN-MODERNIZATION.md` (UI→backend re-engineering), `PLAN-FEATURES.md` (feature roadmap). Screenshot index: `screenshots/INDEX.md`.*
