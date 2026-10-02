// Account A4 screens (reports) rendered from the real Blade views (tools/fixtures/account/*.html).
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
    'account-ledger': 'Account Ledger',
    'account-payables': 'Account Payable',
    'account-receivables': 'Account Receivable',
    'balance-sheet': 'Balance Sheet',
    'cash-flow': 'Cash Flow Report',
    'chart-of-account': 'Chart Of Account',
    'customer-ledger': 'Customer Ledger',
    'equity-statement': 'Equity Statement',
    'expense-analysis': 'Expense Analysis',
    'income-statement': 'Income  Statement',
    'journal-report': 'Ledger Journal',
    'ratio-analysis': 'Ratio Analysis',
    'received-payment-statement': 'Received Payment Statement',
    'revenue-analysis': 'Revenue Analysis',
    'subsidiary-wise-ledger': 'Subsidiary Wise Ledger',
    'supplier-ledger': 'Supplier Ledger',
    'supplier-report': 'Supplier Report',
    'trial-balance': 'Trial Balance',
    'voucher-reports': 'Voucer Reports',
    'nominal-account-ledger': 'Nominal Account Ledger',
    'transaction-ledger': 'Transaction Ledger',
    'stock-in-hand': 'Product Stock In Hand',
    'item-ledger': 'Item Ledger',
};

for (const [name, title] of Object.entries(screens)) {
    test(`${name}: shared page frame, no legacy widget, no page overflow at desktop, tablet and phone`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('.mm-page-title').first()).toContainText(title);
        await expect(page.locator('.mm-ui.mm-acc .mm-panel').first()).toBeVisible();
        await expect(page.locator('.widget-box, .widget-main, .widget-header, .page-header')).toHaveCount(0);
        for (const width of [1280, 768, 390]) {
            await page.setViewportSize({ width, height: 900 });
            const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            expect(over, `${name} overflows at ${width}`).toBeLessThanOrEqual(1);
        }
        expect(errors.filter(e => !ignorable(e))).toEqual([]);
    });
}

test('report filters are their own inline panel and keep their fields', async ({ page }) => {
    const filters = {
        'account-ledger': ['company_id', 'account_id', 'from', 'to'],
        'account-payables': ['company_id', 'account_id'],
        'account-receivables': ['company_id', 'account_id'],
        'customer-ledger': ['company_id'],
        'supplier-ledger': ['company_id'],
        'journal-report': ['company_id'],
        'ratio-analysis': ['from', 'to'],
        'revenue-analysis': ['from', 'to'],
        'nominal-account-ledger': ['from', 'to'],
        'income-statement': ['company_id[]', 'month'],
        'stock-in-hand': ['company_id', 'unit_id', 'product_id'],
        'item-ledger': ['company_id', 'from_date', 'to_date', 'product_id'],
    };
    for (const [name, fields] of Object.entries(filters)) {
        await open(page, name);
        await expect(page.locator('.mm-report-filter').first(), name).toBeVisible();
        for (const field of fields) await expect(page.locator(`.mm-report-filter [name="${field}"]`).first(), `${name} ${field}`).toHaveCount(1);
        await expect(page.locator('.mm-report-filter table'), name).toHaveCount(0);
    }
});

test('ledger reports show their rows and escape data', async ({ page }) => {
    const rows = { 'account-ledger': 'Rent <b>paid</b>', 'journal-report': null, 'revenue-analysis': 'Rent <b>expense</b>', 'received-payment-statement': 'Paid <b>cash</b>', 'voucher-reports': 'Rent <b>paid</b>', 'nominal-account-ledger': 'Rent <b>expense</b>', 'supplier-report': 'Karim <i>Traders</i>', 'account-payables': 'Karim <i>Traders</i>', 'account-receivables': 'Rahim <i>Stores</i>', 'stock-in-hand': 'Rice <b>50kg</b>', 'ratio-analysis': 'Current <b>ratio</b>' };
    for (const [name, text] of Object.entries(rows)) {
        if (!text) continue;
        await open(page, name);
        await expect(page.locator('table').first(), name).toContainText(text);
        await expect(page.locator('table b:text-is("paid"), table b:text-is("expense"), table b:text-is("cash"), table i:text-is("Traders"), table i:text-is("Stores"), table b:text-is("50kg"), table b:text-is("ratio")'), name).toHaveCount(0);
    }
});

test('report toolbars keep Print and Refresh as buttons', async ({ page }) => {
    for (const name of ['account-ledger', 'account-payables', 'balance-sheet', 'trial-balance', 'cash-flow']) {
        await open(page, name);
        await expect(page.getByRole('link', { name: 'Print' }).first(), name).toBeVisible();
    }
    await open(page, 'income-statement');
    await page.evaluate(() => { window.printCalls = 0; window.print = () => { window.printCalls += 1; }; });
    await page.getByRole('link', { name: 'Print' }).first().click();
    expect(await page.evaluate(() => window.printCalls)).toBe(1);
});

test('report tables scroll inside their own container instead of widening the page', async ({ page }) => {
    for (const name of ['account-ledger', 'trial-balance', 'balance-sheet', 'item-ledger', 'stock-in-hand', 'voucher-reports']) {
        await open(page, name, 390);
        const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
        expect(over, name).toBeLessThanOrEqual(1);
    }
});
