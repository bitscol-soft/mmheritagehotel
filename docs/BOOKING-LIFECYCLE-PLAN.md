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

## Findings on night audit (backend, not changed)

- `create()` has `->take(5)` on the transactions query, so the generate form lists and closes only five transactions
  (looks like a leftover debugging limit).
- The Close button has no `type`, so it submits the store form at once and skips the ten-second delay.
- Generate shows an overlay and submits after a fixed `setTimeout` of 10 s; a double click schedules two submits.
- `previous_paid` is posted as one non-indexed hidden input per row, so only the last value reaches the server.
- Unreferenced views (`index-details`, `create`, `create-v3/v4`, `show`) were not migrated.

## Findings on room assignment (not migrated)

- `booking/assaign.blade.php` is dead: `BookingController::edit()` and `BanquetBookingController` return `booking/edit`, and the
  `return view('booking/assaign', ...)` line is commented out. The assign form action is commented out in `booking/edit`.
- The `booking.assign` route and `BookingController::assign()` still exist, but no live Hotel view posts to them. The Banquet
  module has its own `hall_booking/assaign` and `hall_booking/edit` forms that do.
- Decision pending: remove the dead view and the route/controller method, or restore the assign step. No UI work was done for it.

## Findings on edit (backend, not changed)

- The edit form posts `check_in_date` and `check_out`, but `BookingService::update()` reads `$request->check_in` and
  `$request->check_out`. `check_in` is not a field on that form (only the extend-date modal and `booking_next` have it), so the
  stored check-in may be overwritten with an empty value. Confirm on staging before relying on edit for date changes.
- The Reset buttons on create and edit sit outside the `<form>`, so they never reset it.
