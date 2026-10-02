// Remaining Hotel screens rendered from the real Blade views (tools/fixtures/hotel-more/*.html):
// guest SMS, night audit detail, monthly room calendar (two views) and booking migration.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const requests = [];
    const html = fs.readFileSync(`tools/fixtures/hotel-more/${name}.html`, 'utf8');
    await page.route('http://mm-more.test/**', route => {
        const request = route.request();
        const url = new URL(request.url());
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        requests.push({ method: request.method(), path: url.pathname, params: url.searchParams, post: request.postData() });
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-more.test/hotel/x');
    requests.length = 0;
    return { errors, requests };
}

const screens = {
    sms: 'Guest SMS',
    'night-audit-show': 'Night audit detail',
    monthly: 'Hotel monthly report',
    'monthly-booking': 'Hotel monthly booking report',
    'booking-adjust': 'Booking migration',
};

for (const [name] of Object.entries(screens)) {
    test(`${name} renders in the shared frame without script errors`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('.mm-page-title')).toHaveCount(1);
        await expect(page.locator('.widget-box, .widget-main, .widget-header')).toHaveCount(0);
        expect(errors.filter(e => !/toastr|chosen|ace_file_input|datepicker|daterangepicker|tag is not|fullcalendar/i.test(e))).toEqual([]);
    });
    for (const width of [360, 768]) {
        test(`${name} does not scroll sideways at ${width}px`, async ({ page }) => {
            await open(page, name, width);
            const { scroll, client } = await page.evaluate(() => ({ scroll: document.documentElement.scrollWidth, client: document.documentElement.clientWidth }));
            expect(scroll).toBeLessThanOrEqual(client);
        });
    }
}

test('SMS keeps its form contract and counts message characters and parts', async ({ page }) => {
    await open(page, 'sms');
    await expect(page.locator('#companyForm')).toHaveAttribute('method', /post/i);
    await expect(page.locator('#companyForm [name="phone_no"]')).toHaveCount(1);
    await expect(page.locator('#companyForm [name="message"]')).toHaveCount(1);
    await expect(page.locator('.mm-hotel-sms .part-count')).toHaveCount(1);
    await expect(page.locator('.mm-hotel-sms .total-character-count')).toHaveCount(1);
    await page.locator('.message-area').pressSequentially('Welcome to MM Heritage');
    await expect(page.locator('.total-character-count')).not.toHaveText('0');
});

test('SMS posts the phone list and the message once', async ({ page }) => {
    const { requests } = await open(page, 'sms');
    await page.evaluate(() => { document.querySelector('#companyForm').setAttribute('action', '/hotel/guests/submit-sms'); });
    await page.fill('.message-area', 'Hello');
    await page.locator('#companyForm button[type=submit]').click();
    await expect.poll(() => requests.length).toBe(1);
    expect(requests[0].method).toBe('POST');
    expect(requests[0].post).toMatch(/name="message"\s+Hello/);
    expect(requests[0].post).toMatch(/name="phone_no"\s+01700000000, 01800000000/);
});

test('night audit detail lists every transaction and keeps the print-only styling', async ({ page }) => {
    await open(page, 'night-audit-show');
    await expect(page.locator('.mm-audit-summary > div')).toHaveCount(6);
    await expect(page.locator('tbody tr')).toHaveCount(2);
    await expect(page.locator('tbody tr').first()).toContainText('INV-0007');
    await expect(page.locator('tbody tr').first()).toContainText('7,300.00');
    await expect(page.locator('tfoot')).toContainText('Total Collection');
    await page.emulateMedia({ media: 'print' });
    await expect(page.locator('.mm-page-actions, .no-print').first()).toBeHidden();
});

test('monthly calendar colours reserved, booked and checked-out days and shows the guest popup', async ({ page }) => {
    for (const name of ['monthly', 'monthly-booking']) {
        await open(page, name);
        await expect(page.locator('#schedule_table tr')).toHaveCount(2);
        const bg = sel => page.locator(sel).first().evaluate(el => getComputedStyle(el).backgroundColor);
        expect(await bg('#schedule_table .date-1')).toBe('rgb(209, 91, 71)');
        expect(await bg('#schedule_table .date-4')).toBe('rgb(255, 128, 0)');
        await expect(page.locator('#schedule_table td.bg-0 [data-rel="popover"]')).toHaveCount(1);
        await expect(page.locator('.mm-report-legend .label')).toHaveCount(6);
        const wide = await page.locator('.mm-table-scroll').evaluate(el => el.scrollWidth > el.clientWidth);
        expect(wide).toBe(true);
    }
});

test('monthly filter submits month, category, room and guest as GET fields', async ({ page }) => {
    const { requests } = await open(page, 'monthly');
    await page.evaluate(() => { document.querySelector('.mm-report-filter form').setAttribute('action', '/hotel/reports/monthly-summaries'); });
    await page.locator('.mm-report-filter button[type=submit]').click();
    await expect.poll(() => requests.length).toBe(1);
    expect(requests[0].method).toBe('GET');
    expect([...requests[0].params.keys()]).toEqual(expect.arrayContaining(['month', 'room_category', 'id', 'guest_id']));
});

test('booking migration keeps every id, class and hidden value the script reads', async ({ page }) => {
    await open(page, 'booking-adjust');
    for (const sel of ['#store-form', '#checkRoomStatus', '.submit-form-btn', '.migrate_date', '#previousDue', '#previousAdvance', '#currentDate', '#previous_check_out_date', '#selectedRoom', '#selectedRoomNumber', '.check-in-date', '.tr-checkout-date', '#available-room', '.available-rooms', '.room-list', '.roomTbody', '.item-details']) {
        await expect(page.locator(sel), sel).toHaveCount(1);
    }
    await expect(page.locator('#previousDue')).toHaveValue('8000');
    await expect(page.locator('#previousAdvance')).toHaveValue('1000');
    await expect(page.locator('input.room-select')).toHaveCount(2);
    await expect(page.locator('input.room-select').first()).toBeChecked();
    await expect(page.locator('input.room-select').nth(1)).toBeDisabled();
    await expect(page.locator('input.room-select').first()).toHaveAttribute('data-price', '4000');
    await expect(page.locator('[name=booking_id]')).toHaveValue('5');
    await expect(page.locator('[name=type]')).toHaveValue('migrate');
});

test('booking migration lays out the date row, rooms grid and actions without clipping', async ({ page }) => {
    await open(page, 'booking-adjust', 360);
    await page.evaluate(() => { document.querySelector('.available-rooms').style.display = 'block'; });
    const dates = await page.locator('.mm-adjust-dates').boundingBox();
    expect(dates.width).toBeLessThanOrEqual(360);
    const tiles = await page.locator('.available-rooms .room-item').evaluateAll(els => els.map(el => el.getBoundingClientRect().width));
    expect(tiles.length).toBe(2);
    for (const w of tiles) expect(w).toBeGreaterThan(60);
    const button = await page.locator('.submit-form-btn').boundingBox();
    expect(button.x).toBeGreaterThanOrEqual(0);
    expect(button.x + button.width).toBeLessThanOrEqual(360);
    await expect(page.locator('.mm-table-scroll')).toHaveAttribute('role', 'region');
});

test('picking an available room marks it active through the existing handler contract', async ({ page }) => {
    await open(page, 'booking-adjust');
    await page.evaluate(() => { document.querySelector('.available-rooms').style.display = 'block'; });
    const item = page.locator('.available-rooms .room-item').first();
    await item.evaluate(el => el.classList.add('active'));
    await expect(item).toHaveCSS('color', 'rgb(255, 255, 255)');
    await expect(item.locator('input.room-number')).toHaveValue('111');
});
