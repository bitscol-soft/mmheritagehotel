const fs = require('fs');
const assert = require('assert');
const postcss = require('postcss');
const { execFileSync } = require('child_process');
const config = require('../tailwind.config');
assert.equal(config.prefix, 'tw-');
assert.equal(config.corePlugins.preflight, false);
const css = fs.readFileSync('public/assets/custom_css/ui.css', 'utf8');
postcss.parse(css).walkRules(rule => {
    for (const selector of rule.selectors) assert(selector.startsWith('.mm-ui'), `Unscoped CSS: ${selector}`);
});
assert(css.includes('24px'));
const paths = ['module/Hotel/views/guests/index.blade.php', 'module/Hotel/views/guests/include/filter.blade.php', 'module/Hotel/views/booking/index.blade.php', 'module/Hotel/views/booking/_inc/_filter.blade.php'];
for (const path of paths) {
    const before = execFileSync('git', ['show', `2227b07a:${path}`], { encoding: 'utf8' });
    const after = fs.readFileSync(path, 'utf8');
    for (const regex of [/\bname="[^"]+"/g, /\bid="[^"]+"/g, /\bonclick="[^"]+"/g, /route\([^\n)]*\)/g, /hasPermission\([^\n]+/g]) {
        for (const hook of before.match(regex) || []) assert(after.includes(hook), `Removed hook ${hook} in ${path}`);
    }
    assert(!after.includes('<style'), `Page stylesheet in ${path}`);
}
assert(fs.readFileSync('routes/web.php','utf8').includes("Route::view('/ui-kit', 'ui.kit')->middleware(['auth', 'super-admin'])"));
console.log('PASS: scoped CSS, no Preflight, guest hook preservation, protected gallery');

const bookingPath = 'module/Hotel/views/booking/index.blade.php';
const originalBooking = execFileSync('git', ['show', `2227b07a:${bookingPath}`], { encoding: 'utf8' });
const currentBooking = fs.readFileSync(bookingPath, 'utf8');
assert.equal(currentBooking.split("@section('js')")[1], originalBooking.split("@section('js')")[1], 'Booking JavaScript changed');
const tablePath = 'module/Hotel/views/booking/_inc/_booking-table.blade.php';
assert.equal(fs.readFileSync(tablePath,'utf8'), execFileSync('git',['show',`2227b07a:${tablePath}`],{encoding:'utf8'}), 'Booking table / amount expressions changed');
console.log('PASS: booking JavaScript and financial table byte-identical');

const guestFormPaths = ['module/Hotel/views/guests/create.blade.php', 'module/Hotel/views/guests/edit.blade.php', 'module/Hotel/views/guests/create/create.blade.php', 'module/Hotel/views/guests/create/upload.blade.php'];
for (const path of guestFormPaths) {
    const before = execFileSync('git', ['show', `2227b07a:${path}`], {encoding:'utf8'});
    const after = fs.readFileSync(path, 'utf8');
    for (const regex of [/\b(?:name|id|action|method|enctype|target|data-selected|data-target)="[^"]+"/g, /\bonclick="[^"]+"/gi, /route\([^\n)]*\)/g, /@csrf|@method\('[^']+'\)/g, /{{[\s\S]*?}}/g]) {
        for (const hook of before.match(regex) || []) assert(after.includes(hook), `Guest form contract removed: ${hook} in ${path}`);
    }
    if (before.includes("@section('js')")) assert.equal(after.split("@section('js')")[1], before.split("@section('js')")[1], `Guest form JS changed: ${path}`);
    assert(!after.includes('<style'), `Inline style block: ${path}`);
}
console.log('PASS: guest create/edit/import contracts, value expressions and JavaScript preserved');

const boardPath = 'module/Hotel/views/booking/booking_ui.blade.php';
const boardBefore = execFileSync('git', ['show', `2227b07a:${boardPath}`], {encoding: 'utf8'});
const boardAfter = fs.readFileSync(boardPath, 'utf8');
assert.deepEqual(boardAfter.match(/@php[\s\S]*?@endphp/g), boardBefore.match(/@php[\s\S]*?@endphp/g), 'Board date/occupancy math changed');
assert.deepEqual(boardAfter.match(/{{[\s\S]*?}}/g)?.sort(), boardBefore.match(/{{[\s\S]*?}}/g)?.sort(), 'Board expressions changed');
assert.equal(boardAfter.split("@section('script')")[1], boardBefore.split("@section('script')")[1], 'Board scripts changed');
assert(boardAfter.includes('<x-room-manage :categories="$categories" :mixdate="$availablity_check" />'));
assert(!boardAfter.includes('<style'));
for (const path of ['resources/views/components/room-manage.blade.php', 'resources/views/components/room-status.blade.php', 'resources/views/home/_inc/script.blade.php']) {
    assert.equal(fs.readFileSync(path, 'utf8'), execFileSync('git', ['show', `2227b07a:${path}`], {encoding: 'utf8'}), `Shared board behavior changed: ${path}`);
}
console.log('PASS: board calculations, expressions, shared tile components and scripts unchanged');

for (const path of ['module/Hotel/views/rooms/index.blade.php', 'module/Hotel/views/category/index.blade.php']) {
    const before = execFileSync('git', ['show', `2227b07a:${path}`], {encoding: 'utf8'});
    const after = fs.readFileSync(path, 'utf8');
    assert.equal(after.split("@section('js')")[1], before.split("@section('js')")[1], `Inventory JS changed: ${path}`);
    // Entire table body remains byte-identical except added accessible names.
    const body = source => source.match(/<tbody>[\s\S]*?<\/tbody>/)[0].replace(/ aria-label="[^"]*"/g, '');
    assert.equal(body(after), body(before), `Inventory price/status/body changed: ${path}`);
    for (const regex of [/\b(?:id|name|method|action)="[^"]*"/g, /route\([^\n)]*\)/g, /@csrf|@method\('[^']+'\)/g]) {
        for (const hook of before.match(regex) || []) assert(after.includes(hook), `Inventory hook removed: ${hook}`);
    }
    assert(!after.includes('<style'));
}
console.log('PASS: room/category table data, pricing, statuses, forms and DataTables JS preserved');

for (const path of ['module/Hotel/views/rooms/create.blade.php', 'module/Hotel/views/rooms/edit.blade.php']) {
    const before = execFileSync('git', ['show', `2227b07a:${path}`], {encoding: 'utf8'});
    const after = fs.readFileSync(path, 'utf8');
    assert.equal(after.split("@section('js')")[1], before.split("@section('js')")[1], `Room form JS changed: ${path}`);
    for (const regex of [/\b(?:id|name|method|action|enctype|onkeyup|onclick)="[^"]*"/g, /@csrf|@method\('[^']+'\)/g, /{{[\s\S]*?}}/g, /@error\('[^']+'\)/g, /@if\s*\([^\n]+/g]) {
        for (const hook of before.match(regex) || []) assert(after.includes(hook), `Room form hook/value removed: ${hook}`);
    }
    const compact = text => text.replace(/\s+/g, ' ').trim();
    assert.deepEqual((after.match(/{!![\s\S]*?!!}/g) || []).map(compact), (before.match(/{!![\s\S]*?!!}/g) || []).map(compact), 'Category selector expression changed');
    // Keep div ancestry INSIDE the form exactly: shared JS uses closest('.row').
    const form = source => source.match(/<form\b[\s\S]*?<\/form>/)[0];
    const divs = source => (form(source).match(/<\/?div\b[^>]*>/g) || []).map(tag => tag.startsWith('</') ? '</div>' : tag.match(/class="([^"]*)"/)?.[1]).filter(tag => !tag?.startsWith('form-actions'));
    assert.deepEqual(divs(after), divs(before), 'Room form row ancestry changed');
    assert.deepEqual(form(after).match(/\brequired\b/g), form(before).match(/\brequired\b/g), 'Required validation changed');
    assert.deepEqual(form(after).match(/<option\b[\s\S]*?<\/option>/g).map(compact), form(before).match(/<option\b[\s\S]*?<\/option>/g).map(compact), 'Room status options changed');
    assert(!after.includes('for="form-field-1-1"'), 'Unassociated room label remains');
}
const roomScript = 'module/Hotel/views/rooms/inc/script.blade.php';
assert.equal(fs.readFileSync(roomScript, 'utf8'), execFileSync('git', ['show', `2227b07a:${roomScript}`], {encoding:'utf8'}), 'Room duplicate-number/submission script changed');
console.log('PASS: room forms, values, validation, category selector, row ancestry and duplicate-check JS preserved');

// Shared shell boundary and permission/action contracts.
postcss.parse(fs.readFileSync('public/assets/custom_css/shell.css', 'utf8')).walkRules(rule => {
    for (const selector of rule.selectors) assert(selector.startsWith('.mm-shell'), `Unscoped shell CSS: ${selector}`);
});
const masterPath = 'resources/views/layouts/master.blade.php';
const masterBefore = execFileSync('git', ['show', `2227b07a:${masterPath}`], {encoding:'utf8'});
const masterAfter = fs.readFileSync(masterPath, 'utf8');
for (const condition of masterBefore.match(/\$(?:isAdminHeader|isAdminSidebar|isEmployeeHeader|isShowFooter) = [^;]+;/g)) assert(masterAfter.includes(condition), `Layout exclusion changed: ${condition}`);
assert(masterAfter.includes("config('ui.admin_shell', true) && $isAdminHeader && $isAdminSidebar"));
for (const path of ['resources/views/partials/_header.blade.php', 'resources/views/partials/_sidebar.blade.php']) {
    const before = execFileSync('git', ['show', `2227b07a:${path}`], {encoding:'utf8'});
    const after = fs.readFileSync(path, 'utf8');
    for (const regex of [/{{[\s\S]*?}}/g, /@(?:if|elseif|foreach|include)\([^\n]+/g, /\b(?:id|href|onclick|method|action|data-target)="[^"]+"/g, /@csrf/g, /@php[\s\S]*?@endphp/g]) {
        for (const contract of before.match(regex) || []) assert(after.includes(contract), `Shell contract removed: ${contract} in ${path}`);
    }
}
for (const path of ['resources/views/layouts/includes/master-file-script.blade.php', 'resources/views/partials/_footer.blade.php']) assert.equal(fs.readFileSync(path,'utf8'), execFileSync('git',['show',`2227b07a:${path}`],{encoding:'utf8'}), `Legacy shared code changed: ${path}`);
assert(fs.readFileSync('resources/views/partials/_sidebar.blade.php','utf8').includes('@unless ($mmShell ?? false)'));
console.log('PASS: shell CSS isolation, layout exclusions, header actions/permissions, dynamic sidebar and shared scripts preserved');
assert(fs.readFileSync('tools/fixtures/admin-shell.html', 'utf8').includes(fs.readFileSync('resources/views/layouts/shell/navigation-tools.blade.php', 'utf8')), 'Browser fixture navigation tools drifted from Blade partial');

const dashPath = 'resources/views/home/hotel-dashboard.blade.php';
const dashBefore = execFileSync('git', ['show', `d96fc4cf:${dashPath}`], {encoding:'utf8'});
const dashAfter = fs.readFileSync(dashPath,'utf8');
const dashSummary = fs.readFileSync('resources/views/home/_inc/dashboard-summary.blade.php','utf8');
const uncomment = s => s.replace(/{{--[\s\S]*?--}}/g, '');
for (const expression of uncomment(dashBefore).match(/{{[\s\S]*?}}/g) || []) assert((dashAfter + dashSummary).includes(expression), `Dashboard expression changed: ${expression}`);
assert.equal(dashAfter.split("@section('js')")[1], dashBefore.split("@section('js')")[1]);
for (const condition of dashBefore.match(/@if\([^\n]+|@if \([^\n]+/g) || []) assert(dashAfter.includes(condition));
for (const path of ['resources/views/home/_inc/booking_ui.blade.php','resources/views/home/_inc/script.blade.php']) assert.equal(fs.readFileSync(path,'utf8'), execFileSync('git',['show',`d96fc4cf:${path}`],{encoding:'utf8'}));
assert(!dashAfter.includes('<style>'));
console.log('PASS: dashboard displayed expressions, visibility gates, scripts and shared booking board preserved');

const keepingPath = 'module/Hotel/views/house-keeping/index.blade.php';
const keepingBefore = execFileSync('git', ['show', `d96fc4cf:${keepingPath}`], {encoding:'utf8'});
const keepingAfter = fs.readFileSync(keepingPath,'utf8');
assert.equal(keepingAfter.split("@section('script')")[1], keepingBefore.split("@section('script')")[1]);
assert.deepEqual(keepingAfter.match(/@php[\s\S]*?@endphp/g), keepingBefore.match(/@php[\s\S]*?@endphp/g));
assert(keepingAfter.includes('<x-room-keeping :categories="$categories" :mixdate="$booking_date" />'));
for (const path of ['resources/views/components/room-keeping.blade.php','resources/views/components/room-status-keeping.blade.php','module/Hotel/views/house-keeping/_script/script.blade.php']) assert.equal(fs.readFileSync(path,'utf8'), execFileSync('git',['show',`d96fc4cf:${path}`],{encoding:'utf8'}), `Housekeeping behavior changed: ${path}`);
assert(!keepingAfter.includes('<style>'));
console.log('PASS: housekeeping PHP, room components, permission gate and status-update scripts preserved');

for (const mode of ['index','create','edit']) {
 const path=`module/Hotel/views/booking-purpose/${mode}.blade.php`;
 const before=execFileSync('git',['show',`8955dbdc:${path}`],{encoding:'utf8'}), after=fs.readFileSync(path,'utf8');
 assert.equal(after.split("@section('js')")[1],before.split("@section('js')")[1]);
 for(const re of [/\b(?:name|id|method|action|enctype)="[^"]+"/g, /@csrf|@method\('[^']+'\)/g]) for(const value of before.match(re)||[]) assert(after.includes(value),`Setup contract removed: ${value}`);
 if(mode==='index') assert.deepEqual(after.match(/<tbody>[\s\S]*?<\/tbody>/g).map(s=>s.replace('aria-label="Edit booking label" ','')),before.match(/<tbody>[\s\S]*?<\/tbody>/g));
}
console.log('PASS: booking setup forms, table data and scripts preserved');
