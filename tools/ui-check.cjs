const fs = require('fs');
const assert = require('assert');

// Shared board sources are byte-guarded against earlier commits, except for the documented stay date-range change:
// room-manage lost its inline chip script (moved to custom_js/stay-range.js) and gained data-business-date, and
// home/_inc/script.blade.php only changed inside the booking_date picker initialisation.
function assertSharedBoardSource(path, commit) {
    const base = execFileSync('git', ['show', `${commit}:${path}`], {encoding: 'utf8'});
    const current = fs.readFileSync(path, 'utf8');
    const collapse = text => text.replace(/\s+/g, ' ').trim();
    if (path.endsWith('components/room-manage.blade.php')) {
        assert.equal((base.match(/<script>/g) || []).length, 1, 'room-manage baseline should hold only the chip script');
        const expected = base.replace(/<script>[\s\S]*?<\/script>/, '').replace('autocomplete="off"', 'autocomplete="off" data-business-date="{{ today_from_system() }}"');
        assert.equal(collapse(current), collapse(expected), `Shared board behavior changed: ${path}`);
    } else if (path.endsWith('home/_inc/script.blade.php')) {
        const start = "$('input[name=\"booking_date\"]').daterangepicker({", end = 'function updateStatus(id, status, e)';
        assert.equal(current.slice(0, current.indexOf('var $stayRange')).trimEnd(), base.slice(0, base.indexOf(start)).trimEnd(), `Shared board script prefix changed: ${path}`);
        assert.equal(current.slice(current.indexOf(end)), base.slice(base.indexOf(end)), `Shared board script suffix changed: ${path}`);
        assert(current.includes('MMStayRange.init(this)') && current.includes('$stayRange.daterangepicker({'), 'script.blade must init the stay range with a legacy fallback');
    } else {
        assert.equal(current, base, `Shared board source changed: ${path}`);
    }
}

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
const stayRangeTag = "{{ asset('assets/custom_js/stay-range.js') }}";
assert(boardAfter.includes(stayRangeTag), 'Booking board must load stay-range.js');
assert.deepEqual(boardAfter.match(/{{[\s\S]*?}}/g)?.filter(e => e !== stayRangeTag).sort(), boardBefore.match(/{{[\s\S]*?}}/g)?.sort(), 'Board expressions changed');
assert.equal(boardAfter.split("@section('script')")[1].replace("    <script src=\""+stayRangeTag+"\"></script>\n", ''), boardBefore.split("@section('script')")[1], 'Board scripts changed');
assert(boardAfter.includes('<x-room-manage :categories="$categories" :mixdate="$availablity_check" />'));
assert(!boardAfter.includes('<style'));
for (const path of ['resources/views/components/room-manage.blade.php', 'resources/views/components/room-status.blade.php', 'resources/views/home/_inc/script.blade.php']) assertSharedBoardSource(path, '2227b07a');
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
const roomBoardTag = "    <script src=\"{{ asset('assets/custom_js/room-board.js') }}\"></script>\n";
assert(dashAfter.includes(roomBoardTag), 'Dashboard must load room-board.js');
assert.equal(dashAfter.replace(roomBoardTag, '').replace("    <script src=\"{{ asset('assets/custom_js/stay-range.js') }}\"></script>\n", '').split("@section('js')")[1], dashBefore.split("@section('js')")[1]);
for (const condition of dashBefore.match(/@if\([^\n]+|@if \([^\n]+/g) || []) assert(dashAfter.includes(condition));
assertSharedBoardSource('resources/views/home/_inc/script.blade.php', 'd96fc4cf');
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

for (const mode of ['index','edit']) {
 const path=`module/Hotel/views/booking-note/${mode}.blade.php`;
 const before=execFileSync('git',['show',`551d4d76:${path}`],{encoding:'utf8'}), after=fs.readFileSync(path,'utf8');
 const section=mode==='index'?"@section('script')":"@section('js')";
 assert.equal(after.split(section)[1],before.split(section)[1]);
 for(const re of [/\b(?:name|id|method|action|enctype)="[^"]+"/g, /@csrf|@method\('[^']+'\)/g, /{{[\s\S]*?}}/g]) for(const value of uncomment(before).match(re)||[]) assert(after.includes(value),`Note contract removed: ${value}`);
}
for (const path of ['module/Hotel/Controllers/BookingNoteController.php','module/Hotel/views/booking-note/create.blade.php','resources/views/components/status.blade.php']) assert.equal(fs.readFileSync(path,'utf8'),execFileSync('git',['show',`551d4d76:${path}`],{encoding:'utf8'}));
console.log('PASS: note list/edit expressions, forms and status scripts; legacy backend unchanged');

{
 const shellCss=fs.readFileSync('resources/css/shell.css','utf8'), shellJs=fs.readFileSync('public/assets/custom_js/shell.js','utf8');
 assert(!/#sidebar \*\s*\{\s*transition:\s*none/.test(shellCss),'Ace submenu transitions must not be disabled; they release its toggle lock');
 for(const needle of ['aria-controls','aria-expanded','queryKey','mm-menu-clear']) assert(shellJs.includes(needle),`Menu behavior missing ${needle}`);
 const sidebar=fs.readFileSync('resources/views/partials/_sidebar.blade.php','utf8'), base=execFileSync('git',['show','207fae47:resources/views/partials/_sidebar.blade.php'],{encoding:'utf8'});
 assert.equal(sidebar,base);
 console.log('PASS: full-menu behavior, Ace transition lock safeguard and unchanged permission-driven sidebar sources');
}

{
 const normalize = text => text.replace(/\s+/g, ' ').trim();
 for (const mode of ['create','edit']) {
  const path=`module/Hotel/views/category/${mode}.blade.php`;
  const before=execFileSync('git',['show',`207fae47:${path}`],{encoding:'utf8'}), after=fs.readFileSync(path,'utf8');
  assert.equal(after.split("@section('js')")[1], before.split("@section('js')")[1], `Category JS wrapper changed: ${mode}`);
  const form=source=>source.match(/<form\b[\s\S]*?<\/form>/)[0];
  const names=source=>(form(source).match(/\bname="[^"]+"/g)||[]).sort();
  assert.deepEqual(names(after),names(before),`Category field names changed: ${mode}`);
  for (const regex of [/\b(?:method|action|enctype|type|value|multiple|required)="[^"]*"/g,/@csrf|@method\('[^']+'\)/g,/{{[\s\S]*?}}/g,/@for\s*\([^\n]+/g,/@foreach\s*\([^\n]+/g]) {
   for (const hook of form(before).match(regex)||[]) assert(form(after).includes(hook),`Category form hook removed: ${hook}`);
  }
  assert.deepEqual(form(after).match(/\brequired\b/g),form(before).match(/\brequired\b/g),'Category validation changed');
  assert.deepEqual((form(after).match(/<option\b[\s\S]*?<\/option>/g)||[]).map(normalize),(form(before).match(/<option\b[\s\S]*?<\/option>/g)||[]).map(normalize),'Category status options changed');
  const divs=source=>(form(source).match(/<\/?div\b[^>]*>/g)||[]).map(tag=>tag.startsWith('</')?'</div>':tag.match(/class="([^"]*)"/)?.[1]?.replace(' category-form-actions','')?.trim());
  assert.deepEqual(divs(after),divs(before),`Category form ancestry changed: ${mode}`);
  const ids=form(after).replace(/{{--[\s\S]*?--}}/g,"").match(/\bid="[^"]+"/g)||[]; assert.equal(new Set(ids).size,ids.length,`Duplicate IDs remain: ${mode}`);
  assert(after.includes('id="capacity" name="guest_capacity"'),'Guest capacity must keep the #capacity pricing id');
  assert(!after.includes("@error('details')"),'Description error key mismatch remains');
  assert(!/suppliers\.view/.test(after),'Supplier permission remains on category toolbar');
 }
 assert.equal(fs.readFileSync('module/Hotel/views/category/inc/script.blade.php','utf8'),execFileSync('git',['show','207fae47:module/Hotel/views/category/inc/script.blade.php'],{encoding:'utf8'}),'Category pricing/photo script changed');
 console.log('PASS: category create/edit fields, values, validation, rows, status and pricing/photo scripts preserved');
}

{
 const dash=fs.readFileSync('resources/views/home/hotel-dashboard.blade.php','utf8'), baseDash=execFileSync('git',['show','3b824a86:resources/views/home/hotel-dashboard.blade.php'],{encoding:'utf8'});
 assert.equal(dash.replace(roomBoardTag,'').replace("    <script src=\"{{ asset('assets/custom_js/stay-range.js') }}\"></script>\n",'').split("@section('js')")[1],baseDash.split("@section('js')")[1],'Dashboard scripts changed');
 for(const permission of ['bookings.index','hotel.expected-arrival.index','hotel.expected-departure.index','hotel.in-house-guest.index']) assert(dash.includes(`hasPermission('${permission}', $slugs)`),`Dashboard link lost permission ${permission}`);
 for(const route of ['booking.index','report.expected-arrival','report.expected-departure','report.in-house-guest']) assert(dash.includes(`route('${route}')`),`Dashboard link route missing ${route}`);
 const board=fs.readFileSync('resources/views/home/_inc/booking_ui.blade.php','utf8');
 assert(board.includes('<x-alert-message />') && board.includes("@include('home._inc.room-board', ['categories' => $categories, 'mix_date' => $mix_date])"),'Dashboard board include contract changed');
 const roomBoard=fs.readFileSync('resources/views/home/_inc/room-board.blade.php','utf8');
 for(const hook of ['id="booking-form"','id="searchForm"','mmb-proxy','updateStatus(','name="booking_availabe"','name="submit" value="book"','name="submit" value="reserve"','data-add-url="/hotel/add_booking"','data-remove-url="/hotel/remove_booking_next"']) assert((roomBoard+fs.readFileSync('resources/views/home/_inc/room-card.blade.php','utf8')).includes(hook),`Room board lost legacy hook ${hook}`);
 const boardJs=fs.readFileSync('public/assets/custom_js/room-board.js','utf8');
 assert(!/outerHTML|insertAdjacentHTML|\.html\(/.test(boardJs),'room-board.js must not use HTML injection APIs');
 for(const line of boardJs.split('\n').filter(l=>l.includes('innerHTML'))) assert(/innerHTML = ('<i class="fa [a-z-]+" aria-hidden="true"><\/i> [A-Za-z ]+'|card\.querySelector\('\.mmb-bed'\)\.innerHTML);/.test(line),`room-board.js innerHTML must be static or copied server markup: ${line.trim()}`);
 for(const hook of ['mm-board-collapsed','aria-expanded','inert','Escape']) assert(boardJs.includes(hook),`room-board.js missing ${hook}`);
 const legacy=fs.readFileSync('public/assets/custom_css/style.css','utf8');
 for(const needle of ['.room-booking-board .room-list .row::before','.room-booking-board .room-info.inverse { background:','.room-booking-board .room-info.orange { background:']) assert(legacy.includes(needle),`Board visual fix missing: ${needle}`);
 for(const path of ['resources/views/components/room-manage.blade.php','resources/views/components/room-status.blade.php','resources/views/home/_inc/script.blade.php','resources/views/home/_inc/style.blade.php']) assertSharedBoardSource(path,'3b824a86');
 console.log('PASS: dashboard actions are permission-gated, board component/scripts unchanged, tile visual fixes present');
}

{
 // Shell header/footer enhancements: additive, permission-gated, rollback-safe, no HTML injection.
 const master=fs.readFileSync('resources/views/layouts/master.blade.php','utf8');
 assert(/@if \(\$mmShell\)\s*@include\('layouts\.shell\.footer'\)\s*@else\s*@include\('partials\._footer'\)\s*@endif/.test(master),'Legacy footer must remain the non-shell fallback');
 assert(master.includes("@include('layouts.shell.overlays')") && master.includes('shell-tools.js'),'Shell dialogs and tools script must load only inside the shell');
 const header=fs.readFileSync('resources/views/partials/_header.blade.php','utf8');
 assert(/@if \(\$mmShell \?\? false\)\s*@include\('layouts\.shell\.header-tools'\)\s*@endif/.test(header),'Header tools must be shell-only');
 const tools=fs.readFileSync('resources/views/layouts/shell/header-tools.blade.php','utf8');
 assert(tools.includes("@if (hasPermission('bookings.create', $slugs))") && tools.includes("route('booking.create')"),'New booking action must keep its permission gate');
 for(const needle of ['aria-label="New booking"','data-mm-palette-open','data-mm-theme-toggle']) assert(tools.includes(needle),`Header tools missing ${needle}`);
 const js=fs.readFileSync('public/assets/custom_js/shell-tools.js','utf8');
 assert(!/innerHTML|outerHTML|insertAdjacentHTML|document\.write|eval\(|\.html\(/.test(js),'shell-tools.js must not inject HTML');
 for(const needle of ['showModal','aria-activedescendant','mm-theme','navigator.onLine','menuEntries','isTyping']) assert(js.includes(needle),`shell-tools.js missing ${needle}`);
 const footer=fs.readFileSync('resources/views/layouts/shell/footer.blade.php','utf8');
 assert(footer.includes('id="btn-scroll-up"') && footer.includes('rel="noopener"'),'Shell footer must keep back-to-top and safe external links');
 const ui=fs.readFileSync('config/ui.php','utf8');
 for(const key of ["'admin_shell'","'version'","'support_url'","'timezone'"]) assert(ui.includes(key),`config/ui.php missing ${key}`);
 console.log('PASS: shell header tools, footer and palette are additive, gated, rollback-safe and free of HTML injection');
}

{
 // Booking lifecycle step 2 (new-booking form): only the frame, progress, summary and action buttons changed.
 const nextPath='module/Hotel/views/booking/booking_next.blade.php';
 const before=execFileSync('git',['show',`2227b07a:${nextPath}`],{encoding:'utf8'});
 const after=fs.readFileSync(nextPath,'utf8');
 const collapse=s=>s.replace(/\s+/g,' ').trim();
 const formOf=s=>s.slice(s.indexOf('<form class="form-horizontal"'),s.indexOf('</form>')+7);
 const split=s=>{const f=formOf(s);const start=f.search(/<div class="(form-group|mm-form-actions)">\s*(<label for="inputError"|<button)/);const end=f.indexOf("@include('booking/_modal/member-detail-modal')");assert(start>0&&end>start,'booking_next buttons block not found');return [collapse(f.slice(0,start)),collapse(f.slice(end))];};
 assert.deepEqual(split(after),split(before),'booking_next form fields, expressions and includes must be unchanged except the action buttons');
 assert(/<form class="form-horizontal" action="\{\{ route\('booking\.store'\) \}\}" id="submitBookingUpdateForm"\s+method="post" enctype="multipart\/form-data">\s*@csrf/.test(after),'New booking form must still post to booking.store with CSRF');
 const actions=after.slice(after.indexOf('<div class="mm-form-actions">'),after.indexOf("@include('booking/_modal/member-detail-modal')"));
 assert(actions.includes('onclick="submitBookingForm()"')&&actions.includes('type="button"')&&actions.includes('type="Reset"'),'Save must still call submitBookingForm() and Reset must stay a reset button');
 assert(after.includes('<x-alert-message />')&&after.includes("@include('booking._inc._booking-next-input-info')")&&after.includes("@include('booking._script.booking-next-script')")&&after.includes("@include('partials.modal.new_guest_modal')"),'booking_next lost an include');
 assert(!after.includes('widget-box'),'booking_next should use the shared page frame');
 for(const path of ['module/Hotel/views/booking/_inc/_booking-next-input-info.blade.php','module/Hotel/views/booking/_script/booking-next-script.blade.php','module/Hotel/views/booking/_inc/_check-sms-and-email.blade.php','module/Hotel/views/booking/_css/css.blade.php','module/Hotel/Controllers/BookingController.php']) assert.equal(fs.readFileSync(path,'utf8'),execFileSync('git',['show',`2227b07a:${path}`],{encoding:'utf8'}),`${path} must be unchanged`);
 const steps=fs.readFileSync('module/Hotel/views/booking/_inc/_booking-next-steps.blade.php','utf8');
 assert(steps.includes('aria-current="step"')&&!/<(input|form|button|script)/.test(steps)&&!steps.includes('{!!'),'Step summary must be display-only with escaped output');
 console.log('PASS: new-booking form fields, totals expressions, scripts and controller preserved; progress/summary display-only');
}

{
 // Stay date range: past dates blocked, one-night minimum, typed text validated, chips bound independent of load order.
 const js=fs.readFileSync('public/assets/custom_js/stay-range.js','utf8');
 assert(!/innerHTML|outerHTML|insertAdjacentHTML|document\.write|eval\(|\.html\(/.test(js),'stay-range.js must not inject HTML');
 for(const needle of ['data-business-date','data-allow-past','minDate','add(1, \'days\')','role','aria-invalid','$(document).on(\'click\'','autoApply']) assert(js.includes(needle),`stay-range.js missing ${needle}`);
 for(const path of ['resources/views/home/hotel-dashboard.blade.php','module/Hotel/views/booking/booking_ui.blade.php']) {
  const view=fs.readFileSync(path,'utf8');
  assert(view.indexOf("custom_js/stay-range.js")>0 && view.indexOf("custom_js/stay-range.js")<view.indexOf("@include('home._inc.script')"),`${path} must load stay-range.js before the board script`);
 }
 for(const path of ['resources/views/home/_inc/room-board.blade.php','resources/views/components/room-manage.blade.php']) {
  const view=fs.readFileSync(path,'utf8');
  assert(view.includes('data-business-date="{{ today_from_system() }}"')&&view.includes('name="booking_date"'),`${path} must render the business date on the range input`);
  assert(!/<script>/.test(view),`${path} must not bind chips inline (moment loads after the content)`);
 }
 console.log('PASS: stay date range blocks past check-in, keeps one night minimum, validates typed text and binds chips after scripts load');
}

{
 // Booking lifecycle: create/edit frames, action bars and single-date fields. Fields, totals and scripts stay as they were.
 const collapse=s=>s.replace(/\s+/g,' ').trim();
 const base=path=>execFileSync('git',['show',`2227b07a:${path}`],{encoding:'utf8'});
 const formOf=s=>s.slice(s.indexOf('<form class="form-horizontal"'),s.indexOf('</form>')+7);
 for(const name of ['create','edit']) {
  const path=`module/Hotel/views/booking/${name}.blade.php`, after=fs.readFileSync(path,'utf8');
  assert.equal(collapse(formOf(after)),collapse(formOf(base(path))),`${name}: form fields, expressions and includes must be unchanged`);
  assert(after.includes('<x-mm.page class="mm-booking-next"')&&!after.includes('widget-box'),`${name}: should use the shared page frame`);
  assert(after.includes("{{ asset('assets/custom_js/stay-dates.js') }}")&&after.indexOf('stay-dates.js')>after.indexOf("@include('booking._script.script')"),`${name}: stay-dates.js must load after the legacy booking script`);
  const actions=after.slice(after.indexOf('<div class="mm-form-actions">'),after.indexOf('</x-mm.panel>'));
  assert((actions.match(/onclick="submitBookingForm\(\)"/g)||[]).length===(name==='create'?2:1),`${name}: submit buttons must keep onclick="submitBookingForm()"`);
  assert(actions.includes('updateBookingBtn')&&actions.includes('type="Reset"'),`${name}: action classes/reset changed`);
  if(name==='create') assert(actions.includes('value="reserve"')&&actions.includes('value="book"')&&actions.includes('next-step-btn'),'create: reserve/book buttons changed');
  if(name==='edit') assert(after.includes("@include('booking._inc._booking-context', ['booking' => $booking])")&&after.includes("action=\"{{ route('booking.update', $booking->id) }}\""),'edit: context strip or update route changed');
 }
 const createAfter=fs.readFileSync('module/Hotel/views/booking/create.blade.php','utf8');
 assert.equal(collapse(createAfter.replace("$date = today_from_system();","$date = date('Y-m-d');").split('@endsection')[0].split("@section('content')")[1].split('<x-mm.styles />')[0]),collapse(base('module/Hotel/views/booking/create.blade.php').split("@section('content')")[1].split('<div class="row">')[0]),'create: date defaults changed beyond using the business date');
 // Guest-field partials: only the documented date attributes changed.
 const addPath='module/Hotel/views/booking/_inc/_add-guest-input-info.blade.php', editPath='module/Hotel/views/booking/_inc/_edit-guest-input-info.blade.php';
 const addExpected=base(addPath).replace(`value="{{ old('check_in_date', date('Y-m-d')) }}"`,`value="{{ old('check_in_date', today_from_system()) }}" data-business-date="{{ today_from_system() }}" autocomplete="off"`);
 assert.equal(collapse(fs.readFileSync(addPath,'utf8')),collapse(addExpected),'add guest partial changed beyond the check-in date attributes');
 const editExpected=base(editPath).replace(`value="{{ old('check_in_date', date('Y-m-d')) }}"`,`value="{{ old('check_in_date', $booking->check_in_date) }}" data-business-date="{{ today_from_system() }}" data-allow-past="1" autocomplete="off"`).replace('data-date-format="dd-mm-yyyy">','data-date-format="yyyy-mm-dd" autocomplete="off">');
 assert.equal(collapse(fs.readFileSync(editPath,'utf8')),collapse(editExpected),'edit guest partial changed beyond the date attributes');
 for(const path of ['module/Hotel/views/booking/_script/script.blade.php','module/Hotel/views/booking/_script/update-customer-script.blade.php','module/Hotel/views/booking/_inc/create-edit-tfoot.blade.php','module/Hotel/Controllers/BookingController.php','module/Hotel/Services/BookingService.php']) assert.equal(fs.readFileSync(path,'utf8'),base(path),`${path} must be unchanged`);
 const js=fs.readFileSync('public/assets/custom_js/stay-dates.js','utf8');
 assert(!/innerHTML|outerHTML|insertAdjacentHTML|document\.write|eval\(|\.html\(/.test(js),'stay-dates.js must not inject HTML');
 for(const needle of ['data-business-date','data-allow-past','setStartDate','mmStayGuarded','submitBookingForm','role','aria-invalid',"trigger('change')"]) assert(js.includes(needle),`stay-dates.js missing ${needle}`);
 console.log('PASS: booking create/edit fields, totals scripts, controller preserved; single-date fields enforce business date and check-out after check-in');
}

{
 // Booking lifecycle: checkout and payment screen. Expressions, hidden fields, form controls and the calculation script stay as they were.
 const path='module/Hotel/views/booking/view.blade.php', after=fs.readFileSync(path,'utf8');
 const before=execFileSync('git',['show',`84662702:${path}`],{encoding:'utf8'});
 const ex=s=>(s.replace(/\{\{--[\s\S]*?--\}\}/g,'').match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' ')).sort();
 assert.deepEqual(ex(after),ex(before),'checkout: Blade expressions changed');
 const ctl=s=>(s.replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 // Allowed: the four nameless read-only display inputs became text, and the submit button took the shared class.
 assert.deepEqual(only(ctl(before),ctl(after)),['+1 <button class="mm-button" type="submit">','-1 <button class="btn-outline-success btn-sm" type="submit">','-4 <input type="text" value="{{}}" readonly>'].sort(),'checkout: form controls changed beyond the read-only display inputs and the submit button class');
 const formTag=s=>s.match(/<form[^>]*>/)[0].replace(/\s+/g,' ');
 assert.equal(formTag(after),formTag(before),'checkout: form tag changed');
 const php=s=>(s.match(/@php[\s\S]*?@endphp/g)||[]);
 assert.deepEqual(php(after),php(before),'checkout: @php blocks changed');
 const js=s=>s.slice(s.indexOf("@section('js')"));
 assert.equal(js(after),js(before),'checkout: calculation script must be byte-identical');
 const table=s=>s.slice(s.indexOf('<table'),s.indexOf('</table>'));
 assert.equal(table(after).replace(/\s+/g,' '),table(before).replace(/\s+/g,' '),'checkout: charges table changed');
 for(const needle of ['grand-subtotal','grand-service-charge','grand-extra-charge','grand-vat-amount','grand-total-amount','payable-amount','current-due','id="get-due"','id="discount"','id="paidAmount"','id="check-full-payment"']) assert(after.includes(needle),`checkout: missing ${needle}`);
 assert(after.includes('<x-mm.page class="mm-booking-checkout"')&&!after.includes('widget-header')&&!after.includes('widget-box'),'checkout: should use the shared page frame');
 console.log('PASS: checkout expressions, form controls, charges table and calculation script preserved');
}

{
 // Booking lifecycle: invoices. The printed document, its expressions, calculations and scripts stay as they were;
 // only the on-screen frame (and, for v3, a screen-only action bar) changed.
 const collapse=s=>s.replace(/\s+/g,' ').trim();
 const base=path=>execFileSync('git',['show',`ec67feb9:${path}`],{encoding:'utf8'});
 const exprs=s=>(s.replace(/\{\{--[\s\S]*?--\}\}/g,'').match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' ')).sort();
 const phpBlocks=s=>s.match(/@php[\s\S]*?@endphp/g)||[];
 for(const name of ['checkout_invoice','reservation-invoice']) {
  const path=`module/Hotel/views/booking/${name}.blade.php`, after=fs.readFileSync(path,'utf8'), before=base(path);
  assert.deepEqual(exprs(after).filter((x,i,a)=>!(x==="{{ route('booking.index') }}"&&a.indexOf(x)===i)),exprs(before),`${name}: Blade expressions changed beyond the Booking List link`);
  assert.deepEqual(phpBlocks(after),phpBlocks(before),`${name}: @php blocks changed`);
  const region=(s,tail)=>collapse(s.slice(s.indexOf('<div id="print_body"'),s.indexOf('@endsection',s.indexOf('<div id="print_body"')))).replace(tail,'').trim();
  assert.equal(region(after,/(<\/x-mm\.panel> <\/x-mm\.page>)$/),region(before,/(<\/div> ?){6}$/),`${name}: the printed document changed`);
  const part=(s,start)=>s.slice(s.indexOf(start));
  assert.equal(part(after,"@section('js')"),part(before,"@section('js')"),`${name}: print script changed`);
  assert.equal(after.slice(after.indexOf("@section('css')"),after.indexOf("@section('content')")),before.slice(before.indexOf("@section('css')"),before.indexOf("@section('content')")),`${name}: invoice styles changed`);
  assert(after.includes('<x-mm.page class="mm-invoice-page"')&&after.includes('onclick="printPage(\'print_body\'); return false;"')&&after.includes("hasPermission('service.view', $slugs)")&&!after.includes('widget-box'),`${name}: shared frame, permission-gated print action expected`);
 }
 const v3Path='module/Hotel/views/booking/checkout-invoice-v3.blade.php', v3=fs.readFileSync(v3Path,'utf8'), v3Base=base(v3Path);
 const stripBar=s=>s.replace(/\n        \/\* Screen-only action bar[\s\S]*?(?=    <\/style>\n\n<\/head>)/,'').replace(/\n    <nav class="inv-screen-bar"[\s\S]*?<\/nav>\n/,'');
 assert.equal(stripBar(v3),v3Base,'invoice v3: changed beyond the screen-only action bar');
 assert(/@media print \{\s*\.inv-screen-bar \{\s*display: none !important;/.test(v3)&&v3.includes('window.print();'),'invoice v3: action bar must be hidden in print and auto-print kept');
 for(const path of ['module/Hotel/views/booking/checkout-invoice-v2.blade.php','module/Hotel/views/booking/checkout-invoice-v4.blade.php','module/Hotel/views/booking/get_invoice.blade.php','module/Hotel/views/booking/_css/invoice-sheet.blade.php']) assert.equal(fs.readFileSync(path,'utf8'),base(path),`${path} must be unchanged`);
 console.log('PASS: invoices keep their printed documents, expressions and print scripts; screen frame and action bar only');
}

{
 // Booking lifecycle: payment collection. Expressions, hidden fields, form controls, the invoices table and the script stay as they were.
 const path='module/Hotel/views/payment-collection/index.blade.php', after=fs.readFileSync(path,'utf8');
 const before=execFileSync('git',['show',`895b48b3:${path}`],{encoding:'utf8'});
 const ex=s=>(s.replace(/\{\{--[\s\S]*?--\}\}/g,'').match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const ctl=s=>(s.replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 // Allowed: the duplicate Search/Reset pair became one pair, and the six nameless read-only guest display inputs became text.
 assert.deepEqual(only(ex(before),ex(after)),['-1 {{ request()->url() }}'],'payment collection: Blade expressions changed');
 assert.deepEqual(only(ctl(before),ctl(after)),['+2 <button class="mm-button" type="submit">','-1 <button class="btn-outline-success btn-sm" type="submit">','-2 <button class="btn btn-sm btn-success" type="submit">','-6 <input type="text" value="{{}}" readonly>'].sort(),'payment collection: form controls changed beyond the allowed set');
 const forms=s=>(s.match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' '));
 assert.deepEqual(forms(after),forms(before),'payment collection: form tags changed');
 const php=s=>s.match(/@php[\s\S]*?@endphp/g)||[];
 assert.deepEqual(php(after),php(before),'payment collection: @php blocks changed');
 const js=s=>s.slice(s.indexOf("@section('js')"));
 assert.equal(js(after),js(before),'payment collection: calculation script must be byte-identical');
 const table=s=>s.slice(s.indexOf('<table class="table table-bordered table-striped table-hover guest-detail-table">'),s.indexOf('</table>',s.indexOf('guest-detail-table"'))).replace(/\s+/g,' ');
 assert.equal(table(after),table(before),'payment collection: invoices table changed');
 for(const needle of ['payable-amount','current-due','id="get-due"','id="check-full-payment"','class="discount only-number','name="total_paid_amount"','name="payment_type"','name="is_from_due_collection"']) assert(after.includes(needle),`payment collection: missing ${needle}`);
 assert(after.includes('<x-mm.page class="mm-payment-collection"')&&!after.includes('widget-header')&&!after.includes('widget-box'),'payment collection: should use the shared page frame');
 console.log('PASS: payment collection expressions, form controls, invoices table and calculation script preserved');
}

{
 // Booking lifecycle: night audit. The closing form keeps its fields, table, @php blocks and delay script; the list keeps its filter and shared export table; the report only gains a screen-only action bar.
 const read=(path,rev)=>rev?execFileSync('git',['show',`${rev}:${path}`],{encoding:'utf8'}):fs.readFileSync(path,'utf8');
 const nc=s=>s.replace(/\{\{--[\s\S]*?--\}\}/g,'');
 const ex=s=>(nc(s).match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const ctl=s=>(nc(s).replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 const forms=s=>(nc(s).match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const js=s=>s.slice(s.indexOf("@section('js')"));
 const base='45091d5a';
 const createPath='module/Hotel/views/night-audits/create-v2.blade.php', cAfter=read(createPath), cBefore=read(createPath,base);
 assert.deepEqual(only(ex(cBefore),ex(cAfter)),[],'night audit form: Blade expressions changed');
 assert.deepEqual(only(ctl(cBefore),ctl(cAfter)),['+1 <button class="mm-button mm-button-secondary">','+1 <button type="button" class="mm-button save-btn">','+1 <button type="submit" class="mm-button">','-1 <button class="btn-outline-danger btn-sm">','-1 <button type="button" class="btn-sm btn-outline-success save-btn">','-1 <button type="submit" class="btn btn-sm btn-primary">'].sort(),'night audit form: controls changed beyond button classes');
 assert.deepEqual(forms(cAfter),forms(cBefore),'night audit form: form tags changed');
 const php=s=>nc(s).match(/@php[\s\S]*?@endphp/g)||[];
 assert.deepEqual(php(cAfter),php(cBefore),'night audit form: @php blocks changed');
 assert.equal(js(cAfter),js(cBefore),'night audit form: closing script must be byte-identical');
 const tbl=s=>(nc(s).match(/<table[\s\S]*?<\/table>/g)||[]).filter(t=>t.includes('Invoice No')).map(t=>t.replace(/\s+/g,' ')).join('|');
 assert.equal(tbl(cAfter),tbl(cBefore),'night audit form: transaction tables changed');
 assert(cAfter.includes('<x-mm.page class="mm-night-audit"')&&cAfter.includes('id="formSubmit"')&&!cAfter.includes('widget-box')&&!cAfter.includes('<style>'),'night audit form: shared frame expected');
 const idxPath='module/Hotel/views/night-audits/index.blade.php', iAfter=read(idxPath), iBefore=read(idxPath,base);
 assert.deepEqual(only(ex(iBefore),ex(iAfter)),[],'night audit list: Blade expressions changed');
 assert.deepEqual(only(ctl(iBefore),ctl(iAfter)),['+1 <button type="submit" class="mm-button">','-1 <button type="submit" class="btn btn-sm btn-primary">'],'night audit list: controls changed');
 assert.equal(js(iAfter),js(iBefore),'night audit list: script changed');
 assert(iAfter.includes("@include('night-audits.export.excel')")&&iAfter.includes('<x-export-button pdf="1" excel="1" />')&&iAfter.includes('<x-paginate :data="$nightaudits" />')&&iAfter.includes('name="from_date"')&&iAfter.includes('name="to_date"'),'night audit list: export table, export buttons, paginator and filters expected');
 for(const path of ['module/Hotel/views/night-audits/export/excel.blade.php','module/Hotel/views/night-audits/export/pdf.blade.php','module/Hotel/Controllers/NightAuditSummaryController.php']) assert.equal(read(path),read(path,base),`${path} must be unchanged`);
 const invPath='module/Hotel/views/night-audits/invoice.blade.php', inv=read(invPath), invBase=read(invPath,base);
 const stripBar=s=>s.replace(/\n        \/\* Screen-only action bar[\s\S]*?(?=    <\/style>\n\n<\/head>)/,'').replace(/\n    <nav class="inv-screen-bar"[\s\S]*?<\/nav>\n/,'');
 assert.equal(stripBar(inv),invBase,'night audit report: changed beyond the screen-only action bar');
 assert(/@media print \{\s*\.inv-screen-bar \{\s*display: none !important;/.test(inv)&&inv.includes('window.print();'),'night audit report: action bar must be hidden in print and auto-print kept');
 console.log('PASS: night audit form, list and report keep fields, expressions, scripts and printed output; frame and action bar only');
}

{
 // Hotel setup screens: amenities, account types, VAT, currency conversions, registration terms. Fields, expressions, tables and scripts stay as they were.
 const base='22774024';
 const nc=s=>s.replace(/\{\{--[\s\S]*?--\}\}/g,'');
 const ex=s=>(nc(s).match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const ctl=s=>(nc(s).replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
 const forms=s=>(nc(s).match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 const tables=s=>(nc(s).match(/<table[\s\S]*?<\/table>/g)||[]).filter(t=>t.includes('<thead>')).map(t=>t.replace(/\s+/g,' ').replace(/ class="(btn|mm-button)[^"]*"/g,'')).join('|');
 const tail=s=>{const i=s.indexOf("@section('js')");return i<0?'':s.slice(i);};
 const scripts=s=>(nc(s).match(/<script[\s\S]*?<\/script>/g)||[]).map(t=>t.replace(/\s+/g,' ')).join('|');
 const backLink="+1 {{ route('aminities.index') }}";
 const expected={
  'aminities/create':{ex:[backLink],forms:['+1 <form class="form-horizontal" id="companyForm" action="{{ route(\'aminities.store\') }}" method="post" enctype="multipart/form-data">','-1 <form class="form-horizontal" id="companyForm" action="{{ route(\'aminities.store\') }}" method="get" enctype="multipart/form-data">'].sort()},
  'aminities/edit':{ex:[backLink]},
  'account_type/edit':{ex:["+1 {{ route('account-type.index') }}"]},
  'currency-conversions/index':{ctl:['+1 <button aria-label="Search">','-1 <button>'],forms:['+1 <form action="" class="mm-setup-filter">','-1 <form action="">']},
  'guest-registration-terms/include/filter':{forms:['+1 <form action="" class="mm-setup-filter">','-1 <form action="">']},
 };
 for(const f of ['aminities/index','aminities/create','aminities/edit','account_type/index','account_type/edit','vat/index','currency-conversions/index','currency-conversions/create','currency-conversions/edit','guest-registration-terms/index','guest-registration-terms/include/filter','guest-registration-terms/edit']){
  const p=`module/Hotel/views/${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'}),e=expected[f]||{};
  assert.deepEqual(only(ex(before),ex(after)),(e.ex||[]).sort(),`${f}: Blade expressions changed`);
  assert.deepEqual(only(ctl(before),ctl(after)),(e.ctl||[]).sort(),`${f}: form controls changed`);
  assert.deepEqual(only(forms(before),forms(after)),(e.forms||[]).sort(),`${f}: form tags changed`);
  assert.equal(tables(after),tables(before),`${f}: tables changed`);
  assert.equal(tail(after),tail(before),`${f}: page script changed`);
  assert.equal(scripts(after),scripts(before),`${f}: scripts changed`);
  assert(after.includes('<x-mm.page')||after.includes('<x-mm.panel'),`${f}: shared layout expected`);
  assert(!after.includes('widget-box')&&!after.includes('page-header">'),`${f}: legacy widget frame should be gone`);
 }
 console.log('PASS: hotel setup screens keep fields, expressions, tables and scripts; amenities create now posts to its store route');
}

{
 // Hotel report screens: fields, Blade expressions, directives, includes, inline tables and scripts stay as they were; only the legacy widget frame and filter layout changed.
 const base='22774024';
 const nc=s=>s.replace(/\{\{--[\s\S]*?--\}\}/g,'');
 const ex=s=>(nc(s).match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const ctl=s=>(nc(s).replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
 const forms=s=>(nc(s).match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
 const directives=s=>(nc(s).match(/@(?:if|elseif|else|endif|foreach|endforeach|forelse|empty|endforelse|php|endphp|include|isset|endisset)\b(?:\s*\([^\n]*\))?/g)||[]).map(x=>x.replace(/\s+/g,' ').trim()).filter(x=>!/^@(?:empty|else|endif|endforeach|endforelse|endphp|endisset|php)$/.test(x)||true);
 const comps=s=>(nc(s).match(/<x-(?:paginate|export-button|alert-message)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 const tables=s=>(nc(s).match(/<table[\s\S]*?<\/table>/g)||[]).filter(t=>t.includes('<thead>')).map(t=>t.replace(/\s+/g,' ').replace(/ class="(btn|mm-button)[^"]*"/g,'')).join('|');
 const tail=s=>{const i=s.indexOf("@section('js')");return i<0?'':s.slice(i);};
 const scripts=s=>(nc(s).match(/<script[\s\S]*?<\/script>/g)||[]).map(t=>t.replace(/\s+/g,' ')).join('|');
 const expected={
  'today-in-house/index':{forms:['-1 <form action="" method="GET">']},
  'all-reports/index':{dir:['-1 @endif',"-1 @if (hasPermission('pharmacy.view', $slugs))"]},
  'cash-flow/index':{dir:['-1 @endif',"-1 @if (hasPermission('pharmacy.view', $slugs))"]},
 };
 const views=['all-reports','cash-flow','expected-arrival','expected-departure','in-house-guest','room-logs','services','today-activities','today-check-in','today-check-out','today-in-house','vat-report-day','vat-report-monthly'].map(n=>`${n}/index`).concat('night-closing/indexV2');
 for(const f of views){
  const p=`module/Hotel/views/hotel/reports/${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'}),e=expected[f]||{};
  const exB=ex(before),exA=ex(after);
  assert.deepEqual(only(exB,exA),[],`${f}: Blade expressions changed`);
  assert.deepEqual(only(ctl(before),ctl(after)),[],`${f}: form controls changed`);
  assert.deepEqual(only(forms(before),forms(after)),(e.forms||[]).sort(),`${f}: form tags changed`);
  assert.deepEqual(only(directives(before),directives(after)),(e.dir||[]).sort(),`${f}: Blade directives changed`);
  assert.deepEqual(only(comps(before),comps(after)),[],`${f}: alert, paginate or export components changed`);
  assert.equal(tables(after),tables(before),`${f}: tables changed`);
  assert.equal(tail(after),tail(before),`${f}: page script changed`);
  assert.equal(scripts(after),scripts(before),`${f}: scripts changed`);
  assert(after.includes('<x-mm.page')&&after.includes('class="mm-report"'),`${f}: shared layout expected`);
  assert(!after.includes('widget-box')&&!after.includes('widget-main')&&!after.includes('<style>'),`${f}: legacy widget frame and inline styles should be gone`);
 }
 // the export partials are shared with Excel/PDF export and must not change
 const changed=execFileSync('git',['diff','--name-only',base,'--','module/Hotel/views/hotel/reports'],{encoding:'utf8'}).split('\n').filter(x=>/\/export\//.test(x));
 assert.deepEqual(changed,[],'report export partials must stay unchanged');
 console.log('PASS: hotel report screens keep fields, expressions, directives, components, tables and scripts; export partials untouched');
}

{
 // Remaining Hotel screens (guest SMS, night audit detail, monthly calendar x2, booking migration): fields, Blade expressions and scripts stay as they were; only the legacy widget frame changed.
 const base='22774024';
 const nc=s=>s.replace(/\{\{--[\s\S]*?--\}\}/g,'');
 const ex=s=>(nc(s).match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const ctl=s=>(nc(s).replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
 const forms=s=>(nc(s).match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 const tables=s=>(nc(s).match(/<table[\s\S]*?<\/table>/g)||[]).filter(t=>t.includes('<thead>')).map(t=>t.replace(/\s+/g,' ').replace(/ class="(btn|mm-button)[^"]*"/g,'').replace(' style="border: none"','')).join('|');
 const tail=s=>{const i=s.search(/@section\('(?:js|script)'\)/);return i<0?'':s.slice(i);};
 const scripts=s=>(nc(s).match(/<script[\s\S]*?<\/script>/g)||[]).map(t=>t.replace(/\s+/g,' ')).join('|');
 const expected={
  'hotel/reports/monthly/index':{ex:['-1 {{ __(5) }}']},
  'hotel/reports/monthly/booking-ui':{ex:['-1 {{ __(5) }}']},
 };
 for(const f of ['guests/sms/index','night-audits/show','hotel/reports/monthly/index','hotel/reports/monthly/booking-ui','booking/adjust/create']){
  const p=`module/Hotel/views/${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'}),e=expected[f]||{};
  assert.deepEqual(only(ex(before),ex(after)),(e.ex||[]).sort(),`${f}: Blade expressions changed`);
  assert.deepEqual(only(ctl(before),ctl(after)),[],`${f}: form controls changed`);
  assert.deepEqual(only(forms(before),forms(after)),[],`${f}: form tags changed`);
  assert.equal(tables(after),tables(before),`${f}: tables changed`);
  assert.equal(tail(after),tail(before),`${f}: page script changed`);
  assert.equal(scripts(after),scripts(before),`${f}: scripts changed`);
  assert(after.includes('<x-mm.page'),`${f}: shared layout expected`);
  assert(!after.includes('widget-box ')&&!after.includes('widget-main')&&!after.includes('widget-header'),`${f}: legacy widget frame should be gone`);
 }
 // the two printable documents only gain the screen-only bar; everything that prints is unchanged
 for(const f of ['guests/invoice','hotel/reports/night-closing/invoice']){
  const p=`module/Hotel/views/${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'});
  const stripped=after.replace(/\n        \/\* Screen-only action bar[\s\S]*?(?=    <\/style>)/,'\n    ').replace(/\n    <nav class="inv-screen-bar"[\s\S]*?<\/nav>\n/,'');
  const norm=s=>s.replace(/\s+/g,' ');
  assert.equal(norm(stripped),norm(before),`${f}: the printed document changed`);
  assert(after.includes('inv-screen-bar')&&after.includes('@media print')&&/\.inv-screen-bar\s*\{\s*display: none !important/.test(after),`${f}: screen bar must be hidden when printing`);
 }
 console.log('PASS: remaining hotel screens keep fields, expressions, tables and scripts; printable documents only gain a screen-only bar');
}

{
 // Hotel Service screens (service list, sales list, new sale, sale invoice, night audit list): fields, Blade expressions, tables and scripts stay as they were; only the legacy widget frame changed.
 const base='22774024';
 const nc=s=>s.replace(/\{\{--[\s\S]*?--\}\}/g,'');
 const ex=s=>(nc(s).match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const added=/ (?:id="(?:hs-invoice-no|hs-customer-id|sale_date)"|aria-label="[^"]*"|style="[^"]*"|class="[^"]*")/g;
 const ctl=s=>(nc(s).replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(added,''));
 const forms=s=>(nc(s).match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
 const directives=s=>(nc(s).match(/@(?:if|elseif|else|endif|foreach|endforeach|forelse|empty|endforelse|php|endphp|include|isset|endisset|can|endcan)\b/g)||[]);
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 const tables=s=>(nc(s).match(/<table[\s\S]*?<\/table>/g)||[]).filter(t=>t.includes('<thead>')).map(t=>t.replace(/\s+/g,' ').replace(/ class="(btn|mm-button)[^"]*"/g,'').replace(/ aria-label="[^"]*"/g,'').replace(' style="border: none"','')).join('|');
 const tail=s=>{const i=s.search(/@section\('(?:js|script)'\)/);return i<0?'':s.slice(i);};
 const scripts=s=>(nc(s).match(/<script[\s\S]*?<\/script>/g)||[]).map(t=>t.replace(/\s+/g,' ')).join('|');
 const dir='module/HotelService/views/';
 for(const f of ['services/category/index','services/sales/index','services/sales/create','services/sales/show','hotel-service-night-audits/index']){
  const p=`${dir}${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'});
  assert.deepEqual(only(ex(before),ex(after)),[],`${f}: Blade expressions changed`);
  assert.deepEqual(only(ctl(before),ctl(after)),[],`${f}: form controls changed`);
  assert.deepEqual(only(forms(before),forms(after)),[],`${f}: form tags changed`);
  assert.deepEqual(only(directives(before),directives(after)),[],`${f}: Blade directives changed`);
  assert.equal(tables(after),tables(before),`${f}: tables changed`);
  assert.equal(tail(after),tail(before),`${f}: page script changed`);
  assert.equal(scripts(after),scripts(before),`${f}: scripts changed`);
  assert(after.includes('<x-mm.page'),`${f}: shared layout expected`);
  assert(!after.includes('widget-box')&&!after.includes('widget-main')&&!after.includes('widget-header'),`${f}: legacy widget frame should be gone`);
 }
 // the sale form keeps the hooks its script and the form repeater rely on
 const create=fs.readFileSync(`${dir}services/sales/create.blade.php`,'utf8');
 for(const hook of ['id="invForm"','id="table_auto"','<tbody class="container">','id="subTotal"','id="payable_amount"','id="amountPaid"','id="amountDue"','id="discount"','id="guest_name"','id="room_number"','id="booking_number"','r-btnAdd','onclick="submitForm()"','onclick="addItem()"']) assert(create.includes(hook),`sale form lost ${hook}`);
 // everything shared with a modal include, the export partial or the dead screens stays byte-for-byte
 const untouched=execFileSync('git',['diff','--name-only',base,'--',dir+'services/category/add-modal.blade.php',dir+'services/category/edit-modal.blade.php',dir+'services/sales/due-payment-modal.blade.php',dir+'hotel-service-night-audits/details.blade.php',dir+'hotel-service-night-audits/export',dir+'services/sales/edit.blade.php',dir+'services/due-receive'],{encoding:'utf8'}).trim();
 assert.equal(untouched,'','Hotel Service modals, export partials and unreachable screens must stay unchanged');
 // the printable night audit only gains the screen-only bar
 {
  const f='hotel-service-night-audits/invoice',p=`${dir}${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'});
  const stripped=after.replace(/\n\n        \/\* Screen-only action bar[\s\S]*?(?=    <\/style>)/,'\n').replace(/\n    <nav class="inv-screen-bar"[\s\S]*?<\/nav>\n/,'');
  const norm=s=>s.replace(/\s+/g,' ');
  assert.equal(norm(stripped),norm(before),`${f}: the printed document changed`);
  assert(after.includes('inv-screen-bar')&&after.includes('@media print')&&/\.inv-screen-bar\s*\{\s*display: none !important/.test(after),`${f}: screen bar must be hidden when printing`);
 }
 console.log('PASS: hotel service screens keep fields, expressions, directives, tables and scripts; modals and export partials untouched; printable audit only gains a screen-only bar');
}

{
 // Permission screens (module, sub module, parent permission, permissions, users, password forms, access matrices): fields, Blade expressions, tables and scripts stay as they were; only the legacy widget frame changed.
 const base='b5e7ea03';
 const nc=s=>s.replace(/\{\{--[\s\S]*?--\}\}/g,'');
 const ex=s=>(nc(s).match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const added=/ (?:aria-label="[^"]*"|style="[^"]*"|class="[^"]*")/g;
 const ctl=s=>(nc(s).replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(added,''));
 const forms=s=>(nc(s).match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
 const directives=s=>(nc(s).match(/@(?:if|elseif|else|endif|foreach|endforeach|forelse|empty|endforelse|php|endphp|include|isset|endisset|can|endcan|error|enderror|csrf|method)\b/g)||[]);
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 const tables=s=>(nc(s).match(/<table[\s\S]*?<\/table>/g)||[]).filter(t=>t.includes('<thead>')).map(t=>t.replace(/\s+/g,' ').replace(/ class="(btn|mm-button)[^"]*"/g,'').replace(/ aria-label="[^"]*"/g,'').replace(/<th([^>]*?) style="[^"]*"/g,'<th$1')).join('|');
 const scripts=s=>(nc(s).match(/<script[\s\S]*?<\/script>/g)||[]).map(t=>t.replace(/\s+/g,' ')).join('|');
 const dir='module/Permission/views/';
 const files=['module','submodule','parent_permission','permission/index','permission/create','permission/edit','users/index','users/create','users/change_password','users/change_password_by_admin','access/create','access/edit','access/employee-permission'];
 for(const f of files){
  const p=`${dir}${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'});
  assert.deepEqual(only(ex(before),ex(after)),[],`${f}: Blade expressions changed`);
  assert.deepEqual(only(ctl(before),ctl(after)),[],`${f}: form controls changed`);
  assert.deepEqual(only(forms(before),forms(after)),[],`${f}: form tags changed`);
  assert.deepEqual(only(directives(before),directives(after)),[],`${f}: Blade directives changed`);
  assert.equal(tables(after),tables(before),`${f}: tables changed`);
  assert.equal(scripts(after),scripts(before),`${f}: scripts changed`);
  assert(after.includes('<x-mm.page'),`${f}: shared layout expected`);
  assert(!after.includes('widget-box')&&!after.includes('widget-main')&&!after.includes('widget-header'),`${f}: legacy widget frame should be gone`);
 }
 // the access matrices keep the hooks their scripts rely on
 for(const f of ['access/create','access/edit','access/employee-permission']){
  const src=fs.readFileSync(`${dir}${f}.blade.php`,'utf8');
  for(const hook of ['module-checkbox-control','parentCheckBox','childCheckBox','id="csrf"']) assert(src.includes(hook),`${f}: lost ${hook}`);
 }
 for(const [f,hooks] of [['access/create',['load-employee','name="permissions[]"']],['access/edit',['name="permissions[]"']]]){
  const src=fs.readFileSync(`${dir}${f}.blade.php`,'utf8');
  for(const hook of hooks) assert(src.includes(hook),`${f}: lost ${hook}`);
 }
 // the dead password controller view and shared partials stay untouched
 const untouched=execFileSync('git',['diff','--name-only',base,'--','module/Permission/Controllers','module/Permission/routes','app/Http/Controllers/UserController.php'],{encoding:'utf8'}).trim();
 assert.equal(untouched,'','Permission controllers and routes must stay unchanged');
 console.log('PASS: permission screens keep fields, expressions, directives, tables and scripts; controllers and routes untouched');
}

{
 // Restaurant screens (tables, kitchen, night audits, payment collection, reports): fields, Blade expressions, directives and scripts stay as they were; only the legacy widget frame changed.
 const base='5dec6034';
 const nc=s=>s.replace(/\{\{--[\s\S]*?--\}\}/g,'');
 const ex=s=>(nc(s).match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const added=/ (?:aria-label="[^"]*"|style="[^"]*"|class="[^"]*")/g;
 const ctl=s=>(nc(s).replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(added,''));
 const forms=s=>(nc(s).match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
 const directives=s=>(nc(s).match(/@(?:if|elseif|else|endif|foreach|endforeach|forelse|empty|endforelse|include|isset|endisset|can|endcan|error|enderror|csrf|method)\b/g)||[]);
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 const scripts=s=>(nc(s).match(/<script[\s\S]*?<\/script>/g)||[]).map(t=>t.replace(/\s+/g,' ')).join('|');
 const dir='module/Restaurant/views/';
 const files=['rst/tables/index','kitchen/index','kitchen/show','kitchen/create','restaurant-night-audits/index','restaurant-night-audits/create-v2','rst-payment-collection/index','sales/index','sales/show','sales/create','sales/return/index','sales/return/show','sales/return/create','rst/reports/cash-flow/index','rst/reports/sales/index','reports/today-activities/index','reports/inventory/index','reports/inventory-ledger/index'];
 // Known, reviewed differences: the payment collection guest details became plain text (6 readonly inputs turned into a description list).
 // The sale and return forms give the date input an id so its label can point at it.
 const dateId=(sid,end)=>['+1 <input type="text" name="date" id="'+sid+'" value="{{}}" autocomplete="off"'+end+'>','-1 <input type="text" name="date" value="{{}}" autocomplete="off"'+end+'>'];
 const droppedControls={'rst-payment-collection/index':['-6 <input type="text" value="{{}}" readonly>'],'sales/create':dateId('sale_date',''),'sales/return/create':dateId('return_date',' /')};
 for(const f of files){
  const p=`${dir}${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'});
  assert.deepEqual(only(ex(before),ex(after)),[],`${f}: Blade expressions changed`);
  assert.deepEqual(only(ctl(before),ctl(after)),droppedControls[f]||[],`${f}: form controls changed`);
  assert.deepEqual(only(forms(before),forms(after)),[],`${f}: form tags changed`);
  assert.deepEqual(only(directives(before),directives(after)),[],`${f}: Blade directives changed`);
  assert.equal(scripts(after),scripts(before),`${f}: scripts changed`);
  assert(after.includes('<x-mm.page'),`${f}: shared layout expected`);
  assert(!after.includes('widget-box')&&!after.includes('widget-main')&&!after.includes('widget-header'),`${f}: legacy widget frame should be gone`);
 }
 // the shared partials, controllers and routes stay untouched
 const untouched=execFileSync('git',['diff','--name-only',base,'--','module/Restaurant/Controllers','module/Restaurant/routes','module/Bar/views','module/Restaurant/views/reports/inventory/export','module/Restaurant/views/rst/reports/sales/export','module/Restaurant/views/restaurant-night-audits/export','module/Restaurant/views/restaurant-night-audits/details.blade.php'],{encoding:'utf8'}).trim();
 assert.equal(untouched,'','Restaurant controllers, routes, shared Bar views and export partials must stay unchanged');
 // routes used by the rewritten screens
 const night=fs.readFileSync(`${dir}restaurant-night-audits/create-v2.blade.php`,'utf8');
 assert(night.includes("route('rst.night-audits.store')"),'night audit generate must post to rst.night-audits.store');
 assert(fs.readFileSync(`${dir}rst-payment-collection/index.blade.php`,'utf8').includes("route('rst.sales.store-payment-collection')"),'payment collection must post to rst.sales.store-payment-collection');
 console.log('PASS: restaurant screens (R1 and sales/returns) keep fields, expressions, directives and scripts; controllers, routes, Bar views and export partials untouched');
}

{
 // Restaurant purchases (purchase-v2: list, create frame, requisition document, approve form): fields, expressions, directives and scripts stay as they were.
 const base='059fb625';
 const nc=s=>s.replace(/\{\{--[\s\S]*?--\}\}/g,'');
 const ex=s=>(nc(s).match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const added=/ (?:aria-label="[^"]*"|style="[^"]*"|class="[^"]*")/g;
 const ctl=s=>(nc(s).replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(added,''));
 const forms=s=>(nc(s).match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
 const directives=s=>(nc(s).match(/@(?:if|elseif|else|endif|foreach|endforeach|forelse|empty|endforelse|include|isset|endisset|can|endcan|error|enderror|csrf|method)\b/g)||[]);
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 const scripts=s=>(nc(s).match(/<script[\s\S]*?<\/script>/g)||[]).map(t=>t.replace(/\s+/g,' ')).join('|');
 const dir='module/Restaurant/views/';
 // Known, reviewed difference: the requisition's print icon image became a Print button with a font icon.
 // Reviewed R3 follow-up fixes: the list's Refresh link and the approve form's List button point at rst.purchases.index; Received Qty shows the approved quantity (was a copy of Required Qty).
 const droppedExpr={'purchase-v2/show':["-1 {{ asset('assets/images/export-icons/printer-icon.png') }}"],
  'purchase-v2/index':["+1 {{ $purchase->is_approved ? $purchase->purchase_details->sum('quantity') : 0 }}","+1 {{ route('rst.purchases.index') }}","-1 {{ route('purchases.index') }}","-1 {{ $purchase->purchase_details->sum('quantity') }}"].sort(),
  'purchase-v2/approve':["+1 {{ route('rst.purchases.index') }}","-1 {{ route('rst.purchase.index') }}"]};
 for(const f of ['purchase-v2/index','purchase-v2/show','purchase-v2/approve','purchase-v2/create']){
  const p=`${dir}${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'});
  assert.deepEqual(only(ex(before),ex(after)),droppedExpr[f]||[],`${f}: Blade expressions changed`);
  assert.deepEqual(only(ctl(before),ctl(after)),[],`${f}: form controls changed`);
  assert.deepEqual(only(forms(before),forms(after)),[],`${f}: form tags changed`);
  assert.deepEqual(only(directives(before),directives(after)),[],`${f}: Blade directives changed`);
  assert.equal(scripts(after),scripts(before),`${f}: scripts changed`);
  assert(after.includes('<x-mm.page'),`${f}: shared layout expected`);
  assert(!after.includes('widget-box')&&!after.includes('widget-main')&&!after.includes('widget-header')&&!after.includes('page-header'),`${f}: legacy frame should be gone`);
 }
 // the create screen includes the Restaurant partials (restaurant products, bar=0), not the Bar ones
 const create=fs.readFileSync(`${dir}purchase-v2/create.blade.php`,'utf8');
 for(const inc of ["purchase-v2.inc.common","purchase-v2.create.left-side","purchase-v2.create.right-side","purchase-v2/inc/script"]) assert(create.includes(`@include('${inc}')`),`purchase create must include ${inc}`);
 assert(!create.includes('bar.purchase-v2'),'purchase create must not include the Bar partials');
 const untouched=execFileSync('git',['diff','--name-only',base,'--','module/Restaurant/Controllers','module/Restaurant/routes','module/Bar/views','module/Restaurant/views/purchase-v2/inc','module/Restaurant/views/purchase-v2/create'],{encoding:'utf8'}).trim();
 assert.equal(untouched,'','Restaurant controllers, routes, Bar views and the unused purchase-v2 partials must stay unchanged');
 console.log('PASS: restaurant purchases (purchase-v2) keep fields, expressions, directives and scripts; controllers, routes and shared Bar partials untouched');
}

{
 // Restaurant inventory (group R4: setup lists, catalog, production, purchase, stock adjustment): fields, expressions, directives and scripts stay as they were.
 const base='059fb625';
 const nc=s=>s.replace(/\{\{--[\s\S]*?--\}\}/g,'');
 const ex=s=>(nc(s).match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
 const added=/ (?:aria-label="[^"]*"|style="[^"]*"|class="[^"]*")/g;
 const ctl=s=>(nc(s).replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(added,''));
 const forms=s=>(nc(s).match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
 const directives=s=>(nc(s).match(/@(?:if|elseif|else|endif|foreach|endforeach|forelse|empty|endforelse|include|isset|endisset|can|endcan|error|enderror|csrf|method)\b/g)||[]);
 const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
 const scripts=s=>(nc(s).match(/<script[\s\S]*?<\/script>/g)||[]).map(t=>t.replace(/\s+/g,' ')).join('|');
 const dir='module/Restaurant/views/inventory/';
 const pages=['categories/index','units/index','manufacturers/index','supplier/index','product/index','mat_product/index','inventory-report','product/uploads/index','product/uploads/edit','product/create','product/edit','mat_product/create','mat_product/edit','production/items/index','production/items/create','production/items/edit','production/item-units/index','production/item-units/create','production/item-units/edit','production/goods_requisitions/index','production/goods_requisitions/create','production/purchases/index','production/purchases/show','production/purchases/approve','production/purchases/edit','production/purchase-v2/create','adjustment-v2/index','adjustment-v2/create','adjustment-v2/view','adjustment-v2/edit'];
 // Known, reviewed differences: the stock adjustment document pages lost their ace breadcrumb (the shared page header replaces it); the purchase document's print icon image became a Print button.
 const dropped={
  'adjustment-v2/view':["-1 {{ $stockAdjustment->invoice_no }}","-1 {{ route('home') }}"],
  'adjustment-v2/edit':["-1 {{ $stockAdjustment->invoice_no }}","-1 {{ route('home') }}"],
  'production/purchases/show':["-1 {{ asset('assets/images/export-icons/printer-icon.png') }}"],
 };
 for(const f of pages){
  const p=`${dir}${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'});
  assert.deepEqual(only(ex(before),ex(after)),dropped[f]||[],`${f}: Blade expressions changed`);
  assert.deepEqual(only(ctl(before),ctl(after)),[],`${f}: form controls changed`);
  assert.deepEqual(only(forms(before),forms(after)),[],`${f}: form tags changed`);
  assert.deepEqual(only(directives(before),directives(after)),[],`${f}: Blade directives changed`);
  assert.equal(scripts(after),scripts(before),`${f}: scripts changed`);
  assert(after.includes('<x-mm.page'),`${f}: shared layout expected`);
  assert(!after.includes('widget-box')&&!after.includes('widget-main')&&!after.includes('widget-header')&&!after.includes('class="page-header'),`${f}: legacy frame should be gone`);
 }
 // the catalog filters were rewritten to the shared filter markup: every control stays
 for(const f of ['product/_inc/filter','mat_product/_inc/filter']){
  const p=`${dir}${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'});
  const comps=s=>(nc(s).match(/<x-widget\.[\w-]+[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ')).sort();
  assert.deepEqual(comps(after),comps(before),`${f}: filter components changed`);
  assert.deepEqual(only(ctl(before),ctl(after)),[],`${f}: filter controls changed`);
  assert.deepEqual(only(forms(before),forms(after)),[],`${f}: filter form changed`);
 }
 // controllers, routes and the shared partials/scripts stay untouched
 const untouched=execFileSync('git',['diff','--name-only',base,'--','module/Restaurant/Controllers','module/Restaurant/routes','module/Bar/views','module/Restaurant/views/inventory/product/_inc/script.blade.php','module/Restaurant/views/inventory/production/purchase-v2/inc','module/Restaurant/views/inventory/adjustment-v2/inc','module/Restaurant/views/inventory/adjustment-v2/create','module/Restaurant/views/inventory/production/purchase-v2/create'],{encoding:'utf8'}).trim();
 assert.equal(untouched,'','Restaurant controllers, routes, Bar views and the inventory script/form partials must stay unchanged');
 console.log('PASS: restaurant inventory (R4) keeps fields, expressions, directives and scripts; controllers, routes and shared partials untouched');
}

{
  // General Store G1 (items, item units, suppliers, supplier types): fields, expressions, directives and scripts stay as they were.
  const base='ce4089f9';
  const nc=s=>s.replace(/\{\{--[\s\S]*?--\}\}/g,'');
  const ex=s=>(nc(s).match(/\{\{[\s\S]*?\}\}|\{!![\s\S]*?!!\}/g)||[]).map(x=>x.replace(/\s+/g,' '));
  const added=/ (?:aria-label="[^"]*"|style="[^"]*"|class="[^"]*")/g;
  const ctl=s=>(nc(s).replace(/\{\{[\s\S]*?\}\}/g,'{{}}').match(/<(input|select|textarea|button)\b[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(added,''));
  const forms=s=>(nc(s).match(/<form[^>]*>/g)||[]).map(x=>x.replace(/\s+/g,' ').replace(/ class="[^"]*"/,''));
  const directives=s=>(nc(s).match(/@(?:if|elseif|else|endif|foreach|endforeach|forelse|empty|endforelse|include|isset|endisset|can|endcan|error|enderror|csrf|method)\b/g)||[]);
  const only=(l,m)=>{const c={};l.forEach(x=>c[x]=(c[x]||0)+1);m.forEach(x=>c[x]=(c[x]||0)-1);return Object.entries(c).filter(([,n])=>n).map(([k,n])=>`${n>0?'-':'+'}${Math.abs(n)} ${k}`).sort();};
  const scripts=s=>(nc(s).match(/<script[\s\S]*?<\/script>/g)||[]).map(t=>t.replace(/\s+/g,' ')).join('|');
  const dir='module/GeneralStore/views/';
  // Known, reviewed difference: the dead export/print icon row of the item unit list (empty links and a route that does not exist) is commented out, as in the Restaurant material units.
  const dropped={'item-units/index':["-1 {{ URL::to('gs-setup/print-item-unit') }}","-1 {{ asset('assets/images/export-icons/excel-icon.png') }}","-1 {{ asset('assets/images/export-icons/pdf-icon.png') }}","-1 {{ asset('assets/images/export-icons/word-icon.png') }}","-1 {{ asset('assets/images/export-icons/printer-icon.png') }}"].sort()};
  for(const f of ['item-units/index','item-units/create','item-units/edit','items/index','items/create','items/edit','items/upload','suppliers/index','suppliers/create','suppliers/edit','supplier-types/index']){
    const p=`${dir}${f}.blade.php`,after=fs.readFileSync(p,'utf8'),before=execFileSync('git',['show',`${base}:${p}`],{encoding:'utf8'});
    assert.deepEqual(only(ex(before),ex(after)),dropped[f]||[],`${f}: Blade expressions changed`);
    assert.deepEqual(only(ctl(before),ctl(after)),[],`${f}: form controls changed`);
    assert.deepEqual(only(forms(before),forms(after)),[],`${f}: form tags changed`);
    assert.deepEqual(only(directives(before),directives(after)),[],`${f}: Blade directives changed`);
    assert.equal(scripts(after),scripts(before),`${f}: scripts changed`);
    assert(after.includes('<x-mm.page'),`${f}: shared layout expected`);
    assert(!after.includes('widget-box')&&!after.includes('widget-main')&&!after.includes('widget-header')&&!after.includes('class="page-header'),`${f}: legacy frame should be gone`);
  }
  const untouched=execFileSync('git',['diff','--name-only',base,'--','module/GeneralStore/Controllers','module/GeneralStore/routes','module/GeneralStore/Models','module/GeneralStore/views/gs-exports','module/GeneralStore/views/items/export_item.blade.php'],{encoding:'utf8'}).trim();
  assert.equal(untouched,'','General Store controllers, routes, models and export partials must stay unchanged');
  console.log('PASS: general store G1 (items, item units, suppliers, supplier types) keeps fields, expressions, directives and scripts; controllers, routes and export partials untouched');
}
