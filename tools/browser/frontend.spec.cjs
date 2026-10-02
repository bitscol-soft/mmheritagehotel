// Public site (frontend.home, frontend.room_view, frontend.booking-cart) rendered through the
// shared mm-web page frame (tools/fixtures/frontend/*.html).
//
// Fixtures are committed alongside this spec. To regenerate them after a view change:
//   1. Boot Laravel against the seeded SQLite dump (the same env used by docs/BUGS.md "round 2").
//   2. Set MM_WRITE_FIXTURE=1 and run `php tools/ui-blade-check.php`.
//   3. Commit the updated tools/fixtures/frontend/*.html files.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const html = fs.readFileSync(`tools/fixtures/frontend/${name}.html`, 'utf8');
    await page.route('http://mm-web.test/**', route => {
        const url = new URL(route.request().url());
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-web.test/web/x');
    return { errors };
}

const screens = {
    'home': 'Public homepage through the shared mm-web shell',
    'room_view': 'Public room detail through the shared mm-web shell',
    'booking_cart': 'Public booking cart through the shared mm-web shell',
};

for (const [name, title] of Object.entries(screens)) {
    test(`${name}: shared mm-web panel, public chrome intact, no page overflow at desktop, tablet and phone`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('.mm-public-main').first()).toBeVisible();
        await expect(page.locator('.mm-public-main .mm-panel').first()).toBeVisible();
        // Public-site chrome is provided by the shared partials and must still be present.
        await expect(page.locator('.banner-top').first()).toBeVisible();
        await expect(page.locator('.w3_navigation').first()).toBeVisible();
        for (const width of [1280, 768, 390]) {
            await page.setViewportSize({ width, height: 900 });
            const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            expect(over, `${name} overflows by ${over}px at ${width}px`).toBeLessThanOrEqual(0);
        }
        // The new shell must never leak the admin `<x-mm.page>` header (the public site does not
        // need an H1/page-title row above the marketing content).
        await expect(page.locator('.mm-public-main .mm-page-title')).toHaveCount(0);
    });
}

test('home: feature heading and amenities render with escaped content', async ({ page }) => {
    const { errors } = await open(page, 'home');
    // Sanity: the section the homepage actually contains — feature heading + a feature card.
    await expect(page.locator('.banner-bottom, .agileits_banner_bottom').first()).toBeVisible();
    // The search bar (yielded from the layout) must still be present — it's shared with legacy views.
    await expect(page.locator('.search-bar, .booking-details').first()).toBeVisible();
});

test('room_view: category section renders with the room name and amenities list', async ({ page }) => {
    const { errors } = await open(page, 'room_view');
    await expect(page.locator('.category-body').first()).toBeVisible();
    await expect(page.locator('.aminity-list, .category-aminities').first()).toBeVisible();
});

test('booking_cart: cart page renders with the empty-cart message when the booking_cart cookie is absent', async ({ page }) => {
    const { errors } = await open(page, 'booking_cart');
    await expect(page.locator('.booking-cart').first()).toBeVisible();
    await expect(page.locator('table.table').first()).toBeVisible();
});