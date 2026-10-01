// Restaurant screens rendered from the real Blade views (tools/fixtures/restaurant/*.html):
// tables, kitchen list/board/ticket, night audit list and generate form, payment collection and the five reports.
// The shared export partials of the reports are stubbed in the fixtures (they are unchanged); the frame and filters are real.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const requests = [];
    const html = fs.readFileSync(`tools/fixtures/restaurant/${name}.html`, 'utf8');
    await page.addInitScript(() => { window.printCalls = 0; window.print = () => { window.printCalls += 1; }; });
    await page.route('http://mm-rst.test/**', route => {
        const request = route.request();
        const url = new URL(request.url());
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        requests.push({ method: request.method(), path: url.pathname, post: request.postData() });
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-rst.test/rst/x');
    requests.length = 0;
    return { errors, requests };
}

const ignorable = e => /toastr|chosen|ace_file_input|datepicker|daterangepicker|tag is not|parsley|swal|Swal|dataTable|DataTable/i.test(e);
const screens = {
    'tables': 'Tables',
    'kitchen-list': 'Kitchen orders',
    'kitchen-board': 'Kitchen board',
    'kitchen-show': 'Order details',
    'audit-list': 'Restaurant night audit',
    'audit-generate': 'Generate restaurant night audit',
    'payment-collection': 'Restaurant payment collection',
    'sales-list': 'Sale list',
    'sales-show': 'Sale invoice',
    'sales-create': 'New sale',
    'return-list': 'Sale return list',
    'return-show': 'Sale return details',
    'return-create': 'New sale return',
    'report-cash-flow': 'Cash flow',
    'report-sales': 'Sales report',
    'report-today': "Today's activities",
    'report-inventory': 'Product inventory',
    'report-ledger': 'Stock ledger',
};

for (const [name, title] of Object.entries(screens)) {
    test(`${name} renders in the shared frame without script errors`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('h1')).toHaveText(title);
        await expect(page.locator('.mm-page-title')).toHaveCount(1);
        await expect(page.locator('.widget-box, .widget-main, .widget-header, .page-header')).toHaveCount(0);
        expect(errors.filter(e => !ignorable(e))).toEqual([]);
    });
    for (const width of [360, 768]) {
        test(`${name} does not scroll sideways at ${width}px`, async ({ page }) => {
            await open(page, name, width);
            const { scroll, client } = await page.evaluate(() => ({ scroll: document.documentElement.scrollWidth, client: document.documentElement.clientWidth }));
            expect(scroll).toBeLessThanOrEqual(client);
        });
    }
}

test('tables lists the tables with escaped names and row actions', async ({ page }) => {
    await open(page, 'tables');
    await expect(page.locator('#data-table tbody tr')).toHaveCount(2);
    await expect(page.locator('#data-table')).toContainText('Terrace <b>1</b>');
    await expect(page.locator('#data-table b')).toHaveCount(0);
    await expect(page.locator('a[data-toggle="modal"]').first()).toBeVisible();
    await expect(page.locator('.mm-hotel-setup.mm-rst')).toHaveCount(1);
});

test('kitchen list shows the orders', async ({ page }) => {
    await open(page, 'kitchen-list');
    await expect(page.locator('#data-table tbody tr')).toHaveCount(4);
    await expect(page.locator('#data-table')).toContainText('R-0101');
});

test('kitchen board shows one card per open order and posts the next status', async ({ page }) => {
    const { requests } = await open(page, 'kitchen-board');
    await expect(page.locator('.mm-board-card')).toHaveCount(3);
    await expect(page.locator('.mm-board-card').first()).toContainText('Biryani <i>hot</i>');
    await expect(page.locator('.mm-board-card i.fa-arrow-right, .mm-board-card b i')).not.toHaveCount(0);
    await page.evaluate(() => document.querySelectorAll('.mm-board-card form').forEach(f => { f.action = new URL(f.getAttribute('action')).pathname; }));
    const types = await page.locator('.mm-board-card form input[name="type"]').evaluateAll(els => els.map(e => e.value));
    expect(types).toEqual(['Cooking', 'Ready', 'Complete']);
    await page.locator('.mm-board-card').first().locator('form button').click();
    await expect.poll(() => requests.find(r => r.method === 'POST')).toMatchObject({ path: '/kitchen/update-status/1' });
    expect(requests.find(r => r.method === 'POST').post).toContain('type=Cooking');
});

test('kitchen board puts the order list beside the cards on wide screens and below them on narrow ones', async ({ page }) => {
    await open(page, 'kitchen-board', 1280);
    const wide = await page.evaluate(() => ({ cards: document.querySelector('.mm-board-orders').getBoundingClientRect(), list: document.querySelector('.mm-board-list').getBoundingClientRect() }));
    expect(wide.list.left).toBeGreaterThan(wide.cards.right - 1);
    await page.setViewportSize({ width: 768, height: 900 });
    const narrow = await page.evaluate(() => ({ cards: document.querySelector('.mm-board-orders').getBoundingClientRect(), list: document.querySelector('.mm-board-list').getBoundingClientRect() }));
    expect(narrow.list.top).toBeGreaterThan(narrow.cards.bottom - 1);
});

test('kitchen ticket keeps the printable body and prints on request', async ({ page }) => {
    await open(page, 'kitchen-show');
    await expect(page.locator('#print_body')).toContainText('R-0101');
    await expect(page.locator('#print_body')).toContainText('Biryani <i>hot</i>');
    await expect(page.locator('.mm-invoice-page.mm-rst')).toHaveCount(1);
});

test('night audit list shows the audit days and links to Generate', async ({ page }) => {
    await open(page, 'audit-list');
    await expect(page.locator('#data-table tbody tr').first()).toBeVisible();
    await expect(page.getByRole('link', { name: 'Generate' })).toHaveAttribute('href', /rst\/night-audits\/create/);
    await expect(page.locator('a[title="View Details"]').first()).toBeVisible();
});

test('night audit generate keeps its fields, totals and readonly figures', async ({ page }) => {
    await open(page, 'audit-generate');
    await expect(page.locator('input[name="date"]')).toHaveValue('2026-10-01');
    await expect(page.locator('input[name="total_amount"]')).toHaveValue('1400');
    await expect(page.locator('input[name="collection"]')).toHaveValue('900');
    await expect(page.locator('input[name="due_amount"]')).toHaveValue('500');
    for (const name of ['total_amount', 'collection', 'due_amount']) expect(await page.locator(`input[name="${name}"]`).getAttribute('readonly')).not.toBeNull();
    for (const name of ['transaction_ids[21]', 'collections[21]', 'due_amounts[22]', 'payment_way[Cash]', 'payment_way[Card]']) await expect(page.locator(`[name="${name}"]`)).toHaveCount(1);
    expect(await page.locator('#formSubmit').getAttribute('action')).toMatch(/rst\/night-audits$/);
    await expect(page.locator('.mm-night-audit.mm-rst')).toHaveCount(1);
});

test('payment collection shows the guest, invoices and the due total', async ({ page }) => {
    await open(page, 'payment-collection');
    await expect(page.locator('.payable-amount')).toContainText('3,500.00');
    expect(await page.locator('#get-due').inputValue()).toBe('3500');
    await expect(page.locator('select[name="company_id"]')).toHaveCount(0);
    await expect(page.locator('select[name="hotel_guest_id"]')).toHaveCount(1);
    await expect(page.locator('.mm-payment-collection')).toContainText('a@example.com');
    const paid = page.locator('input[name="total_paid_amount"]');
    await paid.fill('1000');
    await expect(page.locator('.current-due')).toHaveText('2500');
});

for (const name of ['report-cash-flow', 'report-sales', 'report-today']) {
    test(`${name} filters sit in one panel above the results`, async ({ page }) => {
        await open(page, name);
        await expect(page.locator('.mm-report-filter form.mm-report-form')).toHaveCount(1);
        const { filter, table } = await page.evaluate(() => ({ filter: document.querySelector('.mm-report-filter').getBoundingClientRect(), table: document.querySelector('table').getBoundingClientRect() }));
        expect(table.top).toBeGreaterThan(filter.bottom - 1);
        await expect(page.locator('.mm-report-filter button[type="submit"]')).toBeVisible();
    });
}

test('sales report keeps the outdoor sale checkbox', async ({ page }) => {
    await open(page, 'report-sales');
    await expect(page.locator('input[name="outdoor_sale"]')).toHaveCount(1);
    await expect(page.locator('.mm-report-check')).toContainText('Outdoor sale');
});

for (const name of ['report-inventory', 'report-ledger']) {
    test(`${name} lays the shared Bar filter out in a row`, async ({ page }) => {
        await open(page, name);
        const boxes = await page.evaluate(() => ['id', 'category_id', 'from_date'].map(n => { const r = document.querySelector(`[name="${n}"]`).getBoundingClientRect(); return { top: Math.round(r.top), left: Math.round(r.left), width: Math.round(r.width) }; }));
        expect(Math.abs(boxes[0].top - boxes[1].top)).toBeLessThan(4);
        expect(boxes[1].left).toBeGreaterThan(boxes[0].left);
        for (const b of boxes) expect(b.width).toBeGreaterThan(60);
    });
}

test('sale list keeps the filters, invoice actions and delete hook', async ({ page }) => {
    await open(page, 'sales-list');
    await expect(page.locator('.mm-report-filter form.mm-report-form')).toHaveCount(1);
    await expect(page.locator('#datatable tbody tr')).toHaveCount(2);
    await expect(page.locator('#datatable a[href*="invoice_type=pos"]').first()).toHaveAttribute('target', '_blank');
    await expect(page.getByRole('link', { name: 'Add New' })).toBeVisible();
    await expect(page.locator('#datatable')).toContainText('Aisha <b>x</b>');
    await expect(page.locator('#datatable b')).toHaveCount(0);
});

test('sale invoice keeps the printable body and escapes guest data', async ({ page }) => {
    await open(page, 'sales-show');
    await expect(page.locator('#print_body')).toContainText('INV-R-0101');
    await expect(page.locator('#print_body')).toContainText('Biryani <i>hot</i>');
    await expect(page.getByRole('link', { name: 'Print' })).toBeVisible();
});

test('new sale shows guest and invoice, items and totals in separate panels', async ({ page }) => {
    await open(page, 'sales-create');
    await expect(page.locator('form.sales-form .mm-panel')).toHaveCount(3);
    await expect(page.locator('input[name="invoice_no"]')).toHaveValue('R-0105');
    await expect(page.locator('input[name="payment_way"]')).toHaveCount(2);
    for (const name of ['subtotal', 'total_amount', 'grand_total', 'change_amount', 'due_amount']) expect(await page.locator(`input[name="${name}"]`).getAttribute('readonly')).not.toBeNull();
    const boxes = await page.evaluate(() => ['guest_name', 'room_number', 'booking_number'].map(n => Math.round(document.querySelector(`form.sales-form [name="${n}"]`).getBoundingClientRect().top)));
    expect(Math.abs(boxes[0] - boxes[1])).toBeLessThan(4);
    await page.locator('[data-target="#add-guest-modal"]').click();
    await expect(page.locator('#add-guest-modal')).toBeVisible();
});

test('new sale return shows guest and invoice, returned items and totals', async ({ page }) => {
    await open(page, 'return-create');
    await expect(page.locator('form.sales-form .mm-panel')).toHaveCount(3);
    await expect(page.locator('input[name="customer_id"]')).toHaveCount(1);
    await expect(page.locator('#table_auto thead th')).toHaveCount(7);
    for (const name of ['subtotal', 'previous_due', 'payable_amount', 'due_amount']) await expect(page.locator(`input[name="${name}"]`)).toHaveCount(1);
});
