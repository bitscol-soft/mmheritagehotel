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
function menuLink(page, name) { return page.locator('#mm-primary-menu a').filter({ hasText: new RegExp('^\\s*' + name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\s*$') }); }
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

const dashboardHtml = () => fs.readFileSync('tools/fixtures/dashboard.html', 'utf8').replace('<!--ROOM-BOARD-->', fs.readFileSync('tools/fixtures/room-board.html', 'utf8'));
async function dashboard(page, width = 1440) {
    await page.setViewportSize({ width, height: 900 });
    await fixture(page);
    const calls = { add: [], remove: [], checkout: [], status: [] };
    await page.route('http://mm-shell.test/hotel/add_booking', route => { calls.add.push(route.request().postData()); route.fulfill({ contentType: 'application/json', body: JSON.stringify({ status: true, data: 'Room added' }) }); });
    await page.route('http://mm-shell.test/hotel/remove_booking_next**', route => { calls.remove.push(route.request().url()); route.fulfill({ contentType: 'application/json', body: JSON.stringify({ status: true, data: 'Room removed' }) }); });
    await page.route('http://mm-shell.test/dashboard-preview**', route => route.fulfill({ contentType: 'text/html', body: dashboardHtml() }));
    await page.addInitScript(() => { window.__calls = { checkout: [], status: [] }; });
    await page.goto('http://mm-shell.test/dashboard-preview');
    // Legacy helpers normally come from home/_inc/script.blade.php.
    await page.evaluate(() => {
        window.checkOut = (...args) => window.__calls.checkout.push(args);
        window.updateStatus = (id, status, node) => { window.__calls.status.push([id, status]); const proxy = node.closest('.room-status-ui').querySelector('.room-info'); proxy.classList.remove('inverse', 'orange'); proxy.classList.add('orange'); };
    });
    return calls;
}

for (const width of [360, 768, 1440]) {
    test(`dashboard summary and room board fixture at ${width}px`, async ({ page }) => {
        await dashboard(page, width);
        await expect(page.locator('.mm-dashboard-stat')).toHaveCount(4);
        await expect(page.getByRole('heading', {name:'Hotel dashboard', exact:true})).toBeVisible();
        await expect(page.getByText('not a housekeeping readiness check', {exact:false})).toBeVisible();
        await expect(page.getByRole('link', {name:'Booking list'})).toBeVisible();
        await expect(page.locator('.mm-dashboard-date')).toContainText('01 Oct 2026');
        await expect(page.locator('#dashboard-board-title')).toBeVisible();
        await expect(page.locator('.mmb-group')).toHaveCount(6);
        await expect(page.locator('.mmb-card')).toHaveCount(23);
        const smallCards = await page.evaluate(() => Array.from(document.querySelectorAll('.mmb-card')).filter(el => el.getBoundingClientRect().height < 44 || el.getBoundingClientRect().width < 44).length);
        expect(smallCards).toBe(0);
        const beds = await page.evaluate(() => { const type = n => { const card = Array.from(document.querySelectorAll('.mmb-card')).find(c => c.querySelector('.mmb-number').textContent.trim() === n); return [card.querySelector('.mmb-bed-label').textContent.trim(), card.querySelectorAll('.mmb-bed svg').length, card.querySelector('.mmb-bed svg')?.getAttribute('viewBox')]; }; return { single: type('301'), double: type('101'), twin: type('201'), triple: type('401'), multi: type('601') }; });
        expect(beds.single).toEqual(['Single bed', 1, '0 0 28 20']);
        expect(beds.double).toEqual(['Double bed', 1, '0 0 40 20']);
        expect(beds.twin).toEqual(['Twin beds', 1, '0 0 59 20']);
        expect(beds.triple).toEqual(['Triple beds', 1, '0 0 90 20']);
        expect(beds.multi[0]).toBe('5 beds');
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBeTruthy();
    });
}

test('room categories collapse, expand, persist and keep accessible state', async ({ page }) => {
    await dashboard(page);
    const toggle = page.locator('#mmb-toggle-1');
    const body = page.locator('#mmb-body-1');
    await expect(toggle).toHaveAttribute('aria-expanded', 'true');
    await toggle.click();
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await expect(body).toBeHidden();
    await expect(page.locator('.mmb-group[data-category="1"] .mmb-count-free')).toBeVisible();
    await page.reload();
    await expect(page.locator('#mmb-toggle-1')).toHaveAttribute('aria-expanded', 'false');
    await page.getByRole('button', { name: 'Expand all' }).click();
    await expect(page.locator('.mmb-group-body:visible')).toHaveCount(6);
    await page.getByRole('button', { name: 'Collapse all' }).click();
    await expect(page.locator('.mmb-group-body:visible')).toHaveCount(0);
    expect(JSON.parse(await page.evaluate(() => localStorage.getItem('mm-board-collapsed'))).length).toBe(6);
});

test('room drawer shows details, traps focus and returns focus', async ({ page }) => {
    await dashboard(page);
    const card = page.locator('.mmb-card', { has: page.locator('.mmb-number', { hasText: /^101$/ }) });
    await card.focus();
    await page.keyboard.press('Enter');
    const drawer = page.locator('#mmb-drawer');
    await expect(drawer).toHaveAttribute('aria-hidden', 'false');
    await expect(drawer.getByRole('heading', { name: 'Room 101' })).toBeVisible();
    await expect(drawer).toContainText('Deluxe King');
    await expect(drawer).toContainText('Double bed');
    await expect(drawer).toContainText('King Bed');
    await expect(page.getByRole('button', { name: 'Close room details' })).toBeFocused();
    await page.keyboard.press('Shift+Tab');
    await expect(drawer.getByRole('button', { name: 'Change housekeeping status' })).toBeFocused();
    await page.keyboard.press('Tab');
    await expect(page.getByRole('button', { name: 'Close room details' })).toBeFocused();
    await page.keyboard.press('Escape');
    await expect(drawer).toHaveAttribute('aria-hidden', 'true');
    await expect(card).toBeFocused();
});

for (const width of [360, 768, 1440]) {
    test(`room drawer fits the viewport with 44px touch targets at ${width}px`, async ({ page }) => {
        await dashboard(page, width);
        await page.locator('.mmb-card', { has: page.locator('.mmb-number', { hasText: /^103$/ }) }).click();
        const drawer = page.locator('#mmb-drawer');
        await expect(drawer).toHaveAttribute('aria-hidden', 'false');
        await expect.poll(() => page.evaluate(() => document.getElementById('mmb-drawer').getBoundingClientRect().right <= innerWidth + 1)).toBeTruthy();
        const result = await page.evaluate(() => {
            const box = document.getElementById('mmb-drawer').getBoundingClientRect();
            const small = Array.from(document.querySelectorAll('#mmb-drawer button, #mmb-drawer a')).filter(el => el.getClientRects().length).filter(el => { const r = el.getBoundingClientRect(); return r.height < 44 || r.width < 44; }).map(el => el.textContent.trim() || el.getAttribute('aria-label'));
            return { left: box.left, right: box.right, width: innerWidth, small, overflow: document.documentElement.scrollWidth > innerWidth };
        });
        expect(result.left).toBeGreaterThanOrEqual(0);
        expect(result.right).toBeLessThanOrEqual(result.width + 1);
        expect(result.small).toEqual([]);
        expect(result.overflow).toBeFalsy();
    });
}

test('drawer selects rooms, calls booking endpoints and submits the existing booking form', async ({ page }) => {
    const calls = await dashboard(page);
    await page.evaluate(() => { document.getElementById('booking-form').addEventListener('submit', event => { event.preventDefault(); window.__submitted = event.submitter && event.submitter.value; }); });
    const open = number => page.locator('.mmb-card', { has: page.locator('.mmb-number', { hasText: new RegExp('^' + number + '$') }) }).click();
    await open('102');
    await page.getByRole('button', { name: 'Add to selection' }).click();
    await expect(page.locator('.mmb-card.is-selected')).toHaveCount(1);
    await expect(page.locator('.mmb-selection')).toBeVisible();
    await expect(page.locator('.mmb-sel-count')).toHaveText('1');
    expect(calls.add[0]).toContain('room_id=2');
    await page.getByRole('button', { name: 'Close room details' }).click();
    await open('301');
    await page.getByRole('button', { name: 'Add to selection' }).click();
    await page.getByRole('button', { name: 'Close room details' }).click();
    await expect(page.locator('.mmb-sel-count')).toHaveText('2');
    await page.getByRole('button', { name: 'Book Now' }).click();
    await expect.poll(() => page.evaluate(() => window.__submitted)).toBe('book');
    expect(calls.add).toHaveLength(2);
    await open('102');
    await page.getByRole('button', { name: 'Remove from selection' }).click();
    await expect(page.locator('.mmb-card.is-selected')).toHaveCount(1);
    expect(calls.remove[0]).toContain('product_id=2');
});

test('booked room drawer shows escaped guest details and keeps legacy actions', async ({ page }) => {
    await dashboard(page);
    await page.locator('.mmb-card', { has: page.locator('.mmb-number', { hasText: /^103$/ }) }).click();
    const drawer = page.locator('#mmb-drawer');
    await expect(drawer).toContainText("Aisha O'Neil <script>alert(1)</script>");
    await expect(drawer.locator('script')).toHaveCount(0);
    await expect(drawer).toContainText('Late arrival <b>note</b>');
    await drawer.getByRole('button', { name: 'Check In Now' }).click();
    expect(await page.evaluate(() => window.__calls.checkout[0][1])).toBe('Check In Now');
    await expect(drawer.getByRole('link', { name: 'Migrate' })).toHaveAttribute('href', /adjusts\/create/);
    await expect(drawer.getByRole('button', { name: 'Add to selection' })).toHaveCount(0);
});

test('housekeeping proxy changes mirror onto the card and drawer', async ({ page }) => {
    await dashboard(page);
    const card = page.locator('.mmb-card', { has: page.locator('.mmb-number', { hasText: /^101$/ }) });
    await card.click();
    await page.getByRole('button', { name: 'Change housekeeping status' }).click();
    await expect(card).toHaveAttribute('data-state', 'maintenance');
    await expect(page.locator('#mmb-drawer-chip')).toContainText('Maintenance');
    expect(await page.evaluate(() => window.__calls.status[0])).toEqual(['1', '1']);
});

for (const width of [360, 768, 1440]) {
test(`full module menu, nested disclosures and clear search at ${width}px`, async ({ page }) => {
        await page.setViewportSize({ width, height: 900 });
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        await fixture(page);
        await page.route('http://mm-shell.test/full-menu**', route => route.fulfill({ path: 'tools/fixtures/full-menu.html' }));
        await page.goto('http://mm-shell.test/full-menu');
        if (width < 992) await page.locator('#menu-toggler').click();
        const hotel = menuLink(page, 'Hotel');
        await expect(hotel).toHaveAttribute('aria-expanded', 'false');
        await hotel.focus();
        await page.keyboard.press('Space');
        await expect(hotel).toHaveAttribute('aria-expanded', 'true');
        // Ace ignores submenu clicks while the parent slide animation is running.
        await expect(page.locator('#mm-primary-menu .submenu.nav-show').first()).not.toHaveAttribute('style', /height/);
        const setup = menuLink(page, 'Setup');
        await setup.click();
        await expect(setup).toHaveAttribute('aria-expanded', 'true');
        await expect(menuLink(page, 'Purpose')).toBeVisible();
        await page.locator('#mm-menu-filter').fill('general ledger');
        await expect(menuLink(page, 'General ledger')).toBeVisible();
        await expect(menuLink(page, 'Accounts & Finance')).toHaveAttribute('aria-expanded', 'true');
        await expect(hotel).toBeHidden();
        await page.getByRole('button', { name: 'Clear menu search' }).click();
        await expect(page.locator('#mm-menu-filter')).toBeFocused();
        await expect(hotel).toHaveAttribute('aria-expanded', 'true');
        await expect(menuLink(page, 'Accounts & Finance')).toHaveAttribute('aria-expanded', 'false');
        expect(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth)).toBeTruthy();
        expect(errors).toEqual([]);
    });
}

test('query-specific menu links have a single current-page marker', async ({ page }) => {
    await fixture(page);
    await page.route('http://mm-shell.test/hotel/booking-purpose**', route => route.fulfill({ path: 'tools/fixtures/full-menu.html' }));
    await page.goto('http://mm-shell.test/hotel/booking-purpose?type=platform');
    await expect(page.locator('#mm-primary-menu [aria-current="page"]')).toHaveCount(1);
    await expect(menuLink(page, 'Platform')).toHaveAttribute('aria-current', 'page');
    await expect(menuLink(page, 'Purpose')).not.toHaveAttribute('aria-current');
});
