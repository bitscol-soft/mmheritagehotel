// Banquet Hall BH1 screens (hall setup and bookings) rendered from the real Blade views (tools/fixtures/banquet/*.html).
// Script partials are not part of the fixtures (they are unchanged); frames, filters, forms and tables are real.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const html = fs.readFileSync(`tools/fixtures/banquet/${name}.html`, 'utf8');
    await page.route('http://mm-bq.test/**', route => {
        const url = new URL(route.request().url());
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-bq.test/bq/x');
    return { errors };
}

const screens = {
    'aminities-index': 'Hall amenities',
    'aminities-create': 'Add hall amenity',
    'aminities-edit': 'Edit hall amenity',
    'category-index': 'Hall categories',
    'category-create': 'Add a hall category',
    'category-edit': 'Edit hall category',
    'rooms-index': 'Halls',
    'rooms-create': 'Add a hall',
    'rooms-edit': 'Edit hall',
    'booking-index': 'Banquet bookings',
    'booking-index-empty': 'Banquet bookings',
    'booking-create': 'New banquet booking',
};

for (const [name, title] of Object.entries(screens)) {
    test(`${name}: shared page frame, no legacy widget, no page overflow at desktop, tablet and phone`, async ({ page }) => {
        await open(page, name);
        await expect(page.locator('.mm-page-title').first()).toContainText(title);
        await expect(page.locator('.mm-ui.mm-banquet .mm-panel').first()).toBeVisible();
        await expect(page.locator('.widget-box, .widget-main, .widget-header, .page-header')).toHaveCount(0);
        for (const width of [1280, 768, 390]) {
            await page.setViewportSize({ width, height: 900 });
            const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            expect(over, `${name} overflows by ${over}px at ${width}px`).toBeLessThanOrEqual(0);
        }
    });
}

test('amenities list: names are escaped and each row has its own delete form', async ({ page }) => {
    await open(page, 'aminities-index');
    await expect(page.locator('#data-table tbody tr')).toHaveCount(2);
    await expect(page.locator('#data-table')).toContainText('Stage <b>lights</b>');
    await expect(page.locator('#data-table b')).toHaveCount(0);
    await expect(page.locator('#deleteCheck_1 input[name="_method"]')).toHaveValue('DELETE');
    await expect(page.locator('#data-table .label-success')).toHaveText('Active');
    await expect(page.locator('#data-table .label-danger')).toHaveText('InActive');
});

test('amenity forms keep their fields; the duplicate alert is gone', async ({ page }) => {
    await open(page, 'aminities-create');
    const form = page.locator('#companyForm');
    await expect(form).toHaveAttribute('enctype', 'multipart/form-data');
    for (const n of ['name', 'aminiti_icon', 'status']) await expect(form.locator(`[name="${n}"]`)).toHaveCount(1);
    await expect(form.locator('button[type=submit].mm-button')).toBeVisible();
    await open(page, 'aminities-edit');
    await expect(page.locator('#companyForm input[name="_method"]')).toHaveValue('PUT');
    await expect(page.locator('#companyForm input[name="name"]')).toHaveValue('Stage <b>lights</b>');
});

test('category forms: amenity checkboxes, status and the list link', async ({ page }) => {
    await open(page, 'category-create');
    await expect(page.locator('input[name="aminities[]"]')).toHaveCount(2);
    await expect(page.locator('.mm-page-title').locator('xpath=ancestor::section').locator('a', { hasText: 'Category List' })).toBeVisible();
    await open(page, 'category-edit');
    await expect(page.locator('input[name="aminities[]"]:checked')).toHaveCount(1);
    await expect(page.locator('input[name="cat_name"]')).toHaveValue('Wedding <b>hall</b>');
    await expect(page.locator('textarea[name="description"]')).toHaveValue('Large <i>hall</i>');
    await expect(page.locator('form input[name="_method"]')).toHaveValue('PUT');
});

test('category list: capacity, status labels and delete handler', async ({ page }) => {
    await open(page, 'category-index');
    await expect(page.locator('#dynamic-table')).toContainText('400 Person');
    await expect(page.locator('#dynamic-table .label-success')).toHaveText('Active');
    await expect(page.locator('#dynamic-table .label-danger')).toHaveText('In Active');
    await expect(page.locator('#dynamic-table button[onclick^="delete_item"]')).toHaveCount(2);
    await expect(page.locator('#dynamic-table')).not.toContainText('Wedding hall');
});

test('hall list: search filter keeps its names and the table scrolls inside its own region', async ({ page }) => {
    await open(page, 'rooms-index');
    const filter = page.locator('.mm-setup-filter form');
    await expect(filter.locator('[name="name"]')).toBeVisible();
    await expect(filter.locator('[name="room_number"]')).toBeVisible();
    await expect(filter.locator('button.mm-button')).toBeVisible();
    await expect(page.locator('.mm-table-scroll')).toHaveCount(1);
    await expect(page.locator('#data-table')).toContainText('Grand <b>Hall</b>');
    await expect(page.locator('#data-table .label-success')).toHaveText('Ready');
});

test('hall forms keep the submit button id and the render-class hook', async ({ page }) => {
    await open(page, 'rooms-create');
    await expect(page.locator('.render-class')).toHaveCount(1);
    for (const n of ['hall_category', 'price', 'max_guests']) await expect(page.locator(`[name="${n}"]`)).toHaveCount(1);
    await expect(page.locator('#submitRoomFormBtn')).toHaveClass(/mm-button/);
    await open(page, 'rooms-edit');
    await expect(page.locator('#roomId')).toHaveValue('5');
    await expect(page.locator('form input[name="_method"]')).toHaveValue('PUT');
});

test('booking list: filter, escaped guest, action buttons and cancel form', async ({ page }) => {
    await open(page, 'booking-index');
    const filter = page.locator('form.mm-booking-filter');
    for (const n of ['customer_id', 'booking_from_date', 'booking_to_date', 'booking_number', 'status']) await expect(filter.locator(`[name="${n}"]`)).toHaveCount(1);
    await expect(filter.locator('button[type=submit]')).toBeVisible();
    const table = page.locator('.mm-table-scroll #data-table');
    await expect(table).toContainText('BQ-0007');
    await expect(table).toContainText('Rahim <b>Uddin</b>');
    await expect(table.locator('b', { hasText: 'Uddin' })).toHaveCount(0);
    await expect(table.locator('.action-button a[title="Check IN"]')).toHaveAttribute('href', '#check-in7');
    await expect(table.locator('.action-button a[title="View Details"]')).toHaveAttribute('href', '#project-details7');
    await expect(table.locator('.action-button button[title="Due Collection"]')).toHaveCount(1);
    await expect(page.locator('#cancelBookingForm')).toHaveAttribute('method', 'POST');
    await expect(page.locator('#project-details7')).toHaveCount(1);
});

test('booking list without rows tells the user how to recover', async ({ page }) => {
    await open(page, 'booking-index-empty');
    await expect(page.locator('[role=status]')).toContainText('No bookings found');
});

test('new booking: form contract, three item tables and the sticky action bar', async ({ page }) => {
    await open(page, 'booking-create');
    const form = page.locator('#submitBookingUpdateForm');
    await expect(form).toHaveAttribute('enctype', 'multipart/form-data');
    await expect(form.locator('input[name="is_from_booking"]')).toHaveValue('1');
    for (const id of ['addrow', 'addItem', 'addInformation']) await expect(page.locator(`#${id}`)).toHaveCount(1);
    await expect(page.locator('.mm-table-scroll')).toHaveCount(3);
    await expect(page.locator('.mm-form-actions .updateBookingBtn')).toHaveCount(2);
    await expect(page.locator('.mm-form-actions button[type=Reset]')).toHaveCount(1);
    await expect(page.locator('#customer_id')).toContainText('Rahim <b>Uddin</b>');
});
