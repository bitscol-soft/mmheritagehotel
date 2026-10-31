// Night audit: the real rendered list (night-audit-index.html) and generate form (night-audit-create.html).
// Generate submits once, immediately, behind a loading overlay; a second click must not schedule another
// submit (the legacy build deferred the submit by 10 seconds and double clicks scheduled two submits).
// Close is a plain button that leaves the form — it must never submit the store form.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const fixtures = { index: fs.readFileSync('tools/fixtures/night-audit-index.html', 'utf8'), create: fs.readFileSync('tools/fixtures/night-audit-create.html', 'utf8') };

async function open(page, mode, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const posts = [];
    await page.route('http://mm-audit.test/**', route => {
        const request = route.request();
        const pathname = new URL(request.url()).pathname;
        if (pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        // 204 keeps the current page so overlay state stays observable after the submit
        if (request.method() === 'POST') { posts.push(request.postData() || ''); return route.fulfill({ status: 204, body: '' }); }
        return route.fulfill({ contentType: 'text/html', body: fixtures[mode] });
    });
    await page.goto('http://mm-audit.test/hotel/night-audits' + (mode === 'create' ? '/create?from_date=2026-10-01' : ''));
    // the fixture form posts to the rendered absolute route; keep that action but route it to the test host
    await page.evaluate(() => { const form = document.querySelector('#formSubmit'); if (form) form.action = new URL(form.getAttribute('action')).pathname; });
    return { errors, posts };
}

test('list renders audit days, totals and actions', async ({ page }) => {
    const { errors } = await open(page, 'index');
    await expect(page.locator('h1')).toHaveText('Night audit');
    await expect(page.locator('#data-table tbody tr')).toHaveCount(2);
    await expect(page.locator('#data-table tfoot')).toContainText('9,400.00');
    await expect(page.getByRole('link', { name: 'Generate' })).toBeVisible();
    await expect(page.locator('a[title=\"View Details\"]').first()).toHaveAttribute('href', /night-audits\/2026-09-30$/);
    expect(errors).toEqual([]);
});

test('generate form shows the day, ledger groups and totals', async ({ page }) => {
    const { errors } = await open(page, 'create');
    await expect(page.locator('h1')).toHaveText('Generate night audit');
    await expect(page.locator('input[name=\"date\"]')).toHaveValue('2026-10-01');
    await expect(page.locator('input[name=\"total_room\"]')).toHaveValue('32');
    await expect(page.locator('.mm-co-charges')).toHaveCount(2);
    await expect(page.locator('input[name=\"total_amount\"]')).toHaveValue('5300');
    await expect(page.locator('input[name=\"collection\"]')).toHaveValue('5300');
    await expect(page.locator('input[name=\"due_amount\"]')).toHaveValue('2000');
    // the readonly figures stay readonly
    for (const name of ['total_room', 'total_amount', 'collection', 'due_amount']) expect(await page.locator(`input[name=\"${name}\"]`).getAttribute('readonly')).not.toBeNull();
    expect(errors).toEqual([]);
});

test('Generate submits exactly once with every field, even when clicked twice', async ({ page }) => {
    const { errors, posts } = await open(page, 'create');
    const btn = page.getByRole('button', { name: 'Generate' });
    // double click: the handler is one-shot and disables the button, so only one submit may land
    await btn.click({ clickCount: 2, delay: 20 });
    expect(await page.evaluate(() => window.overlay)).toEqual(['show']);
    await expect.poll(() => posts.length).toBe(1);
    await page.waitForTimeout(250);
    expect(posts).toHaveLength(1);
    const body = new URLSearchParams(posts[0]);
    for (const name of ['_token', 'date', 'total_reservation', 'total_booked_room', 'total_check_in', 'total_check_out', 'total_room', 'total_cancelled', 'total_dirty_room', 'total_room_maintenance',
        'transaction_ids[11]', 'transaction_ledger_ids[11]', 'total_amounts[11]', 'collections[11]', 'due_amounts[11]', 'total_amount', 'collection', 'due_amount',
        // every row's previous_paid posts under its transaction id (the legacy single
        // name="previous_paid" input meant only the last row reached the server)
        'previous_paid[11]', 'previous_paid[12]', 'previous_paid[21]']) {
        expect(body.has(name), name).toBe(true);
    }
    expect(errors).toEqual([]);
});

test('Close is a plain button that never submits the store form', async ({ page }) => {
    const { posts } = await open(page, 'create');
    const close = page.locator('.mm-form-actions button').filter({ hasText: 'Close' });
    await expect(close).toHaveCount(1);
    // legacy Close had no type, so it acted as an implicit submit on the store form
    await expect(close).toHaveAttribute('type', 'button');
    await expect(close).toHaveAttribute('onclick', /night-audits/);
    await expect(page.locator('#formSubmit')).toHaveCount(1);
    expect(posts).toHaveLength(0);
});

for (const mode of ['index', 'create']) {
    for (const width of [360, 768, 1280]) {
        test(`${mode} does not scroll sideways at ${width}px`, async ({ page }) => {
            await open(page, mode, width);
            const { scroll, client } = await page.evaluate(() => ({ scroll: document.documentElement.scrollWidth, client: document.documentElement.clientWidth }));
            expect(scroll).toBeLessThanOrEqual(client);
        });
    }
}
