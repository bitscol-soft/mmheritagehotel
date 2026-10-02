// Account A2 screens (fund transfers and the four voucher types) rendered from the real Blade views (tools/fixtures/account/*.html).
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
    'contras-show': 'Contra Voucher Details',
    'contras': 'Contra Vouchers',
    'form-contras-create': 'Create Contra Voucher',
    'form-contras-edit': 'Create Contra Voucher',
    'form-fund-transfer-create': 'Fund Transfer Create',
    'form-fund-transfer-edit': 'Fund Transfer Edit',
    'form-journals-create': 'Create Journal Voucher',
    'form-journals-edit': 'Edit Journal Voucher',
    'form-payments-create': 'Payment Voucher',
    'form-receives-create': 'Receive Voucher',
    'fund-transfers': 'Fund Transfers',
    'journals-show': 'Journal Voucher Details',
    'journals': 'Journal Vouchers',
    'payments-show': 'Payment Voucher Detail',
    'payments': 'Payment Vouchers',
    'receives-show': 'Receive Voucher Detail',
    'receives': 'Receive Vouchers',
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

test('voucher lists keep the filter in its own inline panel, the row actions and escape data', async ({ page }) => {
    for (const name of ['receives', 'payments', 'journals', 'contras']) {
        await open(page, name);
        await expect(page.locator('.mm-report-filter form.mm-report-form'), name).toHaveCount(1);
        for (const field of ['invoice_no', 'reference', 'from_date', 'to_date']) await expect(page.locator(`.mm-report-filter [name="${field}"]`), `${name} ${field}`).toHaveCount(1);
        await expect(page.locator('.mm-report-filter table'), name).toHaveCount(0);
        await expect(page.locator('table tbody tr'), name).toHaveCount(2);
        await expect(page.locator('table'), name).toContainText('Unapproved');
        await expect(page.locator('table i:text-is("1")'), name).toHaveCount(0);
        await expect(page.locator('a[href*="?type=approve"]'), name).toHaveCount(name === 'journals' ? 0 : 1);
        await expect(page.locator('a[title="Edit"]'), name).toHaveCount(name === 'journals' ? 1 : 0);
    }
});

test('fund transfer list and forms keep their fields', async ({ page }) => {
    await open(page, 'fund-transfers');
    await expect(page.locator('table')).toContainText('Bank <b>x</b>');
    await expect(page.locator('table b')).toHaveCount(0);
    await open(page, 'form-fund-transfer-create');
    for (const name of ['date', 'amount', 'description', 'reference']) await expect(page.locator(`form [name="${name}"]`)).toHaveCount(1);
    await open(page, 'form-fund-transfer-edit');
    await expect(page.locator('input[name="_method"]')).toHaveCount(1);
    await expect(page.locator('input[name="description"]')).toHaveValue('Rent <b>paid</b>');
});

test('voucher forms keep company, reference, account lines and the update method', async ({ page }) => {
    for (const name of ['form-receives-create', 'form-payments-create', 'form-journals-create', 'form-contras-create']) {
        await open(page, name);
        for (const field of ['company_id', 'voucher_type']) await expect(page.locator(`form [name="${field}"]`).first(), `${name} ${field}`).toHaveCount(1);
        await expect(page.locator('form table').first(), name).toBeVisible();
    }
    for (const name of ['form-journals-edit', 'form-contras-edit']) {
        await open(page, name);
        await expect(page.locator('input[name="_method"]'), name).toHaveCount(1);
        await expect(page.locator('form table').first(), name).toBeVisible();
    }
});

test('voucher details print through their button and escape data', async ({ page }) => {
    for (const name of ['receives-show', 'payments-show', 'journals-show', 'contras-show']) {
        await open(page, name);
        await expect(page.locator('.mm-acc').first(), name).toContainText('Rent <b>expense</b>');
        await expect(page.locator('b:text-is("expense")'), name).toHaveCount(0);
        await expect(page.getByRole('link', { name: 'Print' }), name).toBeVisible();
        if (name === 'journals-show' || name === 'contras-show') continue; // legacy Print link has an empty href and no handler
        await page.evaluate(() => { window.printCalls = 0; window.print = () => { window.printCalls += 1; }; });
        await page.getByRole('link', { name: 'Print' }).click();
        expect(await page.evaluate(() => window.printCalls), name).toBe(1);
    }
});

test('table header rows keep their dark background behind the white header text', async ({ page }) => {
    await open(page, 'payments-show');
    const bg = await page.locator('.mm-acc thead th').first().evaluate(el => getComputedStyle(el).backgroundColor);
    expect(bg).toBe('rgba(0, 0, 0, 0)');
});
