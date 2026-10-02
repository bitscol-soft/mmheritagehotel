// Restaurant inventory screens (group R4) rendered from the real Blade views (tools/fixtures/restaurant-inventory/*.html).
// Script partials are not part of the fixtures (they are unchanged); frames, filters, forms and tables are real.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const requests = [];
    const html = fs.readFileSync(`tools/fixtures/restaurant-inventory/${name}.html`, 'utf8');
    await page.addInitScript(() => { window.printCalls = 0; window.print = () => { window.printCalls += 1; }; window.printForm = window.printForm || function () { window.print(); }; });
    await page.route('http://mm-rsi.test/**', route => {
        const request = route.request();
        const url = new URL(request.url());
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        requests.push({ method: request.method(), path: url.pathname });
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-rsi.test/rst/x');
    requests.length = 0;
    return { errors, requests };
}

// page scripts that rely on globals of the real layout (loadSelect2, select2 data) or on script partials that the fixtures leave out
const ignorable = e => /toastr|chosen|ace_file_input|datepicker|daterangepicker|tag is not|parsley|swal|Swal|dataTable|DataTable|printForm|delete_item|loadSelect2|reading 'length'/i.test(e);
const screens = {
    'categories': 'Product categories', 'units': 'Units', 'manufacturers': 'Manufacturers', 'suppliers': 'Suppliers',
    'products': 'Products', 'mat-products': 'Material products', 'inventory-report': 'Product inventory', 'product-uploads': 'Uploaded products',
    'production-items': 'Item list', 'production-item-units': 'Material units', 'production-requisitions': 'Goods requisition', 'production-purchases': 'Purchase list', 'adjustments': 'Stock adjustments',
    'form-product-create': 'Add product', 'form-product-upload': 'Add product', 'form-product-edit': 'Edit product', 'form-mat-create': 'Add material product', 'form-mat-edit': 'Edit material product', 'form-upload-edit': 'Edit uploaded product',
    'form-item-create': 'Add item', 'form-item-edit': 'Edit item', 'form-item-unit-create': 'Add material unit', 'form-item-unit-edit': 'Edit material unit',
    'form-requisition-create': 'Goods requisition', 'form-purchase-approve': 'Purchase approve', 'form-purchase-edit': 'Edit purchase', 'form-purchase-create': 'Purchase create',
    'form-adjustment-create': 'Stock adjustment', 'adjustment-view': 'Stock adjustment', 'form-adjustment-edit': 'Stock adjustment', 'purchase-show': 'Purchase requisition details',
};

for (const [name, title] of Object.entries(screens)) {
    test(`${name}: shared page frame, no legacy widget, no page overflow at desktop, tablet and phone`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('.mm-page-title').first()).toContainText(title);
        await expect(page.locator('.mm-ui.mm-rst-inv .mm-panel').first()).toBeVisible();
        await expect(page.locator('.widget-box, .widget-main, .widget-header')).toHaveCount(0);
        for (const width of [1280, 768, 390]) {
            await page.setViewportSize({ width, height: 900 });
            const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            expect(over, `${name} overflows at ${width}`).toBeLessThanOrEqual(1);
        }
        expect(errors.filter(e => !ignorable(e))).toEqual([]);
    });
}

test('setup lists keep the modal create link and escape names', async ({ page }) => {
    await open(page, 'categories');
    await expect(page.locator('a[href="#modal-dialog"][data-toggle="modal"]')).toHaveCount(1);
    await expect(page.locator('#data-table')).toContainText('Rice <b>dishes</b>');
    await expect(page.locator('#data-table b:text-is("dishes")')).toHaveCount(0);
    await expect(page.locator('#data-table tbody tr')).toHaveCount(2);
});

test('product list puts the filter in its own panel above the results', async ({ page }) => {
    await open(page, 'products');
    await expect(page.locator('.mm-report-filter')).toHaveCount(1);
    await expect(page.locator('.mm-report-filter [name="barcode"]')).toHaveCount(1);
    const { filter, table } = await page.evaluate(() => ({ filter: document.querySelector('.mm-report-filter').getBoundingClientRect(), table: document.querySelector('table').getBoundingClientRect() }));
    expect(table.top).toBeGreaterThan(filter.bottom - 1);
    await expect(page.getByRole('link', { name: /Upload Product/ })).toBeVisible();
    await expect(page.getByRole('link', { name: /Add New Product/ })).toBeVisible();
    await expect(page.locator('table')).toContainText('Chicken <b>Biryani</b>');
});

test('tables scroll inside their own wrapper on a phone, never the page', async ({ page }) => {
    for (const name of ['products', 'production-items', 'adjustments', 'categories']) {
        await open(page, name, 390);
        const r = await page.evaluate(() => ({ page: document.documentElement.scrollWidth - document.documentElement.clientWidth, wrap: !!document.querySelector('.mm-table-scroll table') }));
        expect(r.page, name).toBeLessThanOrEqual(1);
        expect(r.wrap, name).toBe(true);
    }
});

test('product form stacks label above control and keeps the required fields', async ({ page }) => {
    await open(page, 'form-product-create');
    await expect(page.locator('form[action$="/rst/products/store"][data-parsley-validate]')).toHaveCount(1);
    for (const name of ['name', 'barcode', 'category_id', 'unit_id', 'supplier_id', 'sale_price']) await expect(page.locator(`form [name="${name}"]`)).toHaveCount(1);
    const g = await page.evaluate(() => { const l = document.querySelector('label[for="specification"]'); const i = document.querySelector('#product_name'); return { l: l.getBoundingClientRect(), i: i.getBoundingClientRect() }; });
    expect(g.i.top).toBeGreaterThan(g.l.top);
    expect(g.i.left).toBeLessThan(g.l.left + 5);
});

test('product edit posts as PUT with the product loaded', async ({ page }) => {
    await open(page, 'form-product-edit');
    await expect(page.locator('input[name="_method"][value="PUT"]')).toHaveCount(1);
    await expect(page.locator('input[name="name"]')).toHaveValue('Chicken <b>Biryani</b>');
});

test('uploaded product list keeps delete, confirm-list form and upload link', async ({ page }) => {
    await open(page, 'product-uploads');
    await expect(page.locator('form[action$="/rst/product/add-confirm-list"] button')).toHaveCount(1);
    await expect(page.getByRole('button', { name: /Delete All From This List/ })).toBeVisible();
    await expect(page.locator('a[href*="type=upload"]')).toHaveCount(1);
    await expect(page.locator('.mm-rst-count')).toContainText('Rows waiting');
});

test('purchase create (inventory) keeps the purchase form and totals', async ({ page }) => {
    await open(page, 'form-purchase-create');
    await expect(page.locator('form#purchase-form')).toHaveCount(1);
    for (const name of ['supplier_id', 'account_id', 'challan_id', 'subtotal', 'discount', 'grand_total']) await expect(page.locator(`form#purchase-form [name="${name}"]`)).toHaveCount(1);
});

test('purchase requisition prints through its own button and escapes data', async ({ page }) => {
    await open(page, 'purchase-show');
    await expect(page.locator('.mm-invoice-page .mm-panel')).toContainText('Basmati <b>rice</b>');
    await expect(page.locator('.mm-invoice-page .mm-panel b:text-is("rice")')).toHaveCount(0);
    await page.getByRole('link', { name: 'Print' }).click();
    expect(await page.evaluate(() => window.printCalls)).toBe(1);
});

test('stock adjustment view and edit show the document header and items', async ({ page }) => {
    await open(page, 'adjustment-view');
    await expect(page.locator('.mm-rst-adjust')).toContainText('Invoice :SA-1');
    await expect(page.locator('#purchaseTable')).toContainText('Basmati <b>rice</b>');
    await open(page, 'form-adjustment-edit');
    await expect(page.locator('form[action$="/rst/stock-adjustment/update/1"]')).toHaveCount(1);
    await expect(page.locator('#purchaseTable')).toContainText('Spoiled <b>stock</b>');
});

test('list pages never post on load', async ({ page }) => {
    for (const name of ['categories', 'products', 'adjustments']) {
        const { requests } = await open(page, name);
        await page.waitForTimeout(200);
        expect(requests.filter(r => r.method !== 'GET')).toEqual([]);
    }
});
