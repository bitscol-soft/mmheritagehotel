# Independent review — Frontend / UI redesign & development plan
**Reviewing:** `docs/UI-REDESIGN-PLAN.md` (Plan B — strategy / waves) and `docs/FRONTEND-DEV-PLAN.md` (execution companion / task board), in the context of `docs/PLAN-MODERNIZATION.md` (Plan A), `docs/BUGS.md`, the code on `arena/01a0f37d-mmheritagehotel` (PR #4, tip `2227b07a`, the branch this review was computed against) and a live probe of the test deployment `https://mmheritagehotel-test.dizihotel.com`.
**Reviewed:** 2026-10-01 · branch `arena/01a0f6f8-mmheritagehotel` · method: doc read-through + repo measurement + live-host asset/DOM probe (sandbox has no admin session, see §3.4).

---

## 1. Verdict in one paragraph

The plan is **unusually good for a UI plan**: it is evidence-based, it knows the difference between re-skinning and replatforming, it puts a design system before screens, and it has a genuinely safe operating rule (do not touch form names / ids / AJAX / route names / permission gates / money math) that is already proven by the ten shipped rounds. It is **not yet executable as written**, for four reasons that are all fixable and mostly cheap: (a) the tracker the docs point people at is not actually wired (issues #5–#13 are not in the "UI Renovation" milestone, and the docs claim the work lives on a PR into `AI` while PR #4 targets `main`); (b) the plan *invents* a component set without reconciling the **20+ components that already exist** in `resources/views/components/`, several of which are direct duplicates of planned ones (`x-no-table-record` vs `x-empty`, `x-widget.input-field` vs `x-field`, `x-widget.date-filter` vs `x-daterange`, `x-status` vs `x-badge`); (c) the real, observed deploy blocker is bigger than the plan states — the test box is serving roughly the **round-5 build**, so rounds 6–10 (including the round-10 reservation-invoice fix users are complaining about) have never reached it; and (d) Plan A and Plan B describe **two different design systems and two different brand directions for the same app**, with no ruling on which one wins. Fix those four things and the plan can be executed as written and produce exactly what it promises.

---

## 2. What the plan actually says (structure map)

| Doc | Role | Shape |
|---|---|---|
| `UI-REDESIGN-PLAN.md` | "Plan B" — the UI track. Audit → goals/non-goals → 3 tracks (T1 renovate in place, T2 Bootstrap 3→5.3, T3 Livewire/Vite) → design tokens + 12 `x-*` components → shell → archetypes → print programme → responsive/a11y floor → waves W0–W7 + T2/T3 gates → risks → DoD → next actions | Strategy, ~180 lines |
| `FRONTEND-DEV-PLAN.md` | Execution companion: state-of-play, W0–W4 as checkboxes, W4–W7 summary, verification workflow (php-wasm harness), backlog/decisions table (D1–D4, B1–B4), estimates, "I'm back, what do I do" checklist | Task board, ~110 lines |
| `PLAN-MODERNIZATION.md` | "Plan A" — whole platform: stabilize/secure → Laravel 12 + PHP 8.3 + Vite + Tailwind + Inertia/Vue → rebuild workflows → cross-cutting → public site → deploy | ~6-month programme |
| `BUGS.md` | 47 fixed + 12 open items; the safety net the UI plan leans on ("no behaviour change") | Register |
| GitHub | Milestone "UI Renovation" + issues **#5–#13** intended as the live task board | Tracker |

**Sequencing logic:** W0 unblock shipping → W1 design system → W2 shell → W3 Hotel + print → W4 banquet/services → W5 POS → W6 finance/admin → W7 public site, with T2/T3 optionally after 2 quiet sprints per module. That ordering (system → shell → highest-value module → modules by archetype) is the correct order for this codebase and should not be changed.

---

## 3. Verification ledger — claims checked against the repo and the live host

### 3.1 Claims that hold up

| Claim in the plan | Evidence (this branch) |
|---|---|
| ~973 Blade views | **974** `*.blade.php` outside `vendor/` (Hotel 173 · Account 178 · Restaurant 187 · Bar 109 · BanquetHall 78 · GS 37 · HotelWebsite 21 · HotelService 17 · Permission 13 · CRM 0 · core `resources/views` 161) |
| Bootstrap 3 + Ace/SmartAdmin theme, jQuery, no bundler for admin assets | `layouts/includes/head.blade.php` loads `assets/css/bootstrap.min.css`, `ace.min.css`, `ace-skins.min.css`; jQuery/ace scripts from `public/assets/js`; only a thin `webpack.mix.js` exists |
| `chosen` **and** `select2` side by side | 182 blade files reference `chosen-select`, 60 reference `select2` |
| `bootstrap-datepicker` **and** `daterangepicker` | 225 vs 16 blade files |
| Font-Awesome 4.5 (round 7b swept to FA4) | FA 4.5 local + FA **Pro 5 CDN** still linked in `head.blade.php` — the sweep reduced *usage*, the CDN link is still there (harmless, but the plan should say "remove the link" explicitly) |
| Hundreds of per-page `<style>` blocks as the drift source | **405** `<style>` tags across **364** views (Hotel 70, Account 83); 626 views use inline `style="…"` |
| Existing components it names (`x-alert-message`, `x-room-manage`, `x-room-status`) | ✔ all three exist; `x-alert-message` used in 82 views, `x-room-manage` in 3, `x-room-status` in 2 |
| Board is the only screen with a fluid CSS-grid + sticky footer + quick-range chips | `public/assets/custom_css/style.css` lines 601–926 (`@supports (display: grid)`, `.board-quick-dates`, `.board-footer`, `@media (prefers-reduced-motion)`) and `resources/views/components/room-manage.blade.php` (`data-mode="tonight|week|weekend"`, `#searchForm`, `#booking-form`) |
| `did-floating-*` pattern already in the repo | Present (3 `_css` partials) — promoting it to a token is realistic |
| Dead/duplicate invoice templates mislead design work | `booking/get_invoice.blade.php`, `checkout-invoice-v2/-v3/-v4.blade.php` all present and unreferenced by routes; banquet `booking_ui.blade.php` exists with **no route** |
| Print is improvised per document | `@page` rules appear in only ~5 views, with two of them commented out; 20 hard-coded `816/1056` canvas references remain |
| Deploy is manual, workflows have 0 runs | `.github/workflows/deploy-{staging,production}.yml` exist; `gh run list` is **empty** |
| The round-10 defect class (`@include` emitting `<style>` inside `<style>`) is real and worth a mechanical guard | No instance survives on this branch (§3.2), which is itself evidence the round-10 fix landed in code |

### 3.2 Claims that are imprecise, stale or wrong

| # | Claim | Reality | Impact |
|---|---|---|---|
| C1 | "13 active-ish modules (`Hotel`, `BanquetHall`, `Restaurant`, `Bar`, `GeneralStore`, `Merchandising`, `Knitting&Dyeing`, `Account`, `CRM`, `HRM`, `HotelService`, `Permission`, `HotelWebsite`)" | **10 module directories exist.** `Merchandising`, `Knitting&Dyeing` and `HRM` have no code at all (they exist only as `modules` DB rows / stray references). `CRM` is a minimal code restoration (models + stub service) with **no UI** | W6's "HRM payslip" task is void or must become "hide/remove HRM surfaces"; W5 cannot include Merchandising/Knitting screens |
| C2 | W3.8 delete `checkout-invoice-v2`, `-v4` | `checkout-invoice-v3.blade.php` also exists **and was edited in round 8** ("v3 invoice canvas fix") | The delete list is wrong; deleting v3 would discard round-8 work that may still be reachable |
| C3 | W1.3: extract `x-daterange` "from the proven booking-list filter (rounds 4/5/10)", reusing the `apply → #searchForm submit` flow | `#searchForm` and the quick-date chips live in the **board** component (`x-room-manage` used by `booking_ui`); the booking **list** filter is a separate 210-line partial `booking/_inc/_filter.blade.php` (`booking-filter-panel`, `chosen-select`, 4 `date-picker` inputs) with **no** quick chips | Two different extraction sources; the component spec must name both and the prop surface will differ |
| C4 | "Board = reference implementation … flows to every other screen archetype" | The board is a **bespoke, screen-specific component** (`x-room-manage`: booking pricing, `hasPermission('bookings.create')`, `#booking-form`), and its styling is a page-scoped block inside the global `style.css` (`.room-booking-board …`). It is a good *look*, not a reusable primitive set | W1.7 ("wrap board in `x-page`/`x-toolbar`, must be a visual no-op") is riskier than described: the toolbar/legend/footer live inside a booking-logic component and must be carved out without touching its submit flow |
| C5 | "Every wave lands with … php-wasm harness render checks" | The harness recipe lives only in prose; `tools/` contains **`verify_fixes.sh` only** — no `rawlint`, no `ui-guard.sh`, no harness script. A fresh sandbox must rebuild it from scratch each time (the docs admit this: "it gets wiped") | Verification cost is recurring and unbudgeted; W0.4's guard script must actually be written, and the harness should be committed as a script + a fixture DB step |
| C6 | FRONTEND-DEV-PLAN §0: work branch "`arena/01a0f37d-mmheritagehotel` → PR **#4** into `AI`" | PR #4's `baseRefName` is **`main`** (verified via `gh pr view 4`). The `AI` branch exists but is not the PR target | Anyone following the doc opens/merges against the wrong branch, or believes `AI` is still the integration branch |
| C7 | Tracker = "GitHub milestone 'UI Renovation', waves are issues #5–#12, decisions #13" | The milestone exists but has **0 issues**; #5–#13 have **no milestone and no labels** | The task board the docs tell a returning developer to trust is empty; progress is invisible |
| C8 | `--mm-muted #7a8a99` "retire below 12px" | `#7a8a99` is used **inside** the board block for exactly the 10.5–13px meta text the plan wants to fix — i.e. the token already equals the offending value | Keep the colour for large text; the rule needs to be "meta text uses `--mm-ink-soft`", not a token swap |
| C9 | Status map (free/reserved/booked/in-house/due-today/dirty/maintenance) | Live hooks are class names, not tokens: `board-free`, `reservation` (#9abc32), `booked` (**defined twice — `#D15B47` then overridden `#d278de !important`**), `label-danger`/`check_in` (#F89406), `today-checkout` (#1e6b99), `inverse` (#090613), `orange` (#9585BF), `success` (#82AF6F). These classes are referenced in **46 (`reservation`), 44 (`inverse`), 16 (`room-info`), 10 (`today-checkout`)** blade files | The plan's map is semantically right but must be declared as *class-preserving aliases*; renaming any of these (e.g. to `mm-status-booked`) is a 100+ file visual regression with no compile-time warning |
| C10 | Plan B §3.2 "no Tailwind forced onto every view"; Plan A §2.2 builds a **Vue 3 + Tailwind** component kit (`DataTable`, `FilterBar`, `StatusChip`, `Stepper`, `MoneyInput`, `PrintFrame`) | Two parallel component kits for the same screens, with different lifecycles | Whichever lands second is dead weight; needs an explicit ruling (see §6, G6) |
| C11 | Plan B tokens use brand **navy `#2f63a8`**; Plan A §2.1 "brand deep-green/gold from hotel identity" | Conflicting brand direction; the public site today is a third identity (brown/gold photographic theme, `frontend/assets`) | One brand decision must precede W1 tokens or W1 is wasted |
| C12 | W2.1 sidebar collapse "persisted per user" via `localStorage` | `localStorage` is per browser, not per user; the users table has no preference column (only 6 `localStorage` accesses exist in the whole app today) | Either say "persisted per browser" or add a tiny `user_preferences` mechanism (or a cookie) — the current wording will not match behaviour |
| C13 | W2.2 global search "wire … to existing `searchRoomByNumberAjax`-style endpoints" | No such method exists; hotel AJAX routes are `room_by_search_category/{id}`, `check-available-room`, `get-available-room`, `booking-search-by-date` — nothing that searches bookings/guests globally | Per the plan's own fallback ("else omit") the global search field should be dropped from W2 and kept in the parked B2 bucket |
| C14 | Plan B audit: "Two autocomplete plugins; two date pickers; three modal flavours" | True, and more: the **public site** runs its own jQuery/Bootstrap stack (`resources/views/frontend/*`, `frontend/assets`) with its own cart that displays **`৳` (BDT)** on the Malaysian test box | Public/admin double stack should be costed; the currency symbol is a live data bug worth a one-liner in `BUGS.md` |

### 3.3 Numbers the plan should quote instead (for the DoD burn-down)

| Metric | Today (branch tip) |
|---|---|
| Blade views | 974 |
| Views with a `<style>` block | 364 (405 tags) |
| Views with inline `style="…"` | 626 |
| `class="table…"` occurrences | 755 (66 files initialise `#data-table` DataTables; 59 use `id="data-table"`; 37 use `x-paginate`) |
| `col-md-*` occurrences (fixed-canvas evidence) | 1,865 |
| Hard-coded `816/1056` print canvases | 20 references |
| Permission gates (`hasPermission(`) that must survive byte-identical | 585 |
| `chosen-select` / `select2` files | 182 / 60 |
| Existing `x-*` components | 24 files (incl. 12 in `components/widget/`) |

### 3.4 Live test-deployment probe — the finding the plan under-states

Sandbox egress only reaches the internet through the platform fetch proxy, so I could read public assets and pages but **not POST the login form**. Public evidence, gathered 2026-10-01:

1. `GET /assets/custom_css/tokens.css` → **404** — W1 has not been deployed anywhere (expected: W1 hasn't started).
2. `GET /assets/custom_css/style.css` → the served file **ends with the round-4 board block followed by the round-5 "at a glance strip" block** (`.board-stay-strip`, `.booking-context`, `.room-config-table`). It does **not** contain `.room-booking-board .board-quick-dates`, `.board-report`, the `@supports (display: grid)` block, `.board-booked-tag`, `.board-hint`, or the `@media (prefers-reduced-motion)` block — all of which exist locally (lines 695–926). **The test box is serving roughly the round-5 build.**
3. `.board-quick-dates` is round 4/9 work; its absence plus the missing round-9 responsive/grid block means **rounds 6, 7, 7b, 8, 9 and 10 are not on the test box** — including round 10, the reservation-invoice design fix that the whole W3.1/W0.3 argument is built on.
4. `gh run list` is empty: no deploy workflow has ever run, consistent with the docs.
5. Public homepage is alive but shows the legacy stack and a **`৳` (BDT) cart total** on a Malaysian property; the login page is the vintage SmartERP/Banglafire screen (W6 territory).

**Consequence for the plan:** W0 is not only "wire secrets so future waves ship". Step one must be **"sync the box to the branch tip and verify rounds 6–10 are actually live"** — otherwise W0.3 ("confirm the round-10 fix renders clean on the test box") fails on day one and the team keeps re-debugging a defect that is already fixed in git. Also add a **cache-busting/version check** to the deploy's health step (the layout already versions `style.css?v=20261001`; assert the served file contains a known marker).

---

## 4. What the plan gets right (keep, don't re-litigate)

1. **Design system before screens.** Correct for 974 views and 405 stylesheet blocks; retro-fitting tokens per screen would cost 3× as much.
2. **The four standing rules** (byte-identical hooks, no money math, CSS only in sanctioned files, no `<style>`-emitting include inside `<style>`) are the single most valuable thing in the document. They are what let a UI track touch a live ERP. Make them CI-enforced, not tribal (W0.4).
3. **Archetype-first waves + module-complete rollout.** Avoids the "half-styled module" period, which is the usual failure mode of long UI renovations.
4. **Print programme before the shell (W3, not W2).** Correct prioritisation: invoices/slips are the staff-visible artefact, and the round-6 sheet pattern already exists to generalise.
5. **T1/T2/T3 gating** (replatform only after 2 quiet sprints per module) is the right risk posture for a codebase with no tests on views.
6. **Honest about deploy friction** in the risk register and about the two-look overlap risk.

---

## 5. Where the plan is weak (ranked by cost if ignored)

| Rank | Weakness | Why it bites | Cheap fix |
|---|---|---|---|
| 1 | **Deploy reality understated** (§3.4) | The plan's own W0 exit ("fixes ship same-day") is unachievable this week, and every wave's value is deferred | Add W0.0 "box sync + round 6–10 verification + served-asset marker check" with the branch tip named |
| 2 | **Component collision with the existing 24 components** | You get three generations of components (legacy `widget/*`, rounds' `room-*`, plan's `x-*`) and reviewers can't enforce anything | Add W1.0 "component inventory & inheritance map": every new `x-*` declares its legacy counterpart and the deprecation path; `x-empty` wraps `x-no-table-record` (55 users), `x-field` wraps `x-widget.input-field` (24 users), `x-daterange` wraps `x-widget.date-filter`, `x-badge` preserves `label label-xs reservation|booked|inverse|orange|today-checkout`, `x-data-table` must keep `id="data-table"` (59 users) and the `x-no-table-record` `<tr>` contract |
| 3 | **Plan A vs Plan B design-system collision + brand conflict** (C10/C11) | Two kits, two palettes; whichever ships second is waste, and tokens may be rebuilt twice | One-page ruling: for the admin UI, Plan B (Bootstrap-3-compatible tokens + Blade components) is the near-term source of truth; Plan A's Vue/Tailwind kit is the *post-upgrade* target and must not start before T2/T3 is approved; brand colour chosen by the owner before W1.1 |
| 4 | **Issue tracker not wired** (C6/C7) | The plan's own resume instructions ("read the milestone, tick boxes there") point at an empty board | Attach #5–#13 to the milestone, add labels (`W0…W7`, `ui`, `decision`), correct the `AI`→`main` statement |
| 5 | **Verification harness is prose, not code** (C5) | Every fresh environment pays the discovery cost again; "harness green" is not reproducible by a reviewer | Commit `tools/render-check.php` + `tools/rawlint.php` + `tools/ui-guard.sh` + a `docs/HARNESS.md` quickstart in W0.4 (they're small) |
| 6 | **Optimistic estimates with no verification budget** | W1 "1–2 wk" = 12 components + gallery route + 3 converted screens + unable-to-be-automated visual verification; W3 "Hotel module zero bespoke `<style>` > 20 lines" covers 173 views / 70 style tags | Split W1 into W1a (tokens + 5 primitives) / W1b (rest); state harness/screenshot overhead as ~20–25% inside every wave; make W3 a burn-down (70 → 0 style tags) with a per-archetype exit, not a module-wide one |
| 7 | **Accessibility is asserted, not measured** | "WCAG-AA minimums" with no automated gate will regress on day two | Add `axe`/Lighthouse CI checks (shell + 3 archetype pages) to W2's exit criteria, plus a keyboard-only script per archetype |
| 8 | **Missing non-visual requirements** | CSS weight budget (FA4 + FA5 Pro CDN + chosen + select2 + datepicker + daterange + per-page styles), browser/device matrix (front desk tablets vs managers' phones), i18n (Plan A promises EN/MS; Plan B silent), thermal-printer verification for the 80 mm kitchen ticket (hardware, not CSS), rollback strategy for waves that change global CSS | Add a "non-visual requirements" section to Plan B §7; the thermal ticket needs a real printer test or an explicit "printed at 288 px PDF, verified as PDF" note |
| 9 | **DoD contradiction** | "Zero page-own `<style>` blocks" cannot coexist with the repo's sanctioned `_inc/_css/*.blade.php` partials (the round-6/10 print work *is* a `<style>` include) | Amend DoD: "no `<style>` **inside a page view**; presentation partials (`_css/*`) are component-owned and allowed; global file only grows via tokens/components" |
| 10 | **No explicit definition of "no-op"** | W1.7 says the board conversion "must be visually a no-op" but gives no tolerance | Define it: identical computed styles for the 8 status tiles + identical DOM ids/names, verified by the harness hook-diff and a pixel-diff tolerance (e.g. ≤0.2% differing pixels) |

---

## 6. Recommended revisions (concrete, in order)

**Revised W0 — make it true before making it pretty**
1. **W0.0 (new)** Sync the test box to the branch tip; verify a served-asset marker (`style.css` contains `.board-quick-dates`) and that `reservation-invoice` renders without raw CSS text. Record the deploy method actually used in `docs/DEPLOYMENT.md`.
2. **W0.1/W0.2** as written (secrets + dry-run) — owner task, not agent task; don't let W1 wait on it.
3. **W0.4** commit `rawlint`, `ui-guard.sh`, `render-check.php`, `HARNESS.md`; wire `ui-guard.yml`.
4. **W0.5 (new)** Attach issues to the milestone, labels, and fix the `AI`/`main` / "13 modules" / "v2+v4" statements in the two plan docs (this review supplies the corrected facts).

**Revised W1 — reconcile before you create**
5. **W1.0 (new)** Component inheritance map (existing 24 → planned 12; who wraps whom; what stays).
6. **W1.1** `tokens.css`, with the status map expressed as **aliases to the existing class hooks**, and `--mm-muted` reserved for ≥12 px text.
7. **W1.2–W1.6** as written, in this order: `x-page`, `x-field`/`x-select`/`x-daterange`, `x-filter-bar`, `x-data-table` (keeping `id="data-table"` + `x-no-table-record` DOM), then chip/badge/modal/empty/stepper/print-sheet/tile.
8. **W1.7** proving screens: prefer **three cheap, low-risk** conversions over the board: guests list (already partly filtered), booking list (`_inc/_filter` is already a partial), and the login page (public, zero auth-dependent JS). Move the board conversion to W3 once `x-toolbar`/`x-tile` are stable.

**Waves W3–W6:** unchanged in order; add per-archetype exit (a metric burn-down) instead of module-wide thresholds; keep the print programme first inside W3.

**Estimate revision (1 designer-dev + part-time reviewer, excluding W7/T2/T3):** W0 1–2 d (mostly waiting on owner access) · W1 2–3 wk (was 1–2) · W2 2 wk · W3 3–4 wk · W4 1–1.5 wk · W5 3 wk · W6 3 wk → **≈13–17 weeks**, i.e. Plan B is a **quarter**, not "a few weeks". Plan A's 5–6 month programme with the same screens rebuilt twice (Blade components, then Vue components) is the number that should be reconciled with the owner before W1 starts.

---

## 7. Decisions the owner must make (blocking or near-blocking)

| # | Decision | Blocks | Reviewer's recommendation |
|---|---|---|---|
| D-A | Is the admin UI going **Bootstrap-3-Blade (T1)** for the next 2 quarters, or straight to **BS5/Vue (T2/T3)**? | W1 keystone; everything after | T1 now, T2 gated as written — the app is live and has no view tests |
| D-B | Brand direction: navy `#2f63a8` (Plan B) vs deep-green/gold (Plan A) vs current public-site brown/gold | W1.1 tokens, W7 | Pick the public site's palette as brand, use navy as the *functional* accent (links/actions) — avoids a two-brand mistake |
| D-C | D1 dead templates: delete or `@deprecated`-annotate `get_invoice`, `checkout-invoice-v2/-v3/-v4`, banquet `booking_ui` (+ unrouted `available()`) | W3.8 | Annotate + remove routes first, delete after one sprint of no complaints (keeps round-8's v3 work recoverable in history) |
| D-D | D2 autocomplete: adopt Tom Select (static vendor) or standardise on select2 (already on 60 views, introduced later than chosen) | W1.5 | Tom Select for *new* code, as proposed; do not sweep existing pages in W1 |
| D-E | Thermal kitchen printer model / paper width available for testing | W5 kitchen ticket | Required input; otherwise the 80 mm variant ships unverified |
| D-F | Test-box credentials for a scripted admin smoke test (the sandbox cannot POST login) | every wave's verification | Provide a read-only staff account or allow the harness to run on a server with SSH |

---

## 8. Additions to the definition of done (measurable)

Append to Plan B §10: pixel-diff tolerance defined for "no-op" conversions (≤0.2%); `id="data-table"`/`x-no-table-record`/status-class hooks preserved in the hook-diff report; axe/Lighthouse a11y result recorded for each archetype reference screen; CSS weight delta recorded per wave (`style.css` bytes + per-page style bytes, target: monotonically decreasing); served-asset marker verified after deploy; every new component documented in the `/ui-kit` gallery with its legacy counterpart.

---

## 9. Bottom line

- **Plan quality:** high. Strategy, guardrails and ordering are sound and the risk register is honest. The plan is worth executing.
- **Readiness:** blocked on four cheap fixes — tracker wiring, component-inheritance reconciliation, a real box sync, and a Plan A/B brand-and-stack ruling.
- **Biggest blind spot:** the plan treats deployment as plumbing while the evidence says the test environment is **five rounds behind**, so the flagship "fix users can see" (reservation invoice) is not on the box the plan asks you to verify against.
- **Biggest scope risk:** two design systems (Plan B Blade/BS3 vs Plan A Vue/Tailwind) and 24 pre-existing components the plan does not acknowledge; unmitigated this triples the review surface.
- **Realistic horizon with one designer-dev:** W0–W6 ≈ a quarter; add W7 for the public site, T2/T3 only after two quiet sprints per module.
