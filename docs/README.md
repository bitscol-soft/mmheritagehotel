# Documentation — MM Heritage Hotel suite

Generated 2026-09-30 from a full-repository analysis: all four git branches (`main`, `AI`,
`development`, `zesan`), the entire codebase (Laravel 8 monolith, 9 modules, 1,014 routes) and a
live end-to-end run of the app against the shipped production database dump (the UI was booted
locally, logged in as the admin account and every screen exercised).

| Document | What's inside |
|---|---|
| [USER-WIKI-EN-BN.md](USER-WIKI-EN-BN.md) | **Bilingual User Documentation, Wiki & Help Center (English + বাংলা)** covering all 10 ERP modules, keyboard shortcuts (`Ctrl+K`, `g` chords), Slide-Over App Drawers, POS/KDS workflows, Night Audit SOPs, and troubleshooting (`/preview/wiki` & `/wiki`) |
| [DEPLOYMENT-CHECKLIST-EN-BN.md](DEPLOYMENT-CHECKLIST-EN-BN.md) | **Bilingual Staging & Production Deployment Runbook (English + বাংলা)** covering pre-deploy checks, `W0.1–W0.3` GitHub Actions secrets, cache clearing, and instant `UI_ADMIN_SHELL=false` rollback |
| [FRONTEND-DEV-PLAN.md](FRONTEND-DEV-PLAN.md) | Living execution plan for **Waves W0–W7**, **D3 Light/Dark Glassmorphism**, and **Backlog B1–B4** with full commit map and verification status |
| [TAILWIND-DEVELOPMENT.md](TAILWIND-DEVELOPMENT.md) | Technical architecture of the scoped `.mm-ui` / `.mm-shell` Tailwind 3.4.19 layer, `<x-mm.*>` Blade components, 319-screen preview suite, and Playwright browser specs |
| [USER-MANUAL.md](USER-MANUAL.md) | Screen-by-screen manual: login, front-desk PMS (booking → check-in → folio → checkout → night audit), POS (Restaurant/Bar), stores, accounting, permissions, public booking site, typical routines, and a **known-issues table with workarounds** |
| [DEPLOYMENT.md](DEPLOYMENT.md) | SSH/rsync GitHub Actions deployment setup for `deploy-staging.yml` and `deploy-production.yml` |
| [BUGS.md](BUGS.md) | Tracked functional & legacy codebase quirks (deliberately isolated from UI diffs) |
| [ANALYSIS.md](ANALYSIS.md) | Current-state audit: product scope, branch/git reality (incl. the **missing `module/CRM` submodule that production depends on**), architecture, security & ops findings, code-quality and UX audit |
| [PLAN-MODERNIZATION.md](PLAN-MODERNIZATION.md) | Plan A — phased modernization program (stabilize → upgrade → design system → workflow rebuilds → public site → deploy/migration), with tasks, tools, team and exit criteria |
| [PLAN-FEATURES.md](PLAN-FEATURES.md) | Plan B — feature roadmap: online payments, channel manager/OTA sync, rate plans, guest CRM, e-invoicing (LHDN), notifications, self check-in, BI, mobile app, etc., sequenced in waves |
| [SCREENSHOTS.md](SCREENSHOTS.md) | Index of the 251 captured screenshots in [`/screenshots`](../screenshots) (admin + site, grouped by module; 🚧 marks screens that currently 500 in the shipped build) |

> ⚠️ Act on these first: rotate the SMTP & DB credentials and APP_KEY that are committed in `.env`,
> remove the DB dump from the public repo history, and restrict the sidebar *Backup Database* link —
> details in `ANALYSIS.md §5`.
