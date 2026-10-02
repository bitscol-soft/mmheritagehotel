// Account A1 screens (setup, party, product) rendered from the real Blade views (tools/fixtures/account/*.html).
// Script partials are not part of the fixtures (they are unchanged); frames, filters, forms and tables are real.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const html = fs.readFileSync(`tools/fixtures/account/${name}.html`, 'utf8');
    await page.route('http://mm-acc.test/**', route => {
        const url = new URL(route.request().url());
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-acc.test/acc/x');
    return { errors };
}

// page scripts that rely on globals of the real layout or on script partials that the fixtures leave out
const ignorable = e => /toastr|chosen|ace_file_input|datepicker|daterangepicker|tag is not|parsley|swal|Swal|dataTable|DataTable|delete_item|loadSelect2|reading 'length'|is not defined/i.test(e);
const screens = {
    'account-controls': 'Account Controls',
    'account-groups': 'Account Group',
    'accounts': 'Chart Of Accounts',
    'categories': 'Category List',
    'customers': 'Customers',
    'form-account-create': 'Account',
    'form-category-create': 'Category Create',
    'form-category-edit': 'Category Edit',
    'form-control-create': 'Account Controls',
    'form-control-edit': 'Account Control Edit',
    'form-customer-create': 'Customer Create',
    'form-customer-edit': 'Customer Edit',
    'form-product-create': 'Product Create',
    'form-product-edit': 'Product Edit',
    'form-subsidiary-create': 'Account Subsidiary',
    'form-supplier-create': 'Supplier Create',
    'form-supplier-edit': 'Supplier Edit',
    'form-unit-create': 'Unit Create',
    'opening-balances-data': 'Account Opening Balance',
    'opening-balances': 'Account Opening Balance',
    'products': 'Product List',
    'subsidiaries': 'Account Subsidiaries',
    'suppliers': 'Suppliers',
    'units': 'Units',
};

for (const [name, title] of Object.entries(screens)) {
    test(`${name}: shared page frame, no legacy widget, no page overflow at desktop, tablet and phone`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('.mm-page-title').first()).toContainText(title);
        await expect(page.locator('.mm-ui.mm-acc .mm-panel').first()).toBeVisible();
        await expect(page.locator('.widget-box, .widget-main, .widget-header')).toHaveCount(0);
        for (const width of [1280, 768, 390]) {
            await page.setViewportSize({ width, height: 900 });
            const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            expect(over, `${name} overflows at ${width}`).toBeLessThanOrEqual(1);
        }
        expect(errors.filter(e => !ignorable(e))).toEqual([]);
    });
}

test('setup, party and product lists keep their table and escape names', async ({ page }) => {
    const lists = { 'account-controls': 'Current <b>assets</b>', 'subsidiaries': 'Cash <b>sub</b>', 'accounts': 'Petty <b>cash</b>', 'customers': 'Customer <b>Ltd</b>', 'suppliers': 'Supplier <b>Ltd</b>', 'categories': 'Beverage <b>x</b>', 'units': 'Litre <b>x</b>', 'products': 'Juice <b>x</b>' };
    for (const [name, text] of Object.entries(lists)) {
        await open(page, name);
        await expect(page.locator('#data-table'), name).toContainText(text);
        await expect(page.locator('#data-table b'), name).toHaveCount(0);
    }
});

test('toolbar links are buttons in the page header', async ({ page }) => {
    await open(page, 'customers');
    await expect(page.locator('.mm-ui a.mm-button').first()).toBeVisible();
    await expect(page.getByRole('link', { name: 'Create' })).toBeVisible();
});

test('forms keep their fields and the update method', async ({ page }) => {
    const forms = { 'form-control-create': ['company_id', 'account_group_id', 'name'], 'form-subsidiary-create': ['account_group_id', 'account_control_id', 'name'], 'form-account-create': ['account_group_id', 'account_control_id', 'account_subsidiary_id', 'name'],
        'form-customer-create': ['name', 'mobile', 'email', 'address'], 'form-supplier-create': ['name', 'mobile', 'email', 'address'], 'form-category-create': ['name'], 'form-unit-create': ['name'], 'form-product-create': ['name', 'category_id', 'unit_id', 'purchase_price', 'selling_price'] };
    for (const [name, fields] of Object.entries(forms)) {
        await open(page, name);
        for (const field of fields) await expect(page.locator(`form [name="${field}"]`).first(), `${name} ${field}`).toHaveCount(1);
        await expect(page.locator('form button, form input[type="submit"]').first(), name).toBeVisible();
    }
    for (const name of ['form-control-edit', 'form-customer-edit', 'form-supplier-edit', 'form-category-edit', 'form-product-edit']) {
        await open(page, name);
        await expect(page.locator('input[name="_method"]'), name).toHaveCount(1);
    }
    await open(page, 'form-customer-edit');
    await expect(page.locator('input[name="name"]')).toHaveValue('Customer <b>Ltd</b>');
});

test('opening balance filter is an inline panel and the balances form keeps its inputs', async ({ page }) => {
    await open(page, 'opening-balances');
    await expect(page.locator('.mm-report-filter form.mm-report-form')).toHaveCount(1);
    for (const field of ['company_id', 'account_group_id', 'account_control_id']) await expect(page.locator(`.mm-report-filter [name="${field}"]`)).toHaveCount(1);
    await expect(page.locator('body')).not.toContainText(')');
    await open(page, 'opening-balances-data');
    await expect(page.locator('input[name="account_ids[]"]')).toHaveCount(1);
    await expect(page.locator('input[name="amounts[]"]')).toHaveCount(1);
    await expect(page.locator('form[action*="account-opening-balances/store"]')).toHaveCount(1);
});

test('forms stack on a phone without clipping the fields', async ({ page }) => {
    for (const name of ['form-account-create', 'form-product-create', 'form-customer-create', 'opening-balances']) {
        await open(page, name, 390);
        const bad = await page.evaluate(() => [...document.querySelectorAll('.mm-acc form .form-control')].filter(e => { const r = e.getBoundingClientRect(); return r.width > 0 && (r.left < 0 || r.right > innerWidth + 1); }).length);
        expect(bad, name).toBe(0);
    }
});
