// Checkout and payment screen: the real rendered view (tools/fixtures/booking-checkout.html) with its real calculation script.
// Sample: two rooms for 2 nights (base 4000/night, 5% service, 10% VAT), 3000 already paid, plus a 1500 restaurant charge.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const fixture = fs.readFileSync('tools/fixtures/booking-checkout.html', 'utf8');

async function open(page, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.route('http://mm-checkout.test/**', route => {
        const pathname = new URL(route.request().url()).pathname;
        if (pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        if (route.request().method() === 'POST') return route.fulfill({ contentType: 'text/plain', body: route.request().postData() || '' });
        return route.fulfill({ contentType: 'text/html', body: fixture });
    });
    await page.goto('http://mm-checkout.test/hotel/checkout/7');
    return errors;
}
const text = (page, selector) => page.locator(selector).first().innerText().then(t => t.replace(/[^0-9.\-NaN]/g, ''));

test('renders panels and the charges table without page errors', async ({ page }) => {
    const errors = await open(page);
    await expect(page.locator('h1, .mm-page-title').first()).toContainText(/checkout/i);
    await expect(page.locator('.mm-co-card')).toHaveCount(3);
    await expect(page.locator('.guest-detail-table tbody tr, .guest-detail-table tr').first()).toBeVisible();
    expect(await page.locator('#get-due').count()).toBe(1);
    expect(errors).toEqual([]);
});

const num = async (loc) => Number((await loc.first().innerText()).replace(/[^0-9.\-]/g, ''));
const bookingRow = page => page.locator('tr:has(.night-count)').first();

test('initial values come from the server-rendered amounts', async ({ page }) => {
    const errors = await open(page);
    expect(await bookingRow(page).locator('.night-count').inputValue()).toBe('2');
    expect(await page.locator('#get-due').inputValue()).toBe('6240');
    expect(errors).toEqual([]);
});

test('night + and - recalculate the row, the totals and the hidden inputs', async ({ page }) => {
    const errors = await open(page);
    const row = bookingRow(page);
    await row.locator('.btn-success').click();
    expect(await row.locator('.night-count').inputValue()).toBe('3');
    expect(await row.locator('.total-amount').innerText()).toBe('13860');
    expect(await row.locator('.service-charge').innerText()).toBe('600');
    expect(await row.locator('.vat-amount').innerText()).toBe('1260.00');
    expect(await row.locator('.due-amount').innerText()).toBe('10860');
    expect(await row.locator('.input-total-amount').inputValue()).toBe('13860');
    expect(await row.locator('.input-due-amount').inputValue()).toBe('10860');
    expect(await page.locator('#get-due').inputValue()).toBe('10860');
    expect(await num(page.locator('.grand-total-amount'))).toBe(13860);
    expect(await num(page.locator('.grand-service-charge'))).toBe(600);
    expect(await num(page.locator('.grand-vat-amount'))).toBe(1260);
    expect(await num(page.locator('.current-due'))).toBe(10860);
    await row.locator('.btn-danger').click();
    await row.locator('.btn-danger').click();
    expect(await row.locator('.night-count').inputValue()).toBe('1'); // never below one night
    await row.locator('.btn-danger').click();
    expect(await row.locator('.night-count').inputValue()).toBe('1');
    expect(await page.locator('#get-due').inputValue()).toBe('1620');
    expect(errors).toEqual([]);
});

test('discount, paid amount and the full-payment checkbox update the due amount', async ({ page }) => {
    const errors = await open(page);
    await page.locator('#discount').fill('240');
    expect(await num(page.locator('.current-due'))).toBe(6000);
    expect(await page.locator('#paidAmount').inputValue()).toBe('6000');
    await page.locator('#paidAmount').fill('1000');
    expect(await num(page.locator('.current-due'))).toBe(5240);
    // a discount above the due amount is capped and warned about through the legacy helper
    await page.locator('#discount').fill('99999');
    expect(await page.locator('#discount').inputValue()).toBe('6240');
    expect(await page.evaluate(() => window.warnings)).toContain('You can not discount more.');
    // full payment pays the whole due amount, zeroes and locks the discount; unchecking restores it
    await page.locator('#check-full-payment').check();
    expect(await page.locator('#paidAmount').inputValue()).toBe('6240');
    expect(await page.locator('#discount').inputValue()).toBe('0');
    expect(await page.locator('#discount').getAttribute('readonly')).not.toBeNull();
    expect(await num(page.locator('.current-due'))).toBe(0);
    await page.locator('#check-full-payment').uncheck();
    expect(await page.locator('#paidAmount').inputValue()).toBe('0');
    expect(await page.locator('#discount').getAttribute('readonly')).toBeNull();
    expect(await num(page.locator('.current-due'))).toBe(6240);
    expect(errors).toEqual([]);
});

test('submitting posts every legacy field name', async ({ page }) => {
    const errors = await open(page);
    await page.locator('#paidAmount').fill('500');
    const request = page.waitForRequest(r => r.method() === 'POST');
    await page.locator('button[type="submit"]').click();
    const body = (await request).postData() || '';
    for (const name of ['_token', 'night_count', 'discount', 'paid_amount', 'check_out_date']) expect(body).toContain('name="' + name + '"');
    expect(body).toMatch(/name="paid_amount"\s+500/);
    expect(errors).toEqual([]);
});

for (const width of [360, 768, 1280]) {
    test(`layout does not overflow horizontally at ${width}px`, async ({ page }) => {
        await open(page, width);
        const { scroll, client } = await page.evaluate(() => ({ scroll: document.documentElement.scrollWidth, client: document.documentElement.clientWidth }));
        expect(scroll).toBeLessThanOrEqual(client);
        await expect(page.locator('button[type="submit"]')).toBeVisible();
    });
}
