// Hotel report screens rendered from the real Blade views (tools/fixtures/hotel-reports/*.html).
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const screens = {
    'all-reports': ['Over all reports', ['invoice_no', 'from_date', 'to_date']],
    'cash-flow': ['Cash flow', ['invoice_no', 'from_date', 'to_date', 'from_time', 'to_time']],
    'expected-arrival': ['Expected arrival list', ['date']],
    'expected-departure': ['Expected departure list', ['date']],
    'in-house-guest': ['In-house guest list', ['to']],
    'night-closing': ['Booking night audit report', ['from_date', 'to_date']],
    'room-logs': ['Room logs', ['room_id', 'from_date', 'to_date']],
    'services': ['Service report', ['from_date', 'to_date']],
    'today-activities': ['Today report', ['date']],
    'today-check-in': ['Today check in', []],
    'today-check-out': ['Today check out', []],
    'today-in-house': ['Today in-house guest list', []],
    'vat-report-day': ['Daily VAT report', ['from_date', 'to_date']],
    'vat-report-monthly': ['Monthly VAT report', ['from_date', 'to_date']],
};

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const requests = [];
    const html = fs.readFileSync(`tools/fixtures/hotel-reports/${name}.html`, 'utf8');
    await page.route('http://mm-report.test/**', route => {
        const request = route.request();
        const url = new URL(request.url());
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        requests.push({ method: request.method(), path: url.pathname, params: url.searchParams });
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-report.test/hotel/reports/x');
    requests.length = 0;
    return { errors, requests };
}

for (const [name, [title, fields]] of Object.entries(screens)) {
    test(`${name} renders in the shared frame without script errors`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('h1')).toHaveText(title);
        await expect(page.locator('.mm-report')).toHaveCount(1);
        await expect(page.locator('.widget-box, .widget-main')).toHaveCount(0);
        for (const field of fields) await expect(page.locator(`.mm-report-filter [name="${field}"]`)).toHaveCount(1);
        expect(errors).toEqual([]);
    });
    for (const width of [360, 768]) {
        test(`${name} does not scroll sideways at ${width}px`, async ({ page }) => {
            await open(page, name, width);
            const { scroll, client } = await page.evaluate(() => ({ scroll: document.documentElement.scrollWidth, client: document.documentElement.clientWidth }));
            expect(scroll).toBeLessThanOrEqual(client);
        });
    }
}

test('the filter bar sits above the results and the results scroll inside their own region', async ({ page }) => {
    await open(page, 'expected-arrival', 360);
    const filter = await page.locator('.mm-report-filter').boundingBox();
    const results = await page.locator('.mm-table-scroll').boundingBox();
    expect(filter.y + filter.height).toBeLessThanOrEqual(results.y);
    await expect(page.locator('.mm-table-scroll')).toHaveAttribute('role', 'region');
    const wide = await page.locator('.mm-table-scroll').evaluate(el => el.scrollWidth > el.clientWidth);
    expect(wide).toBe(true);
});

test('the search form submits the same GET fields as before', async ({ page }) => {
    const { requests } = await open(page, 'cash-flow');
    await page.fill('[name="invoice_no"]', '0103');
    await page.locator('.mm-report-filter button[type=submit]').click();
    await expect.poll(() => requests.length).toBeGreaterThan(0);
    const request = requests[0];
    expect(request.method).toBe('GET');
    expect([...request.params.keys()].sort()).toEqual(['from_date', 'from_time', 'invoice_no', 'to_date', 'to_time']);
    expect(request.params.get('invoice_no')).toBe('0103');
});

test('room logs keeps the room select and posts the chosen room', async ({ page }) => {
    const { requests } = await open(page, 'room-logs');
    await page.locator('select[name="room_id"]').selectOption('1');
    await page.locator('.mm-report-filter button[type=submit]').click();
    await expect.poll(() => requests.length).toBeGreaterThan(0);
    expect(requests[0].params.get('room_id')).toBe('1');
});

test('reset link clears the query and the export links are kept', async ({ page }) => {
    await open(page, 'expected-arrival');
    await expect(page.getByRole('link', { name: 'Reset' })).toHaveAttribute('href', /\/hotel\/reports\/expected-arrival$/);
    await expect(page.locator('a[href*="export_type=excel"]')).toHaveCount(1);
    await expect(page.locator('a[href*="export_type=pdf"]')).toHaveCount(1);
    await expect(page.locator('ul.pagination')).toHaveCount(1);
});

test('today report keeps its readonly totals and hidden transaction ids', async ({ page }) => {
    await open(page, 'today-activities');
    await expect(page.locator('input[name="total_check_in"]')).toHaveValue('2');
    await expect(page.locator('input[name="total_check_in"]')).toHaveJSProperty('readOnly', true);
    await expect(page.locator('input[name="transaction_ids[]"]')).toHaveCount(1);
    await expect(page.locator('.header-input').first()).toHaveCSS('border-top-width', '0px');
});

test('night audit report keeps the day detail modals and filters only when audits exist', async ({ page }) => {
    await open(page, 'night-closing');
    await expect(page.locator('#audit-view-details7.modal')).toHaveCount(1);
    await expect(page.locator('.mm-report-filter [name="from_date"]')).toHaveValue('2026-09-30');
});

test('empty results keep the standard no-records row', async ({ page }) => {
    await open(page, 'vat-report-monthly');
    await expect(page.getByText('No records found !')).toBeVisible();
});
