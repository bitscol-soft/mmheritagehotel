# Plan B — Complete UI Redesign / Renovation Plan
### Every screen of `mmheritagehotel`, made modern, consistent, responsive and print-ready
Target repo: `bitscol-soft/mmheritagehotel` · Prepared 2026-10-01
Companion to: `docs/PLAN-MODERNIZATION.md` (Plan A — whole-platform strategy). This document is the **UI track**: what the user sees, in what order, with what guardrails. Platform/security items stay in Plan A.

---

## 0. Why a plan is needed (honest audit of today's UI)

**Scale of the surface (verified by repo scan):**
- 13 active-ish modules (`Hotel`, `BanquetHall`, `Restaurant`, `Bar`, `GeneralStore`, `Merchandising`, `Knitting&Dyeing`, `Account`, `CRM`, `HRM`, `HotelService`, `Permission`, `HotelWebsite` …), ~1,000 routes, **973 Blade views**.
- Stack: Bootstrap 3 + SmartAdmin "ace" theme, jQuery, `chosen` **and** `select2` side by side, `bootstrap-datepicker` **and** `daterangepicker`, Font-Awesome 4.5, no bundler (static files in `public/assets/`), ~150 lines of global CSS in `public/assets/custom_css/style.css` plus **hundreds of per-page `<style>` blocks**.

**Design debt (concrete, found while working in rounds 1–10):**
1. **No shared design language.** Same concept (room status, amount, action) is styled 5+ ways; two autocomplete plugins; two date pickers; three modal flavours.
2. **Inline `<style>` per page** → copy-paste drift and subtle breakage classes: the round-10 incident (a style-emitting `@include` nested inside `<style>` leaked ~200 lines of raw CSS onto the reservation invoice) was only possible because of this habit. 973-file sweep found 1 instance — the pattern itself is the risk.
3. **Fixed-canvas documents.** Invoice sheets hardcode 816×1056 canvases (round-8 had to turn one into `min-height` because long invoices were silently clipped).
4. **Zero responsive intent on core screens.** Board tiles were fixed `col-md-1` (12 micro-tiles on desktop, 2 on phones); tables don't adapt; filter toolbars collapse arbitrarily. Admin screens are used on the front desk *and* on phones by managers.
5. **Dead/duplicate views** (`get_invoice`, `checkout-invoice-v2/v4`, banquet `booking_ui`/`available` unrouted twins) mislead design work and rot.
6. **Print = accident.** Only `checkout_invoice` got the `invoice-doc` sheet treatment (round 6); reservation invoices, slips, payslips and finance reports each improvise.
7. **A11y is near zero** (no focus management, low-contrast greys, click-only tiles). Some of this was patched per-screen (round-3 keyboard access, focus rings) but nothing is systematic.

**What already works (keep and extend):** the booking **board** (`/hotel/booking-ui`) was redesigned in rounds 4/5/9 with a fluid CSS-grid, card tiles, soft filter panel, quick-range chips, sticky action bar — it is now the **reference implementation** for every other screen archetype. Same pattern flows to BanquetHall through the shared component.

---

## 1. Goals / Non-goals

**Goals**
- G1 — One design system: tokens + a component inventory every module reuses.
- G2 — Every admin screen usable at 360px and at 1920px without horizontal scrolling of chrome.
- G3 — Print output that is designed (A4 sheets, page breaks, multi-page safe), not cropped HTML.
- G4 — Zero behaviour risk: **no form-name, AJAX-endpoint, route-name, permission-gate or money-math changes in any UI wave.** (Proven process: 10 rounds shipped under exactly this rule.)
- G5 — Screen-by-screen visual coherence; a staff member can move between Hotel, Restaurant and Accounts without relearning the interface.

**Non-goals (this track)**
- No Laravel upgrade, no backend re-architecture (Plan A phases 1–3 own those).
- No SPA rewrite. No Tailwind utility migration *forced* onto every view — utility layer optional per module.
- No public-website (HotelWebsite landing) redesign inside the admin waves — it gets its own later phase (W7) because conversion/branding is a different job.

---

## 2. Strategy — three tracks, ordered by risk

| Track | What | Risk | When |
|---|---|---|---|
| **T1 · Renovate in place** | Design-token CSS layer + component partials on top of current Bootstrap 3/jQuery stack. Each screen re-skinned by swapping to the shared partials. | Low — pure view/CSS changes, per-screen rollback | Waves W1–W5 (now) |
| **T2 · Replatform CSS** | One-time move from Bootstrap 3 to Bootstrap 5.3 (or Tailwind + preflight) via codemod (`col-md-`→`col-md-`, `panel`→`card`, drop `float` grids, new grid/flex utilities). Keep every Blade hook/selector. | Medium — needs a screenshot-diff QA pass per module | W6, only if T1 demand outgrows BS3 quirks |
| **T3 · Replatform JS** | Table-heavy + wizard screens move to Livewire components (no API layer needed, keeps Blade). Adopt Vite bundle for the new layer only. | High-ish — behaviour surface grows | W7+, aligned with Plan A phase 2 |

Decision gate: start T2/T3 per module only after that module is stable in T1 for 2 sprints with no visual bug reports.

---

## 3. Design system (the core deliverable of W1)

### 3.1 Tokens — one source of truth
New `public/assets/custom_css/tokens.css` (loaded *before* `style.css`; both stay static files — no bundler required):

```
--mm-brand        #2f63a8   (navy — already the de-facto accent in board work)
--mm-brand-600    #275188   pressed / links hover
--mm-ink          #1f2a33   primary text          (contrast-safe on #fff)
--mm-ink-soft     #37536a   secondary text
--mm-muted        #7a8a99   labels, meta          (retire #7a8a99 below 12px — AA gate)
--mm-line         #dbe5f1   borders
--mm-bg           #f7fafd   page/canvas background
--mm-surface      #fff
--mm-ok  #82af6f   --mm-warn #ffae00   --mm-danger #c24a48/#d15b47   --mm-info #4d8cb3
--mm-radius 6px (inputs) / 8px (cards/tiles) / 999px (chips)
--mm-space 4/8/12/16/24/32 (scale only; no 13px margins)
--mm-shadow-1 0 1px 2px rgba(51,71,92,.06);  --mm-shadow-2 0 4px 12px rgba(47,99,168,.16)
--mm-font  system-ui / 'Lato' (body) — one family decision; drop per-page Calistoga/Fira Sans mixes into token `--mm-font-display` (headings, invoice titles only)
status map (board legend = global): free #82af6f · reserved #9abc32 · booked #d278de · in-house #d15b47 · due-today #1e6b99 · dirty #090613 · maintenance #9585BF
```

### 3.2 Component inventory — `x-*` Blade components (the only sanctioned way forward)
Already exist: `x-alert-message`, `x-room-manage`, `x-room-status`. Add (each ~1 file, each rendering the *current* DOM hooks where JS needs them):

| Component | Replaces | Notes |
|---|---|---|
| `x-page` (widget-box wrapper) | 500× `<div class="widget-box">` | header slot + toolbar slot + body; density token |
| `x-data-table` | every `<table class="table">` list | sticky thead, row-hover, `.table-empty` state, optional zebra, **priority columns** collapse at ≤767px (`data-priority` attr), sortable hook reserved |
| `x-filter-bar` | the ad-hoc filter rows | one variant of the round-4 `booking-filter-panel` (pill fields, from/to dates, clear-all) |
| `x-field` | 1,000+ input groups | floating-label pattern (`did-floating-*` already in repo → promote to token), inline error, help slot |
| `x-select` | chosen **and** select2 | keep `chosen-select` class + JS contract, but one plugin chosen for new code: **Tom Select** (light, no-jQuery dep) — legacy pages keep chosen until touched |
| `x-daterange` | both date pickers | wraps existing daterangepicker + `apply→submit` flow used by board quick chips |
| `x-chip` / `x-badge` | `label label-xs arrowed…` | status map from §3.1 |
| `x-modal` | bootstrap modals + "ace" modals | focus-trap + ESC + restore-focus; sizes s/m/l |
| `x-empty` | "no data" tables/sections | icon + copy + primary action slot |
| `x-print-sheet` | every invoice/slip | **generalizes round-6 `invoice-doc`**: A4 (816px `min-height`, never fixed), @page rules, chrome-hidden print CSS, letterhead slot, totals block slot. Amount markup rendered by *existing* PHP expressions — component never computes |
| `x-toolbar` | board-toolbar / page headers | title + actions + quick-chips |
| `x-stepper` | booking_next + adjust flows | 3-step horizontal (search → details → pay), mobile-vertical |

### 3.3 Rules of engagement (enforced in review)
1. Never edit behaviour while restyling: form `name`s, `id` hooks used by JS, AJAX URLs, route names, `hasPermission(...)` gates survive **byte-identical** (10-round precedent + grep-based diff audit).
2. No money math in any UI PR — view-layer diffs must be provably CSS/markup only (the `Due`-column quirk stays in `docs/BUGS.md`, deliberately unfixed).
3. No `@include` that emits `<style>` inside another `<style>` (the round-10 class of bug; codify in review checklist).
4. New CSS only in `tokens.css`/component partials — per-page `<style>` blocks may be *removed* by waves, not added.
5. Every wave lands with: rawlint (token-balance) pass, php-wasm harness render checks of touched routes, before/after screenshot pair per screen family.

---

## 4. Layout shell (W2)

- **Sidebar**: keep SlimScroll behaviour; add 240px→68px icon-rail collapse (persisted per user), current-item pill, section counters for booking-critical items (arrivals/departures/due).
- **Topbar**: global search field (bookings/guests — hits existing `ajax` search endpoints), quick-add (guest/booking/expense), today's-date button jumping to `/hotel/booking-ui?booking_date=today - tomorrow`, notifications bell wired to existing alert service.
- **Page header**: unified via `x-page`: title + breadcrumb + right-aligned action group. Retire 2-line "page-header + widget-header" duplication.
- **Content width**: fluid container (max 1440px) — kills the fixed-width look on wide monitors; tables get horizontal scroll containers instead of page scroll.
- **Mobile**: bottom **action bar** for sticky footers (the board footer pattern generalized); no hamburger-only navigation for primary modules (rail + swipe drawer).

---

## 5. Screen archetypes (renovate by type, not by screen)

Each archetype gets ONE renovated reference screen, then bulk-apply via the components:

| Archetype | Reference | Then applies to |
|---|---|---|
| **Board/grid work surface** | ✅ already: `/hotel/booking-ui` (rounds 4/5/9) | housekeeping board, banquet hall board, restaurant tables, GS stock count |
| **Filter + list + bulk bar** | booking list `booking/index` | guests, transactions, vouchers, sales lists, purchase lists, all `*_index` tables (~120 screens) |
| **Record sheet (view)** | booking `view` (round-5 readonly pattern) | guest profile, invoice view, item view, voucher view |
| **Multi-tab create/edit** | booking create/edit (`booking_next` + `x-stepper`) | sale/purchase create, banquet booking create, item create |
| **Print documents** | `checkout_invoice` (`invoice-doc`) — extend to reservation invoice next (see §6) | slips, payslips, VAT reports, statements |
| **Dashboards** | new `x-tile` grid (reuse board grid) | main dashboard, night-audit summary, today's activities, report landing |
| **Settings forms** | `x-field` + section cards | system settings, account setups, permission assignment |

---

## 6. Print & documents programme (W3 — highest staff-perceived value after the board)

1. Standardize on `x-print-sheet`: company letterhead row, document meta chips (no./date/status), parties block, items table (page-break-safe rows), totals panel, signature line, footer hash note — the round-6 checkout invoice is the visual spec.
2. Migrate, in order: **reservation invoice** (hotel + banquet — currently bespoke CSS + Calistoga webfont; restyle to `invoice-doc`, keep amounts exactly), `booking slip`, `payment receipts`, restaurant/bar **kitchen tickets & bills** (80mm thermal variant via `--sheet-width: 288px` token), payslip, HRM letters, account statements/reports (A4-landscape variant).
3. Kill dead templates (`get_invoice`, `checkout-invoice-v2`, `-v4`, unrouted banquet `booking_ui`/`available`) once a *keep-or-delete* decision is recorded in `docs/BUGS.md` → prevents design effort leaking into code nobody can reach.
4. Print hygiene tokens: `@page { margin: 8mm }`, `-webkit-print-color-adjust: exact` for status colors, hide app chrome (pattern already in `invoice-sheet`), and *no fixed `height` on body ever again* (round-8 lesson).

## 7. Responsive & accessibility floor (checked per screen, not a separate phase)

- Breakpoints 480/768/992/1280; tables → priority-column cards at ≤767; filter bars wrap; modals become full-screen sheets ≤576; daterange picker gets "Tonight / 7 days / Weekend" quick chips everywhere dates are picked (board pattern).
- WCAG-AA minimums: contrast ≥4.5:1 body text (re-audit `--mm-muted` usage), visible focus everywhere (`:focus-visible` ring already on tiles → global token), keyboard for pickers, `aria-live` for selection summaries, `scope` on table headers, popovers get ESC-dismiss + focus return.
- Reduced-motion respected (pattern from round 9) as a token-level media query.

---

## 8. Waves, estimates, sequence

Assumes 1 designer-dev (this agent can execute the code waves) + staff-side smoke testing. Estimates are per-wave calendar time, sequential.

| Wave | Scope | Est. | Exit criteria |
|---|---|---|---|
| **W0 · Now** | Deploy-automation unblock: run staging workflow once secrets added; finish round-10 rollout; hotfix list in `docs/BUGS.md` stays zero | 2–3 d | fix pipeline = every later wave ships same-day |
| **W1 · Design system** | `tokens.css`, 12 `x-*` components §3.2, component gallery route (super-admin only), review checklist doc | 1–2 wk | board + one list + one form converted *and* unchanged behaviour proven by harness |
| **W2 · Shell** | layout, sidebar rail, topbar, page header, mobile action bar, dark-mode *deferred* (tokens ready) | 1–2 wk | all modules inherit shell without per-page edits; 360px OK |
| **W3 · Hotel module** | remaining Hotel screens (lists, guests, night audit, reports, room-management) + print programme §6.2 first six docs | 2–3 wk | Hotel module zero bespoke `<style>` blocks > 20 lines |
| **W4 · Banquet + services** | hall lists/create (shares Hotel partials already — cheap), HotelService, NewsEvents | 1 wk | parity with Hotel by bleed design, no fork drift |
| **W5 · POS cluster** | Restaurant (tables board → grid, order wizard, kitchen ticket 80mm), Bar, Merchandising, Knitting | 2–3 wk | three POS screens pass mobile tap audit |
| **W6 · Finance & admin** | Account (vouchers, ledgers, reports), CRM, HRM, Permission, system settings; GS | 2–3 wk | ledger tables get sticky totals row; voucher entry `x-stepper` |
| **T2 (optional) · CSS replatform** | Bootstrap 3→5.3 codemod, per-module flags | 3–4 wk | gate: ≥2 waves quiet |
| **W7 · Public site** | HotelWebsite landing, availability search, booking confirmation emails — separate visual identity track (brand-led) | 2–4 wk | out of admin waves |
| **T3 (optional) · JS replatform** | Livewire for tables/wizards, Vite bundle | rolling | with Plan A phase 2 |

## 9. Risk register

| Risk | Likelihood | Mitigation |
|---|---|---|
| UI change breaks JS behaviour (selections, totals, AJAX) | the big one | §3.3 rule 1 + hook-grep audit per PR + render harness verification per wave (proven 10×) |
| Print regressions in finance docs | medium | `x-print-sheet` PDF-snapshot diff (headless render vs baseline) for the 6 high-use docs |
| Two-look period while waves overlap | medium-high | waves are module-complete; no half-styled module ships |
| Dead-template effort waste | medium | W3.3 deletion decision gate first |
| Server deploy friction (what this session proved: fix can't reach the box) | high | W0: wire deploy-staging secrets + `view:clear` step in every wave ship |

## 10. Definition of done (every screen)

fluid 360→1920 · zero page-own `<style>` blocks (components only) · tokens not hex · keyboard-reachable primary flow · print output (if printable) uses `x-print-sheet` · screenshots archived in `screenshots/` · hook-diff audit clean · no money-math diff (enforced) · `docs/BUGS.md` updated if a pre-existing quirk was found but not fixed.

## 11. Immediate next actions (proposed, in order)

1. **Deploy W0 plumbing** — add `STAGING_*` secrets (per `docs/DEPLOYMENT.md`) so `deploy-staging.yml` can ship waves + auto-rollback; today the box updates only manually, which is how round-10's fix sat unreleased.
2. **Reservation invoice → `x-print-sheet`** (first consumer migration; completes the design fix users are seeing today).
3. **`x-data-table` + `x-filter-bar` extracted from booking list**, then applied across Hotel lists (W3 core).
4. **Delete-or-keep decision** on dead invoice templates (owner: you) → unlocks W3 cleanly.
5. Sidebar rail + topbar quick-add (W2 starts) once 2–3 list screens validate the components.
