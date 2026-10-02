// Payment collection: the real rendered view (tools/fixtures/payment-collection.html) with its real script.
// Sample: two unpaid invoices (due 2000 + 1500 = 3500).
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const fixture = fs.readFileSync('tools/fixtures/payment-collection.html', 'utf8');

async function open(page, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.route('http://mm-collection.test/**', route => {
        const pathname = new URL(route.request().url()).pathname;
        if (pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        if (route.request().method() === 'POST') return route.fulfill({ contentType: 'text/plain', body: route.request().postData() || '' });
        return route.fulfill({ contentType: 'text/html', body: fixture });
    });
    await page.goto('http://mm-collection.test/hotel/booking-collection?hotel_guest_id=1');
    return errors;
}
const due = page => page.locator('.current-due').innerText().then(t => Number(t.replace(/[^0-9.\-]/g, '')));

test('renders search, guest information, invoices and summary', async ({ page }) => {
    const errors = await open(page);
    await expect(page.locator('h1')).toHaveText('Payment collection');
    await expect(page.locator('.mm-pc-info')).toContainText('aisha@example.com');
    await expect(page.locator('.guest-detail-table tbody tr')).toHaveCount(2);
    await expect(page.locator('.payable-amount')).toContainText('3,500.00');
    expect(await page.locator('#get-due').inputValue()).toBe('3500');
    expect(await page.locator('select[name="hotel_guest_id"]').inputValue()).toBe('1');
    // the discount control stays hidden, as before
    await expect(page.locator('.discount')).toBeHidden();
    expect(errors).toEqual([]);
});

test('paid amount reduces the due amount and is capped at the due amount', async ({ page }) => {
    const errors = await open(page);
    const paid = page.locator('input[name="total_paid_amount"]');
    await paid.fill('1000');
    expect(await due(page)).toBe(2500);
    await paid.fill('9000');
    expect(await paid.inputValue()).toBe('3500');
    expect(await due(page)).toBe(0);
    expect(await page.evaluate(() => window.warnings)).toContain('You can not paid more due amount.');
    expect(errors).toEqual([]);
});

test('full payment fills the paid amount and unchecking restores it', async ({ page }) => {
    const errors = await open(page);
    const paid = page.locator('input[name="total_paid_amount"]');
    await page.locator('#check-full-payment').check();
    expect(await paid.inputValue()).toBe('3500');
    expect(await due(page)).toBe(0);
    await page.locator('#check-full-payment').uncheck();
    expect(await paid.inputValue()).toBe('0');
    expect(await due(page)).toBe(3500);
    expect(errors).toEqual([]);
});

test('submitting posts the legacy field names', async ({ page }) => {
    const errors = await open(page);
    await page.locator('input[name="total_paid_amount"]').fill('500');
    await page.locator('select[name="payment_type"]').evaluate(el => { el.value = '1'; });
    const request = page.waitForRequest(r => r.method() === 'POST');
    await page.locator('form.form-horizontal button[type="submit"]').click();
    const body = (await request).postData() || '';
    for (const name of ['_token', 'hotel_guest_id', 'is_from_due_collection', 'item_ids[]', 'item_types[]', 'total_amount[]', 'item_amount[]', 'previous_collection[]', 'total_paid_amount', 'total_due_amount', 'payment_type']) {
        expect(body).toContain('name="' + name + '"');
    }
    expect(body).toMatch(/name="total_paid_amount"\s+500/);
    expect(errors).toEqual([]);
});

for (const width of [360, 768, 1280]) {
    test(`page does not scroll sideways at ${width}px`, async ({ page }) => {
        await open(page, width);
        const { scroll, client } = await page.evaluate(() => ({ scroll: document.documentElement.scrollWidth, client: document.documentElement.clientWidth }));
        expect(scroll).toBeLessThanOrEqual(client);
        await expect(page.locator('form.form-horizontal button[type="submit"]')).toBeVisible();
    });
}
