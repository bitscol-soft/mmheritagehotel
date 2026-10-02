// Stay date range: real room-board markup, real jQuery, moment, daterangepicker and stay-range.js.
// The business date is fixed by the rendered data-business-date (2026-10-01, a Thursday), not by the browser clock.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const { compose } = require('./compose-fixture.cjs');
const path = require('path');

const board = fs.readFileSync('tools/fixtures/room-board.html', 'utf8');
const html = (dark) => `<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/assets/css/bootstrap.min.css"><link rel="stylesheet" href="/assets/css/ace.min.css">
<link rel="stylesheet" href="/assets/font-awesome/4.5.0/css/font-awesome.min.css"><link rel="stylesheet" href="/assets/css/daterangepicker.min.css">
<link rel="stylesheet" href="/assets/custom_css/ui.css"><link rel="stylesheet" href="/assets/custom_css/shell.css"></head>
<body class="no-skin mm-shell${dark ? ' mm-dark' : ''}"><div class="mm-ui" style="padding:16px">${board}</div>
<script src="/assets/js/jquery-2.1.4.min.js"></script><script src="/assets/js/moment.min.js"></script><script src="/assets/js/daterangepicker.min.js"></script>
<script src="/assets/custom_js/stay-range.js"></script>
<script>$(function(){ $('input[name="booking_date"]').each(function(){ MMStayRange.init(this); }); });</script></body></html>`;

async function open(page, { width = 1440, dark = false } = {}) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.route('http://mm-stay.test/**', route => {
        const pathname = new URL(route.request().url()).pathname;
        if (pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        return route.fulfill({ contentType: 'text/html', body: html(dark) });
    });
    await page.goto('http://mm-stay.test/home');
    return errors;
}
const input = page => page.locator('input[name="booking_date"]');
const day = (page, n) => page.locator('.daterangepicker .calendar.left td.available:not(.off)').filter({ hasText: new RegExp(`^${n}$`) });
async function submittedRange(page, action) {
    const nav = page.waitForURL(/booking_date=/);
    await action();
    await nav;
    return new URL(page.url()).searchParams.get('booking_date');
}

test('days before the business date are disabled and cannot be picked', async ({ page }) => {
    const errors = await open(page);
    await input(page).click();
    await expect(page.locator('.daterangepicker')).toBeVisible();
    await expect(page.locator('.daterangepicker .calendar.left td.disabled').first()).toBeVisible();
    expect(await page.locator('.daterangepicker .calendar.left td.disabled').count()).toBeGreaterThanOrEqual(1);
    await expect(day(page, 1)).toHaveCount(1);
    await page.locator('.daterangepicker .calendar.left td.disabled').first().click({ force: true });
    await page.waitForTimeout(150);
    expect(page.url()).not.toContain('booking_date=');
    await expect(input(page)).toHaveValue('10/01/2026 - 10/02/2026');
    expect(errors).toEqual([]);
});

test('picking a check-in and check-out submits that range', async ({ page }) => {
    await open(page);
    await input(page).click();
    const range = await submittedRange(page, async () => { await day(page, 5).click(); await day(page, 8).click(); });
    expect(range).toBe('10/05/2026 - 10/08/2026');
});

test('picking the same day twice becomes a one-night stay', async ({ page }) => {
    await open(page);
    await input(page).click();
    const range = await submittedRange(page, async () => { await day(page, 10).click(); await day(page, 10).click(); });
    expect(range).toBe('10/10/2026 - 10/11/2026');
});

test('typed past dates and malformed text are blocked with a message', async ({ page }) => {
    await open(page);
    const field = input(page);
    await field.fill('09/20/2026 - 09/22/2026');
    await field.press('Enter');
    const alert = page.getByRole('alert');
    await expect(alert).toContainText('business date (01 Oct 2026)');
    await expect(field).toHaveAttribute('aria-invalid', 'true');
    expect(page.url()).not.toContain('booking_date=');
    await field.fill('next friday');
    await field.press('Enter');
    await expect(alert).toContainText('MM/DD/YYYY');
    expect(page.url()).not.toContain('booking_date=');
    await field.fill('10/03/2026 - 10/03/2026');
    await expect(alert).toBeHidden();
    const range = await submittedRange(page, () => field.press('Enter'));
    expect(range).toBe('10/03/2026 - 10/04/2026');
});

test('quick chips use the business date', async ({ page }) => {
    await open(page);
    expect(await submittedRange(page, () => page.getByRole('button', { name: 'Tonight' }).click())).toBe('10/01/2026 - 10/02/2026');
    await open(page);
    expect(await submittedRange(page, () => page.getByRole('button', { name: 'Next 7 days' }).click())).toBe('10/01/2026 - 10/07/2026');
    await open(page);
    expect(await submittedRange(page, () => page.getByRole('button', { name: 'Weekend' }).click())).toBe('10/03/2026 - 10/05/2026');
});

test('picker fits a 360px screen without page overflow', async ({ page }) => {
    await open(page, { width: 360 });
    await input(page).click();
    const box = await page.locator('.daterangepicker').boundingBox();
    expect(box.x).toBeGreaterThanOrEqual(0);
    expect(box.x + box.width).toBeLessThanOrEqual(361);
    expect(await page.evaluate(() => document.documentElement.scrollWidth > innerWidth)).toBeFalsy();
    await expect(day(page, 12)).toBeVisible();
    const cell = await day(page, 12).boundingBox();
    expect(cell.width).toBeGreaterThanOrEqual(30);
    expect(cell.height).toBeGreaterThanOrEqual(30);
});

test('dark theme styles the picker', async ({ page }) => {
    await open(page, { dark: true });
    await input(page).click();
    const colors = await page.evaluate(() => {
        const dp = getComputedStyle(document.querySelector('.daterangepicker'));
        const cell = getComputedStyle(document.querySelector('.daterangepicker .calendar.left td.available:not(.off):not(.active):not(.in-range)'));
        return { bg: dp.backgroundColor, cell: cell.color };
    });
    expect(colors.bg).toBe('rgb(24, 35, 47)');
    expect(colors.cell).toBe('rgb(228, 236, 245)');
});

test('the calendar scrolls into view when it would open below the fold', async ({ page }) => {
    await open(page);
    await page.setViewportSize({ width: 1440, height: 420 });
    await input(page).click();
    await expect(page.locator('.daterangepicker')).toBeVisible();
    await expect.poll(async () => page.evaluate(() => document.querySelector('.daterangepicker').getBoundingClientRect().bottom <= innerHeight + 1)).toBeTruthy();
});

test('the dashboard preview fixture loads the picker scripts and the range works end to end', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.route('http://mm-stay.test/**', route => {
        const pathname = new URL(route.request().url()).pathname;
        if (pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        return route.fulfill({ contentType: 'text/html', body: compose('dashboard.html') });
    });
    await page.goto('http://mm-stay.test/home');
    await expect(input(page)).toHaveValue('10/01/2026 - 10/02/2026');
    await input(page).click();
    await expect(page.locator('.daterangepicker')).toBeVisible();
    const range = await submittedRange(page, async () => { await day(page, 6).click(); await day(page, 9).click(); });
    expect(range).toBe('10/06/2026 - 10/09/2026');
    expect(errors).toEqual([]);
});
