# Laravel + Blade + Tailwind — first implementation

2026-10-01. This implementation supersedes the immediate Bootstrap-component
strategy in FRONTEND-DEV-PLAN and UI-REDESIGN-PLAN. No platform upgrade or SPA.

## Scope delivered

- Tailwind 3.4.19 pinned, with lockfile and dedicated CLI build alongside Mix.
  Version 3 is deliberate for the legacy-browser-compatible coexistence layer;
  a later major upgrade requires a front-desk device/browser audit.
- `tw-` utilities scoped under `.mm-ui`; no Preflight or universal base reset.
- Pixel-based spacing/type avoids Ace's 10px root shrinking Tailwind defaults.
- Tokens live in `resources/css/ui.css`, scoped to the new layer.
- Compiled `public/assets/custom_css/ui.css` is tracked for current static-file
  deployment. CI rebuilds it and detects stale output. No CDN dependency.
- New `x-mm.page`, `panel`, `badge`, `field`, `table-scroll`, `styles` components.
  Existing `widget/*`, room components and status classes remain untouched.
  `badge` is informational only, NOT a new room-status mapping.
- First consumer: Hotel guest list and its responsive GET filters. Existing
  routes, request names, guest calculations, selection JS and pagination remain.
  Native Select All button adds keyboard access to the existing click handler.
- `/ui-kit` component gallery protected by existing auth + super-admin middleware.
- CSS only loads when a page renders `x-mm.styles`; untouched pages stay legacy.

## Commands

```sh
npm ci
npm run ui:build
npm run ui:check
php tools/ui-blade-check.php
# optional local CSS watcher
npm run ui:watch
```

`ui:check` verifies selector isolation, no Preflight, protected gallery declaration,
and preservation of original guest IDs/names/route expressions/click handlers.
The baseline commit is intentionally fixed to the pre-development snapshot.
The PHP test compiles nine templates using the installed Laravel Blade compiler
and checks escaped field output without loading .env or a database. It is not
an authenticated HTTP or browser test.

## Verification performed

- Tailwind production compilation: passed.
- CSS isolation / guest hook assertions: passed.
- Nine Blade template compilation + PHP token parsing: passed with PHP 8.1 WASM.
- Escaped field render: passed.
- Git whitespace checks: passed.
- npm install reports 13 dependency advisories across the existing toolchain;
  dependency modernization is separate, not silently force-upgraded here.

## Before staging acceptance

Deploy this session branch through the existing reviewed process; no deployment
was performed here. Verify the guest list and `/ui-kit` on 360px/768px/1440px,
keyboard focus, long names, empty/search results, GET filters, page links, visible
row selection versus Select All, and SMS navigation using a test account/data.
Do not send real SMS or delete real guests as part of smoke tests. Verify gallery
access is denied to ordinary staff and anonymous visitors. Test print layout.

No authenticated browser/visual acceptance has been completed. The PHP smoke
check is intentionally isolated and does not claim full controller coverage.

## Next increment

Browser acceptance of the guest screen, then booking-list migration. Keep room
boards, invoice expressions, Bootstrap plugins and money logic intact until
specific regression tests cover those workflows. Do not migrate the entire
shell globally in the first increment.

## Second increment — booking list (2026-10-01)

- The booking index now consumes `x-mm.page`, `badge`, `styles` and
  `table-scroll`, with a clear header, responsive filter grid and empty state.
- Both ordinary and referred-booking filter branches are preserved. Chosen,
  datepicker, selected values, date formats and request names are unchanged.
- Moved the index's embedded stylesheet to scoped `.mm-ui.mm-bookings`
  compatibility rules. Added scoped plugin sizing adapters. No global restyle.
- Booking table and all its amount/permission expressions remain byte-identical;
  the entire index JavaScript section also remains byte-identical. Checks enforce
  both against the baseline. Existing modals, pagination and export controls
  remain outside the new table overflow container.
- Extended the PHP smoke test to 11 templates, plus actual filter renders for
  ordinary/referred routes, expected field visibility and escaped request input.
  These checks passed under PHP 8.1 WASM; Tailwind build and isolation checks pass.
- Still pending: authenticated browser QA at 360/768/1440px, Chosen guest/category
  selection, category-dependent rooms, date filters, pagination, modal opening,
  exports and staff acceptance. No staging deployment performed in this increment.

## Third increment — guest create/edit/import (2026-10-01)

- Guest create (including CSV import mode) and edit now use the shared Tailwind
  page/panel layer. Directory links and primary/secondary actions are consistent.
- Preserved original form nodes, routes, multipart encoding, CSRF/method fields,
  field names, value expressions, required flags, plugin classes and JavaScript.
  Create deliberately still opens its submission in a new tab, as before.
- Added explicit label associations, accessible upload/camera names and CSV
  guidance. Scoped adapters improve field sizing, mobile stacking and camera
  preview containment without replacing Chosen, Select2, Ace upload or webcam JS.
- Removed the create/edit page-level stylesheet blocks; their necessary styling
  now lives under `.mm-ui.mm-guest-form` in the compiled layer.
- Tests: 15 templates compile, two booking-filter renders, escaped-field render,
  actual CSV import render with CSRF/multipart checks, and guest contract/value/JS
  preservation checks pass. These are not authenticated browser acceptance tests.
- Pending staging QA: create/edit validation errors, country/company selections,
  document uploads, webcam modal/capture/close and CSV import using disposable
  data; check 360/768/1440px layouts. No test-site deployment performed.

## Fourth increment — room-booking board shell (2026-10-01)

- `/hotel/booking-ui` now uses the shared Tailwind page header, directory action
  and panel. Stay/room summary chips wrap on narrow screens.
- The underlying `x-room-manage`, `x-room-status`, date picker initialization,
  status colours, selection handlers and form flows were not changed. The old
  page-wide table CSS rule is now scoped to the board page.
- Added byte-for-byte guards on shared board components/scripts and equality
  checks for both PHP calculation blocks and displayed Blade expressions.
- Verification: 16 templates compile; existing render/contract tests pass.
  Added an actual nested page/panel/badge render that verifies action slots,
  escaped titles and exactly one versioned CSS link when requested twice.
- This is a shell migration, not a claim of visual equivalence or full browser
  validation. Pending staging acceptance: date ranges/quick chips, room selection,
  next-step navigation, status popovers, scroll/sticky footer, keyboard and mobile
  layout at 360/768/1440px. No live deployment or data mutation performed.

## Fifth increment — room/category lists (2026-10-01)

- Room inventory and room-category lists now use shared Tailwind page headers,
  action buttons, panels and keyboard-focusable table overflow containers.
- Replaced the rooms filter's layout table with responsive labelled fields for
  name, room number and card number; request names and default GET flow remain.
- Added column scopes, accessible category action names and scoped sizing for
  DataTables controls and row actions. Existing DataTables initialization,
  pagination, category sorting, status labels and delete confirmations remain.
- Automated guards compare both original JavaScript sections and entire table
  bodies (allowing only accessible-name additions) to the baseline. Price and
  currency expressions, status branches, CSRF/delete fields and routes pass.
- Verification: Tailwind build, selector isolation, contract checks, 18 Blade
  compilations and all earlier render smoke tests pass. No deployment performed.
- Browser acceptance still required: DataTables initialization/search/pagination,
  category sorting, room filters, pricing-setting variants, horizontal scrolling
  at 360/768/1440px and confirmation cancellation without deleting real records.

## Sixth increment — room create/edit (2026-10-01)

- Room create/edit use shared Tailwind page and panel components with an inventory
  back-link. Removed the fixed eight-column canvas and duplicated widget headings.
- Scoped form adapters provide full-width mobile fields, consistent input sizing,
  Chosen focus treatment and a responsive submit area. Corrected label `for`
  associations and made duplicate-room feedback an ARIA live status.
- Retained ALL internal form row ancestry: duplicate-number checks rely on
  `closest('.row')` to find the category/room controls. No pricing, occupancy,
  validation or submission code was changed. Create multipart encoding and edit
  PUT spoofing remain. These two forms have no upload inputs to migrate.
- Added checks for both script sections, the shared duplicate/submission script,
  field/value expressions, Laravel Collective category selects, required flags,
  status options and internal div ancestry. Whitespace-only reindentation of
  expressions/options is normalized where needed.
- Verification: production CSS build, contract/isolation checks and 20 Blade
  compilations pass, along with earlier rendering smoke checks. This is not a
  claim of authenticated browser or controller validation. No deployment made.
- Pending: pricing setting on/off, create/edit values and error states, Chosen
  keyboard interactions, duplicate-room AJAX feedback, submit-once behaviour
  and 360/768/1440px browser QA with disposable data.

## Seventh increment — shared admin workspace (2026-10-01)

### What changed

- Standard admin pages now receive a shared responsive shell: fixed desktop
  navigation, a mobile drawer, a tidier top bar, page/date toolbar, consistent
  content/footer spacing, and a browser-local compact-spacing preference.
- “Find a menu” filters only links already rendered by the existing session/menu
  permission logic. Matching descendants retain their parent context; clearing
  search restores Ace's existing open/closed state. No extra routes or permission
  shortcuts are created. Notification branches, sales links, cache action and
  POST/CSRF logout are preserved, not reimplemented.
- Keyboard support includes skip-to-content, focus on mobile search, drawer Tab
  containment, Escape/backdrop/close buttons, focus restoration, current-page
  markers and hidden/inert navigation. Desktop collapse and compact spacing are
  saved locally per browser, NOT per account. Storage denial is handled.
- Tailwind still uses prefixed utilities and no Preflight. Shell adaptations are
  isolated under `.mm-shell` in `resources/css/shell.css`, compiled separately to
  `public/assets/custom_css/shell.css`. The regular component layer is requested
  once by the shell. Bootstrap/Ace continues to own dropdowns and nested menus.
- The shell opts out of Ace's persisted rail/minimized/scroll/hover modes while
  enabled; those modes remain intact for rollback. Existing Chosen resize
  listeners receive their compatibility event. Header background and brand text
  use the existing configured colours; topbar action buttons have neutral styling.
- Employee, salary/payslip, bonus-detail and dedicated sales-v2 exclusions retain
  their existing conditions. Print hides the new chrome. Failed shell-JS loading
  leaves expanded, readable navigation rather than an inaccessible mobile rail.

### Rollback / build

- `config/ui.php`: `MM_ADMIN_SHELL=true` by default. Set it to `false` and rebuild
  or clear Laravel's config cache (`php artisan config:clear`) to restore the
  legacy shell. Earlier opted-in page migrations remain enabled; this flag only
  controls the shared shell.
- `npm run ui:build` builds both CSS layers. `npm run ui:watch` watches component
  styles; `npm run ui:watch:shell` watches shell styles. No Node runtime is needed
  in production when the compiled files are deployed with the Blade changes.
- Browser checks: `npx playwright install --with-deps chromium`, then
  `npm run ui:browser`. `MM_CHROMIUM_PATH` optionally supplies an installed browser.
  Test output directories are ignored. CI now builds/checks both CSS outputs and
  runs the browser suite; the external CI job has not been run in this session.

### Verification and remaining acceptance

- PASS: production builds, all existing preservation checks, shell CSS isolation,
  header expressions/actions/permissions, dynamic sidebar includes and unchanged
  shared footer/scripts. `git diff --check` passes.
- PASS: 26 Blade templates compiled/token-parsed using PHP 8.1 WASM. Earlier
  render checks pass; new actual head/toolbar/navigation renders verify single
  asset links and shell-off omission. Actual layout flag expressions tested with
  shell on/off across 11 representative paths. Full DB-backed master/header/
  sidebar rendering is NOT covered by those standalone tests.
- PASS: eight Chromium browser tests against a representative local HTML fixture
  using real Bootstrap, Ace, shared custom CSS, compiled Tailwind and shell JS:
  360/768/1440px navigation/search, submenu compatibility, local preferences,
  focus handling, breakpoint/backdrop close, account dropdown, print, shell-off
  initialization, unavailable storage and shell-JS failure fallback. Fixture
  tests do not prove authenticated application flows or every module layout.
- Standard Playwright browser download was blocked by sandbox TLS/network access.
  Local tests used Chromium 141 from a temporary external `@sparticuz/chromium`
  installation with bundled runtime libraries. No browser binary or substitute
  runtime is included in the repository.
- Browser tests caught/fixed Ace mobile visibility and inherited input-transition
  conflicts. No live/test-site deployment, authentication, data writes, or real
  permission account matrix was performed. Before rollout, verify full menus
  for admin/restricted roles, company logos/long names/colour settings, populated
  notifications, logout, Chosen/DataTables resize, booking board/modals, and
  employee/POS/payroll print exclusions on staging. Existing npm audit reports
  13 dependency/toolchain vulnerabilities; no forced framework upgrades made.

## Eighth increment — hotel dashboard (2026-10-01)

- Added a shared page heading and responsive four-card summary for bookings,
  check-ins, check-outs and room counts. Today, last-day and seven-day expressions
  are unchanged; no new aggregation queries or financial calculations added.
- Explicitly explains that the existing ready-room figure is total minus booked,
  not verified housekeeping readiness. Added a titled booking-board section while
  preserving the existing visibility setting, shared board and scripts.
- Moved dashboard inline CSS into dashboard-scoped adapters, removing its global
  table-header and Chosen styling effects on other pages.
- PASS: production build, preservation/isolation checks, 28 Blade compilations,
  actual summary render, and 11 Chromium fixture tests (including dashboard at
  360/768/1440px). Sample dashboard fixture is available in the temporary preview;
  it is not the authenticated application and its sample numbers are not live.
- Pending staging: real counters, setting on/off, full booking board interaction,
  long values, role matrix and chart/plugin compatibility. No deployment or data
  writes performed. Existing hidden attendance section remains hidden.

## Ninth increment — housekeeping workspace (2026-10-01)

- Migrated the housekeeping page to the shared page/panel layout with guidance
  and an explicit permission-denied message. The existing component retains its
  own permission check. No additional permissions or routes are introduced.
- Room-category cards now wrap with consistent spacing; the existing status
  legend uses the available width and wraps on narrow screens. Status colours,
  calculation precedence, restricted booked/reserved controls and room contents
  remain in the unchanged room components.
- Moved page-level table/counter/select rules into the scoped housekeeping layer.
- PASS: build, selector and preservation checks, 29 Blade compilations and prior
  rendering tests. Guards verify page PHP and script sections plus byte identity
  of both housekeeping room components and the status-update script.
- The 11 browser fixtures cover the shell/dashboard, NOT housekeeping acceptance.
  Pending staging: actual category/room layouts, empty/denied roles, status dialog,
  cancel/save/errors, date/remarks payload and mobile room controls. The existing
  status-update implementation is intentionally unchanged; this update does not
  claim to fix its optimistic updates or backend error handling.
- No deployment or hotel data writes performed. Temporary preview remains the
  sample dashboard until an actual housekeeping fixture or staging is available.

## Tenth increment — booking purpose/platform CRUD group (2026-10-01)

List/filter/create/edit migrated together. Added field labels/error text, shared
panels and table overflow. Two deliberate navigation fixes: filter/reset retain
`type`; create Back List now returns to booking setup instead of companies.
Routes for writes, hidden rule values, validation requirements, CSRF/PUT and JS
remain unchanged. Build/contracts pass; 33 templates compile. Authenticated CRUD,
delete confirmation and both type-mode tests remain pending. Module-level status
and next groups are now tracked in `docs/MODULE-COMPLETION.md`.

## Eleventh increment — booking notes list/edit (2026-10-01)

Migrated list, filter presentation and edit form to shared components. Added
label associations, textarea focus styling and validation-message presentation.
Existing title expression, status toggle component/AJAX, update action and CSRF/
PUT remain unchanged. Build/contracts pass; 36 Blade templates compile.

NOT complete CRUD: inspected controller has guest-copy create/store/destroy
handlers (including Guest deletion and file deletion), and index queries `name`
while filter sends `title`. No new create/delete controls were exposed; those
handlers and the filter mismatch remain unchanged and need a separately tested
backend correction before claiming module completion. Staging title search,
update validation and status-toggle acceptance remain pending. No live writes.

## Twelfth increment — full navigation menu design (2026-10-01)

Shared shell CSS now styles every sidebar depth the same way: module icon tiles,
wrapped long labels, nested guide lines, hover/open/current states, no duplicate
Ace connectors and no module-specific icon rotation/colour. JS adds disclosure
`aria-expanded`/`aria-controls`, Space-key activation, a clear-search button,
and path+query current-page matching (Purpose and Platform no longer both mark
current). Sidebar Blade sources, links, permissions and route conditions are
unchanged; this only decorates rendered output.

Regression found and fixed during testing: shell CSS had disabled transitions on
all sidebar descendants. Ace releases its submenu lock on `transitionend`, so
after the first expansion later toggles were blocked. Only inputs/buttons/links
now disable transitions; Ace's height transition is left alone. A browser test
expands module then nested groups.

Verification: fixtures contain representative sample links for Hotel, Banquet,
Services, Restaurant, Bar, Store, Finance, Website, Global Setting and User
Access; 15 browser tests at 360/768/1440 pass. Real sidebar partials, permission
combinations, long production labels and non-Hotel module icon variants still
need authenticated staging acceptance. The menu is shell-flag controlled
(`MM_ADMIN_SHELL=false` rolls back).

## Thirteenth increment — room category create/edit (2026-10-01)

Both forms use the shared page/panel and room-form adapter. The inner form,
field names, old/current values, required attributes, status options, amenity
loop, guest-wise price rows, photo upload and pricing/photo scripts are preserved
(row ancestry and script file are guarded). Action buttons use shared button
styles and mobile-safe layout. 38 Blade templates compile.

Deliberate fixes: (1) create toolbar used `suppliers.view` (copy/paste) and now
uses the edit form's `hotel-categories.view`; (2) description validation error
displayed key `details` and now `description`; (3) duplicate `#capacity` ids on
can sleep/bed details/room size are unique, while Guest Capacity keeps
`#capacity` for the pricing script; (4) labels are associated with controls.

Found, not changed: edit hover "remove" icon on current photos has no click
handler, so removal is not implemented in the UI. Staging create/edit/upload,
guest-wise-price toggling and validation acceptance remain pending; no live writes.

## Fourteenth increment — dashboard completion (2026-10-01)

Dashboard header now shows the hotel business date and permission-gated shortcuts
(Booking list, Expected arrivals, Expected departures, In-house guests), reusing
the sidebar's route names and permission keys. The booking board sits in a shared
panel instead of the old dotted widget box. Counter expressions, board component,
room tile markup and dashboard JavaScript are unchanged and guarded.

Visual defects found with a new board fixture and fixed in `style.css`:
1. Bootstrap `.row::before/::after` became empty cells in the CSS-grid room list,
   shifting tiles right and leaving a blank first cell (dashboard and booking board).
2. The white `.room-booking-board .room-info` rule overrode dirty, maintenance,
   cart and booked tile colours, leaving white room numbers on white tiles.

Browser fixture now includes the board (toolbar, legend, categories, free, booked,
reserved, dirty, maintenance, due-today and selected tiles). Tests at 360/768/1440
cover no phantom grid cells, first-tile alignment, tile touch height, state colours
and no horizontal overflow; all 15 browser tests pass. Still needs staging with
real availability data, date-picker/AJAX selection, popovers, permissions and
non-default tile counts. Fixture markup is hand-built from the Blade components,
not rendered from the database.

## Fifteenth increment — dashboard room booking board redesign (2026-10-01)

The dashboard board (`home/_inc/booking_ui` → `room-board`, `room-card`, `bed-icon`)
replaces the dotted tile grid with collapsible category groups, room cards, a
per-type bed icon and a slide-in drawer for room details and booking. The shared
`x-room-manage` / `x-room-status` components still drive the Hotel and Banquet
booking pages and are unchanged.

- **Groups**: one section per category with free/total counts; the open/closed
  state persists in `localStorage` (`mm-board-collapsed`); Expand/Collapse all.
- **Cards**: room number, bed icon and label, rate and a state chip. States are
  derived from the same fields as before: available, in-house, booked, reserved,
  due today, plus Dirty/Maintenance. Cards are real buttons (no `.room-info`), so
  the legacy keyboard and click handlers cannot double-fire.
- **Bed icons**: single, double, twin, triple and multi, chosen from the category
  `bed_details` plus the room `beds` text, falling back to the bed count.
- **Drawer**: category facts, bed, capacity, size, smoking, rate and description;
  guest details for occupied rooms (written with `textContent` only); add/remove
  selection (same `add_booking` / `remove_booking_next` AJAX), check-in/open/migrate
  and housekeeping actions through the legacy `checkOut()` and `updateStatus()`.
  Focus trap, Esc, backdrop, `inert` background, focus returns to the card.
  Booking still submits the legacy `#booking-form` (`book` / `reserve`).
- Legacy hooks kept: `#booking-form`, `#searchForm`, `booking_availabe`, and a
  hidden `.room-info.mmb-proxy` per room so `updateStatus()` can toggle classes;
  a MutationObserver mirrors them to the card.

Verification: `tools/ui-blade-check.php` renders the real partials from sample
data (`tools/room-board-sample.php`, six categories, 23 rooms) and asserts groups,
cards, bed labels, states, escaping and hooks. Only `hasPermission`, `setting` and
`fdate` calls are substituted in a temp copy, because those helpers need an
authenticated user and database. `tools/fixtures/room-board.html` is generated from
that render (`MM_WRITE_FIXTURE=1`) and checked for drift. Browser tests at
360/768/1440 cover layout, bed icons, collapse persistence, drawer focus handling,
selection and submit, escaping, housekeeping mirroring, and 44px touch targets.
Drawer buttons were 40–42px and were raised to 44px.

Not verified: rendering through a real Laravel app with a database, real
availability data, date search, the guest popover replacement and
staging acceptance.

Follow-up (same day): the board's action bar (selection count and total, Reserve,
Book Now) is now sticky at the bottom of the viewport while the board is on screen,
and on 768px and wider the date search toolbar (dates, quick ranges, Check
Availability, Monthly Report, Expand/Collapse all) sticks under the header. Two
causes had defeated the first sticky attempt: the board panel had `overflow:
hidden`, and the bar sat inside `#booking-form`, which bounded how far it could
stick. The bar now sits after the form and its buttons submit it through the
`form="booking-form"` attribute. Buttons are 44px tall; on phones Reserve and Book
Now share one row. Not yet checked with a real browser session against staging.

## Sixteenth increment — shell header, footer and productivity tools (2026-10-01)

Shell-only (`$mmShell`; `MM_ADMIN_SHELL=false` restores the old chrome, including the
untouched `partials/_footer`). Employee, payroll-print and POS layouts are unchanged.

**Header** (`layouts/shell/header-tools`, included from `_header`; existing RST/BAR,
cache clear, notifications and account menu are kept):
- Search button and **Ctrl/⌘+K command palette**. It lists only screens already in the
  permission-filtered sidebar, plus actions (New booking, theme, compact spacing,
  sidebar, full screen, shortcuts) and the last five visited screens. Native
  `<dialog>`, combobox/listbox semantics, arrow keys, Enter, Esc, focus return.
- **New booking** button, gated by `bookings.create`, using `booking.create`.
- Dark/light theme toggle (beta), full-screen toggle (hidden if unsupported) and a
  keyboard shortcuts dialog. Shortcuts: `/` search, `?` help, `[` sidebar, `Esc`;
  single keys are ignored while typing or when a dialog is open.
- Header buttons are 44px (40px on phones). Phones hide the palette button because the
  header row has no room; the sidebar's own menu search remains.

**Toolbar**: breadcrumbs (Home / URL segments / page title) replace the "Hotel workspace"
badge. Intermediate crumbs are text, not links, because URL prefixes are not always pages.

**Footer** (`layouts/shell/footer`): copyright, optional version, developer link, help
link, business date, server clock (ticks in the browser from the server time), last sync
(page load or last successful AJAX response), online/offline status and an environment
badge. Configure in `config/ui.php`: `MM_APP_VERSION`, `MM_SUPPORT_URL`, `MM_UI_TIMEZONE`.

**Dark theme (beta)**: chrome, `.mm-ui` components, the room board and common legacy
widgets (tables, forms, widget boxes, modals, tabs, dropdowns). The preference is
per browser (`mm-theme`). Legacy pages with custom inline colours may still look wrong.

Verification: `tools/ui-blade-check.php` renders the real partials with frozen time and
writes `tools/fixtures/shell-*.html`; `tools/browser/chrome.spec.cjs` covers overflow at
360/768/1440, palette, shortcuts, theme and contrast, footer clock/offline, full screen
and no-dialog fallback. Two defects found by these tests were fixed (phone header
overflow; icon-only controls without accessible names).

Not included: a **language switch**. `resources/lang` has only `en` and no locale route,
so a switcher would do nothing. Also unverified: the real Laravel render with a database,
the real notification dropdown with data, and staging.

## Seventeenth increment: new-booking form (booking lifecycle step 2)

`booking_next` now uses `x-mm.page`/`x-mm.panel`, a display-only progress and stay summary
(`booking/_inc/_booking-next-steps.blade.php`) and a sticky Save/Reset bar. Form fields, expressions, includes, scripts and
`BookingController` are guarded as unchanged by `npm run ui:check`. The room table is intentionally not wrapped in a scroll
container because Chosen dropdowns would be clipped. Plan and risks: `docs/BOOKING-LIFECYCLE-PLAN.md`.
Tests: `tools/browser/booking-next.spec.cjs` (fixture rendered from the real partial; the full form is not rendered).

## Eighteenth increment: stay date range picker

`public/assets/custom_js/stay-range.js` now drives the board's `booking_date` range picker (dashboard and booking board):
- Past check-in dates are disabled, measured from the business date (`today_from_system()`, rendered as `data-business-date`),
  not the browser clock. `data-allow-past="1"` on the input opts out.
- A same-day pick becomes a one-night stay; typed text is validated (format, past dates) before the board form submits.
- Quick chips (Tonight, Next 7 days, Weekend) are bound by a delegated handler in this file. The earlier inline chip scripts
  checked for `moment` while the page was still rendering, but `moment` loads later in `@yield('script')`, so they never ran.
- Picker styling (touch-size cells, disabled days, 360px fit, dark theme) is in `resources/css/shell.css`.
Tests: `tools/browser/stay-range.spec.cjs`. Single-date `.date-picker` fields (bootstrap-datepicker) are unchanged.

## Nineteenth increment: booking create/edit and single-date fields

`booking/create` and `booking/edit` use the shared page frame, a sticky action bar (same `submitBookingForm()` buttons and classes)
and byte-guarded form regions, scripts, tfoot and controller/service. The date fields now share one rule set in
`public/assets/custom_js/stay-dates.js`:
- create: check-in cannot be before the business date (`data-business-date`, from `today_from_system()`); defaults use it too;
- edit: check-in is pre-filled with the booking's real check-in (it was pre-filled with today's date) and may be in the past
  (`data-allow-past="1"`); the existing "extend only" limit on check-out is kept;
- check-out is always after check-in: invalid values move to check-in + 1 night and fire `change`, so the legacy night and amount code
  sees only valid values; changing check-in recalculates nights the same way;
- `submitBookingForm()` is wrapped so an invalid stay is stopped with a message; date format attributes are unified to `yyyy-mm-dd`.
Tests: `tools/browser/booking-dates.spec.cjs` (real rendered partials; legacy night calculation and submit are stubbed).

## Twentieth increment: checkout and payment (`booking/view`)

The screen uses the shared page frame with Guest, Stay and Payment method panels, the charges table (byte-identical, in a scroll
wrapper) and a Payment summary panel. The old `.widget-header` / `.input-group input` page overrides, which leaked into other screens, are gone.
- Preserved exactly: the form tag, `@csrf`, every `{{ }}` expression, the `@php` block, the charges table with its hidden arrays,
  the `grand-*`, `payable-amount`, `current-due`, `#get-due`, `#discount`, `#paidAmount`, `#check-full-payment` hooks, and the whole
  `@section('js')` calculation script (`tools/ui-check.cjs` compares all of these with the previous version).
- Removed: four read-only display inputs with no `name` (guest, mobile, booking number, check-in) - shown as plain text from the same expressions.
- Tests: `tools/browser/booking-checkout.spec.cjs` runs the real rendered view (`tools/fixtures/booking-checkout.html`, 2 rooms x 2 nights, 5% service,
  10% VAT, 3000 paid, plus a restaurant charge): night +/- (3 nights = 13860 total, due 10860; never below 1 night), discount cap and warning,
  paid amount, full payment, form post field names, no overflow at 360/768/1280px.
- Legacy behaviour kept and not fixed: after any night change the script recomputes totals from rows that have a night counter only, so a
  non-room charge on the same invoice list (e.g. Restaurant) drops out of the grand totals and due amount; `calculateAmounts()` shows NaN on an empty field;
  only the first `.extra-charge` is read; `warning()` must exist as a global helper. These need a backend/product decision.

### Visual preview with several screens

`node tools/preview-server.cjs` (port 3000) serves the shell with sample data. Besides the dashboard it now serves
`/preview/checkout` and `/preview/category-create`, rendered from the real Blade views by `tools/ui-blade-check.php`
(`MM_WRITE_FIXTURE=1` writes `tools/fixtures/preview/*.html`), plus an index at `/preview`. Other URLs show a "not in this preview" page
and form posts only show a "nothing saved" note. It is a static sample, not the Laravel app.

## Twenty-first increment: booking invoices

Scope is the on-screen frame only. The printed documents, their expressions, `@php` calculations, styles and print scripts are unchanged
(`tools/ui-check.cjs` compares them with the previous commit).
- `checkout_invoice` (also used by the banquet module) and `reservation-invoice`: the old `widget-box` frame became the shared page frame
  with "Booking List" and a permission-gated "Print" button (same `printPage('print_body')`, which prints `#print_body` only).
  On narrow screens the A4-style sheet keeps a readable width and scrolls inside its panel.
- `checkout-invoice-v3` (the sheet opened after checkout, which auto-prints): a screen-only bar with "Booking List" and "Print again"; hidden in print.
- `checkout-invoice-v2`, `checkout-invoice-v4`, `get_invoice` have no route or controller reference. Not touched; candidates for removal after confirmation.
- Tests: `tools/browser/booking-invoice.spec.cjs` (real rendered `checkout_invoice`; printThis stubbed): auto-print once, Print button, print-media chrome hidden, no sideways page scroll at 360/768/1280px.
  v3 and `reservation-invoice` are compile-checked and guarded but not rendered with sample data. Real printing (paper size, fonts, the taka sign) needs a check on staging.
- Preview: `/preview/invoice`.

## Twenty-second increment: payment collection

`payment-collection/index` uses the shared page frame: a search panel (guest and company selects, Search/Reset), a guest information list,
the unpaid-invoices table (unchanged, in a scroll wrapper) and a payment summary. Guarded by `tools/ui-check.cjs` against the previous commit:
every `{{ }}` expression, the hidden arrays (`item_ids[]`, `item_types[]`, `total_amount[]`, `item_amount[]`, `previous_collection[]`, ...), form controls, `@php`
blocks, the invoices table and the whole script. Allowed differences: the duplicate Search/Reset pair became one pair, six nameless read-only guest
inputs became plain text, buttons use the shared class. The hidden discount row stays hidden (the script still reads it).
Tests: `tools/browser/payment-collection.spec.cjs` (paid amount, cap at the due amount, full payment, post field names, no overflow at 360/768/1280px). Preview: `/preview/payment-collection`.

Legacy issues kept, not fixed (they sit in expressions the guard protects):
- The company select marks an option selected when `request('company_id') == $guest->id`, a variable left over from the guest loop; it should compare with `$customer->id`.
- `$hotelGuest->booking` is read without `optional()`; with transactions found by company (no guest) this may fail on a null guest. Not verified without data.
- The guest fields print the literal `N\A` when empty.

## Twenty-third increment: night audit

- `night-audits/create-v2` (the generate form), `night-audits/index` (the list) moved onto `x-mm.page`, `x-mm.panel` and `x-mm.table-scroll`.
  The page-global `<style>` block (blue table headers, `.widget-header`, `.header-input`, `.footer-input`) was removed; the
  readonly figures are styled by the `.mm-night-audit` block in `resources/css/ui.css`, which also reuses the checkout and
  payment-collection card, field and summary classes.
- `night-audits/invoice` (the printed report behind `night-audits.show`) is untouched apart from a screen-only action bar
  (back to the list, print again) that is hidden in print.
- Preserved byte for byte: every field name, `#formSubmit`, the `.save-btn` overlay and 10 s delay script, the `$$name`
  variable-variable blocks and the transaction tables. `npm run ui:check` guards this against `45091d5a`.
- Tests: `tools/browser/night-audit.spec.cjs` (9) renders the real views through the php-wasm harness. It checks totals, readonly
  fields, the overlay, that nothing posts before 10 s, that all legacy fields post once afterwards, and no sideways scroll at
  360, 768 and 1280 px.
- Preview pages: `/preview/night-audit` and `/preview/night-audit-generate` (sample data).

## Twenty-fourth increment: hotel setup screens

- Migrated together: amenities (index, create, edit), account types (index, edit), VAT, currency conversions (index plus the
  create/edit fragments that the index loads), registration terms (index, filter, edit). All use `x-mm.page`, `x-mm.panel` and
  `x-mm.table-scroll`, and share the `.mm-hotel-setup` block in `resources/css/ui.css` (it reuses the booking setup form rules).
- Preserved: every field name, id, route, hidden `_method`, the `delete_check` forms and scripts, the currency fragment scripts
  and the data tables. `npm run ui:check` compares them with `22774024`.
- One view fix: the amenities create form had `method="get"` while its route is POST-only, so Save never reached `store`. It now
  posts. This is the only behaviour change in this increment.
- Left alone: `guest-registration-terms/create` is a stale copy of the guest form that posts to `guests.store` and is not linked from
  the list; the amenities store ignores the status select; the currency edit button validates through a function written for the
  create form.
- Tests: `tools/browser/hotel-setup.spec.cjs` (34) renders the real views through the php-wasm harness and checks the frame, no
  sideways scroll at 360 and 768 px, the POST payloads, the VAT radio group, the currency validation and the account type layout.
- Preview pages: `/preview/setup-*` (amenities, add amenity, account types, VAT, currency conversions, registration terms).

## Twenty-fifth increment: breadcrumb in a sticky footer, compact page title

- The top toolbar (`layouts/shell/toolbar.blade.php`: breadcrumb, date, density button) is removed. The business date was already
  in the footer status row; the "Compact spacing" button (same `#mm-density-toggle` id and behaviour) moved to the footer.
- The breadcrumb now lives in `layouts/shell/footer.blade.php` beside the copyright, links and status row. The footer is
  `position: fixed` to the bottom edge (left of it is the sidebar width) in two compact rows, about 54 px (47 px on phones, where the
  developer, help and keyboard links, server time and version are hidden).
- `shell.js` publishes the footer height as `--mm-footer-h`; the main content, the back-to-top button and the two sticky bottom bars
  (dashboard booking bar, `booking_next` actions) use it to stay clear of the footer.
- `x-mm.page`: the "MM Heritage Hotel" eyebrow is gone, the title is 20 px and the subtitle 13 px, and they share one line when
  there is room (title block about 25 px instead of about 90 px). Applies to every migrated screen.
- Tests: `chrome.spec.cjs` covers the fixed footer, the clearance variable and the compact title; `shell-toolbar.html` fixture removed.

## Twenty-sixth increment: hotel reports group

Fourteen report screens under `module/Hotel/views/hotel/reports/` moved from the legacy `widget-box` frame to `x-mm.page`, `x-mm.panel` and `x-mm.table-scroll`, scoped to `.mm-report` in `resources/css/ui.css`:

- `all-reports`, `cash-flow`, `expected-arrival`, `expected-departure`, `in-house-guest`, `room-logs`, `services`, `today-activities`, `today-check-in`, `today-check-out`, `today-in-house`, `vat-report-day`, `vat-report-monthly`, `night-closing/indexV2`.
- The table-based filter forms are now one flex filter bar. Field names, ids, placeholders, the `date-picker`/`time-picker`/`chosen-select` classes and the GET submit are unchanged. The Search button uses `mm-button`; the reset link got an `aria-label`.
- The results sit in a panel: the shared `export/excel` partial inside `x-mm.table-scroll`, then `x-paginate` and `x-export-button` as before. **The export partials are shared with the Excel/PDF export and were not touched**; the guard fails if they change.
- The blue `table thead th` and `.header-input`/`.footer-input` `@push('style')` blocks were removed; the equivalent rules live in `.mm-report`.
- Dead code removed: commented-out markup, an empty `widget-toolbar` guarded by `hasPermission('pharmacy.view')` (all-reports, cash-flow) and the empty filter form on `today-in-house`.
- `night-closing/details` (the per-day modal) is unchanged. `monthly/index`, `monthly/booking-ui` (calendar grids with `x-widget.*` inputs and a month picker) and `night-closing/invoice` (printable document) are **not migrated yet**.

Checks: `tools/ui-blade-check.php` renders every migrated view with its real export partial and sample rows (`tools/fixtures/hotel-reports/*.html`); `tools/browser/hotel-reports.spec.cjs` (49 tests: frame, no sideways scroll at 360/768, GET fields, room select, reset/export/pagination, today totals, night audit modal); `tools/ui-check.cjs` compares expressions, controls, directives, components, inline tables and scripts against `99d3c63b`. Preview pages: `/preview/report-*`.

## Twenty-seventh increment: remaining Hotel screens (Hotel module finish)

Every live Hotel screen that still used the legacy `widget-box` frame now uses `x-mm.page` / `x-mm.panel` / `x-mm.table-scroll`.

- **Guest SMS** (`guests/sms/index`, `.mm-hotel-sms`): three panels (mobile numbers tag input, message, SMS configuration) plus the actions row. Ids, names and classes the script reads are unchanged (`#form-field-tags`, `.multiple-phone-input`, `.message-area`, `.total-character-count`, `isFromGuestList`). One-attribute fix: the "SMS" part counter lacked the `part-count` class that `guests/include/script` updates. `guests/include/css.blade.php` was removed: its only user was this view and it printed stray CSS as visible text.
- **Night audit detail** (`night-audits/show`, `.mm-audit-show`): action bar (List, Print), a six-card summary grid and the transaction table with its totals. The table, `print()` and the `@page` A4 rule are unchanged.
- **Monthly room calendar** (`hotel/reports/monthly/index` and `booking-ui`, `.mm-report-monthly`): filter bar plus the calendar table inside a scroll region. The `bg-0`/`bg-2` cell colours, the hover popup and the month picker script are unchanged. The inline `<style>` block moved to `ui.css`.
- **Booking migration** (`booking/adjust/create`, `.mm-booking-adjust`): frame only. The JS-heavy parts (room picks, date row, available rooms, room table, totals footer, `_inc/_script`) keep their ids, classes and hidden inputs. The Search and Book Now buttons use `mm-button` but keep `#checkRoomStatus` and `.submit-form-btn`.
- **Printable documents** (`guests/invoice`, `hotel/reports/night-closing/invoice`): a screen-only action bar (back link, Print again) like the night audit invoice. The printed sheet is unchanged and the guard compares it against the previous version.
- Not migrated, on purpose: `booking-note/create` and `guest-registration-terms/create` (stale copies of the guest form that post to the guest store), `night-audits/index-details`, `create`, `create-v3`, `create-v4`, `hotel/reports/monthly.blade.php`, `night-closing/index`, `house-keeping/_inc/*` and `_css` (no route or include reaches them), and the Bootstrap modals under `booking/_modal`.

Checks: `tools/ui-blade-check.php` renders SMS, night audit detail, both monthly views and booking migration (`tools/fixtures/hotel-more/*.html`) and compiles both printable documents; `tools/browser/hotel-more.spec.cjs` (23 tests: frame, no sideways scroll at 360 and 768 px, SMS counter and POST, calendar colours and popup, migration ids and layout); `tools/ui-check.cjs` compares fields, expressions, tables and scripts against `22774024`. Preview pages: `/preview/hotel-{sms,night-audit-show,monthly,booking-adjust}`.

## Twenty-eighth increment: Hotel Service module

Every live Hotel Service screen under `module/HotelService/views/` now uses `x-mm.page` / `x-mm.panel` / `x-mm.table-scroll`.

- **Service list** (`services/category/index`, `.mm-hotel-setup .mm-hs-services`): the "Add New Service" link moved to the page actions (still `href="#modal-dialog" data-toggle="modal"`, still gated by `service.view`). The service table sits in a scroll region; the per-row edit modals and the add modal are the unchanged includes (`#modal-dialog`, `#modal-dialog{id}`, PUT for edits). Delete still calls `delete_item()`.
- **Sales list** (`services/sales/index`, `.mm-hotel-service`): the table-based filter is now a flex filter bar (field names `invoice_no` and `customer_id`, GET); results and totals row in a scroll region, then the paginator. The due-payment modal include and its `payment()` / `#payable-amount` script are unchanged.
- **New sale** (`services/sales/create`): three panels (guest and invoice, services, totals). Every id, name and hook the script uses is kept: `#invForm`, `#table_auto`, the `container` class on the tbody (`addItem()` appends to `.container`), `#subTotal`, `#discount`, `#payable_amount`, `#amountPaid`, `#amountDue`, `submitForm()`. The only additions are `for`/`id` pairs on labels and an `aria-label` on the add-row button.
- **Sale invoice** (`services/sales/show`): the same screen frame as the booking invoices; Back/Create/Print are page actions (hidden when printing) and `#print_body` is unchanged, including the legacy auto-print on load.
- **Night audit list** (`hotel-service-night-audits/index`, `.mm-report .mm-hs-audit`): the same filter bar and results panel as the booking night audit report. The `export/excel` partial and the `details` modal are unchanged.
- **Printable night audit** (`hotel-service-night-audits/invoice`): a screen-only bar (back link, Print again), hidden with `@media print`; the printed sheet is unchanged and the guard compares it against the previous version.
- Not migrated, on purpose (unreachable): `services/sales/edit` (the controller `edit()` returns nothing), `services/due-receive/*` (no route renders them), `hotel-service-night-audits/export/pdf` and `partials/sidebars/__sidebar_hotelservice`.

Legacy behaviour kept and worth knowing: the night audit list and `index` page error when there are no audits at all (`$nightaudits[0]` on an empty set, the same as the booking night audit); the new-sale form adds its first row on load; the invoice prints on open; `submitForm()` checks a `.today` element that does not exist (the date check never fires).

Checks: `tools/ui-blade-check.php` renders the six screens with sample data (`tools/fixtures/hotel-service/*.html`; preview pages `/preview/hservice-*`); `tools/browser/hotel-service.spec.cjs` (27 tests: frame, no sideways scroll at 360/768, modals, due-payment maths, sale calculation and POST body, print-only bar); `tools/ui-check.cjs` guards fields, expressions, directives, tables, scripts and that modals, export partials and unreachable views are untouched.
