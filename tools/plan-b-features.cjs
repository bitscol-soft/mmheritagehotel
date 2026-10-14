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
                            <tbody id="pb-tbody-${spec.code.toLowerCase()}">${trs}</tbody>
                        </table>
                    </div>
                </div>
                ${spec.script || ''}
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
            controls: `<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px"><strong><i class="fa fa-bolt"></i> Interactive Payment Intent Generator &amp; Webhook Capture Simulator (F1)</strong><span class="mm-badge mm-badge-paid">HMAC-SHA256 Live</span></div>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px;align-items:end">
                    <div><label class="mm-label">Reservation &amp; Guest</label><select id="f1-booking" class="form-control"><option value="#BK-2026-1095 · Rubana Huq">#BK-2026-1095 · Rubana Huq (Suite #403)</option><option value="#BK-2026-1096 · Dr. Ahsan Habib">#BK-2026-1096 · Dr. Ahsan Habib (Suite #401)</option><option value="#BK-2026-1097 · Syed Kamrul Hasan">#BK-2026-1097 · Syed Kamrul Hasan (Suite #106)</option></select></div>
                    <div><label class="mm-label">Payment Gateway</label><select id="f1-gateway" class="form-control"><option value="SSLCommerz · VISA/MasterCard">SSLCommerz · VISA/MasterCard</option><option value="Stripe Checkout · International Card">Stripe Checkout · International Card</option><option value="bKash Tokenized Checkout">bKash Tokenized Checkout</option><option value="HitPay QR / FPX / PayNow">HitPay QR / FPX / PayNow</option></select></div>
                    <div><label class="mm-label">Deposit Tier</label><select id="f1-tier" class="form-control"><option value="Min 20% Deposit">Min 20% Deposit</option><option value="50% Corporate Advance" selected>50% Corporate Advance</option><option value="100% Full Prepay">100% Full Prepay</option></select></div>
                    <div><label class="mm-label">Capture Amount (৳)</label><input type="number" id="f1-amount" class="form-control" value="18500"></div>
                    <div><button type="button" id="f1-btn-capture" class="mm-button" style="width:100%"><i class="fa fa-bolt"></i> Capture &amp; Auto-Post to Folio</button></div>
                </div>`,
            script: `<script>(function(){
                var btn=document.getElementById("f1-btn-capture"),tb=document.getElementById("pb-tbody-f1"),ban=document.getElementById("pb-banner-f1");
                if(btn&&tb){
                    btn.addEventListener("click",function(){
                        var bk=document.getElementById("f1-booking").value,gw=document.getElementById("f1-gateway").value,trTier=document.getElementById("f1-tier").value,amt=Number(document.getElementById("f1-amount").value||18500);
                        var id="#PI-"+(8844+tb.querySelectorAll("tr").length);
                        var tr=document.createElement("tr");
                        tr.innerHTML="<td><code>"+id+"</code></td><td><strong>"+bk+"</strong></td><td>"+gw+"</td><td>"+trTier+'</td><td><div class="mm-share-cell"><span>Captured</span><span class="mm-share-bar"><span class="mm-share-fill is-emerald" style="width:100%"></span></span></div></td><td><strong>৳ '+amt.toLocaleString("en-US")+'</strong></td><td><span class="mm-badge mm-badge-paid">Posted to Folio</span></td>';
                        tb.insertBefore(tr,tb.firstChild);
                        if(ban){ban.innerHTML='<i class="fa fa-check-circle"></i> <strong>Webhook Verified ('+id+'):</strong> Captured ৳ '+amt.toLocaleString("en-US")+" via "+gw+" and auto-posted to "+bk+" folio!";ban.style.display="block";}
                    });
                }
            })();</script>`,
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

    function roomSettingsHubPage() {
        const body = `<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
            <header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
                <div class="mm-page-title">
                    <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-sliders"></i> Room Settings, Pricing, Amenities &amp; Rules Control Center</h1>
                    <p class="tw-m-0 tw-text-sm tw-text-muted">Unified interactive configuration hub for Room Categories, Capacity Tariffs, In-Suite Amenities, Check-In/Out Policies, Tax Rules, and Inventory Guards.</p>
                </div>
                <div class="tw-flex tw-flex-wrap tw-gap-2">
                    <button type="button" class="mm-button" data-toggle="modal" data-target="#rmset-add-cat-modal"><i class="fa fa-plus-circle"></i> + New Room Category</button>
                    <a href="/preview/category-create" class="mm-button mm-button-secondary"><i class="fa fa-external-link"></i> Blade Category Form</a>
                    <a href="/preview/setup-amenities" class="mm-button mm-button-secondary"><i class="fa fa-wifi"></i> Amenities Setup</a>
                    <a href="/preview/rate-plans" class="mm-button mm-button-secondary"><i class="fa fa-tags"></i> Rate Plans (F3)</a>
                    <a href="/preview/inventory-guards" class="mm-button mm-button-secondary"><i class="fa fa-shield"></i> OOO &amp; Guards (F7)</a>
                    <a href="/preview/booking-next" class="mm-button mm-button-secondary"><i class="fa fa-calendar-plus-o"></i> Booking Wizard</a>
                </div>
            </header>
            <div class="mm-table-kpi-grid" aria-label="Room settings summary">
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-bed"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Configured Categories</span><div class="mm-table-kpi-value" id="rmset-kpi-cats">6 Suite Tiers <span class="mm-table-kpi-sub">20 Physical Rooms</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-money"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Base Tariff Range &amp; Avg</span><div class="mm-table-kpi-value" id="rmset-kpi-tariff">৳ 5,500 – ৳ 24,000 <span class="mm-table-kpi-sub">Avg BAR: ৳ 14,000</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-wifi"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active Room Amenities</span><div class="mm-table-kpi-value" id="rmset-kpi-amenities">10 Enabled <span class="mm-table-kpi-sub">Assigned per Category</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-gavel"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Booking &amp; Tax Rules</span><div class="mm-table-kpi-value" id="rmset-kpi-rules">20% Min Deposit <span class="mm-table-kpi-sub">5% Svc + 15% VAT</span></div></div></div>
            </div>

            <!-- Interactive Section Switcher -->
            <div class="mm-panel tw-p-4">
                <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2">
                    <div class="mm-preview-filter-pills" id="rmset-tab-pills" style="margin:0;">
                        <button type="button" class="mm-preview-pill is-active" data-rmset-tab="all"><i class="fa fa-th-large"></i> All Settings Sections</button>
                        <button type="button" class="mm-preview-pill" data-rmset-tab="pricing"><i class="fa fa-money"></i> 1. Categories &amp; Pricing</button>
                        <button type="button" class="mm-preview-pill" data-rmset-tab="amenities"><i class="fa fa-wifi"></i> 2. Room Amenities</button>
                        <button type="button" class="mm-preview-pill" data-rmset-tab="rules"><i class="fa fa-gavel"></i> 3. Booking &amp; Tax Rules</button>
                        <button type="button" class="mm-preview-pill" data-rmset-tab="navmap"><i class="fa fa-compass"></i> 4. Screen &amp; Blade Map</button>
                    </div>
                    <span class="mm-badge mm-badge-paid" id="rmset-sync-badge"><i class="fa fa-bolt"></i> Live Tariff &amp; Tax Calculator Active</span>
                </div>
            </div>

            <div id="rmset-save-banner" class="alert alert-success" style="display:none;margin:0;border-radius:10px;">
                <i class="fa fa-check-circle"></i> <strong id="rmset-save-msg">Room category pricing, amenities assignment, and house booking rules saved &amp; synced to Booking Wizard!</strong>
            </div>

            <!-- 1. Room Categories & Pricing Matrix -->
            <div class="mm-panel tw-p-5 rmset-section" data-rmset-sec="pricing">
                <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:12px;">
                    <div>
                        <h2 class="tw-m-0 tw-text-base tw-font-semibold"><i class="fa fa-money"></i> 1. Room Categories, Capacity &amp; Nightly Pricing Configuration</h2>
                        <p class="tw-m-0 tw-text-xs tw-text-muted">Edit any Base Rate or Corporate Discount % below — Corporate Rate and Gross Tax-Inclusive Tariff recalculate automatically.</p>
                    </div>
                    <div class="tw-flex tw-flex-wrap tw-gap-2">
                        <div class="input-group" style="width:220px;">
                            <span class="input-group-addon" style="font-size:11px;">Corp. Disc %</span>
                            <input type="number" id="rmset-corp-disc" class="form-control input-sm text-right" value="15" min="0" max="50">
                        </div>
                        <button type="button" class="mm-button mm-button-secondary" data-toggle="modal" data-target="#rmset-add-cat-modal" style="min-height:32px;padding:4px 12px;"><i class="fa fa-plus"></i> + Add Category</button>
                        <button type="button" class="mm-button" id="rmset-btn-save-prices" style="min-height:32px;padding:4px 12px;"><i class="fa fa-check"></i> Save Tariff Changes</button>
                    </div>
                </div>
                <div class="mm-table-scroll">
                    <table class="mm-data-table table table-bordered table-striped" id="rmset-cat-table">
                        <thead>
                            <tr>
                                <th>Category Code &amp; Name</th>
                                <th>Assigned Rooms</th>
                                <th>Bed &amp; Max Capacity</th>
                                <th class="text-right">Base Rate / Night (৳)</th>
                                <th class="text-right">Corporate Rate (৳)</th>
                                <th class="text-right">Gross w/ Tax (৳)</th>
                                <th class="text-right">Extra Bed (৳)</th>
                                <th>Included Amenities</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="rmset-cat-tbody">
                            <tr class="rmset-cat-row">
                                <td><code>CAT-01</code> <strong>Heritage Deluxe Single</strong></td>
                                <td><code>#103, #106</code> (Fl. 1)</td>
                                <td>1 Queen · 2 Adults</td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-base-input" value="5500" style="width:105px;display:inline-block;"></td>
                                <td class="text-right"><strong class="rmset-corp-cell">4,675</strong></td>
                                <td class="text-right"><span class="rmset-gross-cell">6,641</span></td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-xbed-input" value="1200" style="width:85px;display:inline-block;"></td>
                                <td><span class="mm-badge mm-badge-paid">Wi-Fi 6</span> <span class="mm-badge mm-badge-paid">Breakfast</span> <span class="mm-badge mm-badge-partial">Smart TV</span></td>
                                <td><span class="mm-badge mm-badge-paid">Active</span></td>
                            </tr>
                            <tr class="rmset-cat-row">
                                <td><code>CAT-02</code> <strong>Deluxe King Suite</strong></td>
                                <td><code>#101, #102, #104, #105</code> (Fl. 1)</td>
                                <td>1 King · 2 Adults, 1 Child</td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-base-input" value="8500" style="width:105px;display:inline-block;"></td>
                                <td class="text-right"><strong class="rmset-corp-cell">7,225</strong></td>
                                <td class="text-right"><span class="rmset-gross-cell">10,264</span></td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-xbed-input" value="1500" style="width:85px;display:inline-block;"></td>
                                <td><span class="mm-badge mm-badge-paid">Wi-Fi 6</span> <span class="mm-badge mm-badge-paid">Breakfast</span> <span class="mm-badge mm-badge-paid">Minibar</span></td>
                                <td><span class="mm-badge mm-badge-paid">Active</span></td>
                            </tr>
                            <tr class="rmset-cat-row">
                                <td><code>CAT-03</code> <strong>Heritage Executive Suite</strong></td>
                                <td><code>#201, #202, #203, #205</code> (Fl. 2)</td>
                                <td>1 King · 3 Adults</td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-base-input" value="12500" style="width:105px;display:inline-block;"></td>
                                <td class="text-right"><strong class="rmset-corp-cell">10,625</strong></td>
                                <td class="text-right"><span class="rmset-gross-cell">15,094</span></td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-xbed-input" value="2000" style="width:85px;display:inline-block;"></td>
                                <td><span class="mm-badge mm-badge-paid">Lounge Access</span> <span class="mm-badge mm-badge-paid">Nespresso</span> <span class="mm-badge mm-badge-paid">Minibar</span></td>
                                <td><span class="mm-badge mm-badge-paid">Active</span></td>
                            </tr>
                            <tr class="rmset-cat-row">
                                <td><code>CAT-04</code> <strong>Royal Family Twin Suite</strong></td>
                                <td><code>#204, #302, #304, #305</code> (Fl. 2–3)</td>
                                <td>2 Queen · 4 Adults, 2 Children</td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-base-input" value="15000" style="width:105px;display:inline-block;"></td>
                                <td class="text-right"><strong class="rmset-corp-cell">12,750</strong></td>
                                <td class="text-right"><span class="rmset-gross-cell">18,113</span></td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-xbed-input" value="2200" style="width:85px;display:inline-block;"></td>
                                <td><span class="mm-badge mm-badge-paid">2 Bedrooms</span> <span class="mm-badge mm-badge-paid">Breakfast ×4</span> <span class="mm-badge mm-badge-paid">Balcony</span></td>
                                <td><span class="mm-badge mm-badge-paid">Active</span></td>
                            </tr>
                            <tr class="rmset-cat-row">
                                <td><code>CAT-05</code> <strong>Ambassador Diplomatic Suite</strong></td>
                                <td><code>#301, #303, #401, #402</code> (Fl. 3–4)</td>
                                <td>1 Emperor King · 3 Adults</td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-base-input" value="18500" style="width:105px;display:inline-block;"></td>
                                <td class="text-right"><strong class="rmset-corp-cell">15,725</strong></td>
                                <td class="text-right"><span class="rmset-gross-cell">22,339</span></td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-xbed-input" value="2500" style="width:85px;display:inline-block;"></td>
                                <td><span class="mm-badge mm-badge-paid">24h Butler</span> <span class="mm-badge mm-badge-paid">Airport Limo</span> <span class="mm-badge mm-badge-paid">Jacuzzi</span></td>
                                <td><span class="mm-badge mm-badge-paid">Active</span></td>
                            </tr>
                            <tr class="rmset-cat-row">
                                <td><code>CAT-06</code> <strong>Grand Presidential Suite</strong></td>
                                <td><code>#403, #404</code> (Fl. 4 Penthouse)</td>
                                <td>2 King + Dining · 4 Adults</td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-base-input" value="24000" style="width:105px;display:inline-block;"></td>
                                <td class="text-right"><strong class="rmset-corp-cell">20,400</strong></td>
                                <td class="text-right"><span class="rmset-gross-cell">28,980</span></td>
                                <td class="text-right"><input type="number" class="form-control input-sm text-right rmset-xbed-input" value="3000" style="width:85px;display:inline-block;"></td>
                                <td><span class="mm-badge mm-badge-paid">Private Butler</span> <span class="mm-badge mm-badge-paid">Chauffeur</span> <span class="mm-badge mm-badge-paid">Private Terrace</span></td>
                                <td><span class="mm-badge mm-badge-paid">Active</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Amenities & 3. Rules side by side -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(380px,1fr));gap:16px">
                <div class="mm-panel tw-p-5 rmset-section" data-rmset-sec="amenities">
                    <div class="tw-flex tw-items-center tw-justify-between" style="margin-bottom:10px">
                        <strong><i class="fa fa-wifi"></i> 2. Room Amenities &amp; In-Suite Facilities</strong>
                        <span class="mm-badge mm-badge-paid" id="rmset-amenity-count-badge">10 Active Amenities</span>
                    </div>
                    <p class="tw-text-xs tw-text-muted" style="margin:0 0 12px">Toggle amenities included across room categories or add a new custom amenity below:</p>
                    <div id="rmset-amenities-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px">
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" class="rmset-am-chk" checked> High-Speed Wi-Fi 6 (Dedicated AP)</label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" class="rmset-am-chk" checked> Complimentary Buffet Breakfast</label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" class="rmset-am-chk" checked> 55" 4K Smart Hospitality TV</label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" class="rmset-am-chk" checked> Stocked In-Room Minibar (F15)</label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" class="rmset-am-chk" checked> Nespresso &amp; Gourmet Tea Bar</label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" class="rmset-am-chk" checked> Laptop-Size Electronic Safe</label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" class="rmset-am-chk" checked> Rain Shower &amp; Luxury Bathrobe</label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" class="rmset-am-chk" checked> VIP Airport Pickup (Suites)</label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" class="rmset-am-chk" checked> Executive Sky-Lounge Access</label>
                        <label style="display:flex;align-items:center;gap:8px;font-weight:500"><input type="checkbox" class="rmset-am-chk" checked> Evening VIP Turndown Service</label>
                    </div>
                    <div style="display:flex;gap:8px;margin-bottom:12px">
                        <input type="text" id="rmset-new-amenity-name" class="form-control" placeholder="Add new amenity (e.g. Private Jacuzzi, Baby Cot)...">
                        <button type="button" id="rmset-btn-add-amenity" class="mm-button" style="white-space:nowrap"><i class="fa fa-plus"></i> Add Amenity</button>
                    </div>
                    <div class="tw-flex tw-flex-wrap tw-gap-2">
                        <a href="/preview/setup-amenity-create" class="mm-button mm-button-secondary"><i class="fa fa-external-link"></i> Blade Add Amenity Form</a>
                        <a href="/preview/setup-amenities" class="mm-button mm-button-secondary"><i class="fa fa-list"></i> All Amenities Table</a>
                    </div>
                </div>

                <div class="mm-panel tw-p-5 rmset-section" data-rmset-sec="rules">
                    <div class="tw-flex tw-items-center tw-justify-between" style="margin-bottom:10px">
                        <strong><i class="fa fa-gavel"></i> 3. Booking, Check-In/Out, Deposit &amp; Tax Rules</strong>
                        <a href="/preview/setup-registration-terms" class="mm-badge mm-badge-partial">House Rules →</a>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px">
                        <div><label class="mm-label">Standard Check-In Time</label><input type="time" id="rmset-rule-checkin" class="form-control" value="14:00"></div>
                        <div><label class="mm-label">Standard Check-Out Time</label><input type="time" id="rmset-rule-checkout" class="form-control" value="12:00"></div>
                        <div><label class="mm-label">Minimum Advance Deposit (%)</label><input type="number" id="rmset-rule-deposit" class="form-control" value="20" min="0" max="100"></div>
                        <div><label class="mm-label">Free Cancellation Cutoff (Hours)</label><input type="number" id="rmset-rule-cancel" class="form-control" value="24" min="0" max="168"></div>
                        <div><label class="mm-label">Service Charge (%)</label><input type="number" id="rmset-rule-svc" class="form-control" value="5" min="0" max="30"></div>
                        <div><label class="mm-label">Government VAT / SST (%)</label><input type="number" id="rmset-rule-vat" class="form-control" value="15" min="0" max="30"></div>
                    </div>
                    <div style="padding:10px 12px;border-radius:10px;background:rgba(14,116,144,0.08);border:1px solid rgba(14,116,144,0.25);margin-bottom:12px;font-size:12px" id="rmset-rule-sim-box">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                            <strong><i class="fa fa-calculator"></i> Live Rule Simulator (Sample ৳ 10,000 Stay)</strong>
                            <span class="mm-badge mm-badge-paid" id="rmset-sim-total">Gross: ৳ 12,075</span>
                        </div>
                        <div id="rmset-sim-details" class="tw-text-xs tw-text-muted">Base: ৳ 10,000 · Svc (5%): ৳ 500 · Compound VAT (15%): ৳ 1,575 · Min Advance (20%): <strong>৳ 2,415</strong></div>
                    </div>
                    <div class="tw-flex tw-flex-wrap tw-gap-2">
                        <button type="button" class="mm-button" id="rmset-btn-save-rules"><i class="fa fa-check"></i> Save Booking &amp; House Rules</button>
                        <a href="/preview/setup-vat" class="mm-button mm-button-secondary"><i class="fa fa-percent"></i> VAT &amp; Service Setup</a>
                        <a href="/preview/setup-registration-terms" class="mm-button mm-button-secondary"><i class="fa fa-file-text-o"></i> Registration Terms</a>
                    </div>
                </div>
            </div>

            <!-- 4. Quick Navigation Directory to All Room & Setup Screens -->
            <div class="mm-panel tw-p-5 rmset-section" data-rmset-sec="navmap">
                <h2 class="tw-m-0 tw-mb-2 tw-text-base tw-font-semibold"><i class="fa fa-compass"></i> 4. Direct Navigation Map — Where Every Room Setting Lives</h2>
                <div class="mm-table-scroll">
                    <table class="mm-data-table table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Configuration Area</th>
                                <th>Admin Menu Location</th>
                                <th>Laravel Blade View / Controller</th>
                                <th>Interactive Live Preview Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Room Categories, Capacity Prices &amp; Amenities</strong></td>
                                <td><code>Hotel → Category → Create / Edit</code></td>
                                <td><code>module/Hotel/views/category/create.blade.php</code></td>
                                <td><a href="/preview/category-create" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:12px;">Open Category &amp; Price Form →</a></td>
                            </tr>
                            <tr>
                                <td><strong>20-Room Inventory, Floor, Bed Type &amp; Status</strong></td>
                                <td><code>Hotel → Room → Room List</code></td>
                                <td><code>module/Hotel/views/room/index.blade.php</code></td>
                                <td><a href="/preview/rooms" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:12px;">Open 20-Room Directory →</a></td>
                            </tr>
                            <tr>
                                <td><strong>Room Amenities Master List &amp; Icons</strong></td>
                                <td><code>Hotel → Setup → Amenities</code></td>
                                <td><code>module/Hotel/views/setup/aminities/index.blade.php</code></td>
                                <td><a href="/preview/setup-amenities" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:12px;">Open Amenities Setup →</a></td>
                            </tr>
                            <tr>
                                <td><strong>VAT, Service Charge &amp; Locality Tax Rules</strong></td>
                                <td><code>Hotel → Setup → VAT &amp; Services</code></td>
                                <td><code>module/Hotel/views/setup/vat/index.blade.php</code></td>
                                <td><a href="/preview/setup-vat" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:12px;">Open VAT Setup →</a> <a href="/preview/tax-rules" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:12px;">Tax Rules (F18) →</a></td>
                            </tr>
                            <tr>
                                <td><strong>Guest Registration Terms &amp; House Rules</strong></td>
                                <td><code>Hotel → Setup → Registration Terms</code></td>
                                <td><code>module/Hotel/views/setup/guest-registration-terms/index.blade.php</code></td>
                                <td><a href="/preview/setup-registration-terms" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:12px;">Open Registration Terms →</a></td>
                            </tr>
                            <tr>
                                <td><strong>Seasonal Rate Plans, Min-Stay &amp; Promo Codes</strong></td>
                                <td><code>Hotel → Distribution → Rate Plans (F3)</code></td>
                                <td><code>Plan B · F3 Rate Engine</code></td>
                                <td><a href="/preview/rate-plans" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:12px;">Open Rate Plans (F3) →</a> <a href="/preview/dynamic-pricing" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:12px;">Dynamic Pricing (F4) →</a></td>
                            </tr>
                            <tr>
                                <td><strong>Overbooking Limits &amp; Out-of-Order (OOO) Rules</strong></td>
                                <td><code>Hotel → Front Desk → Inventory Guards (F7)</code></td>
                                <td><code>Plan B · F7 Inventory Guards</code></td>
                                <td><a href="/preview/inventory-guards" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:12px;">Open Inventory Guards (F7) →</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Room Category Slide-Over Drawer -->
            <div id="rmset-add-cat-modal" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1" role="dialog">
                <div class="modal-dialog"><div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Add New Room Category, Pricing &amp; Amenities</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label class="mm-label">Category Code &amp; Title</label><input type="text" id="rmset-new-cat-name" class="form-control" value="Royal Honeymoon Pool Villa"></div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                            <div class="form-group"><label class="mm-label">Assigned Room Numbers</label><input type="text" id="rmset-new-cat-rooms" class="form-control" value="#405, #406 (Fl. 4)"></div>
                            <div class="form-group"><label class="mm-label">Bed &amp; Max Capacity</label><input type="text" id="rmset-new-cat-cap" class="form-control" value="1 Canopy King · 2 Adults"></div>
                            <div class="form-group"><label class="mm-label">Base Nightly Rate (৳)</label><input type="number" id="rmset-new-cat-rate" class="form-control" value="21000"></div>
                            <div class="form-group"><label class="mm-label">Extra Bed Tariff (৳)</label><input type="number" id="rmset-new-cat-xbed" class="form-control" value="2500"></div>
                        </div>
                        <div class="form-group"><label class="mm-label">Included Amenities</label><input type="text" id="rmset-new-cat-am" class="form-control" value="Plunge Pool, Butler, Floating Breakfast"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="mm-button" id="rmset-btn-create-cat" data-dismiss="modal"><i class="fa fa-check"></i> Create Room Category</button>
                    </div>
                </div></div>
            </div>
        </div></section>
        <script>(function(){
            var b1=document.getElementById("rmset-btn-save-prices"),b2=document.getElementById("rmset-btn-save-rules"),ban=document.getElementById("rmset-save-banner"),msg=document.getElementById("rmset-save-msg");
            var discEl=document.getElementById("rmset-corp-disc"),depEl=document.getElementById("rmset-rule-deposit"),svcEl=document.getElementById("rmset-rule-svc"),vatEl=document.getElementById("rmset-rule-vat");
            function fmt(n){return Math.round(n).toLocaleString("en-US");}
            function recalcAll(){
                var disc=Number(discEl&&discEl.value||15),svc=Number(svcEl&&svcEl.value||5),vat=Number(vatEl&&vatEl.value||15),dep=Number(depEl&&depEl.value||20);
                var grossMult=(1+svc/100)*(1+vat/100);
                var rows=document.querySelectorAll("#rmset-cat-tbody .rmset-cat-row");
                var minR=Infinity,maxR=0,sumR=0;
                for(var i=0;i<rows.length;i++){
                    var inp=rows[i].querySelector(".rmset-base-input");
                    var base=Number(inp&&inp.value||0);
                    if(base<minR)minR=base;
                    if(base>maxR)maxR=base;
                    sumR+=base;
                    var corpCell=rows[i].querySelector(".rmset-corp-cell");
                    var grossCell=rows[i].querySelector(".rmset-gross-cell");
                    if(corpCell)corpCell.textContent=fmt(base*(1-disc/100));
                    if(grossCell)grossCell.textContent=fmt(base*grossMult);
                }
                var avg=rows.length?Math.round(sumR/rows.length):0;
                var kpiCats=document.getElementById("rmset-kpi-cats");
                if(kpiCats)kpiCats.innerHTML=rows.length+' Suite Tiers <span class="mm-table-kpi-sub">20+ Physical Rooms</span>';
                var kpiTariff=document.getElementById("rmset-kpi-tariff");
                if(kpiTariff&&minR<Infinity)kpiTariff.innerHTML="৳ "+fmt(minR)+" – ৳ "+fmt(maxR)+' <span class="mm-table-kpi-sub">Avg BAR: ৳ '+fmt(avg)+"</span>";
                var kpiRules=document.getElementById("rmset-kpi-rules");
                if(kpiRules)kpiRules.innerHTML=dep+'% Min Deposit <span class="mm-table-kpi-sub">'+svc+"% Svc + "+vat+"% VAT</span>";
                var sampleBase=10000,sampleSvc=Math.round(sampleBase*svc/100),sampleVat=Math.round((sampleBase+sampleSvc)*vat/100),sampleGross=sampleBase+sampleSvc+sampleVat,sampleDep=Math.round(sampleGross*dep/100);
                var simTot=document.getElementById("rmset-sim-total"),simDet=document.getElementById("rmset-sim-details");
                if(simTot)simTot.textContent="Gross: ৳ "+fmt(sampleGross);
                if(simDet)simDet.innerHTML="Base: ৳ 10,000 · Svc ("+svc+"%): ৳ "+fmt(sampleSvc)+" · Compound VAT ("+vat+"%): ৳ "+fmt(sampleVat)+" · Min Advance ("+dep+"%): <strong>৳ "+fmt(sampleDep)+"</strong>";
            }
            function recalcAmenities(){
                var chks=document.querySelectorAll(".rmset-am-chk");
                var active=0;
                for(var i=0;i<chks.length;i++)if(chks[i].checked)active++;
                var b=document.getElementById("rmset-amenity-count-badge"),k=document.getElementById("rmset-kpi-amenities");
                if(b)b.textContent=active+" Active Amenities";
                if(k)k.innerHTML=active+' Enabled <span class="mm-table-kpi-sub">Assigned per Category</span>';
            }
            document.addEventListener("input",function(e){
                if(e.target&&(e.target.classList.contains("rmset-base-input")||e.target.id==="rmset-corp-disc"||e.target.id==="rmset-rule-deposit"||e.target.id==="rmset-rule-svc"||e.target.id==="rmset-rule-vat"))recalcAll();
            });
            document.addEventListener("change",function(e){
                if(e.target&&e.target.classList.contains("rmset-am-chk"))recalcAmenities();
            });
            var addAmBtn=document.getElementById("rmset-btn-add-amenity"),newAmInp=document.getElementById("rmset-new-amenity-name"),amGrid=document.getElementById("rmset-amenities-grid");
            if(addAmBtn&&newAmInp&&amGrid){
                addAmBtn.addEventListener("click",function(){
                    var v=(newAmInp.value||"").trim();
                    if(!v)return;
                    var lbl=document.createElement("label");
                    lbl.style.cssText="display:flex;align-items:center;gap:8px;font-weight:500";
                    lbl.innerHTML='<input type="checkbox" class="rmset-am-chk" checked> '+v.replace(/[<>&"]/g,"");
                    amGrid.appendChild(lbl);
                    newAmInp.value="";
                    recalcAmenities();
                });
            }
            var createCatBtn=document.getElementById("rmset-btn-create-cat"),catTbody=document.getElementById("rmset-cat-tbody");
            if(createCatBtn&&catTbody){
                createCatBtn.addEventListener("click",function(){
                    var idx=catTbody.querySelectorAll(".rmset-cat-row").length+1;
                    var code="CAT-0"+idx;
                    var name=(document.getElementById("rmset-new-cat-name").value||"New Suite Category").replace(/[<>&"]/g,"");
                    var rms=(document.getElementById("rmset-new-cat-rooms").value||"#405").replace(/[<>&"]/g,"");
                    var cap=(document.getElementById("rmset-new-cat-cap").value||"1 King · 2 Adults").replace(/[<>&"]/g,"");
                    var rate=Number(document.getElementById("rmset-new-cat-rate").value||18000);
                    var xbed=Number(document.getElementById("rmset-new-cat-xbed").value||2500);
                    var ams=(document.getElementById("rmset-new-cat-am").value||"Wi-Fi 6, Breakfast").split(",").map(function(s){return '<span class="mm-badge mm-badge-paid">'+s.trim().replace(/[<>&"]/g,"")+"</span>";}).join(" ");
                    var tr=document.createElement("tr");
                    tr.className="rmset-cat-row";
                    tr.innerHTML='<td><code>'+code+"</code> <strong>"+name+"</strong></td><td><code>"+rms+"</code></td><td>"+cap+'</td><td class="text-right"><input type="number" class="form-control input-sm text-right rmset-base-input" value="'+rate+'" style="width:105px;display:inline-block;"></td><td class="text-right"><strong class="rmset-corp-cell">0</strong></td><td class="text-right"><span class="rmset-gross-cell">0</span></td><td class="text-right"><input type="number" class="form-control input-sm text-right rmset-xbed-input" value="'+xbed+'" style="width:85px;display:inline-block;"></td><td>'+ams+'</td><td><span class="mm-badge mm-badge-paid">Active</span></td>';
                    catTbody.appendChild(tr);
                    recalcAll();
                    if(ban&&msg){msg.textContent="Added new category "+code+" ("+name+") and recalculated tariff matrix!";ban.style.display="block";}
                });
            }
            var tabPills=document.querySelectorAll("[data-rmset-tab]"),secs=document.querySelectorAll(".rmset-section");
            for(var t=0;t<tabPills.length;t++){
                tabPills[t].addEventListener("click",function(){
                    for(var k=0;k<tabPills.length;k++)tabPills[k].classList.remove("is-active");
                    this.classList.add("is-active");
                    var tab=this.getAttribute("data-rmset-tab");
                    for(var s=0;s<secs.length;s++){
                        secs[s].style.display=(tab==="all"||secs[s].getAttribute("data-rmset-sec")===tab)?"":"none";
                    }
                });
            }
            function showSave(text){
                recalcAll();
                try{localStorage.setItem("mm_room_settings_v1",JSON.stringify({updatedAt:new Date().toISOString(),corpDisc:discEl&&discEl.value,minDeposit:depEl&&depEl.value,svc:svcEl&&svcEl.value,vat:vatEl&&vatEl.value}));}catch(e){}
                if(ban&&msg){msg.textContent=text;ban.style.display="block";ban.scrollIntoView({behavior:"smooth",block:"nearest"});}
            }
            if(b1)b1.addEventListener("click",function(){showSave("Room category tariffs, corporate rates, and tax-inclusive prices saved & synced to Booking Wizard!");});
            if(b2)b2.addEventListener("click",function(){showSave("Check-in/out times, minimum deposit %, and compound VAT/Service rules saved & synced!");});
            recalcAll();
            recalcAmenities();
        })();</script>`;
        return shellWith(body, '', '', 'Room Settings, Pricing, Amenities & Rules', ['Hotel', 'Setup', 'Room Settings & Rules']);
    }

    function folioSettlementPage() {
        const body = `<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
            <header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
                <div class="mm-page-title">
                    <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-columns"></i> Multi-Window Split-Folio &amp; Express Checkout Settlement (Plan A · Phase 3 #4)</h1>
                    <p class="tw-m-0 tw-text-sm tw-text-muted">Split charges between Window A (Corporate Master Bill / City Ledger AR) and Window B (Guest Incidentals), apply late check-out rules, and collect multi-tender payment.</p>
                </div>
                <div class="tw-flex tw-flex-wrap tw-gap-2">
                    <a href="/preview/checkout" class="mm-button mm-button-secondary"><i class="fa fa-credit-card"></i> Classic Checkout</a>
                    <a href="/preview/city-ledger" class="mm-button mm-button-secondary"><i class="fa fa-building-o"></i> City Ledger AR (F20)</a>
                    <a href="/preview/invoice" class="mm-button mm-button-secondary"><i class="fa fa-print"></i> Printable Tax Invoice</a>
                    <button type="button" class="mm-button" id="folio-btn-settle"><i class="fa fa-check-circle"></i> Settle Both Windows &amp; Check-Out</button>
                </div>
            </header>
            <div class="mm-table-kpi-grid" aria-label="Split folio summary">
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-file-text-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active Stay Folio</span><div class="mm-table-kpi-value">#BK-2026-1094 <span class="mm-table-kpi-sub">Suites #101 &amp; #201 · 2N</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-building-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Window A · Corporate AR</span><div class="mm-table-kpi-value" id="folio-kpi-win-a">৳ 42,000 <span class="mm-table-kpi-sub">Orion Pharma City Ledger</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-user"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Window B · Guest Personal</span><div class="mm-table-kpi-value" id="folio-kpi-win-b">৳ 6,450 <span class="mm-table-kpi-sub">POS · Minibar · Laundry</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Unsettled Balance</span><div class="mm-table-kpi-value" id="folio-kpi-due">৳ 0 Due <span class="mm-table-kpi-sub">Multi-Tender Balanced</span></div></div></div>
            </div>

            <div id="folio-settle-banner" class="alert alert-success" style="display:none;margin:0;border-radius:10px;">
                <i class="fa fa-check-circle"></i> <strong id="folio-settle-msg">Split Folio Settled! Window A posted to Orion Pharma City Ledger AR (#CL-2026-01) and Window B settled via VISA + Cash. Suites #101 &amp; #201 marked Dirty for Housekeeping turnaround!</strong>
            </div>

            <!-- Interactive Charge Splitter & Late Checkout Bar -->
            <div class="mm-panel tw-p-5">
                <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:12px">
                    <div>
                        <h2 class="tw-m-0 tw-text-base tw-font-semibold"><i class="fa fa-exchange"></i> Folio Line-Items — Click Any Charge to Move Between Window A (Corporate) &amp; Window B (Guest)</h2>
                        <p class="tw-m-0 tw-text-xs tw-text-muted">Guest: <strong>Tanvir Ahmed Chowdhury</strong> · Corporate Account: <strong>Orion Pharma Ltd. (PO #ORP-9921)</strong></p>
                    </div>
                    <div class="tw-flex tw-items-center tw-gap-2">
                        <label class="mm-label" style="margin:0;font-size:12px;">Late Check-Out Rule:</label>
                        <select id="folio-late-select" class="form-control input-sm" style="width:230px;">
                            <option value="0" selected>Standard 12:00 PM Check-Out (৳ 0)</option>
                            <option value="4250">Late 04:00 PM · 50% Half-Day (+৳ 4,250)</option>
                            <option value="8500">After 06:00 PM · 100% Full-Day (+৳ 8,500)</option>
                        </select>
                    </div>
                </div>
                <div class="mm-table-scroll">
                    <table class="mm-data-table table table-bordered table-striped" id="folio-lines-table">
                        <thead>
                            <tr>
                                <th>Date &amp; Ref</th>
                                <th>Charge Description &amp; Outlet</th>
                                <th>Assigned Folio Window</th>
                                <th class="text-right">Amount (৳)</th>
                                <th class="text-center">Transfer Window</th>
                            </tr>
                        </thead>
                        <tbody id="folio-lines-tbody">
                            <tr class="folio-line-row" data-win="A" data-amt="17000">
                                <td><code>14–16 Oct</code></td>
                                <td><strong>Suite #101 · Deluxe King (2 Nights × ৳ 8,500)</strong></td>
                                <td><span class="mm-badge mm-badge-paid folio-win-badge">Window A · Corporate Master</span></td>
                                <td class="text-right"><strong>17,000</strong></td>
                                <td class="text-center"><button type="button" class="mm-button mm-button-secondary folio-toggle-win" style="min-height:26px;padding:2px 10px;font-size:11px;">Move to Window B ↔</button></td>
                            </tr>
                            <tr class="folio-line-row" data-win="A" data-amt="25000">
                                <td><code>14–16 Oct</code></td>
                                <td><strong>Suite #201 · Heritage Executive (2 Nights × ৳ 12,500)</strong></td>
                                <td><span class="mm-badge mm-badge-paid folio-win-badge">Window A · Corporate Master</span></td>
                                <td class="text-right"><strong>25,000</strong></td>
                                <td class="text-center"><button type="button" class="mm-button mm-button-secondary folio-toggle-win" style="min-height:26px;padding:2px 10px;font-size:11px;">Move to Window B ↔</button></td>
                            </tr>
                            <tr class="folio-line-row" data-win="B" data-amt="3200">
                                <td><code>14 Oct · #KOT-884</code></td>
                                <td><strong>Heritage Dining Restaurant · Room Service Dinner</strong></td>
                                <td><span class="mm-badge mm-badge-partial folio-win-badge">Window B · Guest Incidentals</span></td>
                                <td class="text-right"><strong>3,200</strong></td>
                                <td class="text-center"><button type="button" class="mm-button mm-button-secondary folio-toggle-win" style="min-height:26px;padding:2px 10px;font-size:11px;">Move to Window A ↔</button></td>
                            </tr>
                            <tr class="folio-line-row" data-win="B" data-amt="1850">
                                <td><code>15 Oct · #MB-201</code></td>
                                <td><strong>In-Room Minibar Consumption (Artisan Nuts, Juice, Evian)</strong></td>
                                <td><span class="mm-badge mm-badge-partial folio-win-badge">Window B · Guest Incidentals</span></td>
                                <td class="text-right"><strong>1,850</strong></td>
                                <td class="text-center"><button type="button" class="mm-button mm-button-secondary folio-toggle-win" style="min-height:26px;padding:2px 10px;font-size:11px;">Move to Window A ↔</button></td>
                            </tr>
                            <tr class="folio-line-row" data-win="B" data-amt="1400">
                                <td><code>15 Oct · #SRV-119</code></td>
                                <td><strong>Express Valet Laundry &amp; Suit Pressing (3 pcs)</strong></td>
                                <td><span class="mm-badge mm-badge-partial folio-win-badge">Window B · Guest Incidentals</span></td>
                                <td class="text-right"><strong>1,400</strong></td>
                                <td class="text-center"><button type="button" class="mm-button mm-button-secondary folio-toggle-win" style="min-height:26px;padding:2px 10px;font-size:11px;">Move to Window A ↔</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Multi-Tender Settlement Split -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(360px,1fr));gap:16px">
                <div class="mm-panel tw-p-5">
                    <div class="tw-flex tw-items-center tw-justify-between" style="margin-bottom:10px">
                        <strong><i class="fa fa-building-o"></i> Window A · Corporate Master Bill (Direct Bill AR)</strong>
                        <span class="mm-badge mm-badge-paid" id="folio-win-a-badge">৳ 42,000</span>
                    </div>
                    <p class="tw-text-xs tw-text-muted" style="margin:0 0 10px">Less Advance Deposit Paid (<code>৳ 10,000</code>) → Remaining transferred to Orion Pharma City Ledger AR:</p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <div><label class="mm-label">Advance Deposit Applied (৳)</label><input type="number" id="folio-adv-applied" class="form-control" value="10000" readonly></div>
                        <div><label class="mm-label">Post to City Ledger AR (৳)</label><input type="number" id="folio-ar-amount" class="form-control" value="32000"></div>
                    </div>
                </div>
                <div class="mm-panel tw-p-5">
                    <div class="tw-flex tw-items-center tw-justify-between" style="margin-bottom:10px">
                        <strong><i class="fa fa-credit-card"></i> Window B · Guest Personal Incidentals (Multi-Tender)</strong>
                        <span class="mm-badge mm-badge-partial" id="folio-win-b-badge">৳ 6,450</span>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <div><label class="mm-label">VISA / MasterCard POS (৳)</label><input type="number" id="folio-pay-card" class="form-control" value="5000"></div>
                        <div><label class="mm-label">Cash / bKash Wallet (৳)</label><input type="number" id="folio-pay-cash" class="form-control" value="1450"></div>
                    </div>
                </div>
            </div>
        </div></section>
        <script>(function(){
            function fmt(n){return Math.round(n).toLocaleString("en-US");}
            var lateSel=document.getElementById("folio-late-select"),arInp=document.getElementById("folio-ar-amount"),cardInp=document.getElementById("folio-pay-card"),cashInp=document.getElementById("folio-pay-cash");
            function recalc(){
                var rows=document.querySelectorAll("#folio-lines-tbody .folio-line-row");
                var winA=0,winB=Number(lateSel&&lateSel.value||0);
                for(var i=0;i<rows.length;i++){
                    var w=rows[i].getAttribute("data-win"),a=Number(rows[i].getAttribute("data-amt")||0);
                    if(w==="A")winA+=a;else winB+=a;
                }
                var kA=document.getElementById("folio-kpi-win-a"),kB=document.getElementById("folio-kpi-win-b"),bA=document.getElementById("folio-win-a-badge"),bB=document.getElementById("folio-win-b-badge");
                if(kA)kA.innerHTML="৳ "+fmt(winA)+' <span class="mm-table-kpi-sub">Orion Pharma City Ledger</span>';
                if(kB)kB.innerHTML="৳ "+fmt(winB)+' <span class="mm-table-kpi-sub">POS · Minibar · Laundry</span>';
                if(bA)bA.textContent="৳ "+fmt(winA);
                if(bB)bB.textContent="৳ "+fmt(winB);
                if(arInp)arInp.value=Math.max(0,winA-10000);
                if(cardInp&&cashInp)cashInp.value=Math.max(0,winB-Number(cardInp.value||0));
            }
            var btns=document.querySelectorAll(".folio-toggle-win");
            for(var j=0;j<btns.length;j++){
                btns[j].addEventListener("click",function(){
                    var tr=this.closest(".folio-line-row");
                    if(!tr)return;
                    var cur=tr.getAttribute("data-win"),next=cur==="A"?"B":"A";
                    tr.setAttribute("data-win",next);
                    var bdg=tr.querySelector(".folio-win-badge");
                    if(bdg){
                        bdg.className="mm-badge "+(next==="A"?"mm-badge-paid":"mm-badge-partial")+" folio-win-badge";
                        bdg.textContent=next==="A"?"Window A · Corporate Master":"Window B · Guest Incidentals";
                    }
                    this.textContent="Move to Window "+(next==="A"?"B":"A")+" ↔";
                    recalc();
                });
            }
            if(lateSel)lateSel.addEventListener("change",recalc);
            var settleBtn=document.getElementById("folio-btn-settle"),ban=document.getElementById("folio-settle-banner");
            if(settleBtn&&ban)settleBtn.addEventListener("click",function(){ban.style.display="block";});
        })();</script>`;
        return shellWith(body, '', '', 'Multi-Window Split-Folio & Express Checkout', ['Hotel', 'Bookings', 'Split-Folio Settlement']);
    }

    function renderThermalStyles() {
        return `<style>
            .mm-thermal-studio { display:grid; grid-template-columns:repeat(auto-fit,minmax(335px,1fr)); gap:22px; justify-items:center; padding:12px 6px; background:linear-gradient(180deg,rgba(15,23,42,0.04),rgba(15,23,42,0.08)); border-radius:12px; border:1px dashed rgba(100,116,139,0.38); }
            .mm-thermal-slip { position:relative; width:348px; max-width:100%; background:linear-gradient(180deg,#fffef9 0%,#fffdf5 100%); color:#0f172a; border-left:1px solid #cbd5e1; border-right:1px solid #cbd5e1; border-top:6px solid #0f172a; border-bottom:6px dashed #475569; border-radius:6px; padding:16px 15px; font-family:'Courier New',Courier,monospace; font-size:11.8px; line-height:1.42; box-shadow:0 16px 34px -6px rgba(15,23,42,0.18), 0 2px 6px rgba(15,23,42,0.08); transition:width 0.18s ease; }
            .mm-thermal-studio.is-58mm .mm-thermal-slip { width:268px; font-size:11px; padding:12px 10px; }
            .mm-thermal-hw-tag { display:flex; align-items:center; justify-content:space-between; font-size:10px; font-weight:700; color:#475569; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:4px; padding:3px 7px; margin-bottom:8px; letter-spacing:0.3px; }
            .mm-thermal-crest { width:42px; height:42px; margin:0 auto 5px; border:2px solid #0f172a; border-radius:50%; display:flex; flex-direction:column; align-items:center; justify-content:center; font-weight:900; font-size:11px; line-height:1; letter-spacing:0.5px; background:#fff; }
            .mm-thermal-band { background:#0f172a; color:#ffffff; text-align:center; font-weight:800; padding:5px 8px; margin:6px 0; border-radius:3px; letter-spacing:0.7px; font-size:11.5px; text-transform:uppercase; }
            .mm-thermal-subband { background:#e2e8f0; color:#0f172a; text-align:center; font-weight:800; padding:3px 6px; margin:4px 0 8px; border-radius:3px; border:1px solid #94a3b8; font-size:10.5px; letter-spacing:0.4px; }
            .mm-thermal-meta-grid { display:grid; grid-template-columns:1fr 1fr; gap:3px 8px; border:1px solid #94a3b8; border-radius:4px; padding:6px 8px; margin-bottom:8px; background:#f8fafc; font-size:10.8px; }
            .mm-thermal-divider { border-top:1px dashed #64748b; margin:7px 0; }
            .mm-thermal-double { border-top:3px double #0f172a; border-bottom:3px double #0f172a; padding:6px 0; margin:7px 0; font-weight:800; font-size:13.5px; }
            .mm-thermal-item-row { padding:4px 0; border-bottom:1px dotted #cbd5e1; }
            .mm-thermal-item-row:last-child { border-bottom:0; }
            .mm-thermal-mod { font-size:10.5px; color:#334155; padding-left:10px; font-style:italic; }
            .mm-thermal-stn-badge { display:inline-block; font-size:9.8px; font-weight:800; padding:1px 5px; border:1px solid #0f172a; border-radius:3px; background:#f1f5f9; color:#0f172a; }
            .mm-thermal-sign-box { border:1.5px dashed #334155; border-radius:4px; padding:8px; margin-top:8px; background:#f8fafc; font-size:10.8px; }
            .mm-thermal-pass-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:4px; margin-top:7px; text-align:center; font-size:10px; }
            .mm-thermal-pass-cell { border:1px solid #94a3b8; border-radius:3px; padding:4px 2px; background:#fff; }
            .mm-thermal-barcode { text-align:center; margin-top:8px; padding-top:6px; border-top:1px dashed #94a3b8; font-size:10px; letter-spacing:2px; font-weight:700; }
            .mm-thermal-qr-box { display:flex; align-items:center; gap:10px; border:1px solid #94a3b8; border-radius:4px; padding:6px 8px; margin-top:8px; background:#fff; font-size:10px; line-height:1.35; }
            .mm-thermal-reprint-stamp { display:none; text-align:center; border:2px solid #b42318; color:#b42318; font-weight:900; font-size:11px; padding:2px 6px; margin-bottom:6px; border-radius:3px; letter-spacing:1px; background:#fef2f2; }
            .mm-thermal-studio.is-reprint .mm-thermal-reprint-stamp { display:block; }
            @media print {
                body * { visibility:hidden; }
                #upos-live-receipt-panel, #upos-live-receipt-panel *, #upos-kot-modal, #upos-kot-modal * { visibility:visible; }
                #upos-kot-toolbar, .mm-no-print { display:none !important; }
            }
        </style>`;
    }

    function unifiedPosPage() {
        const body = `<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
            ${renderThermalStyles()}
            <header class="mm-page-head mm-no-print tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
                <div class="mm-page-title">
                    <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-cutlery"></i> Unified Restaurant, Bar &amp; In-Room Dining POS Engine (Plan A · Phase 3 #6)</h1>
                    <p class="tw-m-0 tw-text-sm tw-text-muted">Single shared POS engine across Heritage Dining Restaurant, Skyline Lounge Bar, and 20-Suite Room Service with KDS course fire, table merge, bill splitting, and Dual-Slip 80mm Thermal KOT &amp; Mushak 6.3 Check Studio.</p>
                </div>
                <div class="tw-flex tw-flex-wrap tw-gap-2">
                    <a href="/preview/rst-pos" class="mm-button mm-button-secondary"><i class="fa fa-cutlery"></i> Restaurant POS</a>
                    <a href="/preview/bar-pos" class="mm-button mm-button-secondary"><i class="fa fa-glass"></i> Bar POS</a>
                    <a href="/preview/rst-kitchen-board" class="mm-button mm-button-secondary"><i class="fa fa-fire"></i> Kitchen KDS</a>
                    <button type="button" class="mm-button mm-button-secondary" data-toggle="modal" data-target="#upos-kot-modal"><i class="fa fa-print"></i> 80mm Thermal KOT / Check Drawer</button>
                    <button type="button" class="mm-button" id="upos-btn-fire-kds"><i class="fa fa-fire"></i> Fire KOT &amp; Post to Room Folio</button>
                </div>
            </header>

            <!-- 3-Step POS Workflow Stepper (Fixed 3-Card Horizontal Grid w/ Interactive Step Switching) -->
            <nav class="mm-stepper mm-no-print" aria-label="Unified POS workflow">
                <ol class="mm-stepper-list">
                    <li class="mm-step is-done" id="upos-step-1" data-pos-step="1">
                        <span class="mm-step-index" id="upos-step-idx-1">&#10003;</span>
                        <span class="mm-step-copy">
                            <span class="mm-step-label">1. Outlet, Table / Suite &amp; Courses</span>
                            <span class="mm-step-sub" id="upos-step-sub-1">Heritage Dining · Table #T-04 · 3 Active Courses</span>
                        </span>
                        <span class="mm-step-pill" id="upos-step-pill-1">Completed</span>
                    </li>
                    <li class="mm-step is-current" id="upos-step-2" data-pos-step="2" aria-current="step">
                        <span class="mm-step-index" id="upos-step-idx-2">2</span>
                        <span class="mm-step-copy">
                            <span class="mm-step-label">2. Fire KOT to Kitchen &amp; Bar KDS</span>
                            <span class="mm-step-sub" id="upos-step-sub-2">80mm KOT #2026-892 · Pacing &amp; Station Routing</span>
                        </span>
                        <span class="mm-step-pill" id="upos-step-pill-2">Active Step</span>
                    </li>
                    <li class="mm-step" id="upos-step-3" data-pos-step="3">
                        <span class="mm-step-index" id="upos-step-idx-3">3</span>
                        <span class="mm-step-copy">
                            <span class="mm-step-label">3. Split / Merge Bill &amp; Folio Settlement</span>
                            <span class="mm-step-sub" id="upos-step-sub-3">Mushak 6.3 Check · Suite #204 Folio Charge</span>
                        </span>
                        <span class="mm-step-pill" id="upos-step-pill-3">Ready</span>
                    </li>
                </ol>
            </nav>

            <div class="mm-table-kpi-grid mm-no-print" aria-label="Unified POS summary">
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-cutlery"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active Outlet &amp; Table</span><div class="mm-table-kpi-value" id="upos-kpi-outlet">Heritage Dining <span class="mm-table-kpi-sub">Table #T-04 · Suite #204</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-list"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Order Items &amp; KDS</span><div class="mm-table-kpi-value" id="upos-kpi-items">3 Courses <span class="mm-table-kpi-sub">KOT #892 Ready</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-columns"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Bill Split Mode</span><div class="mm-table-kpi-value" id="upos-kpi-split">Full Single Bill <span class="mm-table-kpi-sub">৳ 4,600 / Guest</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-money"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Gross Payable (w/ Tax)</span><div class="mm-table-kpi-value" id="upos-kpi-total">৳ 5,555 <span class="mm-table-kpi-sub">Subtotal: ৳ 4,600</span></div></div></div>
            </div>

            <div id="upos-banner" class="alert alert-success mm-no-print" style="display:none;margin:0;border-radius:10px;">
                <i class="fa fa-check-circle"></i> <strong id="upos-banner-msg">KOT #892 fired to Kitchen Display Board (KDS) and posted to Suite #204 (Brig. Gen. Mahmudul Hasan) Folio!</strong>
            </div>

            <!-- Step 1: Outlet, Table/Suite & Table Merge Bar -->
            <div class="mm-panel tw-p-4 mm-no-print" id="upos-panel-step1">
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:10px;align-items:end">
                    <div>
                        <label class="mm-label" for="upos-outlet-select">1. Active POS Outlet</label>
                        <select id="upos-outlet-select" class="form-control input-sm">
                            <option value="Heritage Dining Restaurant" selected>Heritage Dining Restaurant (16 Tables)</option>
                            <option value="Skyline Lounge Bar">Skyline Lounge Bar (12 Tables)</option>
                            <option value="24h In-Room Suite Dining">24h In-Room Suite Dining (20 Suites)</option>
                            <option value="Grand Banquet Catering">Grand Banquet Catering</option>
                        </select>
                    </div>
                    <div>
                        <label class="mm-label" for="upos-table-select">2. Table / In-House Suite</label>
                        <select id="upos-table-select" class="form-control input-sm">
                            <option value="Table #T-04 · Suite #204 (Brig. Gen. Mahmud)" selected>Table #T-04 · Suite #204 (Brig. Gen. Mahmud)</option>
                            <option value="Table #T-01 · Suite #101 (Tanvir Chowdhury)">Table #T-01 · Suite #101 (Tanvir Chowdhury)</option>
                            <option value="Lounge #B-02 · Suite #301 (Amb. Takahashi)">Lounge #B-02 · Suite #301 (Amb. Takahashi)</option>
                            <option value="Room Service · Suite #403 (Rubana Huq)">Room Service · Suite #403 (Rubana Huq)</option>
                        </select>
                    </div>
                    <div>
                        <label class="mm-label" for="upos-merge-select">3. Merge Table / Transfer Ticket</label>
                        <div style="display:flex;gap:6px">
                            <select id="upos-merge-select" class="form-control input-sm">
                                <option value="Table #T-05 (Artisan Mocktail Platter)|1250">Merge Table #T-05 (+৳ 1,250)</option>
                                <option value="Bar Tab #B-07 (Sparkling High-Tea)|1800">Merge Bar Tab #B-07 (+৳ 1,800)</option>
                            </select>
                            <button type="button" id="upos-btn-merge" class="mm-button mm-button-secondary" style="min-height:30px;padding:2px 10px;font-size:11.5px;white-space:nowrap"><i class="fa fa-compress"></i> Merge</button>
                        </div>
                    </div>
                    <div>
                        <label class="mm-label" for="upos-course-pace">4. KDS Course Fire Pacing</label>
                        <select id="upos-course-pace" class="form-control input-sm">
                            <option value="Fire All Courses Together">Fire All Courses Together</option>
                            <option value="Fire Starters &amp; Bar Now · Hold Mains" selected>Fire Starters &amp; Bar · Hold Mains</option>
                            <option value="VIP Synchronized Table Service">VIP Synchronized Table Service</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(380px,1fr));gap:16px" class="mm-no-print" id="upos-panel-step3">
                <!-- Left: Category Filter, Search & Menu Grid -->
                <div class="mm-panel tw-p-5">
                    <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:10px">
                        <strong><i class="fa fa-th-large"></i> Menu Catalog — Tap Any Item to Add to Ticket</strong>
                        <input type="search" id="upos-menu-search" class="form-control input-sm" placeholder="Search dish or drink..." style="width:190px;">
                    </div>
                    <div class="mm-preview-filter-pills" id="upos-cat-pills" style="margin-bottom:12px">
                        <button type="button" class="mm-preview-pill is-active" data-upos-cat="all">All (8)</button>
                        <button type="button" class="mm-preview-pill" data-upos-cat="Mains">Signature Mains</button>
                        <button type="button" class="mm-preview-pill" data-upos-cat="Grills">Grills &amp; Seafood</button>
                        <button type="button" class="mm-preview-pill" data-upos-cat="Bar">Bar &amp; Mocktails</button>
                        <button type="button" class="mm-preview-pill" data-upos-cat="Dessert">Desserts</button>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px" id="upos-menu-grid">
                        <button type="button" class="mm-button mm-button-secondary upos-menu-btn" data-cat="Mains" data-course="Main" data-sku="MN-101" data-stn="HOT-1 · 15m" data-mod="Less Ghee, Extra Saffron Aloo, Raita on Side" data-name="Kacchi Biryani Royal Platter" data-price="1450" style="justify-content:space-between;width:100%"><span>Kacchi Biryani Platter</span><strong>৳ 1,450</strong></button>
                        <button type="button" class="mm-button mm-button-secondary upos-menu-btn" data-cat="Grills" data-course="Main" data-sku="GR-204" data-stn="GRILL · 14m" data-mod="Medium-Well, Lemon Caper Butter, No MSG" data-name="Grilled Norwegian Salmon" data-price="2200" style="justify-content:space-between;width:100%"><span>Grilled Norwegian Salmon</span><strong>৳ 2,200</strong></button>
                        <button type="button" class="mm-button mm-button-secondary upos-menu-btn" data-cat="Mains" data-course="Main" data-sku="MN-108" data-stn="HOT-1 · 12m" data-mod="Wild Porcini,aged Parmesan, Truffle Oil" data-name="Truffle Mushroom Risotto" data-price="1350" style="justify-content:space-between;width:100%"><span>Truffle Risotto</span><strong>৳ 1,350</strong></button>
                        <button type="button" class="mm-button mm-button-secondary upos-menu-btn" data-cat="Bar" data-course="Bar" data-sku="BR-301" data-stn="BAR · 05m" data-mod="Fresh Mint, Crushed Ice, Less Sugar" data-name="Signature Virgin Mojito Pitcher" data-price="850" style="justify-content:space-between;width:100%"><span>Virgin Mojito Pitcher</span><strong>৳ 850</strong></button>
                        <button type="button" class="mm-button mm-button-secondary upos-menu-btn" data-cat="Grills" data-course="Main" data-sku="GR-210" data-stn="GRILL · 18m" data-mod="Char-Grilled, Garlic Herb Glaze, Drawn Butter" data-name="Tandoori Lobster Thermidor" data-price="2800" style="justify-content:space-between;width:100%"><span>Lobster Thermidor</span><strong>৳ 2,800</strong></button>
                        <button type="button" class="mm-button mm-button-secondary upos-menu-btn" data-cat="Dessert" data-course="Dessert" data-sku="DS-402" data-stn="PASTRY · 10m" data-mod="Warm Molten Core, Madagascar Vanilla Gelato" data-name="Belgian Dark Lava Cake" data-price="650" style="justify-content:space-between;width:100%"><span>Belgian Lava Cake</span><strong>৳ 650</strong></button>
                        <button type="button" class="mm-button mm-button-secondary upos-menu-btn" data-cat="Bar" data-course="Bar" data-sku="BR-309" data-stn="BAR · 04m" data-mod="Chilled Glass, No Added Syrup, Lime Wedge" data-name="Cold-Pressed Pomegranate Elixir" data-price="620" style="justify-content:space-between;width:100%"><span>Pomegranate Elixir</span><strong>৳ 620</strong></button>
                        <button type="button" class="mm-button mm-button-secondary upos-menu-btn" data-cat="Dessert" data-course="Dessert" data-sku="DS-405" data-stn="PASTRY · 08m" data-mod="Saffron Rabri, Crushed Iranian Pistachio" data-name="Saffron Pistachio Shahi Tukda" data-price="580" style="justify-content:space-between;width:100%"><span>Saffron Shahi Tukda</span><strong>৳ 580</strong></button>
                    </div>
                </div>

                <!-- Right: Live Ticket, Qty +/-, Tax Breakdown, Bill Splitter & Room Charge -->
                <div class="mm-panel tw-p-5">
                    <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:10px">
                        <strong><i class="fa fa-receipt"></i> Active Order Ticket (#KOT-892) &amp; Split-Bill Engine</strong>
                        <select id="upos-split-select" class="form-control input-sm" style="width:195px;">
                            <option value="1" selected>Full Single Bill (1 Way)</option>
                            <option value="2">Split Equally · 2 Guests</option>
                            <option value="3">Split Equally · 3 Guests</option>
                            <option value="4">Split Equally · 4 Guests</option>
                        </select>
                    </div>
                    <div class="mm-table-scroll" style="margin-bottom:10px">
                        <table class="mm-data-table table table-bordered table-striped">
                            <thead><tr><th>Course &amp; Item</th><th class="text-center">Qty</th><th class="text-right">Line (৳)</th><th class="text-center">×</th></tr></thead>
                            <tbody id="upos-ticket-tbody">
                                <tr class="upos-row" data-unit="1450" data-qty="1" data-amt="1450" data-sku="MN-101" data-stn="HOT-1 · 15m" data-mod="Less Ghee, Extra Saffron Aloo, Raita on Side"><td><span class="mm-badge mm-badge-paid">Main</span> <strong>Kacchi Biryani Royal Platter</strong></td><td class="text-center"><button type="button" class="btn btn-xs btn-default upos-qty-dec">-</button> <span class="upos-qty-val">1</span> <button type="button" class="btn btn-xs btn-default upos-qty-inc">+</button></td><td class="text-right upos-line-cell">1,450</td><td class="text-center"><button type="button" class="btn btn-xs btn-danger upos-row-del">&times;</button></td></tr>
                                <tr class="upos-row" data-unit="2200" data-qty="1" data-amt="2200" data-sku="GR-204" data-stn="GRILL · 14m" data-mod="Medium-Well, Lemon Caper Butter, No MSG"><td><span class="mm-badge mm-badge-paid">Main</span> <strong>Grilled Norwegian Salmon</strong></td><td class="text-center"><button type="button" class="btn btn-xs btn-default upos-qty-dec">-</button> <span class="upos-qty-val">1</span> <button type="button" class="btn btn-xs btn-default upos-qty-inc">+</button></td><td class="text-right upos-line-cell">2,200</td><td class="text-center"><button type="button" class="btn btn-xs btn-danger upos-row-del">&times;</button></td></tr>
                                <tr class="upos-row" data-unit="950" data-qty="1" data-amt="950" data-sku="ST-019" data-stn="START · 08m" data-mod="Warm Garlic Butter Naan, Extra Roasted Hummus"><td><span class="mm-badge mm-badge-partial">Starter</span> <strong>Artisan Mezze &amp; Garlic Naan</strong></td><td class="text-center"><button type="button" class="btn btn-xs btn-default upos-qty-dec">-</button> <span class="upos-qty-val">1</span> <button type="button" class="btn btn-xs btn-default upos-qty-inc">+</button></td><td class="text-right upos-line-cell">950</td><td class="text-center"><button type="button" class="btn btn-xs btn-danger upos-row-del">&times;</button></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;font-size:12px">
                        <div><label class="mm-label">Discount / Happy Hour (%)</label><input type="number" id="upos-disc-pct" class="form-control input-sm" value="0" min="0" max="50"></div>
                        <div><label class="mm-label">Settlement Target</label><select id="upos-pay-method" class="form-control input-sm"><option value="Suite #204 Folio">Post to Suite #204 Folio</option><option value="VISA/MC POS + Cash">Multi-Tender: Card + Cash</option><option value="bKash / SSLCommerz QR">bKash / SSLCommerz QR</option></select></div>
                    </div>
                    <div style="padding:10px 12px;border-radius:8px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.28);font-size:12px">
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px"><span id="upos-tax-line">Subtotal: ৳ 4,600 · Svc (5%): ৳ 230 · VAT (15%): ৳ 725</span><span class="mm-badge mm-badge-paid" id="upos-per-person-badge">৳ 4,600 / bill</span></div>
                        <div style="display:flex;justify-content:space-between;align-items:center"><span id="upos-split-summary"><strong>Net Subtotal: ৳ 4,600 · Gross w/ Tax: ৳ 5,555</strong> (1 Way)</span><button type="button" class="mm-button" id="upos-btn-settle-inline" style="min-height:28px;padding:2px 10px;font-size:11.5px;"><i class="fa fa-check"></i> Settle / Post Folio</button></div>
                    </div>
                </div>
            </div>

            <!-- Step 2 & 3 Live On-Page Dual-Slip 80mm Thermal KOT & Mushak-6.3 Guest Check Receipt Studio -->
            <div class="mm-panel tw-p-5" id="upos-live-receipt-panel">
                <div id="upos-kot-toolbar" class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:14px;padding:10px 14px;border-radius:10px;background:rgba(15,23,42,0.04);border:1px solid rgba(148,163,184,0.32)">
                    <div>
                        <strong style="font-size:14px"><i class="fa fa-print"></i> Live 80mm Thermal KOT &amp; Mushak-6.3 Guest Check Receipt Studio (#KOT-2026-892)</strong>
                        <div class="tw-text-xs tw-text-muted">Auto-syncs in real time with Menu Catalog, Qty +/-, Course Pacing, Discounts, Split-Bill &amp; Suite Folio Selection.</div>
                    </div>
                    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2">
                        <div class="mm-preview-filter-pills" style="margin:0">
                            <button type="button" class="mm-preview-pill is-active" data-kot-view="both">Both 80mm Slips (KOT + Check)</button>
                            <button type="button" class="mm-preview-pill" data-kot-view="kot">1. Kitchen / Bar KOT (80mm)</button>
                            <button type="button" class="mm-preview-pill" data-kot-view="check">2. Guest Tax Check (80mm)</button>
                        </div>
                        <div class="mm-preview-filter-pills" style="margin:0">
                            <button type="button" class="mm-preview-pill is-active" data-paper-size="80mm">80mm Roll</button>
                            <button type="button" class="mm-preview-pill" data-paper-size="58mm">58mm Compact</button>
                        </div>
                        <label style="display:inline-flex;align-items:center;gap:5px;font-size:11.5px;margin:0;cursor:pointer;padding:4px 8px;border-radius:6px;border:1px solid rgba(148,163,184,0.35)">
                            <input type="checkbox" id="upos-kot-reprint-chk"> Reprint Stamp
                        </label>
                        <a href="/preview/pos-kot-print" class="mm-button mm-button-secondary" style="min-height:30px;padding:2px 10px;font-size:11.5px;"><i class="fa fa-external-link"></i> Standalone View</a>
                        <button type="button" class="mm-button" onclick="window.print()" style="min-height:30px;padding:2px 12px;font-size:11.5px;"><i class="fa fa-print"></i> Print 80mm Slips</button>
                    </div>
                </div>

                <div class="mm-thermal-studio" id="upos-thermal-studio">
                    <!-- SLIP 1: ADVANCED 80mm KITCHEN & BAR ORDER TICKET (KOT) -->
                    <div class="mm-thermal-slip" id="upos-slip-kot">
                        <div class="mm-thermal-hw-tag">
                            <span>EPSON TM-T88VI · 80MM</span>
                            <span>KDS CH #01 · AUTO-CUT</span>
                        </div>
                        <div class="mm-thermal-reprint-stamp">★ DUPLICATE / KITCHEN REPRINT ★</div>
                        <div class="mm-thermal-band">★ KITCHEN &amp; BAR ORDER TICKET ★</div>
                        <div class="mm-thermal-subband">PRIORITY: VIP IN-HOUSE SUITE · DINE-IN</div>
                        <div style="text-align:center;margin-bottom:6px">
                            <strong id="kot-print-outlet" style="font-size:13px">Heritage Dining Restaurant</strong><br>
                            <span style="font-size:10.5px">M&amp;M Heritage Hotel · Floor 2 Kitchen Pass</span>
                        </div>
                        <div class="mm-thermal-meta-grid">
                            <div>KOT NO: <strong>#KOT-2026-892</strong></div>
                            <div>CHK REF: <strong>#CHK-0892</strong></div>
                            <div>DATE: <strong>14 OCT 2026</strong></div>
                            <div>FIRED: <strong>19:45:18 BST</strong></div>
                            <div>SERVER: <strong>Arifur (#S14)</strong></div>
                            <div>TERM: <strong>POS-02 (RST)</strong></div>
                        </div>
                        <div style="border:2px solid #0f172a;padding:7px 8px;text-align:center;border-radius:4px;margin-bottom:7px;background:#f1f5f9">
                            <strong id="kot-print-table" style="font-size:13px;display:block">Table #T-04 · Suite #204 (Brig. Gen. Mahmud)</strong>
                            <span style="font-size:10.8px;font-weight:700">COVERS: 4 PAX · VIP GOLD · HALAL CERTIFIED</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;gap:4px;margin-bottom:5px">
                            <span class="mm-thermal-stn-badge">HOT KITCHEN #1</span>
                            <span class="mm-thermal-stn-badge">GRILL / TANDOOR</span>
                            <span class="mm-thermal-stn-badge">SKYLINE BAR</span>
                        </div>
                        <div style="font-size:11px;font-weight:800;text-transform:uppercase;padding:4px 6px;background:#fef3c7;border:1px solid #d97706;border-radius:3px;margin-bottom:6px" id="kot-print-pacing">
                            ▶ PACING: Fire Starters &amp; Bar · Hold Mains
                        </div>
                        <div class="mm-thermal-divider"></div>
                        <div style="display:flex;justify-content:space-between;font-weight:800;font-size:10.8px;border-bottom:1.5px solid #0f172a;padding-bottom:3px;margin-bottom:4px">
                            <span>QTY · COURSE / DISH &amp; MODIFIERS</span>
                            <span>STN · SLA</span>
                        </div>
                        <div id="kot-print-lines" style="margin-bottom:8px">
                            <div class="mm-thermal-item-row">
                                <div style="display:flex;justify-content:space-between;align-items:baseline">
                                    <strong>1x [MAIN] Kacchi Biryani Royal Platter</strong>
                                    <span class="mm-thermal-stn-badge">HOT-1 · 15m</span>
                                </div>
                                <div class="mm-thermal-mod">↳ Mod: Less Ghee, Extra Saffron Aloo, Raita on Side</div>
                            </div>
                            <div class="mm-thermal-item-row">
                                <div style="display:flex;justify-content:space-between;align-items:baseline">
                                    <strong>1x [MAIN] Grilled Norwegian Salmon</strong>
                                    <span class="mm-thermal-stn-badge">GRILL · 14m</span>
                                </div>
                                <div class="mm-thermal-mod">↳ Mod: Medium-Well, Lemon Caper Butter, No MSG</div>
                            </div>
                            <div class="mm-thermal-item-row">
                                <div style="display:flex;justify-content:space-between;align-items:baseline">
                                    <strong>1x [STARTER] Artisan Mezze &amp; Garlic Naan</strong>
                                    <span class="mm-thermal-stn-badge">START · 08m</span>
                                </div>
                                <div class="mm-thermal-mod">↳ Mod: Warm Garlic Butter Naan, Extra Roasted Hummus</div>
                            </div>
                        </div>
                        <div class="mm-thermal-divider"></div>
                        <div class="mm-thermal-sign-box">
                            <strong>⚠ ALLERGEN &amp; DIETARY PROTOCOL:</strong><br>
                            • VIP Suite #204 Guest · Strictly No MSG<br>
                            • Keep Shellfish &amp; Peanut Utensils Separate<br>
                            • Expediter: Ring Pass Bell for Table #T-04
                        </div>
                        <div class="mm-thermal-pass-grid">
                            <div class="mm-thermal-pass-cell"><strong>PREP CHEF</strong><br>A. Karim ✓</div>
                            <div class="mm-thermal-pass-cell"><strong>PASS EXPEDITER</strong><br>Chef Riaz ✓</div>
                            <div class="mm-thermal-pass-cell"><strong>RUNNER</strong><br>S. Alam #04</div>
                        </div>
                        <div class="mm-thermal-barcode">
                            || | |||| || ||| | |||| || | ||| |||| | ||<br>
                            <span style="letter-spacing:0.5px;font-size:9.8px">KOT-2026-0892 · KDS ACK 19:45:19</span>
                        </div>
                    </div>

                    <!-- SLIP 2: ADVANCED 80mm GUEST TAX CHECK & ROOM FOLIO SETTLEMENT RECEIPT -->
                    <div class="mm-thermal-slip" id="upos-slip-check">
                        <div class="mm-thermal-hw-tag">
                            <span>MUSHAK-6.3 FISCAL CHECK</span>
                            <span>NBR EFD · 80MM ROLL</span>
                        </div>
                        <div class="mm-thermal-reprint-stamp">★ DUPLICATE GUEST TAX CHECK ★</div>
                        <div class="mm-thermal-crest"><span>M&amp;M</span><span style="font-size:7px">★★★★★</span></div>
                        <div style="text-align:center;margin-bottom:6px">
                            <strong style="font-size:13px;letter-spacing:0.4px">M&amp;M HERITAGE HOTEL &amp; SUITES</strong><br>
                            <span style="font-size:10.2px">House 14, Road 45, Gulshan-2, Dhaka-1212</span><br>
                            <span style="font-size:10.2px">Tel: +880 2-55068899 · vat@mmheritagehotel.com</span><br>
                            <strong style="font-size:10.5px">NBR VAT Reg (BIN): 004192837-0101 · Mushak 6.3</strong>
                        </div>
                        <div class="mm-thermal-band">GUEST TAX CHECK &amp; FOLIO SLIP</div>
                        <div class="mm-thermal-meta-grid">
                            <div>INV NO: <strong>#INV-2026-892</strong></div>
                            <div>KOT REF: <strong>#KOT-2026-892</strong></div>
                            <div>DATE: <strong>14 OCT 2026</strong></div>
                            <div>TIME: <strong>19:48:05 BST</strong></div>
                            <div>CASHIER: <strong>Farhana A.</strong></div>
                            <div>COVERS: <strong>4 PAX (VIP)</strong></div>
                        </div>
                        <div style="font-size:11px;font-weight:700;padding:4px 7px;background:#f1f5f9;border:1px solid #94a3b8;border-radius:4px;margin-bottom:6px" id="check-print-table">
                            Table #T-04 · Suite #204 (Brig. Gen. Mahmud)
                        </div>
                        <div style="display:flex;justify-content:space-between;font-weight:800;font-size:10.8px;border-bottom:1.5px solid #0f172a;padding-bottom:3px;margin-bottom:4px">
                            <span>QTY · ITEM DESCRIPTION &amp; SKU</span>
                            <span>LINE (৳)</span>
                        </div>
                        <div id="check-print-lines" style="margin-bottom:8px">
                            <div class="mm-thermal-item-row">
                                <div style="display:flex;justify-content:space-between">
                                    <strong>1x Kacchi Biryani Royal Platter</strong>
                                    <strong>1,450.00</strong>
                                </div>
                                <div style="font-size:10px;color:#475569">SKU #MN-101 · 1 × @ ৳ 1,450.00 (Main)</div>
                            </div>
                            <div class="mm-thermal-item-row">
                                <div style="display:flex;justify-content:space-between">
                                    <strong>1x Grilled Norwegian Salmon</strong>
                                    <strong>2,200.00</strong>
                                </div>
                                <div style="font-size:10px;color:#475569">SKU #GR-204 · 1 × @ ৳ 2,200.00 (Main)</div>
                            </div>
                            <div class="mm-thermal-item-row">
                                <div style="display:flex;justify-content:space-between">
                                    <strong>1x Artisan Mezze &amp; Garlic Naan</strong>
                                    <strong>950.00</strong>
                                </div>
                                <div style="font-size:10px;color:#475569">SKU #ST-019 · 1 × @ ৳ 950.00 (Starter)</div>
                            </div>
                        </div>
                        <div class="mm-thermal-divider"></div>
                        <div style="font-size:11.2px;line-height:1.5" id="kot-print-totals">
                            <div style="display:flex;justify-content:space-between"><span>Gross F&amp;B Subtotal (3 Items):</span><strong>৳ 4,600.00</strong></div>
                            <div style="display:flex;justify-content:space-between"><span>Promo / Happy Hour Discount (0%):</span><span>- ৳ 0.00</span></div>
                            <div style="display:flex;justify-content:space-between"><span>Net Taxable F&amp;B Amount:</span><strong>৳ 4,600.00</strong></div>
                            <div style="display:flex;justify-content:space-between"><span>Hospitality Service Charge (5.0%):</span><span>+ ৳ 230.00</span></div>
                            <div style="display:flex;justify-content:space-between"><span>NBR Govt. VAT (15.0% Mushak 6.3):</span><span>+ ৳ 725.00</span></div>
                        </div>
                        <div class="mm-thermal-double" style="display:flex;justify-content:space-between;align-items:center">
                            <span>GRAND TOTAL (BDT)</span>
                            <span id="check-print-grand">৳ 5,555</span>
                        </div>
                        <div style="font-size:10.8px;padding:5px 7px;background:#ecfdf5;border:1px solid #059669;border-radius:4px;margin-bottom:6px;text-align:center;font-weight:700" id="check-print-split">
                            Split Mode: Full Single Bill (1 Way · ৳ 5,555)
                        </div>
                        <div class="mm-thermal-sign-box">
                            <strong>ROOM FOLIO CHARGE AUTHORIZATION</strong><br>
                            Settlement Mode: <strong id="check-print-tender">Post to Suite #204 Folio</strong><br>
                            In-House Suite: <strong>#204 — Brig. Gen. Mahmudul Hasan</strong><br>
                            Extra Gratuity / Tip: ৳ _____________________<br>
                            Guest Signature: ___________________________
                        </div>
                        <div class="mm-thermal-qr-box">
                            <div style="width:44px;height:44px;border:2px solid #0f172a;display:grid;grid-template-columns:repeat(5,1fr);gap:1px;padding:2px;background:#fff;flex-shrink:0" aria-hidden="true">
                                <span style="background:#0f172a"></span><span style="background:#0f172a"></span><span></span><span style="background:#0f172a"></span><span style="background:#0f172a"></span>
                                <span style="background:#0f172a"></span><span></span><span style="background:#0f172a"></span><span></span><span style="background:#0f172a"></span>
                                <span></span><span style="background:#0f172a"></span><span style="background:#0f172a"></span><span style="background:#0f172a"></span><span></span>
                                <span style="background:#0f172a"></span><span></span><span style="background:#0f172a"></span><span></span><span style="background:#0f172a"></span>
                                <span style="background:#0f172a"></span><span style="background:#0f172a"></span><span></span><span style="background:#0f172a"></span><span style="background:#0f172a"></span>
                            </div>
                            <div>
                                <strong>[QR VERIFIED: MMH-2026-CHK892]</strong><br>
                                EFD SDC: SDC-DHK-009412<br>
                                Fiscal Hash: 9F4A-882C-E109<br>
                                <em>Thank You for Dining at M&amp;M Heritage!</em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide-Over Drawer Modal Mirror (#upos-kot-modal) -->
            <div id="upos-kot-modal" class="modal fade mm-drawer" data-mm-drawer="true" tabindex="-1" role="dialog">
                <div class="modal-dialog" style="width:94vw;max-width:880px;"><div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-print"></i> 80mm Thermal KOT &amp; Mushak-6.3 Guest Check Print Drawer (#KOT-2026-892)</h4>
                    </div>
                    <div class="modal-body" id="upos-modal-receipt-mirror">
                        <p class="tw-text-xs tw-text-muted tw-mb-2">Live mirror of the Dual-Slip 80mm Thermal KOT &amp; Guest Check Receipt Studio:</p>
                        <div id="upos-modal-studio-slot"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mm-button mm-button-secondary" data-dismiss="modal">Close</button>
                        <a href="/preview/pos-kot-print" class="mm-button mm-button-secondary"><i class="fa fa-external-link"></i> Open Full-Page 80mm Studio</a>
                        <button type="button" class="mm-button" onclick="window.print()"><i class="fa fa-print"></i> Print 80mm Thermal Ticket</button>
                    </div>
                </div></div>
            </div>
        </div></section>
        <script>(function(){
            function fmt(n){return Math.round(n).toLocaleString("en-US");}
            var tbody=document.getElementById("upos-ticket-tbody"),splitSel=document.getElementById("upos-split-select"),outSel=document.getElementById("upos-outlet-select"),tblSel=document.getElementById("upos-table-select"),discInp=document.getElementById("upos-disc-pct"),paceSel=document.getElementById("upos-course-pace"),paySel=document.getElementById("upos-pay-method");
            var studioEl=document.getElementById("upos-thermal-studio"),modalSlot=document.getElementById("upos-modal-studio-slot");
            function syncModalMirror(){
                if(studioEl&&modalSlot){
                    modalSlot.innerHTML=studioEl.outerHTML;
                }
            }
            function recalc(){
                var rows=tbody.querySelectorAll(".upos-row"),sub=0,kotLines=[],chkLines=[];
                for(var i=0;i<rows.length;i++){
                    var u=Number(rows[i].getAttribute("data-unit")||rows[i].getAttribute("data-amt")||0);
                    var q=Number(rows[i].getAttribute("data-qty")||1);
                    var sku=rows[i].getAttribute("data-sku")||("FD-10"+(i+1));
                    var stn=rows[i].getAttribute("data-stn")||"HOT-1 · 12m";
                    var mod=rows[i].getAttribute("data-mod")||"Chef Standard Prep · Serve Hot";
                    var line=u*q;
                    rows[i].setAttribute("data-amt",String(line));
                    var qEl=rows[i].querySelector(".upos-qty-val"),lEl=rows[i].querySelector(".upos-line-cell"),nmEl=rows[i].querySelector("strong"),bdgEl0=rows[i].querySelector(".mm-badge");
                    if(qEl)qEl.textContent=String(q);
                    if(lEl)lEl.textContent=fmt(line);
                    sub+=line;
                    if(nmEl){
                        var courseName=(bdgEl0?bdgEl0.textContent:"Main");
                        var tag=courseName.toUpperCase();
                        kotLines.push('<div class="mm-thermal-item-row"><div style="display:flex;justify-content:space-between;align-items:baseline"><strong>'+q+"x ["+tag+"] "+nmEl.textContent+'</strong><span class="mm-thermal-stn-badge">'+stn+'</span></div><div class="mm-thermal-mod">↳ Mod: '+mod+"</div></div>");
                        chkLines.push('<div class="mm-thermal-item-row"><div style="display:flex;justify-content:space-between"><strong>'+q+"x "+nmEl.textContent+"</strong><strong>"+fmt(line)+'.00</strong></div><div style="font-size:10px;color:#475569">SKU #'+sku+" · "+q+" × @ ৳ "+fmt(u)+".00 ("+courseName+")</div></div>");
                    }
                }
                var discPct=Number(discInp&&discInp.value||0);
                var discAmt=Math.round(sub*(discPct/100));
                var netSub=sub-discAmt;
                var svc=Math.round(netSub*0.05),vat=Math.round((netSub+svc)*0.15),grand=netSub+svc+vat;
                var ways=Number(splitSel&&splitSel.value||1),perSub=Math.round(netSub/ways),perGrand=Math.round(grand/ways);
                var kItems=document.getElementById("upos-kpi-items"),kSplit=document.getElementById("upos-kpi-split"),kTot=document.getElementById("upos-kpi-total");
                if(kItems)kItems.innerHTML=rows.length+' Courses <span class="mm-table-kpi-sub">KOT #892 Ready</span>';
                if(kSplit)kSplit.innerHTML=(ways===1?"Full Single Bill":"Split "+ways+" Ways")+' <span class="mm-table-kpi-sub">৳ '+fmt(perSub)+" / Guest</span>";
                if(kTot)kTot.innerHTML="৳ "+fmt(grand)+' <span class="mm-table-kpi-sub">Subtotal: ৳ '+fmt(netSub)+"</span>";
                var s1Sub=document.getElementById("upos-step-sub-1");
                if(s1Sub&&outSel)s1Sub.textContent=outSel.value.split(" ")[0]+" · "+rows.length+" Active Courses";
                var taxLine=document.getElementById("upos-tax-line"),sumEl=document.getElementById("upos-split-summary"),bdgEl=document.getElementById("upos-per-person-badge");
                if(taxLine)taxLine.textContent="Subtotal: ৳ "+fmt(netSub)+" · Svc (5%): ৳ "+fmt(svc)+" · VAT (15%): ৳ "+fmt(vat);
                if(sumEl)sumEl.innerHTML="<strong>Net Subtotal: ৳ "+fmt(netSub)+" · Gross w/ Tax: ৳ "+fmt(grand)+"</strong> ("+ways+" Way · ৳ "+fmt(perGrand)+"/guest)";
                if(bdgEl)bdgEl.textContent="৳ "+fmt(perSub)+" / bill";
                var kpLines=document.getElementById("kot-print-lines"),cpLines=document.getElementById("check-print-lines"),kpTot=document.getElementById("kot-print-totals"),cpGrand=document.getElementById("check-print-grand"),cpSplit=document.getElementById("check-print-split");
                if(kpLines)kpLines.innerHTML=kotLines.join("");
                if(cpLines)cpLines.innerHTML=chkLines.join("");
                if(kpTot)kpTot.innerHTML='<div style="display:flex;justify-content:space-between"><span>Gross F&amp;B Subtotal ('+rows.length+' Items):</span><strong>৳ '+fmt(sub)+'.00</strong></div><div style="display:flex;justify-content:space-between"><span>Promo / Happy Hour Discount ('+discPct+'%):</span><span>- ৳ '+fmt(discAmt)+'.00</span></div><div style="display:flex;justify-content:space-between"><span>Net Taxable F&amp;B Amount:</span><strong>৳ '+fmt(netSub)+'.00</strong></div><div style="display:flex;justify-content:space-between"><span>Hospitality Service Charge (5.0%):</span><span>+ ৳ '+fmt(svc)+'.00</span></div><div style="display:flex;justify-content:space-between"><span>NBR Govt. VAT (15.0% Mushak 6.3):</span><span>+ ৳ '+fmt(vat)+'.00</span></div>';
                if(cpGrand)cpGrand.textContent="৳ "+fmt(grand);
                if(cpSplit){
                    if(ways===1){
                        cpSplit.innerHTML="Split Mode: Full Single Bill (1 Way · ৳ "+fmt(grand)+")";
                    }else{
                        var seatRows=[];
                        for(var s=1;s<=ways;s++)seatRows.push("Guest #"+s+": ৳ "+fmt(perGrand));
                        cpSplit.innerHTML="Split "+ways+" Ways ("+seatRows.join(" · ")+")";
                    }
                }
                syncModalMirror();
            }
            function appendRow(course,nm,pr,sku,stn,mod){
                var tr=document.createElement("tr");
                tr.className="upos-row";
                tr.setAttribute("data-unit",String(pr));
                tr.setAttribute("data-qty","1");
                tr.setAttribute("data-amt",String(pr));
                tr.setAttribute("data-sku",sku||"FD-209");
                tr.setAttribute("data-stn",stn||"HOT-1 · 12m");
                tr.setAttribute("data-mod",mod||"Chef Signature Prep · Serve Hot");
                tr.innerHTML='<td><span class="mm-badge mm-badge-paid">'+(course||"Main")+"</span> <strong>"+nm+'</strong></td><td class="text-center"><button type="button" class="btn btn-xs btn-default upos-qty-dec">-</button> <span class="upos-qty-val">1</span> <button type="button" class="btn btn-xs btn-default upos-qty-inc">+</button></td><td class="text-right upos-line-cell">'+fmt(pr)+'</td><td class="text-center"><button type="button" class="btn btn-xs btn-danger upos-row-del">&times;</button></td>';
                tbody.appendChild(tr);
                recalc();
            }
            var mBtns=document.querySelectorAll(".upos-menu-btn");
            for(var j=0;j<mBtns.length;j++){
                mBtns[j].addEventListener("click",function(){
                    appendRow(this.getAttribute("data-course"),this.getAttribute("data-name"),Number(this.getAttribute("data-price")||0),this.getAttribute("data-sku"),this.getAttribute("data-stn"),this.getAttribute("data-mod"));
                });
            }
            tbody.addEventListener("click",function(e){
                var t=e.target;
                if(!t)return;
                var tr=t.closest(".upos-row");
                if(!tr)return;
                if(t.classList.contains("upos-qty-inc")){
                    tr.setAttribute("data-qty",String(Number(tr.getAttribute("data-qty")||1)+1));
                    recalc();
                }else if(t.classList.contains("upos-qty-dec")){
                    var q=Math.max(1,Number(tr.getAttribute("data-qty")||1)-1);
                    tr.setAttribute("data-qty",String(q));
                    recalc();
                }else if(t.classList.contains("upos-row-del")){
                    tr.remove();
                    recalc();
                }
            });
            var mrgBtn=document.getElementById("upos-btn-merge"),mrgSel=document.getElementById("upos-merge-select");
            if(mrgBtn&&mrgSel){
                mrgBtn.addEventListener("click",function(){
                    var pts=(mrgSel.value||"").split("|");
                    appendRow("Merged",pts[0]||"Merged Table Order",Number(pts[1]||1250),"MRG-501","BAR/HOT · 06m","Transferred & Merged Table Ticket");
                });
            }
            var catPills=document.querySelectorAll("[data-upos-cat]"),qInp=document.getElementById("upos-menu-search"),activeCat="all";
            function filterMenu(){
                var q=(qInp&&qInp.value||"").toLowerCase().trim();
                for(var i=0;i<mBtns.length;i++){
                    var b=mBtns[i],c=b.getAttribute("data-cat"),nm=(b.getAttribute("data-name")||"").toLowerCase();
                    var ok=(activeCat==="all"||c===activeCat)&&(!q||nm.indexOf(q)>=0);
                    b.style.display=ok?"":"none";
                }
            }
            for(var c=0;c<catPills.length;c++){
                catPills[c].addEventListener("click",function(){
                    for(var k=0;k<catPills.length;k++)catPills[k].classList.remove("is-active");
                    this.classList.add("is-active");
                    activeCat=this.getAttribute("data-upos-cat");
                    filterMenu();
                });
            }
            if(qInp)qInp.addEventListener("input",filterMenu);
            if(splitSel)splitSel.addEventListener("change",recalc);
            if(discInp)discInp.addEventListener("input",recalc);
            function syncOutletHeader(){
                var kOut=document.getElementById("upos-kpi-outlet"),tVal=tblSel?tblSel.value:"Table #T-04 · Suite #204";
                if(kOut&&outSel)kOut.innerHTML=outSel.value+' <span class="mm-table-kpi-sub">'+tVal+"</span>";
                var kpOut=document.getElementById("kot-print-outlet"),kpTbl=document.getElementById("kot-print-table"),cpTbl=document.getElementById("check-print-table"),kpPace=document.getElementById("kot-print-pacing"),cpTend=document.getElementById("check-print-tender");
                if(kpOut&&outSel)kpOut.textContent=outSel.value;
                if(kpTbl)kpTbl.textContent=tVal;
                if(cpTbl)cpTbl.textContent=tVal;
                if(kpPace&&paceSel)kpPace.textContent="▶ PACING: "+paceSel.value;
                if(cpTend&&paySel)cpTend.textContent=paySel.value;
                syncModalMirror();
            }
            if(outSel)outSel.addEventListener("change",syncOutletHeader);
            if(tblSel)tblSel.addEventListener("change",syncOutletHeader);
            if(paceSel)paceSel.addEventListener("change",syncOutletHeader);
            if(paySel)paySel.addEventListener("change",syncOutletHeader);
            var stepItems=document.querySelectorAll("[data-pos-step]");
            function setActivePosStep(stepNum){
                for(var s=0;s<stepItems.length;s++){
                    var el=stepItems[s],n=Number(el.getAttribute("data-pos-step"));
                    var idxEl=document.getElementById("upos-step-idx-"+n),pillEl=document.getElementById("upos-step-pill-"+n);
                    el.classList.remove("is-done","is-current");
                    el.removeAttribute("aria-current");
                    if(n<stepNum){
                        el.classList.add("is-done");
                        if(idxEl)idxEl.innerHTML="&#10003;";
                        if(pillEl)pillEl.textContent="Completed";
                    }else if(n===stepNum){
                        el.classList.add("is-current");
                        el.setAttribute("aria-current","step");
                        if(idxEl)idxEl.textContent=String(n);
                        if(pillEl)pillEl.textContent="Active Step";
                    }else{
                        if(idxEl)idxEl.textContent=String(n);
                        if(pillEl)pillEl.textContent="Ready";
                    }
                }
            }
            for(var si=0;si<stepItems.length;si++){
                stepItems[si].addEventListener("click",function(){
                    var n=Number(this.getAttribute("data-pos-step")||2);
                    setActivePosStep(n);
                    var targetId=n===1?"upos-panel-step1":(n===2?"upos-live-receipt-panel":"upos-panel-step3");
                    var targetEl=document.getElementById(targetId);
                    if(targetEl&&targetEl.scrollIntoView)targetEl.scrollIntoView({behavior:"smooth",block:"nearest"});
                });
            }
            var fireBtn=document.getElementById("upos-btn-fire-kds"),settleBtn=document.getElementById("upos-btn-settle-inline"),ban=document.getElementById("upos-banner"),banMsg=document.getElementById("upos-banner-msg");
            function fireAndPost(){
                setActivePosStep(3);
                if(ban&&banMsg){
                    var payM=paySel?paySel.value:"Suite #204 Folio";
                    var pace=paceSel?paceSel.value:"Fire All Courses";
                    banMsg.textContent="KOT #892 ("+pace+") fired to Kitchen & Bar KDS and settled via "+payM+"!";
                    ban.style.display="block";
                }
            }
            if(fireBtn)fireBtn.addEventListener("click",fireAndPost);
            if(settleBtn)settleBtn.addEventListener("click",fireAndPost);
            var kotViewBtns=document.querySelectorAll("[data-kot-view]"),sKot=document.getElementById("upos-slip-kot"),sChk=document.getElementById("upos-slip-check");
            for(var kv=0;kv<kotViewBtns.length;kv++){
                kotViewBtns[kv].addEventListener("click",function(){
                    for(var k=0;k<kotViewBtns.length;k++)kotViewBtns[k].classList.remove("is-active");
                    this.classList.add("is-active");
                    var v=this.getAttribute("data-kot-view");
                    if(sKot)sKot.style.display=(v==="both"||v==="kot")?"":"none";
                    if(sChk)sChk.style.display=(v==="both"||v==="check")?"":"none";
                    syncModalMirror();
                });
            }
            var paperBtns=document.querySelectorAll("[data-paper-size]");
            for(var pb=0;pb<paperBtns.length;pb++){
                paperBtns[pb].addEventListener("click",function(){
                    for(var k=0;k<paperBtns.length;k++)paperBtns[k].classList.remove("is-active");
                    this.classList.add("is-active");
                    var sz=this.getAttribute("data-paper-size");
                    if(studioEl)studioEl.classList.toggle("is-58mm",sz==="58mm");
                    syncModalMirror();
                });
            }
            var repChk=document.getElementById("upos-kot-reprint-chk");
            if(repChk){
                repChk.addEventListener("change",function(){
                    if(studioEl)studioEl.classList.toggle("is-reprint",this.checked);
                    syncModalMirror();
                });
            }
            syncModalMirror();
        })();</script>`;
        return shellWith(body, '', '', 'Unified Restaurant & Bar POS Engine', ['Restaurant & Bar', 'POS', 'Unified Split-Bill Engine']);
    }

    function posKotPrintPage() {
        const body = `<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
            ${renderThermalStyles()}
            <header class="mm-page-head mm-no-print tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
                <div class="mm-page-title">
                    <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-print"></i> 80mm Thermal KOT &amp; Guest Tax Check Print Studio</h1>
                    <p class="tw-m-0 tw-text-sm tw-text-muted">Dual-slip 80mm thermal paper layout: Slip 1 (Kitchen &amp; Bar Order Ticket w/ Station SLA &amp; Modifiers) + Slip 2 (Mushak 6.3 Guest Tax Check, Room-Folio Authorization &amp; EFD QR).</p>
                </div>
                <div class="tw-flex tw-flex-wrap tw-gap-2">
                    <a href="/preview/pos-unified" class="mm-button mm-button-secondary"><i class="fa fa-cutlery"></i> Back to Unified POS</a>
                    <a href="/preview/rst-kitchen-board" class="mm-button mm-button-secondary"><i class="fa fa-fire"></i> Kitchen KDS Board</a>
                    <button type="button" class="mm-button" onclick="window.print()"><i class="fa fa-print"></i> Print 80mm Thermal Slips</button>
                </div>
            </header>
            <div class="mm-table-kpi-grid mm-no-print" aria-label="80mm thermal print summary">
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-print"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Thermal Paper Spec</span><div class="mm-table-kpi-value">80mm Roll <span class="mm-table-kpi-sub">72mm Print · 203 DPI</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-fire"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Slip 1 · Kitchen KOT</span><div class="mm-table-kpi-value">#KOT-2026-892 <span class="mm-table-kpi-sub">Course SLA + Allergen</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-file-text-o"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Slip 2 · Guest Check</span><div class="mm-table-kpi-value">৳ 5,555 Gross <span class="mm-table-kpi-sub">Mushak 6.3 + Room Sign</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-qrcode"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Fiscal Verification</span><div class="mm-table-kpi-value">EFD QR Stamped <span class="mm-table-kpi-sub">SDC-DHK-009412</span></div></div></div>
            </div>
            <div class="mm-panel tw-p-5">
                <div class="mm-thermal-studio">
                    <div class="mm-thermal-slip">
                        <div class="mm-thermal-hw-tag"><span>EPSON TM-T88VI · 80MM</span><span>KDS CH #01 · AUTO-CUT</span></div>
                        <div class="mm-thermal-band">★ KITCHEN &amp; BAR ORDER TICKET ★</div>
                        <div class="mm-thermal-subband">PRIORITY: VIP IN-HOUSE SUITE · DINE-IN</div>
                        <div style="text-align:center;margin-bottom:6px">
                            <strong style="font-size:13px">Heritage Dining Restaurant</strong><br>
                            <span style="font-size:10.5px">KOT #2026-892 · 14 Oct 2026 · 19:45:18</span>
                        </div>
                        <div class="mm-thermal-meta-grid">
                            <div>KOT NO: <strong>#KOT-2026-892</strong></div>
                            <div>CHK REF: <strong>#CHK-0892</strong></div>
                            <div>SERVER: <strong>Arifur (#S14)</strong></div>
                            <div>TERM: <strong>POS-02 (RST)</strong></div>
                        </div>
                        <div style="border:2px solid #0f172a;padding:6px;text-align:center;border-radius:4px;margin-bottom:8px;background:#f1f5f9">
                            <strong style="font-size:13px">Table #T-04 · Suite #204 (Brig. Gen. Mahmud)</strong><br>
                            <span style="font-size:10.8px;font-weight:700">COVERS: 4 PAX · VIP GOLD · HALAL</span>
                        </div>
                        <div style="font-size:11px;font-weight:800;text-transform:uppercase;padding:4px 6px;background:#fef3c7;border:1px solid #d97706;border-radius:3px;margin-bottom:6px">▶ PACING: Fire Starters &amp; Bar · Hold Mains</div>
                        <div class="mm-thermal-divider"></div>
                        <div style="display:flex;justify-content:space-between;font-weight:800;font-size:10.8px;border-bottom:1.5px solid #0f172a;padding-bottom:3px;margin-bottom:4px"><span>QTY · COURSE / DISH &amp; MODIFIERS</span><span>STN · SLA</span></div>
                        <div style="margin-bottom:8px">
                            <div class="mm-thermal-item-row"><div style="display:flex;justify-content:space-between"><strong>1x [MAIN] Kacchi Biryani Platter</strong><span class="mm-thermal-stn-badge">HOT-1 · 15m</span></div><div class="mm-thermal-mod">↳ Mod: Less Ghee, Extra Saffron Aloo, Raita on Side</div></div>
                            <div class="mm-thermal-item-row"><div style="display:flex;justify-content:space-between"><strong>1x [MAIN] Grilled Norwegian Salmon</strong><span class="mm-thermal-stn-badge">GRILL · 14m</span></div><div class="mm-thermal-mod">↳ Mod: Medium-Well, Lemon Caper Butter, No MSG</div></div>
                            <div class="mm-thermal-item-row"><div style="display:flex;justify-content:space-between"><strong>1x [START] Artisan Mezze &amp; Garlic Naan</strong><span class="mm-thermal-stn-badge">START · 08m</span></div><div class="mm-thermal-mod">↳ Mod: Warm Garlic Butter Naan, Extra Roasted Hummus</div></div>
                        </div>
                        <div class="mm-thermal-divider"></div>
                        <div class="mm-thermal-sign-box"><strong>⚠ ALLERGEN &amp; DIETARY PROTOCOL:</strong><br>• VIP Suite #204 Guest · Strictly No MSG<br>• Salmon Medium-Well · Lemon Butter</div>
                        <div class="mm-thermal-pass-grid">
                            <div class="mm-thermal-pass-cell"><strong>PREP CHEF</strong><br>A. Karim ✓</div>
                            <div class="mm-thermal-pass-cell"><strong>EXPEDITER</strong><br>Chef Riaz ✓</div>
                            <div class="mm-thermal-pass-cell"><strong>RUNNER</strong><br>S. Alam #04</div>
                        </div>
                        <div class="mm-thermal-barcode">|| | |||| || ||| | |||| || | ||| |||| | ||<br><span style="letter-spacing:0.5px;font-size:9.8px">KOT-2026-0892 · KDS ACK 19:45:19</span></div>
                    </div>
                    <div class="mm-thermal-slip">
                        <div class="mm-thermal-hw-tag"><span>MUSHAK-6.3 FISCAL CHECK</span><span>NBR EFD · 80MM ROLL</span></div>
                        <div class="mm-thermal-crest"><span>M&amp;M</span><span style="font-size:7px">★★★★★</span></div>
                        <div style="text-align:center;margin-bottom:6px">
                            <strong style="font-size:13px">M&amp;M HERITAGE HOTEL &amp; SUITES</strong><br>
                            <span style="font-size:10.2px">House 14, Road 45, Gulshan-2, Dhaka-1212</span><br>
                            <strong style="font-size:10.5px">BIN: 004192837-0101 · Mushak 6.3 Tax Check</strong>
                        </div>
                        <div class="mm-thermal-band">GUEST TAX CHECK &amp; FOLIO SLIP</div>
                        <div class="mm-thermal-meta-grid">
                            <div>INV NO: <strong>#INV-2026-892</strong></div>
                            <div>KOT REF: <strong>#KOT-2026-892</strong></div>
                            <div>DATE: <strong>14 OCT 2026</strong></div>
                            <div>CASHIER: <strong>Farhana A.</strong></div>
                        </div>
                        <div style="font-size:11px;font-weight:700;padding:4px 7px;background:#f1f5f9;border:1px solid #94a3b8;border-radius:4px;margin-bottom:6px">Table #T-04 · Suite #204 (Brig. Gen. Mahmud)</div>
                        <div style="display:flex;justify-content:space-between;font-weight:800;font-size:10.8px;border-bottom:1.5px solid #0f172a;padding-bottom:3px;margin-bottom:4px"><span>QTY · ITEM DESCRIPTION &amp; SKU</span><span>LINE (৳)</span></div>
                        <div style="margin-bottom:8px">
                            <div class="mm-thermal-item-row"><div style="display:flex;justify-content:space-between"><strong>1x Kacchi Biryani Royal Platter</strong><strong>1,450.00</strong></div><div style="font-size:10px;color:#475569">SKU #MN-101 · 1 × @ ৳ 1,450.00 (Main)</div></div>
                            <div class="mm-thermal-item-row"><div style="display:flex;justify-content:space-between"><strong>1x Grilled Norwegian Salmon</strong><strong>2,200.00</strong></div><div style="font-size:10px;color:#475569">SKU #GR-204 · 1 × @ ৳ 2,200.00 (Main)</div></div>
                            <div class="mm-thermal-item-row"><div style="display:flex;justify-content:space-between"><strong>1x Artisan Mezze &amp; Garlic Naan</strong><strong>950.00</strong></div><div style="font-size:10px;color:#475569">SKU #ST-019 · 1 × @ ৳ 950.00 (Starter)</div></div>
                        </div>
                        <div class="mm-thermal-divider"></div>
                        <div style="font-size:11.2px;line-height:1.5">
                            <div style="display:flex;justify-content:space-between"><span>Gross F&amp;B Subtotal:</span><strong>৳ 4,600.00</strong></div>
                            <div style="display:flex;justify-content:space-between"><span>Hospitality Service Charge (5%):</span><span>+ ৳ 230.00</span></div>
                            <div style="display:flex;justify-content:space-between"><span>NBR Govt. VAT (15% Mushak 6.3):</span><span>+ ৳ 725.00</span></div>
                        </div>
                        <div class="mm-thermal-double" style="display:flex;justify-content:space-between"><span>GRAND TOTAL (BDT)</span><span>৳ 5,555</span></div>
                        <div class="mm-thermal-sign-box"><strong>ROOM FOLIO CHARGE AUTHORIZATION</strong><br>Suite / Room: <strong>#204 (Brig. Gen. Mahmud)</strong><br>Extra Gratuity / Tip: ৳ _____________________<br>Guest Signature: ___________________________</div>
                        <div style="text-align:center;margin-top:8px;font-size:10.5px;color:#475569">[QR VERIFIED: MMH-2026-CHK892 · SDC-DHK-009412]<br>Thank You for Dining at M&amp;M Heritage!</div>
                    </div>
                </div>
            </div>
        </div></section>`;
        return shellWith(body, '', '', '80mm Thermal KOT & Guest Check Print Studio', ['Restaurant & Bar', 'POS', '80mm Thermal KOT']);
    }

    function planACommandCenterPage() {
        const workflows = [
            ['Phase 3 · #1', 'Front-Desk Executive Dashboard & Room Board', '/', 'frontdesk', '20 Suites · 8 Arrivals · 118ms p95', '/preview/payment-gateway', 'F1 Payment Gateway', 'Live arrivals/departures/in-house tiles, operational pulse strip, room-status board, and quick date chips.'],
            ['Phase 3 · #2', '7-Day Reservation Tape Chart & Server Cart', '/preview/tape-chart', 'frontdesk', 'Server Cart Active · 0 Conflicts', '/preview/channel-manager', 'F2 Channel Manager', '20-room × 7-day visual timeline matrix with server-side booking cart hold & overlap conflict guard.'],
            ['Phase 3 · #3', '3-Step Booking Wizard, Rate/Add-Ons & 2-Page A4 PDF', '/preview/booking-next', 'frontdesk', 'Rate Plans · 2-Page A4 Draft PDF', '/preview/rate-plans', 'F3 Rate Plans & Promos', 'Step 1 floor map & 4-filter bar, Step 2 multi-guest KYC, Step 3 rate/promo/add-ons, deposit guard & 2-page A4 PDF.'],
            ['Phase 3 · #4', 'Multi-Window Split-Folio & Express Checkout', '/preview/folio-settlement', 'finance', 'Window A (Corp) + Window B (Guest)', '/preview/kiosk-checkin', 'F5 Express Kiosk', 'Split Corporate Master Bill vs. Guest Incidentals, late check-out fee engine, and multi-tender settlement.'],
            ['Phase 3 · #5', 'Night-Audit Hardening & Guided Checklist', '/preview/night-audit-hardening', 'finance', '6-Step Pre-Check · Day-Close Lock', '/preview/hotel-night-audit', 'Night Audit Report', '6-step pre-audit verification, recurring tariff auto-posting, variance drill-down, and Day-Close Lock.'],
            ['Phase 3 · #6', 'Unified Restaurant, Bar & Room-Service POS + 80mm KOT', '/preview/pos-unified', 'ops', '3 Outlets · Dual 80mm KOT & Check', '/preview/pos-kot-print', '80mm KOT & Check Studio', 'Single shared POS console across Restaurant, Bar & Suites with KDS course fire, table merge, split bill & 80mm KOT.'],
            ['Phase 3 · #7', 'Accounting & Financial Statement Suite', '/preview/acc-balance-sheet', 'finance', 'P&L · Balance Sheet · Cash Flow', '/preview/acc-income-statement', 'Income Statement & P&L', 'Balance Sheet, Income Statement, Trial Balance, Cash Flow, and Ratio Analysis with visual share bars.'],
            ['Phase 3 · #8', 'Guest & Corporate CRM 360° Revival', '/preview/guest-crm', 'ops', '20 VIP Profiles · B2B Contracts', '/preview/corporate-accounts', 'F11 B2B Corporate Ledger', 'Revived CRM directory with stay history, lifetime value, VIP preferences, and corporate contracts.'],
            ['Phase 3 · #9', 'Room Settings, Pricing, Amenities & Admin Cluster', '/preview/room-settings', 'ops', '6 Room Categories · RBAC Matrix', '/preview/system-settings', 'System Settings & RBAC', 'Unified Room Category tariff calculator, dynamic amenity builder, tax/deposit simulator, and RBAC matrix.'],
        ];
        const phases = [
            ['Phase 0', 'P0', 'Security, Secret Purge & CI Baseline (P0.1–P0.5)', '/preview/audit-compliance', 'Credential rotation, POST-only destructive route guards, 6 diff tripwires (ui-guard.sh), and 34 automated Blade/UI test suites.', 'P0.1 Secrets Purged · P0.2 Branch Reconciled · P0.3 Route Guards · P0.4 /healthz & Rate-Limit · P0.5 CI Baseline'],
            ['Phase 1', 'P1', 'Platform, Redis Queue & Versioned REST API v1 (P1.1–P1.6)', '/preview/api-webhooks', 'Sanctum-scoped /api/v1 endpoints (/rooms, /bookings, /folio), Redis queue/cache, decimal(12,2) money hygiene, and vector PDF service.', 'P1.1 Blade Form Components · P1.2 Scoped CSS Build · P1.3 Redis Queue · P1.4 DB Indexes · P1.5 Lifecycle Tests · P1.6 /api/v1 OpenAPI'],
            ['Phase 2', 'P2', 'Mews/Cloudbeds-Light Design System (<x-mm.*>) (P2.1–P2.5)', '/preview/ui-kit', '14 Blade component primitives, Light & Dark Mode tokens, 40px frosted-glass controls, 3-card Stepper, and 29 Slide-Over Drawers.', 'P2.1 --mm-* Tokens · P2.2 14 <x-mm.*> Primitives · P2.3 AppShell Swap · P2.4 Interactive Islands · P2.5 Bilingual EN/BN Wiki'],
            ['Phase 3', 'P3', '9 Rebuilt Core Hospitality Workflows (P3.1–P3.9)', '/preview/booking-next', 'Dashboard, Tape Chart + Server Cart, 3-Step Wizard, Split-Folio Checkout, Night Audit, Unified POS + 80mm KOT, Accounting, CRM & Settings.', 'P3.1 Dashboard · P3.2 Tape Chart · P3.3 Booking Wizard · P3.4 Split Folio · P3.5 Night Audit · P3.6 Unified POS · P3.7 Accounting · P3.8 CRM · P3.9 Admin'],
            ['Phase 4', 'P4', 'Observability, 2FA, Audit Diff & Performance (P4.1–P4.5)', '/preview/activity-log', 'Immutable activity_logs JSON diff inspector, TOTP 2FA & session manager, <300ms list budgets, and RBAC permission bulk toggles.', 'P4.1 JSON Logs & /healthz · P4.2 2FA & Audit Diff · P4.3 <300ms p95 Cache · P4.4 Backup Drill · P4.5 Docs-as-Code'],
            ['Phase 5', 'P5', 'Public Storefront, SEO Schema.org & Guest Portal (P5.1–P5.3)', '/preview/public-home', 'Renovated mm-web public storefront, HotelRoom JSON-LD Schema.org generator, online booking engine, and Guest Self-Service Portal.', 'P5.1 mm-web Storefront · P5.2 Guest Portal & Pre-Check-In · P5.3 Schema.org HotelRoom SEO'],
            ['Phase 6', 'P6', 'Zero-Downtime Strangler-Fig Rollout & Cutover (P6.1–P6.4)', '/preview/system-settings', 'Coexisting legacy + modern routes with instant toggle control, bilingual Wiki/SOPs, expand-migrate-contract, and 1-click rollback.', 'P6.1 Dev/Staging/Prod Parity · P6.2 Live Rollout Gates · P6.3 Zero-Downtime Cutover · P6.4 Staff Runbook & SOP'],
        ];
        const flagCards = workflows.map(([code, title, route, _cat, telemetry, _pairRoute, pairLabel], idx) => `
            <div class="mm-rollout-card is-active-flag" data-flag-card="${idx}">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:6px">
                    <span class="mm-badge" style="font-weight:700">${code}</span>
                    <span class="mm-badge mm-badge-paid plana-flag-state-pill">ACTIVE · MODERN V2</span>
                </div>
                <div style="font-weight:700;font-size:13.5px;color:var(--mm-ink);line-height:1.3;margin-bottom:4px">${title}</div>
                <div style="font-size:11.5px;color:var(--mm-muted);margin-bottom:10px">
                    <i class="fa fa-tachometer"></i> ${telemetry} · <span style="color:#1d4ed8;font-weight:600">${pairLabel}</span>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:8px;border-top:1px dashed rgba(148,163,184,0.35)">
                    <label style="display:inline-flex;align-items:center;gap:6px;margin:0;cursor:pointer;font-size:12px;font-weight:600;color:var(--mm-ink)">
                        <input type="checkbox" class="plana-flag-chk" checked style="width:15px;height:15px;margin:0;accent-color:#1f7a4d;">
                        <span class="plana-flag-toggle-txt">Modern UI Enabled</span>
                    </label>
                    <a href="${route}" style="font-size:11.5px;font-weight:700;color:var(--mm-brand);text-decoration:none">Open →</a>
                </div>
            </div>`).join('');
        const rows = workflows.map(([code, title, route, cat, telemetry, pairRoute, pairLabel, desc]) => `
            <tr class="plana-wf-row" data-plana-cat="${cat}" data-plana-search="${(code + ' ' + title + ' ' + route + ' ' + telemetry + ' ' + pairLabel + ' ' + desc).toLowerCase()}">
                <td><span class="mm-badge" style="font-weight:700">${code}</span><div style="margin-top:4px;font-size:11px;color:var(--mm-muted)">${telemetry}</div></td>
                <td><strong>${title}</strong><div class="tw-text-xs tw-text-muted">${desc}</div></td>
                <td><span class="mm-badge mm-badge-paid">100% Live</span><div style="margin-top:4px"><a href="${pairRoute}" style="font-size:11px;font-weight:600;text-decoration:underline">${pairLabel} →</a></div></td>
                <td><a href="${route}" class="mm-button mm-button-secondary" style="min-height:28px;padding:2px 10px;font-size:12px;">Launch Workspace (${route}) →</a></td>
            </tr>`).join('');
        const phaseCards = phases.map(([pCode, pShort, pTitle, pRoute, pDesc, pChecklist]) => `
            <a href="${pRoute}" class="mm-preview-card plana-phase-card" data-plana-phase-card="${pShort}">
                <div class="mm-preview-card-head">
                    <span class="mm-preview-card-title"><span class="mm-badge mm-badge-paid" style="margin-right:6px;">${pCode}</span>${pTitle}</span>
                    <span class="mm-preview-card-tag">100% Delivered</span>
                </div>
                <p class="mm-preview-card-desc">${pDesc}</p>
                <div style="font-size:11px;color:#047857;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.25);padding:5px 8px;border-radius:6px">✓ ${pChecklist}</div>
                <span class="mm-preview-card-route">Inspect Phase Workspace (${pRoute}) →</span>
            </a>`).join('');
        const body = `<section class="mm-ui"><div class="tw-space-y-4 tw-py-2">
            <style>
                .mm-rollout-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(290px,1fr)); gap:12px; }
                .mm-rollout-card { padding:14px 16px; border-radius:12px; border:1px solid rgba(16,185,129,0.38); background:linear-gradient(145deg,rgba(255,255,255,0.96),rgba(236,253,245,0.55)); box-shadow:0 4px 14px rgba(15,23,42,0.04); transition:all 0.16s ease; }
                .mm-rollout-card.is-legacy-flag { border-color:rgba(217,119,6,0.42); background:linear-gradient(145deg,rgba(255,255,255,0.96),rgba(254,243,199,0.55)); }
                .mm-shell.mm-dark .mm-rollout-card { background:linear-gradient(145deg,rgba(15,23,42,0.88),rgba(6,78,59,0.28)); border-color:rgba(52,211,153,0.35); }
                .mm-shell.mm-dark .mm-rollout-card.is-legacy-flag { background:linear-gradient(145deg,rgba(15,23,42,0.88),rgba(120,53,15,0.32)); border-color:rgba(251,191,36,0.38); }
            </style>
            <header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
                <div class="mm-page-title">
                    <h1 class="tw-m-0 tw-font-semibold tw-text-ink"><i class="fa fa-sitemap"></i> Plan A — Whole-Software Modernization &amp; Core Workflows Hub</h1>
                    <p class="tw-m-0 tw-text-sm tw-text-muted">Complete interactive command center for Whole-Software Modernization (Phases 0–6 Architecture, Live Module Rollout Controls, and all 9 Phase 3 Core Hospitality Workflows).</p>
                </div>
                <div class="tw-flex tw-flex-wrap tw-gap-2">
                    <a href="/preview/plan-b" class="mm-button"><i class="fa fa-rocket"></i> Plan B Hub (34/34)</a>
                    <a href="/preview/pos-unified" class="mm-button mm-button-secondary"><i class="fa fa-cutlery"></i> Unified POS + 80mm KOT</a>
                    <a href="/preview/folio-settlement" class="mm-button mm-button-secondary"><i class="fa fa-columns"></i> Split-Folio Checkout</a>
                    <button type="button" class="mm-button mm-button-secondary" id="plana-btn-healthcheck"><i class="fa fa-heartbeat"></i> Run /healthz &amp; CI Smoke Check</button>
                </div>
            </header>

            <!-- 3-Stage Modernization Program Stepper -->
            <nav class="mm-stepper mm-no-print" aria-label="Plan A modernization program progress">
                <ol class="mm-stepper-list">
                    <li class="mm-step is-done">
                        <span class="mm-step-index">&#10003;</span>
                        <span class="mm-step-copy">
                            <span class="mm-step-label">1. Phases 0–2 · Security, API v1 &amp; Design System</span>
                            <span class="mm-step-sub">6 Guardrail Tripwires · REST API v1 · 14 UI Component Primitives</span>
                        </span>
                        <span class="mm-step-pill">Completed</span>
                    </li>
                    <li class="mm-step is-done">
                        <span class="mm-step-index">&#10003;</span>
                        <span class="mm-step-copy">
                            <span class="mm-step-label">2. Phase 3 · 9 Rebuilt Core Hospitality Workflows</span>
                            <span class="mm-step-sub">Dashboard · Tape Chart · Wizard · Folio · Audit · POS · Accounts · CRM</span>
                        </span>
                        <span class="mm-step-pill">9 / 9 Live</span>
                    </li>
                    <li class="mm-step is-current" aria-current="step">
                        <span class="mm-step-index">3</span>
                        <span class="mm-step-copy">
                            <span class="mm-step-label">3. Phases 4–6 · Observability, Storefront &amp; Cutover</span>
                            <span class="mm-step-sub">Zero-Downtime Module Rollout · Activity Audit Diff · Live Cutover</span>
                        </span>
                        <span class="mm-step-pill">Production Active</span>
                    </li>
                </ol>
            </nav>

            <div class="mm-table-kpi-grid" aria-label="Plan A modernization summary">
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-avail"><i class="fa fa-check-circle"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Phase 3 Core Workflows</span><div class="mm-table-kpi-value">9 / 9 Rebuilt <span class="mm-table-kpi-sub">100% Interactive</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-all"><i class="fa fa-sitemap"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Modernization Phases</span><div class="mm-table-kpi-value">Phases 0–6 <span class="mm-table-kpi-sub">35 / 35 Deliverables</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-occ"><i class="fa fa-toggle-on"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">Active Modern Modules</span><div class="mm-table-kpi-value" id="plana-kpi-flags">9 / 9 Enabled <span class="mm-table-kpi-sub">Zero-Downtime Rollout</span></div></div></div>
                <div class="mm-table-kpi-card"><span class="mm-table-kpi-icon mm-table-kpi-icon-rev"><i class="fa fa-shield"></i></span><div class="mm-table-kpi-meta"><span class="mm-table-kpi-label">CI &amp; Guardrail Status</span><div class="mm-table-kpi-value" id="plana-kpi-health">34 / 34 Green <span class="mm-table-kpi-sub">p95: 142ms · /healthz OK</span></div></div></div>
            </div>

            <div id="plana-flag-banner" class="alert alert-success" style="display:none;margin:0;border-radius:10px;">
                <i class="fa fa-check-circle"></i> <strong id="plana-flag-msg">Module rollout configuration updated and synced!</strong>
            </div>

            <!-- Interactive Strangler-Fig Rollout & Feature Flag Console (Phase 0 & Phase 6) -->
            <div class="mm-panel tw-p-5" id="plana-flags-panel">
                <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:14px">
                    <div>
                        <h2 class="tw-m-0 tw-text-base tw-font-semibold"><i class="fa fa-sliders"></i> Strangler-Fig Module Rollout &amp; Feature Control Center (Phase 0 &amp; Phase 6)</h2>
                        <p class="tw-m-0 tw-text-xs tw-text-muted">Live production traffic routing across all 9 rebuilt hospitality modules — toggle any workflow between Modern V2 UI and Legacy Fallback with zero downtime.</p>
                    </div>
                    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2">
                        <select id="plana-env-select" class="form-control input-sm" style="width:215px;">
                            <option value="Production (Gulshan-2 Cluster)" selected>Env: Production (Gulshan-2)</option>
                            <option value="Staging (Nightly Anonymized Clone)">Env: Staging (Anonymized Clone)</option>
                            <option value="Docker Compose Dev (PHP 8.3 + Redis)">Env: Docker Dev (PHP 8.3 + Redis)</option>
                        </select>
                        <button type="button" class="mm-button mm-button-secondary" id="plana-btn-enable-all" style="min-height:30px;padding:3px 10px;font-size:12px">Enable All 9</button>
                        <button type="button" class="mm-button mm-button-secondary" id="plana-btn-rollback-all" style="min-height:30px;padding:3px 10px;font-size:12px">Rollback Legacy</button>
                        <button type="button" class="mm-button" id="plana-btn-save-flags" style="min-height:30px;padding:3px 12px;font-size:12px"><i class="fa fa-check"></i> Save Rollout State</button>
                    </div>
                </div>
                <div class="mm-rollout-grid">${flagCards}</div>
            </div>

            <!-- Phase 3 Rebuilt Core Workflows (1–9 Priority Matrix) -->
            <div class="mm-panel tw-p-5">
                <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:12px">
                    <div>
                        <h2 class="tw-m-0 tw-text-base tw-font-semibold"><i class="fa fa-bolt"></i> Phase 3 Rebuilt Core Workflows (1–9 Business Value × Risk Priority Matrix)</h2>
                        <p class="tw-m-0 tw-text-xs tw-text-muted">Click any workflow below to launch its interactive workspace, filter by domain, or jump to its paired Plan B integration.</p>
                    </div>
                    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2">
                        <div class="mm-preview-filter-pills" style="margin:0">
                            <button type="button" class="mm-preview-pill is-active" data-plana-wf-cat="all">All (9)</button>
                            <button type="button" class="mm-preview-pill" data-plana-wf-cat="frontdesk">Front Desk &amp; Reservations (3)</button>
                            <button type="button" class="mm-preview-pill" data-plana-wf-cat="finance">Folio, Audit &amp; Accounts (3)</button>
                            <button type="button" class="mm-preview-pill" data-plana-wf-cat="ops">POS, CRM &amp; Settings (3)</button>
                        </div>
                        <input type="search" id="plana-wf-search" class="form-control input-sm" placeholder="Filter 9 core workflows..." style="width:220px;">
                    </div>
                </div>
                <div class="mm-table-scroll">
                    <table class="mm-data-table table table-bordered table-striped">
                        <thead><tr><th>Priority &amp; Telemetry</th><th>Core Workflow &amp; Capabilities</th><th>Status &amp; Paired Plan B</th><th>Interactive Workspace</th></tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>

            <!-- Phases 0–6 Architecture Roadmap Cards -->
            <div class="mm-panel tw-p-5" id="plana-phases-explorer">
                <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2" style="margin-bottom:12px">
                    <div>
                        <h2 class="tw-m-0 tw-text-base tw-font-semibold"><i class="fa fa-cubes"></i> Phases 0–6 Whole-Software Modernization Architecture Tracks</h2>
                        <p class="tw-m-0 tw-text-xs tw-text-muted">Filter by phase below to inspect deliverables across Security (P0), Platform/API (P1), Design System (P2), Workflows (P3), Observability (P4), Public Web (P5), and Cutover (P6).</p>
                    </div>
                    <div class="mm-preview-filter-pills" style="margin:0">
                        <button type="button" class="mm-preview-pill is-active" data-plana-phase-filter="all">All Phases (P0–P6)</button>
                        <button type="button" class="mm-preview-pill" data-plana-phase-filter="P0">P0 Security</button>
                        <button type="button" class="mm-preview-pill" data-plana-phase-filter="P1">P1 Platform</button>
                        <button type="button" class="mm-preview-pill" data-plana-phase-filter="P2">P2 UI Kit</button>
                        <button type="button" class="mm-preview-pill" data-plana-phase-filter="P3">P3 Workflows</button>
                        <button type="button" class="mm-preview-pill" data-plana-phase-filter="P4">P4 Observability</button>
                        <button type="button" class="mm-preview-pill" data-plana-phase-filter="P5">P5 Storefront</button>
                        <button type="button" class="mm-preview-pill" data-plana-phase-filter="P6">P6 Cutover</button>
                    </div>
                </div>
                <div class="mm-preview-grid">${phaseCards}</div>
            </div>
        </div></section>
        <script>(function(){
            var chks=document.querySelectorAll(".plana-flag-chk"),kFlags=document.getElementById("plana-kpi-flags"),saveBtn=document.getElementById("plana-btn-save-flags"),enAllBtn=document.getElementById("plana-btn-enable-all"),rbAllBtn=document.getElementById("plana-btn-rollback-all"),envSel=document.getElementById("plana-env-select"),ban=document.getElementById("plana-flag-banner"),msg=document.getElementById("plana-flag-msg");
            function updateFlags(){
                var on=0;
                for(var i=0;i<chks.length;i++){
                    var chk=chks[i],card=chk.closest(".mm-rollout-card");
                    if(chk.checked)on++;
                    if(card){
                        card.classList.toggle("is-legacy-flag",!chk.checked);
                        var pill=card.querySelector(".plana-flag-state-pill"),txt=card.querySelector(".plana-flag-toggle-txt");
                        if(pill){
                            pill.textContent=chk.checked?"ACTIVE · MODERN V2":"LEGACY FALLBACK";
                            pill.className="mm-badge "+(chk.checked?"mm-badge-paid":"mm-badge-partial")+" plana-flag-state-pill";
                        }
                        if(txt)txt.textContent=chk.checked?"Modern UI Enabled":"Routed to Legacy";
                    }
                }
                if(kFlags)kFlags.innerHTML=on+" / "+chks.length+' Enabled <span class="mm-table-kpi-sub">Zero-Downtime Rollout</span>';
            }
            for(var j=0;j<chks.length;j++)chks[j].addEventListener("change",updateFlags);
            if(enAllBtn)enAllBtn.addEventListener("click",function(){
                for(var i=0;i<chks.length;i++)chks[i].checked=true;
                updateFlags();
            });
            if(rbAllBtn)rbAllBtn.addEventListener("click",function(){
                for(var i=0;i<chks.length;i++)chks[i].checked=false;
                updateFlags();
                if(ban&&msg){
                    msg.textContent="Instant Strangler-Fig rollback staged: all 9 workflows routed to legacy fallback views.";
                    ban.style.display="block";
                }
            });
            if(saveBtn&&ban&&msg){
                saveBtn.addEventListener("click",function(){
                    var on=0;
                    for(var i=0;i<chks.length;i++)if(chks[i].checked)on++;
                    var env=envSel?envSel.value:"Production";
                    msg.textContent="Rollout state saved on "+env+" ("+on+"/"+chks.length+" modern Phase 3 workflows active)!";
                    ban.style.display="block";
                });
            }
            var hcBtn=document.getElementById("plana-btn-healthcheck"),kHealth=document.getElementById("plana-kpi-health");
            if(hcBtn&&ban&&msg){
                hcBtn.addEventListener("click",function(){
                    if(kHealth)kHealth.innerHTML='34 / 34 Verified <span class="mm-table-kpi-sub">p95: 118ms · Redis &amp; DB OK</span>';
                    msg.textContent="Health & Smoke Check PASSED: GET /healthz 200 OK · 6/6 ui-guard.sh tripwires clean · 34/34 ui-check.cjs suites green · Redis queue & MySQL InnoDB healthy.";
                    ban.style.display="block";
                });
            }
            var q=document.getElementById("plana-wf-search"),wfRows=document.querySelectorAll(".plana-wf-row"),wfCatBtns=document.querySelectorAll("[data-plana-wf-cat]"),activeWfCat="all";
            function filterWorkflows(){
                var s=(q&&q.value||"").toLowerCase().trim();
                for(var i=0;i<wfRows.length;i++){
                    var r=wfRows[i],c=r.getAttribute("data-plana-cat"),txt=r.getAttribute("data-plana-search")||"";
                    var ok=(activeWfCat==="all"||c===activeWfCat)&&(!s||txt.indexOf(s)>=0);
                    r.style.display=ok?"":"none";
                }
            }
            if(q)q.addEventListener("input",filterWorkflows);
            for(var wc=0;wc<wfCatBtns.length;wc++){
                wfCatBtns[wc].addEventListener("click",function(){
                    for(var k=0;k<wfCatBtns.length;k++)wfCatBtns[k].classList.remove("is-active");
                    this.classList.add("is-active");
                    activeWfCat=this.getAttribute("data-plana-wf-cat");
                    filterWorkflows();
                });
            }
            var pBtns=document.querySelectorAll("[data-plana-phase-filter]"),pCards=document.querySelectorAll(".plana-phase-card");
            for(var pf=0;pf<pBtns.length;pf++){
                pBtns[pf].addEventListener("click",function(){
                    for(var k=0;k<pBtns.length;k++)pBtns[k].classList.remove("is-active");
                    this.classList.add("is-active");
                    var v=this.getAttribute("data-plana-phase-filter");
                    for(var m=0;m<pCards.length;m++){
                        var pc=pCards[m].getAttribute("data-plana-phase-card");
                        pCards[m].style.display=(v==="all"||pc===v)?"":"none";
                    }
                });
            }
        })();</script>`;
        return shellWith(body, '', '', 'Plan A Modernization & Core Workflows Hub', ['System', 'Modernization', 'Plan A Hub']);
    }

    const result = {
        '/preview/plan-a': {
            fn: planACommandCenterPage,
            title: 'Plan A — Whole-Software Modernization & Core Workflows Hub',
            crumbs: ['System', 'Modernization', 'Plan A Hub'],
        },
        '/preview/pos-kot-print': {
            fn: posKotPrintPage,
            title: '80mm Thermal KOT & Guest Tax Check Print Studio',
            crumbs: ['Restaurant & Bar', 'POS', '80mm Thermal KOT'],
        },
        '/preview/plan-b': {
            fn: planBCommandCenterPage,
            title: 'Complete Plan B Feature Command Center (F1–F34 · 100%)',
            crumbs: ['Hotel', 'Plan B Roadmap', 'All 34 Features'],
        },
        '/preview/room-settings': {
            fn: roomSettingsHubPage,
            title: 'Room Settings, Pricing, Amenities & Rules Control Center',
            crumbs: ['Hotel', 'Setup', 'Room Settings & Rules'],
        },
        '/preview/folio-settlement': {
            fn: folioSettlementPage,
            title: 'Multi-Window Split-Folio & Express Checkout Settlement (Phase 3 #4)',
            crumbs: ['Hotel', 'Bookings', 'Split-Folio Settlement'],
        },
        '/preview/pos-unified': {
            fn: unifiedPosPage,
            title: 'Unified Restaurant, Bar & In-Room Dining POS Engine (Phase 3 #6)',
            crumbs: ['Restaurant & Bar', 'POS', 'Unified Split-Bill Engine'],
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
