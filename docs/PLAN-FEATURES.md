# Plan B — Feature Roadmap (new capabilities missing from the system)
Companion to `PLAN-MODERNIZATION.md` — features listed here **do not exist today** (verified against routes, DB schema, UI crawl and all 4 branches).

Legend: 🟢 quick win (days) · 🟡 medium (1–3 wks) · 🔴 major (module-level)

---

## 1. Revenue & distribution (biggest business gap)

| # | Feature | Why missing / value | Scope sketch | Effort |
|---|---|---|---|---|
| F1 | **Online payments at booking** (deposit or full prepay) — Stripe / HitPay / eGHL / Curlec (MY) | Today “booking” = unpaid reservation; no gateway code anywhere (`payment_way` is a free-text label). Converts web traffic into guaranteed revenue, kills no-shows | Payment intent service, webhook verification, `payments` ledger rows, refund flow, auto folio entry, currency handling (uses existing currency-conversion table) | 🔴 |
| F2 | **Channel manager / OTA sync** (Booking.com, Agoda, Airbnb, Expedia) + iCal publish/subscribe | The PMS has OTA *platform* field on bookings but zero connectivity; double-booking risk today is manual | Rate/availability mapping layer on room categories & dates; ARI push/pull via channel-API or mid-tier (SiteMinder/Cloudbeds-style); two-way reservations import; conflict engine | 🔴 (start with iCal 🟡) |
| F3 | **Rate plans, seasons & promos** — BAR/LOSEA/Corporate/Gov rates, single/multi-day occupancy pricing, offer rules (7-advance, long-stay discount), promo codes | `room_prices` has one price per category + capacity only; the public site advertises “Fantastic Offers” with no engine behind it | rate_plans, plan_prices(date), restrictions (min-stay/CTA/CTD), closed-to-arrival, promo table + checkout integration | 🔴 |
| F4 | **Dynamic pricing / revenue management** — occupancy-based suggested rates, competitor rate feed | No pricing intelligence | Rules engine first (occupancy %, LOS, pickup pace), ML later via feed providers (OTA Insight etc.) | 🟡 rules |
| F5 | **Up-sell & early check-in/out marketplace** | Booking wizard has extra-charge only as manual line | Offer table, pre-arrival email/portal offer, auto-post to folio | 🟡 |

## 2. Reservations & front desk

| # | Feature | Notes | Effort |
|---|---|---|---|
| F6 | **Reservation calendar grid** (rooms × days, drag-drop move/extend, multi-select room-block) | Today = data tables + “room boxes” cookie cart; single biggest daily-UI gap | 🔴 |
| F7 | **Room types/bed types/inventory guards** — overbooking limit, out-of-order (OOO) & housekeeping blocking that feeds availability | `rooms.status` toggles exist but no OOO reasons/blocks nor availability ledger | 🟡 |
| F8 | **Group & function contracts** (corporate/tour), rooming lists, allotment/cutoff dates, master-bill split | `booking_member_details` exists but no group/cutoff model; Banquet module is disabled | 🔴 |
| F9 | **Walk-in & in-stay changes**: extend-stay already exists but no rate-change audit, no room-move history view, no folio transfer (room A→B) | Extend-checkout route exists; add move-room with folio merge + log | 🟡 |
| F10 | **Self-service / kiosk check-in-out + digital registration card** (zesan branch only added terms page) | ID scan (MyKad/passport OCR), signature capture, key-encoder hook (later) | 🟡 |
| F11 | **Guest CRM revival** — profiles, preferences, stay history, corporate accounts with contracts, blacklists, birthday/marketing lists | CRM was **deleted from code but DB tables + permissions remain** (`c_r_m_customers`, modules row id 170000). Restore + extend: leads, cases, communication log | 🟡 MVP |
| F12 | **Lost & found / guest requests / concierge tasks** — small ticket type with SLA | none exists | 🟢 |

## 3. Housekeeping, maintenance & operations

| # | Feature | Notes | Effort |
|---|---|---|---|
| F13 | **Housekeeping board & mobile checklist** — dirty/clean/inspect states with timestamps, attendant assignment, priorities (arrive-by), turndown | `HouseKeeping` page shows room boxes by status (read-mostly); no task workflow | 🟡 |
| F14 | **Maintenance tickets** — room/asset issues, schedule, parts cost to GL | none | 🟡 |
| F15 | **Assets & minibar stock** — minibar consumption auto-post to folio (currently POS only) | link Bar/GeneralStore stock to room folio | 🟡 |
| F16 | **Staff shift & attendance** — the HRM module was removed (dead `sync-data` routes prove it). Re-implement minimal roster per department (front desk, HK, kitchen) tied to night-audit and POS cash shifts | 🔴 if full HRM; 🟡 if roster only | |

## 4. Finance & compliance (Malaysia-aware)

| # | Feature | Notes | Effort |
|---|---|---|---|
| F17 | **e-Invoicing — LHDN MyInvois** (mandatory phases 2024-26) | PMS generates PDF invoices only; no JSON API validation, no QR/UUID consolidation | 🔴 |
| F18 | **Tourism tax / SST handling per locality** | VAT table is a single flat setting; needs tax rules by date/type, exemption codes, reporting | 🟡 |
| F19 | **Night-audit hardening** — auto-post recurring charges, rate-change posting, transit and no-show fee engine, day-close lock + reopen approval, audit trail | night-audit exists but is a manual report; add posting & locking rules | 🟡 |
| F20 | **AR / city ledger** — corporate invoices, credit limits, aging report, payment terms & reminders | `hotel_customer_ledgers` exists ad-hoc | 🟡 |
| F21 | **Payment-gateway reconciliation report** + cash-declaration per shift (ties POS `payments`, front-desk collections, banks) | none | 🟡 |
| F22 | **Multi-currency at transaction level** (quote USD, settle MYR with saved FX rate — currency_conversions table exists but unused by invoices) | 🟢 use it in invoice engine | |

## 5. Guest experience & communications

| # | Feature | Notes | Effort |
|---|---|---|---|
| F23 | **Notification backbone**: queued mail (SMTP today is inline!), SMS/Twilio, **WhatsApp Business**, email templates UI (zesan `contact-mail` templates are the seed), triggers per lifecycle event (confirm, 24h-before, check-in, receipt, review request) | 🔴 | |
| F24 | **Guest portal / mobile web** — reservation lookup, folio, pre-check-in, invoice PDF, upsell | builds on F1/F10 | 🟡 |
| F25 | **Loyalty & repeat-guest offers**, coupon codes for direct booking | none | 🟡 |
| F26 | **Reviews & reputation** — post-stay Google review prompt, feedback scoring | none | 🟢 |
| F27 | **Public-site content refresh** — real editorial content (currently lorem-ipsum sections live), SEO metadata per page, image CDN, schema.org, multilingual EN/MS/中文 (Chinese guests over-the-air) | 🟡 | |

## 6. Platform & analytics

| # | Feature | Notes | Effort |
|---|---|---|---|
| F28 | **Mobile app** (staff): housekeeping/maintenance/manager KPIs — consumes the v1 API from Modernization Phase 1.6 | 🔴 | |
| F29 | **Manager BI**: occupancy, ADR, RevPAR, pickup pace, LOS, cancellation, channel mix, F&B attach-rate, per-meal covers; drill-through reports + scheduled email | data exists; no analytics UI 🟡 | |
| F30 | **Webhooks + public REST API + Zapier/n8n** — partner integrations (door locks, accounting Xero, OTA) | API skeleton is dead code today (Api routes) 🟡 | |
| F31 | **Multi-property** — company/branch scoping is half-there (`company_id`, orphan `branch_id`); finish: per-property rates, cross-booking, consolidated reports | 🔴 (only if expansion planned) | |
| F32 | **i18n of the admin UI** (EN/MS) + timezone & locale-aware formats | none (strings hardcoded in blades) 🟡 | |
| F33 | **Audit & compliance log viewer** — activity_logs table populated but no UI; add diff view, export, retention policy | 🟢 | |
| F34 | **Import/export everywhere** — bulk room-block import, guest CSV (exists), rate import, GL export CSV/Excel standard buttons; fix the broken GeneralStore export | 🟢 | |

---

## Suggested sequencing (value ÷ effort, assuming Plan-A phases in flight)

**Wave 1 (weeks 1–6, during Modernization Phase 1–2):** F11 CRM-revival, F6 calendar (design), F13 housekeeping board, F18/F22 tax-currency fixes, F23 notification backbone, F17 groundwork (MyInvois sandbox), F33 audit viewer, F34 exports, F12 requests.
**Wave 2 (months 2–3):** F1 payments → F5 upsell, F3 rate plans (unblocks F2), F7 inventory guards, F19 night-audit hardening, F20 AR, F10 digital check-in, F21 recon, F26 reviews, F24 portal-alpha.
**Wave 3 (months 4–5):** F2 channel manager, F8 groups/banquet re-enable, F4 dynamic pricing, F16 roster, F25 loyalty, F27 content/i18n (F32), F28 mobile staff app.
**Wave 4:** F14 maintenance scheduling, F15 minibar, F29 BI suite, F30 public API+webhooks, F31 multi-property (only on business need).

**KPIs to wire from day 1** (F29 must track them): occupancy %, ADR, RevPAR, direct-vs-OTA mix, cancel/no-show %, pickup pace, F&B attach %, check-in NPS, AR days.

> Dependency warning: F2 (channel manager) requires F3 (rate plans) and real availability math (F7) first, otherwise the sync will fight manual overrides. F17 (e-invoice) requires the invoice engine consolidation (Modernization P3 item 6/7) to have one document source.
