// Local representative fixture + real Bootstrap/Ace/Tailwind assets. Not app acceptance.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');
async function fixture(page, { shell = true, script = true } = {}) {
    await page.route('http://mm-shell.test/**', async route => {
        const pathname = new URL(route.request().url()).pathname;
        const file = pathname.startsWith('/assets/') ? path.join(process.cwd(), 'public', pathname) : 'tools/fixtures/admin-shell.html';
        if (pathname === '/assets/custom_js/shell.js' && !script) return route.fulfill({ contentType: 'text/javascript', body: '' });
        if (file === 'tools/fixtures/admin-shell.html' && !shell) return route.fulfill({ contentType: 'text/html', body: fs.readFileSync(file, 'utf8').replace('no-skin mm-shell', 'no-skin') });
        if (!fs.existsSync(file)) return route.fulfill({ status: 404, body: '' });
        await route.fulfill({ path: file });
    });
    await page.goto('http://mm-shell.test/home');
}
for (const width of [360, 768, 1440]) {
    test(`navigation, menu search and spacing at ${width}px`, async ({ page }) => {
        await page.setViewportSize({ width, height: 900 });
        const errors = [];
        page.on('pageerror', e => errors.push(e.message));
        await fixture(page);
        const mobile = width < 992;
        const toggle = page.locator('#menu-toggler');
        await expect(toggle).toHaveAttribute('aria-expanded', mobile ? 'false' : 'true');
        if (mobile) {
            await toggle.click();
            await expect(page.locator('#mm-menu-filter')).toBeFocused();
            await expect(page.locator('#mm-main-content')).toHaveJSProperty('inert', true);
            await page.keyboard.press('Shift+Tab');
            await expect(page.locator('#sidebar [data-mm-close]')).toBeFocused();
        }
        await expect(toggle).toHaveAttribute('aria-expanded', 'true');
        await page.locator('#hotel-menu > a').click();
        await expect(page.locator('#hotel-menu .submenu')).toBeVisible();
        await page.locator('#mm-menu-filter').fill('guest');
        await expect(page.locator('#hotel-menu')).toBeVisible();
        await expect(page.getByRole('link', { name: 'Guests', exact: true })).toBeVisible();
        await expect(page.getByRole('link', { name: 'Rooms', exact: true })).toBeHidden();
        await page.locator('#mm-menu-filter').fill('zzzz');
        await expect(page.locator('#mm-menu-empty')).toBeVisible();
        await page.locator('#mm-menu-filter').fill('');
        await expect(page.locator('#mm-menu-empty')).toBeHidden();
        if (mobile) {
            await page.keyboard.press('Escape');
            await expect(toggle).toBeFocused();
            await expect(toggle).toHaveAttribute('aria-expanded', 'false');
            await expect(page.locator('#mm-main-content')).toHaveJSProperty('inert', false);
        } else {
            await toggle.click();
            await expect(toggle).toHaveAttribute('aria-expanded', 'false');
            await page.reload();
            await expect(toggle).toHaveAttribute('aria-expanded', 'false');
            await toggle.click();
        }
        await page.locator('#mm-density-toggle').click();
        await expect(page.locator('#mm-density-toggle')).toHaveAttribute('aria-pressed', 'true');
        await page.reload();
        await expect(page.locator('#mm-density-toggle')).toHaveAttribute('aria-pressed', 'true');
        await page.getByRole('link', { name: 'Account menu' }).click();
        await expect(page.getByRole('link', { name: 'Change password' })).toBeVisible();
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBeTruthy();
        expect(errors).toEqual([]);
    });
}
test('print removes shell chrome', async ({ page }) => {
    await fixture(page);
    await page.emulateMedia({ media: 'print' });
    await expect(page.locator('#navbar')).toBeHidden();
    await expect(page.locator('#sidebar')).toBeHidden();
    await expect(page.locator('.mm-shell-toolbar')).toBeHidden();
    await expect(page.locator('#mm-main-content')).toBeVisible();
});

test('drawer closes on backdrop and breakpoint; focus and Ace state remain sound', async ({ page }) => {
    await page.setViewportSize({ width: 360, height: 900 });
    await fixture(page);
    const toggle = page.locator('#menu-toggler');
    await toggle.click();
    await page.locator('#mm-menu-filter').fill('hotel');
    await expect(page.getByRole('link', { name: 'Rooms', exact: true })).toBeVisible();
    // The backdrop occupies the uncovered right edge of the drawer.
    await page.locator('.mm-shell-backdrop').click({ position: { x: 340, y: 250 } });
    await expect(toggle).toBeFocused();
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await toggle.click();
    await page.setViewportSize({ width: 1440, height: 900 });
    await expect(page.locator('.mm-shell-backdrop')).toBeHidden();
    await expect(page.locator('#mm-main-content')).toHaveJSProperty('inert', false);
    await page.locator('#mm-menu-filter').fill('');
    await expect(page.locator('#hotel-menu .submenu')).toBeHidden();
    await page.locator('#hotel-menu > a').click();
    await expect(page.locator('#hotel-menu .submenu')).toBeVisible();
    await page.setViewportSize({ width: 360, height: 900 });
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await expect(toggle).toBeFocused();
});

test('shell script is inert when shell is disabled', async ({ page }) => {
    await fixture(page, { shell: false });
    await expect(page.locator('body')).toHaveAttribute('class', 'no-skin');
    await expect(page.locator('#sidebar')).not.toHaveAttribute('aria-hidden');
    await page.locator('#mm-density-toggle').click();
    await expect(page.locator('#mm-density-toggle')).toHaveAttribute('aria-pressed', 'false');
    expect(await page.evaluate(() => localStorage.getItem('mm-shell-compact'))).toBeNull();
});

test('storage denial does not break the shell', async ({ page }) => {
    await page.addInitScript(() => {
        Storage.prototype.getItem = () => { throw new Error('Storage denied'); };
        Storage.prototype.setItem = () => { throw new Error('Storage denied'); };
    });
    await fixture(page);
    await page.locator('#menu-toggler').click();
    await expect(page.locator('#menu-toggler')).toHaveAttribute('aria-expanded', 'false');
    await page.locator('#mm-density-toggle').click();
    await expect(page.locator('#mm-density-toggle')).toHaveAttribute('aria-pressed', 'true');
});

test('mobile navigation remains readable without shell JavaScript', async ({ page }) => {
    await page.setViewportSize({ width: 360, height: 900 });
    await fixture(page, { script: false });
    await expect(page.locator('body')).not.toHaveClass(/mm-shell-ready/);
    await expect(page.getByRole('link', { name: 'Rooms', exact: true })).toBeVisible();
    await expect(page.locator('#mm-menu-filter')).toBeHidden();
});

for (const width of [360, 768, 1440]) {
    test(`dashboard summary fixture at ${width}px`, async ({ page }) => {
        await page.setViewportSize({width, height:900});
        await fixture(page);
        await page.route('http://mm-shell.test/dashboard-preview', route => route.fulfill({path:'tools/fixtures/dashboard.html'}));
        await page.goto('http://mm-shell.test/dashboard-preview');
        await expect(page.locator('.mm-dashboard-stat')).toHaveCount(4);
        await expect(page.getByRole('heading', {name:'Hotel dashboard', exact:true})).toBeVisible();
        await expect(page.getByText('not a housekeeping readiness check', {exact:false})).toBeVisible();
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBeTruthy();
    });
}
