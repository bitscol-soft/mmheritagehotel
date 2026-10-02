// Account A3 screens (purchases, sales, returns, collections and damages) rendered from the real Blade views (tools/fixtures/account/*.html).
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
    'purchases': 'Purchase List',
    'form-purchases-create': 'Purchase Create',
    'form-purchases-edit': 'Edit Purchase',
    'purchase-returns': 'Purchase Return List',
    'form-purchase-returns-create': 'Purchase Return Create',
    'purchase-payments': 'Payment Lists',
    'sales': 'Sale List',
    'form-sales-create': 'Sale Create',
    'form-sales-edit': 'Edit Sale',
    'sale-returns': 'Sale Return List',
    'form-sale-returns-create': 'Sale Return Create',
    'collections': 'Collection Lists',
    'damages': 'Damage List',
    'form-damages-create': 'Damage Create',
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

test('trading lists show their rows, keep row actions and escape data', async ({ page }) => {
    const lists = { purchases: ['INV-7', 'INV-8'], 'purchase-returns': ['RET-6'], sales: ['INV-7', 'INV-8'], 'sale-returns': ['RET-6'], damages: ['DMG-3'] };
    for (const [name, rows] of Object.entries(lists)) {
        await open(page, name);
        for (const row of rows) await expect(page.locator('table'), name).toContainText(row);
        await expect(page.locator('table tbody tr'), name).toHaveCount(rows.length);
        await expect(page.locator('table i:text-is("Traders"), table i:text-is("Stores")'), name).toHaveCount(0);
        await expect(page.locator('a[title="View Details"], a[title="Details"]').first(), name).toBeVisible();
    }
    await open(page, 'purchases');
    await expect(page.locator('.badge:text-is("Production")')).toHaveCount(1);
});

test('purchase payment search keeps its filter in its own inline panel', async ({ page }) => {
    await open(page, 'purchase-payments');
    await expect(page.locator('.mm-report-filter form.mm-report-form')).toHaveCount(1);
    for (const field of ['invoice_no', 'reference']) await expect(page.locator(`.mm-report-filter [name="${field}"]`)).toHaveCount(1);
    await expect(page.locator('.mm-report-filter table')).toHaveCount(0);
    await expect(page.getByRole('button', { name: 'Search' })).toBeVisible();
});

test('trade forms keep party, company, line items and totals', async ({ page }) => {
    const forms = {
        'form-purchases-create': ['supplier_id', 'company_id', 'product_id[]', 'purchase_price[]', 'quantity[]', 'qty_amount', 'discount_amount', 'paid_amount', 'due_amount'],
        'form-sales-create': ['customer_id', 'company_id', 'product_id[]', 'sale_price[]', 'quantity[]', 'qty_amount', 'discount_amount', 'paid_amount', 'due_amount'],
        'form-purchase-returns-create': ['supplier_id', 'company_id'],
        'form-sale-returns-create': ['customer_id', 'company_id'],
        'form-damages-create': ['company_id', 'date', 'total_amount'],
    };
    for (const [name, fields] of Object.entries(forms)) {
        await open(page, name);
        for (const field of fields) await expect(page.locator(`form [name="${field}"]`).first(), `${name} ${field}`).toHaveCount(1);
        await expect(page.locator('form table').first(), name).toBeVisible();
        await expect(page.locator('form button[type="submit"]').first(), name).toBeVisible();
    }
    for (const name of ['form-purchases-edit', 'form-sales-edit']) {
        await open(page, name);
        await expect(page.locator('input[name="_method"]'), name).toHaveCount(1);
        await expect(page.locator('input[name="detail_ids[]"]'), name).toHaveCount(1);
        await expect(page.locator('input[name="quantity[]"]'), name).toHaveValue('3');
    }
});

test('sale edit posts one description per line (stray duplicate textarea is gone)', async ({ page }) => {
    await open(page, 'form-sales-edit');
    await expect(page.locator('textarea[name="description[]"]')).toHaveCount(1);
    await expect(page.locator('textarea[name="description[]"]')).toHaveValue('Basmati');
});

test('empty collections page says so', async ({ page }) => {
    await open(page, 'collections');
    await expect(page.locator('.mm-acc')).toContainText('No Records Founds Yet');
});
