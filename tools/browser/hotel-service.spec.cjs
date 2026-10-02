// Hotel Service screens rendered from the real Blade views (tools/fixtures/hotel-service/*.html):
// service list with its modals, sales list with the due-payment modal, new sale, sale invoice, night audit list and printable night audit.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const printStub = "jQuery.fn.printThis = function () { window.printed = (window.printed || []).concat(this.attr('id')); return this; };";

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const requests = [];
    const html = fs.readFileSync(`tools/fixtures/hotel-service/${name}.html`, 'utf8');
    await page.addInitScript(() => { window.printCalls = 0; window.print = () => { window.printCalls += 1; }; });
    await page.route('http://mm-hs.test/**', route => {
        const request = route.request();
        const url = new URL(request.url());
        if (url.pathname === '/assets/custom_js/printThis.js') return route.fulfill({ contentType: 'text/javascript', body: printStub });
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        requests.push({ method: request.method(), path: url.pathname, params: url.searchParams, post: request.postData() });
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-hs.test/hotelservice/x');
    requests.length = 0;
    return { errors, requests };
}

const ignorable = e => /toastr|chosen|ace_file_input|datepicker|daterangepicker|tag is not|parsley|swal|Swal/i.test(e);
const screens = { services: 'Hotel services', sales: 'Hotel service sales', 'sale-create': 'New hotel service sale', 'sale-show': 'Hotel service invoice', audits: 'Hotel service night audit' };

for (const [name, title] of Object.entries(screens)) {
    test(`${name} renders in the shared frame without script errors`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('h1')).toHaveText(title);
        await expect(page.locator('.mm-page-title')).toHaveCount(1);
        await expect(page.locator('.widget-box, .widget-main, .widget-header')).toHaveCount(0);
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

test('services: add and edit open as modals with the original fields and a PUT for edits', async ({ page }) => {
    await open(page, 'services');
    await expect(page.locator('#data-table tbody tr')).toHaveCount(2);
    await expect(page.locator('#data-table')).toContainText('Laundry <b>x</b>');
    await page.getByRole('link', { name: /Add New Service/ }).click();
    const add = page.locator('#modal-dialog');
    await expect(add).toBeVisible();
    await expect(add.locator('[name=name]')).toBeVisible();
    await expect(add.locator('[name=price]')).toBeVisible();
    await expect(add.locator('button[type=submit]')).toBeVisible();
    await add.locator('[data-dismiss=modal]').last().click();
    await expect(add).toBeHidden();
    await page.locator('a[href="#modal-dialog2"]').click();
    const edit = page.locator('#modal-dialog2');
    await expect(edit).toBeVisible();
    await expect(edit.locator('[name=_method]')).toHaveValue('PUT');
    await expect(edit.locator('[name=name]')).toHaveValue('Airport pickup');
    await expect(edit.locator('[name=price]')).toHaveValue('1500');
});

test('services: delete keeps the confirm-delete hook and the modal is not clipped by the table scroller', async ({ page }) => {
    await open(page, 'services');
    await expect(page.locator('button[onclick^="delete_item("]')).toHaveCount(2);
    await page.getByRole('link', { name: /Add New Service/ }).click();
    await expect(page.locator('#modal-dialog')).toBeVisible();
    await expect.poll(async () => (await page.locator('#modal-dialog .modal-content').boundingBox()).width).toBeGreaterThan(300);
    expect((await page.locator('#modal-dialog .modal-content').boundingBox()).x).toBeGreaterThanOrEqual(0);
});

test('sales: filter posts invoice and guest id as GET fields', async ({ page }) => {
    const { requests } = await open(page, 'sales');
    await page.evaluate(() => { document.querySelector('.mm-hs-filter-form').setAttribute('action', '/hotelservice/service-sales'); });
    await page.fill('[name=customer_id]', '12');
    await page.locator('.mm-hs-filter-form button[type=submit]').click();
    await expect.poll(() => requests.length).toBe(1);
    expect(requests[0].method).toBe('GET');
    expect(requests[0].params.get('invoice_no')).toBe('0301');
    expect(requests[0].params.get('customer_id')).toBe('12');
});

test('sales: lists totals and offers payment only where money is due', async ({ page }) => {
    await open(page, 'sales');
    await expect(page.locator('tbody tr')).toHaveCount(3);
    await expect(page.locator('tbody')).toContainText('2,200.00');
    await expect(page.locator('tbody')).toContainText('500.00');
    await expect(page.locator('tbody td', { hasText: 'PAID' })).toHaveCount(1);
    await expect(page.locator('a[onclick^="payment("]')).toHaveCount(1);
    await expect(page.locator('button[onclick^="delete_item("]')).toHaveCount(2);
});

test('sales: due payment modal fills the due, recalculates and never goes below zero', async ({ page }) => {
    await open(page, 'sales');
    await page.locator('a[onclick^="payment("]').click();
    await expect(page.locator('#exampleModal')).toBeVisible();
    await expect(page.locator('#previous-due')).toHaveValue('500');
    expect(await page.locator('#payment-form').getAttribute('action')).toContain('/hotelservice/service-due-receive/2');
    await expect(page.locator('#payment-form [name=_method]')).toHaveValue('PUT');
    await page.locator('#payable-amount').pressSequentially('200');
    await expect(page.locator('#current-due')).toHaveValue('300');
    await page.locator('#payable-amount').fill('');
    await page.locator('#payable-amount').pressSequentially('600');
    await expect(page.locator('#payable-amount')).toHaveValue('0');
    await expect(page.locator('#current-due')).toHaveValue('500');
});

test('new sale: rows, totals, discount and paid amount calculate with the original script', async ({ page }) => {
    await open(page, 'sale-create');
    // the page script adds the first row on load
    await expect(page.locator('#table_auto tbody tr.repeat-group')).toHaveCount(1);
    await page.locator('.r-btnAdd').click();
    await expect(page.locator('#table_auto tbody tr.repeat-group')).toHaveCount(2);
    await page.evaluate(() => { $('.service-prices').eq(0).val(350); $('.service-prices').eq(1).val(500); });
    await page.locator('.service-quantity').nth(0).pressSequentially('2');
    await expect(page.locator('#subTotal')).toHaveValue('700');
    await page.locator('.service-quantity').nth(1).pressSequentially('1');
    await expect(page.locator('#subTotal')).toHaveValue('1200');
    await page.locator('#discount').fill('');
    await page.locator('#discount').pressSequentially('100');
    await expect(page.locator('#payable_amount')).toHaveValue('1100');
    await page.locator('#amountPaid').fill('');
    await page.locator('#amountPaid').pressSequentially('250');
    await expect(page.locator('#amountDue')).toHaveValue('850');
    await page.locator('.r-btnRemove').first().click();
    await expect(page.locator('#table_auto tbody tr.repeat-group')).toHaveCount(1);
});

test('new sale: Confirm validates the guest and services, then posts the form once', async ({ page }) => {
    const { requests, errors } = await open(page, 'sale-create');
    await page.evaluate(() => { document.querySelector('#invForm').setAttribute('action', '/hotelservice/service-sales'); });
    await page.getByRole('button', { name: 'Confirm' }).click();
    expect(await page.evaluate(() => window.warnings)).toEqual(['Please select Guest Type!']);
    expect(requests.length).toBe(0);
    await page.fill('#guest_name', 'Rahim');
    await page.getByRole('button', { name: 'Confirm' }).click();
    expect(await page.evaluate(() => window.warnings)).toEqual(['Please select Guest Type!', 'Please choose service !']);
    expect(requests.length).toBe(0);
    await page.evaluate(() => { $('.service-ids').val(3); $('.service-prices').val(350); });
    await page.locator('.service-quantity').pressSequentially('2');
    await page.getByRole('button', { name: 'Confirm' }).click();
    await expect.poll(() => requests.length).toBe(1);
    expect(requests[0].method).toBe('POST');
    const body = new URLSearchParams(requests[0].post);
    expect(body.get('guest_name')).toBe('Rahim');
    expect(body.getAll('service_id[]')).toEqual(['3']);
    expect(body.getAll('quantity[]')).toEqual(['2']);
    expect(body.get('subtotal')).toBe('700');
    expect(body.get('payable_amount')).toBe('700');
    expect(errors.filter(e => !ignorable(e))).toEqual([]);
});

test('new sale: fields stack on a phone and the service table scrolls inside its panel', async ({ page }) => {
    await open(page, 'sale-create', 360);
    for (const sel of ['#guest_name', '#room_number', '#booking_number', '#invoice_id', '#amountPaid']) {
        const box = await page.locator(sel).boundingBox();
        expect(box.x, sel).toBeGreaterThanOrEqual(0);
        expect(box.x + box.width, sel).toBeLessThanOrEqual(360);
    }
    await expect(page.locator('.mm-table-scroll')).toHaveAttribute('role', 'region');
    const confirm = await page.getByRole('button', { name: 'Confirm' }).boundingBox();
    expect(confirm.x + confirm.width).toBeLessThanOrEqual(360);
});

test('sale invoice keeps the document, prints it only and hides the page actions in print', async ({ page }) => {
    const { errors } = await open(page, 'sale-show');
    await expect(page.locator('#print_body')).toContainText('0301');
    await expect(page.locator('#print_body')).toContainText('Laundry');
    await expect(page.locator('#print_body')).toContainText('700.00');
    await expect(page.locator('#print_body')).toContainText('Taka 1,100 only');
    await expect(page.locator('#print_body')).toContainText('Rahim <b>Uddin</b>');
    await expect.poll(() => page.evaluate(() => window.printed || [])).toEqual(['print_body']);
    await page.getByRole('link', { name: 'Print' }).click();
    expect(await page.evaluate(() => window.printed)).toEqual(['print_body', 'print_body']);
    await page.emulateMedia({ media: 'print' });
    await expect(page.locator('#print_body')).toBeVisible();
    await expect(page.getByRole('link', { name: 'Print' })).toBeHidden();
    expect(errors.filter(e => !ignorable(e))).toEqual([]);
});

test('night audit list totals each day and opens the details modal', async ({ page }) => {
    await open(page, 'audits');
    await expect(page.locator('#data-table tbody tr')).toHaveCount(2);
    await expect(page.locator('#data-table tfoot')).toContainText('Total');
    await page.locator('a[data-target="#my_Modal9"]').click();
    const modal = page.locator('#my_Modal9');
    await expect(modal).toBeVisible();
    await expect(modal).toContainText('INV-0301');
    await expect(modal).toContainText('Total Collection');
    await expect(page.locator('#data-table a[target=_blank]')).toHaveCount(2);
});

test('night audit filter submits the date range as GET fields', async ({ page }) => {
    const { requests } = await open(page, 'audits');
    await page.evaluate(() => { document.querySelector('.mm-report-filter form').setAttribute('action', '/hotelservice/night-audit'); });
    await page.locator('.mm-report-filter button[type=submit]').click();
    await expect.poll(() => requests.length).toBe(1);
    expect(requests[0].method).toBe('GET');
    expect([...requests[0].params.keys()]).toEqual(expect.arrayContaining(['from_date', 'to_date']));
});

test('printable night audit gains a screen-only bar that is hidden when printing', async ({ page }) => {
    const { errors } = await open(page, 'audit-invoice');
    const bar = page.locator('.inv-screen-bar');
    await expect(bar).toBeVisible();
    await expect(bar.getByRole('link')).toHaveAttribute('href', /night-audit$/);
    await bar.getByRole('button', { name: 'Print again' }).click();
    expect(await page.evaluate(() => window.printCalls)).toBeGreaterThanOrEqual(2);
    await page.emulateMedia({ media: 'print' });
    await expect(bar).toBeHidden();
    await expect(page.locator('body')).toContainText('INV-0301');
    expect(errors.filter(e => !ignorable(e))).toEqual([]);
});
