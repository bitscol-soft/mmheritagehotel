// General Store G1 screens (items, item units, suppliers, supplier types) rendered from the real Blade views (tools/fixtures/general-store/*.html).
// Script partials are not part of the fixtures (they are unchanged); frames, filters, forms and tables are real.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const requests = [];
    const html = fs.readFileSync(`tools/fixtures/general-store/${name}.html`, 'utf8');
    await page.addInitScript(() => { window.printCalls = 0; window.print = () => { window.printCalls += 1; }; window.printForm = window.printForm || function () { window.print(); }; });
    await page.route('http://mm-gs.test/**', route => {
        const request = route.request();
        const url = new URL(request.url());
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        requests.push({ method: request.method(), path: url.pathname });
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-gs.test/gs/x');
    requests.length = 0;
    return { errors, requests };
}

// page scripts that rely on globals of the real layout (loadSelect2, select2 data) or on script partials that the fixtures leave out
const ignorable = e => /toastr|chosen|ace_file_input|datepicker|daterangepicker|tag is not|parsley|swal|Swal|dataTable|DataTable|printForm|delete_item|loadSelect2|reading 'length'/i.test(e);
const screens = {
    'item-units': 'Item units', 'form-item-unit-create': 'Add item unit', 'form-item-unit-edit': 'Edit item unit',
    'items': 'Item list', 'form-item-create': 'Add item', 'form-item-edit': 'Edit item', 'form-item-upload': 'Upload items',
    'suppliers': 'Suppliers', 'form-supplier-create': 'Add supplier', 'form-supplier-edit': 'Edit supplier', 'supplier-types': 'Supplier types',
};

for (const [name, title] of Object.entries(screens)) {
    test(`${name}: shared page frame, no legacy widget, no page overflow at desktop, tablet and phone`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('.mm-page-title').first()).toContainText(title);
        await expect(page.locator('.mm-ui.mm-gs .mm-panel').first()).toBeVisible();
        await expect(page.locator('.widget-box, .widget-main, .widget-header')).toHaveCount(0);
        for (const width of [1280, 768, 390]) {
            await page.setViewportSize({ width, height: 900 });
            const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            expect(over, `${name} overflows at ${width}`).toBeLessThanOrEqual(1);
        }
        expect(errors.filter(e => !ignorable(e))).toEqual([]);
    });
}

test('item units list keeps the delete hook and escapes names; the dead export row is gone', async ({ page }) => {
    await open(page, 'item-units');
    await expect(page.locator('#dynamic-table')).toContainText('Sack <b>x</b>');
    await expect(page.locator('#dynamic-table b:text-is("x")')).toHaveCount(0);
    await expect(page.locator('button[onclick="delete_check(1)"]')).toHaveCount(1);
    await expect(page.locator('form#deleteCheck_1 input[name="_method"][value="DELETE"]')).toHaveCount(1);
    await expect(page.locator('img[src*="export-icons"]')).toHaveCount(0);
    await expect(page.getByRole('link', { name: /Add Item Unit/ })).toBeVisible();
});

test('item unit forms keep their fields and post targets', async ({ page }) => {
    await open(page, 'form-item-unit-create');
    for (const name of ['name', 'conversion', 'status']) await expect(page.locator(`form [name="${name}"]`)).toHaveCount(1);
    await expect(page.locator('form[action*="item-units"]')).toHaveCount(1);
    await open(page, 'form-item-unit-edit');
    await expect(page.locator('form[action*="item-units"] input[name="_method"][value="PUT"]')).toHaveCount(1);
    await expect(page.locator('input[name="name"]')).toHaveValue('Sack <b>x</b>');
});

test('item list keeps the filter, the upload and export buttons and the row actions', async ({ page }) => {
    await open(page, 'items');
    await expect(page.locator('.mm-report-filter form')).toHaveCount(1);
    await expect(page.locator('select[name="company_id"]')).toHaveCount(1);
    await expect(page.locator('a[href$="/gs/item/upload"], a[href$="/gs/item-upload"]').first()).toBeVisible();
    await expect(page.locator('a[href*="item/export"], a[href*="item-export"]').first()).toBeVisible();
    await expect(page.locator('table')).toContainText('Basmati <b>rice</b>');
    await expect(page.locator('table')).toContainText('Kg');
    await expect(page.locator('p.mm-rst-count')).toContainText('Records Found');
});

test('item forms keep the unit select, the opening balance and the update method', async ({ page }) => {
    await open(page, 'form-item-create');
    for (const name of ['company_id', 'name', 'item_unit_id', 'opening_balance', 'rate']) await expect(page.locator(`form [name="${name}"]`)).toHaveCount(1);
    await open(page, 'form-item-edit');
    await expect(page.locator('input[name="_method"][value="PUT"]')).toHaveCount(1);
    await expect(page.locator('input[name="name"]')).toHaveValue('Basmati <b>rice</b>');
    await open(page, 'form-item-upload');
    await expect(page.locator('input[type="file"][name="item_csv_file"]')).toHaveCount(1);
    await expect(page.locator('a[href*="item-sample-csv.csv"]')).toHaveCount(1);
});

test('supplier list opens the detail modal and keeps the delete hook', async ({ page }) => {
    await open(page, 'suppliers');
    await expect(page.locator('#data-table')).toContainText('Sarker <b>Traders</b>');
    await expect(page.locator('a[href="#view-details1"][data-toggle="modal"]')).toHaveCount(1);
    await expect(page.locator('#view-details1')).toContainText('Dhaka <i>1</i>');
    await expect(page.locator('button[onclick="delete_check(1)"]')).toHaveCount(1);
    await expect(page.locator('#view-details1')).toBeHidden();
    await page.locator('a[href="#view-details1"]').click();
    await expect(page.locator('#view-details1')).toBeVisible();
});

test('supplier forms keep every field and the update method', async ({ page }) => {
    await open(page, 'form-supplier-create');
    for (const name of ['group_id', 'name', 'attention', 'supplier_type_id', 'country_id', 'phone', 'email']) await expect(page.locator(`form [name="${name}"]`)).toHaveCount(1);
    await expect(page.locator('select[name="country_id"]')).toHaveValue('18');
    await open(page, 'form-supplier-edit');
    await expect(page.locator('input[name="_method"][value="PUT"]')).toHaveCount(1);
    await expect(page.locator('input[name="name"]')).toHaveValue('Sarker <b>Traders</b>');
});

test('supplier types: add rows with the plus button, remove them, search and edit modal', async ({ page }) => {
    await open(page, 'supplier-types');
    await expect(page.locator('table.edu1 input[name="name[]"]')).toHaveCount(1);
    await page.locator('#add').click();
    await expect(page.locator('table.edu1 input[name="name[]"]')).toHaveCount(2);
    await page.locator('table.edu1 .ibtnDel:not([disabled])').first().click();
    await expect(page.locator('table.edu1 input[name="name[]"]')).toHaveCount(1);
    await expect(page.locator('input[name="name"][placeholder="Sample Type Name"]').first()).toBeVisible();
    await expect(page.locator('body')).toContainText('Total : 2');
    await expect(page.locator('body')).toContainText('Local <b>x</b>');
    await expect(page.locator('button[onclick="delete_check(1)"]')).toHaveCount(1);
});

test('forms stack on a phone without clipping the fields', async ({ page }) => {
    for (const name of ['form-supplier-create', 'form-item-create']) {
        await open(page, name, 390);
        const bad = await page.evaluate(() => [...document.querySelectorAll('.mm-gs form .form-control')].filter(e => { const r = e.getBoundingClientRect(); return r.width > 0 && (r.left < 0 || r.right > innerWidth + 1); }).length);
        expect(bad, name).toBe(0);
    }
});
