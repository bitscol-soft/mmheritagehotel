// Header, footer, command palette, shortcuts and theme tests against fixtures composed from the rendered Blade partials.
const { test, expect } = require('@playwright/test');
const path = require('path');
const fs = require('fs');
const { compose } = require('./compose-fixture.cjs');

async function open(page, width = 1440, { name = 'admin-shell.html', height = 900 } = {}) {
    await page.setViewportSize({ width, height });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    await page.route('http://mm-shell.test/**', route => {
        const pathname = new URL(route.request().url()).pathname;
        if (pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        return route.fulfill({ contentType: 'text/html', body: compose(name) });
    });
    await page.goto('http://mm-shell.test/hotel/booking/create');
    return errors;
}
const modifier = process.platform === 'darwin' ? 'Meta' : 'Control';

for (const width of [360, 768, 1440]) {
    test(`header tools and footer fit without overflow at ${width}px`, async ({ page }) => {
        const errors = await open(page, width);
        const result = await page.evaluate(() => {
            const items = Array.from(document.querySelectorAll('#navbar .ace-nav a, #navbar .ace-nav button')).filter(el => el.getClientRects().length);
            const outside = items.filter(el => { const r = el.getBoundingClientRect(); return r.left < 0 || r.right > innerWidth + 1; }).map(el => el.getAttribute('aria-label') || el.textContent.trim());
            const small = items.filter(el => el.getBoundingClientRect().height < 40).map(el => el.getAttribute('aria-label') || el.textContent.trim());
            return { outside, small, overflow: document.documentElement.scrollWidth > innerWidth, header: document.getElementById('navbar').getBoundingClientRect().height };
        });
        expect(result.outside).toEqual([]);
        expect(result.small).toEqual([]);
        expect(result.overflow).toBeFalsy();
        await expect(page.getByRole('link', { name: 'New booking' })).toBeVisible();
        await expect(page.getByRole('button', { name: 'Dark theme' })).toBeVisible();
        if (width >= 576) await expect(page.getByRole('button', { name: /Search screens/ })).toBeVisible();
        else await expect(page.locator('.mm-hdr-search')).toBeHidden();
        if (width >= 768) await expect(page.getByRole('button', { name: 'Keyboard shortcuts' }).first()).toBeVisible();
        const footer = page.locator('.mm-shell-footer');
        await footer.scrollIntoViewIfNeeded();
        await expect(footer).toBeVisible();
        await expect(footer).toContainText('Business date');
        await expect(footer).toContainText('01 Oct 2026');
        await expect(footer.locator('.mm-env')).toHaveText('staging');
        const box = await footer.boundingBox();
        expect(box.x + box.width).toBeLessThanOrEqual(width + 1);
        if (width >= 992) expect(box.x).toBeGreaterThanOrEqual(240);
        expect(errors).toEqual([]);
    });
}

test('breadcrumb shows the current location', async ({ page }) => {
    await open(page);
    const crumbs = page.getByRole('navigation', { name: 'Breadcrumb' });
    await expect(crumbs.getByRole('link', { name: 'Home' })).toBeVisible();
    await expect(crumbs).toContainText('Hotel');
    await expect(crumbs.locator('[aria-current="page"]')).toHaveText('Create');
});

test('command palette opens with the keyboard, filters permitted menu screens and navigates', async ({ page }) => {
    const errors = await open(page);
    await page.keyboard.press(`${modifier}+k`);
    const dialog = page.locator('#mm-palette');
    await expect(dialog).toBeVisible();
    const input = page.locator('#mm-palette-input');
    await expect(input).toBeFocused();
    await expect(input).toHaveAttribute('role', 'combobox');
    await expect(page.getByRole('option').first()).toBeVisible();
    await input.fill('booking');
    await expect(page.getByRole('option', { name: /Bookings/ })).toBeVisible();
    await expect(page.getByRole('option', { name: /Bookings/ })).toContainText('Hotel');
    await expect(page.getByRole('option', { name: /New booking/ })).toBeVisible();
    await input.fill('zzzz-nothing');
    await expect(page.locator('#mm-palette-empty')).toBeVisible();
    await expect(page.getByRole('option')).toHaveCount(0);
    await input.fill('ledger');
    await expect(page.getByRole('option', { name: /General ledger/ })).toHaveAttribute('aria-selected', 'true');
    await expect(input).toHaveAttribute('aria-activedescendant', /mm-palette-opt-/);
    await input.fill('guests');
    await input.press('ArrowDown');
    await input.press('ArrowUp');
    await input.press('Enter');
    await expect(page).toHaveURL(/\/hotel\/guests$/);
    expect(errors).toEqual([]);
});

test('command palette closes with Escape, returns focus and ignores single-key shortcuts while typing', async ({ page }) => {
    await open(page);
    await page.locator('#mm-density-toggle').focus();
    await page.keyboard.press('/');
    await expect(page.locator('#mm-palette')).toBeVisible();
    await page.keyboard.press('Escape');
    await expect(page.locator('#mm-palette')).toBeHidden();
    await expect(page.locator('#mm-density-toggle')).toBeFocused();
    await page.locator('#mm-menu-filter').focus();
    await page.keyboard.type('/?');
    await expect(page.locator('#mm-palette')).toBeHidden();
    await expect(page.locator('#mm-shortcuts')).toBeHidden();
    await expect(page.locator('#mm-menu-filter')).toHaveValue('/?');
    await page.locator('#mm-palette-input').count();
    await page.getByRole('button', { name: /Search screens/ }).click();
    await expect(page.locator('#mm-palette')).toBeVisible();
    await page.mouse.click(5, 5);
    await expect(page.locator('#mm-palette')).toBeHidden();
});

test('palette actions run: theme, compact spacing and shortcuts', async ({ page }) => {
    await open(page);
    await page.keyboard.press(`${modifier}+k`);
    await page.locator('#mm-palette-input').fill('dark');
    await page.keyboard.press('Enter');
    await expect(page.locator('body')).toHaveClass(/mm-dark/);
    await page.keyboard.press(`${modifier}+k`);
    await page.locator('#mm-palette-input').fill('compact');
    await page.keyboard.press('Enter');
    await expect(page.locator('body')).toHaveClass(/mm-compact/);
    await page.keyboard.press(`${modifier}+k`);
    await page.locator('#mm-palette-input').fill('keyboard');
    await page.keyboard.press('Enter');
    await expect(page.locator('#mm-shortcuts')).toBeVisible();
});

test('shortcut help opens with ? and from the footer, and closes with Escape', async ({ page }) => {
    await open(page);
    await page.locator('body').click({ position: { x: 700, y: 400 } });
    await page.keyboard.press('Shift+/');
    const help = page.locator('#mm-shortcuts');
    await expect(help).toBeVisible();
    await expect(help.getByRole('heading', { name: 'Keyboard shortcuts' })).toBeVisible();
    await page.keyboard.press('Escape');
    await expect(help).toBeHidden();
    await page.locator('.mm-footer-link').click();
    await expect(help).toBeVisible();
    await help.getByRole('button', { name: 'Close keyboard shortcuts' }).click();
    await expect(help).toBeHidden();
});

test('[ toggles the sidebar like the menu button', async ({ page }) => {
    await open(page);
    await page.locator('body').click({ position: { x: 700, y: 400 } });
    await expect(page.locator('#menu-toggler')).toHaveAttribute('aria-expanded', 'true');
    await page.keyboard.press('[');
    await expect(page.locator('#menu-toggler')).toHaveAttribute('aria-expanded', 'false');
    await page.keyboard.press('[');
    await expect(page.locator('#menu-toggler')).toHaveAttribute('aria-expanded', 'true');
});

test('theme toggle persists, applies dark chrome and stays accessible', async ({ page }) => {
    await open(page);
    const toggle = page.locator('[data-mm-theme-toggle]');
    await expect(toggle).toHaveAttribute('aria-pressed', 'false');
    await toggle.click();
    await expect(page.locator('body')).toHaveClass(/mm-dark/);
    await expect(toggle).toHaveAttribute('aria-pressed', 'true');
    await expect(toggle).toHaveAttribute('aria-label', 'Light theme');
    expect(await page.evaluate(() => localStorage.getItem('mm-theme'))).toBe('dark');
    const colors = await page.evaluate(() => { const pick = (selector, prop) => getComputedStyle(document.querySelector(selector))[prop]; return { header: pick('#navbar', 'backgroundColor'), page: pick('.page-content', 'backgroundColor'), sidebar: pick('#sidebar', 'backgroundColor'), footer: pick('.mm-shell-footer', 'backgroundColor') }; });
    expect(colors).toEqual({ header: 'rgb(19, 29, 40)', page: 'rgb(15, 23, 32)', sidebar: 'rgb(19, 29, 40)', footer: 'rgb(19, 29, 40)' });
    await page.reload();
    await expect(page.locator('body')).toHaveClass(/mm-dark/);
    await page.locator('[data-mm-theme-toggle]').click();
    await expect(page.locator('body')).not.toHaveClass(/mm-dark/);
    expect(await page.evaluate(() => localStorage.getItem('mm-theme'))).toBe('light');
});

test('dark theme keeps dashboard content readable', async ({ page }) => {
    await page.addInitScript(() => localStorage.setItem('mm-theme', 'dark'));
    await open(page, 1440, { name: 'dashboard.html' });
    await expect(page.locator('body')).toHaveClass(/mm-dark/);
    const readable = await page.evaluate(() => {
        const luminance = css => { const [r, g, b] = css.match(/\d+(\.\d+)?/g).slice(0, 3).map(Number).map(v => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); }); return 0.2126 * r + 0.7152 * g + 0.0722 * b; };
        const ratio = (a, b) => { const [hi, lo] = [luminance(a), luminance(b)].sort((x, y) => y - x); return (hi + 0.05) / (lo + 0.05); };
        const bad = [];
        for (const selector of ['.mm-dashboard-stat', '.mmb-group-toggle', '.mmb-card', '.mmb-pill', '.mmb-legend .mmb-chip', '.mm-dashboard-board h2', '.mm-crumbs [aria-current="page"]', '.mm-shell-footer', '#sidebar .nav-list > li > a']) {
            const el = document.querySelector(selector);
            if (!el) { bad.push(selector + ' missing'); continue; }
            let bg = el, color = getComputedStyle(el).color, back = 'rgba(0, 0, 0, 0)';
            while (bg && /rgba\(.*, 0\)|transparent/.test(back)) { back = getComputedStyle(bg).backgroundColor; bg = bg.parentElement; }
            if (ratio(color, back) < 4.5) bad.push(selector + ' ' + color + ' on ' + back);
        }
        return bad;
    });
    expect(readable).toEqual([]);
});

test('footer clock ticks, offline state is announced and last sync is relative', async ({ page, context }) => {
    await open(page);
    const clock = page.locator('#mm-clock');
    const first = await clock.textContent();
    await expect(clock).not.toHaveText(first, { timeout: 4000 });
    await expect(page.locator('#mm-online')).toHaveAttribute('data-online', 'true');
    await context.setOffline(true);
    await expect(page.locator('#mm-online')).toHaveAttribute('data-online', 'false');
    await expect(page.locator('#mm-online-text')).toHaveText('Offline');
    await context.setOffline(false);
    await expect(page.locator('#mm-online-text')).toHaveText('Online');
    await expect(page.locator('#mm-sync')).toHaveText('just now');
});

test('full screen button is hidden unless the browser supports it and toggles state', async ({ page }) => {
    await open(page);
    const supported = await page.evaluate(() => !!(document.fullscreenEnabled && document.documentElement.requestFullscreen));
    const button = page.locator('[data-mm-fullscreen]');
    if (!supported) return expect(button).toBeHidden();
    await expect(button).toBeVisible();
    await button.click();
    await expect.poll(() => page.evaluate(() => !!document.fullscreenElement)).toBeTruthy();
    await expect(button).toHaveAttribute('aria-pressed', 'true');
    await button.click();
    await expect.poll(() => page.evaluate(() => !!document.fullscreenElement)).toBeFalsy();
});

test('controls stay hidden and pages work without dialog support', async ({ page }) => {
    await page.addInitScript(() => { delete HTMLDialogElement.prototype.showModal; });
    const errors = await open(page);
    await expect(page.locator('.mm-hdr-search')).toBeHidden();
    await expect(page.locator('.mm-footer-link')).toBeHidden();
    await page.keyboard.press(`${modifier}+k`);
    await page.keyboard.press('?');
    await expect(page.locator('#mm-palette')).toBeHidden();
    expect(errors).toEqual([]);
});
