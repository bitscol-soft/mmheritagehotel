const { test, expect } = require('@playwright/test');
const { spawn } = require('child_process');
const http = require('http');

let srv;
const PORT = 4397;

function waitServer() {
    return new Promise((resolve, reject) => {
        const start = Date.now();
        const tick = () => {
            http.get(`http://127.0.0.1:${PORT}/preview/wiki`, res => {
                res.resume();
                if (res.statusCode === 200) resolve();
                else if (Date.now() - start > 8000) reject(new Error('Server timeout'));
                else setTimeout(tick, 120);
            }).on('error', () => {
                if (Date.now() - start > 8000) reject(new Error('Server start error'));
                else setTimeout(tick, 120);
            });
        };
        tick();
    });
}

test.beforeAll(async () => {
    srv = spawn(process.execPath, ['tools/preview-server.cjs'], {
        env: { ...process.env, PORT: String(PORT), HOST: '127.0.0.1' },
        stdio: 'ignore',
    });
    await waitServer();
});

test.afterAll(async () => {
    if (srv) srv.kill();
});

test('bilingual wiki & help center (/preview/wiki) toggles EN/BN and filters topics', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 900 });
    await page.goto(`http://127.0.0.1:${PORT}/preview/wiki`);
    await expect(page.locator('.mm-page-title').first()).toContainText('User Documentation, Wiki & Help');
    await expect(page.locator('.mm-page-title').first()).toContainText('ব্যবহারকারী ম্যানুয়াল ও সহায়িকা');
    await expect(page.locator('.wiki-card')).toHaveCount(8);

    await page.locator('[data-wiki-lang="bn"]').click();
    await expect(page.locator('.wiki-card .wiki-lang-en').first()).toBeHidden();
    await expect(page.locator('.wiki-card .wiki-lang-bn').first()).toBeVisible();

    await page.locator('[data-wiki-lang="both"]').click();
    await page.fill('#mm-wiki-search', 'পিওএস');
    const visibleCount = await page.locator('.wiki-card:visible').count();
    expect(visibleCount).toBeGreaterThan(0);
});

test('printable bilingual SOP cheat sheet (/preview/sop-print) hides chrome on print media', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 900 });
    await page.goto(`http://127.0.0.1:${PORT}/preview/sop-print`);
    await expect(page.locator('.mm-print-sheet-a4')).toBeVisible();
    await page.emulateMedia({ media: 'print' });
    await expect(page.locator('header.mm-page-head.mm-no-print')).toBeHidden();
    await expect(page.locator('.mm-print-sheet-a4')).toBeVisible();
});

for (const [route, heading] of [
    ['/preview/onboarding', 'Role-Based Staff Onboarding'],
    ['/preview/tape-chart', '7-Day Reservation Tape Chart'],
    ['/preview/housekeeping-board', 'Housekeeping & Maintenance SLA Board'],
    ['/preview/concierge-requests', 'Concierge, Guest Requests & Lost-and-Found'],
    ['/preview/channel-manager', 'OTA Channel Manager & iCal Sync'],
    ['/preview/rate-plans', 'Seasonal Rate Plans & Promo Engine'],
    ['/preview/booking-drawers', 'Front-Desk & POS Slide-Over App Drawers'],
]) {
    test(`${route} renders shell frame, KPI/table panels, and does not overflow at desktop or phone`, async ({ page }) => {
        await page.setViewportSize({ width: 1280, height: 900 });
        await page.goto(`http://127.0.0.1:${PORT}${route}`);
        await expect(page.locator('.mm-page-title').first()).toContainText(heading);
        await expect(page.locator('.mm-panel').first()).toBeVisible();
        for (const width of [1280, 390]) {
            await page.setViewportSize({ width, height: 900 });
            const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            expect(over, `${route} overflow at ${width}`).toBeLessThanOrEqual(1);
        }
    });
}
