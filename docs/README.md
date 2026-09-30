# Documentation — MM Heritage Hotel suite

Generated 2026-09-30 from a full-repository analysis: all four git branches (`main`, `AI`,
`development`, `zesan`), the entire codebase (Laravel 8 monolith, 9 modules, 1,014 routes) and a
live end-to-end run of the app against the shipped production database dump (the UI was booted
locally, logged in as the admin account and every screen exercised).

| Document | What's inside |
|---|---|
| [USER-MANUAL.md](USER-MANUAL.md) | Screen-by-screen manual: login, front-desk PMS (booking → check-in → folio → checkout → night audit), POS (Restaurant/Bar), stores, accounting, permissions, public booking site, typical routines, and a **known-issues table with workarounds** |
| [ANALYSIS.md](ANALYSIS.md) | Current-state audit: product scope, branch/git reality (incl. the **missing `module/CRM` submodule that production depends on**), architecture, security & ops findings, code-quality and UX audit |
| [PLAN-MODERNIZATION.md](PLAN-MODERNIZATION.md) | Plan A — phased modernization program (stabilize → upgrade → design system → workflow rebuilds → public site → deploy/migration), with tasks, tools, team and exit criteria |
| [PLAN-FEATURES.md](PLAN-FEATURES.md) | Plan B — feature roadmap: online payments, channel manager/OTA sync, rate plans, guest CRM, e-invoicing (LHDN), notifications, self check-in, BI, mobile app, etc., sequenced in waves |
| [SCREENSHOTS.md](SCREENSHOTS.md) | Index of the 251 captured screenshots in [`/screenshots`](../screenshots) (admin + site, grouped by module; 🚧 marks screens that currently 500 in the shipped build) |

> ⚠️ Act on these first: rotate the SMTP & DB credentials and APP_KEY that are committed in `.env`,
> remove the DB dump from the public repo history, and restrict the sidebar *Backup Database* link —
> details in `ANALYSIS.md §5`.
