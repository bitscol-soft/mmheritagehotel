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
$files=array_merge($files, [$root . '/module/Hotel/views/house-keeping/index.blade.php']);
$files=array_merge($files, glob($root . '/module/Hotel/views/booking-purpose/*.blade.php'), [$root . '/module/Hotel/views/booking-purpose/include/filter.blade.php']);
$files=array_merge($files, [$root . '/module/Hotel/views/booking-note/index.blade.php', $root . '/module/Hotel/views/booking-note/edit.blade.php', $root . '/module/Hotel/views/booking-note/include/filter.blade.php']);
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
    $source = str_replace(["hasPermission('bookings.create', p_slugs())", "setting('room_wise_pricing_booking')", "fdate(\$date[0], 'Y-m-d')"], ['true', '1', "date('Y-m-d', strtotime(\$date[0]))"], $source);
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
