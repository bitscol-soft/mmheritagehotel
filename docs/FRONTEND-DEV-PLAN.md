# Frontend / UI Development Plan — execution companion to Plan B

> **2026-10-01 implementation update:** The owner approved Laravel + Blade + Tailwind. See [TAILWIND-DEVELOPMENT.md](TAILWIND-DEVELOPMENT.md) for the active stack, first delivery and verification status. Earlier Bootstrap-first tasks below are historical planning, not the current implementation contract.
### Task-level breakdown of `docs/UI-REDESIGN-PLAN.md` so anyone can pick this up cold and continue
Owner: — · Tracker: GitHub **milestone “UI Renovation”** — waves are issues **#5–#12**, decisions/backlog **#13** (tick boxes there) · Strategy doc: `docs/UI-REDESIGN-PLAN.md` · Prepared 2026-10-01 (branch `arena/01a0f37d-mmheritagehotel`, PR #4)

---

## 0. State of play when you resume (read this first)

| Fact | Value |
|---|---|
| Work branch | `arena/01a0f37d-mmheritagehotel` → PR **#4** into `AI` (never merged yet; do **not** merge without the owner) |
| Done & shipped to branch | Rounds 1–10 (hardening, booking board, booking flow, invoice/slip print, guests sweep, app-wide FA4 icon sweep, banquet parity, reservation-invoice fix, booking-UI responsive redesign). Details: PR #4 comments per round. |
| Deploy | **Manual.** `.github/workflows/deploy-{staging,production}.yml` exist but have 0 runs — `STAGING_*`/`PROD_*` secrets not configured (see `docs/DEPLOYMENT.md`). Test box = `mmheritagehotel-test.dizihotel.com`. Until W0 is done, every shipped wave needs a manual sync + `php artisan view:clear` on the box. |
| Verification harness | php-wasm (no PHP binary in sandbox). Recipe in §5 — keep it; every UI task is verified by *rendering the real route and grepping markers*, plus `rawlint` token check. |
| Standing rules (violated = reject) | ① form `name`s, element `id`s used by JS, AJAX URLs, route names, `hasPermission(...)` gates stay **byte-identical**. ② **No money/amount/currency math changes in any UI diff.** ③ New CSS only in `tokens.css` / component partials; per-page `<style>` shrinks, never grows. ④ A `<style>`-emitting `@include` must never sit inside another `<style>` (round-10 incident). |
| Known open quirks (deliberately unfixed) | `Due` column math in `_booking-table` (docs/BUGS.md), dead invoice templates (decision D1), dark mode (deferred, tokens-ready), `salary_without_payslip.blade.php` is a *directory* (repo oddity — do not "fix" casually). |

Screen inventory (verified by count): Hotel 87 routes/173 views · Account 61/178 · Restaurant 59/187 · GS 37/37 · Bar 29/109 · Permission 20/13 · HotelService 12/17 · HotelWebsite 10/21 (public) · BanquetHall 7/78 (mostly bleeds into Hotel views) · core `routes/` 49 & `resources/views` 160 (shell, landing, shared components, auth).

---

## 1. W0 — Unblock shipping (do before any pixels)  → issue #5

- [ ] **W0.1** Create GitHub environments `staging`/`production`; add secrets `STAGING_SSH_HOST/USER/PORT/SSH_KEY/DEPLOY_PATH/PHP_BIN/URL` (format: `docs/DEPLOYMENT.md`).
- [ ] **W0.2** Dry-run `gh workflow run deploy-staging.yml -f ref=arena/01a0f37d-mmheritagehotel` → must reach Health check (preflight proves secrets; rsync excludes already protect `.env`, `storage/`, `public/uploads`).
- [ ] **W0.3** Confirm the round-10 reservation-invoice fix renders clean on the test box (console check: `document.body.innerText.includes('.col-print-1 {') === false`).
- [ ] **W0.4** Add repo check `tools/ui-guard.sh`: greps staged diff for (a) changes to `*_invoice*/`+`*amount*` lines → fail; (b) `@include` between `<style>`…`</style>` → fail. Wire into a CI workflow (`ui-guard.yml`, push/PR).
  Exit: waves ship same-day, guardrail is mechanical, not tribal knowledge.

## 2. W1 — Design system core  → issue #6

Files: `public/assets/custom_css/tokens.css` (new), `resources/views/components/*` (new), `public/assets/custom_css/style.css` (append-only during this wave, becomes the legacy bucket).

- [ ] **W1.1** `tokens.css` — exact token list in Plan B §3.1; include focus-ring, density, motion tokens; load before `style.css` in `layouts/master.blade.php` (+ any other layout masters found by grep `custom_css/style.css`).
- [ ] **W1.2** `x-page` — slots: `title`, `actions`, `body`; wraps existing `widget-box` markup (keep classes so old CSS doesn't fight); replaces header duplication pattern.
- [ ] **W1.3** `x-filter-bar` + `x-field` + `x-daterange` — extract from the proven booking-list filter (rounds 4/5/10) into components; `x-daterange` must reuse the `apply→#searchForm submit` flow and ship quick-range chips (props: `quick=['tonight','week','weekend']`, like the board).
- [ ] **W1.4** `x-data-table` — props: `columns` (each: `label`, `width`, `priority:1-3`, `align`), `empty` (text+action), `sticky:true`; ≤767px non-priority columns fold into the row-expander (first version: `data-priority` attr + one small shared JS in `assets/custom_js/mm-table.js` + CSS; no framework).
- [ ] **W1.5** `x-select` (Tom Select vendored static, class shim `chosen-select` retained), `x-modal` (focus trap, ESC, size s/m/l), `x-empty`, `x-chip`/`x-badge` (status map from tokens), `x-toolbar`, `x-stepper` (3-step; markup contract: keeps existing form submit flow — steps are visual + `data-step` nav only), `x-print-sheet`, `x-tile`.
- [ ] **W1.6** Component gallery route `/ui-kit` (super-admin only, `Route::view`, dev-time) — every component × states; later doubles as screenshot baseline page.
- [ ] **W1.7** Convert **three proving screens**: booking board (wrap in `x-page`/`x-toolbar` — must be visually a no-op), booking list (`x-filter-bar`+`x-data-table`), guest list. Gate: harness render diffs show zero removed hook strings.
  Exit: 12 components + gallery + 3 converted screens, all behaviour-clean.

## 3. W2 — App shell  → issue #7

- [x] **W2.1** Sidebar icon-rail (68px, persisted in `localStorage`), active-item pill, module counters (arrivals/departures/due-today via existing AJAX endpoints, 60s cache). Shipped in `e62ade52`; module counters omitted (no existing endpoints).
- [x] **W2.2** Topbar: global search (wire to existing `searchRoomByNumberAjax`-style endpoints per module *only where an endpoint already exists*; else omit), quick-add menu (route-gated by same permissions as their create buttons), date button → board-today. Shipped in `83034b00`; global search omitted (no existing endpoints).
- [x] **W2.3** `x-page` becomes the only header pattern; delete per-page `page-header` blocks *as encountered* (never bulk-sed). 219 dead blocks removed (commit `7efd787d`); ui-guard Tripwire 3 prevents regression; 95 untouched blocks remain in non-ported pages (encountered-pool).
- [x] **W2.4** Fluid container (max 1440) + horizontal-scroll wrappers for all tables (`.mm-table-scroll`); mobile bottom action-bar variant for `x-toolbar` footers. Shipped in `96c9ee85`; mobile action-bar deferred.
- [x] **W2.5** `prefers-reduced-motion` + focus-visible global tokens; contrast sweep: replace sub-12px `--mm-muted` on colored bg (board meta pattern) with `--mm-ink-soft`. Shipped in `96c9ee85`.
  Exit: every module visually inherits shell without per-page edits; 360px audit passes on dashboard, booking list, login.

## 4. W3 — Hotel module completion + print programme  → issue #8

Order = user value; each task = one PR-size chunk. Screens not listed keep their layout until their archetype lands.

- [x] **W3.1** **Reservation invoice → `x-print-sheet`** (the *open user-visible task*: restyle `module/Hotel/views/booking/reservation-invoice.blade.php` to the round-6 `invoice-doc` look; amounts PHP left byte-identical; twin `hall_booking/reservation-invoice` follows in W4). Shipped in `8fa1373b`; body content kept under `.invoice-content-legacy` (full body restructuring deferred to a follow-up commit).
- [ ] **W3.2** Booking check-in slip + payment receipt → `x-print-sheet`.
- [ ] **W3.3** Guests (`/hotel/guests` family), referred-booking, booking notes: `x-filter-bar` + `x-data-table`.
- [ ] **W3.4** Room management CRUD (categories/rooms/amenities/vat/account-type) — forms → `x-field`/`x-select`; photo uploader dropzone (progress bar only, endpoint unchanged).
- [ ] **W3.5** Night audit + today-activities → `x-tile` dashboard grid + `x-data-table`.
- [ ] **W3.6** Housekeeping board → same tile grid as booking board (reuses `x-room-status`; verify `updateStatus` hook intact).
- [ ] **W3.7** Reports (12 screens): print variant via `x-print-sheet`; export buttons stay (ExportService untouched).
- [ ] **W3.8** Decision D1 executed (see backlog): delete dead templates `booking/get_invoice`, `checkout-invoice-v2`, `-v4` + unrouted banquet `booking_ui`/`available()` (or annotate `@deprecated`).
- [ ] **W3.9** Hotel `booking/create|edit|adjust` deep pass: `x-stepper` wrapping the existing `booking_next` flow; payment tab → `x-field`; modals → `x-modal` (extend-date, extra-charge, member-detail).
  Exit: Hotel module has no bespoke `<style>` block > 20 lines; print docs all sheet-based; module frozen 2 sprints → eligible for T2.

## 5. W4–W7 summary (full task lists in issues #9–#12)

- **W4 Banquet & services** (issue #9): hall lists/forms ride Hotel partials (cheap by design — verify bleed, don't fork); `hall_booking/reservation-invoice` → `x-print-sheet`; HotelService + News & Events lists/tables; booking-purpose CRUD.
- **W5 POS cluster** (issue #10): Restaurant table map → board-grid; order screen → `x-stepper` (courses→fire→pay); kitchen ticket 80mm (`x-print-sheet --sheet=thermal`); Bar & Merchandising & Knitting follow the same 3 patterns (list/create/print). GS: requisition/GRN lists + stock-count board grid.
- **W6 Finance & admin** (issue #11): Account voucher entry (ledger rows as `x-field` repeat-groups; totals JS untouched), statements/ledgers → `x-data-table` with sticky totals row, financial-report prints; HRM payslip; Permission matrix screen → grid + bulk toggle; system settings → section cards; login/password pages → clean token-based layout (first public-facing screen to renovate).
- **W7 Public site** (issue #12): HotelWebsite landing, availability search, gallery, guest self-register flow — brand-led pass, separate token set (`--web-*`), no admin dependency. T2/T3 decisions recorded here too (gate: 2 quiet sprints per module).

## 6. Verification workflow (per task — the part that makes this safe)

1. `rawlint` — token-balance every touched blade (`php.run` token_get_all script; kept in `tools/` after W0.4).
2. Render harness (php-wasm 3.1.56; sandbox has no PHP):
   - `npm i @php-wasm/node@3.1.56 @php-wasm/universal@3.1.56` in a scratch dir;
   - sync repo → `app/` via `cp -al` **but real-copy `storage/`** (chmod on hardlinks dirties the repo!) and `rm`-before-write any app-copy file you patch (CSRF stub, probe routes);
   - `PHPRequestHandler({php, documentRoot, cookieStore})` — custom store required (handler overwrites `Cookie` otherwise); decode `%3D` in incoming cookie header; extension-less routes need the `/index.php` prefix;
   - auth: session round-trip is unreliable there → mount a probe route in the *app copy*: `Auth::login($u,true)` then call the controller directly (pattern proven in rounds 9/10).
   - Assert: HTTP 200, hook-string greps (before/after identical), new markers present, style tag balance, no CSS text outside `<style>`.
3. For print tasks: `curl` HTML + headless PDF (chromium `--print-to-pdf`) → page-count + no-clipped-text check; never compare screenshots of amounts — compare the **PHP expressions in the diff** (must be absent).
4. Update `screenshots/` pairs + PR comment per round; record any newly found-but-unfixed quirks in `docs/BUGS.md`.

## 7. Backlog & decisions (issue #13)

| ID | Item | Status |
|---|---|---|
| D1 | Delete vs keep dead invoice templates (W3.8) | **needs owner** |
| D2 | Tom Select as canonical autocomplete | proposed → ratify at W1.5 |
| D3 | Dark mode (tokens ready) | parked, post-W6 |
| D4 | Due-column math quirk | flagged in BUGS.md; needs owner + separate functional ticket (out of UI scope) |
| B1 | Charts for reports (pick one lib, static) | W6 stretch |
| B2 | Global search API (needs backend; outside Plan B) | parked |
| B3 | Email templates (booking confirmations, payment reminders) — same design tokens, MJML optional | W6.5 |
| B4 | Keyboard shortcut layer (`g b` board, `/` focus search) | W2 stretch |

## 8. Estimation recap & suggested cadence

W0 2–3d · W1 1–2wk · W2 1–2wk · W3 2–3wk · W4 1wk · W5 2–3wk · W6 2–3wk · W7 2–4wk · (T2 3–4wk, T3 rolling — gated).
Cadence: 1 task per PR-sized chunk; every chunk ships green on the test box (same-day once W0 lands); waves close with a screenshot-diff review (gallery route §W1.6) against the previous wave's baseline.

## 9. “I’m back — what do I do?” checklist

1. `git log --oneline docs/UI-REDESIGN-PLAN.md docs/FRONTEND-DEV-PLAN.md` + read milestone **UI Renovation** (issues are the live task board; tick boxes there, not here).
2. Check PR #4 status (open rounds are listed in its comments).
3. If nothing changed since this file: start **W0.1**, then **W1.1** (`tokens.css`).
4. Rebuild the harness per §6 if the sandbox is fresh (it gets wiped — that's normal).
5. Keep the four standing rules (§0) non-negotiable; when in doubt about money lines: touch nothing.
