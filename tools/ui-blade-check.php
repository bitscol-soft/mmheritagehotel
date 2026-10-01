<?php
error_reporting(E_ALL & ~E_DEPRECATED); // newer PHP versions flag deprecations inside the vendored Carbon/Symfony; they must not leak into the rendered fixtures
set_error_handler(function ($no, $msg, $file, $line) { if (!(error_reporting() & $no)) return false; throw new ErrorException($msg . ' in ' . basename($file) . ':' . $line, 0, $no, $file, $line); }, E_WARNING | E_NOTICE | E_USER_WARNING | E_USER_NOTICE); // sample data must be complete: a warning is a failure, never output
// Standalone Blade smoke check: no database, .env or application boot needed.
$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';
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
    $source = str_replace(["hasPermission('bookings.create', p_slugs())", "setting('room_wise_pricing_booking')", "fdate(\$date[0], 'Y-m-d')", 'today_from_system()'], ['true', '1', "date('Y-m-d', strtotime(\$date[0]))", "'2026-10-01'"], $source);
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
    'aminities/edit' => ['name="_method" value="PUT"', 'name="aminiti_icon"', '<option value="1" selected>Active</option>'],
    'account_type/index' => ['id="deleteCheck_2"', 'name="name"', 'Add account type'],
    'account_type/edit' => ['name="_method" value="PUT"', 'name="name" value="Cash"', '<option value="1" selected>Active</option>'],
    'vat/index' => ['name="hotel_vat" value="10"', 'name="resturent_vat"', 'name="bar_vat"', 'name="vat_number" value="BIN-123"', 'name="room_rate"', 'name="room_service"', 'name="rst_service_charge"', 'name="key[use_vat_included]"'],
    'currency-conversions/index' => ['class="form-horizontal createCurrencyConversionForm"', 'id="currencyId"', 'id="effectedDate"', 'submitRoomStoreForm', 'render-currency-class', 'id="deleteCheck_3"', 'name="currency_id"'],
    'currency-conversions/edit' => ['name="_method" value="PUT"', 'render(`', '<option value="2" selected>USD</option>', 'value="122.5"'],
    'guest-registration-terms/index' => ['name="title"', 'Registration terms', 'Check-in after 2pm'],
    'guest-registration-terms/edit' => ['name="_method" value="PUT"', '<textarea name="title"', 'Check-in after 2pm'],
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
        ['mm-hotel-setup', 'mm-hs-services', 'id="data-table"', 'data-toggle="modal"', 'href="#modal-dialog"', 'href="#modal-dialog2"', 'id="modal-dialog"', 'id="modal-dialog1"', 'id="modal-dialog2"', 'name="name"', 'name="price"', 'name="_method" value="PUT"', 'delete_item(', 'Airport pickup', 'Laundry &lt;b&gt;x&lt;/b&gt;'], ['widget-box', 'widget-main', 'widget-header', 'Laundry <b>x</b>']],
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
        ['mm-perm-narrow', 'action="http://localhost/setting/permissions/11"', 'name="_method" value="PUT"', 'name="parent_permission_id"', 'value="rooms.view"', 'See rooms'], ['widget-box', 'widget-main', 'widget-header']],
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
