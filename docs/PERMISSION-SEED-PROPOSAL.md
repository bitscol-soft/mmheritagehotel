# Permission seed proposal — authorization review (2026-10-16)

**Status: APPLIED to `database/mmheritagehotel_db.sql` (2026-10-16, owner-approved).**
42 permission rows (ids 200001+) + 79 `permission_user` grants (explicit ids 775+;
the table has no AUTO_INCREMENT) now sit next to the tables' existing INSERTs in
the dump. Take effect on the next database import; nothing was changed on any
live database. Companion to the audit in `docs/MODULE-COMPLETION.md`.

## Background

`hasAccess()` (controller) and `hasPermission()` (view) both deny any slug that
does not exist in the `permissions` table (admin user id 1 bypasses). The audit
found 61 called slugs missing from the shipped table. They fall into three
groups.

## Group 1 — applied already (spelling/action drift of existing families)

Fixed in code on this branch; no DB change needed:

| was | now | family evidence |
|---|---|---|
| `vouchers.journal.{index,create,edit,show,approve}` | `voucher-journals.{index,create,edit,view,edit}` | `voucher-journals.*` rows + view gates exist (`view('vouchers.journal.*')` names deliberately untouched) |
| `rst.stock-adjustment.{store,update}` | `rst.stock-adjustment.{create,edit}` | family has `.create`/`.edit`; the code used Laravel method names |
| `restaurant.table-manages.*` | `resturant.table-manages.*` (index→`.view`) | `resturant.table-manages.*` rows exist (family has `.view`, not `.index`) |
| `account.*.reports` (6 report pages) | `report.*` route-name slugs | every working report gate in the same controller uses `report.*` == route name |

Judgment call inside group 1: journal **approve** mapped to
`voucher-journals.edit` (the family has no `.approve` row and no view gate for
an approve button). If approvals should be separately assignable, seed
`voucher-journals.approve` instead and move the two `approvejournalVoucher`/
`showjournalApprove` gates to it.

## Group 2 — proposed seed (families the code and views already agree on)

These family names are used consistently by controllers **and** views/sidebars;
only the DB rows are missing. Proposed rows + grants are in the SQL appendix
(ids 200001+, synthetic parent tags 110060+; the dump's `parent_permission_id`
values are inconsistent, e.g. `suppliers.*` is parented to
`permission.accesses.edit`, so new rows use clean tags).

| family | modeled grants on | proposed holders (user ids) |
|---|---|---|
| `supplier.types.*` (5) | `suppliers.*` action-for-action | 2 |
| `items.*` (6) | `resturant.inventories.index` | 2, 3 |
| `purchases.*` (7, General Store) | `rst.purchase.index` | 2, 3 |
| `purchase.receives.*` (4) | `rst.purchase.index` | 2, 3 |
| `create.requisitions.*` (7) | `rst.purchase.index` | 2, 3 |
| `gs.reports.*` (2) | `resturant.reports.index` | 2, 3 |
| `fund.transfers.*` (5) | `voucher-payments.index` | 2, 3 |
| `report.{expense,ratio,revenue}-analysis`, `report.{transaction,nominal-account}-ledger`, `report.received-payment-statement` (6) | `report.ledger-journal` | 2, 3 |

Shipped users: 1 Mr. Admin, 2 Super admin, 3 admin, 4 frontdesk1, 5 frontdesk2,
6 booking. None of the front-desk or booking accounts hold the modeled sibling
grants today, so the proposal does not widen their access.

## Group 3 — needs a product decision before any change

- `acc_collections.*` / `acc_payments.*` (Account `CollectionController`/
  `PaymentController`): likely rename-drift of `voucher-receives.*` /
  `voucher-payments.*` (those screens exist and are gated) — verify the route
  mapping, then either remap code or seed the two families.
- `pharmacy.*` (Bar/Restaurant `ProductUnit`/`ProductUpload` + the
  `GuestUploadController`/`TableManageController::storeOrUpdate` copy-paste
  gates): candidate remap to `bar.inventories.*`. `storeOrUpdate()` in
  `TableManageController` still gates on `pharmacy.edit` (an upsert — decide
  whether it needs `.create`, `.edit`, or both).
- View-side drift (out of scope while `hasPermission()` gates are
  byte-frozen): e.g. the table-manage views gate buttons on
  `rst.table-manages.index`, which is not in the table either.

## SQL appendix (draft — review before applying)

```sql
-- === Permission seed draft (2026-10-16) — NOT APPLIED ===
-- 42 permission rows + 79 permission_user grants.
-- Verify the modeled-holder mapping per family above before running.

INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200001,'Supplier Types','supplier.types.index',NULL,1,1,110060,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200001,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200002,'View','supplier.types.view',NULL,1,1,110060,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200002,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200003,'Create','supplier.types.create',NULL,1,1,110060,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200003,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200004,'Edit','supplier.types.edit',NULL,1,1,110060,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200004,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200005,'Delete','supplier.types.delete',NULL,1,1,110060,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200005,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200006,'Store Items','items.index',NULL,1,1,110061,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200006,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200006,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200007,'View','items.view',NULL,1,1,110061,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200007,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200007,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200008,'Create','items.create',NULL,1,1,110061,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200008,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200008,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200009,'Edit','items.edit',NULL,1,1,110061,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200009,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200009,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200010,'Delete','items.delete',NULL,1,1,110061,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200010,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200010,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200011,'Upload','items.upload',NULL,1,1,110061,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200011,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200011,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200012,'Store Purchases','purchases.index',NULL,1,1,110062,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200012,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200012,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200013,'View','purchases.view',NULL,1,1,110062,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200013,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200013,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200014,'Create','purchases.create',NULL,1,1,110062,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200014,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200014,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200015,'Edit','purchases.edit',NULL,1,1,110062,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200015,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200015,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200016,'Delete','purchases.delete',NULL,1,1,110062,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200016,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200016,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200017,'Approve','purchases.approve',NULL,1,1,110062,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200017,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200017,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200018,'GRN List','purchases.grn.list',NULL,1,1,110062,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200018,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200018,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200019,'Purchase Receives','purchase.receives.index',NULL,1,1,110063,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200019,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200019,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200020,'View','purchase.receives.view',NULL,1,1,110063,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200020,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200020,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200021,'Create','purchase.receives.create',NULL,1,1,110063,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200021,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200021,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200022,'Delete','purchase.receives.delete',NULL,1,1,110063,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200022,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200022,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200023,'Goods Requisitions','create.requisitions.index',NULL,1,1,110064,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200023,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200023,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200024,'View','create.requisitions.view',NULL,1,1,110064,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200024,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200024,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200025,'Create','create.requisitions.create',NULL,1,1,110064,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200025,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200025,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200026,'Edit','create.requisitions.edit',NULL,1,1,110064,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200026,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200026,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200027,'Delete','create.requisitions.delete',NULL,1,1,110064,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200027,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200027,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200028,'Approve','create.requisitions.approve',NULL,1,1,110064,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200028,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200028,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200029,'GIN List','create.requisitions.gin.list',NULL,1,1,110064,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200029,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200029,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200030,'Item Details','gs.reports.item.details',NULL,1,1,110065,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200030,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200030,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200031,'Item Ledger','gs.reports.item.ledger',NULL,1,1,110065,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200031,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200031,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200032,'Fund Transfers','fund.transfers.index',NULL,1,1,110066,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200032,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200032,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200033,'Create','fund.transfers.create',NULL,1,1,110066,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200033,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200033,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200034,'Edit','fund.transfers.edit',NULL,1,1,110066,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200034,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200034,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200035,'Delete','fund.transfers.delete',NULL,1,1,110066,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200035,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200035,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200036,'Approve','fund.transfers.approve',NULL,1,1,110066,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200036,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200036,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200040,'Expense Analysis','report.expense-analysis',NULL,1,1,110067,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200040,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200040,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200041,'Nominal Account Ledger','report.nominal-account-ledger',NULL,1,1,110067,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200041,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200041,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200042,'Ratio Analysis','report.ratio-analysis',NULL,1,1,110067,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200042,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200042,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200043,'Received Payment Statement','report.received-payment-statement',NULL,1,1,110067,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200043,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200043,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200044,'Revenue Analysis','report.revenue-analysis',NULL,1,1,110067,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200044,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200044,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permissions` (`id`,`name`,`slug`,`description`,`created_by`,`updated_by`,`parent_permission_id`,`created_at`,`updated_at`,`status`) VALUES (200045,'Transaction Ledger','report.transaction-ledger',NULL,1,1,110067,'2026-10-16 00:00:00','2026-10-16 00:00:00',1);
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200045,2,'2026-10-16 00:00:00','2026-10-16 00:00:00');
INSERT INTO `permission_user` (`permission_id`,`user_id`,`created_at`,`updated_at`) VALUES (200045,3,'2026-10-16 00:00:00','2026-10-16 00:00:00');
```
