// Check-in / check-out fields of the booking create and edit forms: real rendered partials, real jQuery,
// bootstrap-datepicker and date-picker.js, plus stay-dates.js. The legacy night calculation and submit are stubbed.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const fixtures = { create: fs.readFileSync('tools/fixtures/booking-add-dates.html', 'utf8'), edit: fs.readFileSync('tools/fixtures/booking-edit-dates.html', 'utf8') };
const html = (mode) => `<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/assets/css/bootstrap.min.css"><link rel="stylesheet" href="/assets/css/ace.min.css"><link rel="stylesheet" href="/assets/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="/assets/css/bootstrap-datepicker3.min.css"><link rel="stylesheet" href="/assets/custom_css/ui.css"><link rel="stylesheet" href="/assets/custom_css/shell.css"></head>
<body class="no-skin mm-shell"><section class="mm-ui mm-booking-next"><div class="mm-panel tw-p-4 sm:tw-p-6"><form class="form-horizontal" id="submitBookingUpdateForm"><div class="row">${fixtures[mode]}</div></form>
<div class="mm-form-actions"><button type="button" class="updateBookingBtn mm-button" onclick="submitBookingForm()">Save</button></div></div></section>
<script src="/assets/js/jquery-2.1.4.min.js"></script><script src="/assets/js/bootstrap.min.js"></script><script src="/assets/js/ace-elements.min.js"></script><script src="/assets/js/ace.min.js"></script>
<script src="/assets/js/bootstrap-datepicker.min.js"></script><script src="/assets/js/bootstrap-timepicker.min.js"></script>
<script>
window.nightsSeen = []; window.legacySubmits = 0;
$(document).on('change', '.check_out, .checkOut', function () { window.nightsSeen.push($(this).val()); });
function submitBookingForm() { window.legacySubmits++; }
</script>
<script src="/assets/custom_js/date-picker.js"></script>
<script>$('.checkOut').datepicker({ autoclose: true, format: 'yyyy-mm-dd', todayHighlight: true, startDate: $('.expectedCheckoutDate').val() });</script>
<script src="/assets/custom_js/stay-dates.js"></script></body></html>`;

async function open(page, mode, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.route('http://mm-dates.test/**', route => {
        const pathname = new URL(route.request().url()).pathname;
        if (pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        return route.fulfill({ contentType: 'text/html', body: html(mode) });
    });
    await page.goto('http://mm-dates.test/hotel/booking/' + mode);
    return errors;
}
const checkIn = page => page.locator('input.check-in-date');
const checkOut = page => page.locator('input.check-out-date-picker');
const alertFor = (page, field) => field(page).locator('xpath=ancestor::div[contains(@class,"form-group")]').getByRole('alert');
const calendarDay = (page, n) => page.locator('.datepicker-dropdown td.day:not(.old):not(.new)').filter({ hasText: new RegExp(`^${n}$`) });

test('create: defaults come from the business date and past days cannot be picked', async ({ page }) => {
    const errors = await open(page, 'create');
    await expect(checkIn(page)).toHaveValue('2026-10-01');
    await expect(checkOut(page)).toHaveValue('2026-10-02');
    await checkIn(page).click();
    await expect(page.locator('.datepicker-dropdown')).toBeVisible();
    expect(await page.locator('.datepicker-dropdown td.day.disabled').count()).toBeGreaterThan(0);
    await page.locator('.datepicker-dropdown td.day.old').first().click({ force: true });
    await expect(checkIn(page)).toHaveValue('2026-10-01');
    expect(errors).toEqual([]);
});

test('create: moving check-in forward moves check-out and recalculates nights', async ({ page }) => {
    await open(page, 'create');
    await checkIn(page).click();
    await calendarDay(page, 5).click();
    await expect(checkIn(page)).toHaveValue('2026-10-05');
    await expect(checkOut(page)).toHaveValue('2026-10-06');
    expect(await page.evaluate(() => window.nightsSeen)).toContain('2026-10-06');
    // A later check-out is kept.
    await checkOut(page).click();
    await calendarDay(page, 9).click();
    await expect(checkOut(page)).toHaveValue('2026-10-09');
    await checkIn(page).click();
    await calendarDay(page, 7).click();
    await expect(checkOut(page)).toHaveValue('2026-10-09');
});

test('create: check-out on or before check-in is corrected before the legacy calculation sees it', async ({ page }) => {
    await open(page, 'create');
    await checkIn(page).fill('2026-10-04');
    await checkIn(page).blur();
    await page.evaluate(() => { window.nightsSeen = []; });
    await checkOut(page).fill('2026-10-04');
    await checkOut(page).blur();
    await expect(checkOut(page)).toHaveValue('2026-10-05');
    await expect(alertFor(page, checkOut)).toContainText('Check-out must be after check-in');
    const seen = await page.evaluate(() => window.nightsSeen);
    expect(seen.length).toBeGreaterThan(0);
    expect(Array.from(new Set(seen))).toEqual(['2026-10-05']); // the legacy handler never receives the invalid value
    await checkOut(page).fill('2026-10-01');
    await checkOut(page).blur();
    await expect(checkOut(page)).toHaveValue('2026-10-05');
});

test('create: typed past check-in becomes the business date with a message', async ({ page }) => {
    await open(page, 'create');
    await checkIn(page).fill('2026-09-20');
    await checkIn(page).blur();
    await expect(checkIn(page)).toHaveValue('2026-10-01');
    await expect(alertFor(page, checkIn)).toContainText('business date (2026-10-01)');
    await expect(checkOut(page)).toHaveValue('2026-10-02');
});

test('create: submit is stopped for an invalid stay and allowed for a valid one', async ({ page }) => {
    await open(page, 'create');
    await page.evaluate(() => { document.querySelector('input.check-out-date-picker').value = '2026-09-01'; });
    await page.getByRole('button', { name: 'Save' }).click();
    expect(await page.evaluate(() => window.legacySubmits)).toBe(0);
    await expect(alertFor(page, checkOut)).toContainText('Check-out must be after check-in');
    await checkOut(page).fill('2026-10-03');
    await checkOut(page).blur();
    await page.getByRole('button', { name: 'Save' }).click();
    expect(await page.evaluate(() => window.legacySubmits)).toBe(1);
    await page.evaluate(() => { document.querySelector('input.check-in-date').value = 'not a date'; });
    await page.getByRole('button', { name: 'Save' }).click();
    expect(await page.evaluate(() => window.legacySubmits)).toBe(1);
    await expect(alertFor(page, checkIn)).toContainText('YYYY-MM-DD');
});

test('edit: shows the booking check-in, allows past check-in, and keeps check-out after it', async ({ page }) => {
    const errors = await open(page, 'edit');
    await expect(checkIn(page)).toHaveValue('2026-09-29');
    await expect(checkOut(page)).toHaveValue('2026-10-03');
    await expect(checkOut(page)).toHaveAttribute('data-date-format', 'yyyy-mm-dd');
    await checkIn(page).fill('2026-09-20');
    await checkIn(page).blur();
    await expect(checkIn(page)).toHaveValue('2026-09-20');
    await expect(checkOut(page)).toHaveValue('2026-10-03');
    await checkIn(page).fill('2026-10-10');
    await checkIn(page).blur();
    await expect(checkOut(page)).toHaveValue('2026-10-11');
    expect(errors).toEqual([]);
});

test('edit: the earliest check-out stays the legacy extension limit', async ({ page }) => {
    await open(page, 'edit');
    await checkOut(page).click();
    await expect(page.locator('.datepicker-dropdown')).toBeVisible();
    await expect(page.locator('.datepicker-dropdown td.day.disabled').filter({ hasText: /^3$/ }).first()).toBeVisible();
    await expect(calendarDay(page, 4)).toBeVisible();
});

for (const mode of ['create', 'edit']) {
    test(`${mode}: date fields fit a 360px screen`, async ({ page }) => {
        await open(page, mode, 360);
        const result = await page.evaluate(() => ({
            overflow: document.documentElement.scrollWidth > innerWidth,
            culprits: Array.from(document.querySelectorAll('body *')).filter(el => el.getBoundingClientRect().right > innerWidth + 1 && el.getClientRects().length).slice(0, 5).map(el => el.tagName + '.' + el.className),
            fields: ['input.check-in-date', 'input.check-out-date-picker'].map(s => { const r = document.querySelector(s).getBoundingClientRect(); return [r.left, r.right, r.height]; }),
        }));
        expect(result.culprits).toEqual([]);
        expect(result.overflow).toBeFalsy();
        for (const [l, r, h] of result.fields) { expect(l).toBeGreaterThanOrEqual(0); expect(r).toBeLessThanOrEqual(361); expect(h).toBeGreaterThanOrEqual(30); }
    });
}
