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
| Room assignment | `booking.assign` | `assaign` | pending |
| Invoices / print | `getInvoice`, `getInvoiceV2`, `reservationInvoice`, `checkoutInvoice` | `checkout_invoice`, `checkout-invoice-v2/v3/v4`, `get_invoice`, `reservation-invoice` | `checkout_invoice`, `reservation-invoice`: screen frame migrated; `checkout-invoice-v3`: screen-only action bar; v2, v4 and `get_invoice` are not referenced by any route or controller and were left alone |
| Payment collection | `BookingCollection` | `payment-collection.index` | pending |
| Night audit | n/a | n/a | pending |

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

## Findings on edit (backend, not changed)

- The edit form posts `check_in_date` and `check_out`, but `BookingService::update()` reads `$request->check_in` and
  `$request->check_out`. `check_in` is not a field on that form (only the extend-date modal and `booking_next` have it), so the
  stored check-in may be overwritten with an empty value. Confirm on staging before relying on edit for date changes.
- The Reset buttons on create and edit sit outside the `<form>`, so they never reset it.
