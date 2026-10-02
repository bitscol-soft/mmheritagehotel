// Booking invoice (checkout_invoice): real rendered view with the shared page frame. printThis is stubbed so print requests can be counted.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const fixture = fs.readFileSync('tools/fixtures/booking-invoice.html', 'utf8');
const printStub = "jQuery.fn.printThis = function () { window.printed = (window.printed || []).concat(this.attr('id')); return this; };";

async function open(page, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.route('http://mm-invoice.test/**', route => {
        const pathname = new URL(route.request().url()).pathname;
        if (pathname === '/assets/custom_js/printThis.js') return route.fulfill({ contentType: 'text/javascript', body: printStub });
        if (pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        return route.fulfill({ contentType: 'text/html', body: fixture });
    });
    await page.goto('http://mm-invoice.test/hotel/booking/invoice/7');
    return errors;
}

test('renders the invoice document with its totals and no page errors', async ({ page }) => {
    const errors = await open(page);
    await expect(page.locator('h1')).toHaveText('Booking invoice');
    await expect(page.locator('#print_body .inv-head')).toBeVisible();
    await expect(page.locator('#print_body')).toContainText('BK-0007');
    await expect(page.locator('#print_body')).toContainText('8,000.00');
    await expect(page.locator('#print_body')).toContainText('3,500.00');
    // guest data is escaped, not interpreted as markup
    await expect(page.locator('#print_body')).toContainText("<b>O'Neil</b>");
    expect(errors).toEqual([]);
});

test('prints once on open and again from the Print button, always the document only', async ({ page }) => {
    const errors = await open(page);
    await expect.poll(() => page.evaluate(() => window.printed || [])).toEqual(['print_body']);
    await page.getByRole('link', { name: 'Print' }).click();
    expect(await page.evaluate(() => window.printed)).toEqual(['print_body', 'print_body']);
    expect(page.url()).not.toContain('#');
    expect(errors).toEqual([]);
});

test('page chrome is hidden when printing, the document stays', async ({ page }) => {
    await open(page);
    await page.emulateMedia({ media: 'print' });
    await expect(page.locator('#print_body')).toBeVisible();
    await expect(page.getByRole('link', { name: 'Print' })).toBeHidden();
});

for (const width of [360, 768, 1280]) {
    test(`page does not scroll sideways at ${width}px; the sheet scrolls inside its panel`, async ({ page }) => {
        await open(page, width);
        const { scroll, client } = await page.evaluate(() => ({ scroll: document.documentElement.scrollWidth, client: document.documentElement.clientWidth }));
        expect(scroll).toBeLessThanOrEqual(client);
        await expect(page.locator('#print_body .inv-head')).toBeVisible();
    });
}
