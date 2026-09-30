# Plan A — Whole-Software Modernization Plan
### UI redesign → frontend rebuild → backend/platform re-engineering
Target repo: `bitscol-soft/mmheritagehotel` · Prepared 2026-09-30

---

## 0. Guiding principles

1. **Strangler-fig over rewrite.** The app carries live bookings; every phase must ship behind flags and coexist with the legacy screens.
2. **Fix the rails before the train.** Git hygiene, environments, tests, CI and data-migrations come before any pixel — otherwise a modern UI lands on the same broken foundation.
3. **One stack, few moving parts.** The team is small: choose boring, mainstream, LTS tech (Laravel + Inertia + Vue + Tailwind, MySQL, Redis), not microservices, not JS-heavy SPA duplication.
4. **Design system first**, screens second; every screen is generated from the same component set.
5. Every phase ends with: merged to `main`, deployed, smoke-tested, announced.

---

## Phase 0 — Stabilize & secure the current app (1–2 weeks, no features)

**Goal: stop the bleeding; make the repo safe to build on.**

- [ ] **P0.1 Credential purge & rotation** — `.env` (APP_KEY, DB root password, SMTP password), `database/mmheritagehotel_db.sql` (guest PII + password hashes), `vendor/` (312 MB), `public/assets/uploads` etc. are committed. Rotate SMTP + DB creds, invalidate sessions (new APP_KEY), migrate DB dump + uploads to private storage (git-lfs bucket/S3) out of git, rewrite git history (`git filter-repo`) **or** start from a fresh orphan branch, add `.gitignore` (`.env`, `/vendor`, `/node_modules`, `/public/storage`, `/storage/*`, backups), enable GitHub secret-scanning + push-protection.
- [ ] **P0.2 Branch reconciliation** — merge `zesan`→`development`→`main` (booking-success page, RM invoices, mail templates), delete stray `AI` branch, resolve the **broken `module/CRM` gitlink** (either recover the CRM repo or remove the reference + its orphan `modules` DB rows), adopt trunk-based flow with short-lived PRs + 1 review.
- [ ] **P0.3 Hotfixes already discovered** (screenshots list in `ANALYSIS.md §4`): missing `SaleExchangeController` (Hotel/Bar/Restaurant routes), `ParentPermission/Submodule::create`, `ExportItemCSV`, HRM-referencing `/sync-data*`, `permission-access`, `select/employee/list` — either implement or remove routes; make `GET /optimize-clear` and `GET /hotel/delete-all-booking-by-query` **POST + super-admin only**; remove `debug-bar`/`dump-server` from prod autoload; disable `update-debug`.
- [ ] **P0.4 Guardrails** — move `db-backup` out of browser download (console command + signed S3/Drive upload, delete `public/app/backups`), login rate-limit (throttle 5/min + lockout), force-HTTPS/HSTS, cookie `SameSite=Lax`+`secure`, hide stack traces (`APP_DEBUG=false`, custom 500 page), add `GET /healthz`.
- [ ] **P0.5 Baseline** — snapshot prod schema → **real migrations** (`php artisan migrate:fresh` parity), seeders (roles/permissions/settings/rooms), Dockerfile + `docker-compose.yml` (php-fpm 8.3, nginx, mysql 8, redis), `.env.example`, one-click README. Add Pest + PHPStan(Lv5) + Pint; GitHub Actions CI: lint, static analysis, tests, `npm ci && vite build`.

*Exit criteria: fresh clone → `docker compose up` → seeded app; zero known 500s; no secrets in git.*

## Phase 1 — Platform upgrade (2–4 weeks)

- [ ] **1.1 PHP 8.3 + Laravel 11→12** (jump via 10 first if needed: `laravel/upgrade` tooling), replace retired packages: `laravelcollective/html` → Blade form + `Form` components (mechanical, view-only change; ~120 forms), Livewire 2 → **Livewire 3** or deprecate (only a handful of usages — audit), `Fruitcake cors` → native config, mPDF → **Dompdf→Snappy or Browsershot (Chromium)** single PDF service.
- [ ] **1.2 Frontend build**: replace Laravel Mix with **Vite**; move ad-hoc `public/assets/*` (jQuery plugins, fonts, swf!) into npm deps or delete; Tailwind 4 + Headless UI/DaisyUI-free — custom layer; **remove jQuery+DataTables progressively** (see Phase 2), keep for legacy pages until strangled.
- [ ] **1.3 Sessions/cache/queue on Redis**, `QUEUE_CONNECTION=redis`, all mail/notification via queued jobs; Horizon for visibility (free admin UI).
- [ ] **1.4 DB hygiene**: InnoDB FKs enforced, soft deletes for `booking`, money → `decimal(12,2)` + rounding policy doc, unique invoice sequence via `SELECT … FOR UPDATE` or Redis counter, date indexes on every report query, `EXPLAIN` audit of the top-20 report SQL.
- [ ] **1.5 Tests that matter**: feature tests for booking lifecycle (create→assign→checkin→extra-charge→collection→invoice→checkout→night-audit), POS sale, permission gating; factories + snapshot data; CI blocks merge under 60% on `module/Hotel` core.
- [ ] **1.6 API surface**: keep Sanctum, build versioned JSON API `routes/api.php` (`/api/v1/auth`, `/rooms`, `/bookings`, `/folio`…) with OpenAPI (scramble) — prerequisite for mobile/kiosk/channel-manager later. Rate-limit + scopes.

*Exit: same screens, new platform, all green CI, deploy pipeline (GH Actions → deploy script/Forge/Ploi).*

## Phase 2 — New UI design system + shell (3–5 weeks, overlaps 1)

**Design language:** a modern hospitality console — think “Mews / Cloudbeds light”: calm, dense-when-needed, keyboard-friendly, mobile-first.

- [ ] **2.1 Design tokens** — palette (brand deep-green/gold from hotel identity, semantic `success/warning/danger/info`, dark-mode neutrals), type scale (Inter/IBM Plex Sans; tabular numerals for money), spacing 4-pt, radius 8/12, elevation 3 levels. Figma library + `tokens.css`/Tailwind theme single source.
- [ ] **2.2 Component kit (Vue 3 + Tailwind)**: `AppShell` (collapsible rail sidebar **data-driven from permissions**, global search, date/time, notifications, user menu), `DataTable` (server-side, saved views, column picker, density, CSV export), `FilterBar`, `DateRangePicker`, `MoneyInput`, `StatusChip` (unified booking states), `Stepper`, `Modal/Drawer`, `FormRow` with inline validation, `EmptyState`, `Skeleton`, `Toast`, `ConfirmDialog`, `PrintFrame`.
- [ ] **2.3 App shell re-skin** — new layout for **all** legacy pages via a Blade master swap (Tailwind CDN of the component CSS), keeping Blade bodies initially (progressive enhancement: replace tables/modals first). Fix accessibility (focus rings, contrast AA, keyboard nav) at shell level.
- [ ] **2.4 Inertia.js + Vue islands** for every **new/rebuilt** screen; Livewire 3 allowed for wizard-style interactive forms (booking) — pick Inertia as default for shared state, store: Pinia (auth/user/perms), router-driven breadcrumbs.
- [ ] **2.5 Pattern library & docs** (Storybook) + UI review process; EN/MS language files from day 1 (`i18n` — Laravel lang + `vue-i18n`), RTL-safe not required; mobile breakpoints (front-desk tablets are the real device).

*Exit: shell + 10 flagship screens on new UI; token-driven; Storybook published; lighthouse a11y ≥ 95 on shell.*

## Phase 3 — Rebuild the core workflows (6–10 weeks, screen by screen)

Priority order (business value × risk):

1. **Front-desk Dashboard** — today’s arrivals/departures/in-house/needs-clean tiles, quick actions, live clock, night-audit status, credit alerts. (Replace `HomeController::index` widgets with API-backed components.)
2. **Reservations calendar & room-box board** — drag-drop over a per-day timeline (rooms × dates), color by status, click-to-assign, conflicts guarded server-side; replaces `booking-ui` + cookie cart with a **server-side reservation cart** (`booking_carts` table already exists — finish it; kill `booking_info` cookie).
3. **Booking wizard rebuild** (Inertia): search → room/pax/rates → guest (CRM pick/create) → addons (services, banquet, F&B) → payment plan → confirm; all validation live; autosave draft bookings; print/preview folio inline.
4. **Check-in / check-out** — kiosk-lite fast lane (room + guest scan), document upload with signed URLs, key notes, folio balance enforcement, late-checkout request → manager approve.
5. **Night audit** — guided checklist (rooms → F&B/bar postings → currency → VAT → lock day), variance report with drill-down; cron-safe job + re-run protection.
6. **POS (Restaurant/Bar) unified** — single reusable POS engine (products, tables, orders, KDS ticket view, split/merge bills, payments) shared by both modules (code today is 2 forks).
7. **Accounting & reports** — keep ledger logic, re-skin reports as a `Report Builder` (saved queries + column presets + Excel/PDF via queues), cash-flow chart, P&L/balance sheet per period + per company.
8. **Guest & company CRM** — revive the deleted CRM (data still in `c_r_m_customers`/submodules rows): profiles, stay history, preferences, corporate contracts, leads; auto-link from bookings/invoices.
9. **Admin/Settings cluster** — users/roles/permissions editor (matrix UI vs today’s table-of-checkboxes), system settings grouped wizard, room-type & rate setup, VAT/tax config, currency config, website CMS (live-preview editor), backup/health pages (replacing raw endpoints).

Each item ships as: API endpoints → tests → UI → behind flag → pilot on the local clone with the real dump → flag off legacy.

## Phase 4 — Cross-cutting engineering (continuous, budget 20%)

- [ ] **Observability**: structured JSON logs → Loki/CloudWatch or Papertrail, Sentry (frontend+backend), slow-query log, uptime monitor on `/healthz`, weekly error report.
- [ ] **Security program**: 2FA (TOTP) for staff + optional SSO (Entra/Google) for admin, device/session management page, audit log UI (activity_logs wired properly), signed URLs for uploads + AV scan hook, dependency-review + Renovate, secrets in GH Actions environments, DB access via bastion, quarterly pen-test checklist (OWASP ASVS L2).
- [ ] **Performance**: route-level HTTP cache headers for lists, index audit, N+1 killers (`Model::preventLazyLoading` in dev), eager-load scan (e.g., `larastan` + blade `->loadMissing` policy), pagination caps, image pipeline (glide/Intervention on upload, webp/avif, lazy-load), Redis cache for pricing/availability lookups, target <300 ms p95 for list endpoints, Lighthouse ≥90 on public site.
- [ ] **Data migrations discipline**: migrations-only from now on (schema diff CI check vs dump), seeders for every env, nightly logical backup (queue job, encrypted to S3/GCS, 30-day retention, restore drill quarterly).
- [ ] **Docs-as-code**: ADR log, ERD (dbdiagram.io), per-module README with route/permission tables auto-generated from the router.

## Phase 5 — Public site & guest-facing web (3–4 weeks)

- Rebuild marketing site with **the same Inertia app** (SSR for SEO) or Astro static front + API — decision: keep Laravel single deploy, use Blade→Vue islands, SSR; content from `HotelWebsite` CMS models. Real gallery (signed CDN), rooms pages driven by live availability + rates, **booking engine rewritten** (dates → guests → promo codes → deposit via payment gateway in Plan B) with a modern responsive wizard; the zesan `booking-success` design becomes the confirmation base (folio PDF emailed + printable, iCal link, add-to-calendar, support contacts, review prompt).
- Guest portal (Phase B feature) hooks: reservation lookup by number+email, pre-check-in form, folio & invoice download.
- SEO basics (meta per page via settings table, sitemap, schema.org `HotelRoom` offers), cookie consent, contact form → CRM lead + mail templates (zesan branch), reCAPTCHA v3.

## Phase 6 — Deploy, migration & rollback plan

- Environments: `dev` (docker compose), `staging` (clone of prod data, nightly refresh + anonymization script), `prod`.
- Deploy: zero-downtime (Envoyer/Forty-php or GitHub Actions + `deployer` recipe: pull → composer install --no-dev → migrate → cache:roll-back-safe → restart php-fpm); feature flags table + `fnhas()` helper for the strangler.
- **Data cutover strategy**: none needed for phases 0–4 (same DB). If DB split or Laravel-upgrade issues arise: expand→migrate→contract pattern, dual-write fallback for 1 release.
- Rollback: every release tagged, `envoy` rollback task, migration reversibility check in CI, DB migration gates for breaking changes.
- Training & rollout: staff runbook per module (manual in `USER-MANUAL.md` regenerated for new UI at each phase), 2-week parallel-run on front desk, super-user champions, feedback loop via in-app “report a problem” (creates a ticket in the same ERP).

## Effort & schedule summary

| Phase | Duration | Team (min) | Risk |
|---|---|---|---|
| 0 Stabilize | 1–2 wk | 1 full-stack + 1 devops (part) | low |
| 1 Platform upgrade | 3–4 wk | 2 full-stack | medium (dependency churn) |
| 2 Design system + shell | 4–5 wk | 1 designer + 2 front (1 back assists) | low |
| 3 Core workflows rebuild | 8–10 wk | 3 full-stack + designer | high (domain logic) |
| 4 Cross-cutting | continuous +20% | shared | low |
| 5 Public/guest web | 3–4 wk | 1 front + 1 back | medium |
| 6 Deploy/migration | per-phase + 1 wk final | devops | medium |
| **Total** | **≈ 5–6 months** | 3–4 people | |

## Definition of done (for the whole program)

* Laravel 12 / PHP 8.3 / MySQL 8 / Redis; zero retired packages; CI green (tests, phpstan, e2e smoke on 15 critical journeys).
* All 4 previously-broken areas fixed or deleted; no public GET destructive routes; no secrets in repo (verified by scan).
* New design system + 100% of daily-driver screens rebuilt; legacy screens read-only-frozen or removed.
* Booking lifecycle + night audit fully tested; p95 < 300 ms; a11y AA; EN/MS ready.
* Backups/restore drill documented & executed; monitoring + Sentry wired; staff trained on new UI; docs updated automatically.
