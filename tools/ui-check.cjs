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
