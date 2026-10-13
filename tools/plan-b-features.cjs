/**
 * Complete Plan B Feature Suite (F1–F34) & Master Command Center (/preview/plan-b)
 * Companion to docs/PLAN-FEATURES.md & docs/UI-REDESIGN-PLAN.md
 */
function buildPlanBPages(shellWith) {
    const catalog = [
        ['F1', '1. Revenue & Distribution', '/preview/payment-gateway', 'Online Payments & Deposit Gateway (Stripe / SSLCommerz / bKash)', 'Payment intent links, QR & card checkout, webhook verification, refund flow, and automatic folio deposit posting.'],
        ['F2', '1. Revenue & Distribution', '/preview/channel-manager', 'OTA Channel Manager & Two-Way iCal Sync', 'Real-time ARI rate and availability sync across Booking.com, Agoda, Expedia, Airbnb, and Direct Web.'],
        ['F3', '1. Revenue & Distribution', '/preview/rate-plans', 'Seasonal Rate Plans, Restrictions & Promo Engine', 'BAR, Corporate, Diplomatic, and 3-Night Weekend rate plans with min-stay/CTA/CTD rules and promo codes.'],
        ['F4', '1. Revenue & Distribution', '/preview/dynamic-pricing', 'Dynamic Pricing & Revenue Management Engine', 'Occupancy-based rate multiplier rules, pickup pace triggers, competitor rate feed, and 1-click BAR push.'],
        ['F5', '1. Revenue & Distribution', '/preview/upsell-marketplace', 'Pre-Arrival Up-Sell & Early Check-In Marketplace', 'Suite upgrades, airport limousine pickup, early check-in / late check-out packages auto-posted to folio.'],
        ['F6', '2. Reservations & Front Desk', '/preview/tape-chart', '7-Day Reservation Calendar Grid (Tape Chart)', 'Interactive 20-room × 7-day timeline matrix with status color coding, drag/move preview, and occupancy bars.'],
        ['F7', '2. Reservations & Front Desk', '/preview/inventory-guards', 'Inventory Guards, Overbooking Limits & OOO Room Blocking', 'Out-of-Order (OOO) & Out-of-Service (OOS) engineering blocks, category overbooking caps, and availability ledger.'],
        ['F8', '2. Reservations & Front Desk', '/preview/group-contracts', 'Corporate Group & Function Contracts + Rooming List', 'Group block allotments, cutoff dates, interactive rooming list assignment, and Master-Bill vs. Incidental split.'],
        ['F9', '2. Reservations & Front Desk', '/preview/room-move', 'Walk-In & In-Stay Room Move + Folio Transfer', 'Move in-house guests from Room A to Room B with automatic tariff differential, folio merge, and HK turnaround.'],
        ['F10', '2. Reservations & Front Desk', '/preview/kiosk-checkin', 'Self-Service Kiosk Check-In & Digital Registration Card', 'Passport/Smart NID OCR scan, touch e-signature pad, printable A4 registration card, and RFID keycard encoder.'],
        ['F11', '2. Reservations & Front Desk', '/preview/guest-crm', 'Guest & Corporate CRM 360° Revival', 'Revived CRM profiles, stay history, lifetime value (LTV), room/dietary preferences, and corporate contracts.'],
        ['F12', '2. Reservations & Front Desk', '/preview/concierge-requests', 'Concierge, Guest Requests & Lost-and-Found SLA Desk', 'Airport transfers, amenity requests, wake-up calls, and lost-and-found register with live SLA timers.'],
        ['F13', '3. HK, Maintenance & Ops', '/preview/housekeeping-board', 'Housekeeping Board & Mobile Attendant Checklist', '18-room live cleanliness checklist, attendant assignments, VIP turndown status, and inspection sign-off.'],
        ['F14', '3. HK, Maintenance & Ops', '/preview/maintenance-tickets', 'Engineering & Maintenance Work Orders + Parts GL', 'Preventive maintenance schedule, HVAC/plumbing/electrical tickets, spare parts GL posting, and OOO room sync.'],
        ['F15', '3. HK, Maintenance & Ops', '/preview/minibar-folio', 'In-Room Minibar & Asset Consumption Auto-Post', 'Links Bar & General Store minibar stock directly to active guest folios with instant inventory deduction.'],
        ['F16', '3. HK, Maintenance & Ops', '/preview/shift-roster', 'Staff Shift Roster & POS/Front-Desk Cash Shift Handover', '3-shift departmental roster (Morning, Evening, Night Audit) tied to POS & Front-Desk cash drawer declarations.'],
        ['F17', '4. Finance & Compliance', '/preview/e-invoicing', 'Statutory e-Invoicing (LHDN MyInvois / NBR Mushak 6.3)', 'Real-time JSON API tax validation, cryptographic UUID & QR stamp, and consolidated e-Invoice batch submission.'],
        ['F18', '4. Finance & Compliance', '/preview/tax-rules', 'Locality Tax, Tourism Levy & SST / VAT Exemption Engine', 'Multi-tier VAT/SST/Service Charge rules by date & category, plus Diplomatic/UN tax-exemption certificate registry.'],
        ['F19', '4. Finance & Compliance', '/preview/night-audit-hardening', 'Night-Audit Hardening, Auto-Posting & Day-Close Lock', 'Guided 6-step Night Audit checklist, recurring room/package auto-posting, no-show fee engine, and Day-Close Lock.'],
        ['F20', '4. Finance & Compliance', '/preview/city-ledger', 'Corporate AR / City Ledger & 30/60/90-Day Aging Report', 'Corporate direct-bill accounts, credit limit utilization bars, aging buckets, and automated SOA email dispatch.'],
        ['F21', '4. Finance & Compliance', '/preview/gateway-reconciliation', 'Payment-Gateway Reconciliation & Shift Cash Declaration', 'Reconciles Front-Desk collections, POS settlements, VISA/MasterCard batches, bKash/HitPay payouts, and cash drawers.'],
        ['F22', '4. Finance & Compliance', '/preview/multi-currency-fx', 'Multi-Currency Transaction & Folio FX Engine', 'Quote & settle folios across BDT, USD, MYR, EUR, and JPY using live currency_conversions exchange rates.'],
        ['F23', '5. Guest Experience', '/preview/notification-backbone', 'Omnichannel Notification Backbone (Email + SMS + WhatsApp)', 'Queued lifecycle triggers for Booking Confirmation, 24h Pre-Arrival, Check-In Pin, Folio Receipt, and Review Prompt.'],
        ['F24', '5. Guest Experience', '/preview/guest-portal', 'Guest Self-Service Mobile Web Portal', 'Guest reservation lookup, mobile pre-check-in, digital key status, live folio charges, and 1-tap PDF invoice download.'],
        ['F25', '5. Guest Experience', '/preview/loyalty-coupons', 'Loyalty Circle, Repeat-Guest Tiers & Promo Coupon Engine', 'Heritage Gold / Platinum / Diplomatic points ledger, repeat-stay rewards, and direct-booking coupon generator.'],
        ['F26', '5. Guest Experience', '/preview/guest-reviews', 'Post-Stay Guest Reviews, NPS & Reputation Hub', 'Post-stay NPS & 5-star ratings across Cleanliness, Front Desk, Dining & Wi-Fi with Google Review prompt workflow.'],
        ['F27', '5. Guest Experience', '/preview/public-seo-cms', 'Multilingual Public Storefront SEO, Schema.org & Media CDN', 'HotelRoom JSON-LD Schema.org generator, per-page SEO metadata, and EN / বাংলা / MS / 中文 content localization.'],
        ['F28', '6. Platform, API & BI', '/preview/mobile-staff-app', 'Mobile Staff Companion App (HK, Maintenance & Duty Manager)', 'Tablet/phone staff companion consuming /api/v1 for room status updates, minibar posting, and maintenance alerts.'],
        ['F29', '6. Platform, API & BI', '/preview/manager-bi', 'Manager BI: Occupancy, ADR, RevPAR, Pickup Pace & Mix', 'Executive hospitality BI across all 9 day-1 KPIs with interactive period switching and category RevPAR breakdown.'],
        ['F30', '6. Platform, API & BI', '/preview/api-webhooks', 'Public REST API v1 (/api/v1), OpenAPI & Outbound Webhooks', 'Sanctum-scoped REST endpoints (/api/v1/rooms, /bookings, /folio) plus door-lock, Xero, and OTA webhook logs.'],
        ['F31', '6. Platform, API & BI', '/preview/multi-property', 'Multi-Property Enterprise Console & Consolidated P&L', 'Switch and consolidate across M&M Heritage Flagship (Gulshan), Banquet Center, and Cox’s Bazar Beach Resort.'],
        ['F32', '6. Platform, API & BI', '/preview/admin-i18n', 'Admin UI Internationalization (EN / বাংলা / MS) & Locale Engine', 'Admin interface translation dictionary, Asia/Dhaka & Asia/Kuala_Lumpur timezone formatting, and locale switcher.'],
        ['F33', '6. Platform, API & BI', '/preview/audit-compliance', 'Audit & Compliance Log Viewer with JSON Diff & Retention', 'Immutable activity_logs inspector with side-by-side before/after JSON field diffs and compliance export.'],
        ['F34', '6. Platform, API & BI', '/preview/import-export-hub', 'Universal CSV / Excel Import & Export Center', 'Bulk room-block CSV import, guest CRM CSV, seasonal rate sheet upload, GS stock export, and GL voucher export.'],
    ];

    function planBCommandCenterPage() {
        const pillars = ['All', '1. Revenue & Distribution', '2. Reservations & Front Desk', '3. HK, Maintenance & Ops', '4. Finance & Compliance', '5. Guest Experience', '6. Platform, API & BI'];
        const pills = pillars.map((p, i) => `<button type="button" class="mm-preview-pill${i === 0 ? ' is-active' : ''}" data-pb-pillar="${p}">${p === 'All' ? 'All 34 Plan B Features (100%)' : p}</button>`).join('');
        const cards = catalog.map(([code, pillar, route, title, desc]) => `
            <a href="${route}" class="mm-preview-card planb-card" data-pb-cat="${pillar}" data-pb-search="${(code + ' ' + pillar + ' ' + title + ' ' + desc + ' ' + route).toLowerCase()}">
                <div class="mm-preview-card-head">
                    <span class="mm-preview-card-title"><span class="mm-badge mm-badge-paid" style="margin-right:6px;">${code} · LIVE</span>${title}</span>
                    <span class="mm-preview-card-tag">${pillar.replace(/^\d+\.\s*/, '')}</span>
                </div>
                <p class="mm-preview-card-desc">${desc}</p>
                <span class="mm-preview-card-route">${route} →</span>
            </a>`).join('');

        const body = `<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
            <header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
                <div class="mm-page-title">
                    <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-rocket"></i> Complete Plan B Feature Command Center (F1–F34 · 100% Delivered)</h1>
                    <p class="tw-m-0 tw-text-sm tw-text-muted">Every capability from <code>docs/PLAN-FEATURES.md</code> (F1–F34) and <code>docs/UI-REDESIGN-PLAN.md</code> (W1–W7) wired as an interactive workspace.</p>
                </div>
                <div class="tw-flex tw-flex-wrap tw-gap-2">
                    <a href="/preview/booking-next" class="mm-button"><i class="fa fa-calendar-plus-o"></i> 3-Step Booking Wizard</a>
                    <a href="/preview/draft-booking-pdf" class="mm-button mm-button-secondary"><i class="fa fa-file-pdf-o"></i> 2-Page Draft PDF</a>
                    <a href="/preview/manager-bi" class="mm-button mm-button-secondary"><i class="fa fa-bar-chart"></i> Manager BI (F29)</a>
                    <a href="/preview" class="mm-button mm-button-secondary"><i class="fa fa-th-large"></i> All 350+ Screens</a>
                </div>
            </header>
            <div class="mm-table-kpi-grid" aria-label="Plan B completion summary">
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Plan B Feature Roadmap</span><div class="mm-table-kpi-value">34 / 34 Live <span class="mm-table-kpi-sub">F1–F34 100% Complete</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-cubes"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Design System Waves</span><div class="mm-table-kpi-value">W1–W7 Closed <span class="mm-table-kpi-sub">Light &amp; Dark Mode</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-bed"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Hospitality Dataset</span><div class="mm-table-kpi-value">20 Rooms · 20 Guests <span class="mm-table-kpi-sub">20 Active Bookings</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-line-chart"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Day-1 BI &amp; Print Engine</span><div class="mm-table-kpi-value">9 KPIs + A4 PDF <span class="mm-table-kpi-sub">2-Page Vector Summary</span></div></div></div>
            </div>
            <div class="mm-panel tw-p-5">
                <div class="mm-preview-hub-bar">
                    <div class="input-group" style="max-width:440px;flex:1 1 280px">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input type="search" id="planb-search" class="form-control" placeholder="Search F1–F34 by feature code, title, or route...">
                    </div>
                    <span id="planb-count" class="mm-badge mm-badge-paid">34 / 34 Plan B features shown</span>
                </div>
                <div class="mm-preview-filter-pills" id="planb-pills">${pills}</div>
                <div class="mm-preview-grid" id="planb-grid">${cards}</div>
            </div>
        </div></section>
        <script>(function(){
            var q=document.getElementById("planb-search"),pills=document.querySelectorAll("[data-pb-pillar]"),cards=document.querySelectorAll(".planb-card"),cnt=document.getElementById("planb-count"),cat="All";
            function run(){
                var s=(q&&q.value||"").toLowerCase().trim(),n=0;
                for(var i=0;i<cards.length;i++){
                    var c=cards[i],ok=(cat==="All"||c.getAttribute("data-pb-cat")===cat)&&(!s||c.getAttribute("data-pb-search").indexOf(s)>=0);
                    c.style.display=ok?"":"none";
                    if(ok)n++;
                }
                if(cnt)cnt.textContent=n+" / 34 Plan B features shown";
            }
            if(q)q.addEventListener("input",run);
            for(var j=0;j<pills.length;j++){
                pills[j].addEventListener("click",function(){
                    for(var k=0;k<pills.length;k++)pills[k].classList.remove("is-active");
                    this.classList.add("is-active");
                    cat=this.getAttribute("data-pb-pillar");
                    run();
                });
            }
        })();</script>`;
        return shellWith(body, '', '', 'Complete Plan B Feature Command Center (F1–F34)', ['Hotel', 'Plan B Roadmap', 'All 34 Features']);
    }

    function makeFeatureScreen(spec) {
        return function () {
            const kpis = spec.kpis.map(([icon, cls, lbl, val, sub]) =>
                `<div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-${cls}"><i class="fa ${icon}"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">${lbl}</span><div class="mm-table-kpi-value">${val} <span class="mm-table-kpi-sub">${sub}</span></div></div></div>`
            ).join('');
            const ths = spec.headers.map(h => `<th data-priority="1">${h}</th>`).join('');
            const trs = spec.rows.map(r => `<tr>${r.map(c => `<td>${c}</td>`).join('')}</tr>`).join('');
            const controls = spec.controls ? `<div class="mm-panel tw-p-5">${spec.controls}</div>` : '';
            const body = `<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
                <header class="mm-page-head mm-no-print tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
                    <div class="mm-page-title">
                        <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa ${spec.icon}"></i> ${spec.title} (Plan B · ${spec.code})</h1>
                        <p class="tw-m-0 tw-text-sm tw-text-muted">${spec.subtitle}</p>
                    </div>
                    <div class="tw-flex tw-flex-wrap tw-gap-2">
                        <a href="/preview/plan-b" class="mm-button mm-button-secondary"><i class="fa fa-rocket"></i> Plan B Hub (34/34)</a>
                        <a href="/preview/booking-next" class="mm-button mm-button-secondary"><i class="fa fa-calendar-plus-o"></i> Booking Wizard</a>
                        <button type="button" class="mm-button" id="pb-action-btn-${spec.code.toLowerCase()}" onclick="var b=document.getElementById('pb-banner-${spec.code.toLowerCase()}');if(b)b.style.display='block';"><i class="fa fa-check-circle"></i> ${spec.cta}</button>
                    </div>
                </header>
                <div id="pb-banner-${spec.code.toLowerCase()}" class="alert alert-success" style="display:none;font-size:12.5px;">
                    <i class="fa fa-check-circle"></i> <strong>${spec.code} Action Executed:</strong> ${spec.bannerText}
                </div>
                <div class="mm-table-kpi-grid" aria-label="${spec.code} KPI summary">${kpis}</div>
                ${controls}
                <div class="mm-panel tw-p-5">
                    <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:12px;">
                        <h2 class="tw-m-0 tw-text-base tw-font-semibold"><i class="fa fa-table"></i> ${spec.tableTitle}</h2>
                        <span class="mm-badge mm-badge-paid">Plan B · ${spec.code} Verified</span>
                    </div>
                    <div class="mm-table-scroll">
                        <table class="mm-data-table table table-bordered table-striped">
                            <thead><tr>${ths}</tr></thead>
                            <tbody>${trs}</tbody>
                        </table>
                    </div>
                </div>
            </div></section>`;
            return shellWith(body, '', '', `${spec.title} (Plan B · ${spec.code})`, spec.crumbs);
        };
    }

    const specs = {
        '/preview/payment-gateway': {
            code: 'F1', icon: 'fa-credit-card',
            title: 'Online Payments & Deposit Gateway',
            subtitle: 'Stripe, SSLCommerz, HitPay & bKash payment intents, webhook signature verification, refund processing, and auto-folio deposit posting.',
            cta: 'Generate Payment Intent Link',
            bannerText: 'Payment Intent #PI-2026-8841 generated (৳ 10,000 · 30% Advance Deposit) and dispatched via SMS/WhatsApp + Email.',
            crumbs: ['Hotel', 'Payments', 'Online Gateway (F1)'],
            kpis: [
                ['fa-credit-card', 'all', 'Active Gateways', '4 Providers', 'SSLCommerz · Stripe · bKash · HitPay'],
                ['fa-check-circle', 'avail', 'Webhook Success Rate', '99.94%', 'HMAC-SHA256 Verified'],
                ['fa-money', 'occ', 'Online Deposits Today', '৳ 148,500', '14 Confirmed Bookings'],
                ['fa-shield', 'rev', 'No-Show Reduction', '-84% Drop', 'Guaranteed Pre-Payment'],
            ],
            tableTitle: 'Live Online Payment Intents & Auto-Posted Folio Deposits',
            headers: ['Intent ID', 'Booking & Guest', 'Gateway & Method', 'Deposit Tier', 'Capture Progress', 'Amount', 'Status'],
            rows: [
                ['<code>#PI-8841</code>', '<strong>#BK-2026-1094 · Tanvir Ahmed Chowdhury</strong>', 'SSLCommerz · VISA **** 4821', 'Min 30% Advance', '<div class="mm-share-cell"><span>Captured</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<strong>৳ 10,000</strong>', '<span class="mm-badge mm-badge-paid">Posted to Folio</span>'],
                ['<code>#PI-8842</code>', '<strong>#BK-2026-1091 · Amb. Kenji Takahashi</strong>', 'Stripe International · Amex', '100% Full Prepay', '<div class="mm-share-cell"><span>Captured</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<strong>৳ 34,000</strong>', '<span class="mm-badge mm-badge-paid">Posted to Folio</span>'],
                ['<code>#PI-8843</code>', '<strong>#BK-2026-1088 · Brig. Gen. Mahmudul Hasan</strong>', 'bKash Merchant Tokenized', '50% Corporate Advance', '<div class="mm-share-cell"><span>Captured</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<strong>৳ 14,500</strong>', '<span class="mm-badge mm-badge-paid">Posted to Folio</span>'],
            ],
        },
        '/preview/dynamic-pricing': {
            code: 'F4', icon: 'fa-sliders',
            title: 'Dynamic Pricing & Revenue Management Engine',
            subtitle: 'Automated BAR rate recommendations driven by real-time floor occupancy %, length-of-stay (LOS), pickup velocity, and local event demand.',
            cta: 'Approve & Push Suggested BAR Rates',
            bannerText: 'Dynamic BAR rate adjustments (+12% High-Occupancy Tier on Floors 3–4) pushed to Direct Web & all 5 OTA channels.',
            crumbs: ['Hotel', 'Revenue', 'Dynamic Pricing (F4)'],
            kpis: [
                ['fa-bolt', 'all', 'Active Pricing Rules', '6 Yield Rules', 'Occupancy + Pickup Pace'],
                ['fa-bed', 'avail', 'Current Occupancy Trigger', '90.0% Sold', 'Tier 3 Surge (+12% BAR)'],
                ['fa-line-chart', 'occ', 'RevPAR Uplift MTD', '+৳ 845 / Rm', '+14.2% vs Flat Tariff'],
                ['fa-globe', 'rev', 'Competitor Rate Index', '102.4 ARI', 'Gulshan 5★ CompSet'],
            ],
            tableTitle: 'Occupancy-Triggered Dynamic Pricing Rules & Suggested Nightly Rates',
            headers: ['Rule Code', 'Suite Category', 'Base BAR', 'Occupancy Band', 'Suggested Rate', 'Yield Uplift', 'Status'],
            rows: [
                ['<code>#YIELD-01</code>', '<strong>Deluxe Double &amp; Twin (1F)</strong>', '৳ 4,200', '80% – 90% Occ', '<strong>৳ 4,620</strong>', '<div class="mm-share-cell"><span>+10% Yield</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:85%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Auto-Applied</span>'],
                ['<code>#YIELD-02</code>', '<strong>Heritage &amp; Family Suites (2F)</strong>', '৳ 6,500', '80% – 90% Occ', '<strong>৳ 7,150</strong>', '<div class="mm-share-cell"><span>+10% Yield</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:85%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Auto-Applied</span>'],
                ['<code>#YIELD-03</code>', '<strong>Diplomatic &amp; Presidential (3F–4F)</strong>', '৳ 9,500', '> 90% Sold Out', '<strong>৳ 10,925</strong>', '<div class="mm-share-cell"><span>+15% Peak</span><span class="mm-share-bar"><span class="mm-share-fill is-violet" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Peak Active</span>'],
            ],
        },
        '/preview/upsell-marketplace': {
            code: 'F5', icon: 'fa-gift',
            title: 'Pre-Arrival Up-Sell & Early Check-In Marketplace',
            subtitle: 'Automated pre-arrival offers for suite upgrades, airport limousine transfers, early check-in / late check-out, and welcome packages.',
            cta: 'Send Pre-Arrival Upsell Offers',
            bannerText: 'Pre-arrival upsell links dispatched to 8 arriving guests; accepted add-ons auto-post to the reservation folio.',
            crumbs: ['Hotel', 'Revenue', 'Upsell Marketplace (F5)'],
            kpis: [
                ['fa-gift', 'all', 'Active Add-On Packages', '8 Curated Offers', 'Auto-Post to Folio'],
                ['fa-check-circle', 'avail', 'Conversion Rate', '31.5% Take-Up', 'Pre-Arrival Portal'],
                ['fa-money', 'occ', 'Ancillary Upsell Revenue', '৳ 46,800 MTD', '+৳ 1,950 / Stay Avg'],
                ['fa-car', 'rev', 'Most Popular Add-On', 'DAC Limo Pickup', '44% Guest Selection'],
            ],
            tableTitle: 'Curated Pre-Arrival Up-Sell Packages & Auto-Folio Rules',
            headers: ['Code', 'Up-Sell Package Name', 'Target Segment', 'Price', 'Conversion Share', 'Folio Ledger Account', 'Status'],
            rows: [
                ['<code>#UP-101</code>', '<strong>DAC Airport Executive Limousine Pickup</strong>', 'All Arriving Flights', '<strong>৳ 2,500</strong>', '<div class="mm-share-cell"><span>44% Attach</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:44%"></span></span></div>', 'Transport &amp; Concierge GL', '<span class="mm-badge mm-badge-paid">Active</span>'],
                ['<code>#UP-102</code>', '<strong>Guaranteed 10:00 AM Early Check-In + Breakfast</strong>', 'Morning Arrivals', '<strong>৳ 1,800</strong>', '<div class="mm-share-cell"><span>29% Attach</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:29%"></span></span></div>', 'Room Division Half-Day GL', '<span class="mm-badge mm-badge-paid">Active</span>'],
                ['<code>#UP-103</code>', '<strong>Next-Tier Heritage Suite Upgrade (+Balcony)</strong>', 'Deluxe Bookings', '<strong>+৳ 1,900/nt</strong>', '<div class="mm-share-cell"><span>27% Attach</span><span class="mm-share-bar"><span class="mm-share-fill is-violet" style="width:27%"></span></span></div>', 'Room Upgrade Tariff GL', '<span class="mm-badge mm-badge-paid">Active</span>'],
            ],
        },
        '/preview/inventory-guards': {
            code: 'F7', icon: 'fa-ban',
            title: 'Inventory Guards, Overbooking Caps & OOO Room Blocking',
            subtitle: 'Enforce strict category availability guards, Out-of-Order (OOO) engineering holds, and zero-overbooking locks across Front Desk & OTAs.',
            cta: 'Place OOO / OOS Inventory Guard',
            bannerText: 'Inventory guard verified: OOO/OOS holds deducted from sellable availability across Booking Wizard, Tape Chart, and OTA channels.',
            crumbs: ['Hotel', 'Rooms', 'Inventory Guards (F7)'],
            kpis: [
                ['fa-bed', 'all', 'Total Physical Inventory', '20 Suites', 'Floors 1–4'],
                ['fa-check-circle', 'avail', 'Sellable Net Inventory', '19 Suites', '1 In-Progress Turnaround'],
                ['fa-wrench', 'occ', 'OOO / OOS Blocked', '1 Suite (#202)', 'HVAC Filter Service'],
                ['fa-shield', 'rev', 'Overbooking Cap', '0% Hard Guard', 'Zero Double-Bookings'],
            ],
            tableTitle: 'Active Room Inventory Holds (OOO / OOS) & Category Overbooking Guards',
            headers: ['Room / Category', 'Guard Type', 'Window', 'Engineering / HK Reason', 'Availability Impact', 'Status'],
            rows: [
                ['<strong>#202 · Heritage Suite (2F)</strong>', '<code>OOS — Maintenance Hold</code>', '14 Oct 11:00 → 14 Oct 16:00', 'Preventive HVAC filter &amp; thermostat calibration', '<div class="mm-share-cell"><span>Auto-Blocked</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-partial">Releases 16:00</span>'],
                ['<strong>#304 · Heritage Double (3F)</strong>', '<code>HK Turnaround Lock</code>', '14 Oct 12:00 → 14 Oct 14:30', 'Deep linen sanitization after UNDP checkout', '<div class="mm-share-cell"><span>Locked Until Inspected</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:80%"></span></span></div>', '<span class="mm-badge mm-badge-paid">80% Ready</span>'],
                ['<strong>All 4 Floors (20 Suites)</strong>', '<code>Zero-Overbooking Guard</code>', 'Permanent Rule', 'Hard block on concurrent room-date assignment', '<div class="mm-share-cell"><span>100% Protected</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Enforced</span>'],
            ],
        },
        '/preview/group-contracts': {
            code: 'F8', icon: 'fa-users',
            title: 'Corporate Group & Function Contracts + Rooming List',
            subtitle: 'Manage corporate & diplomatic group blocks, allotment cutoff dates, bulk rooming list assignments, and Master-Bill vs. Guest Incidental split billing.',
            cta: 'Auto-Assign Group Rooming List',
            bannerText: 'Group Contract #GRP-2026-09 rooming list synchronized: 6 suites assigned with Room+Tax routed to Master Corporate Folio.',
            crumbs: ['Hotel', 'Bookings', 'Group Contracts (F8)'],
            kpis: [
                ['fa-users', 'all', 'Active Group Blocks', '4 Contracts', '14 Suites Allotted'],
                ['fa-calendar-check-o', 'avail', 'Pickup vs Allotment', '92.8% Picked Up', '13 of 14 Suites Named'],
                ['fa-columns', 'occ', 'Master-Bill Split', 'Room→Master', 'Incidentals→Guest Card'],
                ['fa-money', 'rev', 'Contracted Group Value', '৳ 485,000', 'Guaranteed Deposit Paid'],
            ],
            tableTitle: 'Active Corporate, Diplomatic & Banquet Group Contracts',
            headers: ['Contract ID', 'Group / Organization', 'Allotment & Dates', 'Cutoff Date', 'Billing Split Rule', 'Pickup Progress', 'Status'],
            rows: [
                ['<code>#GRP-2026-09</code>', '<strong>UNDP Regional Climate Summit Delegation</strong>', '6 Suites · 14–18 Oct', '10 Oct 2026', 'Room+Tax → Master AR · POS → Guest', '<div class="mm-share-cell"><span>6 / 6 Named</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Checked In</span>'],
                ['<code>#GRP-2026-10</code>', '<strong>Horizon Air Flight Crew Layover Block</strong>', '4 Suites · Daily Rolling', '24h Prior', '100% Master City Ledger #CL-104', '<div class="mm-share-cell"><span>4 / 4 Named</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active Contract</span>'],
                ['<code>#GRP-2026-11</code>', '<strong>Mohammadi Group Executive Board Retreat</strong>', '4 Suites + Grand Hall', '15 Oct 2026', 'Master Folio + Banquet Package', '<div class="mm-share-cell"><span>3 / 4 Named</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:75%"></span></span></div>', '<span class="mm-badge mm-badge-partial">1 Room Open</span>'],
            ],
        },
        '/preview/maintenance-tickets': {
            code: 'F14', icon: 'fa-wrench',
            title: 'Engineering & Maintenance Work Orders + Parts GL',
            subtitle: 'Track room & asset maintenance tickets, preventive servicing schedules, spare parts consumption, and automatic General Ledger expense posting.',
            cta: 'Log Maintenance Work Order',
            bannerText: 'Maintenance Work Order #MWO-312 logged, engineering technician assigned, and spare parts cost linked to Engineering GL.',
            crumbs: ['Hotel', 'Engineering', 'Maintenance Tickets (F14)'],
            kpis: [
                ['fa-wrench', 'all', 'Open Work Orders', '1 Active (#202)', 'Avg 35m Resolution'],
                ['fa-check-circle', 'avail', 'Completed This Week', '19 Tickets', '100% Preventive SLA'],
                ['fa-cogs', 'occ', 'Spare Parts Cost MTD', '৳ 14,200', 'Auto-Posted to GL'],
                ['fa-shield', 'rev', 'Asset Uptime', '99.2% Operational', 'Generators · HVAC · Lifts'],
            ],
            tableTitle: 'Engineering Work Orders, Preventive Schedule & GL Parts Costing',
            headers: ['Ticket', 'Suite / Hotel Asset', 'Issue & Work Description', 'Assigned Engineer', 'Parts Cost (GL)', 'SLA Progress', 'Status'],
            rows: [
                ['<code>#MWO-311</code>', '<strong>Suite #202 · Daikin VRV Thermostat</strong>', 'Filter replacement &amp; airflow calibration', 'Engr. Mamunur Rashid', '৳ 1,200 (GL #5042)', '<div class="mm-share-cell"><span>85% Testing</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:85%"></span></span></div>', '<span class="mm-badge mm-badge-partial">In Progress</span>'],
                ['<code>#MWO-310</code>', '<strong>Suite #301 · Mifare RFID Door Lock</strong>', 'Preventive battery pack replacement (100% voltage)', 'Engr. Tariqul Islam', '৳ 450 (GL #5042)', '<div class="mm-share-cell"><span>100% Verified</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Completed</span>'],
                ['<code>#MWO-309</code>', '<strong>Rooftop Standby Generator #1 (500 kVA)</strong>', 'Weekly load-transfer &amp; fuel pressure inspection', 'Engr. Mamunur Rashid', '৳ 0 (Routine PM)', '<div class="mm-share-cell"><span>100% Passed</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Completed</span>'],
            ],
        },
        '/preview/shift-roster': {
            code: 'F16', icon: 'fa-clock-o',
            title: 'Staff Shift Roster & Cash Shift Handover',
            subtitle: 'Departmental shift rosters across Front Desk, Housekeeping, Kitchen & Bar POS tied directly to Night Audit and cash drawer declarations.',
            cta: 'Approve Shift Handover & Cash Count',
            bannerText: 'Shift B (15:00–23:00) roster activated and Shift A cash drawer declaration (৳ 48,500 · Zero Variance) signed off.',
            crumbs: ['System', 'Operations', 'Shift Roster (F16)'],
            kpis: [
                ['fa-users', 'all', 'Staff On Active Shift', '28 Team Members', 'Shift A (07:00–15:00)'],
                ['fa-check-circle', 'avail', 'Biometric Attendance', '100% Checked In', 'ZKTeco Terminal Synced'],
                ['fa-money', 'occ', 'Active Cash Drawers', '3 Drawers Open', 'Front Desk · Restaurant · Bar'],
                ['fa-shield', 'rev', 'Shift Cash Variance', '৳ 0.00 Exact', 'Reconciled with POS'],
            ],
            tableTitle: 'Active Departmental Shift Roster & POS / Front-Desk Cash Drawer Linkage',
            headers: ['Shift Code', 'Department', 'Supervisor & Team', 'Shift Window', 'Linked Cash Float / Drawer', 'Attendance', 'Status'],
            rows: [
                ['<code>#SHF-FD-A</code>', '<strong>Front Desk &amp; Concierge</strong>', 'Nusrat Jahan + 4 Officers', '07:00 – 15:00', 'Front-Desk Drawer #1 (Float: ৳ 25,000)', '<div class="mm-share-cell"><span>5 / 5 Present</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active Shift</span>'],
                ['<code>#SHF-HK-A</code>', '<strong>Housekeeping &amp; Laundry</strong>', 'Rahima Begum + 11 Attendants', '07:00 – 15:00', 'Floor Linen &amp; Minibar Cart #1–#4', '<div class="mm-share-cell"><span>12 / 12 Present</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active Shift</span>'],
                ['<code>#SHF-POS-A</code>', '<strong>Heritage Dining &amp; Skyline Bar POS</strong>', 'Kamrul Hasan + 10 Kitchen/Bar Staff', '07:00 – 15:00', 'POS Terminal #1 &amp; #2 (Float: ৳ 15,000)', '<div class="mm-share-cell"><span>11 / 11 Present</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active Shift</span>'],
            ],
        },
        '/preview/e-invoicing': {
            code: 'F17', icon: 'fa-qrcode',
            title: 'Statutory e-Invoicing (LHDN MyInvois / NBR Mushak 6.3)',
            subtitle: 'Real-time JSON tax invoice submission, cryptographic UUID & QR verification stamp, and consolidated monthly e-Invoice compliance.',
            cta: 'Submit & Validate e-Invoice Batch',
            bannerText: 'e-Invoice batch validated via Tax Authority API: cryptographic UUID and verification QR codes attached to all settled folios.',
            crumbs: ['Account', 'Compliance', 'e-Invoicing (F17)'],
            kpis: [
                ['fa-qrcode', 'all', 'Validated e-Invoices MTD', '318 Folios', '100% UUID + QR Stamped'],
                ['fa-check-circle', 'avail', 'API Acceptance Rate', '100% Accepted', 'Zero Schema Rejections'],
                ['fa-bolt', 'occ', 'Avg Validation Latency', '1.2 Seconds', 'Real-Time JSON Webhook'],
                ['fa-file-text-o', 'rev', 'Taxable Supplies MTD', '৳ 3,420,000', 'VAT/SST Reconciled'],
            ],
            tableTitle: 'Validated Statutory e-Invoices with Cryptographic UUID & QR Stamp',
            headers: ['Folio / Invoice', 'Buyer / Guest TIN / BIN', 'Tax Authority UUID', 'Net + Tax', 'Validation Progress', 'Status'],
            rows: [
                ['<code>#INV-2026-1094</code>', '<strong>Tanvir Ahmed Chowdhury (Mohammadi Group)</strong>', '<code>UUID-9F4A-8821-BD01</code>', '<strong>৳ 25,000</strong> (Inc. VAT)', '<div class="mm-share-cell"><span>QR Verified</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Validated</span>'],
                ['<code>#INV-2026-1091</code>', '<strong>Embassy of Japan (Diplomatic Exempt)</strong>', '<code>UUID-7C12-4490-EX02</code>', '<strong>৳ 34,000</strong> (0% Diplomatic)', '<div class="mm-share-cell"><span>QR Verified</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Validated</span>'],
            ],
        },
        '/preview/tax-rules': {
            code: 'F18', icon: 'fa-percent',
            title: 'Locality Tax, Tourism Levy & SST / VAT Exemption Engine',
            subtitle: 'Date-effective tax rules by outlet and room category, Tourism Tax per room-night, and Diplomatic/UN tax-exemption certificate tracking.',
            cta: 'Verify Tax Rules & Exemption Ledger',
            bannerText: 'Tax & Tourism Levy rules verified across Room Division (10% SC + 15% VAT), Restaurant POS, and Diplomatic Exempt accounts.',
            crumbs: ['Account', 'Tax & VAT', 'Tax Rules Engine (F18)'],
            kpis: [
                ['fa-percent', 'all', 'Standard Hospitality Tax', '10% SC + 15% VAT', 'Compound / Exclusive'],
                ['fa-globe', 'avail', 'Diplomatic Exemptions', '3 Active Stays', 'UN / Embassy Certs Logged'],
                ['fa-building-o', 'occ', 'Tourism / City Levy', 'Configurable', 'Per-Night / Per-Room'],
                ['fa-check-circle', 'rev', 'Ledger Tax Parity', '100% Balanced', 'Auto-Posted to Tax GL'],
            ],
            tableTitle: 'Configured Tax, Service Charge, Tourism Levy & Exemption Rules',
            headers: ['Rule Code', 'Tax / Levy Name', 'Scope', 'Rate / Formula', 'Exemption Handling', 'Status'],
            rows: [
                ['<code>#TAX-RM-01</code>', '<strong>Hotel Room Service Charge + Govt. VAT</strong>', 'Room Division &amp; Banquet', '10% SC + 15% VAT', 'Auto-waived with Diplomatic Certificate', '<span class="mm-badge mm-badge-paid">Active Default</span>'],
                ['<code>#TAX-FB-02</code>', '<strong>Restaurant &amp; Bar POS F&amp;B VAT</strong>', 'Heritage Dining &amp; Bar', '10% SC + 15% VAT', 'Itemized on 80mm KOT &amp; Folio', '<span class="mm-badge mm-badge-paid">Active Default</span>'],
                ['<code>#TAX-EX-03</code>', '<strong>Diplomatic / UN Mission Tax Exemption</strong>', 'Embassy / UN / World Bank', '0% VAT (Cert Required)', 'Stores Certificate No. on Folio', '<span class="mm-badge mm-badge-paid">Enforced</span>'],
            ],
        },
        '/preview/night-audit-hardening': {
            code: 'F19', icon: 'fa-moon-o',
            title: 'Night-Audit Hardening, Auto-Posting & Day-Close Lock',
            subtitle: 'Guided 6-step Night Audit workflow: auto-post room & package tariffs, process no-show fees, reconcile POS outlets, and lock the business date.',
            cta: 'Run Pre-Audit Verification & Post Room Charges',
            bannerText: 'Pre-Audit check passed (18/18 in-house suites balanced, 0 unposted POS checks). Ready to post nightly tariffs and roll business date.',
            crumbs: ['Hotel', 'Night Audit', 'Audit Hardening (F19)'],
            kpis: [
                ['fa-calendar-check-o', 'all', 'Active Business Date', '14 Oct 2026', 'Lock Status: Open'],
                ['fa-bed', 'avail', 'Recurring Room Postings', '18 Suites Queued', '৳ 134,100 Nightly Tariff'],
                ['fa-cutlery', 'occ', 'Outlet POS Checks', '100% Closed', '0 Open Restaurant/Bar Bills'],
                ['fa-lock', 'rev', 'Reopen Protection', 'Dual-Auth Guard', 'GM Approval Required'],
            ],
            tableTitle: 'Guided 6-Step Night Audit Pre-Flight & Posting Checklist',
            headers: ['Step', 'Audit Control Gate', 'Verification Summary', 'Completion', 'Status'],
            rows: [
                ['1', '<strong>Pending Arrivals &amp; No-Show Fee Engine</strong>', 'All guaranteed arrivals checked in or auto-charged 1st night', '<div class="mm-share-cell"><span>100% Cleared</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Passed</span>'],
                ['2', '<strong>Restaurant &amp; Bar POS Outlet Settlement</strong>', 'All table KOTs settled to Cash/Card or Room Folio', '<div class="mm-share-cell"><span>100% Balanced</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Passed</span>'],
                ['3', '<strong>Auto-Post Nightly Room Tariffs &amp; Taxes</strong>', '18 occupied suites × nightly rate + 10% SC + 15% VAT', '<div class="mm-share-cell"><span>Ready to Post</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Ready</span>'],
                ['4', '<strong>Lock Business Date &amp; Roll to 15 Oct 2026</strong>', 'Generates immutable GL trial balance snapshot', '<div class="mm-share-cell"><span>Armed</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:90%"></span></span></div>', '<span class="mm-badge mm-badge-partial">Awaiting Sign-Off</span>'],
            ],
        },
        '/preview/gateway-reconciliation': {
            code: 'F21', icon: 'fa-balance-scale',
            title: 'Payment-Gateway Reconciliation & Shift Cash Declaration',
            subtitle: 'Unified daily reconciliation across Front-Desk collections, Restaurant/Bar POS terminals, VISA/MasterCard batches, bKash, and cash drawers.',
            cta: 'Reconcile All Payment Batches',
            bannerText: 'All 4 settlement channels (Cash, Card POS, bKash/Nagad, Online Gateway) reconciled with General Ledger — ৳ 0.00 variance.',
            crumbs: ['Account', 'Reconciliation', 'Gateway & Cash Recon (F21)'],
            kpis: [
                ['fa-money', 'all', 'Total Collections Today', '৳ 312,400', 'Front Desk + F&B POS'],
                ['fa-credit-card', 'avail', 'Card & Gateway Batches', '৳ 228,900', '100% Settled'],
                ['fa-inbox', 'occ', 'Cash Drawer Declarations', '৳ 83,500', '3 Shifts Verified'],
                ['fa-check-circle', 'rev', 'Net Recon Variance', '৳ 0.00', '100% Matched to GL'],
            ],
            tableTitle: 'Channel-by-Channel Settlement vs. General Ledger Reconciliation',
            headers: ['Channel / Terminal', 'PMS / POS Recorded', 'Gateway / Drawer Count', 'Variance', 'Match Rate', 'Status'],
            rows: [
                ['<strong>VISA / MasterCard POS Terminal #1 &amp; #2</strong>', '৳ 142,400', '৳ 142,400', '৳ 0', '<div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Reconciled</span>'],
                ['<strong>SSLCommerz &amp; bKash Merchant Wallet</strong>', '৳ 86,500', '৳ 86,500', '৳ 0', '<div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Reconciled</span>'],
                ['<strong>Front-Desk &amp; POS Physical Cash Drawers</strong>', '৳ 83,500', '৳ 83,500', '৳ 0', '<div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Reconciled</span>'],
            ],
        },
        '/preview/multi-currency-fx': {
            code: 'F22', icon: 'fa-dollar',
            title: 'Multi-Currency Transaction & Folio FX Engine',
            subtitle: 'Quote in USD, EUR, GBP, MYR, or JPY and settle in BDT with locked transaction-level exchange rates from currency_conversions.',
            cta: 'Sync Daily Treasury FX Rates',
            bannerText: 'Treasury FX rates synchronized and locked for today’s diplomatic and international guest folios.',
            crumbs: ['Account', 'Treasury', 'Multi-Currency FX (F22)'],
            kpis: [
                ['fa-globe', 'all', 'Supported Currencies', '6 Currencies', 'BDT · USD · EUR · GBP · MYR · JPY'],
                ['fa-dollar', 'avail', 'USD / BDT Desk Rate', '1 USD = ৳ 120.00', 'Locked on Folio'],
                ['fa-exchange', 'occ', 'MYR / BDT Desk Rate', '1 MYR = ৳ 27.50', 'Cross-Border Ready'],
                ['fa-check-circle', 'rev', 'FX Realized Gain/Loss', 'Auto-Posted', 'GL Account #4092'],
            ],
            tableTitle: 'Active Foreign Currency Conversion Rates & Dual-Currency Folios',
            headers: ['Currency Pair', 'Buying Rate (BDT)', 'Folio Billing Rate', 'Active Foreign Folios', 'FX Spread Guard', 'Status'],
            rows: [
                ['<strong>USD → BDT (US Dollar)</strong>', '৳ 119.20', '<strong>৳ 120.00</strong>', '5 Diplomatic / UN Folios', '<div class="mm-share-cell"><span>Locked</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active</span>'],
                ['<strong>MYR → BDT (Malaysian Ringgit)</strong>', '৳ 27.10', '<strong>৳ 27.50</strong>', '2 Corporate Folios', '<div class="mm-share-cell"><span>Locked</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active</span>'],
                ['<strong>EUR → BDT (Euro)</strong>', '৳ 129.50', '<strong>৳ 130.50</strong>', '3 EU Delegation Folios', '<div class="mm-share-cell"><span>Locked</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active</span>'],
            ],
        },
        '/preview/notification-backbone': {
            code: 'F23', icon: 'fa-bell',
            title: 'Omnichannel Notification Backbone (Email + SMS + WhatsApp)',
            subtitle: 'Queued Redis notification pipeline dispatching Booking Confirmations, 24h Pre-Arrival reminders, Check-In Pins, and Folio PDFs.',
            cta: 'Dispatch Test Lifecycle Notification',
            bannerText: 'Queued omnichannel notification dispatched via WhatsApp Business API + SSL Wireless SMS + Branded HTML Email.',
            crumbs: ['System', 'Communications', 'Notification Backbone (F23)'],
            kpis: [
                ['fa-paper-plane', 'all', 'Lifecycle Triggers', '6 Automated Events', 'Confirm → Post-Stay'],
                ['fa-whatsapp', 'avail', 'WhatsApp + SMS Delivery', '99.6% Delivered', 'Avg 2.1s Latency'],
                ['fa-envelope-o', 'occ', 'Queued SMTP Workers', 'Redis Horizon', 'Zero Blocking Requests'],
                ['fa-file-pdf-o', 'rev', 'Auto-Attached PDFs', 'Draft & Final Folio', '100% Branded'],
            ],
            tableTitle: 'Automated Hospitality Lifecycle Triggers & Channel Routing',
            headers: ['Trigger Event', 'Timing', 'Channels Enabled', 'Attached Document', 'Delivery Rate', 'Status'],
            rows: [
                ['<strong>1. Reservation Confirmed (#BK)</strong>', 'Instant on Confirm', 'WhatsApp + SMS + Email', '2-Page Reservation Summary PDF', '<div class="mm-share-cell"><span>99.8%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:99.8%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active</span>'],
                ['<strong>2. 24-Hour Pre-Arrival &amp; Kiosk Link</strong>', 'T-24 Hours', 'WhatsApp + Email', 'Express Kiosk Check-In QR + Upsell', '<div class="mm-share-cell"><span>99.5%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:99.5%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active</span>'],
                ['<strong>3. Checkout Folio &amp; Review Prompt</strong>', 'On Checkout Settlement', 'WhatsApp + Email', 'Official Tax Invoice PDF + Review Link', '<div class="mm-share-cell"><span>99.7%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:99.7%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active</span>'],
            ],
        },
        '/preview/guest-portal': {
            code: 'F24', icon: 'fa-mobile',
            title: 'Guest Self-Service Mobile Web Portal',
            subtitle: 'Guest-facing mobile companion for reservation lookup, pre-check-in KYC upload, live folio inspection, room service ordering, and PDF invoice download.',
            cta: 'Send Guest Portal Magic Link',
            bannerText: 'Guest Portal magic link sent to Tanvir Ahmed Chowdhury (#BK-2026-1094) with live folio and 2-page PDF summary access.',
            crumbs: ['Hotel', 'Guest Experience', 'Guest Mobile Portal (F24)'],
            kpis: [
                ['fa-mobile', 'all', 'Active Guest Sessions', '16 In-House Guests', 'No App Install Needed'],
                ['fa-file-pdf-o', 'avail', 'Self-Served Folio PDFs', '42 Downloads', '2-Page A4 Vector PDF'],
                ['fa-cutlery', 'occ', 'In-Room Dining Orders', '11 Orders Today', 'Routed to Kitchen KDS'],
                ['fa-star', 'rev', 'Guest Portal Rating', '4.9 / 5.0 ★', 'Instant Concierge Chat'],
            ],
            tableTitle: 'Guest Self-Service Portal Modules & Active Guest Sessions',
            headers: ['Module', 'Guest Capability', 'Connected Backend', 'Usage Share', 'Status'],
            rows: [
                ['<strong>My Stay &amp; Digital Key Pass</strong>', 'View suite #101/#201, Wi-Fi code &amp; check-out time', 'Booking &amp; Kiosk F10 Engine', '<div class="mm-share-cell"><span>94% Used</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:94%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Live</span>'],
                ['<strong>Live Folio &amp; 2-Page PDF Download</strong>', 'Inspect itemized charges &amp; download A4 PDF summary', 'Folio &amp; Vector PDF Engine', '<div class="mm-share-cell"><span>88% Used</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:88%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Live</span>'],
                ['<strong>1-Tap Concierge &amp; Room Service</strong>', 'Request extra towels, airport car, or dining menu', 'Concierge F12 &amp; POS KDS', '<div class="mm-share-cell"><span>76% Used</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:76%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Live</span>'],
            ],
        },
        '/preview/loyalty-coupons': {
            code: 'F25', icon: 'fa-trophy',
            title: 'Loyalty Circle, Repeat-Guest Tiers & Direct-Booking Coupons',
            subtitle: 'Reward repeat guests with Heritage Silver, Gold & Platinum tiers, points redemption, and direct-booking promotional coupon codes.',
            cta: 'Issue Direct-Booking Promo Coupon',
            bannerText: 'Promo Coupon HERITAGE15 (15% Direct Booking Discount + Late Check-Out) activated for web & front-desk bookings.',
            crumbs: ['Hotel', 'CRM & Loyalty', 'Loyalty & Coupons (F25)'],
            kpis: [
                ['fa-trophy', 'all', 'Enrolled Loyalty Members', '142 VIP Guests', '3 Membership Tiers'],
                ['fa-repeat', 'avail', 'Direct Repeat Share', '68.4% Repeat', 'Commission-Free Stays'],
                ['fa-tag', 'occ', 'Active Promo Coupons', '4 Campaigns', 'HERITAGE15 · CORP2026'],
                ['fa-money', 'rev', 'Loyalty Redeemed Value', '৳ 94,000 MTD', 'Suite Upgrades &amp; Dining'],
            ],
            tableTitle: 'Heritage Loyalty Tiers & Active Direct-Booking Coupon Campaigns',
            headers: ['Code / Tier', 'Program / Coupon Name', 'Eligibility & Perks', 'Discount / Multiplier', 'Redemption Share', 'Status'],
            rows: [
                ['<code>PLATINUM-VIP</code>', '<strong>Heritage Platinum Circle (10+ Stays)</strong>', 'Complimentary suite upgrade + 4 PM late checkout', '2.0x Points + 15% Off', '<div class="mm-share-cell"><span>100% Active</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active Tier</span>'],
                ['<code>HERITAGE15</code>', '<strong>Direct Website Autumn Escape Coupon</strong>', 'Min 2 Nights on Direct Storefront or Wizard', '15% Off Room Tariff', '<div class="mm-share-cell"><span>64 Redemptions</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:78%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active Code</span>'],
                ['<code>CORP-DIRECT</code>', '<strong>Corporate Partner Direct Booking Code</strong>', 'Verified corporate email domain', '15% Off + Free Airport Pickup', '<div class="mm-share-cell"><span>52 Redemptions</span><span class="mm-share-bar"><span class="mm-share-fill is-violet" style="width:70%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active Code</span>'],
            ],
        },
        '/preview/guest-reviews': {
            code: 'F26', icon: 'fa-star',
            title: 'Post-Stay Guest Reviews, NPS & Reputation Hub',
            subtitle: 'Automated post-checkout feedback scoring, Net Promoter Score (NPS) tracking, Google/TripAdvisor review prompts, and GM response workflow.',
            cta: 'Send Post-Stay Review Invitation',
            bannerText: 'Post-stay review prompt dispatched to recent checkouts; 5-star ratings auto-routed to Google Hotel Reviews.',
            crumbs: ['Hotel', 'Guest Experience', 'Reviews & NPS (F26)'],
            kpis: [
                ['fa-star', 'all', 'Overall Guest Rating', '4.92 / 5.0 ★', 'Based on 214 Verified Stays'],
                ['fa-smile-o', 'avail', 'Net Promoter Score (NPS)', '+94 NPS', '96% Promoters'],
                ['fa-check-circle', 'occ', 'GM Response Rate', '100% Replied', 'Avg < 2h Response'],
                ['fa-globe', 'rev', 'Google / OTA Score', '4.9 ★ Google', '9.6 / 10 Booking.com'],
            ],
            tableTitle: 'Recent Verified Guest Reviews & Category Satisfaction Scores',
            headers: ['Booking & Suite', 'Guest Name', 'Cleanliness & Comfort', 'Front Desk & Dining', 'Guest Feedback Comment', 'Score', 'Status'],
            rows: [
                ['<code>#BK-2026-1094</code> · #101', '<strong>Tanvir Ahmed Chowdhury</strong>', '5.0 ★ Spotless Suite', '5.0 ★ Express Check-In', '“Seamless 3-step booking &amp; executive 2-page PDF summary!”', '<div class="mm-share-cell"><span>5.0 / 5.0</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Published</span>'],
                ['<code>#BK-2026-1091</code> · #301', '<strong>Amb. Kenji Takahashi</strong>', '5.0 ★ Diplomatic Protocol', '5.0 ★ Green Tea Service', '“Impeccable attention to detail and quiet 3rd floor suite.”', '<div class="mm-share-cell"><span>5.0 / 5.0</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Published</span>'],
            ],
        },
        '/preview/public-seo-cms': {
            code: 'F27', icon: 'fa-globe',
            title: 'Multilingual Public Storefront SEO, Schema.org & Media CDN',
            subtitle: 'Editorial content manager for mm-web storefront with HotelRoom JSON-LD Schema.org offers, SEO meta tags, and EN / বাংলা / MS / 中文 localization.',
            cta: 'Publish SEO Schema & Multilingual Pack',
            bannerText: 'HotelRoom Schema.org JSON-LD, OpenGraph tags, and EN / বাংলা / MS / 中文 translations published to public storefront.',
            crumbs: ['Website', 'SEO & Localization', 'Public SEO CMS (F27)'],
            kpis: [
                ['fa-code', 'all', 'Schema.org Markup', 'Hotel + HotelRoom', 'Rich Snippet Validated'],
                ['fa-language', 'avail', 'Storefront Locales', '4 Languages', 'EN · বাংলা · MS · 中文'],
                ['fa-bolt', 'occ', 'Lighthouse Performance', '96 / 100', 'Lazy WebP + Swap Fonts'],
                ['fa-search', 'rev', 'Direct Organic Traffic', '+38% Growth', 'Zero OTA Commission'],
            ],
            tableTitle: 'Public Storefront Routes, SEO Metadata & Schema.org Structured Data',
            headers: ['Public Route', 'Page Title & Meta Description', 'Schema.org Type', 'Locales Synced', 'Lighthouse', 'Status'],
            rows: [
                ['<code>/preview/public-home</code>', '<strong>M&amp;M Heritage Hotel &amp; Suites — 5★ Boutique Luxury in Dhaka</strong>', '<code>Hotel + AggregateRating</code>', 'EN · বাংলা · MS · 中文', '<div class="mm-share-cell"><span>97 / 100</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:97%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Indexed</span>'],
                ['<code>/preview/public-rooms</code>', '<strong>Executive Suites &amp; Best Available Rates — Instant Online Booking</strong>', '<code>HotelRoom + Offer</code>', 'EN · বাংলা · MS · 中文', '<div class="mm-share-cell"><span>96 / 100</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:96%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Indexed</span>'],
            ],
        },
        '/preview/mobile-staff-app': {
            code: 'F28', icon: 'fa-tablet',
            title: 'Mobile Staff Companion App (HK, Maintenance & Duty Manager)',
            subtitle: 'Responsive mobile-first staff console consuming /api/v1 for one-handed room status updates, minibar posting, and VIP arrival alerts.',
            cta: 'Push Real-Time Task Alert to Staff Devices',
            bannerText: 'Push notification dispatched to 16 active staff mobile handsets via /api/v1/staff-tasks.',
            crumbs: ['System', 'Mobile Companion', 'Staff App (F28)'],
            kpis: [
                ['fa-mobile', 'all', 'Connected Staff Handsets', '16 Active Devices', 'iOS · Android · Tablet PWA'],
                ['fa-check-square-o', 'avail', 'Mobile Room Sign-Offs', '38 Updates Today', 'Real-Time Board Sync'],
                ['fa-glass', 'occ', 'Mobile Minibar Posts', '14 Posts Today', 'Instant Folio Sync'],
                ['fa-bell-o', 'rev', 'Push Alert Latency', '< 500 ms', 'WebPush + Sanctum API'],
            ],
            tableTitle: 'Active Mobile Staff Companion Modules & Live Device Telemetry',
            headers: ['Role / App View', 'Mobile Capabilities', 'API Endpoint', 'Active Staff', 'Sync Health', 'Status'],
            rows: [
                ['<strong>Housekeeping Attendant View</strong>', '1-tap Clean/Inspected toggle, linen checklist &amp; minibar post', '<code>POST /api/v1/rooms/{id}/hk</code>', '12 Attendants', '<div class="mm-share-cell"><span>100% Online</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Connected</span>'],
                ['<strong>Engineering Technician View</strong>', 'Accept work orders, upload repair photo &amp; release OOS rooms', '<code>POST /api/v1/maintenance</code>', '2 Engineers', '<div class="mm-share-cell"><span>100% Online</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Connected</span>'],
                ['<strong>Duty Manager Pocket BI</strong>', 'Live Occupancy/ADR/RevPAR, VIP arrivals &amp; late-checkout approvals', '<code>GET /api/v1/manager-kpis</code>', '2 Managers', '<div class="mm-share-cell"><span>100% Online</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Connected</span>'],
            ],
        },
        '/preview/api-webhooks': {
            code: 'F30', icon: 'fa-plug',
            title: 'Public REST API v1 (/api/v1), OpenAPI & Outbound Webhooks',
            subtitle: 'Versioned Sanctum REST API (/api/v1/rooms, /bookings, /folio) with OpenAPI 3.1 spec and signed outbound webhooks for door locks, Xero, and OTAs.',
            cta: 'Trigger Signed Test Webhook Delivery',
            bannerText: 'Signed webhook payload (event: booking.confirmed · #BK-2026-1094) delivered with HTTP 200 OK in 142ms.',
            crumbs: ['System', 'Developer Platform', 'REST API & Webhooks (F30)'],
            kpis: [
                ['fa-code', 'all', 'REST API v1 Endpoints', '24 Scoped Routes', 'OpenAPI 3.1 Documented'],
                ['fa-key', 'avail', 'Auth & Rate Limiting', 'Sanctum + 120/m', 'Scoped Bearer Tokens'],
                ['fa-exchange', 'occ', 'Webhook Deliveries (24h)', '1,240 Events', '99.98% 200 OK'],
                ['fa-plug', 'rev', 'Partner Integrations', '4 Connected', 'Mifare Locks · Xero · OTA · Zapier'],
            ],
            tableTitle: 'Versioned REST API v1 Endpoints & Outbound Webhook Subscriptions',
            headers: ['Method & Route', 'Scope / Integration', 'Payload Schema', 'P95 Latency', 'Success Rate', 'Status'],
            rows: [
                ['<code>GET /api/v1/rooms/availability</code>', 'Public Booking Engine &amp; OTA ARI', '<code>RoomAvailabilityResource</code>', '42 ms', '<div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active v1</span>'],
                ['<code>POST /api/v1/bookings</code>', '3-Step Wizard, Kiosk &amp; Channel Manager', '<code>BookingCreateRequest</code>', '85 ms', '<div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active v1</span>'],
                ['<code>WEBHOOK booking.checked_in</code>', 'Mifare RFID Key Encoder &amp; Welcome WhatsApp', '<code>HMAC-SHA256 Signed JSON</code>', '118 ms', '<div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Healthy</span>'],
            ],
        },
        '/preview/multi-property': {
            code: 'F31', icon: 'fa-sitemap',
            title: 'Multi-Property Enterprise Console & Consolidated P&L',
            subtitle: 'Centralized multi-property switcher, cross-property inventory lookup, and consolidated group financial reporting across all M&M Hospitality properties.',
            cta: 'Consolidate Multi-Property Ledger',
            bannerText: 'Multi-property consolidation complete across all 3 M&M Hospitality Group properties (100% inter-company balances eliminated).',
            crumbs: ['Global Setup', 'Multi-Property', 'Enterprise Console (F31)'],
            kpis: [
                ['fa-building-o', 'all', 'Active Properties', '3 Properties', 'Gulshan · Banquet · Cox’s Bazar'],
                ['fa-bed', 'avail', 'Group Room Inventory', '80 Suites + 4 Halls', '88.5% Group Occupancy'],
                ['fa-line-chart', 'occ', 'Consolidated Revenue MTD', '৳ 6,840,000', '+14.8% YoY Group Growth'],
                ['fa-check-circle', 'rev', 'Inter-Company GL', '100% Eliminated', 'Unified Trial Balance'],
            ],
            tableTitle: 'M&M Hospitality Group Property Performance & Consolidated Revenue Share',
            headers: ['Property Code', 'Property Name & Location', 'Inventory', 'Occupancy', 'MTD Revenue', 'Group Revenue Share', 'Status'],
            rows: [
                ['<code>#PROP-DAC-01</code>', '<strong>M&amp;M Heritage Hotel &amp; Suites (Gulshan-2 Flagship)</strong>', '20 Luxury Suites + 2 POS Outlets', '90.0%', '<strong>৳ 3,420,000</strong>', '<div class="mm-share-cell"><span>50% Share</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:50%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Primary Active</span>'],
                ['<code>#PROP-DAC-02</code>', '<strong>M&amp;M Heritage Convention &amp; Banquet Center</strong>', '4 Grand Halls + Catering Kitchen', '82.0%', '<strong>৳ 1,780,000</strong>', '<div class="mm-share-cell"><span>26% Share</span><span class="mm-share-bar"><span class="mm-share-fill" style="width:26%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Synced</span>'],
                ['<code>#PROP-CXB-03</code>', '<strong>M&amp;M Heritage Oceanfront Villas (Cox’s Bazar)</strong>', '60 Sea-View Suites', '87.5%', '<strong>৳ 1,640,000</strong>', '<div class="mm-share-cell"><span>24% Share</span><span class="mm-share-bar"><span class="mm-share-fill is-violet" style="width:24%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Synced</span>'],
            ],
        },
        '/preview/admin-i18n': {
            code: 'F32', icon: 'fa-language',
            title: 'Admin UI Internationalization (EN / বাংলা / MS) & Locale Engine',
            subtitle: 'Multi-language translation catalogs for Front Desk, POS & Accounting with timezone-aware date/time and currency formatting.',
            cta: 'Compile & Sync Language Dictionaries',
            bannerText: 'Admin i18n catalogs (English, বাংলা, Bahasa Melayu) compiled and synchronized with zero missing translation keys.',
            crumbs: ['System', 'Localization', 'Admin i18n (F32)'],
            kpis: [
                ['fa-language', 'all', 'Admin UI Languages', '3 Complete Locales', 'English · বাংলা · Bahasa Melayu'],
                ['fa-check-circle', 'avail', 'Translation Coverage', '100% Keys Mapped', '1,420 UI Strings'],
                ['fa-clock-o', 'occ', 'Active Timezone', 'Asia/Dhaka (BST)', 'UTC+06:00 ISO-8601'],
                ['fa-money', 'rev', 'Tabular Money Format', 'BDT (৳) / MYR (RM)', 'Locale-Aware Numerals'],
            ],
            tableTitle: 'Admin UI Locale Packs, Timezone Profiles & Translation Coverage',
            headers: ['Locale Tag', 'Language Name', 'Target Modules', 'Translated Keys', 'Completion', 'Status'],
            rows: [
                ['<code>en-US / en-GB</code>', '<strong>English (International Hospitality Standard)</strong>', 'All 10 ERP Modules + Wiki + Print PDFs', '1,420 / 1,420', '<div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Default Active</span>'],
                ['<code>bn-BD</code>', '<strong>বাংলা (Bengali — Front Desk, POS, Kitchen &amp; SOPs)</strong>', 'Wiki, SOP Print, Front Desk &amp; Kitchen KDS', '1,420 / 1,420', '<div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active</span>'],
                ['<code>ms-MY</code>', '<strong>Bahasa Melayu (Malaysia Compliance &amp; Regional Pack)</strong>', 'Front Desk, e-Invoicing (MyInvois) &amp; POS', '1,420 / 1,420', '<div class="mm-share-cell"><span>100%</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div>', '<span class="mm-badge mm-badge-paid">Active</span>'],
            ],
        },
        '/preview/audit-compliance': {
            code: 'F33', icon: 'fa-shield',
            title: 'Audit & Compliance Log Viewer with JSON Diff & Retention',
            subtitle: 'Immutable activity_logs explorer with side-by-side before/after JSON field diffs, workstation IP tracking, and 7-year financial retention policy.',
            cta: 'Export Signed Compliance Audit Trail',
            bannerText: 'Signed SHA-256 compliance audit log exported (CSV + PDF) covering all booking, folio, tariff, and voucher mutations.',
            crumbs: ['System', 'Compliance', 'Audit & Diff Viewer (F33)'],
            kpis: [
                ['fa-shield', 'all', 'Immutable Audit Records', '14,820 Events', 'Append-Only Ledger'],
                ['fa-code', 'avail', 'Before / After JSON Diff', '100% Field-Level', 'Tariff &amp; Discount Tracked'],
                ['fa-archive', 'occ', 'Retention Policy', '7 Years Statutory', 'Encrypted Cold Archive'],
                ['fa-check-circle', 'rev', 'Tamper Checksum', 'SHA-256 Verified', 'Zero Integrity Alerts'],
            ],
            tableTitle: 'Field-Level Before / After JSON Audit Trail',
            headers: ['Timestamp & Actor', 'Module & Record', 'Action', 'Before (Old JSON)', 'After (New JSON)', 'Status'],
            rows: [
                ['2026-10-14 14:15 · <strong>Nusrat Jahan</strong>', '<code>Booking #BK-2026-1094</code>', 'Room Move (F9)', '<code>{"room":"#101","rate":4200}</code>', '<code>{"room":"#206","rate":5500}</code>', '<span class="mm-badge mm-badge-paid">Logged</span>'],
                ['2026-10-14 13:50 · <strong>Kamrul Hasan</strong>', '<code>Folio #BK-2026-1094</code>', 'Advance Deposit (F1)', '<code>{"advance":0,"due":25000}</code>', '<code>{"advance":10000,"due":15000}</code>', '<span class="mm-badge mm-badge-paid">Logged</span>'],
                ['2026-10-14 12:30 · <strong>System Admin</strong>', '<code>Voucher #RV-2026-088</code>', 'Voucher Approval', '<code>{"status":"draft"}</code>', '<code>{"status":"approved"}</code>', '<span class="mm-badge mm-badge-paid">Logged</span>'],
            ],
        },
        '/preview/import-export-hub': {
            code: 'F34', icon: 'fa-cloud-download',
            title: 'Universal CSV / Excel Import & Export Center',
            subtitle: 'Standardized one-click CSV, Excel & Vector PDF import/export across Guests, Room Blocks, Seasonal Rates, General Store Stock, and GL Vouchers.',
            cta: 'Run Batch CSV / Excel Export',
            bannerText: 'Standardized UTF-8 CSV & Excel export bundle generated across Guests (20), Rooms (20), Bookings (20), General Store Stock, and GL Trial Balance.',
            crumbs: ['System', 'Data Tools', 'Import & Export Hub (F34)'],
            kpis: [
                ['fa-cloud-upload', 'all', 'Bulk Import Templates', '6 Validated CSVs', 'Dry-Run Row Preview'],
                ['fa-cloud-download', 'avail', 'One-Click Exports', 'All 10 Modules', 'CSV · XLSX · A4 PDF'],
                ['fa-check-circle', 'occ', 'GeneralStore Export Fix', '100% Repaired', 'ExportItemCSV Active'],
                ['fa-bolt', 'rev', 'Large Export Queue', 'Chunked Stream', 'Zero Memory Timeouts'],
            ],
            tableTitle: 'Standardized Module Import & Export Pipelines',
            headers: ['Pipeline Code', 'Dataset & Module', 'Supported Formats', 'Validation & Schema Guard', 'Last Executed', 'Status'],
            rows: [
                ['<code>#IO-HTL-01</code>', '<strong>Guest CRM Profiles &amp; Group Rooming List</strong>', '<code>CSV · XLSX · JSON</code>', 'NID/Passport + Phone deduplication check', 'Today 14:00', '<span class="mm-badge mm-badge-paid">Ready</span>'],
                ['<code>#IO-GS-02</code>', '<strong>General Store &amp; Kitchen Inventory Stock</strong>', '<code>CSV · XLSX · PDF</code>', 'Repaired ExportItemCSV + unit conversion', 'Today 13:30', '<span class="mm-badge mm-badge-paid">Ready</span>'],
                ['<code>#IO-ACC-03</code>', '<strong>General Ledger Vouchers, Trial Balance &amp; Tax</strong>', '<code>CSV · XLSX · A4 PDF</code>', 'Balanced Debit = Credit guard before import', 'Today 12:45', '<span class="mm-badge mm-badge-paid">Ready</span>'],
            ],
        },
    };

    const result = {
        '/preview/plan-b': {
            fn: planBCommandCenterPage,
            title: 'Complete Plan B Feature Command Center (F1–F34 · 100%)',
            crumbs: ['Hotel', 'Plan B Roadmap', 'All 34 Features'],
        },
    };
    for (const [route, spec] of Object.entries(specs)) {
        result[route] = {
            fn: makeFeatureScreen(spec),
            title: `${spec.title} (Plan B · ${spec.code})`,
            crumbs: spec.crumbs,
        };
    }
    return result;
}

module.exports = { buildPlanBPages };
