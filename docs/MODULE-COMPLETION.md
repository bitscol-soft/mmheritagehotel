# Module-by-module completion tracker

Completion means migrated screens plus automated checks AND authenticated staging
acceptance. UI implementation alone is not production completion.

## Sequence

1. Hotel: finish related CRUD groups, then booking lifecycle, payments and print.
2. Banquet/services.
3. Restaurant/bar/POS and inventory.
4. Finance/accounts.
5. HRM/payroll.
6. Permissions/settings and remaining modules.

## Hotel status

| Area | UI implementation | Acceptance |
|---|---|---|
| Guest directory/create/edit/import | Migrated | Staging pending |
| Room list/create/edit | Migrated | Staging pending |
| Category list | Migrated; forms pending | Staging pending |
| Booking list/board | Migrated; create/edit/next/checkout migrated; invoices, payment collection, night audit pending | Staging pending |
| Dashboard | Migrated | Staging pending |
| Housekeeping | Layout migrated; status behavior unchanged | Staging pending |
| Booking purposes/platforms | List/filter/create/edit migrated | Staging pending |
| Booking notes | List/filter presentation/edit migrated; backend blockers below | Pending |
| Payments, checkout, invoices, night audit | Migrated (collection, checkout, invoices, night audit list/form/report) | Staging pending |
| Setup: amenities, account types, VAT, currency conversions, registration terms | Migrated | Staging pending |
| Hotel reports, house-keeping filters, guest modals | Migrated (reports, guest SMS, monthly calendar, booking migration) | Staging pending |

## Hotel Service status

| Area | UI implementation | Acceptance |
|---|---|---|
| Service list with add/edit modals | Migrated | Staging pending |
| Service sales list, due-payment modal, new sale, invoice | Migrated | Staging pending |
| Night audit list, details modal, printable audit | Migrated | Staging pending |
| `sales/edit`, `due-receive/*`, `export/pdf`, old sidebar partial | Unreachable; not migrated | n/a |

## Permission status

| Area | UI implementation | Acceptance |
|---|---|---|
| Modules, sub modules, parent permissions, permissions (list/create/edit) | Migrated | Staging pending |
| Permitted users list, new user, change password (own and by admin) | Migrated | Staging pending |
| User role/permission matrix (create, edit) and employee permissions | Migrated; checkbox/accordion scripts unchanged | Staging pending |
| `EmployeePasswordChangeController` view | Dead; not migrated | n/a |

## Restaurant status (groups R1, R2, R3 and R4 of four)

| Area | UI implementation | Acceptance |
|---|---|---|
| Tables, kitchen orders, kitchen board (KDS) and kitchen ticket | Migrated | Staging pending |
| Night audit list and generate form, payment collection | Migrated | Staging pending |
| Reports: cash flow, sales, today's activities, product inventory, stock ledger | Migrated; shared Bar/export partials unchanged | Staging pending |
| Sale list, invoice, new sale; sale return list, details, new return (R2) | Migrated; scripts unchanged | Staging pending |
| Inventory (R4): categories, units, manufacturers, suppliers, products, material products, uploads, inventory report, production items / units / requisitions / purchases, purchase create, stock adjustment | Migrated (30 pages); script partials, controllers and routes unchanged | Staging pending |
| POS sale workspace (`rst/sales-v2/create`) and the auto-print POS/office documents | Not changed (already card-based; needs its own design pass) | n/a |
| Purchase list, requisition, approve, create frame (R3) | Migrated; create uses the Restaurant partials (restaurant products); link, Received Qty and permission-gated link fixes applied (see TAILWIND-DEVELOPMENT, thirty-fourth increment) | Staging pending |
| Inventory screens (R4) | Not started | n/a |
| Unreachable views (`sales/bck_show`, `sales/exchange/*`, `purchase/{create,index,show}`, `reports/{cash-flow,sales}/index`, `kitchen/edit`, ...) | Not migrated | n/a |

## Latest group: Booking Purpose & Platform

- Shared page/panel layouts, responsive table overflow and labelled name fields.
- Search and reset now retain the `type` query parameter, so filtering does not
  silently switch between purpose/platform modes.
- Corrected create's Back List destination from company list to booking setup.
- Preserved rule values, required name, CSRF, PUT, multipart, actions and scripts.
- Build and preservation guards pass; 33 templates compile. These are not real
  create/edit/delete or role-matrix acceptance tests. Validate both type modes,
  errors, filtered reset and delete confirmation with disposable staging records.

Deployment remains blocked by staging setup. Temporary preview is sample data,
not a deployed Laravel application. See TAILWIND-DEVELOPMENT.md for prior work.

## Booking Notes blockers

The legacy controller's create/store/destroy paths operate on Guest records,
not BookingNote. Do not use these routes as note CRUD. No new controls added.
The index query uses `name` but the filter submits `title`; search functionality
is not fixed by the presentation migration. Backend correction and authorization
review, then authenticated tests, are required before this group is complete.
Next UI group: remaining room-category forms.

## Full menu design

Shared navigation design applied to all module sidebars through the shell, not by
editing each permission-driven sidebar. Fixture coverage uses sample links; real
role/permission and long-label acceptance remains pending in staging.

## Room category forms

Create/edit migrated together with preserved pricing/photo behavior and the
documented permission/error-key/id fixes. Existing-photo remove icon is inert
legacy UI (no handler). Staging acceptance pending. Hotel remaining: booking
lifecycle/detail/edit, payments/checkout/invoices/print, night audit.

## Dashboard completion

Header context/shortcuts, board panel and two board visual defects (phantom grid
cells, invisible dirty/maintenance/cart tiles) fixed. HRM/garments dashboards are
separate screens and remain in HRM/other module phases. Staging data acceptance pending.

## Dashboard room board redesign

Collapsible category groups, room cards, bed-type icons and a booking/detail drawer
replace the tile grid on the hotel dashboard only (Hotel and Banquet booking pages
keep the shared component). Legacy booking form, AJAX endpoints and housekeeping
handlers are reused. Verified with a rendered-Blade sample and browser tests;
staging with real data, date search and permissions is still pending.

## Shell header and footer

Header search/command palette, New booking shortcut, theme/full-screen/shortcuts tools,
breadcrumbs, and a status footer (business date, server clock, last sync, online state,
environment) are in the shared admin shell. Language switching is not available (English
only). Staging acceptance pending.

### Booking lifecycle (in progress)
New-booking form (`booking_next`), `create` and `edit` frames, sticky actions and single-date rules done; checkout, invoices,
payment collection and night audit pending. See `docs/BOOKING-LIFECYCLE-PLAN.md`.

## General Store (module/GeneralStore)

| Group | Status | Verification |
|---|---|---|
| G1: item units, items (list, add, edit, CSV upload), suppliers (list with detail modals, add, edit), supplier types | Migrated; fields, expressions, directives and scripts unchanged; the dead export/print icon row of the item unit list is commented out | Render fixtures + Playwright; staging pending |
| G2: purchases (list, show, approve, create, edit), purchase receive create, GRN list, purchase receive list | Migrated; expressions, controls, forms, directives and scripts unchanged (compared with `3ed5211b`). `purchase_receives/print-receive` stays a standalone print document. Visible changes: the "Purchase List" link on purchase create is now shown (it carried `only-print`), the printer image on the purchase form became a Print button | Render fixtures + Playwright; staging pending |
| G3: goods requisitions (list, create, edit, approve), GIN list, weekly movement, stock in hand, item ledger | Migrated; expressions, controls, forms, directives and scripts unchanged (compared with `3ed5211b`). `print-gin-details`, `print_item_details`, `print_items_stock`, `gs-paginate` and the exports are untouched. Two reviewed fixes: the create screen's "Goods Requisition List" link pointed at `purchases.index`; three hidden inputs on the edit screen read `old()[$key]` without a default (a warning that Laravel turns into an error on PHP 8) | Render fixtures + Playwright; staging pending |
| `reports/print_item_details`, `reports/print_items_stock` | Only reached by controller methods that have no route (dead code); not migrated | n/a |

## Bar (module/Bar)

| Group | Status | Verification |
|---|---|---|
| B1: product categories, units, manufacturers, suppliers, products (list, add, edit), product inventory report, packages, tables, purchases, sales (list, create, show), sale returns, sales / cash flow / inventory / today-activities reports, night audit list and generate | Migrated; expressions, controls, forms, directives and scripts unchanged (compared with `df1b849e`). Filters rebuilt as inline fields. The manufacturer edit modal now posts to the Bar route instead of the Restaurant one | Render fixtures + Playwright (`bar.spec.cjs`); staging pending |
| POS (`sales-v2/create`), `sales-v2/show`, `pos-print`, `bar-night-audits/invoice`, `frontend.bar-menu`, unreachable views | Not migrated (POS and print documents, deprioritised) | n/a |

## Account (module/Account)

| Group | Status | Verification |
|---|---|---|
| A1: account controls, groups, subsidiaries, chart of accounts, opening balances, customers, suppliers, product categories, units, products | Migrated; expressions, controls, forms, directives and scripts unchanged (compared with `3516b9d9`). Opening balances filter is inline; stray `)` and an unclosed div fixed | Render fixtures + Playwright (`account.spec.cjs`); staging pending |
| A2: fund transfers, receive / payment / journal / contra vouchers | Migrated (18 views); expressions, controls, forms, directives and scripts unchanged (compared with `bc9504ba`). Filters are inline; `table-header-bg` header contrast fixed. | Render fixtures, `account-vouchers.spec.cjs` (21 tests: frame, overflow at 1280/768/390, filters, Print, forms); not run against real Laravel / DB |
| A3: purchases, sales, purchase / sale returns, damages, supplier payments and collections lists | Migrated (14 views); expressions, controls, forms, directives and scripts unchanged except one removed legacy bug in `sale/sales/edit` (compared with `a53777be`). Invoices, `returnable-items` partials and the unused `show` views stay legacy. | Render fixtures, `account-trading.spec.cjs` (19 tests: frame, overflow at 1280/768/390, rows, filters, forms, line items); not run against real Laravel / DB |
| A4: reports (ledgers, trial balance, income statement, balance sheet, cash flow, ...) | Not migrated; print and export documents stay legacy | n/a |

