// New-booking progress, stay summary and sticky action bar rendered from the real partial fixture with real ui.css.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

const steps = fs.readFileSync('tools/fixtures/booking-next-steps.html', 'utf8');
const page_html = `<!doctype html><html><head><meta charset="utf-8"><link rel="stylesheet" href="/assets/custom_css/ui.css"></head>
<body style="margin:0"><section class="mm-ui mm-booking-next"><div class="tw-space-y-6 tw-py-4">${steps}
<div class="mm-panel tw-p-4"><div style="height:1600px">form body</div>
<div class="mm-form-actions"><button class="mm-button" type="button">Save</button><button class="mm-button mm-button-secondary" type="reset">Reset</button></div></div></div></section></body></html>`;

async function open(page, width) {
    await page.setViewportSize({ width, height: 700 });
    await page.route('http://mm-booking.test/**', route => {
        const pathname = new URL(route.request().url()).pathname;
        if (pathname.startsWith('/assets/')) return route.fulfill({ path: path.join(process.cwd(), 'public', pathname) });
        return route.fulfill({ contentType: 'text/html', body: page_html });
    });
    await page.goto('http://mm-booking.test/');
}

for (const width of [360, 768, 1440]) {
    test(`new-booking progress, summary and sticky actions at ${width}px`, async ({ page }) => {
        await open(page, width);
        const nav = page.getByRole('navigation', { name: 'Booking progress' });
        await expect(nav.locator('[aria-current="step"]')).toContainText('Guest and stay details');
        await expect(nav.locator('li')).toHaveCount(3);
        const summary = page.getByLabel('Selected stay');
        await expect(summary).toContainText('Check-in');
        await expect(summary).toContainText('2026-10-05');
        const layout = await page.evaluate(() => ({
            overflow: document.documentElement.scrollWidth > innerWidth,
            cells: Array.from(document.querySelectorAll('.mm-stay-summary > div')).map(el => { const r = el.getBoundingClientRect(); return [r.left, r.right]; }),
            marks: Array.from(document.querySelectorAll('.mm-step-mark')).map(el => el.getBoundingClientRect().width),
        }));
        expect(layout.overflow).toBeFalsy();
        for (const [l, r] of layout.cells) { expect(l).toBeGreaterThanOrEqual(0); expect(r).toBeLessThanOrEqual(width + 1); }
        expect(layout.marks.every(w => w >= 28)).toBeTruthy();
        // The action bar stays reachable at the bottom of the viewport while the long form is above it.
        const bar = page.locator('.mm-form-actions');
        const box = await bar.boundingBox();
        expect(box.y + box.height).toBeLessThanOrEqual(700 + 1);
        await expect(page.getByRole('button', { name: 'Save' })).toBeVisible();
        const save = await page.getByRole('button', { name: 'Save' }).boundingBox();
        expect(save.height).toBeGreaterThanOrEqual(40);
    });
}

test('completed step is announced to screen readers and the current step uses the brand colour', async ({ page }) => {
    await open(page, 1440);
    await expect(page.locator('.mm-steps li.is-done .mm-visually-hidden')).toHaveText('Completed: ');
    const colors = await page.evaluate(() => { const m = document.querySelector('.is-current .mm-step-mark'); return getComputedStyle(m).backgroundColor; });
    expect(colors).toBe('rgb(47, 99, 168)');
});
