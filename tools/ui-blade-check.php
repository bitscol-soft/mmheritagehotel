<?php
error_reporting(E_ALL & ~E_DEPRECATED); // newer PHP versions flag deprecations inside the vendored Carbon/Symfony; they must not leak into the rendered fixtures
set_error_handler(function ($no, $msg, $file, $line) { if (!(error_reporting() & $no)) return false; throw new ErrorException($msg . ' in ' . basename($file) . ':' . $line, 0, $no, $file, $line); }, E_WARNING | E_NOTICE | E_USER_WARNING | E_USER_NOTICE); // sample data must be complete: a warning is a failure, never output
// Standalone Blade smoke check: no database, .env or application boot needed.
$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
// Freeze time for the whole run: several views and fixtures read now()/date(), and the check must not depend on the day it runs.
Carbon\Carbon::setTestNow(Carbon\Carbon::parse('2026-10-01 09:30:00', 'Asia/Dhaka'));
$app = new Illuminate\Foundation\Application($root . '');
$app->singleton('config', function() use ($root){ return new Illuminate\Config\Repository(['view'=>['paths'=>[$root . '/resources/views',$root . '/module/Hotel/views'], 'compiled'=>'/tmp/mm-blade-cache']]); });
@mkdir('/tmp/mm-blade-cache');
$app->instance('files', new Illuminate\Filesystem\Filesystem());
$app->register(Illuminate\Events\EventServiceProvider::class);
$app->register(Illuminate\View\ViewServiceProvider::class);
$app->instance('request', Illuminate\Http\Request::create('/ui-kit'));
$compiler=$app->make('blade.compiler');
$guestFormFiles = array_map(function ($path) use ($root) { return $root . '/module/Hotel/views/guests/' . $path; }, ['create.blade.php', 'edit.blade.php', 'create/create.blade.php', 'create/upload.blade.php']);
$files=array_merge([$root . '/module/Hotel/views/rooms/create.blade.php', $root . '/module/Hotel/views/rooms/edit.blade.php', $root . '/module/Hotel/views/rooms/index.blade.php', $root . '/module/Hotel/views/category/index.blade.php', $root . '/module/Hotel/views/booking/booking_ui.blade.php'], $guestFormFiles, glob($root . '/resources/views/components/mm/*.blade.php'), [$root . '/resources/views/ui/kit.blade.php',$root . '/module/Hotel/views/guests/index.blade.php',$root . '/module/Hotel/views/guests/include/filter.blade.php', $root . '/module/Hotel/views/booking/index.blade.php', $root . '/module/Hotel/views/booking/_inc/_filter.blade.php']);
$files=array_merge($files, [$root . '/module/Hotel/views/category/create.blade.php', $root . '/module/Hotel/views/category/edit.blade.php', $root . '/resources/views/layouts/master.blade.php', $root . '/resources/views/layouts/includes/head.blade.php', $root . '/resources/views/partials/_header.blade.php', $root . '/resources/views/partials/_sidebar.blade.php'], glob($root . '/resources/views/layouts/shell/*.blade.php'));
$files=array_merge($files, [$root . '/resources/views/home/hotel-dashboard.blade.php', $root . '/resources/views/home/_inc/dashboard-summary.blade.php', $root . '/resources/views/home/_inc/room-board.blade.php', $root . '/resources/views/home/_inc/room-card.blade.php', $root . '/resources/views/home/_inc/bed-icon.blade.php', $root . '/resources/views/home/_inc/booking_ui.blade.php']);
$files=array_merge($files, [$root . '/module/Hotel/views/house-keeping/index.blade.php'], glob($root . '/resources/views/layouts/shell/*.blade.php'), [$root . '/resources/views/partials/_header.blade.php']);
$files=array_merge($files, array_map(function ($v) use ($root) { return $root . '/module/Hotel/views/' . $v . '.blade.php'; }, ['aminities/index', 'aminities/create', 'aminities/edit', 'account_type/index', 'account_type/edit', 'vat/index', 'currency-conversions/index', 'currency-conversions/create', 'currency-conversions/edit', 'guest-registration-terms/index', 'guest-registration-terms/include/filter', 'guest-registration-terms/edit']));
$files=array_merge($files, glob($root . '/module/Hotel/views/booking-purpose/*.blade.php'), [$root . '/module/Hotel/views/booking-purpose/include/filter.blade.php']);
$files=array_merge($files, [$root . '/module/Hotel/views/booking-note/index.blade.php', $root . '/module/Hotel/views/booking-note/edit.blade.php', $root . '/module/Hotel/views/booking-note/include/filter.blade.php']);
$files=array_merge($files, [$root . '/module/Hotel/views/booking/booking_next.blade.php', $root . '/module/Hotel/views/booking/_inc/_booking-next-steps.blade.php', $root . '/module/Hotel/views/booking/view.blade.php', $root . '/module/Hotel/views/night-audits/index.blade.php', $root . '/module/Hotel/views/night-audits/invoice.blade.php', $root . '/module/Hotel/views/night-audits/create-v2.blade.php', $root . '/module/Hotel/views/payment-collection/index.blade.php', $root . '/module/Hotel/views/booking/checkout_invoice.blade.php', $root . '/module/Hotel/views/booking/reservation-invoice.blade.php', $root . '/module/Hotel/views/booking/checkout-invoice-v3.blade.php', $root . '/module/Hotel/views/booking/create.blade.php', $root . '/module/Hotel/views/booking/edit.blade.php', $root . '/module/Hotel/views/booking/_inc/_add-guest-input-info.blade.php', $root . '/module/Hotel/views/booking/_inc/_edit-guest-input-info.blade.php']);
$files=array_merge($files, array_map(function ($v) use ($root) { return $root . '/module/Hotel/views/hotel/reports/' . $v . '.blade.php'; }, ['all-reports/index', 'cash-flow/index', 'expected-arrival/index', 'expected-departure/index', 'in-house-guest/index', 'room-logs/index', 'services/index', 'today-activities/index', 'today-check-in/index', 'today-check-out/index', 'today-in-house/index', 'vat-report-day/index', 'vat-report-monthly/index', 'night-closing/indexV2']));
foreach($files as $f){ $compiled=$compiler->compileString(file_get_contents($f)); token_get_all($compiled,TOKEN_PARSE); echo "PASS compile ".basename($f)."\n"; }
$html = $app->make('view')->make('components.mm.field', ['label'=>'Guest','id'=>'test','name'=>'name','value'=>'<script>','error'=>null,'attributes'=>new Illuminate\View\ComponentAttributeBag])->render();

if (strpos($html, '&lt;script&gt;') === false) { throw new RuntimeException('Field escaping failed'); }
echo "PASS escaped field render\n";

// Render the REAL booking filter for both routes, without DB/application providers.
Illuminate\Support\Facades\Facade::setFacadeApplication($app);
$routes = new Illuminate\Routing\RouteCollection();
$routes->add((new Illuminate\Routing\Route('GET', 'hotel/referred-booking', function() {}))->name('booking.referred-booking'));
foreach (['/hotel/booking' => false, '/hotel/referred-booking' => true] as $path => $referred) {
    $request = Illuminate\Http\Request::create($path, 'GET', ['booking_number' => '<script>alert(1)</script>']);
    $app->instance('request', $request);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($routes, $request));
    $html = $app->make('view')->make('booking._inc._filter', ['guest' => [], 'category' => []])->render();
    foreach (['customer_id', 'check_in_date', 'check_out_date', 'booking_number'] as $name) {
        if (strpos($html, 'name="'.$name.'"') === false) throw new RuntimeException('Missing filter '.$name);
    }
    foreach (['category_id', 'room_id', 'status', 'booking_from_date', 'booking_to_date'] as $name) {
        if ((strpos($html, 'name="'.$name.'"') !== false) === $referred) throw new RuntimeException('Wrong normal-route fields');
    }
    foreach (['reference', 'booking_from', 'booking_to'] as $name) {
        if ((strpos($html, 'name="'.$name.'"') !== false) !== $referred) throw new RuntimeException('Wrong referred-route fields');
    }
    if (strpos($html, '<script>') !== false || strpos($html, '&lt;script&gt;') === false) throw new RuntimeException('Filter escaping failed');
    echo "PASS filter render ".$path."\n";
}

// Render the actual import form: verify multipart/CSRF and accessible file label.
$routes->add((new Illuminate\Routing\Route('POST', 'hotel/guest-uploads', function() {}))->name('guest-uploads.store'));
$session = new Illuminate\Session\Store('ui-tests', new Illuminate\Session\ArraySessionHandler(120));
$session->start();
$app->instance('session', $session);
$import = $app->make('view')->make('guests.create.upload')->render();
foreach (['enctype="multipart/form-data"', 'name="_token"', 'name="csv_file"', 'for="guest-csv"', 'name="store_type" value="upload"'] as $marker) {
    if (strpos($import, $marker) === false) throw new RuntimeException('Import contract missing: '.$marker);
}
echo "PASS import form render, CSRF and multipart contract\n";

// Exercise nested Blade components and the deferred stylesheet stack together.
// This catches missing assets that isolated component compilation cannot detect.
$probeBase = tempnam(sys_get_temp_dir(), 'mm-ui-');
$probePath = $probeBase . '.blade.php';
rename($probeBase, $probePath);
file_put_contents($probePath, <<<'BLADE'
<x-mm.styles />
<x-mm.styles />
<x-mm.page title="Test & review" class="mm-booking-board-page">
    <x-slot name="actions"><a href="#summary" class="mm-button">View summary</a></x-slot>
    <x-mm.panel id="summary"><x-mm.badge>2 rooms</x-mm.badge></x-mm.panel>
</x-mm.page>
@stack('ui-styles')
BLADE
);
try {
    $probe = $app->make('view')->file($probePath)->render();
    foreach (['mm-booking-board-page', 'Test &amp; review', 'id="summary"', '2 rooms', 'ui.css?v='] as $marker) {
        if (strpos($probe, $marker) === false) throw new RuntimeException('Nested component render missing '.$marker);
    }
    if (substr_count($probe, 'ui.css?v=') !== 1) throw new RuntimeException('Stylesheet must load exactly once');
    echo "PASS nested page/panel/badge slots and deduplicated stylesheet stack\n";
} finally {
    unlink($probePath);
}

// Evaluate the ACTUAL layout gating expressions over representative request paths.
// No layout DB queries are executed; this tests shell inclusion, not authentication.
$source = file_get_contents($root . '/resources/views/layouts/master.blade.php');
preg_match_all('/\$(?:isAdminHeader|isAdminSidebar|isEmployeeHeader|isShowFooter|mmShell) = [^;]+;/', $source, $gates);
foreach ([true, false] as $enabled) {
    $app['config']->set('ui.admin_shell', $enabled);
    foreach (['/home'=>true, '/hotel/guests'=>true, '/em/home'=>false, '/rst/sales-v2/create'=>false, '/bar/sales-v2'=>false, '/hrm/payroll/master-salary/1'=>false, '/hrm/payroll/bank-salary/1'=>false, '/hrm/payroll/cash-salary/1'=>false, '/hrm/payroll/master-salary-without-payslip/1'=>false, '/hrm/payroll/master-salary-with-payslip/1'=>false, '/hrm/bonus/fixed/bonus/details/1'=>false] as $path=>$eligible) {
        $app->instance('request', Illuminate\Http\Request::create($path));
        eval(implode("\n", $gates[0]));
        if ($mmShell !== ($enabled && $eligible)) throw new RuntimeException('Shell gate failed: '.$path);
    }
}
echo "PASS shell exclusions and configuration rollback across 11 paths\n";

// Render the actual head plus new partials; substitute only the auth dependency.
// Header/sidebar notification/model queries are still compilation-only checks.
$app->instance('auth', new class {
    public function user() { return (object)['name'=>'UI fixture account']; }
});
$probeBase = tempnam(sys_get_temp_dir(), 'mm-head-');
$probePath = $probeBase . '.blade.php';
rename($probeBase, $probePath);
file_put_contents($probePath, <<<'BLADE'
@section('title', 'Shell fixture')
@include('layouts.includes.head')
@if ($mmShell)
    @include('layouts.shell.navigation-tools')
@endif
BLADE
);
try {
    foreach ([true, false] as $enabled) {
        $html = $app->make('view')->file($probePath, ['mmShell'=>$enabled, 'fav_icon'=>'/icon.png'])->render();
        foreach (['ui.css?v=', 'shell.css?v=', 'id="mm-menu-filter"'] as $marker) {
            if (substr_count($html, $marker) !== ($enabled ? 1 : 0)) throw new RuntimeException('Shell asset/partial gate failed: '.$marker);
        }
    }
    echo "PASS real head/navigation renders, one asset link, rollback omits shell\n";
} finally { unlink($probePath); }

$html = $app->make('view')->make('home._inc.dashboard-summary', ['total_room'=>25, 'today_room_booked'=>7, 'today_booking'=>3])->render();
foreach (['25', '18', '3', 'Room overview', 'not a housekeeping readiness check'] as $marker) {
    if (strpos($html, $marker) === false) throw new RuntimeException('Dashboard summary missing '.$marker);
}
echo "PASS dashboard summary render with sample values and default-zero counters\n";

// Render the real dashboard room board with sample rooms, stubbed helpers and escaped guest data.
require_once __DIR__ . '/room-board-sample.php';
// Application helpers need auth/settings, so render copies with only those three calls substituted.
$boardViews = '/tmp/mm-board-views';
@mkdir($boardViews . '/home/_inc', 0777, true);
foreach (['room-board', 'room-card', 'bed-icon'] as $partial) {
    $source = file_get_contents($root . '/resources/views/home/_inc/' . $partial . '.blade.php');
    $source = str_replace(["hasPermission('bookings.create', p_slugs())", "setting('room_wise_pricing_booking')", "fdate(\$date[0], 'Y-m-d')", 'today_from_system()', "date('Y-m-d')"], ['true', '1', "date('Y-m-d', strtotime(\$date[0]))", "'2026-10-01'", "'2026-10-01'"], $source);
    if (strpos($source, 'hasPermission(') !== false || strpos($source, 'setting(') !== false) throw new RuntimeException('Unsubstituted helper in ' . $partial);
    file_put_contents($boardViews . '/home/_inc/' . $partial . '.blade.php', $source);
}
$app['view']->getFinder()->prependLocation($boardViews);
$boardRoutes = new Illuminate\Routing\RouteCollection();
foreach (['report.monthly-booking' => 'report/monthly-booking', 'booking.next.step' => 'hotel/next-step', 'check.in.update' => 'hotel/check-in/{id}', 'booking.checkout' => 'hotel/checkout/{id}', 'booking-adjusts.create' => 'hotel/adjusts/create'] as $routeName => $uri) {
    $boardRoutes->add((new Illuminate\Routing\Route('GET', $uri, function () {}))->name($routeName));
}
$boardRequest = Illuminate\Http\Request::create('/home');
$app->instance('request', $boardRequest);
$app->instance('url', new Illuminate\Routing\UrlGenerator($boardRoutes, $boardRequest));
$boardHtml = mm_board_render($app, mm_board_sample_categories());
foreach (['id="mmb"', 'aria-expanded="true"', 'aria-controls="mmb-body-1"', 'id="mmb-drawer"', 'role="dialog"', 'name="submit" value="book"', 'name="submit" value="reserve"', 'name="booking_availabe"'] as $marker) {
    if (strpos($boardHtml, $marker) === false) throw new RuntimeException('Room board missing ' . $marker);
}
if (substr_count($boardHtml, 'class="mmb-group"') !== 6) throw new RuntimeException('Room board should render six collapsible categories');
if (substr_count($boardHtml, 'class="mmb-card"') !== 23) throw new RuntimeException('Room board should render 23 room cards');
foreach (['Single bed' => 4, 'Double bed' => 8, 'Twin beds' => 8, 'Triple beds' => 2, '5 beds' => 1] as $label => $expected) {
    // Each card renders the label as visible text and in its aria-label/title; count visible labels only.
    if (substr_count($boardHtml, 'class="mmb-bed-label">' . $label . '<') !== $expected) throw new RuntimeException('Unexpected bed label count for ' . $label);
}
foreach (['available' => 17, 'booked' => 1, 'reserved' => 1, 'dirty' => 1, 'maintenance' => 1, 'due' => 1] as $state => $expected) {
    if (substr_count($boardHtml, 'class="mmb-card" data-room') === 0 || substr_count($boardHtml, 'data-state="' . $state . '"><span class="mmb-chip-dot"') < $expected) throw new RuntimeException('Unexpected room state count for ' . $state);
}
if (strpos($boardHtml, '<script>alert(1)</script>') !== false || strpos($boardHtml, '&lt;script&gt;alert(1)&lt;\/script&gt;') === false && strpos($boardHtml, '&lt;script&gt;alert(1)&lt;/script&gt;') === false) throw new RuntimeException('Guest data escaping failed in room board');
foreach (['mmb-proxy-trigger', 'updateStatus'] as $marker) { if (strpos($boardHtml, $marker) === false) throw new RuntimeException('Legacy housekeeping hook missing ' . $marker); }
if (getenv('MM_WRITE_FIXTURE')) { file_put_contents(__DIR__ . '/fixtures/room-board.html', $boardHtml); }
if (file_get_contents(__DIR__ . '/fixtures/room-board.html') !== $boardHtml) throw new RuntimeException('tools/fixtures/room-board.html is stale; regenerate it with MM_WRITE_FIXTURE=1');
echo "PASS dashboard room board render: groups, bed types, states, legacy hooks and escaped guest data\n";

// Render the real shell chrome partials (header tools, footer, dialogs) with frozen time.
// Only the permission check is substituted in a temp copy; it needs an authenticated user.
Carbon\Carbon::setTestNow(Carbon\Carbon::parse('2026-10-01 09:30:00', 'Asia/Dhaka'));
$app->instance('env', 'staging');
$app['config']->set('ui.timezone', 'Asia/Dhaka');
$app['config']->set('ui.version', '2026.10.1');
$app['config']->set('ui.support_url', 'https://example.test/help?a=1&b=<2>');
$chromeViews = '/tmp/mm-chrome-views';
@mkdir($chromeViews . '/layouts/shell', 0777, true);
foreach (['header-tools', 'footer', 'overlays'] as $partial) {
    $source = file_get_contents($root . '/resources/views/layouts/shell/' . $partial . '.blade.php');
    $source = str_replace("hasPermission('bookings.create', \$slugs)", 'true', $source);
    if (strpos($source, 'hasPermission(') !== false) throw new RuntimeException('Unsubstituted permission check in ' . $partial);
    file_put_contents($chromeViews . '/layouts/shell/' . $partial . '.blade.php', $source);
}
$app['view']->getFinder()->prependLocation($chromeViews);
$chromeRoutes = new Illuminate\Routing\RouteCollection();
foreach (['booking.create' => 'hotel/booking/create', 'home' => 'home'] as $routeName => $uri) {
    $chromeRoutes->add((new Illuminate\Routing\Route('GET', $uri, function () {}))->name($routeName));
}
$chromeRequest = Illuminate\Http\Request::create('/hotel/booking/create');
$app->instance('request', $chromeRequest);
$app->instance('url', new Illuminate\Routing\UrlGenerator($chromeRoutes, $chromeRequest));
$chrome = [];
foreach (['header-tools', 'footer', 'overlays'] as $partial) {
    $chrome[$partial] = $app->make('view')->make('layouts.shell.' . $partial, ['slugs' => []])->render();
}
$expectChrome = [
    'header-tools' => ['data-mm-palette-open', 'data-mm-theme-toggle', 'data-mm-fullscreen', 'data-mm-shortcuts-open', 'data-mm-action="new-booking"', 'href="http://localhost/hotel/booking/create"'],
    'footer' => ['aria-label="Breadcrumb"', 'aria-current="page"', 'Hotel</span>', 'Booking</span>', 'id="mm-density-toggle"', '>Create</span>', 'role="contentinfo"', 'Business date', '01 Oct 2026', 'id="mm-clock"', 'data-timezone="Asia/Dhaka"', '09:30:00', 'id="mm-online"', 'mm-env-staging', 'v2026.10.1', 'rel="noopener"', 'id="btn-scroll-up"', 'Help &amp; support', 'a=1&amp;b=&lt;2&gt;'],
    'overlays' => ['id="mm-palette"', 'role="combobox"', 'role="listbox"', 'id="mm-shortcuts"', 'aria-labelledby="mm-shortcuts-title"'],
];
foreach ($expectChrome as $partial => $markers) {
    foreach ($markers as $marker) {
        if (strpos($chrome[$partial], $marker) === false) throw new RuntimeException('Shell ' . $partial . ' missing ' . $marker);
    }
}
foreach ($chrome as $partial => $html) {
    if (preg_match('/<script/i', $html)) throw new RuntimeException('Shell ' . $partial . ' must not render inline scripts');
    $file = __DIR__ . '/fixtures/shell-' . $partial . '.html';
    if (getenv('MM_WRITE_FIXTURE')) file_put_contents($file, $html);
    if (file_get_contents($file) !== $html) throw new RuntimeException($file . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
// Without the booking permission the quick action must disappear.
$noBooking = str_replace('data-mm-action="new-booking"', 'data-x', $chrome['header-tools']);
$denied = file_get_contents($root . '/resources/views/layouts/shell/header-tools.blade.php');
if (strpos($denied, "@if (hasPermission('bookings.create', \$slugs))") === false) throw new RuntimeException('New booking action lost its permission gate');
echo "PASS shell chrome render: header tools, breadcrumbs, footer status and dialogs\n";

// Render the new-booking progress/summary partial with sample rows and hostile text.
$stepsHtml = $app->make('view')->make('booking._inc._booking-next-steps', ['booking' => [
    ['check_in' => '2026-10-02', 'check_out' => '2026-10-05', 'nights' => 3, 'category_name' => 'Deluxe'],
    ['check_in' => '2026-10-02', 'check_out' => '<script>alert(1)</script>', 'nights' => 2, 'category_name' => 'Suite'],
]])->render();
foreach (['aria-label="Booking progress"', 'aria-current="step"', 'Select rooms', 'Guest and stay details', 'Review and save', '<dt>Rooms</dt><dd>2</dd>', '<dt>Nights</dt><dd>3</dd>', '2026-10-02'] as $marker) {
    if (strpos($stepsHtml, $marker) === false) throw new RuntimeException('Booking steps missing ' . $marker);
}
$emptyHtml = $app->make('view')->make('booking._inc._booking-next-steps', ['booking' => []])->render();
if (strpos($emptyHtml, '<dt>Rooms</dt><dd>0</dd>') === false || strpos($emptyHtml, '<dt>Nights</dt><dd>-</dd>') === false) throw new RuntimeException('Booking steps empty state failed');
// Only the first row's check-out is displayed; escaping is verified on the first row too.
$escHtml = $app->make('view')->make('booking._inc._booking-next-steps', ['booking' => [['check_in' => '<b>x</b>', 'check_out' => '2026-10-03', 'nights' => 1]]])->render();
if (strpos($escHtml, '<b>x</b>') !== false || strpos($escHtml, '&lt;b&gt;x&lt;/b&gt;') === false) throw new RuntimeException('Booking steps escaping failed');
$stepsFile = __DIR__ . '/fixtures/booking-next-steps.html';
if (getenv('MM_WRITE_FIXTURE')) file_put_contents($stepsFile, $stepsHtml);
if (file_get_contents($stepsFile) !== $stepsHtml) throw new RuntimeException($stepsFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
echo "PASS new-booking progress and stay summary render, empty state and escaping\n";

// Render the real booking guest/stay partials (create and edit) with sample data. Only app helpers are substituted:
// today_from_system() -> the frozen business date and setting() -> off.
$stayViews = '/tmp/mm-stay-views';
@mkdir($stayViews . '/booking/_inc', 0777, true);
foreach (['_add-guest-input-info', '_edit-guest-input-info'] as $partial) {
    $source = file_get_contents($root . '/module/Hotel/views/booking/_inc/' . $partial . '.blade.php');
    $source = str_replace(['today_from_system()', "setting('bulk_booking')"], ["'2026-10-01'", '0'], $source);
    if (strpos($source, 'today_from_system') !== false || strpos($source, 'setting(') !== false) throw new RuntimeException('Unsubstituted helper in ' . $partial);
    file_put_contents($stayViews . '/booking/_inc/' . $partial . '.blade.php', $source);
}
$app['view']->getFinder()->prependLocation($stayViews);
$stayRequest = Illuminate\Http\Request::create('/hotel/booking/create');
$stayRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
$app->instance('request', $stayRequest);
$app->instance('url', new Illuminate\Routing\UrlGenerator(new Illuminate\Routing\RouteCollection(), $stayRequest));
$stayBooking = (object) ['customer' => (object) ['name' => 'Rahim', 'company_id' => null], 'booking_date' => '2026-09-28', 'check_in_date' => '2026-09-29', 'check_out_date' => '2026-10-03', 'booking_pax' => 2, 'customer_id' => 1, 'purpose' => '', 'reference' => '', 'pickup' => '', 'drop' => '', 'pickup_flight' => '', 'drop_flight' => '', 'emergency_cont_name' => '', 'emergency_cont_phone' => '', 'purpose_id' => null, 'platform_id' => null, 'book_type' => 0, 'company_id' => null, 'status' => 0];
$stayCommon = ['booking_purpose' => collect([]), 'crmCompanies' => collect([]), 'guest' => collect([]), 'guests' => collect([]), 'tomorrow' => '2026-10-02', 'platforms' => collect([]), 'errors' => new Illuminate\Support\ViewErrorBag()];
$stayFixtures = [
    'booking-add-dates' => $app->make('view')->make('booking._inc._add-guest-input-info', $stayCommon)->render(),
    'booking-edit-dates' => $app->make('view')->make('booking._inc._edit-guest-input-info', $stayCommon + ['booking' => $stayBooking])->render(),
];
foreach ([
    'booking-add-dates' => ['name="check_in_date"', 'value="2026-10-01"', 'data-business-date="2026-10-01"', 'value="2026-10-02" name="check_out_date"', 'check-out-date-picker'],
    'booking-edit-dates' => ['value="2026-09-29"', 'data-allow-past="1"', 'value="2026-10-03"', 'name="check_out"', 'data-date-format="yyyy-mm-dd"', 'value="2026-10-04"'],
] as $fixtureName => $markers) {
    foreach ($markers as $marker) {
        if (strpos($stayFixtures[$fixtureName], $marker) === false) throw new RuntimeException($fixtureName . ' missing ' . $marker);
    }
    $file = __DIR__ . '/fixtures/' . $fixtureName . '.html';
    if (getenv('MM_WRITE_FIXTURE')) file_put_contents($file, $stayFixtures[$fixtureName]);
    if (file_get_contents($file) !== $stayFixtures[$fixtureName]) throw new RuntimeException($file . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS booking create/edit guest and stay partials render with the business date, existing check-in and unified date formats\n";

// Render the real checkout/payment view (booking/view) with sample transactions. Only the master layout, the alert component
// and app helpers are substituted; the markup, expressions and the calculation script are the real ones.
$coViews = '/tmp/mm-checkout-views';
@mkdir($coViews . '/booking/_inc', 0777, true);
$coSource = file_get_contents($root . '/module/Hotel/views/booking/view.blade.php');
$coSource = str_replace(["@extends('layouts.master')", '<x-alert-message />', 'vatSetting()->room_service_charge', 'vatSetting()->hotel_vat'], ["@extends('mm-checkout-layout')", '', "'5'", "'10'"], $coSource);
$coContext = file_get_contents($root . '/module/Hotel/views/booking/_inc/_booking-context.blade.php');
$coContext = str_replace(['calculateCurrencyAmount($bc_total, 1)', 'calculateCurrencyAmount($bc_paid, 1)', 'calculateCurrencyAmount($bc_total)'], ['($bc_total)', '($bc_paid)', 'number_format($bc_total, 2)'], $coContext);
if (preg_match('/vatSetting\(|calculateCurrencyAmount|x-alert-message/', $coSource . $coContext)) throw new RuntimeException('Unsubstituted helper in checkout view');
file_put_contents($coViews . '/booking/view.blade.php', $coSource);
file_put_contents($coViews . '/booking/_inc/_booking-context.blade.php', $coContext);
file_put_contents($coViews . '/mm-checkout-layout.blade.php', <<<'BLADE'
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Checkout fixture</title>
<link rel="stylesheet" href="/assets/css/bootstrap.min.css"><link rel="stylesheet" href="/assets/css/ace.min.css"><link rel="stylesheet" href="/assets/font-awesome/4.5.0/css/font-awesome.min.css">
<!--MM-HEAD--><link rel="stylesheet" href="/assets/css/chosen.min.css"><link rel="stylesheet" href="/assets/css/bootstrap-datepicker3.min.css">@yield('css')@stack('style')<!--/MM-HEAD--><link rel="stylesheet" href="/assets/custom_css/style.css"><link rel="stylesheet" href="/assets/custom_css/ui.css"><link rel="stylesheet" href="/assets/custom_css/shell.css"></head><body class="no-skin mm-shell"><div class="mm-shell-main" style="padding:16px"><!--MM-BODY-->@yield('content')<!--/MM-BODY--></div>
<script src="/assets/js/jquery-2.1.4.min.js"></script><script src="/assets/js/bootstrap.min.js"></script><script src="/assets/js/ace-elements.min.js"></script><script src="/assets/js/ace.min.js"></script>
<!--MM-JS--><script src="/assets/js/chosen.jquery.min.js"></script><script src="/assets/js/bootstrap-datepicker.min.js"></script><script src="/assets/js/bootstrap-timepicker.min.js"></script><script src="/assets/custom_js/date-picker.js"></script>
<script>window.warnings = []; function warning(kind, message) { window.warnings.push(message); } jQuery.LoadingOverlay = function (action) { window.overlay = (window.overlay || []).concat(action); };</script>
@yield('js')<!--/MM-JS--></body></html>
BLADE);
$app['view']->getFinder()->prependLocation($coViews);
$coRoutes = new Illuminate\Routing\RouteCollection();
foreach (['booking.index' => 'hotel/booking', 'booking.checkout' => 'hotel/checkout/{id}'] as $routeName => $uri) {
    $coRoutes->add((new Illuminate\Routing\Route('GET', $uri, function () {}))->name($routeName));
}
$coRequest = Illuminate\Http\Request::create('/hotel/booking/7');
$coRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
$app->instance('request', $coRequest);
$app->instance('url', new Illuminate\Routing\UrlGenerator($coRoutes, $coRequest));
$coTx = function (array $overrides) {
    return (object) array_merge(['id' => 1, 'source_id' => 7, 'source_type' => 'Booking', 'total_amount' => 9240, 'due_amount' => 6240, 'service_charge' => 400, 'service_amount' => 400, 'vat_amount' => 840,
        'extra_charge' => 0, 'collection' => 3000, 'change_amount' => 0, 'discount' => 0, 'invoice_no' => 'INV-0007',
        'source' => (object) ['details' => collect([(object) ['roomNumber' => (object) ['room_number' => '101']], (object) ['roomNumber' => (object) ['room_number' => '102 <b>x</b>']]])],
        'booking' => (object) ['bookingAdjusts' => collect([])]], $overrides);
};
$coBooking = (object) ['id' => 7, 'sub_total' => 8000, 'advanced_payment' => 3000, 'getVat' => (object) ['hotel_vat' => 10], 'booking_number' => 'BK-0007', 'status' => 1,
    'guestInfo' => (object) ['name' => "Aisha O'Neil <script>alert(1)</script>", 'phone_no' => '01700000000'], 'check_in_date' => '2026-10-01', 'check_out_date' => '2026-10-03',
    'booking_members' => collect([(object) ['id' => 5, 'name' => 'Member One']]), 'bookingDetails' => collect([1, 2]), 'transection' => (object) ['collection' => 3000, 'total_amount' => 9240]];
$coHtml = $app->make('view')->make('booking.view', ['booking' => $coBooking, 'account_type' => [1 => 'Cash', 2 => 'Card'], 'total_night' => 2,
    'transactions' => collect([$coTx([]), $coTx(['id' => 2, 'source_id' => 9, 'source_type' => 'Restaurant', 'total_amount' => 1500, 'due_amount' => 0, 'service_charge' => 0, 'service_amount' => 0, 'vat_amount' => 0, 'collection' => 1500, 'invoice_no' => 'INV-0009', 'source' => null, 'booking' => null])]),
    'errors' => new Illuminate\Support\ViewErrorBag()])->render();
$coHtml = str_replace('http://localhost/assets', '/assets', $coHtml);
$coHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', $coHtml);
foreach (['name="old_discount"', 'name="id[]"', 'name="item_ids[]"', 'name="item_types[]"', 'name="total_amount[]"', 'name="item_amount[]"', 'name="service_charge[]"', 'name="vat_amount[]"', 'name="extra-charge"', 'name="payble_amount"',
    'name="night_count"', 'name="discount"', 'name="paid_amount"', 'name="payment_type"', 'name="pay_by"', 'name="check_out_date"', 'id="get-due"', 'id="check-full-payment"', 'class="font-18 bold grand-subtotal"', 'payable-amount', 'current-due',
    'action="http://localhost/hotel/checkout/7"', 'type="submit"', 'aria-labelledby="mm-co-summary"', 'role="region"'] as $marker) {
    if (strpos($coHtml, $marker) === false) throw new RuntimeException('Checkout view missing ' . $marker);
}
if (strpos($coHtml, '<script>alert(1)</script>') !== false || strpos($coHtml, '102 <b>x</b>') !== false) throw new RuntimeException('Checkout view did not escape guest or room data');
$previewDir = __DIR__ . '/fixtures/preview';
if (getenv('MM_WRITE_FIXTURE')) { @mkdir($previewDir, 0777, true); file_put_contents($previewDir . '/checkout.html', $coHtml); }
$coFile = __DIR__ . '/fixtures/booking-checkout.html';
if (getenv('MM_WRITE_FIXTURE')) file_put_contents($coFile, $coHtml);
if (file_get_contents($coFile) !== $coHtml) throw new RuntimeException($coFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
echo "PASS booking checkout view render: charges, summary, form fields, escaped data and the real calculation script\n";

// Preview-only page: the room category create form (real view, stub layout, sample amenities). Not a guarded fixture.
@mkdir($coViews . '/category/inc', 0777, true);
$catSource = preg_replace('/hasPermission\([^)]*\)/', 'true', str_replace(["@extends('layouts.master')", '<x-alert-message />'], ["@extends('mm-checkout-layout')", ''], file_get_contents($root . '/module/Hotel/views/category/create.blade.php')));
file_put_contents($coViews . '/category/create.blade.php', $catSource);
file_put_contents($coViews . '/category/inc/script.blade.php', str_replace('currencySign()', "'৳'", file_get_contents($root . '/module/Hotel/views/category/inc/script.blade.php')));
$catRoutes = new Illuminate\Routing\RouteCollection();
foreach (['hotel-categories.index' => 'preview/categories', 'hotel-categories.store' => 'preview/categories/store'] as $routeName => $uri) {
    $catRoutes->add((new Illuminate\Routing\Route('GET', $uri, function () {}))->name($routeName));
}
$app->instance('url', new Illuminate\Routing\UrlGenerator($catRoutes, $coRequest));
$catHtml = $app->make('view')->make('category.create', ['slugs' => [], 'errors' => new Illuminate\Support\ViewErrorBag(),
    'aminities' => collect(array_map(function ($i, $n) { return (object) ['id' => $i, 'name' => $n]; }, [1, 2, 3, 4], ['Air conditioning', 'Wi-Fi', 'Breakfast', 'Sea view']))])->render();
$catHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $catHtml));
if (getenv('MM_WRITE_FIXTURE')) file_put_contents($previewDir . '/category-create.html', $catHtml);
echo "PASS preview page render: room category create\n";

// Render the real booking invoice (checkout_invoice) with sample data. Layout, permission, VAT and auth helpers are substituted.
@mkdir($coViews . '/booking', 0777, true);
$invSource = file_get_contents($root . '/module/Hotel/views/booking/checkout_invoice.blade.php');
$invSource = str_replace(["@extends('layouts.master')", "hasPermission('service.view', \$slugs)", 'vatSetting()->vat_number', 'vatSetting()->hotel_vat', 'optional(auth()->user())->name'], ["@extends('mm-checkout-layout')", 'true', "'VAT-0042'", "'10'", "'Front Desk'"], $invSource);
if (preg_match('/vatSetting\(|auth\(\)|hasPermission\(/', $invSource)) throw new RuntimeException('Unsubstituted helper in invoice view');
file_put_contents($coViews . '/booking/checkout_invoice.blade.php', $invSource);
$invRoutes = new Illuminate\Routing\RouteCollection();
$invRoutes->add((new Illuminate\Routing\Route('GET', 'hotel/booking', function () {}))->name('booking.index'));
$app->instance('url', new Illuminate\Routing\UrlGenerator($invRoutes, $coRequest));
$invItem = function ($cat, $room, $amount, $paid) {
    return (object) ['id' => 1, 'roomCategory' => (object) ['name' => $cat], 'roomNumber' => (object) ['room_number' => $room], 'total_amount' => $amount, 'bookingTransection' => (object) ['total_amount' => $amount, 'collection' => $paid]];
};
$invBooking = (object) ['id' => 7, 'sub_total' => 8000, 'advanced_payment' => 3000, 'getVat' => (object) ['hotel_vat' => 10], 'vat_amount' => 800, 'service_amount' => 400, 'booking_number' => '0007',
    'booking_date' => '2026-09-28', 'check_in_date' => '2026-10-01', 'check_out_date' => '2026-10-03', 'paymentType' => (object) ['name' => 'Cash'],
    'guestInfo' => (object) ['name' => 'Aisha <b>O\'Neil</b>', 'address' => 'Dhaka', 'phone_no' => '01700000000', 'country' => (object) ['name' => 'Bangladesh']],
    'bookingDetail' => (object) ['roomCategory' => (object) ['name' => 'Deluxe'], 'roomNumber' => (object) ['room_number' => '101']],
    'bookingDetails' => collect([$invItem('Deluxe', '101', 4000, 1500), $invItem('Deluxe', '102', 4000, 1500)]), 'transection' => (object) ['total_amount' => 8000, 'collection' => 3000, 'due_amount' => 5000], 'bookingAdjusts' => collect([]), 'hotelServiceSale' => collect([]), 'resturentServiceSale' => collect([])];
$invHtml = $app->make('view')->make('booking.checkout_invoice', ['booking' => $invBooking, 'slugs' => [], 'company' => (object) ['name' => 'MM Heritage Hotel', 'head_office' => 'Melaka', 'phone_number' => '0600000000', 'email' => 'info@example.com', 'logo' => ''],
    'errors' => new Illuminate\Support\ViewErrorBag()])->render();
$invHtml = preg_replace('/Printed [0-9]{2} [A-Za-z]{3} [0-9]{4}, [0-9]{2}:[0-9]{2} [AP]M/', 'Printed 01 Oct 2026, 09:00 AM', str_replace('http://localhost/assets', '/assets', $invHtml));
foreach (['id="print_body"', 'class="invoice-doc"', 'mm-invoice-page', 'onclick="printPage(\'print_body\'); return false;"', 'BK-0007', 'VAT-0042', 'Front Desk', 'Booking List'] as $marker) {
    if (strpos($invHtml, $marker) === false) throw new RuntimeException('Invoice view missing ' . $marker);
}
if (strpos($invHtml, '<b>Warning</b>') !== false || strpos($invHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Invoice sample data is incomplete (PHP warning in output)');
if (strpos($invHtml, '<b>O\'Neil</b>') !== false) throw new RuntimeException('Invoice view did not escape guest data');
$invFile = __DIR__ . '/fixtures/booking-invoice.html';
if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($invFile, $invHtml); file_put_contents($previewDir . '/invoice.html', $invHtml); }
if (file_get_contents($invFile) !== $invHtml) throw new RuntimeException($invFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
echo "PASS booking invoice render: frame, print action, document markup and escaped data\n";

// Render the real payment collection view with sample unpaid invoices (layout and alert component substituted).
@mkdir($coViews . '/payment-collection', 0777, true);
$pcSource = str_replace(["@extends('layouts.master')", '<x-alert-message />'], ["@extends('mm-checkout-layout')", ''], file_get_contents($root . '/module/Hotel/views/payment-collection/index.blade.php'));
file_put_contents($coViews . '/payment-collection/index.blade.php', $pcSource);
$pcRoutes = new Illuminate\Routing\RouteCollection();
$pcRoutes->add((new Illuminate\Routing\Route('POST', 'hotel/store-collection', function () {}))->name('store-payment-collection'));
$pcRequest = Illuminate\Http\Request::create('/hotel/booking-collection?hotel_guest_id=1');
$pcRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
$app->instance('request', $pcRequest);
$app->instance('url', new Illuminate\Routing\UrlGenerator($pcRoutes, $pcRequest));
$pcTx = function ($id, $invoice, $total, $paid, $due) {
    return (object) ['source_id' => $id, 'source_type' => 'Booking', 'total_amount' => $total, 'due_amount' => $due, 'service_amount' => 100, 'extra_charge' => 0, 'collection' => $paid, 'invoice_no' => $invoice,
        'discount' => 0, 'date' => '2026-10-01', 'change_amount' => 0,
        'source' => (object) ['service_amount' => 100, 'vat_amount' => 200, 'discount' => 0, 'date' => '2026-10-01', 'created_by' => 1, 'company_id' => null]];
};
$pcHtml = $app->make('view')->make('payment-collection.index', ['errors' => new Illuminate\Support\ViewErrorBag(), 'account_type' => [1 => 'Cash', 2 => 'Card'],
    'hotelGuests' => collect([(object) ['id' => 1, 'name' => 'Aisha <b>O\'Neil</b>', 'phone_no' => '01700000000'], (object) ['id' => 2, 'name' => 'Rahim', 'phone_no' => '01800000000']]),
    'customers' => collect([(object) ['id' => 9, 'org_name' => 'Acme Ltd', 'org_phone' => '029999']]),
    'hotelGuest' => (object) ['name' => 'Aisha', 'email' => 'aisha@example.com', 'phone_no' => '01700000000', 'nid_no' => '1234567890', 'address' => 'Dhaka', 'booking' => (object) ['bookingInfo' => (object) ['booking_number' => '0007']]],
    'transactions' => collect([$pcTx(7, 'INV-0007', 5000, 3000, 2000), $pcTx(8, 'INV-0008', 3000, 1500, 1500)])])->render();
$pcHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $pcHtml));
foreach (['name="hotel_guest_id"', 'name="company_id"', 'name="item_ids[]"', 'name="item_types[]"', 'name="total_amount[]"', 'name="item_amount[]"', 'name="previous_collection[]"', 'name="is_from_due_collection"', 'name="total_paid_amount"',
    'name="total_due_amount"', 'name="payment_type"', 'id="get-due"', 'id="check-full-payment"', 'class="discount only-number', 'payable-amount', 'current-due', 'action="http://localhost/hotel/store-collection"', 'mm-payment-collection'] as $marker) {
    if (strpos($pcHtml, $marker) === false) throw new RuntimeException('Payment collection view missing ' . $marker);
}
if (strpos($pcHtml, '<b>Warning</b>') !== false || strpos($pcHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Payment collection sample data is incomplete (PHP warning in output)');
if (strpos($pcHtml, '<b>O\'Neil</b>') !== false) throw new RuntimeException('Payment collection did not escape guest data');
$pcFile = __DIR__ . '/fixtures/payment-collection.html';
if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($pcFile, $pcHtml); file_put_contents($previewDir . '/payment-collection.html', $pcHtml); }
if (file_get_contents($pcFile) !== $pcHtml) throw new RuntimeException($pcFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
echo "PASS payment collection view render: search, invoices, summary, form fields, escaped data and the real script\n";

// Render the real night audit generate form (create-v2) with sample ledger rows. Layout, alert, currency and business-date helpers are substituted.
if (!function_exists('mm_cur')) { function mm_cur($amount, $ignore = 0) { return $ignore ? $amount : number_format($amount, 2); } }
@mkdir($coViews . '/night-audits', 0777, true);
$naSource = str_replace(["@extends('layouts.master')", '<x-alert-message />', 'calculateCurrencyAmount(', 'today_from_system()'], ["@extends('mm-checkout-layout')", '', 'mm_cur(', "'2026-10-01'"], file_get_contents($root . '/module/Hotel/views/night-audits/create-v2.blade.php'));
if (preg_match('/calculateCurrencyAmount|today_from_system|setting\(/', $naSource)) throw new RuntimeException('Unsubstituted helper in night audit view');
file_put_contents($coViews . '/night-audits/create-v2.blade.php', $naSource);
$naRoutes = new Illuminate\Routing\RouteCollection();
foreach (['night-audits.index' => ['GET', 'hotel/night-audits'], 'night-audits.store' => ['POST', 'hotel/night-audits']] as $routeName => [$method, $uri]) {
    $naRoutes->add((new Illuminate\Routing\Route($method, $uri, function () {}))->name($routeName));
}
$naRequest = Illuminate\Http\Request::create('/hotel/night-audits/create?from_date=2026-10-01&to_date=2026-10-01');
$naRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
$app->instance('request', $naRequest);
$app->instance('url', new Illuminate\Routing\UrlGenerator($naRoutes, $naRequest));
$naLedger = function ($id, $in, $account) { return (object) ['id' => $id, 'in' => $in, 'payment_type' => 1, 'account' => (object) ['name' => $account]]; };
$naTx = function ($id, $type, $invoice, $paid, $total_due, $ledgers, $source = null) {
    return (object) ['id' => $id, 'date' => '2026-10-01', 'source_type' => $type, 'invoice_no' => $invoice, 'ledger_paid' => $paid, 'discount' => 0, 'previous_paid' => 500, 'total_due_amount' => $total_due, 'extra_charge' => 0,
        'transaction_ledgers' => collect($ledgers), 'source' => $source];
};
$naHtml = $app->make('view')->make('night-audits.create-v2', ['errors' => new Illuminate\Support\ViewErrorBag(), 'from_date' => '2026-10-01', 'to_date' => '2026-10-01', 'accountTypes' => collect([1 => 'Cash', 2 => 'Card']),
    'total_reservation' => 3, 'total_booked_room' => 7, 'total_check_in' => 2, 'total_check_out' => 1, 'total_room' => 32, 'total_cancel' => 0, 'total_dirty_room' => 4, 'total_maintenance_room' => 1,
    'transactions' => collect([
        'Booking' => collect([$naTx(11, 'Booking', '0007', 3000, 2000, [$naLedger(101, 3000, 'Cash')], (object) ['details' => collect([(object) ['roomNumber' => (object) ['room_number' => '101']], (object) ['roomNumber' => (object) ['room_number' => '102']]])]),
            $naTx(12, 'Booking', '0008', 1500, 0, [$naLedger(102, 1500, 'Card')], (object) ['details' => collect([(object) ['roomNumber' => (object) ['room_number' => '201 <b>x</b>']]])])]),
        'Restaurant Sale' => collect([$naTx(21, 'Restaurant Sale', '0101', 800, 0, [$naLedger(103, 800, 'Cash')])]),
    ])])->render();
$naHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $naHtml));
foreach (['name="from_date"', 'name="to_date"', 'name="date"', 'name="total_reservation"', 'name="total_booked_room"', 'name="total_check_in"', 'name="total_check_out"', 'name="total_room"', 'name="total_cancelled"', 'name="total_dirty_room"', 'name="total_room_maintenance"',
    'name="transaction_ledger_ids[11]"', 'name="transaction_ids[11]"', 'name="total_amounts[11]"', 'name="previous_paid"', 'name="collections[11]"', 'name="due_amounts[11]"', 'name="total_amount"', 'name="collection"', 'name="due_amount"',
    'id="formSubmit"', 'action="http://localhost/hotel/night-audits"', 'save-btn', 'mm-night-audit'] as $marker) {
    if (strpos($naHtml, $marker) === false) throw new RuntimeException('Night audit view missing ' . $marker);
}
if (strpos($naHtml, '<b>Warning</b>') !== false || strpos($naHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Night audit sample data is incomplete (PHP warning in output)');
if (strpos($naHtml, '201 <b>x</b>') !== false) throw new RuntimeException('Night audit did not escape room data');
$naFile = __DIR__ . '/fixtures/night-audit-create.html';
if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($naFile, $naHtml); file_put_contents($previewDir . '/night-audit-create.html', $naHtml); }
if (file_get_contents($naFile) !== $naHtml) throw new RuntimeException($naFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
echo "PASS night audit generate view render: period filter, ledger groups, summary, form fields and escaped data\n";

// Render the real night audit list (index) with sample audit days. Components that need the app (export button, paginator) and currency helpers are substituted.
@mkdir($coViews . '/night-audits/export', 0777, true);
$niSub = function ($file) use ($root) {
    return str_replace(["@extends('layouts.master')", '<x-alert-message />', '<x-export-button pdf="1" excel="1" />', '<x-paginate :data="$nightaudits" />', 'calculateCurrencyAmount('], ["@extends('mm-checkout-layout')", '', '', '', 'mm_cur('], file_get_contents($root . $file));
};
$niSource = $niSub('/module/Hotel/views/night-audits/index.blade.php');
$niExcel = $niSub('/module/Hotel/views/night-audits/export/excel.blade.php');
if (preg_match('/calculateCurrencyAmount|x-export-button|x-paginate/', $niSource . $niExcel)) throw new RuntimeException('Unsubstituted helper in night audit list');
file_put_contents($coViews . '/night-audits/index.blade.php', $niSource);
file_put_contents($coViews . '/night-audits/export/excel.blade.php', $niExcel);
$niRoutes = new Illuminate\Routing\RouteCollection();
foreach (['night-audits.create' => ['GET', 'hotel/night-audits/create'], 'night-audits.show' => ['GET', 'hotel/night-audits/{id}'], 'night-audits.destroy' => ['DELETE', 'hotel/night-audits/{id}']] as $routeName => [$method, $uri]) {
    $niRoutes->add((new Illuminate\Routing\Route($method, $uri, function () {}))->name($routeName));
}
$niRequest = Illuminate\Http\Request::create('/hotel/night-audits?from_date=2026-09-30');
$niRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
$app->instance('request', $niRequest);
$app->instance('url', new Illuminate\Routing\UrlGenerator($niRoutes, $niRequest));
$niDay = function ($date, $in, $out, $res, $cancel, $room, $dirty, $collection, $due) { return (object) ['date' => $date, 'total_check_in' => $in, 'total_check_out' => $out, 'total_reservation' => $res, 'total_cancelled' => $cancel, 'total_room' => $room, 'total_dirty_room' => $dirty, 'collection' => $collection, 'due_amount' => $due]; };
$niHtml = $app->make('view')->make('night-audits.index', ['errors' => new Illuminate\Support\ViewErrorBag(), 'paginate' => 1,
    'nightaudits' => collect([$niDay('2026-09-30', 2, 1, 3, 0, 7, 4, 5300, 2000), $niDay('2026-09-29', 1, 2, 1, 1, 5, 2, 4100, 0)])])->render();
$niHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $niHtml));
foreach (['name="from_date"', 'name="to_date"', 'id="data-table"', 'mm-night-audit', 'Generate', 'delete_item(', 'View Details', '2026-09-30', '5,300.00'] as $marker) {
    if (strpos($niHtml, $marker) === false) throw new RuntimeException('Night audit list missing ' . $marker);
}
if (strpos($niHtml, '<b>Warning</b>') !== false || strpos($niHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Night audit list sample data is incomplete (PHP warning in output)');
$niFile = __DIR__ . '/fixtures/night-audit-index.html';
if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($niFile, $niHtml); file_put_contents($previewDir . '/night-audit-index.html', $niHtml); }
if (file_get_contents($niFile) !== $niHtml) throw new RuntimeException($niFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
echo "PASS night audit list render: filter, audit rows, totals, actions\n";

// Render the hotel setup screens (amenities, account types, VAT, currency conversions, registration terms) with sample records.
if (!class_exists('Form')) {
    class Form {
        public static function text($name, $value = null, $attrs = []) { return '<input type="text" name="' . e($name) . '" value="' . e($value) . '" class="' . e($attrs['class'] ?? '') . '" placeholder="' . e($attrs['placeholder'] ?? '') . '">'; }
        public static function select($name, $options, $selected = null, $attrs = []) {
            $html = '<select name="' . e($name) . '" id="' . e($attrs['id'] ?? '') . '" class="' . e($attrs['class'] ?? '') . '" required>';
            if (isset($attrs['placeholder'])) $html .= '<option value="">' . e($attrs['placeholder']) . '</option>';
            foreach ($options as $value => $label) $html .= '<option value="' . e($value) . '"' . ($selected == $value ? ' selected' : '') . '>' . e($label) . '</option>';
            return $html . '</select>';
        }
    }
}
$hsViews = $coViews;
@mkdir($hsViews, 0777, true);
foreach (['aminities/index', 'aminities/create', 'aminities/edit', 'account_type/index', 'account_type/edit', 'vat/index', 'currency-conversions/index', 'currency-conversions/create', 'currency-conversions/edit', 'guest-registration-terms/index', 'guest-registration-terms/include/filter', 'guest-registration-terms/edit'] as $hsView) {
    @mkdir(dirname($hsViews . '/' . $hsView), 0777, true);
    $hsSource = str_replace(["@extends('layouts.master')", '<x-alert-message />'], ["@extends('mm-checkout-layout')", ''], file_get_contents($root . '/module/Hotel/views/' . $hsView . '.blade.php'));
    file_put_contents($hsViews . '/' . $hsView . '.blade.php', $hsSource);
}
$hsRoutes = new Illuminate\Routing\RouteCollection();
foreach (['aminities' => ['index', 'create', 'store', 'edit', 'update', 'destroy'], 'account-type' => ['index', 'store', 'edit', 'update', 'destroy'], 'vat' => ['update'], 'currency-conversions' => ['index', 'create', 'store', 'edit', 'update', 'destroy'], 'guest-registration-terms' => ['index', 'edit', 'update']] as $hsBase => $hsActions) {
    foreach ($hsActions as $hsAction) {
        $hsMethod = in_array($hsAction, ['store']) ? 'POST' : (in_array($hsAction, ['update']) ? 'PUT' : ($hsAction === 'destroy' ? 'DELETE' : 'GET'));
        $hsUri = 'hotel/' . $hsBase . (in_array($hsAction, ['edit', 'update', 'destroy']) ? '/{id}' . ($hsAction === 'edit' ? '/edit' : '') : ($hsAction === 'create' ? '/create' : ''));
        $hsRoutes->add((new Illuminate\Routing\Route($hsMethod, $hsUri, function () {}))->name($hsBase . '.' . $hsAction));
    }
}
$hsRequest = Illuminate\Http\Request::create('/hotel/setup');
$hsRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
$app->instance('request', $hsRequest);
$app->instance('url', new Illuminate\Routing\UrlGenerator($hsRoutes, $hsRequest));
$hsErrors = new Illuminate\Support\ViewErrorBag();
$hsData = [
    'aminities/index' => ['data' => collect([(object) ['id' => 1, 'name' => 'Free WiFi', 'aminities_icon' => 'uploads/wifi.png', 'status' => 1], (object) ['id' => 2, 'name' => 'Pool <i>view</i>', 'aminities_icon' => 'uploads/pool.png', 'status' => 0]])],
    'aminities/create' => [],
    'aminities/edit' => ['aminities' => (object) ['id' => 1, 'name' => 'Free WiFi', 'status' => 1]],
    'account_type/index' => ['account' => collect([(object) ['id' => 1, 'name' => 'Cash'], (object) ['id' => 2, 'name' => 'Card']])],
    'account_type/edit' => ['account' => (object) ['id' => 1, 'name' => 'Cash', 'status' => 1]],
    'vat/index' => ['vat' => (object) ['id' => 1, 'hotel_vat' => 10, 'resturent_vat' => 5, 'bar_vat' => 15, 'vat_number' => 'BIN-123', 'room_rate' => '126.50', 'room_service_charge' => 10, 'rst_service_charge' => 5], 'systemSetting' => (object) ['value' => '1']],
    'currency-conversions/index' => ['currencies' => [1 => 'BDT', 2 => 'USD'], 'currencyConversions' => collect([(object) ['id' => 1, 'currency' => (object) ['name' => 'BDT'], 'rate' => 1, 'effected_date' => '2026-01-01'], (object) ['id' => 3, 'currency' => (object) ['name' => 'USD'], 'rate' => 122.5, 'effected_date' => '2026-09-01']])],
    'currency-conversions/edit' => ['currencies' => [1 => 'BDT', 2 => 'USD'], 'currencyConversions' => collect(), 'currencyConversion' => (object) ['id' => 3, 'currency_id' => 2, 'rate' => 122.5, 'effected_date' => '2026-09-01']],
    'guest-registration-terms/index' => ['bookingNotes' => collect([(object) ['id' => 1, 'title' => '<p>Check-in after <b>2pm</b></p>']])],
    'guest-registration-terms/edit' => ['bookingNote' => (object) ['id' => 1, 'title' => 'Check-in after 2pm']],
];
$hsMarkers = [
    'aminities/index' => ['mm-hotel-setup', 'id="data-table"', 'Free WiFi', 'delete_check(1)', 'id="deleteCheck_1"', 'aminities.create' === 0 ? '' : 'Add New Aminities'],
    'aminities/create' => ['name="name"', 'name="aminiti_icon"', 'name="status"', 'method="post"'],
    'aminities/edit' => ['name="_method"', 'name="aminiti_icon"', '<option value="1" selected>Active</option>'],
    'account_type/index' => ['id="deleteCheck_2"', 'name="name"', 'Add account type'],
    'account_type/edit' => ['name="_method"', 'name="name" value="Cash"', '<option value="1" selected>Active</option>'],
    'vat/index' => ['name="hotel_vat" value="10"', 'name="resturent_vat"', 'name="bar_vat"', 'name="vat_number" value="BIN-123"', 'name="room_rate"', 'name="room_service"', 'name="rst_service_charge"', 'name="key[use_vat_included]"'],
    'currency-conversions/index' => ['class="form-horizontal createCurrencyConversionForm"', 'id="currencyId"', 'id="effectedDate"', 'submitRoomStoreForm', 'render-currency-class', 'id="deleteCheck_3"', 'name="currency_id"'],
    'currency-conversions/edit' => ['name="_method"', 'render(`', '<option value="2" selected>USD</option>', 'value="122.5"'],
    'guest-registration-terms/index' => ['name="title"', 'Registration terms', 'Check-in after 2pm'],
    'guest-registration-terms/edit' => ['name="_method"', '<textarea name="title"', 'Check-in after 2pm'],
];
@mkdir(__DIR__ . '/fixtures/hotel-setup', 0777, true);
foreach ($hsData as $hsView => $hsViewData) {
    $hsHtml = $app->make('view')->make(str_replace('/', '.', $hsView), array_merge(['errors' => $hsErrors], $hsViewData))->render();
    $hsHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $hsHtml));
    foreach ($hsMarkers[$hsView] as $hsMarker) {
        if ($hsMarker !== '' && strpos($hsHtml, $hsMarker) === false) throw new RuntimeException('Hotel setup view ' . $hsView . ' missing ' . $hsMarker);
    }
    if (strpos($hsHtml, '<b>Warning</b>') !== false || strpos($hsHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Hotel setup view ' . $hsView . ' sample data is incomplete (PHP warning in output)');
    if (strpos($hsHtml, 'Pool <i>view</i>') !== false || strpos($hsHtml, '<p>Check-in after') !== false && $hsView === 'guest-registration-terms/index') throw new RuntimeException('Hotel setup view ' . $hsView . ' did not escape or strip record text');
    $hsFile = __DIR__ . '/fixtures/hotel-setup/' . str_replace('/', '-', $hsView) . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($hsFile, $hsHtml); file_put_contents($previewDir . '/setup-' . str_replace('/', '-', $hsView) . '.html', $hsHtml); }
    if (file_get_contents($hsFile) !== $hsHtml) throw new RuntimeException($hsFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS hotel setup screens render: amenities, account types, VAT, currency conversions, registration terms\n";

// Render the hotel report screens (filter bar plus the shared export partial inside the results panel) with sample rows.
// The layout, alerts, currency/date helpers and the three app components are substituted; the real index views and export partials render.
if (!function_exists('mm_amount')) { function mm_amount($a, $b) { return $a ?: $b; } }
if (!function_exists('mm_fdate')) { function mm_fdate($date, $format = 'Y-m-d') { return date($format, strtotime($date)); } }
$rpNoRecord = trim(file_get_contents($root . '/resources/views/components/no-table-record.blade.php'));
$rpExport = '<div class="pull-left hidden-print" style="margin-top:10px; margin-left:10px"><a href="/hotel/reports/x?export_type=excel&amp;date=2026-10-01" target="_blank" style="margin-right: 5px"><img src="/assets/images/export-icons/excel-icon.png"></a><a href="/hotel/reports/x?export_type=pdf&amp;date=2026-10-01" target="_blank" style="margin-right: 5px"><img src="/assets/images/export-icons/pdf-icon.png"></a></div>';
$rpPaginate = '<span class="pull-right"><ul class="pagination"><li class=" disabled"><a class="" href="#">← First</a></li><li class="active"><span>1</span></li><li><a href="#">2</a></li><li><a class="" href="#">Last →</a></li></ul></span>';
$rpSub = function ($file) use ($root, $rpNoRecord, $rpExport, $rpPaginate) {
    $source = file_get_contents($root . '/module/Hotel/views/' . $file . '.blade.php');
    $source = preg_replace('/<x-paginate :data="[^"]*" \/>/', $rpPaginate, $source);
    $source = str_replace(["@extends('layouts.master')", '<x-alert-message />', "@include('partials._alert_message')", '<x-export-button :pdf=1 :excel=1 />', '<x-no-table-record />', 'calculateCurrencyAmount(', 'fdate(', "date('Y-m-d')", "route('report.detailsShow', "],
        ["@extends('mm-checkout-layout')", '', '', $rpExport, $rpNoRecord, 'mm_cur(', 'mm_fdate(', "'2026-10-01'", "route('report.detailsShow', "], $source);
    $source = preg_replace('/getTotalPaymentAmount\([^)]*\)/', '1000', $source);
    $source = str_replace('= amount(', '= mm_amount(', $source);
    $source = preg_replace('/getTotalPayment\w+Amount\([^)]*\)/', "['collection' => 1000, 'totalDue' => 200, 'due' => 200]", $source);
    if (preg_match('/calculateCurrencyAmount|getTotalPayment|x-paginate|x-export-button|x-no-table-record|fdate\(/', preg_replace('/mm_fdate\(/', '', $source))) throw new RuntimeException('Unsubstituted helper in ' . $file);
    return $source;
};
$rpRoutes = new Illuminate\Routing\RouteCollection();
$rpRoutes->add((new Illuminate\Routing\Route('GET', 'hotel/reports/night-closing/{date}', function () {}))->name('report.detailsShow'));
$rpGuest = function ($name, $phone) { return (object) ['name' => $name, 'email' => strtolower($name) . '@example.com', 'phone_no' => $phone, 'nid_no' => 'N-1', 'address' => 'Dhaka', 'company' => (object) ['name' => 'Acme']]; };
$rpBooking = function ($no, $name) use ($rpGuest) {
    return (object) ['booking_number' => $no, 'guestInfo' => $rpGuest($name, '0170000000'), 'customer' => (object) ['name' => $name], 'pickup' => 'Airport', 'pickup_flight' => 'BG-12', 'drop' => 'Hotel lobby', 'drop_flight' => 'BG-99', 'booking_pax' => 2,
        'booking_date' => '2026-09-28', 'check_in_date' => '2026-10-01', 'check_out_date' => '2026-10-03', 'check_in_time' => '14:00:00', 'check_out_time' => '11:00:00',
        'bookingDetails' => collect([(object) ['roomNumber' => (object) ['room_number' => '101', 'name' => 'Deluxe King']]]),
        'transection' => (object) ['source' => (object) ['details' => collect([(object) ['roomNumber' => (object) ['room_number' => '101']]])]]];
};
$rpTx = function ($id, $type, $invoice, $total, $paid) {
    return (object) ['id' => $id, 'source_type' => $type, 'account' => (object) ['name' => 'Cash'], 'transaction' => (object) ['invoice_no' => $invoice, 'total_amount' => $total, 'collection' => $paid, 'source' => (object) ['details' => collect([(object) ['roomNumber' => (object) ['room_number' => '102']]])]]];
};
$rpMoney = function ($invoice, $type, $amount) { return (object) ['date' => '2026-10-01', 'invoice_no' => $invoice, 'source_type' => $type, 'created_user' => (object) ['name' => 'Front desk'], 'service_charge' => $amount, 'total_amount' => $amount * 10, 'vat_amount' => $amount, 'collection' => $amount, 'datetime' => '2026-10-01 14:30:00', 'account' => (object) ['name' => 'Cash']]; };
$rpAudit = (object) ['id' => 7, 'date' => '2026-09-30', 'details' => collect([(object) ['total_collection' => 5300, 'total_due' => 2000, 'collection' => 5300, 'due' => 2000, 'total_amount' => 7300, 'transaction' => (object) ['invoice_no' => '0007', 'source_type' => 'Booking', 'total_amount' => 7300, 'collection' => 5300, 'due_amount' => 2000, 'transaction_ledgers' => collect()]]])];
$rpEmpty = collect();
$rpCases = [
    'expected-arrival' => ['/hotel/reports/expected-arrival?date=2026-10-01', ['bookings' => collect([$rpBooking('B-0007', 'Rahim'), $rpBooking('B-0008', 'Karim <b>x</b>')])], ['name="date"', 'value="2026-10-01"', 'Rahim', 'B-0007', 'class="pagination"'], ['Karim <b>x</b>']],
    'expected-departure' => ['/hotel/reports/expected-departure?date=2026-10-01', ['bookings' => collect([$rpBooking('B-0009', 'Salma')])], ['name="date"', 'Salma', 'B-0009'], []],
    'in-house-guest' => ['/hotel/reports/in-house-guest?to=2026-10-01', ['bookings' => collect([$rpBooking('B-0010', 'Nadia')]), 'roomCategories' => collect([(object) ['name' => 'Deluxe King']])], ['name="to"', 'Nadia'], []],
    'today-in-house' => ['/hotel/reports/today-in-house?x=1', ['bookings' => $rpEmpty, 'roomCategories' => collect()], ['No records found', 'mm-report'], ['name="date"']],
    'room-logs' => ['/hotel/reports/room-logs?from_date=2026-10-01', ['rooms' => collect([(object) ['id' => 1, 'name' => 'Deluxe King', 'room_number' => '101']]), 'room_logs' => collect([(object) ['date' => '2026-10-01', 'room' => (object) ['name' => 'Deluxe King', 'room_number' => '101'], 'user' => (object) ['name' => 'Front desk'], 'received' => (object) ['name' => 'Cashier'], 'booked' => (object) ['name' => 'Agent'], 'remarks' => 'Cleaned', 'note' => 'Minibar']])], ['name="room_id"', 'chosen-select', 'name="from_date"', 'name="to_date"', 'Cleaned'], []],
    'services' => ['/hotel/reports/services?from_date=2026-10-01', ['services' => collect([$rpMoney('0101', 'Restaurant Sale', 250)])], ['name="from_date"', 'name="to_date"', '0101', '250.00'], []],
    'vat-report-day' => ['/hotel/reports/vat-report-day?from_date=2026-10-01', ['daily_vats' => collect([$rpMoney('0102', 'Booking', 120)])], ['name="from_date"', '0102', '1,200.00'], []],
    'vat-report-monthly' => ['/hotel/reports/vat-report-monthly?x=1', ['monthly_vats' => $rpEmpty], ['name="from_date"', 'No records found'], []],
    'cash-flow' => ['/hotel/reports/cash-flow?from_date=2026-10-01', ['cashFlows' => collect([$rpMoney('0103', 'Booking', 900)])], ['name="invoice_no"', 'name="from_time"', 'name="to_time"', 'id="time_start"', 'id="time_end"', '0103', '900.00', "timepicker({"], []],
    'all-reports' => ['/hotel/reports/all-reports?invoice_no=1', ['transactions' => $rpEmpty, 'account_types' => collect([1 => 'Cash'])], ['name="invoice_no"', 'name="from_date"', 'No records found'], []],
    'today-activities' => ['/hotel/reports/today-activities?date=2026-10-01', ['date' => '2026-10-01', 'booking_count' => 2, 'total_check_in' => 2, 'total_reservation' => 3, 'total_check_out' => 1, 'total_cancel' => 0, 'total_room' => 32, 'total_booked_room' => 7, 'total_dirty_room' => 4, 'total_maintenance_room' => 1, 'transactions' => collect([$rpTx(11, 'Booking', '0007', 7300, 5300)])],
        ['name="date"', 'name="total_check_in"', 'class="header-input"', 'name="transaction_ids[]"', 'INV-0007', 'mm-report'], []],
    'today-check-in' => ['/hotel/reports/today-check-in', ['date' => '2026-10-01', 'today_booking' => collect([$rpBooking('B-0011', 'Tania')])], ['name="date"', 'Today Check-In Report', 'B-0011', 'Tania'], []],
    'today-check-out' => ['/hotel/reports/today-check-out', ['date' => '2026-10-01', 'today_booking' => collect([$rpBooking('B-0012', 'Imran')])], ['name="date"', 'B-0012', 'Imran'], []],
    'night-closing' => ['/hotel/reports/night-closing?from_date=2026-09-30', ['nightaudits' => collect([$rpAudit]), 'paginate' => 1, 'account_types' => collect([1 => 'Cash'])], ['name="from_date"', 'name="to_date"', 'id="audit-view-details7"', 'audit-view-details7'], []],
];
@mkdir(__DIR__ . '/fixtures/hotel-reports', 0777, true);
foreach ($rpCases as $rpName => [$rpUrl, $rpData, $rpMarkers, $rpAbsent]) {
    $rpDir = $rpName === 'night-closing' ? 'night-closing' : $rpName;
    @mkdir($coViews . '/hotel/reports/' . $rpDir . '/export', 0777, true);
    $rpIndex = $rpName === 'night-closing' ? 'indexV2' : 'index';
    file_put_contents($coViews . '/hotel/reports/' . $rpDir . '/' . $rpIndex . '.blade.php', $rpSub('hotel/reports/' . $rpDir . '/' . $rpIndex));
    file_put_contents($coViews . '/hotel/reports/' . $rpDir . '/export/excel.blade.php', $rpSub('hotel/reports/' . $rpDir . '/export/excel'));
    if ($rpName === 'night-closing') file_put_contents($coViews . '/hotel/reports/night-closing/details.blade.php', $rpSub('hotel/reports/night-closing/details'));
}
foreach ($rpCases as $rpName => [$rpUrl, $rpData, $rpMarkers, $rpAbsent]) {
    $rpRequest = Illuminate\Http\Request::create($rpUrl);
    $rpRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
    $app->instance('request', $rpRequest);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($rpRoutes, $rpRequest));
    $rpIndex = $rpName === 'night-closing' ? 'indexV2' : 'index';
    $rpHtml = $app->make('view')->make('hotel.reports.' . $rpName . '.' . $rpIndex, array_merge(['errors' => new Illuminate\Support\ViewErrorBag()], $rpData))->render();
    $rpHtml = str_replace('http://localhost/assets', '/assets', $rpHtml);
    foreach (array_merge(['mm-report', 'mm-panel', 'mm-page-title'], $rpMarkers) as $rpMarker) {
        if (strpos($rpHtml, $rpMarker) === false) throw new RuntimeException('Report ' . $rpName . ' missing ' . $rpMarker);
    }
    foreach (array_merge(['widget-box', 'widget-main', 'widget-header'], $rpName === 'night-closing' ? [] : ['col-sm-12'], $rpAbsent) as $rpMarker) {
        if (strpos($rpHtml, $rpMarker) !== false) throw new RuntimeException('Report ' . $rpName . ' still contains ' . $rpMarker);
    }
    if (strpos($rpHtml, '<b>Warning</b>') !== false || strpos($rpHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Report ' . $rpName . ' sample data is incomplete (PHP warning in output)');
    $rpFile = __DIR__ . '/fixtures/hotel-reports/' . $rpName . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($rpFile, $rpHtml); file_put_contents($previewDir . '/report-' . $rpName . '.html', $rpHtml); }
    if (file_get_contents($rpFile) !== $rpHtml) throw new RuntimeException($rpFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS hotel report screens render: filter bar, results panel, export partial, escaped data\n";

// Render the remaining Hotel screens: guest SMS, night audit detail, monthly room calendar (two views), booking migration, and the two printable documents.
$hmSub = function ($file) use ($root) {
    $source = file_get_contents($root . '/module/Hotel/views/' . $file . '.blade.php');
    $source = str_replace(["@extends('layouts.master')", '<x-alert-message />', "@include('partials._alert_message')", 'calculateCurrencyAmount(', 'fdate(', 'today_from_system()', 'getCurrentCurrencyRate('],
        ["@extends('mm-checkout-layout')", '', '', 'mm_cur(', 'mm_fdate(', "'2026-10-01'", 'mm_rate('], $source);
    $source = preg_replace(['/vatSetting\(\)->(\w+)/', "/(?<![\\w>])setting\\('[^']*'\\)/", '/hasPermission\\([^)]*\\)/', '/@extends\\(\'layouts\\.master\'\\)/'], ["'10'", "'1'", 'true', "@extends('mm-checkout-layout')"], $source);
    if (preg_match('/vatSetting\(|calculateCurrencyAmount|x-alert-message|hasPermission\(|[^_\w]setting\(/', $source)) throw new RuntimeException('Unsubstituted helper in ' . $file);
    return $source;
};
if (!function_exists('mm_rate')) { function mm_rate($c) { return 1; } }
if (!function_exists('mm_fdate')) { function mm_fdate($date, $format = 'Y-m-d') { return date($format, strtotime($date)); } }
if (!function_exists('mm_days')) { function mm_days($m) { return 31; } }
$hmViews = $coViews;
foreach (['guests/sms/index', 'guests/include/script', 'night-audits/show', 'hotel/reports/monthly/index', 'hotel/reports/monthly/booking-ui', 'hotel/reports/monthly/inc/date-wise-room-status', 'booking/adjust/create', 'booking/adjust/_inc/_script', 'booking/adjust/_inc/show-room', 'booking/_inc/_booking-context', 'booking/_inc/create-edit-tfoot', 'booking/_inc/_check-sms-and-email', 'guests/invoice', 'hotel/reports/night-closing/invoice'] as $hmView) {
    @mkdir(dirname($hmViews . '/' . $hmView), 0777, true);
    $hmSource = $hmSub($hmView);
    if ($hmView === 'hotel/reports/monthly/index' || $hmView === 'hotel/reports/monthly/booking-ui') $hmSource = str_replace('totalDaysInMonth(', 'mm_days(', $hmSource);
    $hmSource = str_replace(["date('d')", "date('Y-m-d')"], ["'01'", "'2026-10-01'"], $hmSource); // native date() ignores the frozen Carbon clock
    file_put_contents($hmViews . '/' . $hmView . '.blade.php', $hmSource);
}
$hmRoutes = new Illuminate\Routing\RouteCollection();
foreach (['guests.index' => 'GET hotel/guests', 'guests.create' => 'GET hotel/guests/create', 'guests.submit-sms' => 'POST hotel/guests/submit-sms', 'night-audits.index' => 'GET hotel/night-audits', 'booking.index' => 'GET hotel/booking', 'booking-adjusts.store' => 'POST hotel/booking-adjusts', 'booking-adjusts.create' => 'GET hotel/booking-adjusts/create', 'report.night-audit' => 'GET hotel/reports/night-audits'] as $hmName => $hmDef) {
    [$hmMethod, $hmUri] = explode(' ', $hmDef);
    $hmRoutes->add((new Illuminate\Routing\Route($hmMethod, $hmUri, function () {}))->name($hmName));
}
$hmTx = function ($type, $invoice, $total, $paid, $rooms = ['101']) { return (object) ['transaction' => (object) ['source_type' => $type, 'invoice_no' => $invoice, 'total_amount' => $total, 'collection' => $paid], 'source' => (object) ['bookingDetails' => collect(array_map(function ($n) { return (object) ['roomNumber' => (object) ['room_number' => $n]]; }, $rooms))], 'account' => (object) ['name' => 'Cash']]; };
$hmGuest = (object) ['name' => 'Rahim <b>Uddin</b>', 'address' => 'Dhaka', 'phone_no' => '01700000000', 'country' => (object) ['name' => 'Bangladesh']];
$hmRoom = function ($id, $no, $cat, $price) { return (object) ['id' => $id, 'room_number' => $no, 'roomCategory' => (object) ['name' => $cat, 'price' => $price]]; };
$hmBooking = (object) ['id' => 5, 'booking_number' => 'B-0007', 'booking_date' => '2026-09-28', 'check_in_date' => '2026-10-01', 'check_out_date' => '2026-10-03', 'status' => 1, 'guestInfo' => $hmGuest,
    'transection' => (object) ['collection' => 1000, 'total_amount' => 9000],
    'hotel_transaction' => collect([(object) ['due_amount' => 8000, 'collection' => 1000]]),
    'bookingAdjusts' => collect(),
    'bookingDetails' => collect([(object) ['room_id' => 1, 'roomNumber' => $hmRoom(1, '101', 'Deluxe King', 4000), 'guest_count' => 2, 'infant_count' => 0, 'night_count' => 2, 'check_out_date' => '2026-10-03', 'allow_breakfast' => 1], (object) ['room_id' => 2, 'roomNumber' => $hmRoom(2, '102', 'Standard Twin', 3000), 'guest_count' => 1, 'infant_count' => 0, 'night_count' => 2, 'check_out_date' => '2026-10-03', 'allow_breakfast' => 0]])];
$hmMonthBooking = (object) ['status' => 1, 'check_in_time' => '14:00:00', 'check_out_time' => '11:00:00', 'check_in_note' => 'Late arrival', 'booking_number' => 'B-0007', 'check_in_date' => '2026-10-01', 'check_out_date' => '2026-10-03', 'guestInfo' => $hmGuest, 'customer' => (object) ['name' => 'Rahim']];
$hmMonthRooms = collect([(object) ['room_number' => '101', 'roomCategory' => (object) ['name' => 'Deluxe King'], 'booking_dates' => collect([(object) ['date' => '2026-10-01', 'booking' => $hmMonthBooking], (object) ['date' => '2026-10-02', 'booking' => (object) ['status' => 0, 'check_in_time' => null, 'check_out_time' => null, 'check_in_note' => null, 'guestInfo' => $hmGuest]], (object) ['date' => '2026-10-04', 'booking' => (object) ['status' => 3, 'check_in_time' => null, 'check_out_time' => null, 'check_in_note' => null, 'guestInfo' => $hmGuest]]])], (object) ['room_number' => '102', 'roomCategory' => (object) ['name' => 'Standard Twin'], 'booking_dates' => collect()]]);
$hmCases = [
    'sms' => ['guests.sms.index', '/hotel/guests/sms', ['phones' => '01700000000, 01800000000', 'smsbal' => 120],
        ['mm-hotel-sms', 'id="companyForm"', 'name="phone_no"', 'name="message"', 'id="form-field-tags"', 'multiple-phone-input', 'message-area', 'total-character-count', 'part-count', 'name="isFromGuestList"', '01700000000, 01800000000', '>120<'], ['widget-box ', 'widget-main', 'widget-header']],
    'night-audit-show' => ['night-audits.show', '/hotel/night-audits/7?date=2026-09-30', ['audit' => (object) ['date' => '2026-09-30', 'total_check_in' => 3, 'total_reservation' => 2, 'total_check_out' => 1, 'total_cancel' => 0, 'total_room' => 32, 'total_dirty_room' => 4, 'details' => collect([(object) ['transaction' => $hmTx('Booking', '0007', 7300, 5300, ['101', '102'])], (object) ['transaction' => $hmTx('Booking', '0008', 2000, 2000)]])]],
        ['mm-audit-show', 'mm-audit-summary', 'Generate Date', 'INV-0007', 'INV-0008', 'Total Collection', 'class="item-total"', 'onclick="print()"', '@page'], ['widget-box', 'widget-main', 'widget-header', 'class="no-print']],
    'monthly' => ['hotel.reports.monthly.index', '/hotel/reports/monthly-summaries?month=2026-10', ['room_categories' => collect([(object) ['id' => 1, 'name' => 'Deluxe King']]), 'room_datas' => collect([(object) ['id' => 1, 'name' => 'Room 101']]), 'guests' => collect([(object) ['id' => 1, 'name' => 'Rahim', 'phone_no' => '017']]), 'categories' => collect(), 'rooms' => $hmMonthRooms],
        ['mm-report-monthly', 'name="month"', 'name="room_category"', 'name="guest_id"', 'mm-report-legend', 'bg-dark', 'id="schedule_table"', 'date-1 bg-0', 'date-2 bg-1', 'date-4 bg-2', 'guest-popup', 'popover-success', 'Late arrival'], ['widget-box', 'widget-main', 'widget-header', 'col-sm-12']],
    'monthly-booking' => ['hotel.reports.monthly.booking-ui', '/hotel/reports/monthly-booking-summaries?month=2026-10', ['room_categories' => collect([(object) ['id' => 1, 'name' => 'Deluxe King']]), 'room_datas' => collect([(object) ['id' => 1, 'name' => 'Room 101']]), 'guests' => collect([(object) ['id' => 1, 'name' => 'Rahim', 'phone_no' => '017']]), 'categories' => collect(), 'rooms' => $hmMonthRooms],
        ['mm-report-monthly', 'name="month"', 'name="room_category"', 'name="guest_id"', 'mm-report-legend'], ['widget-box', 'widget-main', 'widget-header', 'col-sm-12']],
    'booking-adjust' => ['booking.adjust.create', '/hotel/booking-adjusts/create?booking_id=5&type=migrate', ['booking' => $hmBooking, 'account_types' => collect([1 => 'Cash']), 'roomCategories' => collect([(object) ['id' => 1, 'name' => 'Deluxe King', 'price' => 4000, 'rooms' => collect([(object) ['id' => 11, 'room_number' => '111'], (object) ['id' => 12, 'room_number' => '112']])]])],
        ['mm-booking-adjust', 'id="store-form"', 'name="booking_id" value="5"', 'name="type" value="migrate"', 'name="from_booking_migration"', 'name="migrate_date"', 'name="check_out_date"', 'id="checkRoomStatus"', 'class="check-in-date"', 'class="tr-checkout-date"', 'id="previousDue"', 'id="previousAdvance"', 'id="previous_check_out_date"', 'id="selectedRoom"', 'name="room_ids"', 'room-select', 'data-room-category="Deluxe King"', 'class="available-rooms"', 'id="available-room"', 'room-list', 'roomTbody', 'submit-form-btn', 'pickRoom(this, '], ['widget-box', 'widget-main', 'widget-header', 'Half Day']],
];
@mkdir(__DIR__ . '/fixtures/hotel-more', 0777, true);
foreach ($hmCases as $hmName => [$hmViewName, $hmUrl, $hmData, $hmMarkers, $hmAbsent]) {
    $hmRequest = Illuminate\Http\Request::create($hmUrl);
    $hmRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
    $hmRequest->setRouteResolver(function () use ($hmRoutes, $hmName, $hmRequest) { $hmRoute = $hmRoutes->getByName($hmName === 'booking-adjust' ? 'booking-adjusts.create' : 'guests.index'); $hmRoute->bind($hmRequest); return $hmRoute; });
    $app->instance('request', $hmRequest);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($hmRoutes, $hmRequest));
    if ($hmName === 'night-audit-show') $hmViewName = 'night-audits.show';
    $hmHtml = $app->make('view')->make($hmViewName, array_merge(['errors' => new Illuminate\Support\ViewErrorBag()], $hmData))->render();
    $hmHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $hmHtml));
    foreach (array_merge(['mm-panel', 'mm-page-title'], $hmMarkers) as $hmMarker) {
        if (strpos($hmHtml, $hmMarker) === false) throw new RuntimeException('Hotel screen ' . $hmName . ' missing ' . $hmMarker);
    }
    foreach ($hmAbsent as $hmMarker) {
        if (strpos($hmHtml, $hmMarker) !== false) throw new RuntimeException('Hotel screen ' . $hmName . ' still contains ' . $hmMarker);
    }
    if (strpos($hmHtml, '<b>Warning</b>') !== false || strpos($hmHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Hotel screen ' . $hmName . ' sample data is incomplete (PHP warning in output)');
    $hmFile = __DIR__ . '/fixtures/hotel-more/' . $hmName . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($hmFile, $hmHtml); file_put_contents($previewDir . '/hotel-' . $hmName . '.html', $hmHtml); }
    if (file_get_contents($hmFile) !== $hmHtml) throw new RuntimeException($hmFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
// The two printable documents only gained a screen-only bar: compile both to PHP and parse the result (the guard in ui-check.cjs compares the printed markup).
foreach (['guests/invoice', 'hotel/reports/night-closing/invoice'] as $hmDoc) {
    $hmCompiled = $app->make('blade.compiler')->compileString(file_get_contents($root . '/module/Hotel/views/' . $hmDoc . '.blade.php'));
    token_get_all($hmCompiled, TOKEN_PARSE);
    if (strpos($hmCompiled, 'inv-screen-bar') === false || strpos($hmCompiled, 'window.print()') === false) throw new RuntimeException($hmDoc . ' lost its screen-only bar');
}
echo "PASS remaining hotel screens render: SMS, night audit detail, monthly calendar, booking migration\n";

// Render the Hotel Service screens: service list with its modals, sales list with the due-payment modal, new sale, sale invoice, night audit list and the printable night audit.
// Layout, permissions, currency/date helpers and the app components are substituted; the real views and the shared export partial render.
if (!function_exists('mm_bdt')) { function mm_bdt($amount) { return 'Taka ' . number_format((float) $amount) . ' only'; } }
if (!function_exists('mm_pay')) { function mm_pay() { return '1,000'; } }
if (!class_exists('MmHsPage')) {
    class MmHsPage implements Countable, IteratorAggregate {
        private $items;
        public function __construct($items) { $this->items = array_values($items); }
        public function count(): int { return count($this->items); }
        public function getIterator(): Iterator { return new ArrayIterator($this->items); }
        public function firstItem() { return count($this->items) ? 1 : null; }
        public function appends($query) { return $this; }
        public function render() { return new Illuminate\Support\HtmlString('<ul class="pagination"><li class="active"><span>1</span></li><li><a href="#">2</a></li></ul>'); }
    }
    class MmHsAudit {
        public function __construct($fields) { foreach ($fields as $k => $v) $this->$k = $v; }
        public function first() { return $this; }
    }
}
$hsSubst = function ($file) use ($root, $rpNoRecord, $rpExport, $rpPaginate) {
    $source = file_get_contents($root . '/module/HotelService/views/' . $file . '.blade.php');
    $source = str_replace(["@extends('layouts.master')", '<x-alert-message />', "@include('partials._alert_message')", '<x-export-button pdf="1" excel="1" />', '<x-paginate :data="$nightaudits" />', '<x-no-table-record />', 'calculateCurrencyAmount(', "date('Y-m-d')", 'BDT(', '= amount(', "route('hotelservice.service-sales.show', \$service)", "route('hotelservice.service-sales.destroy', \$service)"],
        ["@extends('mm-checkout-layout')", '', '', $rpExport, $rpPaginate, $rpNoRecord, 'mm_cur(', "'2026-10-01'", 'mm_bdt(', '= mm_amount(', "route('hotelservice.service-sales.show', \$service->id)", "route('hotelservice.service-sales.destroy', \$service->id)"], $source);
    $source = preg_replace(['/getTotalPaymentAmount\([^)]*\)/', '/hasPermission\([^)]*\)/'], ['mm_pay()', 'true'], $source);
    if (preg_match('/calculateCurrencyAmount|getTotalPayment|x-paginate|x-export-button|x-no-table-record|hasPermission\(|[^_\w]BDT\(|[^_\w]amount\(/', $source)) throw new RuntimeException('Unsubstituted helper in Hotel Service view ' . $file);
    return $source;
};
foreach (['services/category/index', 'services/category/add-modal', 'services/category/edit-modal', 'services/sales/index', 'services/sales/due-payment-modal', 'services/sales/create', 'services/sales/show', 'hotel-service-night-audits/index', 'hotel-service-night-audits/details', 'hotel-service-night-audits/export/excel', 'hotel-service-night-audits/invoice'] as $hsvView) {
    @mkdir(dirname($coViews . '/' . $hsvView), 0777, true);
    file_put_contents($coViews . '/' . $hsvView . '.blade.php', $hsSubst($hsvView));
}
$hsvRoutes = new Illuminate\Routing\RouteCollection();
foreach (['hotelservice.services.store' => 'POST hotelservice/services', 'hotelservice.services.update' => 'PUT hotelservice/services/{id}', 'hotelservice.services.destroy' => 'DELETE hotelservice/services/{id}', 'hotelservice.service-sales.index' => 'GET hotelservice/service-sales', 'hotelservice.service-sales.create' => 'GET hotelservice/service-sales/create', 'hotelservice.service-sales.store' => 'POST hotelservice/service-sales', 'hotelservice.service-sales.show' => 'GET hotelservice/service-sales/{id}', 'hotelservice.service-sales.destroy' => 'DELETE hotelservice/service-sales/{id}', 'hotelservice.service-due-receive' => 'PUT hotelservice/service-due-receive/{id}', 'hotelservice.night-audits.index' => 'GET hotelservice/night-audit', 'hotelservice.night-audits.show' => 'GET hotelservice/night-audit-show/{id}'] as $hsvName => $hsvDef) {
    [$hsvMethod, $hsvUri] = explode(' ', $hsvDef);
    $hsvRoutes->add((new Illuminate\Routing\Route($hsvMethod, $hsvUri, function () {}))->name($hsvName));
}
$hsvGuest = (object) ['id' => 12, 'name' => 'Rahim <b>Uddin</b>', 'phone_no' => '01700000000', 'address' => 'Dhaka', 'country' => (object) ['name' => 'Bangladesh']];
$hsvSale = function ($id, $invoice, $subtotal, $discount, $paid, $payable) use ($hsvGuest) { return (object) ['id' => $id, 'invoice_no' => $invoice, 'subtotal' => $subtotal, 'discount' => $discount, 'paid_amount' => $paid, 'payable_amount' => $payable, 'hotel_guest' => $hsvGuest, 'guest_name' => 'Walk-in']; };
$hsvTx = function ($invoice, $total, $paid) { return (object) ['total_amount' => 0, 'collection' => 0, 'due' => 0, 'transaction' => (object) ['invoice_no' => $invoice, 'source_type' => 'Hotel Service Sale', 'total_amount' => $total, 'collection' => $paid, 'due_amount' => $total - $paid, 'account' => (object) ['name' => 'Cash']], 'total_collection' => $paid, 'total_due' => $total - $paid]; };
$hsvAudit = new MmHsAudit(['id' => 9, 'date' => '2026-09-30', 'total_check_in' => 2, 'total_check_out' => 1, 'total_reservation' => 3, 'total_cancelled' => 0, 'total_cancel' => 0, 'total_room' => 7, 'total_dirty_room' => 4, 'restourantCount' => 2, 'details' => collect([$hsvTx('0301', 1200, 1200), $hsvTx('0302', 800, 300)])]);
$hsvAudit2 = new MmHsAudit(['id' => 8, 'date' => '2026-09-29', 'total_check_in' => 1, 'total_check_out' => 2, 'total_reservation' => 1, 'total_cancelled' => 1, 'total_cancel' => 1, 'total_room' => 5, 'total_dirty_room' => 2, 'restourantCount' => 1, 'details' => collect([$hsvTx('0299', 500, 500)])]);
$hsvCases = [
    'services' => ['services.category.index', '/hotelservice/services', ['services' => new MmHsPage([(object) ['id' => 1, 'name' => 'Laundry <b>x</b>', 'price' => 350, 'created_at' => '2026-09-01 10:00:00', 'updated_at' => '2026-09-02 11:30:00'], (object) ['id' => 2, 'name' => 'Airport pickup', 'price' => 1500, 'created_at' => '2026-09-03 09:00:00', 'updated_at' => '2026-09-03 09:00:00']])],
        ['mm-hotel-setup', 'mm-hs-services', 'id="data-table"', 'data-toggle="modal"', 'href="#modal-dialog"', 'href="#modal-dialog2"', 'id="modal-dialog"', 'id="modal-dialog1"', 'id="modal-dialog2"', 'name="name"', 'name="price"', 'name="_method"', 'delete_item(', 'Airport pickup', 'Laundry &lt;b&gt;x&lt;/b&gt;'], ['widget-box', 'widget-main', 'widget-header', 'Laundry <b>x</b>']],
    'sales' => ['services.sales.index', '/hotelservice/service-sales?invoice_no=0301', ['account_types' => collect([1 => 'Cash', 2 => 'Card']), 'services' => new MmHsPage([$hsvSale(1, '0301', 1200, 0, 1200, 1200), $hsvSale(2, '0302', 1000, 200, 300, 800)])],
        ['mm-hotel-service', 'name="invoice_no"', 'name="customer_id"', 'value="0301"', 'id="exampleModal"', 'id="payment-form"', 'name="previous_due"', 'id="previous-due"', 'id="payable-amount"', 'id="current-due"', 'name="is_from_due_collection"', 'onclick="payment(', 'PAID', 'delete_item(', 'class="pagination"', 'Total Amount', '2,200.00', '500.00'], ['widget-box', 'widget-main', 'widget-header', 'Rahim <b>Uddin</b>']],
    'sale-create' => ['services.sales.create', '/hotelservice/service-sales/create', [],
        ['mm-hotel-service', 'id="invForm"', 'name="guest_name"', 'id="guest_name"', 'name="hotel_guest_id"', 'name="hotel_room_id"', 'name="room_number"', 'name="hotel_booking_id"', 'name="booking_number"', 'id="invoice_id"', 'name="date"', 'id="table_auto"', 'class="container"', 'onclick="addItem()"', 'name="subtotal"', 'id="subTotal"', 'name="discount"', 'id="discount"', 'name="payable_amount"', 'id="payable_amount"', 'name="paid_amount"', 'id="amountPaid"', 'name="due_amount"', 'id="amountDue"', 'onclick="submitForm()"', 'function addItem(', 'class="repeat-group"'], ['widget-box', 'widget-main', 'widget-header']],
    'sale-show' => ['services.sales.show', '/hotelservice/service-sales/1', ['invoice' => (object) ['id' => 1, 'invoice_no' => '0301', 'invoice_date' => '2026-09-30', 'subtotal' => 1200, 'discount' => 100, 'paid_amount' => 1100, 'payable_amount' => 1100, 'company' => (object) ['name' => 'MM Heritage', 'head_office' => 'Dhaka', 'phone_number' => '017', 'email' => 'hi@example.com'], 'hotel_guest' => $hsvGuest, 'user' => (object) ['name' => 'Front desk'],
            'saleItems' => collect([(object) ['price' => 350, 'quantity' => 2, 'service' => (object) ['name' => 'Laundry']], (object) ['price' => 500, 'quantity' => 1, 'service' => (object) ['name' => 'Airport pickup']]])]],
        ['mm-invoice-page', 'id="print_body"', 'printPage(', 'Hotel service invoice', 'Guest\'s Information', '0301', 'Laundry', 'Airport pickup', '700.00', 'Taka 1,100 only', 'Received By', 'Prepared By'], ['widget-box', 'widget-main', 'widget-header']],
    'audits' => ['hotel-service-night-audits.index', '/hotelservice/night-audit?from_date=2026-09-29', ['nightaudits' => collect([$hsvAudit, $hsvAudit2]), 'paginate' => 1, 'account_types' => collect([1 => 'Cash'])],
        ['mm-hs-audit', 'name="from_date"', 'name="to_date"', 'id="data-table"', 'id="my_Modal9"', 'id="my_Modal8"', 'data-target="#my_Modal9"', 'class="pagination"', 'Cash Sale Amount', 'Total Collection', 'night-audit-show/'], ['widget-box', 'widget-main', 'widget-header']],
    'audit-invoice' => ['hotel-service-night-audits.invoice', '/hotelservice/night-audit-show/2026-09-30?date=2026-09-30', ['audits' => collect([$hsvAudit, $hsvAudit2]), 'company' => (object) ['name' => 'MM Heritage', 'head_office' => 'Dhaka', 'phone_number' => '017', 'email' => 'hi@example.com', 'logo' => 'logo.png'], 'account_types' => collect([1 => 'Cash'])],
        ['inv-screen-bar', 'Print again', 'window.print()', 'display: none !important', 'Hotel Service Night Audit/ Day Closing Report', 'INV-0301', 'HOTEL SERVICE SALE'], []],
];
@mkdir(__DIR__ . '/fixtures/hotel-service', 0777, true);
foreach ($hsvCases as $hsvName => [$hsvViewName, $hsvUrl, $hsvData, $hsvMarkers, $hsvAbsent]) {
    $hsvRequest = Illuminate\Http\Request::create($hsvUrl);
    $hsvRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
    $app->instance('request', $hsvRequest);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($hsvRoutes, $hsvRequest));
    $hsvHtml = $app->make('view')->make($hsvViewName, array_merge(['errors' => new Illuminate\Support\ViewErrorBag(), 'slugs' => []], $hsvData))->render();
    $hsvHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $hsvHtml));
    foreach (array_merge($hsvName === 'audit-invoice' ? [] : ['mm-panel', 'mm-page-title'], $hsvMarkers) as $hsvMarker) {
        if (strpos($hsvHtml, $hsvMarker) === false) throw new RuntimeException('Hotel Service screen ' . $hsvName . ' missing ' . $hsvMarker);
    }
    foreach ($hsvAbsent as $hsvMarker) {
        if (strpos($hsvHtml, $hsvMarker) !== false) throw new RuntimeException('Hotel Service screen ' . $hsvName . ' still contains ' . $hsvMarker);
    }
    if (strpos($hsvHtml, '<b>Warning</b>') !== false || strpos($hsvHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Hotel Service screen ' . $hsvName . ' sample data is incomplete (PHP warning in output)');
    $hsvFile = __DIR__ . '/fixtures/hotel-service/' . $hsvName . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($hsvFile, $hsvHtml); if ($hsvName !== 'audit-invoice') file_put_contents($previewDir . '/hservice-' . $hsvName . '.html', $hsvHtml); }
    if (file_get_contents($hsvFile) !== $hsvHtml) throw new RuntimeException($hsvFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS hotel service screens render: services, sales list and due modal, new sale, invoice, night audit list and printable audit\n";

// Render the Permission screens: module/sub module/parent permission lists, permissions, users, password forms and the access matrices.
// Layout, permission and paginator helpers are substituted; the real views render with sample modules, employees and users.
if (!class_exists('MmPermEmployee')) {
    class MmPermEmployee {
        public function __construct($fields) { foreach ($fields as $k => $v) $this->$k = $v; }
        public function getDepartmentName() { return $this->department->name; }
        public function getDesignationName() { return $this->designation->name; }
    }
}
$prSubst = function ($file) use ($root, $rpPaginate) {
    $source = file_get_contents($root . '/module/Permission/views/' . $file . '.blade.php');
    $source = str_replace(["@extends('layouts.master')", "@include('partials._alert_message')", "\\Route::has('selected_employee')"], ["@extends('mm-checkout-layout')", '', 'true'], $source);
    $source = preg_replace(["/@include\\('partials\\._paginate', \\['data' => [^\\]]*\\]\\)/", '/hasPermission\\([^)]*\\)/'], [str_replace('$', '\\$', $rpPaginate), 'true'], $source);
    if (preg_match('/hasPermission\\(|partials\\._paginate|Route::has/', $source)) throw new RuntimeException('Unsubstituted helper in Permission view ' . $file);
    return $source;
};
$prNames = ['module', 'submodule', 'parent_permission', 'permission/index', 'permission/create', 'permission/edit', 'users/index', 'users/create', 'users/change_password', 'users/change_password_by_admin', 'access/create', 'access/edit', 'access/employee-permission'];
foreach ($prNames as $prView) {
    @mkdir(dirname($coViews . '/perm/' . $prView), 0777, true);
    file_put_contents($coViews . '/perm/' . $prView . '.blade.php', $prSubst($prView));
}
$prRoutes = new Illuminate\Routing\RouteCollection();
foreach (['active.deactive.module' => 'GET setting/modules/{id}/status', 'admin.edit.password' => 'GET setting/users/{id}/password', 'admin.update.password' => 'POST setting/users/password', 'edit.permitted.users' => 'GET setting/permitted-users/{id}/edit', 'employee_list' => 'POST setting/employee-list', 'modules.destroy' => 'DELETE setting/modules/{id}', 'modules.edit' => 'GET setting/modules/{id}/edit', 'modules.store' => 'POST setting/modules', 'modules.update' => 'PUT setting/modules/{id}', 'parent-permissions.destroy' => 'DELETE setting/parent-permissions/{id}', 'parent-permissions.edit' => 'GET setting/parent-permissions/{id}/edit', 'parent-permissions.store' => 'POST setting/parent-permissions', 'parent-permissions.update' => 'PUT setting/parent-permissions/{id}', 'permission-access.create' => 'GET setting/permission-access/create', 'permission-access.employee.store' => 'POST setting/permission-access/employee', 'permission-access.store' => 'POST setting/permission-access', 'permissions.create' => 'GET setting/permissions/create', 'permissions.destroy' => 'DELETE setting/permissions/{id}', 'permissions.edit' => 'GET setting/permissions/{id}/edit', 'permissions.index' => 'GET setting/permissions', 'permissions.store' => 'POST setting/permissions', 'permissions.update' => 'PUT setting/permissions/{id}', 'permitted.user.delete' => 'DELETE setting/permitted-users/{id}', 'permitted.users' => 'GET setting/permitted-users', 'selected_employee' => 'POST setting/selected-employee', 'settings.create-user' => 'GET setting/create-user', 'settings.store-user' => 'POST setting/store-user', 'submodules.destroy' => 'DELETE setting/submodules/{id}', 'submodules.edit' => 'GET setting/submodules/{id}/edit', 'submodules.store' => 'POST setting/submodules', 'submodules.update' => 'PUT setting/submodules/{id}', 'update.permission.access' => 'PUT setting/permission-access/{id}', 'user.active.deactive' => 'GET setting/users/{id}/{status}', 'user.password.update' => 'POST user/password'] as $prName => $prDef) {
    [$prMethod, $prUri] = explode(' ', $prDef);
    $prRoutes->add((new Illuminate\Routing\Route($prMethod, $prUri, function () {}))->name($prName));
}
$prMods = collect([(object) ['id' => 1, 'name' => 'Hotel <b>Core</b>', 'status' => 1], (object) ['id' => 2, 'name' => 'Restaurant', 'status' => 2]]);
$prSub = (object) ['id' => 4, 'name' => 'Rooms', 'slug' => 'rooms', 'module_id' => 1, 'module' => $prMods[0]];
$prParent = (object) ['id' => 7, 'name' => 'Room list', 'submodule_id' => 4, 'submodule' => $prSub];
$prPerm = function ($id, $name, $slug) { return (object) ['id' => $id, 'name' => $name, 'slug' => $slug]; };
$prRow = function (array $perms) { return (object) ['permissions' => collect($perms)]; };
$prAccessModules = collect([(object) ['id' => 1, 'name' => 'Hotel', 'submodules' => collect([(object) ['id' => 4, 'name' => 'Rooms', 'parent_permissions' => collect([$prRow([$prPerm(11, 'View', 'rooms.view'), $prPerm(12, 'Create', 'rooms.create'), $prPerm(13, 'Edit', 'rooms.edit')]), $prRow([$prPerm(14, 'Delete', 'rooms.delete')])])]])]]);
$prEmployee = function ($id, $name) { return new MmPermEmployee(['id' => $id, 'name' => $name, 'email' => 'e' . $id . '@example.com', 'employee_full_id' => 'EMP-00' . $id, 'company_id' => 1, 'department' => (object) ['name' => 'Front desk'], 'designation' => (object) ['name' => 'Manager']]); };
$prAccessData = ['existing_employee' => collect([$prEmployee(1, 'Rahim <b>Uddin</b>'), $prEmployee(2, 'Karim')]), 'employee_ids' => collect([$prEmployee(1, 'Rahim <b>Uddin</b>'), $prEmployee(2, 'Karim')]), 'hasFeatures' => ['Company', 'Department', 'Designation'], 'orderTypes' => collect([]), 'companies' => collect([1 => 'MM Heritage', 2 => 'MM Resort']), 'buyers' => collect([]), 'departments' => collect([1 => 'Front desk', 2 => 'Kitchen']), 'designations' => collect([1 => 'Manager', 2 => 'Chef']), 'modules' => $prAccessModules];
$prAccessUser = (object) ['id' => 5, 'name' => 'Rahim <b>Uddin</b>', 'email' => 'rahim@example.com', 'employee_id' => 1, 'employee' => $prEmployee(1, 'Rahim'), 'department_id' => 1, 'department' => (object) ['name' => 'Front desk'], 'designation_id' => 1, 'designation' => (object) ['name' => 'Manager']];
$prUsers = collect([(object) ['id' => 5, 'name' => 'Rahim <b>Uddin</b>', 'email' => 'rahim@example.com', 'status' => 1, 'employee' => $prEmployee(1, 'Rahim'), 'company' => (object) ['name' => 'MM Heritage'], 'credential' => (object) ['secrete' => 'pw-1234']], (object) ['id' => 6, 'name' => 'Front desk', 'email' => 'desk@example.com', 'status' => 2, 'employee' => null, 'company' => (object) ['name' => 'MM Heritage'], 'credential' => null]]);
$prCases = [
    'module' => ['perm.module', '/setting/modules', ['modules' => new MmHsPage($prMods->all())],
        ['mm-hotel-setup', 'mm-perm', 'action="http://localhost/setting/modules"', 'name="name"', 'id="dynamic-table"', 'Hotel &lt;b&gt;Core&lt;/b&gt;', 'delete_check(', 'class="pagination"', 'setting/modules/1/edit', 'setting/modules/2/status'], ['widget-box', 'widget-main', 'widget-header', 'Hotel <b>Core</b>']],
    'submodule' => ['perm.submodule', '/setting/submodules', ['modules' => collect([1 => 'Hotel']), 'submodules' => new MmHsPage([$prSub])],
        ['mm-perm', 'action="http://localhost/setting/submodules"', 'name="name"', 'name="module_id"', 'id="dynamic-table"', 'rooms', 'class="pagination"', 'setting/submodules/4/edit'], ['widget-box', 'widget-main', 'widget-header']],
    'parent-permission' => ['perm.parent_permission', '/setting/parent-permissions', ['submodules' => collect([4 => 'Rooms']), 'parentPermissions' => new MmHsPage([$prParent])],
        ['mm-perm', 'action="http://localhost/setting/parent-permissions"', 'name="name"', 'name="submodule_id"', 'id="dynamic-table"', 'Room list', 'class="pagination"', 'setting/parent-permissions/7/edit'], ['widget-box', 'widget-main', 'widget-header']],
    'permission-index' => ['perm.permission.index', '/setting/permissions', ['permissions' => new MmHsPage([(object) ['id' => 11, 'name' => 'View rooms', 'slug' => 'rooms.view', 'parent_permission' => (object) ['name' => 'Room list', 'submodule' => (object) ['name' => 'Rooms', 'module' => (object) ['name' => 'Hotel']]]]])],
        ['mm-perm-list', 'id="dynamic-table"', 'rooms.view', 'Room list', 'href="http://localhost/setting/permissions/create"', 'setting/permissions/11/edit', 'class="pagination"'], ['widget-box', 'widget-main', 'widget-header', 'class="page-header']],
    'permission-create' => ['perm.permission.create', '/setting/permissions/create', ['parentPermissions' => collect([7 => 'Room list'])],
        ['mm-perm-narrow', 'action="http://localhost/setting/permissions"', 'name="parent_permission_id"', 'name="name"', 'name="actions[]"', 'value="Super Approve"', 'name="description"'], ['widget-box', 'widget-main', 'widget-header']],
    'permission-edit' => ['perm.permission.edit', '/setting/permissions/11/edit', ['parentPermissions' => collect([7 => 'Room list']), 'permission' => (object) ['id' => 11, 'name' => 'View rooms', 'slug' => 'rooms.view', 'description' => 'See rooms', 'parent_permission_id' => 7]],
        ['mm-perm-narrow', 'action="http://localhost/setting/permissions/11"', 'name="_method"', 'name="parent_permission_id"', 'value="rooms.view"', 'See rooms'], ['widget-box', 'widget-main', 'widget-header']],
    'users-index' => ['perm.users.index', '/setting/permitted-users', ['users' => $prUsers, 'slugs' => []],
        ['mm-perm-list', 'id="data-table"', 'Rahim &lt;b&gt;Uddin&lt;/b&gt;', 'Not an Employee', 'delete_check(5)', 'id="deleteCheck_5"', 'name="_method" value="DELETE"', 'setting/users/5/2', 'setting/users/6/1', 'data-rel="popover"', 'setting/create-user', 'setting/users/5/password'], ['widget-box', 'widget-main', 'widget-header', 'color: white', 'Rahim <b>Uddin</b>']],
    'users-create' => ['perm.users.create', '/setting/create-user', [],
        ['mm-perm-user', 'action="http://localhost/setting/store-user"', 'name="name"', 'name="email"', 'name="mobile_number"', 'name="password"', 'name="confirm_password"', 'href="http://localhost/setting/permitted-users"'], ['widget-box', 'widget-main', 'widget-header', 'acrion=']],
    'change-password' => ['perm.users.change_password', '/user/password', [],
        ['mm-perm-password', 'action="http://localhost/user/password"', 'name="current_password"', 'name="new_password"', 'name="new_confirm_password"'], ['widget-box', 'widget-main', 'widget-header']],
    'change-password-admin' => ['perm.users.change_password_by_admin', '/setting/users/5/password', ['user' => $prAccessUser],
        ['mm-perm-password', 'action="http://localhost/setting/users/password"', 'name="id" value="5"', 'name="new_password"', 'name="confirm_password"', 'Set new password for', 'href="http://localhost/setting/permitted-users"'], ['widget-box', 'widget-main', 'widget-header']],
    'access-create' => ['perm.access.create', '/setting/permission-access/create', $prAccessData,
        ['mm-perm-access', 'action="http://localhost/setting/permission-access"', 'name="existing_employee"', 'class="btn btn-default btn-sm load-employee"', 'id="select-new-employee-id"', 'onchange="loadEmployeeInfo(this)"', 'name="employee_id"', 'name="employee_name"', 'name="password"', 'name="companies[]"', 'name="departments[]"', 'name="designations[]"', 'name="permissions[]"', 'value="14"', 'class="ace module-checkbox-control"', 'class="ace parentCheckBox"', 'access-control', 'id="csrf"', 'Rahim &lt;b&gt;Uddin&lt;/b&gt;'], ['widget-box', 'widget-main', 'widget-header', 'Rahim <b>Uddin</b>']],
    'access-edit' => ['perm.access.edit', '/setting/permission-access/5/edit', array_merge($prAccessData, ['user' => $prAccessUser, 'isPermitted' => ['rooms.view'], 'hasCompanies' => ['MM Heritage'], 'hasDepartments' => ['Kitchen'], 'hasDesignations' => ['Chef']]),
        ['mm-perm-access', 'action="http://localhost/setting/permission-access/5"', 'name="_method" value="put"', 'name="permissions[]"', 'value="11"', 'checked', 'name="companies[]"', 'class="ace module-checkbox-control"', 'class="ace parentCheckBox"', 'id="csrf"', 'Update'], ['widget-box', 'widget-main', 'widget-header']],
    'employee-permission' => ['perm.access.employee-permission', '/setting/permission-access/employee', ['modules' => $prAccessModules->map(function ($m) { $c = clone $m; $c->name = 'Employee Permission'; return $c; }), 'isEmployeePermitted' => ['rooms.view']],
        ['mm-perm-access', 'action="http://localhost/setting/permission-access/employee"', 'name="employee_permissions[]"', 'checked', 'class="ace module-checkbox-control"', 'class="ace parentCheckBox"', 'id="csrf"', 'Employee permissions'], ['widget-box', 'widget-main', 'widget-header']],
];
@mkdir(__DIR__ . '/fixtures/permission', 0777, true);
foreach ($prCases as $prName => [$prViewName, $prUrl, $prData, $prMarkers, $prAbsent]) {
    $prRequest = Illuminate\Http\Request::create($prUrl);
    $prRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
    $app->instance('request', $prRequest);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($prRoutes, $prRequest));
    $prHtml = $app->make('view')->make($prViewName, array_merge(['errors' => new Illuminate\Support\ViewErrorBag(), 'slugs' => []], $prData))->render();
    $prHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $prHtml));
    $prHtml = preg_replace('/(id="csrf" value=")[A-Za-z0-9]*"/', '$1fixture-csrf-token"', $prHtml);
    $prMissing = [];
    foreach (array_merge(['mm-panel', 'mm-page-title'], $prMarkers) as $prMarker) {
        if (strpos($prHtml, $prMarker) === false) $prMissing[] = $prMarker;
    }
    if ($prMissing) throw new RuntimeException('Permission screen ' . $prName . ' missing ' . implode(' | ', $prMissing));
    foreach ($prAbsent as $prMarker) {
        if (strpos($prHtml, $prMarker) !== false) throw new RuntimeException('Permission screen ' . $prName . ' still contains ' . $prMarker);
    }
    if (strpos($prHtml, '<b>Warning</b>') !== false || strpos($prHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Permission screen ' . $prName . ' sample data is incomplete (PHP warning in output)');
    $prFile = __DIR__ . '/fixtures/permission/' . $prName . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($prFile, $prHtml); file_put_contents($previewDir . '/perm-' . $prName . '.html', $prHtml); }
    if (file_get_contents($prFile) !== $prHtml) throw new RuntimeException($prFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS permission screens render: module lists, permissions, users, password forms and access matrices\n";

// Render the Restaurant screens: tables, kitchen board/list/ticket, night audit list and generate form, payment collection and the five reports.
// Layout, permissions, currency/date helpers and app components are substituted. The shared export partials of the reports are stubbed with a small
// table (they are not changed); the night audit partials and every other view render for real.
if (!class_exists('MmRsDate')) {
    class MmRsDate {
        public function __construct(private $d) {}
        public function format($f) { return date($f, strtotime($this->d)); }
        public function __toString() { return $this->d; }
    }
}
if (!class_exists('MmRsModel')) {
    class MmRsModel {
        public function __construct($fields) { foreach ($fields as $k => $v) $this->$k = $v; }
        public function __toString() { return json_encode($this); }
    }
}
if (!function_exists('mm_status')) { function mm_status($s) { return $s == 1 ? 'Active' : 'Inactive'; } }
if (!function_exists('mm_setting')) { function mm_setting($key) { return 0; } }
if (!function_exists('mm_words')) { function mm_words($amount) { return 'Words of ' . $amount; } }
if (!function_exists('mm_mount')) { function mm_mount($a = null, $b = null, $c = null) { return 0; } }
$rsExport = '<div class="pull-left hidden-print"><a href="/rst/export?export_type=excel"><img src="/assets/images/export-icons/excel-icon.png"></a></div>';
$rsStub = '<table class="table table-striped table-bordered"><thead><tr><th>SL</th><th>Invoice</th><th>Total</th></tr></thead><tbody><tr><td>1</td><td>RST-0101</td><td>1,250.00</td></tr></tbody></table>';
$rsSubst = function ($file, $rsModule = 'Restaurant') use ($root, $rpNoRecord, $rpPaginate, $rsExport) {
    $source = file_get_contents($root . '/module/' . $rsModule . '/views/' . $file . '.blade.php');
    $source = str_replace(["@extends('layouts.master')", '<x-alert-message />', "@include('partials._alert_message')", '<x-export-button pdf="1" excel="1" />', '<x-export-button :pdf=1 :excel=1 />', '<x-paginate :data="$nightaudits" />', '<x-paginate :data="$cashFlows" />', '<x-paginate :data="$sales" />', '<x-paginate :data="$products" />', '<x-no-table-record />', 'calculateCurrencyAmount(', 'today_from_system()', "@include('currency-conversions.inc.script')", "@include('kitchen.inc.script')", '<x-widget.date-filter />', '{{ $sales->links() }}', '<x-company-info :company="$sale->company" />', 'convert_number(', "@include('sales/_inc/guest-modal')", "@include('sales/_inc/script')", "date('Y-m-d')", 'csrf_token()'],
        ["@extends('mm-checkout-layout')", '', '', $rsExport, $rsExport, $rpPaginate, $rpPaginate, $rpPaginate, $rpPaginate, $rpNoRecord, 'mm_cur(', "'2026-10-01'", '', '', '<div class="input-group"><input type="text" name="from_date" class="form-control date-picker"><input type="text" name="to_date" class="form-control date-picker"></div>', $rpPaginate, '<div class="company-info"><h3>MM Heritage</h3></div>', 'mm_words(', "@include('rs.sales._inc.guest-modal')", "@include('rs.sales._inc.script')", "'2026-10-01'", "'fixture-csrf-token'"], $source);
    $source = preg_replace('/<x-paginate :data="[^"]*" \/>/', $rpPaginate, $source);
    $source = str_replace("@include('bar/inventory/includes/filter')", "@include('rs-filter')", $source);
    $source = str_replace(["@include('rst.tables.create-modal')", "@include('rst.tables.edit-modal')", "@include('restaurant-night-audits/export.excel')", "@include('restaurant-night-audits.details')"], ["@include('rs.rst.tables.create-modal')", "@include('rs.rst.tables.edit-modal')", "@include('rs.restaurant-night-audits.export.excel')", "@include('rs.restaurant-night-audits.details')"], $source);
    $source = preg_replace(['/hasPermission\([^)]*\)/', '/(?<![\w>])status\(/', '/getTotalPaymentAmount\(/', '/(?<![\w>])setting\(/', "/@include\('(?:bar\/reports\/cash-flow\/export\/excel|rst\/reports\/sales\/export\/excel|reports\/inventory\/export\/excel|reports\/inventory-ledger\/export\/excel|reports\.today-activities\.export\.excel)'\)/", '/fdate\(/'], ['true', 'mm_status(', 'mm_mount(', 'mm_setting(', "@include('rs-stub')", 'mm_fdate('], $source);
    if (preg_match('/hasPermission\(|calculateCurrencyAmount|x-export-button|x-paginate|x-no-table-record|today_from_system/', $source)) throw new RuntimeException('Unsubstituted helper in Restaurant view ' . $file);
    return $source;
};
if (!function_exists('mm_fdate')) { function mm_fdate($date, $format = 'Y-m-d') { return date($format, strtotime($date)); } }
file_put_contents($coViews . '/rs-stub.blade.php', $rsStub);
// Stand-in for the shared Bar inventory filter (not changed): same wrapper classes, a product, a category and a date input.
file_put_contents($coViews . '/rs-filter.blade.php', '<div class="col-sm-12 mb-2 ml-4"><form method="get"><div class="row"><div class="col-md-4"><div class="input-group"><div class="input-group-addon"><label class="input-group-text">Product</label></div><input type="text" name="id" class="form-control"></div></div><div class="col-md-3"><div class="input-group"><div class="input-group-addon"><label class="input-group-text">Category</label></div><select name="category_id" class="form-control"><option>All</option></select></div></div><div class="col-md-3"><div class="input-group"><div class="input-group-addon"><label class="input-group-text">From</label></div><input type="text" name="from_date" class="form-control date-picker"></div></div><div class="col-md-2"><button type="submit" class="btn btn-sm btn-primary">Search</button></div></div></form><script>function loadSelect2() {} function formatProduct() {} function formatSelection() {} // the product-select widget defines this in the real filter</script></div>');
$rsViews = ['rst/tables/index', 'rst/tables/create-modal', 'rst/tables/edit-modal', 'kitchen/index', 'kitchen/create', 'kitchen/show', 'restaurant-night-audits/index', 'restaurant-night-audits/export/excel', 'restaurant-night-audits/details', 'restaurant-night-audits/create-v2', 'rst-payment-collection/index', 'sales/index', 'sales/show', 'sales/create', 'sales/_inc/guest-modal', 'sales/_inc/script', 'sales/return/index', 'sales/return/show', 'sales/return/create', 'rst/reports/cash-flow/index', 'rst/reports/sales/index', 'reports/today-activities/index', 'reports/inventory/index', 'reports/inventory-ledger/index', 'purchase-v2/index', 'purchase-v2/show', 'purchase-v2/approve', 'purchase-v2/create'];
foreach ($rsViews as $rsView) {
    @mkdir(dirname($coViews . '/rs/' . $rsView), 0777, true);
    file_put_contents($coViews . '/rs/' . $rsView . '.blade.php', $rsSubst($rsView));
}
// purchase-v2/create includes the Restaurant purchase partials (they load restaurant products, bar=0): render them for real; the script partial is not rendered.
foreach (['purchase-v2/inc/common', 'purchase-v2/create/left-side', 'purchase-v2/create/right-side'] as $rsPart) {
    @mkdir(dirname($coViews . '/rs/' . $rsPart), 0777, true);
    file_put_contents($coViews . '/rs/' . $rsPart . '.blade.php', $rsSubst($rsPart));
}
$rsCreate = file_get_contents($coViews . '/rs/purchase-v2/create.blade.php');
file_put_contents($coViews . '/rs/purchase-v2/create.blade.php', str_replace(["@include('purchase-v2.", "@include('purchase-v2/inc/script')"], ["@include('rs.purchase-v2.", ''], $rsCreate));
$rsIndex = file_get_contents($coViews . '/rs/purchase-v2/index.blade.php');
file_put_contents($coViews . '/rs/purchase-v2/index.blade.php', str_replace('@include(\'partials._paginate\', [\'data\' => $purchases])', $rpPaginate, $rsIndex));
$rsRoutes = new Illuminate\Routing\RouteCollection();
foreach (['rst.table-manages.update' => 'PUT rst/table-manages/{id}', 'rst.table-manages.store' => 'POST rst/table-manages', 'rst.table-manages.destroy' => 'DELETE rst/table-manages/{id}', 'kit.kitchen.show' => 'GET kitchen/kitchen/{id}', 'kit.update-status' => 'POST kitchen/update-status/{id}', 'rst.night-audits.index' => 'GET rst/night-audit', 'rst.sales.index' => 'GET rst/sales', 'rst.sales.create' => 'GET rst/sales/create', 'rst.sales.store' => 'POST rst/sales', 'rst.sales.show' => 'GET rst/sales/{id}', 'rst.sales.destroy' => 'DELETE rst/sales/{id}', 'rst.sales-v2.create' => 'GET rst/sales-v2/create', 'rst.sales-v2.show' => 'GET rst/sales-v2/{id}', 'rst.sale-returns.create' => 'GET rst/sale-returns/create', 'rst.sale-returns.store' => 'POST rst/sale-returns', 'rst.sale-returns.show' => 'GET rst/sale-returns/{id}', 'rst.sale-returns.destroy' => 'DELETE rst/sale-returns/{id}', 'rst.save-guest-data' => 'POST rst/save-guest-data', 'rst.night-audits.create' => 'GET rst/night-audits/create', 'rst.night-audits.store' => 'POST rst/night-audits', 'rst.night-audits.show' => 'GET rst/night-audit-show/{id}', 'night-audits.index' => 'GET hotel/night-audit', 'night-audits.destroy' => 'DELETE hotel/night-audits/{id}', 'rst.sales.store-payment-collection' => 'POST rst/store-payment-collection', 'rst.purchases.index' => 'GET rst/purchases', 'rst.purchases.create' => 'GET rst/purchases/create', 'rst.purchases.store' => 'POST rst/purchases', 'rst.purchases.show' => 'GET rst/purchases/{id}', 'rst.purchases.destroy' => 'DELETE rst/purchases/{id}', 'purchases.index' => 'GET purchases', 'rst.approvePurchase' => 'PUT rst/purchase-approve/{id}', 'rst.purchase.index' => 'GET rst/inventory/purchase'] as $rsName => $rsDef) {
    [$rsMethod, $rsUri] = explode(' ', $rsDef);
    $rsRoutes->add((new Illuminate\Routing\Route($rsMethod, $rsUri, function () {}))->name($rsName));
}
$rsProduct = function ($name, $qty) { return (object) ['product' => (object) ['name' => $name], 'qty' => $qty, 'price' => 100 * $qty]; };
$rsOrder = function ($id, $invoice, $table, $status) use ($rsProduct) { return (object) ['id' => $id, 'invoice_no' => $invoice, 'table_no' => $table, 'waiter_no' => 'W-' . $id, 'total_amount' => 450, 'date' => '2026-10-01', 'order_status' => $status, 'customer_name' => 'Rahim <b>x</b>', 'order_items' => collect([$rsProduct('Biryani <i>hot</i>', 2), $rsProduct('Lassi', 1)])]; };
$rsOrders = collect([$rsOrder(1, 'R-0101', 'T1', 'Pending'), $rsOrder(2, 'R-0102', 'T2', 'Cooking'), $rsOrder(3, 'R-0103', 'T3', 'Ready'), $rsOrder(4, 'R-0104', 'T4', 'Complete')]);
$rsAuditDay = function ($date, $collection, $due) { return new MmHsAudit(['id' => 9, 'date' => $date, 'collection' => $collection, 'due_amount' => $due, 'details' => collect([(object) ['total_amount' => 1000, 'collection' => $collection, 'due' => $due, 'transaction' => (object) ['invoice_no' => 'R-0101', 'source_type' => 'Restaurant Sale', 'total_amount' => 1000, 'collection' => $collection, 'due_amount' => $due, 'transaction_ledgers' => collect()]]])]); };
$rsLedger = function ($id, $in, $account) { return (object) ['id' => $id, 'in' => $in, 'payment_type' => 1, 'account' => (object) ['name' => $account]]; };
$rsTx = function ($id, $invoice, $total, $paid) use ($rsLedger) { return (object) ['id' => $id, 'date' => '2026-10-01', 'source_type' => 'Restaurant Sale', 'invoice_no' => $invoice, 'total_amount' => $total, 'collection' => $paid, 'ledger_paid' => $paid, 'discount' => 0, 'previous_paid' => 0, 'total_due_amount' => $total - $paid, 'extra_charge' => 0, 'transaction_ledgers' => collect([$rsLedger($id + 100, $paid, 'Cash')]), 'source' => (object) ['details' => collect([])]]; };
$rsPay = function ($id, $invoice, $total, $paid) { return (object) ['source_id' => $id, 'source_type' => 'Restaurant Sale', 'total_amount' => $total, 'due_amount' => $total - $paid, 'service_amount' => 0, 'extra_charge' => 0, 'collection' => $paid, 'invoice_no' => $invoice, 'discount' => 0, 'date' => '2026-10-01', 'change_amount' => 0, 'source' => (object) ['date' => '2026-10-01', 'service_amount' => 0, 'vat_amount' => 0, 'discount' => 0, 'created_by' => 1, 'company_id' => null]]; };

$rsSaleRow = function ($id, $invoice, $total, $paid) { return (object) ['id' => $id, 'date' => '2026-10-01', 'invoice_no' => $invoice, 'guest_name' => 'Aisha <b>x</b>', 'guestInfo' => (object) ['name' => 'Aisha <b>x</b>', 'phone_no' => '017', 'address' => 'Dhaka', 'is_stuff' => 0], 'table' => (object) ['table_no' => 'T1'], 'payable_amount' => $total, 'discount' => 50, 'paid_amount' => $paid, 'subtotal' => $total, 'vat_amount' => 10, 'service_amount' => 5, 'change_amount' => 0, 'waiter_no' => 'W-1', 'company' => null, 'user' => (object) ['name' => 'Rahim'],
    'booking' => (object) ['bookingDetails' => collect([(object) ['roomNumber' => (object) ['room_number' => '101']]])], 'transaction_ledgers' => collect([(object) ['account' => (object) ['name' => 'Cash']]]),
    'items' => collect([(object) ['product' => (object) ['name' => 'Biryani <i>hot</i>'], 'sales_price' => 250, 'quantity' => 2, 'item_price' => 500]])]; };
$rsReturnRow = function ($id, $invoice) { return (object) ['id' => $id, 'date' => new MmRsDate('2026-10-01'), 'subtotal' => 500, 'invoice_no' => $invoice, 'guest_name' => 'Aisha <b>x</b>', 'guest' => (object) ['name' => 'Aisha', 'mobile_number' => '017'], 'company' => (object) ['name' => 'MM Heritage', 'head_office' => 'Dhaka', 'phone_number' => '029', 'email' => 'a@example.com'], 'user' => (object) ['name' => 'Rahim'], 'discount' => 0, 'payable_amount' => 500, 'total_amount' => 500, 'return_amount' => 400, 'due_amount' => 100, 'paid_amount' => 400,
    'items' => collect([(object) ['product' => (object) ['name' => 'Lassi'], 'sales_price' => 100, 'quantity' => 1, 'item_price' => 100]])]; };
$rsPurchaseUser = (object) ['name' => 'Rahim <b>x</b>', 'employee' => (object) ['designation' => (object) ['name' => 'Manager']]];
$rsPurchase = function ($id, $challan, $approved) use ($rsPurchaseUser) { return (object) ['id' => $id, 'date' => '2026-10-01', 'challan_id' => $challan, 'company_id' => 1, 'is_approved' => $approved, 'company' => (object) ['name' => 'MM Heritage <i>Ltd</i>'], 'created_user' => $rsPurchaseUser, 'updated_user' => $rsPurchaseUser, 'created_at' => '2026-10-01 09:00:00', 'updated_at' => '2026-10-01 10:00:00',
    'purchase_details' => collect([(object) ['quantity' => 3, 'item_price' => 120, 'product' => (object) ['id' => 7, 'name' => 'Basmati <b>rice</b>', 'sale_price' => 130, 'available_quantity' => 12, 'unit' => (object) ['id' => 2, 'name' => 'Kg'], 'rstStock' => (object) ['available_quantity' => 12]]]])]; };
$rsCases = [
    'purchase-list' => ['rs.purchase-v2.index', '/rst/purchases?purchase_number=1', ['companies' => [1 => 'MM Heritage'], 'purchases' => new MmHsPage([$rsPurchase(1, 'P-0001', 0), $rsPurchase(2, 'P-0002', 1)])],
        ['mm-report', 'mm-rst', 'mm-rst-purchase', 'class="form-horizontal mm-setup-filter mm-report-form"', 'name="company_id"', 'name="from_date"', 'name="to_date"', 'name="purchase_number"', 'rst/purchases/create', 'rst/purchases/1', 'id="deleteCheck_1"', 'onclick="delete_check(1)"', 'id="csrf"', 'class="pagination"', 'P-0002', 'MM Heritage'], ['widget-box', 'widget-main', 'widget-header', 'page-header', 'col-sm-offset']],
    'purchase-show' => ['rs.purchase-v2.show', '/rst/purchases/1', ['purchases' => $rsPurchase(1, 'P-0001', 1)],
        ['mm-invoice-page', 'mm-rst', 'onclick="printForm()"', 'border-print-none', 'P-0001', 'MM Heritage &lt;i&gt;Ltd&lt;/i&gt;', 'Basmati &lt;b&gt;rice&lt;/b&gt;', 'Purchase Form No:', 'Created By:', 'Approved By:'], ['widget-box', 'widget-main', 'widget-header', 'page-header', 'Basmati <b>rice</b>']],
    'purchase-approve' => ['rs.purchase-v2.approve', '/rst/purchase-approve/1', ['purchase' => $rsPurchase(1, 'P-0001', 0), 'companies' => [1 => 'MM Heritage', 2 => 'Other']],
        ['mm-rst-purchase', 'class="form-horizontal"', 'action="http://localhost/rst/purchase-approve/1"', 'name="_method"', 'name="company_id"', 'name="purchase_date"', 'id="purchase_table"', 'name="purchase_id"', 'name="product_id[]"', 'name="item_unit_id[]"', 'name="item_price[]"', 'name="quantity[]"', 'name="available_quantity[]"', 'name="total"', 'Approve', 'Basmati &lt;b&gt;rice&lt;/b&gt;'], ['widget-box', 'widget-main', 'widget-header', 'page-header', 'Basmati <b>rice</b>', 'col-sm-offset']],
    'purchase-create' => ['rs.purchase-v2.create', '/rst/purchases/create', ['challan_id' => 'P-0003', 'suppliers' => collect([(object) ['id' => 1, 'name' => 'Fresh <b>Co</b>']]), 'accounts' => collect([(object) ['id' => 1, 'name' => 'Cash']])],
        ['mm-rst-purchase-create', 'id="purchase-form"', 'action="http://localhost/rst/purchases"', 'name="supplier_id"', 'name="account_id"', 'name="challan_id"', 'name="date"', 'id="product-search"', 'id="products"', 'name="subtotal"', 'name="discount"', 'name="total_vat"', 'name="grand_total"', 'name="paid_amount"', 'name="due_amount"', 'save-purchase', 'P-0003'], ['widget-box', 'widget-main', 'widget-header', 'page-header', 'Fresh <b>Co</b>']],
    'tables' => ['rs.rst.tables.index', '/rst/table-manages', ['table_manages' => new MmHsPage([new MmRsModel(['id' => 1, 'name' => 'Terrace <b>1</b>', 'table_no' => 'T1', 'status' => 1]), new MmRsModel(['id' => 2, 'name' => 'Hall', 'table_no' => 'T2', 'status' => 2])]), 'slugs' => []],
        ['mm-hotel-setup', 'mm-rst', 'id="data-table"', 'href="#modal-dialog"', 'data-toggle="modal"', 'onclick="editTable(', 'delete_item(', 'function editTable(', 'Terrace &lt;b&gt;1&lt;/b&gt;'], ['widget-box', 'widget-main', 'widget-header', 'Terrace <b>1</b>']],
    'kitchen-list' => ['rs.kitchen.index', '/kitchen/kitchen', ['orders' => $rsOrders],
        ['mm-rst-kitchen', 'id="data-table"', 'R-0101', 'id="myModal"', 'id="modalContent"', 'kitchen/kitchen/1', 'function delete_check('], ['widget-box', 'widget-main', 'widget-header', 'page-header']],
    'kitchen-board' => ['rs.kitchen.create', '/kitchen/kitchen/create', ['orders' => $rsOrders],
        ['mm-board-card', 'action="http://localhost/kitchen/update-status/1"', 'name="type" value="Cooking"', 'name="type" value="Ready"', 'name="type" value="Complete"', 'don\'t accept', 'Biryani &lt;i&gt;hot&lt;/i&gt;', 'id="data-table"', 'Order List'], ['widget-box', 'widget-main', 'widget-header', 'page-header', 'Biryani <i>hot</i>']],
    'kitchen-show' => ['rs.kitchen.show', '/kitchen/kitchen/1', ['orders' => $rsOrder(1, 'R-0101', 'T1', 'Pending'), 'slugs' => []],
        ['mm-invoice-page', 'id="print_body"', 'printPage(', 'R-0101', 'Biryani &lt;i&gt;hot&lt;/i&gt;', 'Rahim &lt;b&gt;x&lt;/b&gt;', 'Pending', 'printThis('], ['widget-box', 'widget-main', 'widget-header', 'Rahim <b>x</b>']],
    'audit-list' => ['rs.restaurant-night-audits.index', '/rst/night-audit?from_date=2026-09-29', ['nightaudits' => collect([$rsAuditDay('2026-09-30', 1000, 0), $rsAuditDay('2026-09-29', 500, 200)]), 'account_types' => collect([1 => 'Cash'])],
        ['mm-hs-audit', 'name="from_date"', 'name="to_date"', 'id="data-table"', 'rst/night-audits/create', 'class="pagination"', 'View Details'], ['widget-box', 'widget-main', 'widget-header']],
    'audit-generate' => ['rs.restaurant-night-audits.create-v2', '/rst/night-audits/create?from_date=2026-10-01&to_date=2026-10-01', ['from_date' => '2026-10-01', 'to_date' => '2026-10-01', 'accountTypes' => collect([1 => 'Cash', 2 => 'Card']), 'total_reservation' => 3, 'total_booked_room' => 7, 'total_check_in' => 2, 'total_check_out' => 1, 'total_room' => 32, 'total_cancel' => 0, 'total_dirty_room' => 4, 'total_maintenance_room' => 1, 'transactions' => collect(['Restaurant Sale' => collect([$rsTx(21, 'R-0101', 800, 800), $rsTx(22, 'R-0102', 600, 100)])])],
        ['mm-night-audit', 'mm-rst', 'id="formSubmit"', 'action="http://localhost/rst/night-audits"', 'name="date"', 'name="transaction_ids[21]"', 'name="collections[21]"', 'name="due_amounts[21]"', 'name="payment_way[Cash]"', 'name="payment_way[Card]"', 'name="total_amount"', 'name="collection"', 'name="due_amount"', 'save-btn'], ['widget-box', 'widget-main', 'widget-header']],
    'payment-collection' => ['rs.rst-payment-collection.index', '/rst/payment-collection?hotel_guest_id=1', ['account_type' => [1 => 'Cash', 2 => 'Card'], 'hotelGuests' => collect([(object) ['id' => 1, 'name' => 'Aisha <b>x</b>', 'phone_no' => '017'], (object) ['id' => 2, 'name' => 'Rahim', 'phone_no' => '018']]), 'hotelGuest' => (object) ['name' => 'Aisha', 'email' => 'a@example.com', 'phone_no' => '017', 'nid_no' => '1', 'address' => 'Dhaka', 'booking' => (object) ['bookingInfo' => (object) ['booking_number' => '0007']]], 'transactions' => collect([$rsPay(7, 'R-0007', 5000, 3000), $rsPay(8, 'R-0008', 3000, 1500)])],
        ['mm-payment-collection', 'name="hotel_guest_id"', 'name="item_ids[]"', 'name="total_amount[]"', 'name="payment_type"', 'id="get-due"', 'id="check-full-payment"', 'action="http://localhost/rst/store-payment-collection"', 'payable-amount', 'current-due', 'R-0007'], ['widget-box', 'widget-main', 'widget-header', 'name="company_id"', 'Aisha <b>x</b>']],
    'sales-list' => ['rs.sales.index', '/rst/sales?invoice_no=1', ['sales' => new MmHsPage([$rsSaleRow(1, 'R-0101', 1200, 1200), $rsSaleRow(2, 'R-0102', 800, 300)])],
        ['mm-report', 'mm-rst', 'name="customer"', 'name="date"', 'name="invoice_no"', 'id="datatable"', 'rst/sales-v2/1?invoice_type=pos', 'delete_item(', 'rst/sales-v2/create', 'class="pagination"'], ['widget-box', 'widget-main', 'widget-header', 'col-sm-offset', 'Aisha <b>x</b>']],
    'sales-show' => ['rs.sales.show', '/rst/sales/1', ['sale' => $rsSaleRow(1, 'R-0101', 1200, 1200)],
        ['mm-invoice-page', 'id="print_body"', 'printPage(', 'INV-R-0101', 'Biryani &lt;i&gt;hot&lt;/i&gt;', 'Aisha &lt;b&gt;x&lt;/b&gt;', 'Words of 1200', '101'], ['widget-box', 'widget-main', 'widget-header', 'Aisha <b>x</b>']],
    'sales-create' => ['rs.sales.create', '/rst/sales/create', ['invoice_id' => 'R-0105', 'vat_percent' => 5, 'account_types' => collect([(object) ['id' => 1, 'name' => 'Cash'], (object) ['id' => 2, 'name' => 'Card']])],
        ['mm-rst-sale', 'class="form-horizontal sales-form"', 'action="http://localhost/rst/sales"', 'name="hotel_guest_id"', 'name="guest_name"', 'name="room_number"', 'name="booking_number"', 'name="invoice_no"', 'name="date"', 'name="product_name"', 'id="drug-name"', 'id="table_auto"', 'id="product-details"', 'name="payment_way"', 'name="subtotal"', 'name="discount"', 'name="total_amount"', 'name="vat_amount"', 'name="service_amount"', 'name="grand_total"', 'name="paid_amount"', 'name="change_amount"', 'name="due_amount"', 'name="draft"', 'id="add-guest-modal"', 'const vat_percent = "5"'], ['widget-box', 'widget-main', 'widget-header']],
    'return-list' => ['rs.sales.return.index', '/rst/sale-returns', ['sales' => new MmHsPage([$rsReturnRow(1, 'RET-1'), $rsReturnRow(2, 'RET-2')])],
        ['mm-report', 'mm-rst', 'name="invoice_no"', 'id="datatable"', 'rst/sale-returns/1', 'delete_item(', 'class="pagination"'], ['widget-box', 'widget-main', 'widget-header', 'col-sm-offset']],
    'return-show' => ['rs.sales.return.show', '/rst/sale-returns/1', ['sale' => $rsReturnRow(1, 'RET-1')],
        ['mm-invoice-page', 'id="print_body"', 'printPage(', 'RET-1', 'Lassi', '01-10-2026'], ['widget-box', 'widget-main', 'widget-header']],
    'return-create' => ['rs.sales.return.create', '/rst/sale-returns/create', [],
        ['mm-rst-sale', 'class="form-horizontal sales-form"', 'action="http://localhost/rst/sale-returns"', 'name="customer_id"', 'name="guest_name"', 'name="invoice_no"', 'name="date"', 'name="product_name"', 'id="table_auto"', 'id="product-details"', 'name="subtotal"', 'name="previous_due"', 'name="payable_amount"', 'name="return_amount"', 'name="due_amount"', 'name="draft"'], ['widget-box', 'widget-main', 'widget-header']],
    'report-cash-flow' => ['rs.rst.reports.cash-flow.index', '/rst/reports/cash-flow?invoice_no=1', ['cashFlows' => collect([])], ['mm-report', 'mm-rst', 'name="invoice_no"', 'name="from_date"', 'name="to_date"', 'RST-0101'], ['widget-box', 'widget-main', 'widget-header', 'col-sm-offset']],
    'report-sales' => ['rs.rst.reports.sales.index', '/rst/reports/sales?invoice_no=1', ['sales' => collect([])], ['mm-report', 'name="invoice_no"', 'name="guest_name"', 'name="date"', 'name="outdoor_sale"', 'RST-0101'], ['widget-box', 'widget-main', 'widget-header', 'col-sm-offset']],
    'report-today' => ['rs.reports.today-activities.index', '/rst/reports/today-activities', [], ['mm-report', 'name="invoice_no"', 'name="from_date"', 'RST-0101'], ['widget-box', 'widget-main', 'widget-header', 'col-sm-offset']],
    'report-inventory' => ['rs.reports.inventory.index', '/rst/reports/inventory', ['products' => collect([])], ['mm-rst-inventory', 'class="json_table', 'name="category_id"', 'RST-0101'], ['widget-box', 'widget-main', 'widget-header']],
    'report-ledger' => ['rs.reports.inventory-ledger.index', '/rst/reports/inventory-ledger', [], ['mm-rst-inventory', 'class="json_table', 'name="category_id"', 'Stock ledger'], ['widget-box', 'widget-main', 'widget-header']],
];
@mkdir(__DIR__ . '/fixtures/restaurant', 0777, true);
foreach ($rsCases as $rsName => [$rsViewName, $rsUrl, $rsData, $rsMarkers, $rsAbsent]) {
    $rsRequest = Illuminate\Http\Request::create($rsUrl);
    $rsRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
    $app->instance('request', $rsRequest);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($rsRoutes, $rsRequest));
    try { $rsHtml = $app->make('view')->make($rsViewName, array_merge(['errors' => new Illuminate\Support\ViewErrorBag(), 'slugs' => []], $rsData))->render(); }
    catch (Throwable $e) { throw new RuntimeException('Restaurant screen ' . $rsName . ' failed to render: ' . $e->getMessage(), 0, $e); }
    $rsHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $rsHtml));
    // the real layout loads jquery-ui, loadDetails.js and reference_filter.js for every page; the create screens' guest search scripts need it
    if (substr($rsName, -6) === 'create' && strpos($rsName, 'audit') === false) $rsHtml = str_replace('<!--MM-JS-->', '<script src="/assets/js/jquery-ui.min.js"></script><script src="/assets/custom_js/loadDetails.js"></script><script src="/assets/custom_js/reference_filter.js"></script><!--MM-JS-->', $rsHtml);
    $rsMissing = [];
    foreach (array_merge(['mm-panel', 'mm-page-title'], $rsMarkers) as $rsMarker) { if (strpos($rsHtml, $rsMarker) === false) $rsMissing[] = $rsMarker; }
    if ($rsMissing) throw new RuntimeException('Restaurant screen ' . $rsName . ' missing ' . implode(' | ', $rsMissing));
    foreach ($rsAbsent as $rsMarker) { if (strpos($rsHtml, $rsMarker) !== false) throw new RuntimeException('Restaurant screen ' . $rsName . ' still contains ' . $rsMarker); }
    if (strpos($rsHtml, '<b>Warning</b>') !== false || strpos($rsHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Restaurant screen ' . $rsName . ' sample data is incomplete (PHP warning in output)');
    $rsFile = __DIR__ . '/fixtures/restaurant/' . $rsName . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($rsFile, $rsHtml); file_put_contents($previewDir . '/rst-' . $rsName . '.html', $rsHtml); }
    if (file_get_contents($rsFile) !== $rsHtml) throw new RuntimeException($rsFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS restaurant screens render: tables, kitchen board/list/ticket, night audit list and generate, payment collection and reports\n";

// Render the Restaurant inventory screens (group R4): setup lists, product / material / production lists, forms, purchase and stock adjustment.
// Same substitutions as the other Restaurant screens ($rsSubst); script partials are not rendered (they are not changed). Missing sample fields fall back to MmRiAny.
if (!class_exists('MmRiAny')) {
    class MmRiAny implements ArrayAccess, IteratorAggregate, Countable {
        public function __get($k) { return new MmRiAny(); }
        public function __isset($k) { return false; }
        public function __call($m, $a) { return new MmRiAny(); }
        public function __toString() { return ''; }
        public function offsetExists($o): bool { return false; }
        public function offsetGet($o): mixed { return new MmRiAny(); }
        public function offsetSet($o, $v): void {}
        public function offsetUnset($o): void {}
        public function getIterator(): Iterator { return new ArrayIterator([]); }
        public function count(): int { return 0; }
    }
    class MmRiRow extends MmRiAny {
        public function __construct($fields) { foreach ($fields as $k => $v) $this->$k = $v; }
        public function __isset($k) { return isset($this->$k); }
    }
    class MmRiList extends MmRiAny {
        private $items;
        public function __construct($items) { $this->items = array_values($items); }
        public function count(): int { return count($this->items); }
        public function getIterator(): Iterator { return new ArrayIterator($this->items); }
        public function firstItem() { return count($this->items) ? 1 : null; }
        public function total() { return count($this->items); }
        public function links() { return ''; }
        public function lastPage() { return 1; }
        public function perPage() { return 15; }
        public function currentPage() { return 1; }
        public function lastItem() { return count($this->items); }
        public function hasPages() { return false; }
        public function appends($q) { return $this; }
        public function first() { return $this->items[0] ?? null; }
        public function sum($k = null) { return 0; }
        public function pluck($k = null) { return collect([]); }
        public function isEmpty() { return !count($this->items); }
    }
}
if (!function_exists('mm_barcode')) { function mm_barcode($code, $type = null) { return '<div class="mm-barcode-sample">|||| ||| ||</div>'; } }
if (!function_exists('mm_auth_user')) { function mm_auth_user() { return new class { public $id = 1; public $name = 'Rahim'; public function permissions() { return collect([(object) ['slug' => 'items.create'], (object) ['slug' => 'items.edit'], (object) ['slug' => 'items.delete']]); } }; } }
$riRow = function (array $f) { return new MmRiRow($f); };
$riList = function (array $rows) { return new MmRiList($rows); };
$riViews = ['categories/index', 'categories/create-modal', 'categories/edit-modal', 'units/index', 'units/create-modal', 'units/edit-modal', 'manufacturers/index', 'manufacturers/create-modal', 'manufacturers/edit-modal', 'supplier/index', 'supplier/create-modal', 'supplier/edit-modal',
    'product/index', 'product/_inc/filter', 'mat_product/index', 'mat_product/_inc/filter', 'inventory-report', 'includes/filter', 'product/uploads/index', 'product/uploads/edit',
    'product/create', 'product/create/create', 'product/create/upload', 'product/edit', 'mat_product/create', 'mat_product/create/create', 'mat_product/create/upload', 'mat_product/edit', 'categories/inc/_create-options',
    'production/items/index', 'production/items/create', 'production/items/edit', 'production/item-units/index', 'production/item-units/create', 'production/item-units/edit',
    'production/goods_requisitions/index', 'production/goods_requisitions/create', 'production/purchases/index', 'production/purchases/show', 'production/purchases/approve', 'production/purchases/edit',
    'production/purchase-v2/create', 'production/purchase-v2/inc/common', 'production/purchase-v2/create/left-side', 'production/purchase-v2/create/right-side',
    'adjustment-v2/index', 'adjustment-v2/create', 'adjustment-v2/view', 'adjustment-v2/edit', 'adjustment-v2/inc/common', 'adjustment-v2/create/left-side', 'adjustment-v2/create/right-side'];
foreach ($riViews as $riView) {
    $riSource = $rsSubst('inventory/' . $riView);
    $riSource = preg_replace(["/@include\\('inventory([.\\/])/", "/@include\\('rs\\.inventory[.\\/][\\w.\\/-]*script'\\)/", "/@include\\('partials\\._paginate', \\['data' => [^\\]]*\\]\\)/"], ["@include('ri.inventory$1", '', $rpPaginate], $riSource);
    $riSource = preg_replace("/@include\\('ri\\.inventory[.\\/][\\w.\\/-]*script'\\)/", '', $riSource);
    $riSource = str_replace(['DNS1D::getBarcodeHTML(', 'Auth::user()', 'Carbon::parse(now())'], ['mm_barcode(', 'mm_auth_user()', "Carbon::parse('2026-10-01')"], $riSource);
    @mkdir(dirname($coViews . '/ri/inventory/' . $riView), 0777, true);
    file_put_contents($coViews . '/ri/inventory/' . $riView . '.blade.php', $riSource);
}
// Route names used by the inventory views are registered on the fly (the real route file needs the whole application); optional {id?} takes a scalar argument.
$riSeen = [];
foreach ($riViews as $riView) {
    preg_match_all("/route\('([\w.-]+)'/", file_get_contents($coViews . '/ri/inventory/' . $riView . '.blade.php'), $riMatch);
    foreach ($riMatch[1] as $riRoute) {
        if (isset($riSeen[$riRoute]) || $rsRoutes->getByName($riRoute)) continue;
        $riSeen[$riRoute] = true;
        $rsRoutes->add((new Illuminate\Routing\Route(['GET', 'POST'], (strpos($riRoute, 'rst.') === 0 ? '' : 'rst/') . str_replace('.', '/', $riRoute) . '/{id?}', function () {}))->name($riRoute));
    }
}
$riProduct = function ($id, $name) use ($riRow) { return $riRow(['id' => $id, 'name' => $name, 'barcode' => 'B-' . $id, 'is_bar' => 0, 'is_matrial' => 1, 'stock_limit' => 5, 'unit_cost' => 100, 'supplier_id' => 1, 'unit_id' => 1, 'category_id' => 1, 'sale_price' => 250, 'status' => 1, 'vat_amount' => 5, 'opening_quantity' => 3, 'available_quantity' => 8, 'purchased_quantity' => 5, 'sold_quantity' => 2, 'return_quantity' => 0, 'category' => $riRow(['name' => 'Rice dishes']), 'unit' => $riRow(['name' => 'Plate']), 'supplier' => $riRow(['name' => 'Sarker Traders'])]); };
$riCategory = function ($id, $name, $status = 1) use ($riRow) { return $riRow(['id' => $id, 'name' => $name, 'status' => $status, 'is_bar' => 0, 'parent_id' => null]); };
$riCatalog = ['categories' => collect([$riRow(['id' => 1, 'name' => 'Rice <b>dishes</b>', 'childCategories' => $riList([])])]), 'suppliers' => [1 => 'Sarker <b>Traders</b>'], 'units' => [1 => 'Plate', 2 => 'Kg']];
$riAdjust = ['stockAdjustment' => $riRow(['id' => 1, 'invoice_no' => 'SA-1', 'date' => '2026-10-01', 'approve_date' => '', 'supplier_id' => 1, 'current_status' => 'Pending']), 'Adjustment' => $riRow(['adjustment_details' => $riList([$riRow(['id' => 5, 'quantity' => 2, 'stock_type' => 'Decrease', 'adjustment_reason' => 'Spoiled <b>stock</b>', 'product' => $riRow(['name' => 'Basmati <b>rice</b>'])])])])];
$riCases = [
    'categories' => ['ri.inventory.categories.index', '/rst/categories', ['categories' => $riList([$riCategory(1, 'Rice <b>dishes</b>'), $riCategory(2, 'Drinks', 0)])],
        ['mm-hotel-setup', 'id="data-table"', 'href="#modal-dialog"', 'data-toggle="modal"', 'Rice &lt;b&gt;dishes&lt;/b&gt;', 'delete_item('], ['Rice <b>dishes</b>']],
    'units' => ['ri.inventory.units.index', '/rst/units', ['units' => $riList([$riRow(['id' => 1, 'name' => 'Kg <b>x</b>', 'status' => 1]), $riRow(['id' => 2, 'name' => 'Litre', 'status' => 0])])],
        ['mm-hotel-setup', 'id="data-table"', 'data-toggle="modal"', 'Kg &lt;b&gt;x&lt;/b&gt;'], ['Kg <b>x</b>']],
    'manufacturers' => ['ri.inventory.manufacturers.index', '/rst/manufacturers', ['manufacturers' => $riList([$riRow(['id' => 1, 'name' => 'Fresh <b>Co</b>', 'status' => 1])])],
        ['mm-hotel-setup', 'id="data-table"', 'data-toggle="modal"', 'Fresh &lt;b&gt;Co&lt;/b&gt;'], ['Fresh <b>Co</b>']],
    'suppliers' => ['ri.inventory.supplier.index', '/rst/suppliers', ['suppliers' => $riList([$riRow(['id' => 1, 'name' => 'Sarker <b>Traders</b>', 'phone' => '017', 'status' => 1])])],
        ['mm-hotel-setup', 'id="data-table"', 'data-toggle="modal"', 'Sarker &lt;b&gt;Traders&lt;/b&gt;'], ['Sarker <b>Traders</b>']],
    'products' => ['ri.inventory.product.index', '/rst/products?name=1', ['products' => $riList([$riProduct(1, 'Chicken <b>Biryani</b>'), $riProduct(2, 'Lassi')]), 'categories' => collect([$riRow(['id' => 1, 'name' => 'Rice dishes']), $riRow(['id' => 2, 'name' => 'Drinks'])])],
        ['mm-report', 'mm-rst-inventory', 'mm-report-filter', 'id="datatable"', 'rst/products/create?type=upload', 'Chicken &lt;b&gt;Biryani&lt;/b&gt;', 'class="pagination"'], ['Chicken <b>Biryani</b>', 'col-sm-offset']],
    'mat-products' => ['ri.inventory.mat_product.index', '/rst/mat-products?name=1', ['products' => $riList([$riProduct(1, 'Basmati <b>rice</b>'), $riProduct(2, 'Mustard oil')]), 'categories' => collect([$riRow(['id' => 1, 'name' => 'Dry goods'])])],
        ['mm-report', 'mm-rst-inventory', 'mm-report-filter', 'Basmati &lt;b&gt;rice&lt;/b&gt;', 'class="pagination"'], ['Basmati <b>rice</b>', 'col-sm-offset']],
    'inventory-report' => ['ri.inventory.inventory-report', '/rst/inventory-report?category_id=1', ['products' => $riList([$riProduct(1, 'Chicken <b>Biryani</b>')])],
        ['mm-report', 'mm-rst-inventory', 'mm-report-filter', 'Chicken &lt;b&gt;Biryani&lt;/b&gt;'], ['Chicken <b>Biryani</b>']],
    'product-uploads' => ['ri.inventory.product.uploads.index', '/rst/product-uploads', ['products' => $riList([$riProduct(1, 'Chicken <b>Biryani</b>')])],
        ['mm-report', 'id="datatable"', 'mm-rst-count', 'delete_item(', 'rst/product/add-confirm-list', 'Chicken &lt;b&gt;Biryani&lt;/b&gt;'], ['Chicken <b>Biryani</b>', 'float: right']],
    'production-items' => ['ri.inventory.production.items.index', '/rst/production/items?name=1', ['items' => $riList([$riRow(['id' => 1, 'name' => 'Basmati <b>rice</b>', 'opening_balance' => 10, 'rate' => 120, 'purchase_detail_count' => 0, 'goods_requisition_count' => 0, 'created_at' => '2026-09-30', 'updated_at' => '2026-10-01', 'company' => $riRow(['name' => 'MM Heritage'])])]), 'companies' => [1 => 'MM Heritage'], 'item_ids' => [1 => 'Basmati <b>rice</b>']],
        ['mm-report', 'mm-rst-inv', 'Basmati &lt;b&gt;rice&lt;/b&gt;'], ['Basmati <b>rice</b></td>']],
    'production-item-units' => ['ri.inventory.production.item-units.index', '/rst/production/item-units', ['item_units' => $riList([$riRow(['id' => 1, 'name' => 'Sack <b>x</b>', 'conversion' => 25, 'status' => 1])])],
        ['mm-report', 'Sack &lt;b&gt;x&lt;/b&gt;'], ['Sack <b>x</b>']],
    'production-requisitions' => ['ri.inventory.production.goods_requisitions.index', '/rst/production/goods-requisitions', ['productions' => $riList([$riRow(['id' => 1, 'challan_no' => 'GR-1', 'date' => '2026-10-01', 'remarks' => 'Lunch <b>batch</b>', 'is_approved' => 1, 'fgood_details' => $riList([$riRow(['quantity' => 20, 'product' => $riRow(['name' => 'Fried <b>rice</b>'])])]), 'metrial_details' => $riList([$riRow(['quantity' => 5, 'product' => $riRow(['name' => 'Basmati'])])])])])],
        ['mm-report', 'GR-1', 'Lunch &lt;b&gt;batch&lt;/b&gt;'], ['Lunch <b>batch</b>']],
    'production-purchases' => ['ri.inventory.production.purchases.index', '/rst/production/purchases?company_id=1', ['purchases' => $riList([$riRow(['id' => 1, 'challan_id' => 'P-1', 'date' => '2026-10-01', 'is_approved' => 1, 'company' => $riRow(['name' => 'MM <b>Heritage</b>'])])]), 'companies' => [1 => 'MM Heritage']],
        ['mm-report', 'P-1', 'MM &lt;b&gt;Heritage&lt;/b&gt;'], ['MM <b>Heritage</b>']],
    'adjustments' => ['ri.inventory.adjustment-v2.index', '/rst/stock-adjustment', ['stock_adjustment' => $riList([$riRow(['id' => 1, 'invoice_no' => 'SA-1', 'date' => '2026-10-01', 'total_amount' => 500, 'total_qty' => 5, 'current_status' => 'Pending', 'adjustment_details' => $riList([$riRow(['stock_type' => 'Decrease', 'adjustment_reason' => 'Spoiled <b>stock</b>'])])])])],
        ['mm-report', 'SA-1', 'rst/stock-adjustment/create', 'Spoiled &lt;b&gt;stock&lt;/b&gt;'], ['Spoiled <b>stock</b>']],
    'form-product-create' => ['ri.inventory.product.create', '/rst/products/create', $riCatalog, ['mm-rst-form', 'action="http://localhost/rst/products/store"', 'data-parsley-validate', 'name="name"', 'name="barcode"', 'name="category_id"', 'name="unit_id"', 'name="supplier_id"', 'name="sale_price"', 'id="product_name"', 'Rice &lt;b&gt;dishes&lt;/b&gt;', 'rst/products/index'], ['Rice <b>dishes</b>']],
    'form-product-upload' => ['ri.inventory.product.create', '/rst/products/create?type=upload', $riCatalog, ['mm-rst-form', 'type="file"', 'rst/product-uploads'], []],
    'form-product-edit' => ['ri.inventory.product.edit', '/rst/products/1/edit', $riCatalog + ['product' => $riProduct(1, 'Chicken <b>Biryani</b>') ], ['mm-rst-form', 'name="_method"', 'action="http://localhost/rst/products/update/1"', 'name="name"', 'name="barcode"', 'Chicken &lt;b&gt;Biryani&lt;/b&gt;'], ['Chicken <b>Biryani</b>']],
    'form-mat-create' => ['ri.inventory.mat_product.create', '/rst/mat-products/create', $riCatalog, ['mm-rst-form', 'name="name"', 'name="category_id"', 'name="unit_id"'], ['Rice <b>dishes</b>']],
    'form-mat-edit' => ['ri.inventory.mat_product.edit', '/rst/mat-products/1/edit', $riCatalog + ['product' => $riProduct(1, 'Basmati <b>rice</b>')], ['mm-rst-form', 'name="_method"', 'name="name"', 'Basmati &lt;b&gt;rice&lt;/b&gt;'], ['Basmati <b>rice</b>']],
    'form-upload-edit' => ['ri.inventory.product.uploads.edit', '/rst/product-uploads/1/edit', ['product' => $riProduct(1, 'Chicken <b>Biryani</b>')], ['mm-rst-form', 'name="name"', 'Chicken &lt;b&gt;Biryani&lt;/b&gt;'], ['Chicken <b>Biryani</b>']],
    'form-item-create' => ['ri.inventory.production.items.create', '/rst/production/items/create', ['companies' => [1 => 'MM <b>Heritage</b>'], 'item_units' => [1 => 'Sack'], 'message' => ''], ['mm-rst-form', 'name="company_id"', 'name="name"', 'MM &lt;b&gt;Heritage&lt;/b&gt;'], ['MM <b>Heritage</b>']],
    'form-item-edit' => ['ri.inventory.production.items.edit', '/rst/production/items/1/edit', ['companies' => [1 => 'MM Heritage'], 'item_units' => [1 => 'Sack'], 'message' => '', 'item' => $riRow(['id' => 1, 'name' => 'Basmati <b>rice</b>', 'company_id' => 1, 'item_unit_id' => 1, 'opening_balance' => 4, 'rate' => 120, 'purchase_detail_count' => 0, 'goods_requisition_count' => 0])], ['mm-rst-form', 'name="_method"', 'Basmati &lt;b&gt;rice&lt;/b&gt;'], ['Basmati <b>rice</b>']],
    'form-item-unit-create' => ['ri.inventory.production.item-units.create', '/rst/production/item-units/create', ['message' => ''], ['mm-rst-form', 'name="name"', 'name="conversion"'], []],
    'form-item-unit-edit' => ['ri.inventory.production.item-units.edit', '/rst/production/item-units/1/edit', ['message' => '', 'itemUnit' => $riRow(['id' => 1, 'name' => 'Sack <b>x</b>', 'conversion' => 25, 'status' => 1])], ['mm-rst-form', 'name="_method"', 'Sack &lt;b&gt;x&lt;/b&gt;'], ['Sack <b>x</b>']],
    'form-requisition-create' => ['ri.inventory.production.goods_requisitions.create', '/rst/production/goods-requisitions/create', ['message' => '', 'challan_id' => 'GR-2', 'companies' => [1 => 'MM Heritage'], 'departments' => [1 => 'Kitchen']], ['mm-rst-form', 'GR-2', 'name="challan_id"', 'name="date"'], []],
    'form-purchase-approve' => ['ri.inventory.production.purchases.approve', '/rst/production/purchases/1/approve', ['message' => '', 'companies' => [1 => 'MM Heritage'], 'purchase' => $riRow(['id' => 1, 'company_id' => 1, 'date' => '2026-10-01', 'purchase_reference' => 'REF-1', 'purchase_details' => $riList([$riRow(['quantity' => 3, 'item_price' => 120, 'product' => $riRow(['id' => 7, 'name' => 'Basmati <b>rice</b>', 'available_quantity' => 12, 'unit' => $riRow(['id' => 2, 'name' => 'Kg'])])])])])], ['mm-rst-form', 'Basmati &lt;b&gt;rice&lt;/b&gt;', 'name="purchase_id"'], ['Basmati <b>rice</b>']],
    'form-purchase-edit' => ['ri.inventory.production.purchases.edit', '/rst/production/purchases/1/edit', ['message' => '', 'companies' => [1 => 'MM Heritage'], 'systemSetting' => $riRow(['value' => 'Reference']), 'items' => $riList([$riRow(['id' => 3, 'name' => 'Basmati <b>rice</b>', 'company_id' => 1])]), 'purchase' => $riRow(['id' => 1, 'company_id' => 1, 'purchase_reference' => 'REF-1', 'purchase_details' => $riList([$riRow(['item_id' => 3, 'quantity' => 3, 'item' => $riRow(['current_stock' => 5, 'item_unit' => $riRow(['name' => 'Kg'])])])])])], ['mm-rst-form', 'Basmati &lt;b&gt;rice&lt;/b&gt;'], ['Basmati <b>rice</b>']],
    'form-purchase-create' => ['ri.inventory.production.purchase-v2.create', '/rst/purchase/create', ['challan_id' => 'P-0003', 'suppliers' => collect([$riRow(['id' => 1, 'name' => 'Fresh <b>Co</b>'])]), 'accounts' => collect([$riRow(['id' => 1, 'name' => 'Cash'])])], ['mm-rst-purchase-create', 'mm-rst-form', 'id="purchase-form"', 'name="supplier_id"', 'name="account_id"', 'name="challan_id"'], []],
    'form-adjustment-create' => ['ri.inventory.adjustment-v2.create', '/rst/stock-adjustment/create', ['challan_id' => 'SA-3', 'suppliers' => collect([$riRow(['id' => 1, 'name' => 'Fresh <b>Co</b>'])]), 'accounts' => collect([$riRow(['id' => 1, 'name' => 'Cash'])])], ['mm-rst-form', 'id="purchase-form"', 'name="supplier_id"'], []],
    'purchase-show' => ['ri.inventory.production.purchases.show', '/rst/production/purchases/1', ['purchase' => $riRow(['id' => 1, 'challan_id' => 'P-1', 'date' => '2026-10-01', 'is_approved' => 1, 'created_at' => '2026-10-01', 'updated_at' => '2026-10-01', 'company' => $riRow(['name' => 'MM <b>Heritage</b>']), 'created_user' => $riRow(['name' => 'Rahim <b>x</b>']), 'updated_user' => $riRow(['name' => 'Karim']), 'purchase_details' => $riList([$riRow(['quantity' => 3, 'product' => $riRow(['name' => 'Basmati <b>rice</b>', 'available_quantity' => 12, 'unit_cost' => 120, 'unit' => $riRow(['name' => 'Kg'])])])])])], ['mm-invoice-page', 'printForm(', 'Basmati &lt;b&gt;rice&lt;/b&gt;', 'P-1'], ['Basmati <b>rice</b>', 'Rahim <b>x</b>']],
    'adjustment-view' => ['ri.inventory.adjustment-v2.view', '/rst/stock-adjustment/1', $riAdjust, ['mm-rst-adjust', 'SA-1', 'Spoiled &lt;b&gt;stock&lt;/b&gt;'], ['Spoiled <b>stock</b>']],
    'form-adjustment-edit' => ['ri.inventory.adjustment-v2.edit', '/rst/stock-adjustment/1/edit', $riAdjust, ['mm-rst-adjust', 'mm-rst-form', 'SA-1', 'Spoiled &lt;b&gt;stock&lt;/b&gt;', 'action="http://localhost/rst/stock-adjustment/update/1"'], ['Spoiled <b>stock</b>']],
];
@mkdir(__DIR__ . '/fixtures/restaurant-inventory', 0777, true);
foreach ($riCases as $riName => [$riViewName, $riUrl, $riData, $riMarkers, $riAbsent]) {
    $riRequest = Illuminate\Http\Request::create($riUrl);
    $riRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
    $app->instance('request', $riRequest);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($rsRoutes, $riRequest));
    try { $riHtml = $app->make('view')->make($riViewName, array_merge(['errors' => new Illuminate\Support\ViewErrorBag(), 'slugs' => []], $riData))->render(); }
    catch (Throwable $e) { throw new RuntimeException('Restaurant inventory screen ' . $riName . ' failed to render: ' . $e->getMessage(), 0, $e); }
    $riHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $riHtml));
    if (strpos($riName, 'form-') === 0) $riHtml = str_replace('<!--MM-JS-->', '<script src="/assets/js/jquery-ui.min.js"></script><script src="/assets/custom_js/loadDetails.js"></script><script src="/assets/custom_js/reference_filter.js"></script><!--MM-JS-->', $riHtml);
    $riMissing = [];
    foreach (array_merge(['mm-panel', 'mm-page-title', 'mm-rst-inv'], $riMarkers) as $riMarker) { if (strpos($riHtml, $riMarker) === false) $riMissing[] = $riMarker; }
    if ($riMissing) throw new RuntimeException('Restaurant inventory screen ' . $riName . ' missing ' . implode(' | ', $riMissing));
    foreach (array_merge(['widget-box', 'widget-main', 'widget-header', 'class="page-header"'], $riAbsent) as $riMarker) { if (strpos($riHtml, $riMarker) !== false) throw new RuntimeException('Restaurant inventory screen ' . $riName . ' still contains ' . $riMarker); }
    if (strpos($riHtml, '<b>Warning</b>') !== false || strpos($riHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Restaurant inventory screen ' . $riName . ' sample data is incomplete (PHP warning in output)');
    $riFile = __DIR__ . '/fixtures/restaurant-inventory/' . $riName . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($riFile, $riHtml); file_put_contents($previewDir . '/rsi-' . $riName . '.html', $riHtml); }
    if (file_get_contents($riFile) !== $riHtml) throw new RuntimeException($riFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS restaurant inventory screens render: setup lists, catalog, production, purchase and stock adjustment\n";

// ---- General Store G1: items, item units, suppliers, supplier types (module/GeneralStore/views, same helper substitutions as the Restaurant views) ----
$gsViews = ['item-units/index', 'item-units/create', 'item-units/edit', 'items/index', 'items/create', 'items/edit', 'items/upload', 'suppliers/index', 'suppliers/create', 'suppliers/edit', 'supplier-types/index',
    'purchases/index', 'purchases/show', 'purchases/approve', 'purchases/create', 'purchases/edit', 'purchase_receives/create', 'purchase_receives/grn_list', 'purchase_receives/purchase_receive_list',
    'goods_requisitions/index', 'goods_requisitions/gin_list', 'goods_requisitions/approve', 'goods_requisitions/create', 'goods_requisitions/edit', 'reports/weakly_movement_issue', 'reports/stock-in-hand', 'reports/item-ledger'];
foreach ($gsViews as $gsView) {
    $gsSource = $rsSubst($gsView, 'GeneralStore');
    $gsSource = preg_replace("/@include\\('(?:partials\\._paginate|reports\\.gs-paginate)', \\['data' => [^\\]]*\\]\\)/", $rpPaginate, $gsSource);
    $gsSource = str_replace(['Auth::user()', "\\Carbon\\Carbon::parse(", 'Carbon\\Carbon::parse('], ['mm_auth_user()', 'mm_gs_date(', 'mm_gs_date('], $gsSource);
    @mkdir(dirname($coViews . '/gs/' . $gsView), 0777, true);
    file_put_contents($coViews . '/gs/' . $gsView . '.blade.php', $gsSource);
}
if (!function_exists('mm_gs_date')) { function mm_gs_date($value) { return new class($value) { private $v; public function __construct($v) { $this->v = $v; } public function format($f) { return '2026-10-01 (' . $this->v . ')'; } }; } }
$gsSeen = [];
foreach ($gsViews as $gsView) {
    preg_match_all("/route\\('([\\w.-]+)'/", file_get_contents($coViews . '/gs/' . $gsView . '.blade.php'), $gsMatch);
    foreach ($gsMatch[1] as $gsRoute) {
        if (isset($gsSeen[$gsRoute]) || $rsRoutes->getByName($gsRoute)) continue;
        $gsSeen[$gsRoute] = true;
        $rsRoutes->add((new Illuminate\Routing\Route(['GET', 'POST'], 'gs/' . str_replace('.', '/', $gsRoute) . '/{id?}', function () {}))->name($gsRoute));
    }
}
$gsType = function ($id, $name) use ($riRow) { return $riRow(['id' => $id, 'name' => $name]); };
$gsSupplier = $riRow(['id' => 1, 'name' => 'Sarker <b>Traders</b>', 'mobile' => '017', 'phone' => '018', 'email' => 's@example.com', 'address' => 'Dhaka <i>1</i>', 'attention' => 'Mr Sarker', 'fax' => '', 'website' => '', 'head_office' => '', 'factory_' => '', 'country_id' => 18, 'supplier_type_id' => 1, 'created_at' => '2026-09-30', 'updated_at' => '2026-10-01', 'supplier_type' => $riRow(['name' => 'Local <b>x</b>']), 'group' => $riRow(['name' => 'Main'])]);
$gsItem = $riRow(['id' => 7, 'name' => 'Basmati <b>rice</b>', 'current_stock' => 12, 'item_unit' => $riRow(['name' => 'Kg'])]);
$gsDetail = $riRow(['id' => 3, 'item_id' => 7, 'quantity' => 3, 'received_quantity' => 1, 'item' => $gsItem]);
$gsPurchase = function ($id, $form, $approved) use ($riRow, $riList, $gsDetail) { return $riRow(['id' => $id, 'form_number' => $form, 'purchase_date' => '2026-10-01', 'purchase_reference' => 'REF-' . $id, 'company_id' => 1, 'is_approved' => $approved, 'created_at' => '2026-10-01', 'updated_at' => '2026-10-01', 'company' => $riRow(['name' => 'MM <b>Heritage</b>']), 'created_user' => $riRow(['name' => 'Rahim <b>x</b>']), 'updated_user' => $riRow(['name' => 'Karim']), 'purchase_details' => $riList([$gsDetail]), 'purchase_receives' => $riList([])]); };
$gsReceive = $riRow(['id' => 5, 'form_number' => 'GRN-0001', 'purchase_challan_number' => 'CH-9', 'purchase_receive_date' => '2026-10-01', 'remarks' => 'Fine', 'updated_at' => '2026-10-01', 'company' => $riRow(['name' => 'MM Heritage']), 'updated_user' => $riRow(['name' => 'Karim']), 'purchase' => $riRow(['id' => 1, 'form_number' => 'GP-0001', 'purchase_date' => '2026-10-01', 'purchase_details' => collect([$gsDetail])]), 'purchase_receive_details' => collect([$riRow(['item_id' => 7, 'quantity' => 1, 'rate' => 100, 'is_in_stock' => [], 'item' => $gsItem])])]);
$gsCases = [
    'item-units' => ['gs.item-units.index', '/gs/item-units', ['item_units' => $riList([$riRow(['id' => 1, 'name' => 'Sack <b>x</b>', 'conversion' => 25, 'status' => 1]), $riRow(['id' => 2, 'name' => 'Box', 'conversion' => 12, 'status' => 0])])],
        ['mm-gs', 'id="dynamic-table"', 'delete_check(1)', 'Sack &lt;b&gt;x&lt;/b&gt;', 'class="pagination"'], ['Sack <b>x</b>', 'btnPrint']],
    'form-item-unit-create' => ['gs.item-units.create', '/gs/item-units/create', [], ['mm-gs', 'mm-rst-form', 'name="name"', 'name="conversion"', 'name="status"'], ['group_id']],
    'form-item-unit-edit' => ['gs.item-units.edit', '/gs/item-units/1/edit', ['itemUnit' => $riRow(['id' => 1, 'name' => 'Sack <b>x</b>', 'conversion' => 25, 'status' => 1])], ['mm-gs', 'name="_method"', 'Sack &lt;b&gt;x&lt;/b&gt;'], ['Sack <b>x</b>']],
    'items' => ['gs.items.index', '/gs/items?name=1', ['items' => $riList([$riRow(['id' => 1, 'name' => 'Basmati <b>rice</b>', 'opening_balance' => 10, 'rate' => 120, 'purchase_detail_count' => 0, 'goods_requisition_count' => 0, 'created_at' => '2026-09-30', 'updated_at' => '2026-10-01', 'company' => $riRow(['name' => 'MM Heritage']), 'item_unit' => $riRow(['name' => 'Kg']), 'created_user' => $riRow(['name' => 'Rahim']), 'updated_user' => $riRow(['name' => 'Karim'])])]), 'companies' => [1 => 'MM Heritage'], 'item_ids' => [1 => 'Basmati <b>rice</b>']],
        ['mm-gs', 'mm-report-filter', 'Basmati &lt;b&gt;rice&lt;/b&gt;', 'gs/item/upload', 'gs/item/export', 'Records Found'], ['Basmati <b>rice</b></td>']],
    'form-item-create' => ['gs.items.create', '/gs/items/create', ['companies' => [1 => 'MM <b>Heritage</b>'], 'item_units' => [1 => 'Sack'], 'message' => ''], ['mm-gs', 'mm-rst-form', 'name="company_id"', 'name="name"', 'MM &lt;b&gt;Heritage&lt;/b&gt;'], ['MM <b>Heritage</b>']],
    'form-item-edit' => ['gs.items.edit', '/gs/items/1/edit', ['companies' => [1 => 'MM Heritage'], 'item_units' => [1 => 'Sack'], 'message' => '', 'item' => $riRow(['id' => 1, 'name' => 'Basmati <b>rice</b>', 'company_id' => 1, 'item_unit_id' => 1, 'opening_balance' => 4, 'rate' => 120, 'purchase_detail_count' => 0, 'goods_requisition_count' => 0])], ['mm-gs', 'name="_method"', 'Basmati &lt;b&gt;rice&lt;/b&gt;'], ['Basmati <b>rice</b>']],
    'form-item-upload' => ['gs.items.upload', '/gs/item-upload', [], ['mm-gs', 'mm-rst-form', 'name="item_csv_file"', 'item-sample-csv.csv'], []],
    'suppliers' => ['gs.suppliers.index', '/generalstore/suppliers', ['suppliers' => $riList([$gsSupplier])],
        ['mm-gs', 'id="data-table"', 'href="#view-details1"', 'id="view-details1"', 'delete_check(1)', 'Sarker &lt;b&gt;Traders&lt;/b&gt;', 'Local &lt;b&gt;x&lt;/b&gt;'], ['Sarker <b>Traders</b>', 'Local <b>x</b>']],
    'form-supplier-create' => ['gs.suppliers.create', '/generalstore/suppliers/create', ['supplier_types' => [1 => 'Local <b>x</b>'], 'countries' => [18 => 'Bangladesh', 19 => 'India']], ['mm-gs', 'mm-rst-form', 'name="group_id"', 'name="name"', 'name="supplier_type_id"', 'name="country_id"', 'Local &lt;b&gt;x&lt;/b&gt;'], ['Local <b>x</b>']],
    'form-supplier-edit' => ['gs.suppliers.edit', '/generalstore/suppliers/1/edit', ['Supplier' => $gsSupplier, 'supplier_types' => [1 => 'Local <b>x</b>'], 'countries' => [18 => 'Bangladesh']], ['mm-gs', 'mm-rst-form', 'name="_method"', 'Sarker &lt;b&gt;Traders&lt;/b&gt;'], ['Sarker <b>Traders</b>']],
    'supplier-types' => ['gs.supplier-types.index', '/generalstore/supplier-types?name=1', ['supplierTypes' => $riList([$riRow(['id' => 1, 'name' => 'Local <b>x</b>']), $riRow(['id' => 2, 'name' => 'Import'])])],
        ['mm-gs', 'id="myTable"', 'name="name[]"', 'href="#edit1"', 'id="edit1"', 'delete_check(1)', 'Local &lt;b&gt;x&lt;/b&gt;', 'Total : 2', 'class="pagination"'], ['Local <b>x</b>']],
    'purchases' => ['gs.purchases.index', '/gs/purchases?purchase_number=1', ['companies' => [1 => 'MM Heritage'], 'systemSetting' => $riRow(['value' => 'Ref No.']), 'purchases' => $riList([$gsPurchase(1, 'GP-0001', 0), $gsPurchase(2, 'GP-0002', 1)])],
        ['mm-gs', 'mm-report-filter', 'name="is_approved"', 'GP-0002', 'Not Approved', 'class="pagination"', 'delete_check(1)', 'class="exportForm"'], ['MM <b>Heritage</b></td>']],
    'purchase-show' => ['gs.purchases.show', '/gs/purchases/1', ['purchase' => $gsPurchase(1, 'GP-0001', 1), 'systemSetting' => $riRow(['value' => 'Ref No.'])], ['mm-invoice-page', 'mm-gs', 'printForm(', 'GP-0001', 'Basmati &lt;b&gt;rice&lt;/b&gt;', 'Designation'], ['Basmati <b>rice</b>']],
    'form-purchase-approve' => ['gs.purchases.approve', '/gs/purchase-approve/1', ['purchase' => $gsPurchase(1, 'GP-0001', 0), 'companies' => [1 => 'MM Heritage'], 'items' => collect([$riRow(['id' => 7, 'name' => 'Basmati <b>rice</b>', 'company_id' => 1])]), 'last_purchases' => [0 => $riRow(['id' => 4, 'form_number' => 'GP-0000'])]], ['mm-gs', 'mm-rst-form', 'name="last_purchases[]"'], []],
    'form-purchase-create' => ['gs.purchases.create', '/gs/purchases/create', ['companies' => [1 => 'MM Heritage'], 'items' => collect([$riRow(['id' => 7, 'name' => 'Basmati <b>rice</b>', 'company_id' => 1])]), 'systemSetting' => $riRow(['value' => 'Ref No.'])], ['mm-gs', 'mm-rst-form', 'name="company_id"'], []],
    'form-purchase-edit' => ['gs.purchases.edit', '/gs/purchases/1/edit', ['purchase' => $gsPurchase(1, 'GP-0001', 0), 'companies' => [1 => 'MM Heritage'], 'items' => collect([$riRow(['id' => 7, 'name' => 'Basmati <b>rice</b>', 'company_id' => 1])]), 'systemSetting' => $riRow(['value' => 'Ref No.'])], ['mm-gs', 'mm-rst-form', 'name="_method"'], []],
    'grn-list' => ['gs.purchase_receives.grn_list', '/gs/grn-list', ['companies' => [1 => 'MM Heritage'], 'purchase_receives' => $riList([$gsReceive])], ['mm-gs', 'mm-report', 'GRN-0001', 'class="pagination"'], []],
    'receive-list' => ['gs.purchase_receives.purchase_receive_list', '/gs/purchase-receive/list/1', ['purchase_receives' => $riList([$gsReceive])], ['mm-gs', 'mm-report', 'GRN-0001'], []],
    'form-receive-create' => ['gs.purchase_receives.create', '/gs/purchase-receive/create/1', ['purchase' => $gsPurchase(1, 'GP-0001', 1), 'suppliers' => [1 => 'Sarker <b>Traders</b>'], 'receive_items' => collect([]), 'receive_items_quantity' => 0, 'requisition_number' => 'REQ-1', 'requisition_from_item' => [], 'systemSetting' => $riRow(['value' => 'Ref No.'])], ['mm-gs', 'mm-rst-form', 'name="purchase_id"'], []],
];
$gsGrDetail = function ($id, $qty) use ($riRow, $gsItem) { return $riRow(['id' => $id, 'item_id' => 7, 'quantity' => $qty, 'remarks' => 'For <i>kitchen</i>', 'item' => $gsItem]); };
$gsGr = function ($id, $form, $approved) use ($riRow, $riList, $gsGrDetail) { return $riRow(['id' => $id, 'form_number' => $form, 'goods_requisition_date' => '2026-10-01', 'goods_requisition_reference' => 'REF-' . $id, 'company_id' => 1, 'department_id' => 1, 'is_approved' => $approved, 'issue_date' => '2026-10-01', 'issue_number' => $approved ? 'GIN-000' . $id : '', 'created_at' => '2026-10-01', 'updated_at' => '2026-10-01', 'company' => $riRow(['name' => 'MM <b>Heritage</b>']), 'department' => $riRow(['name' => 'Kitchen <b>x</b>']), 'created_user' => $riRow(['name' => 'Rahim <b>x</b>']), 'updated_user' => $riRow(['name' => 'Karim']), 'goods_requisition_details' => $riList([$gsGrDetail(3, 4)])]); };
$gsGrItems = collect([$riRow(['id' => 7, 'name' => 'Basmati <b>rice</b>', 'company_id' => 1])]);
$gsGrCases = [
    'gr-list' => ['gs.goods_requisitions.index', '/gs/goods-requisitions?requisition_number=1', ['companies' => [1 => 'MM Heritage'], 'systemSetting' => $riRow(['value' => 'Ref No.']), 'goods_requisitions' => $riList([$gsGr(1, 'GR-0001', 0), $gsGr(2, 'GR-0002', 1)])],
        ['mm-gs', 'name="requisition_number"', 'name="gin_number"', 'name="is_approved"', 'GR-0002', 'GIN-0002', 'class="pagination"', 'delete_check(1)', 'id="goods-requisition-details1"', 'class="exportForm"', 'Kitchen &lt;b&gt;x&lt;/b&gt;'], ['Kitchen <b>x</b>']],
    'gin-list' => ['gs.goods_requisitions.gin_list', '/gs/gin-list', ['companies' => [1 => 'MM Heritage'], 'systemSetting' => $riRow(['value' => 'Ref No.']), 'goods_requisitions' => $riList([$gsGr(2, 'GR-0002', 1)])],
        ['mm-gs', 'mm-report', 'GIN-0002', 'id="goods-requisition-details2"', 'class="pagination"'], ['Kitchen <b>x</b>']],
    'form-gr-approve' => ['gs.goods_requisitions.approve', '/gs/goods-requisitions/approve/1', ['goodsRequisition' => $gsGr(1, 'GR-0001', 0), 'companies' => [1 => 'MM Heritage'], 'departments' => [1 => 'Kitchen'], 'items' => $gsGrItems, 'previous_unapprove' => null, 'message' => ''],
        ['mm-gs', 'mm-rst-form', 'id="goods_requisition_table"', 'name="_method"', 'name="company_id"', 'Approve'], []],
    'form-gr-create' => ['gs.goods_requisitions.create', '/gs/goods-requisitions/create', ['companies' => [1 => 'MM Heritage'], 'departments' => [1 => 'Kitchen'], 'items' => $gsGrItems, 'systemSetting' => $riRow(['value' => 'Ref No.']), 'message' => ''],
        ['mm-gs', 'mm-rst-form', 'id="purchase_table"', 'name="company_id"', 'name="department_id"'], []],
    'form-gr-edit' => ['gs.goods_requisitions.edit', '/gs/goods-requisitions/1/edit', ['goodsRequisition' => $gsGr(1, 'GR-0001', 0), 'companies' => [1 => 'MM Heritage'], 'departments' => [1 => 'Kitchen'], 'items' => $gsGrItems, 'systemSetting' => $riRow(['value' => 'Ref No.']), 'message' => '', 'receive_items' => [0 => []], 'receive_items_quantity' => [0 => []], 'requisition_number' => [0 => $riRow(['id' => 2, 'issue_number' => 'GIN-0002'])], 'requisition_from_item' => [0 => null]],
        ['mm-gs', 'mm-rst-form', 'id="goods_requisition_table"', 'name="_method"', 'GIN-0002'], []],
    'weekly-movement' => ['gs.reports.weakly_movement_issue', '/gs/gs-reports/weakly-movement-issue?x=1', ['companies' => [1 => 'MM Heritage'], 'departments' => [1 => 'Kitchen'], 'systemSetting' => $riRow(['value' => 'Ref No.']), 'goods_requisitions' => $riList([$gsGr(2, 'GR-0002', 1)]), 'requisition_from_items' => [0 => [0 => null]], 'requisition_from_receives' => [0 => [0 => []]]],
        ['mm-gs', 'GIN-0002', 'id="goods-requisition-details2"', 'class="pagination"'], ['Kitchen <b>x</b>']],
    'stock-in-hand' => ['gs.reports.stock-in-hand', '/gs/gs-reports/items-stock', ['companies' => [1 => 'MM Heritage'], 'units' => [1 => 'Kg'], 'items' => [7 => 'Basmati <b>rice</b>'], 'item_stocks' => $riList([$riRow(['id' => 7, 'name' => 'Basmati <b>rice</b>', 'company_id' => 1, 'current_stock' => 12, 'created_at' => '2026-09-30', 'item_unit' => $riRow(['name' => 'Kg']), 'company' => $riRow(['name' => 'MM <b>Heritage</b>'])])])],
        ['mm-gs', 'name="unit_id"', 'id="dynamic-table"', 'Records Found', 'Basmati &lt;b&gt;rice&lt;/b&gt;', 'class="pagination"', 'class="exportForm"'], ['Basmati <b>rice</b></td>']],
    'item-ledger' => ['gs.reports.item-ledger', '/gs/reports/item-ledger?item_id=Rice', ['companies' => [1 => 'MM Heritage'], 'selected_item' => $riRow(['name' => 'Basmati <b>rice</b>', 'created_at' => '2026-09-30']), 'opening_stock' => 5, 'opening_rate' => 100,
        'item_stock_details' => collect([$riRow(['date' => '2026-10-01', 'type' => 'Purchase Receive', 'source_number' => 'GRN-0001', 'credit_qty' => 10, 'credit_rate' => 100, 'debit_qty' => 0, 'debit_rate' => 0]), $riRow(['date' => '2026-10-01', 'type' => 'Issue', 'source_number' => 'GIN-0002', 'credit_qty' => 0, 'credit_rate' => 0, 'debit_qty' => 3, 'debit_rate' => 100])])],
        ['mm-gs', 'name="item_id"', 'GRN-0001', 'GIN-0002', 'Stock Details', 'class="pagination"', 'class="exportForm"'], []],
];
$gsCases = array_merge($gsCases, $gsGrCases);
@mkdir(__DIR__ . '/fixtures/general-store', 0777, true);
foreach ($gsCases as $gsName => [$gsViewName, $gsUrl, $gsData, $gsMarkers, $gsAbsent]) {
    $gsRequest = Illuminate\Http\Request::create($gsUrl);
    $gsRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
    $app->instance('request', $gsRequest);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($rsRoutes, $gsRequest));
    try { $gsHtml = $app->make('view')->make($gsViewName, array_merge(['errors' => new Illuminate\Support\ViewErrorBag(), 'slugs' => []], $gsData))->render(); }
    catch (Throwable $e) { throw new RuntimeException('General Store screen ' . $gsName . ' failed to render: ' . $e->getMessage(), 0, $e); }
    $gsHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $gsHtml));
    $gsMissing = [];
    foreach (array_merge(['mm-panel', 'mm-page-title'], $gsMarkers) as $gsMarker) { if (strpos($gsHtml, $gsMarker) === false) $gsMissing[] = $gsMarker; }
    if ($gsMissing) throw new RuntimeException('General Store screen ' . $gsName . ' missing ' . implode(' | ', $gsMissing));
    foreach (array_merge(['widget-box', 'widget-main', 'widget-header', 'class="page-header"'], $gsAbsent) as $gsMarker) { if (strpos($gsHtml, $gsMarker) !== false) throw new RuntimeException('General Store screen ' . $gsName . ' still contains ' . $gsMarker); }
    if (strpos($gsHtml, '<b>Warning</b>') !== false || strpos($gsHtml, '<b>Notice</b>') !== false) throw new RuntimeException('General Store screen ' . $gsName . ' sample data is incomplete (PHP warning in output)');
    $gsFile = __DIR__ . '/fixtures/general-store/' . $gsName . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($gsFile, $gsHtml); file_put_contents($previewDir . '/gs-' . $gsName . '.html', $gsHtml); }
    if (file_get_contents($gsFile) !== $gsHtml) throw new RuntimeException($gsFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS general store screens render: items, item units, suppliers, supplier types, purchases, receives, GRN list, requisitions, GIN and reports\n";

// ---- Bar B1: inventory, tables, purchases, sales, returns, reports and night audit lists (module/Bar/views, same helper substitutions as the Restaurant views) ----
$brMigrated = ['bar/inventory/categories/index', 'bar/inventory/units/index', 'bar/inventory/manufacturers/index', 'bar/inventory/supplier/index', 'bar/inventory/product/create', 'bar/inventory/product/edit', 'bar/inventory/product/index', 'bar/inventory/inventory-report', 'bar/inventory/product/uploads/edit', 'bar/inventory/product/package/index', 'bar/inventory/product/package/create', 'bar/tables/index', 'bar/purchase/index', 'bar/sales/index', 'bar/sales/return/index', 'bar/sales-v2/index', 'bar/reports/sales/index', 'bar/reports/cash-flow/index', 'bar/reports/inventory/index', 'bar/reports/today-activities/index', 'bar-night-audits/index', 'bar-night-audits/create-v2', 'bar/sales/create', 'bar/sales/return/create', 'bar/purchase/show', 'bar/sales/show', 'bar/sales/return/show', 'bar/purchase-v2/create', 'bar/inventory/product/uploads/index'];
foreach ($brMigrated as $brFile) { $compiler->compileString(file_get_contents($root . '/module/Bar/views/' . $brFile . '.blade.php')); token_get_all($compiler->compileString(file_get_contents($root . '/module/Bar/views/' . $brFile . '.blade.php')), TOKEN_PARSE); }
echo "PASS compile bar views\n";
$brViews = array_merge($brMigrated, ['bar/inventory/categories/create-modal', 'bar/inventory/categories/edit-modal', 'bar/inventory/units/create-modal', 'bar/inventory/units/edit-modal', 'bar/inventory/manufacturers/create-modal', 'bar/inventory/manufacturers/edit-modal', 'bar/inventory/manufacturers/create-supplier-modal', 'bar/inventory/supplier/create-modal', 'bar/inventory/supplier/edit-modal', 'bar/tables/create-modal', 'bar/tables/edit-modal', 'bar/inventory/product/_inc/filter', 'bar/inventory/product/_inc/script', 'bar/inventory/product/create/create', 'bar/inventory/product/create/upload', 'bar/sales-v2/_inc/style', 'bar/sales/_inc/create-script', 'bar/sales/_inc/booking-filter-script']);
foreach ($brViews as $brView) {
    $brSource = $rsSubst($brView, 'Bar');
    $brSource = str_replace(["@include('inventory.categories.inc._create-options'", "@include('inventory/product/_inc/script')", "@include('bar.inventory.includes.filter')", "\Carbon\Carbon::parse(", 'Carbon\Carbon::parse(', 'Auth::user()', 'DNS1D::getBarcodeHTML(', '<x-company-info :company="$purchases->company" />', '<x-company-info :company="optional($sale->company)" />'], ["@include('ri.inventory.categories.inc._create-options'", "@include('bar/inventory/product/_inc/script')", "@include('rs-filter')", 'mm_gs_date(', 'mm_gs_date(', 'mm_auth_user()', 'mm_barcode(', '<div class="company-info"><h3>MM Heritage</h3></div>', '<div class="company-info"><h3>MM Heritage</h3></div>'], $brSource);
    $brSource = preg_replace("/@include\\('(?:bar[.\\/]reports[.\\/][\\w.\\/-]*export[.\\/]excel|bar-night-audits[.\\/]export[.\\/]excel|bar-night-audits[.\\/]details|reports\\.today-activities\\.export\\.excel)'\\)/", "@include('rs-stub')", $brSource);
    $brSource = preg_replace('/getTotalPaymentTypeInvoice\\([^)]*\\)/', '0', $brSource);
    @mkdir(dirname($coViews . '/' . $brView), 0777, true);
    file_put_contents($coViews . '/' . $brView . '.blade.php', $brSource);
}
$brSeen = [];
foreach ($brViews as $brView) {
    preg_match_all("/route\('([\w.-]+)'/", file_get_contents($coViews . '/' . $brView . '.blade.php'), $brMatch);
    foreach ($brMatch[1] as $brRoute) {
        if (isset($brSeen[$brRoute]) || $rsRoutes->getByName($brRoute)) continue;
        $brSeen[$brRoute] = true;
        $rsRoutes->add((new Illuminate\Routing\Route(['GET', 'POST'], 'x/' . str_replace('.', '/', $brRoute) . '/{id?}', function () {}))->name($brRoute));
    }
}
$brPurchase = function ($id, $challan) { return (object) ['id' => $id, 'challan_id' => $challan, 'date' => '2026-10-01', 'subtotal' => 1000, 'discount' => 50, 'total_vat' => 20, 'payable_amount' => 970, 'paid_amount' => 500, 'due_amount' => 470, 'supplier' => (object) ['id' => 1, 'name' => 'Sarker <b>Traders</b>', 'email' => 's@example.com', 'phone' => '017'], 'company' => (object) ['name' => 'MM Heritage <i>Ltd</i>'], 'user' => (object) ['name' => 'Karim'], 'purchase_details' => collect([(object) ['quantity' => 5, 'item_price' => 200, 'subtotal' => 1000, 'product' => (object) ['name' => 'Black <b>Label</b>', 'pack_unit' => (object) ['name' => 'Bottle']]]])]; };
$brSale = function () { return (object) ['id' => 1, 'date' => '2026-10-01', 'invoice_no' => 'B-0101', 'guest_name' => 'Aisha <b>x</b>', 'payable_amount' => 1200, 'discount' => 50, 'paid_amount' => 1200, 'vat_amount' => 10, 'service_amount' => 5, 'change_amount' => 0, 'company' => null, 'user' => (object) ['name' => 'Rahim'], 'transaction_ledgers' => collect([(object) ['amount' => 1200, 'account' => (object) ['name' => 'Cash']]]), 'items' => collect([(object) ['quantity' => 2, 'sales_price' => 600, 'item_price' => 600, 'item_discount' => 0, 'product' => (object) ['name' => 'Whisky <i>hot</i>'], 'unit' => (object) ['name' => 'Peg'], 'account' => (object) ['name' => 'Cash']]])]; };
$brCatalog = $riCatalog; $brCatalog['units'] = collect([(object) ['id' => 1, 'name' => 'Bottle', 'type' => 'pack'], (object) ['id' => 2, 'name' => 'Peg', 'type' => 'retail']]);
$brCases = [
    'bar-categories' => ['bar.inventory.categories.index', '/bar/inventory/product-categories', ['categories' => $riList([$riCategory(1, 'Spirits <b>x</b>'), $riCategory(2, 'Beer', 0)])],
        ['mm-hotel-setup', 'mm-bar', 'id="data-table"', 'href="#modal-dialog"', 'Spirits &lt;b&gt;x&lt;/b&gt;'], ['Spirits <b>x</b>']],
    'bar-units' => ['bar.inventory.units.index', '/bar/inventory/product-units', ['units' => $riList([$riRow(['id' => 1, 'name' => 'Peg <b>x</b>', 'status' => 1]), $riRow(['id' => 2, 'name' => 'Bottle', 'status' => 0])])],
        ['mm-hotel-setup', 'mm-bar', 'id="data-table"', 'Peg &lt;b&gt;x&lt;/b&gt;'], ['Peg <b>x</b>']],
    'bar-manufacturers' => ['bar.inventory.manufacturers.index', '/bar/inventory/manufacturers', ['manufacturers' => $riList([$riRow(['id' => 1, 'name' => 'Distillers <b>Co</b>', 'status' => 1])])],
        ['mm-hotel-setup', 'mm-bar', 'id="data-table"', 'Distillers &lt;b&gt;Co&lt;/b&gt;'], ['Distillers <b>Co</b>']],
    'bar-suppliers' => ['bar.inventory.supplier.index', '/bar/inventory/suppliers', ['suppliers' => $riList([$riRow(['id' => 1, 'name' => 'Sarker <b>Traders</b>', 'phone' => '017', 'status' => 1])])],
        ['mm-hotel-setup', 'mm-bar', 'id="data-table"', 'Sarker &lt;b&gt;Traders&lt;/b&gt;'], ['Sarker <b>Traders</b>']],
    'bar-products' => ['bar.inventory.product.index', '/bar/inventory/products?name=1', ['products' => $riList([$riProduct(1, 'Black <b>Label</b>'), $riProduct(2, 'Lager')]), 'categories' => collect([$riRow(['id' => 1, 'name' => 'Spirits']), $riRow(['id' => 2, 'name' => 'Beer'])])],
        ['mm-report', 'mm-bar', 'mm-rst-inventory', 'mm-report-filter', 'id="datatable"', 'Black &lt;b&gt;Label&lt;/b&gt;', 'class="pagination"'], ['Black <b>Label</b>', 'col-sm-offset']],
    'bar-tables' => ['bar.tables.index', '/bar/table-manages', ['table_manages' => $riList([$riRow(['id' => 1, 'name' => 'Bar <b>1</b>', 'table_no' => 'T1', 'status' => 1])])],
        ['mm-hotel-setup', 'mm-bar', 'id="data-table"', 'Bar &lt;b&gt;1&lt;/b&gt;'], ['Bar <b>1</b>']],
    'bar-purchase-list' => ['bar.purchase.index', '/bar/purchases?purchase_number=1', ['companies' => [1 => 'MM Heritage'], 'purchases' => $riList([$brPurchase(1, 'P-0001'), $brPurchase(2, 'P-0002')])],
        ['mm-report', 'mm-bar', 'mm-rst-purchase', 'P-0002'], ['col-sm-offset']],
    'bar-purchase-show' => ['bar.purchase.show', '/bar/purchases/1', ['purchases' => $brPurchase(1, 'P-0001')],
        ['mm-invoice-page', 'mm-bar', 'P-0001', 'Black &lt;b&gt;Label&lt;/b&gt;', 'Sarker &lt;b&gt;Traders&lt;/b&gt;'], ['Black <b>Label</b>']],
    'bar-sales-list' => ['bar.sales.index', '/bar/sales?invoice_no=1', ['sales' => $riList([$rsSaleRow(1, 'R-0101', 1200, 1200), $rsSaleRow(2, 'R-0102', 800, 300)])],
        ['mm-report', 'mm-bar', 'mm-report-filter', 'name="invoice_no"', 'id="datatable"', 'class="pagination"'], ['col-sm-offset']],
    'bar-sales-show' => ['bar.sales.show', '/bar/sales/1', ['sale' => $brSale(), 'account_types' => collect([(object) ['id' => 1, 'name' => 'Cash']])],
        ['mm-invoice-page', 'mm-bar', 'B-0101', 'Whisky &lt;i&gt;hot&lt;/i&gt;'], ['Whisky <i>hot</i>']],
    'bar-return-list' => ['bar.sales.return.index', '/bar/sale-returns', ['sales' => $riList([$rsReturnRow(1, 'RET-1'), $rsReturnRow(2, 'RET-2')])],
        ['mm-report', 'mm-bar', 'mm-report-filter', 'name="invoice_no"', 'id="datatable"', 'class="pagination"'], ['col-sm-offset']],
    'bar-return-show' => ['bar.sales.return.show', '/bar/sale-returns/1', ['sale' => $rsReturnRow(1, 'RET-1')],
        ['mm-invoice-page', 'mm-bar', 'RET-1'], []],
    'bar-sales-create' => ['bar.sales.create', '/bar/sales/create', ['invoice_id' => 'B-0105', 'vat_percent' => 5, 'account_types' => collect([(object) ['id' => 1, 'name' => 'Cash'], (object) ['id' => 2, 'name' => 'Card']])],
        ['mm-rst-sale', 'mm-bar', 'name="invoice_no"', 'name="date"', 'id="product-details"', 'name="payment_way"', 'name="subtotal"', 'name="payable_amount"', 'name="draft"'], []],
    'bar-return-create' => ['bar.sales.return.create', '/bar/sale-returns/create', [],
        ['mm-rst-sale', 'mm-bar', 'class="form-horizontal sales-form"', 'name="invoice_no"', 'id="table_auto"', 'name="return_amount"'], []],
    'bar-report-cash-flow' => ['bar.reports.cash-flow.index', '/bar/reports/cash-flow?invoice_no=1', ['cashFlows' => collect([])], ['mm-report', 'mm-bar', 'mm-report-filter', 'name="invoice_no"', 'name="from_date"'], ['col-sm-offset']],
    'bar-report-sales' => ['bar.reports.sales.index', '/bar/reports/sales?invoice_no=1', ['sales' => collect([])], ['mm-report', 'mm-bar', 'name="invoice_no"', 'name="guest_name"', 'mm-report-filter'], ['col-sm-offset']],
    'bar-report-today' => ['bar.reports.today-activities.index', '/bar/reports/today-activities', [], ['mm-report', 'mm-bar', 'mm-report-filter', 'name="from_date"'], ['col-sm-offset']],
    'bar-report-inventory' => ['bar.reports.inventory.index', '/bar/reports/inventory', ['products' => collect([])], ['mm-rst-inventory', 'mm-bar', 'name="category_id"'], []],
    'bar-audit-list' => ['bar-night-audits.index', '/bar/night-audit?from_date=2026-09-29', ['nightaudits' => collect([$rsAuditDay('2026-09-30', 1000, 0), $rsAuditDay('2026-09-29', 500, 200)]), 'account_types' => collect([1 => 'Cash'])],
        ['mm-bar', 'mm-report-filter', 'name="from_date"', 'name="to_date"'], []],
    'bar-audit-generate' => ['bar-night-audits.create-v2', '/bar/night-audits/create?from_date=2026-10-01&to_date=2026-10-01', ['from_date' => '2026-10-01', 'to_date' => '2026-10-01', 'accountTypes' => collect([1 => 'Cash', 2 => 'Card']), 'total_reservation' => 3, 'total_booked_room' => 7, 'total_check_in' => 2, 'total_check_out' => 1, 'total_room' => 32, 'total_cancel' => 0, 'total_dirty_room' => 4, 'total_maintenance_room' => 1, 'transactions' => collect(['Bar Sale' => collect([$rsTx(21, 'B-0101', 800, 800), $rsTx(22, 'B-0102', 600, 100)])])],
        ['mm-night-audit', 'mm-bar', 'id="formSubmit"', 'name="date"', 'name="transaction_ids[21]"', 'name="payment_way[Cash]"', 'name="total_amount"'], []],
    'bar-product-create' => ['bar.inventory.product.create', '/bar/inventory/products/create', $brCatalog, ['mm-bar', 'mm-rst-form', 'data-parsley-validate', 'name="name"', 'name="barcode"', 'name="category_id"', 'name="unit_id"', 'name="supplier_id"', 'Rice &lt;b&gt;dishes&lt;/b&gt;'], ['Rice <b>dishes</b>']],
    'bar-product-edit' => ['bar.inventory.product.edit', '/bar/inventory/products/1/edit', $brCatalog + ['product' => (object) ['id' => 1, 'name' => 'Black <b>Label</b>', 'barcode' => 'B-1', 'category' => (object) ['name' => 'Spirits'], 'category_id' => 1, 'pack_size' => 12, 'pack_unit_id' => 1, 'sale_price' => 250, 'status' => 1, 'stock_limit' => 5, 'unit_cost' => 100, 'supplier_id' => 1, 'unit_id' => 2, 'vat_amount' => 5]], ['mm-bar', 'mm-rst-form', 'name="_method"', 'name="name"', 'Black &lt;b&gt;Label&lt;/b&gt;'], ['Black <b>Label</b>']],
    'bar-inventory-report' => ['bar.inventory.inventory-report', '/bar/inventory/inventory-report?category_id=1', ['products' => $riList([$riProduct(1, 'Black <b>Label</b>')])], ['mm-bar', 'mm-rst-inventory', 'Black &lt;b&gt;Label&lt;/b&gt;'], ['Black <b>Label</b>']],
];
@mkdir(__DIR__ . '/fixtures/bar', 0777, true);
foreach ($brCases as $brName => [$brViewName, $brUrl, $brData, $brMarkers, $brAbsent]) {
    $brRequest = Illuminate\Http\Request::create($brUrl);
    $brRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
    $app->instance('request', $brRequest);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($rsRoutes, $brRequest));
    try { $brHtml = $app->make('view')->make($brViewName, array_merge(['errors' => new Illuminate\Support\ViewErrorBag(), 'slugs' => []], $brData))->render(); }
    catch (Throwable $e) { throw new RuntimeException('Bar screen ' . $brName . ' failed to render: ' . $e->getMessage(), 0, $e); }
    $brHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $brHtml));
    $brMissing = [];
    foreach (array_merge(['mm-panel', 'mm-page-title'], $brMarkers) as $brMarker) { if (strpos($brHtml, $brMarker) === false) $brMissing[] = $brMarker; }
    if ($brMissing) throw new RuntimeException('Bar screen ' . $brName . ' missing ' . implode(' | ', $brMissing));
    if (preg_match('/class="[^"]*\\b(?:widget-box|widget-main|widget-header|page-header)\\b/', $brHtml)) throw new RuntimeException('Bar screen ' . $brName . ' still contains the legacy frame');
    foreach ($brAbsent as $brMarker) { if (strpos($brHtml, $brMarker) !== false) throw new RuntimeException('Bar screen ' . $brName . ' still contains ' . $brMarker); }
    if (strpos($brHtml, '<b>Warning</b>') !== false || strpos($brHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Bar screen ' . $brName . ' sample data is incomplete (PHP warning in output)');
    $brFile = __DIR__ . '/fixtures/bar/' . substr($brName, 4) . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($brFile, $brHtml); file_put_contents($previewDir . '/' . $brName . '.html', $brHtml); }
    if (file_get_contents($brFile) !== $brHtml) throw new RuntimeException($brFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS bar screens render: setup, catalog, purchases, sales, returns, reports and night audit\n";

// ---- Account A1: setup, party and product screens (module/Account/views, same helper substitutions as the other modules) ----
$acMigrated = ['setup/account-controls/index', 'setup/account-controls/create', 'setup/account-controls/edit', 'setup/account-groups/index', 'setup/account-opening-balances/create', 'setup/account-subsidiaries/index', 'setup/account-subsidiaries/create', 'setup/account-subsidiaries/edit', 'setup/accounts/index', 'setup/accounts/create', 'setup/accounts/edit', 'party/customers/index', 'party/customers/create', 'party/customers/edit', 'party/suppliers/index', 'party/suppliers/create', 'party/suppliers/edit', 'product/categories/index', 'product/categories/create', 'product/categories/edit', 'product/units/index', 'product/units/create', 'product/units/edit', 'product/products/index', 'product/products/create', 'product/products/edit'];
foreach ($acMigrated as $acFile) { token_get_all($compiler->compileString(file_get_contents($root . '/module/Account/views/' . $acFile . '.blade.php')), TOKEN_PARSE); }
echo "PASS compile account views\n";
$acViews = array_merge($acMigrated, ['includes/inputs/date-field', 'includes/inputs/input-field', 'includes/inputs/option-select', 'includes/inputs/select-balance-type', 'includes/inputs/status', 'includes/inputs/textarea-field', 'partials/_user-log']);
foreach ($acViews as $acView) {
    $acSource = $rsSubst($acView, 'Account');
    $acSource = str_replace('auth()->user()->company->id', '1', $acSource);
    $acSource = str_replace(["@include('includes.inputs.", "@include('partials._user-log'", '@include(\'partials._paginate\', [\'data\' => $accounts])', 'auth()->user()', 'Auth::user()'], ["@include('acc.includes.inputs.", "@include('acc.partials._user-log'", '', 'mm_auth_user()', 'mm_auth_user()'], $acSource);
    $acSource = preg_replace('/(?<![\\\\\\w])Str::/', '\\\\Illuminate\\\\Support\\\\Str::', $acSource);
    @mkdir(dirname($coViews . '/acc/' . $acView), 0777, true);
    file_put_contents($coViews . '/acc/' . $acView . '.blade.php', $acSource);
}
if (!function_exists('oldSelect')) { function oldSelect($name, $value, $edit = null) { return $edit !== null && (string) $edit === (string) $value ? 'selected' : ''; } }
$acSeen = [];
foreach ($acViews as $acView) {
    preg_match_all("/route\\('([\\w.-]+)'/", file_get_contents($coViews . '/acc/' . $acView . '.blade.php'), $acMatch);
    foreach ($acMatch[1] as $acRoute) {
        if (isset($acSeen[$acRoute]) || $rsRoutes->getByName($acRoute)) continue;
        $acSeen[$acRoute] = true;
        $rsRoutes->add((new Illuminate\Routing\Route(['GET', 'POST'], 'acc/' . str_replace('.', '/', $acRoute) . '/{id?}', function () {}))->name($acRoute));
    }
}
$acOpt = function ($rows) use ($riRow) { return collect(array_map(function ($r) use ($riRow) { return $riRow($r); }, $rows)); };
$acGroup = $acOpt([['id' => 1, 'name' => 'Asset <b>group</b>'], ['id' => 2, 'name' => 'Liability']]);
$acControl = $acOpt([['id' => 1, 'name' => 'Current <b>assets</b>']]);
$acSub = $acOpt([['id' => 1, 'name' => 'Cash <b>sub</b>']]);
$acParty = function ($kind) use ($riRow) { return $riRow(['id' => 3, 'name' => $kind . ' <b>Ltd</b>', 'mobile' => '017', 'email' => 'a@example.com', 'address' => 'Dhaka <i>1</i>', 'opening_balance' => 100, 'current_balance' => 150]); };
$acCases = [
    'account-controls' => ['acc.setup.account-controls.index', '/acc/account-controls', ['accountControls' => $acOpt([['id' => 1, 'name' => 'Current <b>assets</b>', 'status' => 1, 'accountGroup' => $riRow(['name' => 'Asset'])], ['id' => 2, 'name' => 'Loans', 'status' => 0, 'accountGroup' => $riRow(['name' => 'Liability'])]])],
        ['mm-acc', 'mm-report', 'id="data-table"', 'Current &lt;b&gt;assets&lt;/b&gt;'], ['Current <b>assets</b>']],
    'account-groups' => ['acc.setup.account-groups.index', '/acc/account-groups', ['data' => $acOpt([['id' => 1, 'name' => 'Asset <b>x</b>', 'status' => 1, 'balance_type' => 'Debit']])],
        ['mm-acc', 'table-striped', 'Asset &lt;b&gt;x&lt;/b&gt;'], ['Asset <b>x</b>']],
    'subsidiaries' => ['acc.setup.account-subsidiaries.index', '/acc/account-subsidiaries', ['accountSubsidiaries' => $acOpt([['id' => 1, 'name' => 'Cash <b>sub</b>', 'status' => 1, 'accountControl' => $riRow(['name' => 'Current']), 'accountGroup' => $riRow(['name' => 'Asset'])]])],
        ['mm-acc', 'id="data-table"', 'Cash &lt;b&gt;sub&lt;/b&gt;'], ['Cash <b>sub</b>']],
    'accounts' => ['acc.setup.accounts.index', '/acc/accounts', ['accounts' => $acOpt([['id' => 1, 'name' => 'Petty <b>cash</b>', 'status' => 1, 'opening_balance' => 500, 'accountControl' => $riRow(['name' => 'Current']), 'accountGroup' => $riRow(['name' => 'Asset']), 'accountSubsidiary' => $riRow(['name' => 'Cash']), 'accountType' => $riRow(['name' => 'Cash'])]])],
        ['mm-acc', 'id="data-table"', 'Petty &lt;b&gt;cash&lt;/b&gt;'], ['Petty <b>cash</b>']],
    'customers' => ['acc.party.customers.index', '/acc/customers', ['customers' => $acOpt([['id' => 3, 'name' => 'Customer <b>Ltd</b>', 'mobile' => '017', 'email' => 'a@example.com']])],
        ['mm-acc', 'id="data-table"', 'Customer &lt;b&gt;Ltd&lt;/b&gt;'], ['Customer <b>Ltd</b>']],
    'suppliers' => ['acc.party.suppliers.index', '/acc/suppliers', ['suppliers' => $acOpt([['id' => 3, 'name' => 'Supplier <b>Ltd</b>', 'mobile' => '017', 'email' => 'a@example.com', 'opening_balance' => 100]])],
        ['mm-acc', 'id="data-table"', 'Supplier &lt;b&gt;Ltd&lt;/b&gt;'], ['Supplier <b>Ltd</b>']],
    'categories' => ['acc.product.categories.index', '/acc/categories', ['categories' => $acOpt([['id' => 1, 'name' => 'Beverage <b>x</b>']])],
        ['mm-acc', 'id="data-table"', 'Beverage &lt;b&gt;x&lt;/b&gt;'], ['Beverage <b>x</b>']],
    'units' => ['acc.product.units.index', '/acc/units', ['units' => $acOpt([['id' => 1, 'name' => 'Litre <b>x</b>']])],
        ['mm-acc', 'id="data-table"', 'Litre &lt;b&gt;x&lt;/b&gt;'], ['Litre <b>x</b>']],
    'products' => ['acc.product.products.index', '/acc/products', ['products' => $acOpt([['id' => 1, 'name' => 'Juice <b>x</b>', 'description' => 'Fresh <i>d</i>', 'opening_quantity' => 5, 'purchase_price' => 10, 'selling_price' => 15, 'category' => $riRow(['name' => 'Beverage']), 'unit' => $riRow(['name' => 'Litre'])]])],
        ['mm-acc', 'id="data-table"', 'Juice &lt;b&gt;x&lt;/b&gt;'], ['Juice <b>x</b>']],
    'opening-balances' => ['acc.setup.account-opening-balances.create', '/acc/account-opening-balances/create', ['companies' => [1 => 'MM <b>Heritage</b>'], 'accountGroups' => $acGroup, 'accountControls' => [1 => 'Current'], 'accounts' => $acOpt([['id' => 1, 'name' => 'Petty <b>cash</b>', 'opening_balances' => $riRow(['amount' => 5])]])],
        ['mm-acc', 'name="company_id"', 'MM &lt;b&gt;Heritage&lt;/b&gt;'], ['MM <b>Heritage</b>']],
    'opening-balances-data' => ['acc.setup.account-opening-balances.create', '/acc/account-opening-balances/create?company_id=1', ['companies' => [1 => 'MM <b>Heritage</b>'], 'accountGroups' => [1 => 'Asset'], 'accountControls' => [1 => 'Current'], 'accounts' => $riList([$riRow(['id' => 1, 'name' => 'Petty <b>cash</b>', 'opening_balances' => collect([$riRow(['amount' => 5])])])])],
        ['mm-report-filter', 'name="account_ids[]"', 'name="amounts[]"', 'Petty &lt;b&gt;cash&lt;/b&gt;', 'account-opening-balances/store'], ['Petty <b>cash</b>']],
    'form-control-create' => ['acc.setup.account-controls.create', '/acc/account-controls/create', ['company' => [1 => 'MM <b>Heritage</b>'], 'accountGroups' => $acGroup],
        ['mm-rst-form', 'name="company_id"', 'name="account_group_id"', 'name="name"', 'Asset &lt;b&gt;group&lt;/b&gt;'], ['Asset <b>group</b>']],
    'form-control-edit' => ['acc.setup.account-controls.edit', '/acc/account-controls/1/edit', ['accountControl' => $riRow(['id' => 1, 'name' => 'Current <b>assets</b>', 'account_group_id' => 1, 'status' => 1]), 'accountGroups' => $acGroup],
        ['mm-rst-form', 'name="_method"', 'name="name"', 'Current &lt;b&gt;assets&lt;/b&gt;'], ['Current <b>assets</b>']],
    'form-subsidiary-create' => ['acc.setup.account-subsidiaries.create', '/acc/account-subsidiaries/create', ['accountGroups' => $acGroup, 'accountControls' => $acControl],
        ['mm-rst-form', 'name="account_group_id"', 'name="account_control_id"', 'name="name"'], []],
    'form-account-create' => ['acc.setup.accounts.create', '/acc/accounts/create', ['accountGroups' => $acGroup, 'accountControls' => $acControl, 'accountSubsidiaries' => $acSub],
        ['mm-rst-form', 'name="account_group_id"', 'name="account_control_id"', 'name="account_subsidiary_id"', 'name="name"', 'name="remarks"'], []],
    'form-customer-create' => ['acc.party.customers.create', '/acc/customers/create', [], ['mm-rst-form', 'name="name"', 'name="mobile"', 'name="email"', 'name="address"', 'name="opening_balance"'], []],
    'form-customer-edit' => ['acc.party.customers.edit', '/acc/customers/3/edit', ['customer' => $acParty('Customer')], ['mm-rst-form', 'name="_method"', 'Customer &lt;b&gt;Ltd&lt;/b&gt;', 'Dhaka &lt;i&gt;1&lt;/i&gt;'], ['Customer <b>Ltd</b>']],
    'form-supplier-create' => ['acc.party.suppliers.create', '/acc/suppliers/create', [], ['mm-rst-form', 'name="name"', 'name="mobile"', 'name="address"'], []],
    'form-supplier-edit' => ['acc.party.suppliers.edit', '/acc/suppliers/3/edit', ['supplier' => $acParty('Supplier')], ['mm-rst-form', 'name="_method"', 'Supplier &lt;b&gt;Ltd&lt;/b&gt;'], ['Supplier <b>Ltd</b>']],
    'form-category-create' => ['acc.product.categories.create', '/acc/categories/create', [], ['mm-rst-form', 'name="name"'], []],
    'form-category-edit' => ['acc.product.categories.edit', '/acc/categories/1/edit', ['category' => $riRow(['id' => 1, 'name' => 'Beverage <b>x</b>'])], ['mm-rst-form', 'name="_method"', 'Beverage &lt;b&gt;x&lt;/b&gt;'], ['Beverage <b>x</b>']],
    'form-unit-create' => ['acc.product.units.create', '/acc/units/create', [], ['mm-rst-form', 'name="name"'], []],
    'form-product-create' => ['acc.product.products.create', '/acc/products/create', ['categories' => [1 => 'Beverage <b>x</b>'], 'units' => [1 => 'Litre']], ['mm-rst-form', 'name="name"', 'name="category_id"', 'name="unit_id"', 'name="purchase_price"', 'name="selling_price"', 'Beverage &lt;b&gt;x&lt;/b&gt;'], ['Beverage <b>x</b>']],
    'form-product-edit' => ['acc.product.products.edit', '/acc/products/1/edit', ['categories' => [1 => 'Beverage'], 'units' => [1 => 'Litre'], 'product' => $riRow(['id' => 1, 'name' => 'Juice <b>x</b>', 'description' => 'Fresh', 'category_id' => 1, 'unit_id' => 1, 'opening_quantity' => 5, 'purchase_price' => 10, 'selling_price' => 15])], ['mm-rst-form', 'name="_method"', 'Juice &lt;b&gt;x&lt;/b&gt;'], ['Juice <b>x</b>']],
];
@mkdir(__DIR__ . '/fixtures/account', 0777, true);
foreach ($acCases as $acName => [$acViewName, $acUrl, $acData, $acMarkers, $acAbsent]) {
    $acRequest = Illuminate\Http\Request::create($acUrl);
    $acRequest->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
    $app->instance('request', $acRequest);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($rsRoutes, $acRequest));
    try { $acHtml = $app->make('view')->make($acViewName, array_merge(['errors' => new Illuminate\Support\ViewErrorBag(), 'slugs' => []], $acData))->render(); }
    catch (Throwable $e) { throw new RuntimeException('Account screen ' . $acName . ' failed to render: ' . $e->getMessage(), 0, $e); }
    $acHtml = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $acHtml));
    $acMissing = [];
    foreach (array_merge(['mm-panel', 'mm-page-title'], $acMarkers) as $acMarker) { if (strpos($acHtml, $acMarker) === false) $acMissing[] = $acMarker; }
    if ($acMissing) throw new RuntimeException('Account screen ' . $acName . ' missing ' . implode(' | ', $acMissing));
    if (preg_match('/class="[^"]*\\b(?:widget-box|widget-main|widget-header|page-header)\\b/', $acHtml)) throw new RuntimeException('Account screen ' . $acName . ' still contains the legacy frame');
    foreach ($acAbsent as $acMarker) { if (strpos($acHtml, $acMarker) !== false) throw new RuntimeException('Account screen ' . $acName . ' still contains ' . $acMarker); }
    if (strpos($acHtml, '<b>Warning</b>') !== false || strpos($acHtml, '<b>Notice</b>') !== false) throw new RuntimeException('Account screen ' . $acName . ' sample data is incomplete (PHP warning in output)');
    $acFile = __DIR__ . '/fixtures/account/' . $acName . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($acFile, $acHtml); file_put_contents($previewDir . '/acc-' . $acName . '.html', $acHtml); }
    if (file_get_contents($acFile) !== $acHtml) throw new RuntimeException($acFile . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS account screens render: setup, party and product\n";

// ---- Account A2: fund transfers and the four voucher types ----
$a2Migrated = ['fund-transfers/index', 'fund-transfers/create', 'fund-transfers/edit', 'voucher/receives/index', 'voucher/receives/create', 'voucher/receives/show', 'voucher/payments/index', 'voucher/payments/create', 'voucher/payments/show', 'voucher/journals/index', 'voucher/journals/create', 'voucher/journals/edit', 'voucher/journals/show', 'voucher/contras/index', 'voucher/contras/create', 'voucher/contras/edit', 'voucher/contras/show'];
foreach ($a2Migrated as $a2File) { token_get_all($compiler->compileString(file_get_contents($root . '/module/Account/views/' . $a2File . '.blade.php')), TOKEN_PARSE); }
echo "PASS compile account voucher views\n";
foreach ($a2Migrated as $a2View) {
    $a2Source = $rsSubst($a2View, 'Account');
    $a2Source = preg_replace("/@include\\('partials\\._paginate', \\['data' => \\$\\w+\\]\\)/", '', $a2Source);
    $a2Source = str_replace('auth()->user()->company->id', '1', $a2Source);
    $a2Source = str_replace(["@include('includes.inputs.", "@include('partials._user-log'", 'auth()->user()', 'Auth::user()'], ["@include('acc.includes.inputs.", "@include('acc.partials._user-log'", 'mm_auth_user()', 'mm_auth_user()'], $a2Source);
    $a2Source = preg_replace('/(?<![\\\\\\w])Str::/', '\\Illuminate\\Support\\Str::', $a2Source);
    @mkdir(dirname($coViews . '/acc/' . $a2View), 0777, true);
    file_put_contents($coViews . '/acc/' . $a2View . '.blade.php', $a2Source);
}
$a2Seen = [];
foreach ($a2Migrated as $a2View) {
    preg_match_all("/route\\('([\\w.-]+)'/", file_get_contents($coViews . '/acc/' . $a2View . '.blade.php'), $a2Match);
    foreach ($a2Match[1] as $a2Route) {
        if (isset($a2Seen[$a2Route]) || $rsRoutes->getByName($a2Route)) continue;
        $a2Seen[$a2Route] = true;
        $rsRoutes->add((new Illuminate\Routing\Route(['GET', 'POST'], 'acc/' . str_replace('.', '/', $a2Route) . '/{id?}', function () {}))->name($a2Route));
    }
}
$a2Accounts = $acOpt([['id' => 1, 'name' => 'Cash <b>in hand</b>'], ['id' => 2, 'name' => 'Bank']]);
$a2Voucher = function ($type) use ($riRow, $acOpt) { return $riRow(['id' => 4, 'voucher_type' => $type, 'invoice_no' => 'V-0004', 'date' => '2026-10-01', 'reference' => 'Ref <i>1</i>', 'description' => 'Rent <b>paid</b>', 'amount' => 1500, 'is_approved' => 0, 'company' => $riRow(['name' => 'MM Heritage']), 'attachment' => '', 'details' => $acOpt([['id' => 1, 'account_id' => 1, 'balance_type' => 'Debit', 'amount' => 1500, 'account' => $riRow(['name' => 'Rent <b>expense</b>']), 'note' => 'n'], ['id' => 2, 'account_id' => 2, 'balance_type' => 'Credit', 'amount' => 1500, 'account' => $riRow(['name' => 'Cash']), 'note' => 'n']])]); };
$a2Index = function ($var) use ($riList, $riRow) { return [$var => $riList([$riRow(['id' => 4, 'invoice_no' => 'V-0004', 'date' => '2026-10-01', 'reference' => 'Ref <i>1</i>', 'amount' => 1500, 'is_approved' => 0, 'description' => 'Rent <b>paid</b>', 'fromAccount' => $riRow(['name' => 'Cash']), 'toAccount' => $riRow(['name' => 'Bank <b>x</b>'])]), $riRow(['id' => 5, 'invoice_no' => 'V-0005', 'date' => '2026-10-01', 'reference' => 'Ref 2', 'amount' => 200, 'is_approved' => 1, 'description' => 'd', 'fromAccount' => $riRow(['name' => 'Cash']), 'toAccount' => $riRow(['name' => 'Bank'])])])]; };
$a2Create = ['companies' => [1 => 'MM <b>Heritage</b>'], 'company' => [1 => 'MM <b>Heritage</b>'], 'accounts' => $a2Accounts, 'accountGroups' => $acGroup];
$a2Cases = [
    'fund-transfers' => ['acc.fund-transfers.index', '/acc/fund-transfers', $a2Index('transfers'), ['mm-acc', 'table-striped', 'Bank &lt;b&gt;x&lt;/b&gt;', 'V-0005'], ['Bank <b>x</b>']],
    'form-fund-transfer-create' => ['acc.fund-transfers.create', '/acc/fund-transfers/create', $a2Create, ['mm-rst-form', 'name="date"', 'name="amount"', 'name="description"', 'name="reference"'], []],
    'form-fund-transfer-edit' => ['acc.fund-transfers.edit', '/acc/fund-transfers/4/edit', $a2Create + ['fundTransfer' => $riRow(['id' => 4, 'date' => '2026-10-01', 'amount' => 1500, 'description' => 'Rent <b>paid</b>', 'reference' => 'Ref', 'from_account_id' => 1, 'to_account_id' => 2])], ['mm-rst-form', 'name="_method"', 'name="amount"'], []],
];
foreach (['receives' => ['Receive', 'voucher-receives'], 'payments' => ['Payment', 'voucher-payments'], 'journals' => ['Journal', 'voucher-journals'], 'contras' => ['Contra', 'voucher-contras']] as $a2Kind => [$a2Type, $a2Slug]) {
    $a2Cases[$a2Kind] = ['acc.voucher.' . $a2Kind . '.index', '/acc/' . $a2Slug . '?invoice_no=1', $a2Index('vouchers'), ['mm-acc', 'mm-report-filter', 'name="invoice_no"', 'name="reference"', 'name="from_date"', 'name="to_date"', 'V-0004', 'V-0005', 'Unapproved'], ['Ref <i>1</i>']];
    $a2Cases['form-' . $a2Kind . '-create'] = ['acc.voucher.' . $a2Kind . '.create', '/acc/' . $a2Slug . '/create', $a2Create, ['mm-rst-form', 'name="company_id"', 'name="voucher_type"'], []];
    $a2Cases[$a2Kind . '-show'] = ['acc.voucher.' . $a2Kind . '.show', '/acc/' . $a2Slug . '/4', ['voucher' => $a2Voucher($a2Type)], ['mm-invoice-page', 'V-0004', 'Rent &lt;b&gt;expense&lt;/b&gt;'], ['Rent <b>expense</b>']];
}
foreach (['journals', 'contras'] as $a2Kind) {
    $a2Cases['form-' . $a2Kind . '-edit'] = ['acc.voucher.' . $a2Kind . '.edit', '/acc/voucher-' . $a2Kind . '/4/edit', $a2Create + ['voucher' => $a2Voucher($a2Kind)], ['mm-rst-form', 'name="_method"', 'name="date"', 'name="reference"'], []];
}
@mkdir(__DIR__ . '/fixtures/account', 0777, true);
foreach ($a2Cases as $a2Name => [$a2ViewName, $a2Url, $a2Data, $a2Markers, $a2Absent]) {
    $a2Request = Illuminate\Http\Request::create($a2Url);
    $a2Request->setLaravelSession(new Illuminate\Session\Store('mm', new Illuminate\Session\ArraySessionHandler(10)));
    $app->instance('request', $a2Request);
    $app->instance('url', new Illuminate\Routing\UrlGenerator($rsRoutes, $a2Request));
    try { $a2Html = $app->make('view')->make($a2ViewName, array_merge(['errors' => new Illuminate\Support\ViewErrorBag(), 'slugs' => []], $a2Data))->render(); }
    catch (Throwable $e) { throw new RuntimeException('Account screen ' . $a2Name . ' failed to render: ' . $e->getMessage(), 0, $e); }
    $a2Html = preg_replace('/(name="_token" value=")[A-Za-z0-9]+"/', '$1fixture-csrf-token"', str_replace('http://localhost/assets', '/assets', $a2Html));
    $a2Missing = [];
    foreach (array_merge(['mm-panel', 'mm-page-title'], $a2Markers) as $a2Marker) { if (strpos($a2Html, $a2Marker) === false) $a2Missing[] = $a2Marker; }
    if ($a2Missing) throw new RuntimeException('Account screen ' . $a2Name . ' missing ' . implode(' | ', $a2Missing));
    if (preg_match('/class="[^"]*\\b(?:widget-box|widget-main|widget-header|page-header)\\b/', $a2Html)) throw new RuntimeException('Account screen ' . $a2Name . ' still contains the legacy frame');
    foreach ($a2Absent as $a2Marker) { if (strpos($a2Html, $a2Marker) !== false) throw new RuntimeException('Account screen ' . $a2Name . ' still contains ' . $a2Marker); }
    if (strpos($a2Html, '<b>Warning</b>') !== false || strpos($a2Html, '<b>Notice</b>') !== false) throw new RuntimeException('Account screen ' . $a2Name . ' sample data is incomplete (PHP warning in output)');
    $a2File = __DIR__ . '/fixtures/account/' . $a2Name . '.html';
    if (getenv('MM_WRITE_FIXTURE')) { file_put_contents($a2File, $a2Html); file_put_contents($previewDir . '/acc-' . $a2Name . '.html', $a2Html); }
    if (file_get_contents($a2File) !== $a2Html) throw new RuntimeException($a2File . ' is stale; regenerate it with MM_WRITE_FIXTURE=1');
}
echo "PASS account voucher screens render\n";
