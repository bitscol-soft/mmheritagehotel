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
