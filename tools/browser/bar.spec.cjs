// Bar B1 screens (inventory setup and catalog, tables, purchases, sales, returns, reports, night audit) rendered from the real Blade views (tools/fixtures/bar/*.html).
// Script partials are not part of the fixtures (they are unchanged); frames, filters, forms and tables are real.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const html = fs.readFileSync(`tools/fixtures/bar/${name}.html`, 'utf8');
    await page.addInitScript(() => { window.printCalls = 0; window.print = () => { window.printCalls += 1; }; });
    await page.route('http://mm-bar.test/**', route => {
        const url = new URL(route.request().url());
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-bar.test/bar/x');
    return { errors };
}

// page scripts that rely on globals of the real layout or on script partials that the fixtures leave out
const ignorable = e => /toastr|chosen|ace_file_input|datepicker|daterangepicker|tag is not|parsley|swal|Swal|dataTable|DataTable|printForm|printPage|delete_item|loadSelect2|reading 'length'|is not defined/i.test(e);
const screens = {
    'categories': 'Product categories', 'units': 'Units', 'manufacturers': 'Manufacturers', 'suppliers': 'Suppliers', 'tables': 'Tables',
    'products': 'Products', 'product-create': 'Add product', 'product-edit': 'Edit product', 'inventory-report': 'Product inventory',
    'purchase-list': 'Purchase list', 'purchase-show': 'Purchase details',
    'sales-list': 'Sale list', 'sales-show': 'Sale invoice', 'sales-create': 'New sale',
    'return-list': 'Sale return list', 'return-show': 'Sale return details', 'return-create': 'New sale return',
    'report-cash-flow': 'Cash flow', 'report-sales': 'Sales report', 'report-today': 'Today', 'report-inventory': 'Product inventory',
    'audit-list': 'Bar night audit', 'audit-generate': 'Generate bar night audit',
};

for (const [name, title] of Object.entries(screens)) {
    test(`${name}: shared page frame, no legacy widget, no page overflow at desktop, tablet and phone`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('.mm-page-title').first()).toContainText(title);
        await expect(page.locator('.mm-ui.mm-bar .mm-panel').first()).toBeVisible();
        await expect(page.locator('.widget-box, .widget-main, .widget-header')).toHaveCount(0);
        for (const width of [1280, 768, 390]) {
            await page.setViewportSize({ width, height: 900 });
            const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            expect(over, `${name} overflows at ${width}`).toBeLessThanOrEqual(1);
        }
        expect(errors.filter(e => !ignorable(e))).toEqual([]);
    });
}

test('setup lists keep their modals, edit hooks and delete hooks, and escape names', async ({ page }) => {
    await open(page, 'categories');
    await expect(page.locator('#data-table')).toContainText('Spirits <b>x</b>');
    await expect(page.locator('#data-table b:text-is("x")')).toHaveCount(0);
    await expect(page.locator('a[href="#modal-dialog"]').first()).toHaveCount(1);
    await expect(page.locator('[onclick*="editCategory"]')).toHaveCount(2);
    await expect(page.locator('[onclick*="delete_item"]')).toHaveCount(2);
    await open(page, 'units');
    await expect(page.locator('#data-table')).toContainText('Peg <b>x</b>');
    await open(page, 'suppliers');
    await expect(page.locator('#data-table')).toContainText('Sarker <b>Traders</b>');
    await open(page, 'tables');
    await expect(page.locator('#data-table')).toContainText('Bar <b>1</b>');
});

test('manufacturer edit modal posts to the Bar route, not the Restaurant one', async ({ page }) => {
    await open(page, 'manufacturers');
    await expect(page.locator('form[action$="/bar/manufacturers/update/1"]')).toHaveCount(1);
    await expect(page.locator('form[action*="/rst/"]')).toHaveCount(0);
    await expect(page.locator('form[action$="/bar/manufacturers/store"]')).toHaveCount(1);
});

test('product list keeps the filter in its own panel and the row table', async ({ page }) => {
    await open(page, 'products');
    await expect(page.locator('.mm-report-filter form')).toHaveCount(1);
    for (const name of ['name', 'barcode', 'category_id']) await expect(page.locator(`.mm-report-filter [name="${name}"]`)).toHaveCount(1);
    await expect(page.locator('#datatable')).toContainText('Black <b>Label</b>');
    await expect(page.locator('#datatable b:text-is("Label")')).toHaveCount(0);
    await expect(page.locator('.pagination').first()).toBeVisible();
    await expect(page.locator('.mm-report-filter table')).toHaveCount(0);
    const tops = await page.evaluate(() => ['name', 'barcode', 'category_id'].map(n => Math.round(document.querySelector(`.mm-report-filter [name="${n}"]`).getBoundingClientRect().top)));
    expect(new Set(tops).size, 'filter fields share one row on desktop').toBe(1);
});

test('product forms keep their fields, posts and the update method', async ({ page }) => {
    await open(page, 'product-create');
    for (const name of ['name', 'barcode', 'category_id', 'unit_id', 'supplier_id']) await expect(page.locator(`form [name="${name}"]`).first()).toHaveCount(1);
    await expect(page.locator('form[data-parsley-validate]').first()).toHaveCount(1);
    await open(page, 'product-edit');
    await expect(page.locator('input[name="_method"][value="PUT"]')).toHaveCount(1);
    await expect(page.locator('input[name="name"]')).toHaveValue('Black <b>Label</b>');
});

test('report and list filters are inline fields in a filter panel, not bordered tables', async ({ page }) => {
    const filters = { 'sales-list': ['customer', 'date', 'invoice_no'], 'return-list': ['invoice_no'], 'report-cash-flow': ['invoice_no', 'from_date', 'to_date'], 'report-sales': ['invoice_no', 'guest_name', 'from_date', 'to_date'], 'report-today': ['from_date', 'to_date'], 'audit-list': ['from_date', 'to_date'] };
    for (const [name, fields] of Object.entries(filters)) {
        await open(page, name);
        await expect(page.locator('.mm-report-filter form.mm-report-form'), name).toHaveCount(1);
        for (const field of fields) await expect(page.locator(`.mm-report-filter [name="${field}"]`), `${name} ${field}`).toHaveCount(1);
        await expect(page.locator('.mm-report-filter table'), name).toHaveCount(0);
        await expect(page.locator('.mm-report-filter .mm-button[type="submit"]'), name).toBeVisible();
    }
});

test('product inventory reports keep the filter fields in the filter panel', async ({ page }) => {
    for (const name of ['inventory-report', 'report-inventory']) {
        await open(page, name);
        await expect(page.locator('.mm-report-filter select[name="category_id"]'), name).toHaveCount(1);
        await expect(page.locator('.mm-report-filter')).toBeVisible();
    }
});

test('purchase, sale and return lists keep the row actions and escape data', async ({ page }) => {
    await open(page, 'purchase-list');
    await expect(page.locator('[onclick*="delete_item"]')).toHaveCount(2);
    await expect(page.locator('a[href*="/bar/purchases/show/1"], a[href*="/bar/purchases/1"]').first()).toHaveCount(1);
    await open(page, 'sales-list');
    await expect(page.locator('#datatable')).toBeVisible();
    await expect(page.locator('[onclick*="delete_item"]')).toHaveCount(2);
    await open(page, 'return-list');
    await expect(page.locator('[onclick*="delete_item"]')).toHaveCount(2);
});

test('invoices print through their own button and escape data', async ({ page }) => {
    await open(page, 'sales-show');
    await expect(page.locator('.mm-bar').first()).toContainText('Whisky <i>hot</i>');
    await expect(page.locator('i:text-is("hot")')).toHaveCount(0);
    await expect(page.locator('#print_body')).toHaveCount(1);
    await open(page, 'purchase-show');
    await expect(page.locator('.mm-bar').first()).toContainText('Black <b>Label</b>');
    await expect(page.locator('b:text-is("Label")')).toHaveCount(0);
    await open(page, 'return-show');
    await expect(page.locator('#print_body')).toHaveCount(1);
});

test('sale and return forms keep their fields', async ({ page }) => {
    await open(page, 'sales-create');
    for (const name of ['invoice_no', 'date', 'payment_way', 'subtotal', 'payable_amount', 'due_amount', 'draft']) await expect(page.locator(`[name="${name}"]`).first()).toHaveCount(1);
    await expect(page.locator('#product-details')).toHaveCount(1);
    await open(page, 'return-create');
    for (const name of ['customer_id', 'invoice_no', 'date', 'subtotal', 'return_amount', 'due_amount', 'draft']) await expect(page.locator(`[name="${name}"]`).first()).toHaveCount(1);
});

test('night audit generate keeps its fields', async ({ page }) => {
    await open(page, 'audit-generate');
    await expect(page.locator('form#formSubmit')).toHaveCount(1);
    for (const name of ['date', 'transaction_ids[21]', 'collections[21]', 'due_amounts[21]', 'payment_way[Cash]', 'payment_way[Card]', 'total_amount']) await expect(page.locator(`[name="${name}"]`), name).toHaveCount(1);
});

test('forms and lists stack on a phone without clipping the fields', async ({ page }) => {
    for (const name of ['product-create', 'sales-create', 'audit-generate', 'report-sales']) {
        await open(page, name, 390);
        const bad = await page.evaluate(() => [...document.querySelectorAll('.mm-bar form .form-control')].filter(e => { const r = e.getBoundingClientRect(); return r.width > 0 && (r.left < 0 || r.right > innerWidth + 1); }).length);
        expect(bad, name).toBe(0);
    }
});
