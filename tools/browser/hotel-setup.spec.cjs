// Hotel setup screens rendered from the real Blade views (tools/fixtures/hotel-setup/*.html).
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const screens = {
    'aminities-index': 'Amenities', 'aminities-create': 'Add amenity', 'aminities-edit': 'Edit amenity',
    'account_type-index': 'Account types', 'account_type-edit': 'Edit account type', 'vat-index': 'VAT and services',
    'currency-conversions-index': 'Currency conversions', 'guest-registration-terms-index': 'Registration terms', 'guest-registration-terms-edit': 'Edit registration terms',
};

// Fixture forms are multipart or urlencoded; return a URLSearchParams-like view of the fields.
function parseBody(body, type) {
    const params = new URLSearchParams();
    const boundary = /boundary=(.+)$/.exec(type);
    if (!boundary) return new URLSearchParams(body);
    for (const part of body.split('--' + boundary[1])) {
        const m = /name="([^"]*)"(?:; filename="[^"]*")?\r\n(?:[^\r\n]+\r\n)*\r\n([\s\S]*?)\r\n$/.exec(part);
        if (m) params.append(m[1], m[2]);
    }
    return params;
}

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const posts = [];
    const html = fs.readFileSync(`tools/fixtures/hotel-setup/${name}.html`, 'utf8');
    await page.route('http://mm-setup.test/**', route => {
        const request = route.request();
        const pathname = new URL(request.url()).pathname;
        if (pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        if (request.method() === 'POST') { posts.push(parseBody(request.postData() || '', request.headers()['content-type'] || '')); return route.fulfill({ contentType: 'text/plain', body: 'saved' }); }
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-setup.test/hotel/setup');
    // fixture forms carry absolute route URLs; point them at the test host so the POST is captured
    await page.evaluate(() => document.querySelectorAll('form[action^="http"]').forEach(f => { f.action = new URL(f.getAttribute('action')).pathname; }));
    return { errors, posts };
}

for (const [name, title] of Object.entries(screens)) {
    test(`${name} renders in the shared frame without script errors`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('h1')).toHaveText(title);
        await expect(page.locator('.mm-hotel-setup')).toHaveCount(1);
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

test('amenity create posts the name and status to the store route', async ({ page }) => {
    const { posts } = await open(page, 'aminities-create');
    await expect(page.locator('#companyForm')).toHaveAttribute('method', 'post');
    await page.locator('input[name="name"]').fill('Airport pickup');
    await page.locator('select[name="status"]').selectOption('1');
    await page.getByRole('button', { name: 'Save' }).click();
    await expect.poll(() => posts.length).toBe(1);
    expect(posts[0].get('name')).toBe('Airport pickup');
    expect(posts[0].get('status')).toBe('1');
    expect(posts[0].has('_token')).toBe(true);
});

test('amenity list keeps the delete form and escapes record names', async ({ page }) => {
    await open(page, 'aminities-index');
    await expect(page.locator('#data-table tbody tr')).toHaveCount(2);
    await expect(page.locator('#deleteCheck_2')).toHaveCount(1);
    await expect(page.locator('#data-table')).toContainText('Pool <i>view</i>');
    await expect(page.locator('#data-table .label-success')).toHaveText('Active');
    await expect(page.locator('#data-table .label-danger')).toHaveText('InActive');
});

test('account type page lists types beside the add form', async ({ page }) => {
    await open(page, 'account_type-index', 1280);
    const list = await page.locator('#data-table').boundingBox();
    const form = await page.locator('#companyForm').boundingBox();
    expect(form.x).toBeGreaterThan(list.x + list.width - 1);
    await expect(page.locator('#data-table tbody tr')).toHaveCount(2);
    await page.setViewportSize({ width: 600, height: 900 });
    const stacked = await page.locator('#companyForm').boundingBox();
    expect(stacked.y).toBeGreaterThan((await page.locator('#data-table').boundingBox()).y);
});

test('VAT form keeps its inputs, radio group and PUT method', async ({ page }) => {
    const { posts } = await open(page, 'vat-index');
    await expect(page.locator('input[name="hotel_vat"]')).toHaveValue('10');
    await page.locator('input[name="hotel_vat"]').fill('12');
    await page.getByLabel('No').check();
    await expect(page.getByLabel('Yes')).not.toBeChecked();
    await page.getByRole('button', { name: 'Save' }).click();
    await expect.poll(() => posts.length).toBe(1);
    expect(posts[0].get('_method')).toBe('PUT');
    expect(posts[0].get('hotel_vat')).toBe('12');
    expect(posts[0].get('key[use_vat_included]')).toBe('0');
    for (const name of ['resturent_vat', 'bar_vat', 'vat_number', 'room_rate', 'room_service', 'rst_service_charge']) expect(posts[0].has(name), name).toBe(true);
});

test('currency conversion create form sits beside the list and posts its fields', async ({ page }) => {
    const { posts } = await open(page, 'currency-conversions-index');
    await expect(page.locator('#data-table tbody tr')).toHaveCount(2);
    await expect(page.locator('.render-currency-class')).toContainText('Add New');
    await page.locator('#currencyId').selectOption('2');
    await page.locator('#rate').fill('123.4');
    await page.evaluate(() => window.jQuery('#effectedDate').datepicker('setDate', '2026-10-01'));
    await page.getByRole('button', { name: 'Save' }).click();
    await expect.poll(() => posts.length).toBe(1);
    expect(posts[0].get('currency_id')).toBe('2');
    expect(posts[0].get('rate')).toBe('123.4');
    expect(posts[0].get('effected_date')).toBe('2026-10-01');
});

test('currency conversion Save asks for a missing field instead of posting', async ({ page }) => {
    await page.addInitScript(() => { window.toastr = { error: message => { window.toasts = (window.toasts || []).concat(message); } }; });
    const { posts } = await open(page, 'currency-conversions-index');
    await page.locator('#currencyId').selectOption('2');
    await page.getByRole('button', { name: 'Save' }).click();
    expect(await page.evaluate(() => window.toasts)).toEqual(['Please choose a Rate!']);
    expect(posts).toHaveLength(0);
});

test('registration terms search keeps the title filter and list shows plain text', async ({ page }) => {
    await open(page, 'guest-registration-terms-index');
    await expect(page.locator('input[name="title"]')).toBeVisible();
    await expect(page.locator('table tbody td').nth(1)).toHaveText('Check-in after 2pm');
});
