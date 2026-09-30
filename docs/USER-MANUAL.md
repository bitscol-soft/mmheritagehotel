# MM Heritage Hotel — User Manual
### Complete guide to the property-management, POS and back-office suite
System: https://mm-heritage-hotel.dizihotel.com · Build analyzed: repo `main` @ `0bdac333` (+ `development`/`zesan` deltas noted) · Manual generated 2026-09-30 with live screenshots (full capture set in `screenshots/` (251 files), index in `docs/SCREENSHOTS.md`).

> **How to read this manual.** Part I walks through daily operation (login → booking → check-in → folio → check-out → night audit). Part II is the module-by-module reference (every screen, where it is, what it does). Part III covers administration. Part IV covers the public website & guest-side booking. Part V lists known issues you should train staff around.
> Screenshots marked 🚧 were erroring in the current build (pre-existing bugs) — the route is documented anyway.

---

# Part I — Getting started

## 1. Logging in

**Screen:** `screenshots/` (see SCREENSHOTS.md)

1. Open the site URL → you land on **Login Information** (Banglafire “Smart ERP” skin).
2. Enter **Email** (staff e-mail; *numeric employee-ID login is also accepted* — the login form reads an `employee_full_id` field behind the scenes, added for HRM integration) and **Password**.
3. Tick **Remember Me** only on a private machine.
4. Press **Login**. You are redirected to **/home** (the Dashboard).
5. **Forgot password?** — click “I forgot my password”, submit your e-mail; a reset link arrives by mail; the reset form asks *e-mail + token + new password*. (If mail is not configured on the environment, an administrator must reset it under *Global Setting → Users → change password*.)

Notes:
* A user must have `status = active`; deactivated accounts are bounced back with “User is deactivated”.
* The login page also holds the public **guest-registration** and **reset-password** sub-forms (single-page toggle).

## 2. The shell: header, sidebar, module menus

**Screen:** `screenshots/` (see SCREENSHOTS.md)

* **Top bar** — hotel logo; **RST / BAR** quick toggle (jumps to the Restaurant or Bar POS landing screen); a **refresh** button (reloads); a **bell** with live notification count (payment alerts / notices); user menu (**Welcome, <name>**) with profile links and **Logout**.
* **Left sidebar (the main navigation).** Contents are *permission-driven*: you only see modules your role was granted. The default top-level menu is:

| Menu | URL | Purpose (detailed in Part II) |
|---|---|---|
| Dashboard | /home | KPIs & shortcuts |
| Global Setting | /setting/* | company, users, groups, system & ID-card settings, backup |
| User Access | /setting/permission-access etc. | modules/submodules/permissions matrix |
| Website CMS | /hotel-website/* | public site content manager |
| **Front Desk** | /hotel/* | the PMS core: booking, guests, rooms, invoices, reports |
| House Keeping | /hotel/house-keeping | board of room statuses |
| H. Service | /hotel-service/* | bookable hotel services (spa etc.) |
| Restaurant | /restaurant/* | F&B POS, kitchen, inventory |
| Bar | /bar/* | bar POS + stock |
| Account | /setup/*, /sale/*, /purchase… | full accounting & finance |
| General Store | /gs/* | internal store items & requisitions |
| Inventory | /product/* | shared product master (Account module) |

* Banquet Hall and CRM exist as DB entities but are **disabled/hidden** in production (see Part V).
* Menu state is remembered (collapsed sections persist per browser via `ace-save-state`).

## 3. Dashboard

**Screen:** `screenshots/` (see SCREENSHOTS.md) — `/home` (+ `/dashboard` variant).

Widget groups: counts of **rooms by status** (free/dirty/occupied…), **expected arrivals / departures / in-house / due-out** tiles for today, quick actions (New booking, Check-in list), notice board (`news`), and the *SmartSoft payment schedule* alert strip (licence/subscription banner for the vendor). Data is scoped by the logged-in user's company and permissions. Every tile is a link into the matching filtered list.

## 4. First steps for a new property (admin)

Order that the screens expect:
1. **Global Setting → Company** — create the company (name, address, currency symbol, tax no, logo) — `screenshots/module-generalstore_gs_gs-reports_company-items.jpg`
2. **Global Setting → Users** — add staff (name, e-mail, password, status; role/branch fields legacy) — `screenshots/` (see SCREENSHOTS.md)
3. **User Access → Modules / Submodules / Parent permission / Permission** — normally shipped pre-configured; verify the slugs match the routes you license per role.
4. **User Access → Permission Access** — assign permission slugs per user 🚧 (screen currently 500s — see Part V; until fixed use DB or the permitted-user list).
5. **System Setting / Id Card Setting / Invoice number setups** in Account → Setup.
6. **Front Desk → Category & Amenities & Rooms & Vat & Account type** — physical inventory before bookings are possible.
7. **Website CMS → Website Setting / Banner / Feature / Gallery** — public site content.

---

# Part II — Module reference

## 5. Front Desk (Hotel PMS) — `/hotel/…`

### 5.1 Booking UI — *box-style reservation board*
**Screens:** `screenshots/module-hotel_hotel_booking-ui.jpg`, `screenshots/` (see SCREENSHOTS.md), `screenshots/module-hotel_hotel_booking_next-step.jpg`
**Menu:** Front Desk → Booking UI (`/hotel/booking-ui`)

1. Pick the **booking date range** (single daterange input, format `MM/DD/YYYY - MM/DD/YYYY`) and search.
2. The page renders **category cards**; inside each card a **grid of room boxes**. Each box = one physical room; green/red shading reflects availability on those dates; clicking a box toggles it into the (cookie-based) **selection cart** — counter updates top-right.
3. Press **Next step** → `/hotel/booking/next-step` — the giant create form pre-populated with your selected rooms, one row per room.
4. Fill in (**required**): `Customer` (pick existing guest or create inline), **Check-in / Check-out**, **Room category** & **Room number** per row, **Purpose** & **booking type/platform** (walk-in/OTA/website), guest & member details, flight **pickup/drop** fields, **VAT**, **payment way**, **deposit** (Advanced Payment / Deposits Money), account type (city ledger), plus the **SMS / E-mail** tickboxes (send confirmations).
5. **Save** creates: `booking` header (status *Reserved*), `booking_details` per room/night, guest↔booking link, an entry in the **transaction ledger**, and advances the monthly **invoice number sequence** (`YYYY-MM-####`). Toast: *“Your booking have been successfully Reserved!”*
6. The same cart mechanic powers **House-keeping room-move** and **Banquet** booking screens (separate module, disabled by default).

> ⚠️ Selections live in a `booking_info` **cookie** (1 000-minute lifetime) — don't switch browsers mid-booking, and don't clear cookies before saving.

### 5.2 Bookings list & lifecycle
**Screens:** `screenshots/module-hotel_hotel_booking.jpg`, `screenshots/` (see SCREENSHOTS.md)
**Menu:** Front Desk → Booking (`/hotel/booking`)

* **Table** (DataTables, server-side) columns: booking no, guest, rooms, dates, nights, total, paid, due, status, source (platform), created. Filters: **date-range search** (`/hotel/booking-search-by-date`), **status**, referred-booking view (`/hotel/referred-booking`).
* **Row actions** (icon buttons per row):
  * 👁 **View** — booking profile with rooms table, guests, payment history, notes; `img/14`
  * ✏️ **Edit** — same giant form (dates, prices recompute; changing dates re-derives nights & totals)
  * 🗑 **Cancel** — sets status *Cancelled* (stock/availability released); hard-delete only for drafts
  * 🔑 **Assign room** (PUT `/hotel/booking-assign/{id}`) — modal to assign actual room number for reserved-but-unassigned bookings (the box grid reuses the availability board)
  * ✅ **Check-in** (POST `/hotel/booking-checkin/{id}`) — mark in-house, capture **check-in time/notes**; guest document upload allowed here (Guest Uploads)
  * ➡️ **Check-out** (POST `/hotel/booking-checkout/{id}`) — closes folio, requires balance settled (unless account type allows), status → *Checked-out*
  * ➕ **Extend checkout date** (POST `/hotel/extend-checkout-date/{id}`) — nights + price recompute
  * 💰 **Payment collection** — see 5.5
  * 🧾 **Invoices** — see 5.6
* Statuses used by the system: `Reserved → Check-in → Check-out`, plus `Cancelled`, and *Due/Partial* flags computed from collections.

### 5.3 Booking adjust (rates/room changes)
**Route:** `/hotel/booking/booking-adjusts` — list + create modal (`adjust/`). Used to change price/room of an existing booking; writes an audit row in `booking_adjusts` and posts the delta to the ledger.

### 5.4 House-keeping board
**Screen:** `screenshots/module-hotel_hotel_house-keeping.jpg` — `/hotel/house-keeping`
Room boxes colored by *dirty / clean / occupied / vacant / checked-out-today*; toggle each room's keeping status (POST `update-status`); a date-range selector filters the day. Same board layout as booking UI (shares the `house-keeping/index` view). This is the read-board staff use for cleaning priorities; there is no task assignment yet (see Plan B F13).

### 5.5 Payment collection & extra charges
**Routes:** `/hotel/booking-collection` (`screenshots/module-hotel_hotel_booking-collection.jpg`), modals on booking rows; POST `booking-due-collection` (multi), `booking-extra-charge`, `store-payment-collection`.
* **Collection modal** fields: date, amount, **payment way** (cash/card/online — from `payment_type` settings), payment type (deposit/due), account/remarks. Collections accumulate against the booking; the ledger (`hotel_customer_ledgers`) and booking `advanced_payment` update atomically inside a transaction.
* **Extra charge**: any manual line (minibar, breakage, laundry…) with qty/price/note — posts to folio & ledger. Editable/deletable while not closed by night audit.
* **Due report** shown per booking; over-capacity blocks none of this (no credit-limit logic yet).

### 5.6 Invoices / folios (print)
**Screens:** `screenshots/` (see SCREENSHOTS.md), `screenshots/` (see SCREENSHOTS.md)
GET routes (browser print views, A4, RM currency — the *Ringgit fix* lives on `development`; merge it if you deploy from main):
| Route | Use |
|---|---|
| `/hotel/booking/invoice/{id}` | classic folio invoice (rooms × nights, extras, VAT, paid/due) |
| `/hotel/booking/invoice-v2/{id}` | redesigned invoice (preferred) |
| `/hotel/booking/reservation-invoice/{id}` | pre-arrival “reservation confirmation” (no balance) |
| `/hotel/booking/rest-sale-invoice/{id}` | restaurant sale attached invoice |
| checkout-invoice v1–v4 | check-out folios shown on the check-out modal (v3/v4 latest; the app currently points staff at **v3/v4**) |

All invoices are browser-print (Ctrl-P → PDF); header/footer come from company settings (logo, address, tax no). QR/barcode stamps exist in the layout (qrcode package) for payment reference.

### 5.7 Guests directory
**Screens:** `screenshots/module-hotel_hotel_guests.jpg`, `screenshots/module-hotel_hotel_guests_create.jpg` — `/hotel/guests`
CRUD for guests (national/international): **name, NID/passport no, phone (unique), e-mail, address, country/state/district, gender, DOB, ID-card images (multiple, via guest-uploads), status**. List shows current bookings & stay count. Actions: edit / view booking link / **send SMS** (`/hotel/guests/send/sms` form: template textarea + number; posts to `submit/sms`) / image upload (`guest-image-update`) / CSV import-export (buttons on index).
Supporting screens: **Booking purpose** (`/hotel/booking-purpose`), **Booking notes** (`/hotel/booking-note` — internal per-booking memo list), **Guest registration terms** (`/hotel/guest-registration-terms` — the waiver text shown to web guests), **Customer ledger** (`/hotel/hotel-customer-ledger`, per-guest debit/credit lines feeding “due” amounts).

### 5.8 Room management
**Screens:** `screenshots/` (see SCREENSHOTS.md), `screenshots/module-hotel_hotel_room-management_hotel-categories.jpg`, `screenshots/module-hotel_hotel_room-management_aminities.jpg`, `screenshots/module-hotel_hotel_room-management_vat.jpg`, `screenshots/module-hotel_hotel_room-management_account-type.jpg`
| Screen | Route | Fields / behavior |
|---|---|---|
| Rooms | `/hotel/rooms` | room number, category, floor/short details, status, photo gallery (multi-upload to `assets/uploads/hotel/room`), floor-map position; uniqueness of number checked per company; edit shows current booking conflict info |
| Category | `/hotel/room-management/hotel-categories` | **name (req), amenities (multi, req), base price (req, numeric) + extra-bed price per capacity, VAT applicable flag, description, bed details, sqft, can-sleep, status (req), cover image**; a room is bookable only if category active |
| Amenities | `/hotel/room-management/aminities` | icon+label per facility used in cards & website |
| VAT | `/hotel/room-management/vat` | single active config: name, percentage, applies-to flags — feeds all folio math |
| Account type | `/hotel/room-management/account-type` | ledger account classes (Company, Online, Guest…) used for city-ledger posting |
| Currency | `/hotel/currency-conversions` | per-currency conversion rates used on invoices for foreign rates |
| Images | `/hotel/guest-uploads` | document store (IDs, permits) per guest/booking |

### 5.9 Night audit
**Screens:** `screenshots/module-hotel_hotel_night-audits.jpg`, `screenshots/module-hotel_hotel_night-audits_create.jpg` — `/hotel/night-audits`
Daily close sheet: one row per night with **collection, due_amount, total_check_in, total_check_out, total_reservation, total_cancelled, total_room, total_dirty_room, total_booked_room** (all numeric, prefilled from live data — edit to taste), plus per-room detail snapshot (`night_audit_room_details`) and transaction postings (`night_audit_transactions`). Once generated, booking creation for that past date is blocked (guard exists in the booking add-path, currently commented — see Part V). Reports: **Night audits report** & **detailsShow/{id}** give the printable ledger.

### 5.10 Hotel reports
**Menu Front Desk → Report** (`/hotel/reports/…`): monthly booking summary, monthly booking *detail*, cash-flow, night-audit, room-logs, services, today activities, expected arrival, expected departure, VAT report (per period), plus export/print variants (Excel via Maatwebsite, print views). Each opens as filter-form + table; totals row at footer.

### 5.11 Booking cart & referred bookings
* `/booking-cart` is the **public** cart (Part IV).
* `/hotel/referred-booking` — filtered list of bookings where a `referral` staff member is set (commission tracking view).
* `/hotel/check-avaiable` + `/hotel/check-room-availability` — helper endpoints used by the UI (dates → availability JSON).

## 6. Restaurant — `/restaurant/…`

**Screens:** `screenshots/` (see SCREENSHOTS.md), `screenshots/module-restaurant_restaurant_sale-exchanges_create.jpg`
* **Table management** (`/restaurant/tables`? — menu “Rst Table Manage”): floor grid of tables, statuses free/occupied/bill; click table → open order.
* **Sale (POS)** (`/restaurant/sales` list, `/restaurant/sales/create` new bill): pick customer (walk-in default) → product rows (searchable select w/ info popup, unit auto-fill, price from product, qty/serial, discount row-level & bill-level) → **pay now / later (city ledger)**; prints kitchen ticket + receipt (barcode/QR supported). *Exchange/Return* bill types live under sales menu (return & exchange screens 🚧 exchange controller missing — Part V).
* **Kitchen** (`/restaurant/kitchen`): incoming order queue per station; item statuses (queued/ready/served) — the ticket print + status buttons; `/restaurant/kitchen-order*` management.
* **Inventory** (module-local, mirrors Bar): Product master (`/restaurant/products` — name, category, unit, purchase/sale price, VAT flag, tax no, opening stock, supplier, photo, *batch enabled* flag), **Batch** (`/restaurant/product-batches`, expiry tracking), **Stock adjust** (`/restaurant/stock-adjustments` with reason), material/recipe (`RstMaterial`, `PrductMetrial`) + **production** (`/restaurant/rst-productions`: finish-goods recipes consuming stock), supplier & purchase (`/restaurant/purchases` + GRN-style receive), damages (`/restaurant/damages`), ledger reports (`/restaurant/reports/*`: product ledger, supplier ledger, VAT, expiry, stock report, sales reports…) and its own **restaurant night audit** (`/restaurant/restaurant-night-audits`) that posts kitchen revenue.
* Restaurant module also handles **hotel-room charging** (a “hotel” payment source: bills can be moved to a guest folio via `rest-sale-invoice` flow).

## 7. Bar — `/bar/…`

**Screen:** `screenshots/` (see SCREENSHOTS.md)
Structurally the same POS as Restaurant (own tables: `bar_*`), tailored for a pharmacy/bar catalogue: Products have **brand, generic, type, package** (`/bar/products`, `/bar/product-brands`, `/bar/medicine-generics`, `/bar/medicine-types`, `/bar/product-packages`) and batches/expiry. Sales (`/bar/sales`, v2 fast-sale `/bar/sales-v2`), **Returns**, **Exchanges** (🚧 controller missing), Purchases + receive + returns, Suppliers + payments, Rst table management (shared style), ledger & VAT reports. The **BAR** top-bar button jumps to this POS.

## 8. General Store — `/gs/…`

Internal store (not POS): **Items** (`/gs/items`: code, name, unit, description, reorder level, category), **Units** (`/gs/units`), **Suppliers** (`/gs/suppliers`), **Purchase order → GRN** (`/gs/purchases`, receive list `/gs/grn-list`), **Goods Requisition** (`/gs/goods-requisitions` — internal requisition workflow with approval + issue), **Stock & GIN ledger** (`/gs/gin-list` = goods issued notes, `/gs/stock`), **Item export/upload** (CSV templates in `public/assets/*.csv`; export screen 🚧 missing class), **Reports** (`/gs/gs-reports/*`: item ledger, summary, requisition reports). All postings reduce/increase `Stock` rows in the GS-only stock table (parallel to other modules' stock — see Plan A §1.4).

## 9. Banquet Hall — `/banquet-hall/…` (currently OFF in production)

Hall bookings use the **same box-cart flow as Front Desk** (own category/room/booking tables), plus **item/product add-ons**, **amenities**, **purposes**, and its own invoice family (`checkout-invoice-v3`). Enable by activating the `BanquetHall` row in `modules` (status=1) — UI then appears in sidebar. Its sale screens are under the same Account-module inventory (shared products).

## 10. Hotel Service — `/hotel-service/…`

**Screen:** `screenshots/module-hotel_hotelservice_service-sales.jpg` — simple catalog of bookable services (name, price, unit, status) consumed by booking extra-charge/service pickers and `night-audit` service postings.

## 11. Website CMS — `/hotel-website/…`

**Screens:** `screenshots/` (see SCREENSHOTS.md) — manages the **public site**: `Website Setting` (site title, logo, favicon, contact e-mail/phone/address, social links, map embed), `Banner` + images (hero slides), `About section` (heading/text/2 images + *offer* block), `Our features` (list CRUD), `Our services` (2 blocks: headline + bullet lists), `Gallery` (title+image), `Pages` (slug-able static pages, WYSIWYG Trumbowyg), `Privacy policy` & `Terms`, and the **contact/booking email** wiring (zesan branch adds mailables for the contact form). Every record maps 1:1 to homepage/room-page slots.

## 12. Account & Finance — `/setup/* /sale/* /purchase/* …`

The ERP backbone (inherited from the Smart-ERP suite):
* **Setup** (`screenshots/module-account_setup_accounts.jpg`): `Accounts` (chart of accounts tree: group → subsidiary → control → account), `Account setup` (system accounts per voucher type), `Opening balances`, `Invoice No` settings (per type/month sequences incl. Booking/Banquet…), `Banks`, `Units/Categories/Products` masters shared with stores (`/product/*`, incl. **bulk CSV import**), `Parties`: Customers & Suppliers (ledgers, opening balance, VAT no).
* **Daily transactions**: `Collections` (customer receipts), `Payments` (supplier payments), **Vouchers** (journal: voucher type → debit/credit lines table, print A4) `screenshots/module-account_voucher_contras_create.jpg`, `Fund transfers` between banks, `Damages` (stock loss postings), `Sales` & `Purchase` (the module-level stock-sale docs used by Restaurant/Bar/Banquet/Hotel services: `/sale/acc-sales`, `/purchase/acc-purchases`, plus return/exchange docs), `Stock` & `Stock summaries` (`/setup/stocks…`).
* **Reports** (`screenshots/module-account_reports_account-ledger.jpg`): Day-book, Sales report (detail/summary), Purchase report, Customer/Supplier ledgers & balances, Stock ledger/valuation, Profit & Loss (income statement), Balance sheet, Tax/VAT reports, Income & Expense summary, Transaction reports, **Cash flow** (`/account/report/cash-flow`), each with period filter + Excel export.
All monetary postings honor `company_id`; voucher numbering via the `InvoiceNo` engine (same engine as hotel folios).

## 13. Users, Groups, Companies, Modules (admin surfaces)

* **Global Setting → Company** (`/company`): legal name, address, phone, email, logo, currency symbol, VAT/SST no — feeds every print/PDF.
* **Users** (`/users` 🚧 see Part V — user CRUD routes exist): staff accounts incl. “add-user from employee” (legacy), password change (`/user/password/edit`), per-user status.
* **Groups** (`/group`): access groups for legacy HRM — harmless.
* **Id Card Setting** (`/id-card-settings`): template fields for printable guest/staff cards.
* **System Setting** (`/system-setting`): key/value toggles (`general_store_reference_no_change`, `finger_id_get`, `out_work_date_picker`… — several are HRM-legacy switches).
* **Suppliers / Supplier types** (`/global-setting/suppliers`, `/supplier-types`): shared supplier master for stores.
* **Database**: `Backup Database` (downloads a full/partial `mysqldump` .sql — **restrict to super admin!**) and `Backup To Drive` (Google Drive schema backup; requires the `google_drive` service JSON in storage).
* **User Access** (module `Permission`):
  * `Modules` / `Submodules` / `Parent permission` / `Permission` CRUD — the menu/permission catalogue (`screenshots/module-permission_setting_modules.jpg`, `screenshots/module-permission_setting_parent-permissions.jpg`, `screenshots/module-permission_setting_submodules.jpg`, `screenshots/module-permission_setting_permissions.jpg`). 🚧 Create-screens for parent-permissions & submodules currently 500.
  * **Permission Access** (`/setting/permission-access`) — per-employee permission matrix 🚧 500 (HRM references); **Permitted Users** (`/setting/permitted-users` — user-level slug editor `screenshots/module-permission_setting_permitted_employee_list.jpg`) and **Employee Permission** are the working variants.
  * Effect: `hasPermissionV2(slug)` gates both menu and controller actions; menu list is cached per session — users must re-login after permission changes.

---

# Part III — Typical end-to-end routines

## A. Walk-in reservation to checkout (front desk)
1. `Front Desk → Booking UI` → dates → click room boxes → **Next**.
2. Fill customer (create guest inline if new), purpose, prices (auto from category, editable), deposit if any, tick e-mail/SMS → **Save**.  print **Reservation invoice**.
3. On arrival: `Bookings list → ✅ Check-in` (capture time; upload ID if missing). The room flips **occupied** on House-keeping.
4. During stay: extra charges via 💰 → `extra charge` modal (minibar, laundry…); payments via `collection` modal; extend stay via ➕.
5. Departure: ➡️ **Check-out** → the modal shows the **checkout folio** (v3/v4); settle due → print → confirm. Room becomes dirty → cleaning flag on House-keeping.
6. Nightly: `Night Audits → create` → verify prefilled numbers → save; `Reports → Night audits` to print.

## B. Web booking (guest side, staff follow-up)
Guest searches availability on the site → cart → registers (`guest/registration`) → submits: booking lands in `Bookings list` with platform = Website, status *Reserved*, plus a row in `website enquiries` (the contact form and booking confirmation **mails require the zesan branch templates**). Front desk just confirms & pre-assigns rooms; check-in path as above.

## C. Restaurant billing to room
`Restaurant → New Sale` (table) → add items → pay **later / hotel folio** → staff attaches booking on `Bookings list → rest-sale-invoice` → amount shows on folio & night audit service postings.

## D. Corporate (city ledger) guest
Guest's **account type = Company** → charges post to `Hotel Customer Ledger`; collections against ledger update via Account → `Collections`; ageing visible on the ledger report.

## E. Month close (finance)
Account → Reports: **Income statement**, **Balance sheet** (auto from vouchers + module postings), **Tax report**, VAT vs module data → adjustments via Journal Vouchers → archive PDFs. (No locking yet — see Plan B.)

---

# Part IV — Public website (guest-facing)

> Capture note: the partial screenshot run did not include the public pages; their
> behavior below is documented from code + live-site inspection.

**Screens:** `screenshots/` (see SCREENSHOTS.md), `screenshots/` (see SCREENSHOTS.md), `screenshots/` (see SCREENSHOTS.md), `screenshots/` (see SCREENSHOTS.md), `screenshots/` (see SCREENSHOTS.md) (zesan)

1. **Homepage** — hero slider (Banners), **BOOKING DATE search** (Room Type select + Arrival/Departure daterange) → search runs the same availability service; amenities strip, about section, “Rooms and Rates” cards (from categories incl. images), services, gallery, contact/newsletter form (mailables on zesan branch), footer with TikTok/social + developer credit.
2. **Room category pages** `/category/{slug}` — per-category intro (description, bed details, sqft, capacity), amenities list, **price per night**, room photos gallery, inline date search, and each listed room card with **Add to cart** (nights picker) → the cart badge in the header.
3. **Cart** `/booking-cart` — rows (category/room/nights/price), remove link, subtotal panel (⚠️ header “Subtotal: RM 0.00” until JS loads), **Checkout**.
4. **Checkout** — if not logged-in: **guest registration** form (`/guest/registration`: name, phone, e-mail, nid/passport, address, password auto); then booking summary + terms acceptance → **submit** → booking created (platform Website), optional success page with folio preview + printable invoice (zesan `booking-success`), and confirmation e-mail (when mailables wired).
5. **Terms** & **Privacy** pages (CMS-driven), custom **pages** (`/pages/{slug}`) for e.g. F&B, pool rules.
6. Cancellation/modification are **manual** (call/e-mail) — no guest portal yet.

---

# Part V — Known issues & workarounds (verified on the running build)

| # | Screen / route | Symptom | Workaround until fixed |
|---|---|---|---|
| 1 | `/setting/permission-access`, `/setting/select/employee/list`, `/setting/permitted/employee/list` | 500 — code still references the removed **HRM module** (`Module\HRM\...`) | Use **Permitted Users** editor; remove/repair the HRM-bound screens (Plan A P0.3) |
| 2 | `/setting/parent-permissions/create`, `/setting/submodules/create` | 500 — `create` methods missing on controllers | Add via `/setting/permissions` variants or DB seed |
| 3 | `/gs/item-export` | 500 — `ExportItemCSV` class missing | Export via DB/backup |
| 4 | Restaurant & Bar **sale-exchange** screens | 500 — `SaleExchangeController` missing **on every branch** | Use Returns instead of Exchange; restore controller (development branch templates exist) |
| 5 | `/sync-data*`, `/home` HR widgets, payslip view | dead references to HRM (some silent, `sync-data` fatal) | Hide menu items; delete dead code (Plan A P0.3) |
| 6 | Mobile API (`/api/dashboard`, `all-users`) | 500 — `Api\ApiDashboardController` missing | Don't expose the API until rebuilt (Plan A 1.6) |
| 7 | Booking UI selection cart | lives in `booking_info` **cookie**; 1000-min TTL; per-browser | Save drafts as bookings; don't switch devices mid-booking |
| 8 | Night-audit lock | “no booking before audit” guard is **commented out** — past dates still bookable | Staff discipline: fix dates via adjust; restore guard in code |
| 9 | Permission menu cache | sidebar only refreshes on new session | Log out/in after permission edits |
| 10 | E-mails | contact/booking-success mailables only on `zesan`; SMS route is a stub | Merge branches; configure SMTP before enabling |
| 11 | Banquet & CRM | modules rows exist, menus hidden (status 0) / code missing | Re-enable only with matching code deployed |
| 12 | `/db-backup` in sidebar | any user with the menu can download full DB dump | Restrict to super-admin immediately |

---

# Appendix

* **Full screen inventory** — every crawled URL × status × screenshot: `docs/SCREENSHOTS.md` (auto-generated).
* **Shortcuts people actually need:** `Front Desk → Booking UI` (new reservation), `Bookings` (today filter), `Check-out` modal, `Night audits`, `Reports → Expected arrival/departure`, `Restaurant → New Sale`, `Bar → New Sale`, `Account → Vouchers`, `Website CMS` for content edits.
* **Keyboard:** all lists support `/`-style search via DataTables search box; datepickers respond to typing `MM/DD/YYYY - MM/DD/YYYY` in the range fields.
* **Browser support:** built for Chrome/Edge desktop; mobile admin works only read-mostly (see Plan A Phase 2).
