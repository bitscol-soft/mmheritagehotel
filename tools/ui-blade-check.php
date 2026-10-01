<?php
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
$files=array_merge($files, glob($root . '/module/Hotel/views/booking-purpose/*.blade.php'), [$root . '/module/Hotel/views/booking-purpose/include/filter.blade.php']);
$files=array_merge($files, [$root . '/module/Hotel/views/booking-note/index.blade.php', $root . '/module/Hotel/views/booking-note/edit.blade.php', $root . '/module/Hotel/views/booking-note/include/filter.blade.php']);
$files=array_merge($files, [$root . '/module/Hotel/views/booking/booking_next.blade.php', $root . '/module/Hotel/views/booking/_inc/_booking-next-steps.blade.php', $root . '/module/Hotel/views/booking/view.blade.php', $root . '/module/Hotel/views/night-audits/index.blade.php', $root . '/module/Hotel/views/night-audits/invoice.blade.php', $root . '/module/Hotel/views/night-audits/create-v2.blade.php', $root . '/module/Hotel/views/payment-collection/index.blade.php', $root . '/module/Hotel/views/booking/checkout_invoice.blade.php', $root . '/module/Hotel/views/booking/reservation-invoice.blade.php', $root . '/module/Hotel/views/booking/checkout-invoice-v3.blade.php', $root . '/module/Hotel/views/booking/create.blade.php', $root . '/module/Hotel/views/booking/edit.blade.php', $root . '/module/Hotel/views/booking/_inc/_add-guest-input-info.blade.php', $root . '/module/Hotel/views/booking/_inc/_edit-guest-input-info.blade.php']);
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
    @include('layouts.shell.toolbar')
    @include('layouts.shell.navigation-tools')
@endif
BLADE
);
try {
    foreach ([true, false] as $enabled) {
        $html = $app->make('view')->file($probePath, ['mmShell'=>$enabled, 'fav_icon'=>'/icon.png'])->render();
        foreach (['ui.css?v=', 'shell.css?v=', 'id="mm-menu-filter"', 'id="mm-density-toggle"'] as $marker) {
            if (substr_count($html, $marker) !== ($enabled ? 1 : 0)) throw new RuntimeException('Shell asset/partial gate failed: '.$marker);
        }
    }
    echo "PASS real head/toolbar/navigation renders, one asset link, rollback omits shell\n";
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

// Render the real shell chrome partials (header tools, toolbar, footer, dialogs) with frozen time.
// Only the permission check is substituted in a temp copy; it needs an authenticated user.
Carbon\Carbon::setTestNow(Carbon\Carbon::parse('2026-10-01 09:30:00', 'Asia/Dhaka'));
$app->instance('env', 'staging');
$app['config']->set('ui.timezone', 'Asia/Dhaka');
$app['config']->set('ui.version', '2026.10.1');
$app['config']->set('ui.support_url', 'https://example.test/help?a=1&b=<2>');
$chromeViews = '/tmp/mm-chrome-views';
@mkdir($chromeViews . '/layouts/shell', 0777, true);
foreach (['header-tools', 'toolbar', 'footer', 'overlays'] as $partial) {
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
foreach (['header-tools', 'toolbar', 'footer', 'overlays'] as $partial) {
    $chrome[$partial] = $app->make('view')->make('layouts.shell.' . $partial, ['slugs' => []])->render();
}
$expectChrome = [
    'header-tools' => ['data-mm-palette-open', 'data-mm-theme-toggle', 'data-mm-fullscreen', 'data-mm-shortcuts-open', 'data-mm-action="new-booking"', 'href="http://localhost/hotel/booking/create"'],
    'toolbar' => ['aria-label="Breadcrumb"', 'aria-current="page"', 'Hotel</span>', 'Booking</span>', 'id="mm-density-toggle"', 'Create'],
    'footer' => ['role="contentinfo"', 'Business date', '01 Oct 2026', 'id="mm-clock"', 'data-timezone="Asia/Dhaka"', '09:30:00', 'id="mm-online"', 'mm-env-staging', 'v2026.10.1', 'rel="noopener"', 'id="btn-scroll-up"', 'Help &amp; support', 'a=1&amp;b=&lt;2&gt;'],
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
$stayBooking = (object) ['booking_date' => '2026-09-28', 'check_in_date' => '2026-09-29', 'check_out_date' => '2026-10-03', 'booking_pax' => 2, 'customer_id' => 1, 'purpose' => '', 'reference' => '', 'pickup' => '', 'drop' => '', 'pickup_flight' => '', 'drop_flight' => '', 'emergency_cont_name' => '', 'emergency_cont_phone' => '', 'purpose_id' => null, 'platform_id' => null, 'book_type' => 0, 'company_id' => null, 'status' => 0];
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
