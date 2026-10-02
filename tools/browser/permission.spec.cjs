// Permission screens rendered from the real Blade views (tools/fixtures/permission/*.html):
// module/sub module/parent permission lists, permissions, permitted users, password forms and the access matrices.
const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

async function open(page, name, width = 1280) {
    await page.setViewportSize({ width, height: 900 });
    const errors = [];
    page.on('pageerror', e => errors.push(e.message));
    const requests = [];
    const html = fs.readFileSync(`tools/fixtures/permission/${name}.html`, 'utf8');
    await page.addInitScript(() => { window.printCalls = 0; window.print = () => { window.printCalls += 1; }; });
    await page.route('http://mm-perm.test/**', route => {
        const request = route.request();
        const url = new URL(request.url());
        if (url.pathname.startsWith('/assets/')) {
            const file = path.join(process.cwd(), 'public', url.pathname);
            return fs.existsSync(file) ? route.fulfill({ path: file }) : route.fulfill({ status: 404, body: '' });
        }
        requests.push({ method: request.method(), path: url.pathname, post: request.postData() });
        return route.fulfill({ contentType: 'text/html', body: html });
    });
    await page.goto('http://mm-perm.test/setting/x');
    requests.length = 0;
    return { errors, requests };
}

const ignorable = e => /toastr|chosen|ace_file_input|datepicker|daterangepicker|tag is not|parsley|swal|Swal|dataTable|DataTable/i.test(e);
const screens = {
    'module': 'Modules',
    'submodule': 'Sub modules',
    'parent-permission': 'Parent permissions',
    'permission-index': 'User permissions',
    'permission-create': 'Create permission',
    'permission-edit': 'Edit permission',
    'users-index': 'Permitted users',
    'users-create': 'Add new user',
    'change-password': 'Change password',
    'change-password-admin': 'Change user password',
    'access-create': 'User role and permissions',
    'access-edit': 'Edit user permissions',
    'employee-permission': 'Employee permissions',
};

for (const [name, title] of Object.entries(screens)) {
    test(`${name} renders in the shared frame without script errors`, async ({ page }) => {
        const { errors } = await open(page, name);
        await expect(page.locator('h1')).toHaveText(new RegExp(title));
        await expect(page.locator('.mm-page-title')).toHaveCount(1);
        await expect(page.locator('.widget-box, .widget-main, .widget-header, .page-header')).toHaveCount(0);
        expect(errors.filter(e => !ignorable(e))).toEqual([]);
    });
    for (const width of [360, 768]) {
        test(`${name} does not scroll sideways at ${width}px`, async ({ page }) => {
            await open(page, name, width);
            const { scroll, client } = await page.evaluate(() => ({ scroll: document.documentElement.scrollWidth, client: document.documentElement.clientWidth }));
            expect(scroll).toBeLessThanOrEqual(client);
        });
    }
}

test('module: the add form posts the original field and the list keeps its row actions', async ({ page }) => {
    await open(page, 'module');
    await expect(page.locator('#dynamic-table tbody tr')).toHaveCount(2);
    await expect(page.locator('#dynamic-table')).toContainText('Hotel <b>Core</b>');
    await expect(page.locator('#dynamic-table [onclick^="delete_check("]')).toHaveCount(2);
    await expect(page.locator('#dynamic-table a[href$="/setting/modules/1/edit"]')).toHaveCount(1);
    const form = page.locator('form[action$="/setting/modules"]');
    await expect(form.locator('[name=name]')).toBeVisible();
    await expect(form.locator('button[type=submit]')).toBeVisible();
    await expect(page.locator('.pagination')).toBeVisible();
});

test('users list: table scrolls inside its panel and keeps the password popover and row actions', async ({ page }) => {
    await open(page, 'users-index', 360);
    await expect(page.locator('#data-table tbody tr')).toHaveCount(2);
    await expect(page.locator('#data-table')).toContainText('Rahim <b>Uddin</b>');
    await expect(page.locator('#data-table')).toContainText('Not an Employee');
    await expect(page.locator('.mm-table-scroll').first()).toBeVisible();
    await expect(page.locator('[data-rel=popover]')).toHaveCount(1);
    await expect(page.locator('[onclick="delete_check(5)"]')).toHaveCount(1);
    await expect(page.locator('form#deleteCheck_5 [name=_method]')).toHaveValue('DELETE');
    // the header cells no longer force white text; they follow the shared table header colour
    const colors = await page.locator('#data-table thead th').first().evaluate(el => getComputedStyle(el).color);
    expect(colors).not.toBe('rgb(255, 255, 255)');
});

test('new user: labels sit beside the fields on desktop and stack on a phone; the Close link works', async ({ page }) => {
    await open(page, 'users-create');
    const label = page.locator('label[for=name]');
    const input = page.locator('input[name=name]');
    const lb = await label.boundingBox();
    const ib = await input.boundingBox();
    expect(ib.x).toBeGreaterThan(lb.x + lb.width - 4);
    expect(Math.abs(ib.y - lb.y)).toBeLessThan(30);
    await expect(page.getByRole('link', { name: /Close/ })).toHaveAttribute('href', /permitted-users$/);
    await page.setViewportSize({ width: 360, height: 800 });
    const lb2 = await label.boundingBox();
    const ib2 = await input.boundingBox();
    expect(ib2.y).toBeGreaterThan(lb2.y + lb2.height - 4);
    expect(ib2.x + ib2.width).toBeLessThanOrEqual(360);
});

test('password forms: every field keeps its name and the show/hide buttons stay inside the screen', async ({ page }) => {
    await open(page, 'change-password-admin', 360);
    for (const name of ['new_password', 'confirm_password', 'id']) await expect(page.locator(`[name=${name}]`)).toHaveCount(1);
    await expect(page.locator('input[name=id]')).toHaveValue('5');
    const boxes = await page.locator('.input-group').evaluateAll(els => els.map(e => { const r = e.getBoundingClientRect(); return { right: r.right, left: r.left }; }));
    expect(boxes.length).toBeGreaterThan(1);
    for (const b of boxes) { expect(b.left).toBeGreaterThanOrEqual(0); expect(b.right).toBeLessThanOrEqual(360); }
    await expect(page.getByRole('link', { name: /Permitted User List/i })).toBeVisible();
});

test('permission create: the action checkboxes are ticked by default and the parent select is kept', async ({ page }) => {
    await open(page, 'permission-create');
    await expect(page.locator('input[name="actions[]"]')).toHaveCount(6);
    await expect(page.locator('input[name="actions[]"]:checked')).toHaveCount(6);
    await expect(page.locator('select[name=parent_permission_id]')).toHaveCount(1);
});

test('access matrix: module checkbox ticks everything below it and Select All ticks one sub module', async ({ page }) => {
    await open(page, 'access-create');
    const permissions = page.locator('input[name="permissions[]"]');
    await expect(permissions).toHaveCount(4);
    await expect(page.locator('input[name="permissions[]"]:checked')).toHaveCount(0);
    const tick = selector => page.locator(selector).first().evaluate(el => el.click());
    await tick('.module-checkbox-control');
    await expect(page.locator('input[name="permissions[]"]:checked')).toHaveCount(4);
    await tick('.module-checkbox-control');
    await expect(page.locator('input[name="permissions[]"]:checked')).toHaveCount(0);
    await tick('.access-control .parentCheckBox');
    await expect(page.locator('input[name="permissions[]"]:checked')).toHaveCount(4);
});

test('access matrix: the employee picker fills the read-only employee fields and feature tables stay in the panel', async ({ page }) => {
    await open(page, 'access-create', 360);
    await page.locator('#select-new-employee-id').evaluate(el => { el.value = '1'; el.dispatchEvent(new Event('change')); });
    await expect(page.locator('input[name=employee_full_id]')).toHaveValue('EMP-001');
    await expect(page.locator('input[name=email]')).toHaveValue('e1@example.com');
    await expect(page.locator('input[name=department]')).toHaveValue('Front desk');
    await expect(page.locator('input[name=designation]')).toHaveValue('Manager');
    await expect(page.locator('input[name="companies[]"]')).toHaveCount(2);
    await expect(page.locator('input[name="departments[]"]')).toHaveCount(2);
    await expect(page.locator('input[name="designations[]"]')).toHaveCount(2);
    const panel = await page.locator('.mm-panel').first().boundingBox();
    for (const box of await page.locator('.input-group').evaluateAll(els => els.map(e => e.getBoundingClientRect().right))) expect(box).toBeLessThanOrEqual(panel.x + panel.width + 1);
});

test('access edit: stored permissions are ticked and the update form uses PUT', async ({ page }) => {
    await open(page, 'access-edit');
    await expect(page.locator('form[action$="/setting/permission-access/5"] [name=_method]')).toHaveValue('put');
    await expect(page.locator('input[name="permissions[]"][value="11"]')).toBeChecked();
    await expect(page.locator('input[name="permissions[]"][value="12"]')).not.toBeChecked();
    await expect(page.getByRole('button', { name: /Update/ })).toBeVisible();
});

test('permission screens in dark theme keep readable panels', async ({ page }) => {
    await open(page, 'access-create');
    await page.evaluate(() => document.body.classList.add('mm-dark'));
    const bg = await page.locator('.mm-panel').first().evaluate(el => getComputedStyle(el).backgroundColor);
    expect(bg).not.toBe('rgb(255, 255, 255)');
});
