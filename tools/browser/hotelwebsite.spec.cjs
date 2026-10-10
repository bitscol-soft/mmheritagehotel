// HotelWebsite screens (public website content) rendered from the real Blade views (tools/fixtures/hotelwebsite/*.html).
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const html = fs.readFileSync(`tools/fixtures/hotelwebsite/${name}.html`, 'utf8');
    await page.route(/^https?:\/\/(?!mm-web\.test\/)/, route => route.fulfill({ status: 204, body: '' }));
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
    'banner-index': 'Homepage banners',
    'banner-create': 'Add a banner',
    'banner-edit': 'Edit banner',
    'gallery-index': 'Gallery',
    'gallery-create': 'Add an image',
    'gallery-edit': 'Edit image',
    'feature-heading': 'Homepage feature heading',
    'feature-list-index': 'Homepage features',
    'feature-list-create': 'Add a feature',
    'feature-list-edit': 'Edit feature',
    'service-heading': 'Our services heading',
    'service-list-index': 'Service boxes',
    'service-list-create': 'Add a service box',
    'service-list-edit': 'Edit service box',
    'pages-index': 'Pages',
    'pages-create': 'Add a page',
    'pages-edit': 'Edit page',
    'about': 'About section',
    'privacy': 'Privacy policy',
    'settings': 'Website settings',
};

for (const [name, title] of Object.entries(screens)) {
    test(`${name}: shared page frame, no legacy widget, no page overflow at desktop, tablet and phone`, async ({ page }) => {
        await open(page, name);
        await expect(page.locator('.mm-page-title').first()).toContainText(title);
        await expect(page.locator('.mm-ui.mm-web .mm-panel').first()).toBeVisible();
        await expect(page.locator('.widget-box, .widget-main, .widget-header, .page-header')).toHaveCount(0);
        for (const width of [1280, 768, 390]) {
            await page.setViewportSize({ width, height: 900 });
            const over = await page.evaluate(() => document.documentElement.scrollWidth - document.documentElement.clientWidth);
            expect(over, `${name} overflows by ${over}px at ${width}px`).toBeLessThanOrEqual(0);
        }
    });
}

test('list pages: escaped data, add button and per-row edit and delete', async ({ page }) => {
    for (const [name, text, add] of [['banner-index', 'Welcome <b>home</b>', 'Add Banner'], ['gallery-index', 'Lobby <b>view</b>', 'Add Gallery'], ['feature-list-index', 'Free <b>wifi</b>', null], ['service-list-index', 'Spa <b>care</b>', null], ['pages-index', 'Terms <b>page</b>', 'New Page']]) {
        await open(page, name);
        await expect(page.locator('#data-table tbody tr')).toHaveCount(1);
        await expect(page.locator('#data-table')).toContainText(text);
        await expect(page.locator('#data-table b')).toHaveCount(0);
        if (add) await expect(page.locator('.mm-ui.mm-web .mm-button', { hasText: add }).first()).toBeVisible();
    }
});

test('banner forms keep their fields and the list link', async ({ page }) => {
    await open(page, 'banner-create');
    const form = page.locator('form.form-horizontal');
    await expect(form).toHaveAttribute('enctype', 'multipart/form-data');
    await expect(form.locator('[name="banner_head_title"]')).toHaveCount(1);
    await expect(form.locator('button[type=submit].mm-button')).toBeVisible();
    await expect(page.locator('.mm-page-title').locator('xpath=ancestor::section').locator('a', { hasText: 'Banner List' })).toBeVisible();
    await open(page, 'banner-edit');
    await expect(page.locator('input[name="_method"]')).toHaveValue('PUT');
    await expect(page.locator('input[name="banner_head_title"]')).toHaveValue('Welcome <b>home</b>');
});

test('page forms: create and edit keep the content fields', async ({ page }) => {
    await open(page, 'pages-create');
    await expect(page.locator('form.form-horizontal')).toHaveAttribute('enctype', 'multipart/form-data');
    await open(page, 'pages-edit');
    await expect(page.locator('input[name="_method"]')).toHaveValue('PUT');
    await expect(page.locator('input[name="title"]')).toHaveValue('Terms <b>page</b>');
});

test('singleton forms keep #companyForm and sit in a narrow panel', async ({ page }) => {
    for (const name of ['feature-heading', 'feature-list-create', 'feature-list-edit', 'service-heading', 'service-list-create', 'service-list-edit']) {
        await open(page, name);
        await expect(page.locator('#companyForm')).toHaveCount(1);
        await expect(page.locator('#companyForm button[type=submit].mm-button')).toBeVisible();
        const w = await page.locator('.mm-web-narrow').first().evaluate(el => el.getBoundingClientRect().width);
        expect(w).toBeLessThanOrEqual(761);
    }
});

test('about, privacy and settings render their saved values escaped', async ({ page }) => {
    await open(page, 'about');
    await expect(page.locator('input[name="heading_title"]')).toHaveValue('About <b>us</b>');
    await open(page, 'privacy');
    await expect(page.locator('input[name="privacy_header"]')).toHaveValue('Privacy <b>head</b>');
    await open(page, 'settings');
    await expect(page.locator('input[name="website_first_name"]')).toHaveValue('MM');
    await expect(page.locator('.mm-ui.mm-web form button[type=submit].mm-button')).toBeVisible();
});
