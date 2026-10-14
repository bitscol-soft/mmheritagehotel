const { test, expect } = require('@playwright/test');
const { spawn } = require('child_process');
const http = require('http');

let srv;
const PORT = 4397;

function waitServer() {
    return new Promise((resolve, reject) => {
        const start = Date.now();
        const tick = () => {
            http.get(`http://127.0.0.1:${PORT}/preview/wiki`, res => {
                res.resume();
                if (res.statusCode === 200) resolve();
                else if (Date.now() - start > 8000) reject(new Error('Server timeout'));
                else setTimeout(tick, 120);
            }).on('error', () => {
                if (Date.now() - start > 8000) reject(new Error('Server start error'));
                else setTimeout(tick, 120);
            });
        };
        tick();
    });
}

test.beforeAll(async () => {
    srv = spawn(process.execPath, ['tools/preview-server.cjs'], {
        env: { ...process.env, PORT: String(PORT), HOST: '127.0.0.1' },
        stdio: 'ignore',
    });
    await waitServer();
});

test.afterAll(async () => {
    if (srv) srv.kill();
});

test('bilingual wiki & help center (/preview/wiki) toggles EN/BN and filters topics', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 900 });
    await page.goto(`http://127.0.0.1:${PORT}/preview/wiki`);
    await expect(page.locator('.mm-page-title').first()).toContainText('User Documentation, Wiki & Help');
    await expect(page.locator('.mm-page-title').first()).toContainText('ব্যবহারকারী ম্যানুয়াল ও সহায়িকা');
    await expect(page.locator('.wiki-card')).toHaveCount(8);

    await page.locator('[data-wiki-lang="bn"]').click();
    await expect(page.locator('.wiki-card .wiki-lang-en').first()).toBeHidden();
    await expect(page.locator('.wiki-card .wiki-lang-bn').first()).toBeVisible();

    await page.locator('[data-wiki-lang="both"]').click();
    await page.fill('#mm-wiki-search', 'পিওএস');
    const visibleCount = await page.locator('.wiki-card:visible').count();
    expect(visibleCount).toBeGreaterThan(0);
});

test('printable bilingual SOP cheat sheet (/preview/sop-print) hides chrome on print media', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 900 });
    await page.goto(`http://127.0.0.1:${PORT}/preview/sop-print`);
    await expect(page.locator('.mm-print-sheet-a4')).toBeVisible();
    await page.emulateMedia({ media: 'print' });
    await expect(page.locator('header.mm-page-head.mm-no-print')).toBeHidden();
    await expect(page.locator('.mm-print-sheet-a4')).toBeVisible();
});

for (const [route, heading] of [
    ['/preview/onboarding', 'Role-Based Staff Onboarding'],
    ['/preview/tape-chart', '7-Day Reservation Tape Chart'],
    ['/preview/rooms', 'Hotel Rooms & Suite Inventory'],
    ['/preview/guests', 'Registered Guests & VIP CRM Directory'],
    ['/preview/bookings', 'Room Bookings & Active Reservations'],
    ['/preview/housekeeping-board', 'Housekeeping & Maintenance SLA Board'],
    ['/preview/concierge-requests', 'Concierge, Guest Requests & Lost-and-Found'],
    ['/preview/channel-manager', 'OTA Channel Manager & iCal Sync'],
    ['/preview/rate-plans', 'Seasonal Rate Plans & Promo Engine'],
    ['/preview/kiosk-checkin', 'Self-Service Kiosk Check-In & Digital Registration Card'],
    ['/preview/room-move', 'In-Stay Room Move, Upgrade & Folio Transfer'],
    ['/preview/guest-crm', 'Guest & Corporate CRM 360° Directory'],
    ['/preview/minibar-folio', 'In-Room Minibar & Asset Consumption Auto-Post to Folio'],
    ['/preview/city-ledger', 'Corporate AR / City Ledger & Credit Aging Report'],
    ['/preview/manager-bi', 'Manager BI: Occupancy, ADR, RevPAR & Channel Mix'],
    ['/preview/booking-drawers', 'Front-Desk & POS Slide-Over App Drawers'],
]) {
    test(`${route} renders shell frame, KPI/table panels, and does not overflow at desktop or phone`, async ({ page }) => {
        await page.setViewportSize({ width: 1280, height: 900 });
        await page.goto(`http://127.0.0.1:${PORT}${route}`);
        await expect(page.locator('.mm-page-title').first()).toContainText(heading);
        await expect(page.locator('.mm-panel').first()).toBeVisible();
        for (const width of [1280, 390]) {
            await page.setViewportSize({ width, height: 900 });
            const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            expect(over, `${route} overflow at ${width}`).toBeLessThanOrEqual(1);
        }
    });
}

test('20-room & 20-guest sample dataset renders across Tape Chart, Rooms, Guests, Bookings, Housekeeping, Concierge & Booking Wizard', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 900 });

    await page.goto(`http://127.0.0.1:${PORT}/preview/tape-chart`);
    await expect(page.locator('.tape-room-row')).toHaveCount(20);

    await page.goto(`http://127.0.0.1:${PORT}/preview/rooms`);
    await expect(page.locator('.rm-dir-row')).toHaveCount(20);
    await page.locator('[data-rm-floor="1"]').click();
    await expect(page.locator('.rm-dir-row:visible')).toHaveCount(6);
    await page.locator('[data-rm-floor="all"]').click();
    await expect(page.locator('.rm-dir-row:visible')).toHaveCount(20);

    await page.goto(`http://127.0.0.1:${PORT}/preview/guests`);
    await expect(page.locator('.gst-dir-row')).toHaveCount(20);
    await page.fill('#mm-gst-search', 'UNICEF');
    await expect(page.locator('.gst-dir-row:visible')).toHaveCount(1);

    await page.goto(`http://127.0.0.1:${PORT}/preview/bookings`);
    await expect(page.locator('.bk-dir-row')).toHaveCount(20);

    await page.goto(`http://127.0.0.1:${PORT}/preview/housekeeping-board`);
    await expect(page.locator('table.mm-data-table tbody tr')).toHaveCount(18);

    await page.goto(`http://127.0.0.1:${PORT}/preview/concierge-requests`);
    await expect(page.locator('table.mm-data-table tbody tr')).toHaveCount(16);

    await page.goto(`http://127.0.0.1:${PORT}/preview/booking-next`);
    await expect(page.locator('#wiz-floorplan-grid [data-floor]')).toHaveCount(18);
    await expect(page.locator('#wiz-guest-preset option')).toHaveCount(18);
    await expect(page.locator('#wiz-floorplan-grid')).toHaveClass(/is-scrollable/);
    await expect(page.locator('#wiz-room-list')).toHaveClass(/is-scrollable/);

    // Verify Step 1 4-dropdown room filter bar (Status, Room Type, Bed Type, Floor)
    await expect(page.locator('#wiz-filter-status')).toBeVisible();
    await expect(page.locator('#wiz-filter-type')).toBeVisible();
    await expect(page.locator('#wiz-filter-bed')).toBeVisible();
    await expect(page.locator('#wiz-filter-floor')).toBeVisible();
    await page.selectOption('#wiz-filter-status', 'Occupied');
    await expect(page.locator('#wiz-filter-count')).toContainText('Showing 6 of 18');
    await page.selectOption('#wiz-filter-status', 'HouseKeeping');
    await expect(page.locator('#wiz-filter-count')).toContainText('Showing 2 of 18');
    await page.selectOption('#wiz-filter-status', 'Hold');
    await expect(page.locator('#wiz-filter-count')).toContainText('Showing 2 of 18');
    await page.selectOption('#wiz-filter-status', 'Booked');
    await expect(page.locator('#wiz-filter-count')).toContainText('Showing 2 of 18');
    await page.selectOption('#wiz-filter-status', 'Available');
    await expect(page.locator('#wiz-filter-count')).toContainText('Showing 6 of 18');
    await page.selectOption('#wiz-filter-floor', '2');
    await expect(page.locator('#wiz-filter-count')).toContainText('Showing 2 of 18');
    await page.locator('#wiz-filter-reset').click();
    await expect(page.locator('#wiz-filter-count')).toContainText('Showing 18 of 18');

    await page.locator('[data-wiz-range-preset="3"]').click();
    await expect(page.locator('#wiz-nights')).toHaveValue('3');
    await expect(page.locator('#wiz-coguests-tbody .wiz-coguest-row')).toHaveCount(2);
    await page.locator('#wiz-add-coguest-btn').click();
    await expect(page.locator('#wiz-coguests-tbody .wiz-coguest-row')).toHaveCount(3);
    await page.fill('#wiz-advance', '500');
    await expect(page.locator('#wiz-advance-error')).toBeVisible();
    await page.locator('[data-wiz-adv-pct="50"]').click();
    await expect(page.locator('#wiz-advance-error')).toBeHidden();
    await expect(page.locator('#wiz-advance-ok')).toBeVisible();

    // Verify Step 3 Whole Booking Draft Summary Preview & Print PDF option before confirming
    await page.locator('#wiz-btn-draft-preview').click();
    await expect(page.locator('#wiz-draft-summary-modal')).toBeVisible();
    const drawerWidth = await page.locator('#wiz-draft-summary-modal .modal-dialog').evaluate(el => el.getBoundingClientRect().width);
    expect(drawerWidth).toBeGreaterThanOrEqual(1000);
    await expect(page.locator('#wiz-draft-page-1')).toBeVisible();
    await expect(page.locator('#wiz-draft-page-2')).toBeVisible();
    await expect(page.locator('.mm-wiz-pdf-hero')).toBeVisible();
    await expect(page.locator('.mm-wiz-pdf-crest')).toContainText('M&M');
    await expect(page.locator('#wiz-btn-download-draft-pdf')).toBeVisible();
    await expect(page.locator('#wiz-btn-print-draft-top')).toBeVisible();
    await expect(page.locator('#draft-nights')).toContainText('3 Nights · 4 Guests');
    await expect(page.locator('#draft-rooms-tbody tr')).toHaveCount(2);
    await expect(page.locator('#draft-guests-tbody tr')).toHaveCount(3);
    const screenFit = await page.evaluate(() => {
        const p1 = document.getElementById('wiz-draft-page-1');
        const gt = document.getElementById('draft-guests-table');
        const rt = document.getElementById('draft-rooms-table');
        return {
            p1W: p1.clientWidth,
            gtW: gt.scrollWidth,
            rtW: rt.scrollWidth,
        };
    });
    expect(screenFit.gtW).toBeLessThanOrEqual(screenFit.p1W + 2);
    expect(screenFit.rtW).toBeLessThanOrEqual(screenFit.p1W + 2);

    await page.evaluate(() => { window.print = () => { window.__draftPrinted = true; }; });
    await page.locator('#wiz-btn-print-draft').click();
    await expect(page.locator('#wiz-draft-print-banner')).toBeVisible();

    await page.emulateMedia({ media: 'print' });
    await expect(page.locator('#wiz-draft-sheet')).toBeVisible();
    await expect(page.locator('#wiz-draft-page-1')).toBeVisible();
    await expect(page.locator('#wiz-draft-page-2')).toBeVisible();
    await expect(page.locator('#wiz-draft-summary-modal .modal-footer')).toBeHidden();
    await expect(page.locator('#wiz-pdf-toolbar')).toBeHidden();
    const printFit = await page.evaluate(() => {
        const p1 = document.getElementById('wiz-draft-page-1');
        const gt = document.getElementById('draft-guests-table');
        const rt = document.getElementById('draft-rooms-table');
        const cs1 = getComputedStyle(p1);
        return {
            p1W: p1.clientWidth,
            gtW: gt.scrollWidth,
            rtW: rt.scrollWidth,
            breakAfter: cs1.breakAfter || cs1.pageBreakAfter,
        };
    });
    expect(printFit.gtW).toBeLessThanOrEqual(printFit.p1W + 2);
    expect(printFit.rtW).toBeLessThanOrEqual(printFit.p1W + 2);
    expect(['page', 'always']).toContain(printFit.breakAfter);
    await page.emulateMedia({ media: 'screen' });

    await page.goto(`http://127.0.0.1:${PORT}/preview/draft-booking-pdf`);
    await expect(page.locator('#wiz-draft-sheet')).toBeVisible();
    await expect(page.locator('#wiz-draft-page-1')).toBeVisible();
    await expect(page.locator('#wiz-draft-page-2')).toBeVisible();
    await expect(page.locator('.mm-wiz-pdf-hero')).toBeVisible();

    // Verify Complete Plan B Command Center (F1-F34) & all 22 new Plan B workspaces
    await page.goto(`http://127.0.0.1:${PORT}/preview/plan-b`);
    await expect(page.locator('.planb-card')).toHaveCount(34);
    await page.locator('[data-pb-pillar="4. Finance & Compliance"]').click();
    await expect(page.locator('.planb-card:visible')).toHaveCount(6);
    await page.locator('[data-pb-pillar="All"]').click();
    await expect(page.locator('.planb-card:visible')).toHaveCount(34);
    await page.fill('#planb-search', 'F17');
    await expect(page.locator('.planb-card:visible')).toHaveCount(1);

    const planBNewRoutes = [
        '/preview/payment-gateway',
        '/preview/dynamic-pricing',
        '/preview/upsell-marketplace',
        '/preview/inventory-guards',
        '/preview/group-contracts',
        '/preview/maintenance-tickets',
        '/preview/shift-roster',
        '/preview/e-invoicing',
        '/preview/tax-rules',
        '/preview/night-audit-hardening',
        '/preview/gateway-reconciliation',
        '/preview/multi-currency-fx',
        '/preview/notification-backbone',
        '/preview/guest-portal',
        '/preview/loyalty-coupons',
        '/preview/guest-reviews',
        '/preview/public-seo-cms',
        '/preview/mobile-staff-app',
        '/preview/api-webhooks',
        '/preview/multi-property',
        '/preview/admin-i18n',
        '/preview/audit-compliance',
        '/preview/import-export-hub',
    ];
    for (const r of planBNewRoutes) {
        const resp = await page.goto(`http://127.0.0.1:${PORT}${r}`);
        expect(resp && resp.status()).toBe(200);
        await expect(page.locator('.mm-table-kpi-grid .mm-table-kpi-card').first()).toBeVisible();
    }

    // Verify Room Settings, Pricing, Amenities & Rules Control Center (/preview/room-settings)
    await page.goto(`http://127.0.0.1:${PORT}/preview/room-settings`);
    await expect(page.locator('#rmset-cat-tbody .rmset-cat-row')).toHaveCount(6);
    await expect(page.locator('#rmset-amenity-count-badge')).toContainText('10 Active Amenities');
    await page.fill('#rmset-new-amenity-name', 'Private Plunge Pool');
    await page.locator('#rmset-btn-add-amenity').click();
    await expect(page.locator('#rmset-amenity-count-badge')).toContainText('11 Active Amenities');
    await page.fill('#rmset-rule-deposit', '30');
    await expect(page.locator('#rmset-kpi-rules')).toContainText('30% Min Deposit');
    await page.locator('#rmset-btn-save-prices').click();
    await expect(page.locator('#rmset-save-banner')).toBeVisible();

    // Verify Plan A Phase 3 Core Workflows: Split-Folio Checkout (/preview/folio-settlement), Unified POS (/preview/pos-unified) & Plan A Hub (/preview/plan-a)
    await page.goto(`http://127.0.0.1:${PORT}/preview/folio-settlement`);
    await expect(page.locator('#folio-lines-tbody .folio-line-row')).toHaveCount(5);
    await page.locator('.folio-toggle-win').first().click();
    await expect(page.locator('#folio-win-a-badge')).toContainText('৳ 25,000');
    await page.locator('#folio-btn-settle').click();
    await expect(page.locator('#folio-settle-banner')).toBeVisible();

    await page.goto(`http://127.0.0.1:${PORT}/preview/pos-unified`);
    await expect(page.locator('#upos-ticket-tbody .upos-row')).toHaveCount(3);
    await page.locator('.upos-menu-btn').first().click();
    await expect(page.locator('#upos-ticket-tbody .upos-row')).toHaveCount(4);
    await page.selectOption('#upos-split-select', '2');
    await expect(page.locator('#upos-kpi-split')).toContainText('Split 2 Ways');

    await page.goto(`http://127.0.0.1:${PORT}/preview/plan-a`);
    await expect(page.locator('table.mm-data-table tbody tr')).toHaveCount(9);
});

test('14-module sidebar role switcher, top sub-menu bar & global command drawers work interactively', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 900 });

    // Verify Daily Manager Flash Report & Shift Roster (/preview/daily-flash-report)
    await page.goto(`http://127.0.0.1:${PORT}/preview/daily-flash-report`);
    await expect(page.locator('.mm-page-title').first()).toContainText('Daily Manager Flash Report');
    await expect(page.locator('.mm-panel').first()).toBeVisible();

    // Verify Sidebar Role Switcher (#mm-role-switcher) & Clean Top Sub-Menu Bar (#mm-top-subnav) with curtain pull-down & pin-to-top
    await page.goto(`http://127.0.0.1:${PORT}/preview/dashboard`);
    await expect(page.locator('#dash-ops-strip')).toHaveCount(0);
    await expect(page.locator('#mm-top-favs-bar')).toHaveCount(0);
    await expect(page.locator('#mm-sidebar-collapse-bar')).toHaveCount(0);
    await expect(page.locator('#mm-role-switcher')).toBeVisible();
    await page.locator('#mm-role-switcher [data-role-filter="pos"]').click();
    await expect(page.locator('#mm-primary-menu > li:not(.mm-nav-section):visible')).toHaveCount(5);
    await page.locator('#mm-role-switcher [data-role-filter="all"]').click();
    await expect(page.locator('#mm-primary-menu > li:not(.mm-nav-section):visible')).toHaveCount(14);

    await expect(page.locator('#mm-top-subnav')).toHaveClass(/is-sticky/);
    await expect(page.locator('#mm-top-subnav')).toHaveClass(/is-slid-up/);
    await expect(page.locator('#mm-top-curtain-btn')).toHaveAttribute('aria-expanded', 'false');
    await page.locator('#mm-top-curtain-btn').click();
    await expect(page.locator('#mm-top-subnav')).not.toHaveClass(/is-slid-up/);
    await expect(page.locator('#mm-top-curtain-btn')).toHaveAttribute('aria-expanded', 'true');

    await expect(page.locator('#mm-top-pin-btn')).toHaveAttribute('aria-pressed', 'true');
    await page.locator('#mm-top-pin-btn').click();
    await expect(page.locator('#mm-top-subnav')).not.toHaveClass(/is-sticky/);
    await page.locator('#mm-top-pin-btn').click();
    await expect(page.locator('#mm-top-subnav')).toHaveClass(/is-sticky/);

    await page.locator('#mm-top-more-btn').click();
    await expect(page.locator('#mm-top-more-panel')).toBeVisible();
    await page.locator('#mm-top-more-btn').click();
    await page.locator('#mm-top-curtain-btn').click();
    await expect(page.locator('#mm-top-subnav')).toHaveClass(/is-slid-up/);

    // Verify Redesigned New Banquet Booking (/preview/bq-booking-create)
    await page.goto(`http://127.0.0.1:${PORT}/preview/bq-booking-create`);
    await expect(page.locator('.mm-page-title').first()).toContainText('New banquet booking');
    await expect(page.locator('#bq-venue-cards .mm-bq-venue-card')).toHaveCount(4);
    await expect(page.locator('#bq-halls-tbody .bq-calc-row')).toHaveCount(2);
    await page.locator('#addrow').click();
    await expect(page.locator('#bq-halls-tbody .bq-calc-row')).toHaveCount(3);
    await expect(page.locator('#bq-kpi-venues')).toContainText('3 Halls');
    await page.locator('#bq-btn-book').click();
    await expect(page.locator('#bq-confirm-banner')).toBeVisible();

    // Verify Redesigned Booking Migration (/preview/hotel-booking-adjust & /preview/booking-migration)
    await page.goto(`http://127.0.0.1:${PORT}/preview/hotel-booking-adjust`);
    await expect(page.locator('.mm-page-title').first()).toContainText('Booking migration');
    await expect(page.locator('.available-rooms .room-item')).toHaveCount(6);
    await page.locator('.available-rooms .room-item').nth(2).click();
    await expect(page.locator('#mig-kpi-route')).toContainText('#101 → #202');
    await page.locator('#mig-submit-btn').click();
    await expect(page.locator('#mig-confirm-banner')).toBeVisible();
});

test('redesigned Express Quick-Create Drawer (Alt+Q) & Website Settings (/preview/web-settings) work interactively', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 800 });
    await page.goto(`http://127.0.0.1:${PORT}/preview/web-settings`);

    // Verify Redesigned Website Settings Control Center (/preview/web-settings)
    await expect(page.locator('.mm-page-title').first()).toContainText('Website settings');
    await expect(page.locator('.mm-table-kpi-grid .mm-table-kpi-card')).toHaveCount(4);
    await page.locator('#website_slogan').fill('Diplomatic Luxury Suites & Banquet Halls in Gulshan Dhaka');
    await expect(page.locator('#webset-serp-title')).toContainText('Diplomatic Luxury Suites & Banquet Halls in Gulshan Dhaka');
    await page.locator('#webset-btn-save').click();
    await expect(page.locator('#webset-save-banner')).toBeVisible();

    // Verify Redesigned Express Walk-In, 80mm KOT, GL Voucher & Banquet Quick-Create Drawer (Alt+Q)
    await page.locator('#mm-top-curtain-btn').click();
    await page.locator('#mm-express-create-btn').click();
    await expect(page.locator('#mm-express-drawer')).toBeVisible();
    await expect(page.locator('#mm-express-drawer .mm-drawer-kpi-grid .mm-table-kpi-card')).toHaveCount(4);
    await page.locator('#exp-suite-pills .exp-suite-pill').nth(2).click();
    await expect(page.locator('#exp-walkin-est-text')).toContainText('19,580');
    await page.locator('#mm-express-drawer [data-express-tab="pos"]').click();
    await page.locator('#exp-kot-chips .exp-kot-chip').nth(1).click();
    await expect(page.locator('#exp-kot-preview-text')).toContainText('Grilled Norwegian Salmon');
    await page.locator('#mm-express-drawer [data-express-tab="banquet"]').click();
    await page.locator('#exp-bq-submit').click();
    await expect(page.locator('#mm-express-log-count')).toContainText('4');
    await expect(page.locator('#mm-express-confirm-banner')).toBeVisible();
});

test('redesigned Website CMS, Payment Collection, KDS Kitchen Board & Trial Balance work interactively', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 800 });

    // 1. Homepage Hero Banners (/preview/web-banner-index)
    await page.goto(`http://127.0.0.1:${PORT}/preview/web-banner-index`);
    await expect(page.locator('.mm-page-title').first()).toContainText('Homepage banners');
    await page.locator('#wban-slide-pills .wban-slide-pill').nth(1).click();
    await expect(page.locator('#wban-prev-title')).toContainText('Grand Ballroom & Royal Banquet Halls');
    await page.locator('#wban-add-btn').click();
    await expect(page.locator('#wban-kpi-active')).toContainText('5 Slides');
    await expect(page.locator('#wban-confirm-banner')).toBeVisible();

    // 2. Suite & Property Gallery (/preview/web-gallery-index)
    await page.goto(`http://127.0.0.1:${PORT}/preview/web-gallery-index`);
    await expect(page.locator('#wgal-tbody tr')).toHaveCount(4);
    await page.locator('#wgal-filter-pills [data-gal-cat="suites"]').click();
    await expect(page.locator('#wgal-tbody tr:visible')).toHaveCount(1);

    // 3. Website About Section (/preview/web-about)
    await page.goto(`http://127.0.0.1:${PORT}/preview/web-about`);
    await page.locator('#heading_title').fill('M&M Heritage Diplomatic Suites Dhaka');
    await expect(page.locator('#wabout-prev-title')).toContainText('M&M Heritage Diplomatic Suites Dhaka');
    await page.locator('#wabout-save-btn').click();
    await expect(page.locator('#wabout-save-banner')).toBeVisible();

    // 4. Multi-Tender Folio Payment Collection (/preview/payment-collection)
    await page.goto(`http://127.0.0.1:${PORT}/preview/payment-collection`);
    await page.locator('#pc-guest-pills .pc-guest-pill').nth(1).click();
    await expect(page.locator('#pc-kpi-guest')).toContainText('Amb. Sarah Jenkins');
    await page.locator('#check-full-payment').click();
    await expect(page.locator('#pc-kpi-due')).toContainText('0.00');
    await page.locator('#pc-submit-btn').click();
    await expect(page.locator('#pc-confirm-banner')).toBeVisible();

    // 5. Live Kitchen Display System (KDS) Board (/preview/rst-kitchen-board)
    await page.goto(`http://127.0.0.1:${PORT}/preview/rst-kitchen-board`);
    await page.locator('.mm-board-card[data-order-id="R-0101"] .kds-advance-btn').click();
    await expect(page.locator('#mm-kds-kpi-cooking')).toContainText('2');
    await page.locator('#kds-bump-ready-btn').click();
    await expect(page.locator('#mm-kds-kpi-ready')).toContainText('0');
    await expect(page.locator('#kds-confirm-banner')).toBeVisible();

    // 6. Double-Entry Trial Balance (/preview/acc-trial-balance)
    await page.goto(`http://127.0.0.1:${PORT}/preview/acc-trial-balance`);
    await page.locator('#tb-class-pills [data-tb-group="Asset"]').click();
    await expect(page.locator('#tb-kpi-accounts')).toContainText('3 Accounts');
});

test('Wave 2 High-Traffic Operational Screens (Checkout, Night Audit, POS, Vouchers & Statements) work interactively', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 800 });

    // 1. Express Checkout Folio & Split Bill (/preview/checkout)
    await page.goto(`http://127.0.0.1:${PORT}/preview/checkout`);
    await expect(page.locator('#co-kpi-due')).toContainText('6,240');
    await page.locator('#co-split-pills [data-split-due="5000"]').click();
    await expect(page.locator('#co-kpi-due')).toContainText('5,000');
    await page.locator('#check-full-payment').click();
    await page.locator('#co-submit-btn').click();
    await expect(page.locator('#co-confirm-banner')).toBeVisible();

    // 2. Generate Night Audit (/preview/night-audit-generate)
    await page.goto(`http://127.0.0.1:${PORT}/preview/night-audit-generate`);
    await expect(page.locator('#na-kpi-date')).toContainText('2026-10-14');
    await page.locator('#na-run-btn').click();
    await expect(page.locator('#na-kpi-date')).toContainText('2026-10-15');
    await expect(page.locator('#na-confirm-banner')).toBeVisible();

    // 3. Restaurant & Bar POS Terminals (/preview/rst-pos-create & /preview/bar-pos-create) + Focused Fullscreen Mode
    await page.goto(`http://127.0.0.1:${PORT}/preview/rst-pos-create`);
    await expect(page.locator('#mm-pos-table-grid [data-pos-table-card]')).toHaveCount(6);
    await expect(page.locator('#invoice-list tr')).toHaveCount(2);
    await page.locator('.rpos-quick-item').first().click();
    await expect(page.locator('#rpos-kpi-items')).toContainText('3 Items');
    await page.locator('[data-pos-modifier]').first().click();
    await expect(page.locator('#pos-active-modifiers')).toContainText('No Nuts');
    await page.locator('#rpos-fire-kot-btn').click();
    await expect(page.locator('#rpos-confirm-banner')).toBeVisible();
    await page.locator('#mm-focused-fullscreen-btn').click();
    await expect(page.locator('body')).toHaveClass(/mm-focused-fullscreen/);
    await expect(page.locator('#mm-fullscreen-hud')).toBeVisible();
    await page.locator('#mm-fullscreen-exit-btn').click();
    await expect(page.locator('body')).not.toHaveClass(/mm-focused-fullscreen/);

    await page.goto(`http://127.0.0.1:${PORT}/preview/bar-pos-create`);
    await expect(page.locator('#mm-pos-table-grid [data-pos-table-card]')).toHaveCount(6);
    await page.locator('#bpos-happy-hour-btn').click();
    await expect(page.locator('#bpos-kpi-total')).toContainText('1,530');
    await page.locator('#mm-focused-fullscreen-btn').click();
    await expect(page.locator('body')).toHaveClass(/mm-focused-fullscreen/);
    await page.locator('#mm-fullscreen-exit-btn').click();

    await page.goto(`http://127.0.0.1:${PORT}/preview/booking-next`);
    await page.locator('#mm-focused-fullscreen-btn').click();
    await expect(page.locator('body')).toHaveClass(/mm-focused-fullscreen/);
    await expect(page.locator('#mm-fullscreen-hud')).toBeVisible();
    await page.locator('#mm-fullscreen-exit-btn').click();
    await expect(page.locator('body')).not.toHaveClass(/mm-focused-fullscreen/);

    // 4. Receive Vouchers, Balance Sheet & Income Statement
    await page.goto(`http://127.0.0.1:${PORT}/preview/acc-receives`);
    await page.locator('#rcv-approve-all-btn').click();
    await expect(page.locator('#rcv-kpi-approved')).toContainText('4 Approved');

    await page.goto(`http://127.0.0.1:${PORT}/preview/acc-report-balance-sheet`);
    await expect(page.locator('#bs-kpi-proof')).toContainText('Balanced');
    await page.locator('#bs-filter-pills [data-bs-sec="Asset"]').click();
    await expect(page.locator('#bs-tbody tr:visible')).toHaveCount(3);

    await page.goto(`http://127.0.0.1:${PORT}/preview/acc-income-statement`);
    await expect(page.locator('#is-kpi-net')).toContainText('825,000');
});

test('General Store & Restaurant Inventory Procurement Screens (POs, GRN, Stock-in-Hand & Kitchen Inventory) work interactively', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 800 });

    // 1. General Store Purchase Orders (/preview/gs-purchases)
    await page.goto(`http://127.0.0.1:${PORT}/preview/gs-purchases`);
    await expect(page.locator('#gspo-kpi-pending')).toContainText('1 PO');
    await page.locator('#gspo-approve-btn').click();
    await expect(page.locator('#gspo-kpi-approved')).toContainText('4 POs');
    await expect(page.locator('#gspo-confirm-banner')).toBeVisible();

    // 2. General Store GRN List (/preview/gs-grn-list)
    await page.goto(`http://127.0.0.1:${PORT}/preview/gs-grn-list`);
    await expect(page.locator('.mm-page-title').first()).toContainText('GRN list');

    // 3. General Store Stock in Hand (/preview/gs-stock-in-hand)
    await page.goto(`http://127.0.0.1:${PORT}/preview/gs-stock-in-hand`);
    await page.locator('#gssih-filter-pills [data-stock-filter="low"]').click();
    await expect(page.locator('#gssih-kpi-skus')).toContainText('2 SKUs');
    await page.locator('#gssih-reorder-btn').click();
    await expect(page.locator('#gssih-confirm-banner')).toBeVisible();

    // 4. Restaurant Kitchen Product Inventory (/preview/rsi-inventory-report)
    await page.goto(`http://127.0.0.1:${PORT}/preview/rsi-inventory-report`);
    await page.locator('#rsi-filter-pills [data-rsi-cat="Chilled"]').click();
    await expect(page.locator('#rsi-kpi-skus')).toContainText('2 SKUs');
    await page.locator('#rsi-req-btn').click();
    await expect(page.locator('#rsi-confirm-banner')).toBeVisible();
});



