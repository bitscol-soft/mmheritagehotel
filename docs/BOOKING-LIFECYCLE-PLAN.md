# Booking lifecycle: migration plan

Scope: `module/Hotel/views/booking/*` and the screens it feeds. Laravel + Blade + Tailwind, module by module, with
preservation guards in `tools/ui-check.cjs` before any markup moves. Financial screens change last and only with guards.

## Flow and screens

| Step | Route / controller method | View | Status |
|---|---|---|---|
| Room board | `booking.ui`, dashboard | `booking_ui`, `home/_inc/room-board` | migrated |
| Booking list | `booking.index` | `index` | migrated |
| **New booking (step 2)** | `booking.next.step` -> `nextStep()` | `booking_next` | **frame, progress, summary, sticky actions migrated** |
| Create (direct form) | `booking.create` | `create` | **frame, sticky actions and single-date fix migrated** |
| Edit | `booking.edit` | `edit` | **frame, sticky actions and single-date fix migrated** |
| Booking detail / checkout | `booking.show`, posts `booking.checkout` | `view` (704 → 491) | migrated (panels, summary, shared frame); calculation script byte-identical; staging pending |
| Room assignment | `booking.assign` (PUT) | `booking/assaign` | not migrated: unreachable (see findings below) |
| Invoices / print | `getInvoice`, `getInvoiceV2`, `reservationInvoice`, `checkoutInvoice` | `checkout_invoice`, `checkout-invoice-v2/v3/v4`, `get_invoice`, `reservation-invoice` | `checkout_invoice`, `reservation-invoice`: screen frame migrated; `checkout-invoice-v3`: screen-only action bar; v2, v4 and `get_invoice` are not referenced by any route or controller and were left alone |
| Payment collection | `BookingCollection` | `payment-collection.index` (532 → 307) | migrated (search, guest info, invoices table, summary); script byte-identical; staging pending |
| Night audit | `NightAuditSummaryController` (`index`, `create`, `store`, `show`) | `night-audits/index`, `create-v2` (384 lines, was 552), `invoice` | migrated: list and generate form on the shared frame; the report only gains a screen-only action bar; closing script byte-identical; staging pending |

## Risks

- `booking_next` and `view` are driven by legacy jQuery that reads input names and classes (`room_category[]`,
  `due_amount`, `payment_type`, `.input-total-amount`, `.per-night-amount*`, `.grand-*`, `.payable-amount`).
  Never rename these. Tables with Chosen selects must not be wrapped in an `overflow` container (the dropdown is clipped).
- `view.blade.php` posts hidden arrays (`id[]`, `item_ids[]`, `item_types[]`, `total_amount[]`, `item_amount[]`,
  `service_charge[]`, `vat_amount[]`) plus `extra-charge`, `old_discount`, `payble_amount`, `is_adjust`, `night_count`,
  `pay_by`, `payment_type`, `check_out_date`. A guard must compare these byte for byte with the base commit.
- Invoices are printed. Verify print CSS (A4, no shell chrome) separately; do not restyle invoice numbers or tax rows.
- Real Laravel, database and permission behaviour is not verified by the fixture tests. Staging acceptance is required.

## Order

1. `booking_next`, `create` and `edit` (done): guest and room forms, lower financial risk.
2. `view` (checkout) with a form-field guard and a fixture exercising totals.
3. Invoices and print styles.
4. Payment collection, then night audit.

## Findings on night audit — resolved (03ba56fd)

- ~~`create()` has `->take(5)` on the transactions query~~ — debug limit removed.
- ~~The Close button has no `type`~~ — now `type="button"`, returns to the audit list.
- ~~Generate submits after a fixed 10 s `setTimeout`; double click schedules two submits~~ — one-shot
  immediate submit with the overlay kept up during the request.
- ~~`previous_paid` is one non-indexed hidden input per row~~ — now `previous_paid[{id}]` per row.
- Unreferenced views (`index-details`, `create`, `create-v3/v4`) were deleted in the 2026-10-16 dead-code sweep; `show` is kept (the tool suite renders it as a contract screen) and `list` is live (included by `index`).

## Findings on room assignment — resolved (removed)

- `booking/assaign.blade.php`, `hall_booking/assaign.blade.php` and `hall_booking/edit.blade.php` were all unreachable:
  `BookingController::edit()` and `BanquetBookingController::edit()` return `booking/edit`, and the assign returns are
  commented out. (The Banquet `hall_booking/*` assign/edit forms did post to `booking.assign`, but nothing rendered them.)
- The `booking.assign` route and `BookingController::assign()`/`BookingService::assign()` were therefore dormant — a
  registered mutating endpoint (it re-runs `makePayment`) with no live UI, and `assign()` read `check_in`/`check_out`
  field names the forms never posted.
- **Decision (2026-10-16, owner-approved): remove the dead code.** Deleted the three views, the route, and the
  assign/assignBookingDetails methods in both `BookingService`s, plus the commented leftovers in the controllers and
  `booking/edit`. Restoring the assign step later is a git revert away if the workflow is wanted.

## Findings on edit — resolved (03ba56fd)

- ~~The edit form posts `check_in_date`/`check_out` but `BookingService::update()` read `check_in`/`check_out`~~ —
  `update()` now accepts both field-name pairs and falls back to the stored dates (the extend-date modal and
  `booking_next` keep posting `check_in`/`check_out`). Confirm on staging before relying on edit for date changes.
- ~~The Reset buttons on create and edit sit outside the `<form>`~~ — now `type="reset"` with the form attribute
  (also fixed on the three live Banquet `hall_booking` forms).
