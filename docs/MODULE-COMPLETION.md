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
| Booking list/board | Migrated; lifecycle/detail/edit pending | Staging pending |
| Dashboard | Migrated | Staging pending |
| Housekeeping | Layout migrated; status behavior unchanged | Staging pending |
| Booking purposes/platforms | List/filter/create/edit migrated | Staging pending |
| Booking notes | List/filter presentation/edit migrated; backend blockers below | Pending |
| Payments, checkout, invoices, night audit | Not migrated | Pending |
| Remaining hotel setup screens | Not migrated | Pending |

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
