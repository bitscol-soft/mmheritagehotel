@php
    // Intermediate crumbs are plain text: a URL prefix such as /hotel is not always a page.
    $mmSegments = array_values(array_filter(request()->segments(), 'strlen'));
    $mmHumanize = function ($segment) {
        return is_numeric($segment) ? '#' . $segment : \Illuminate\Support\Str::title(str_replace(['-', '_'], ' ', $segment));
    };
    $mmLast = count($mmSegments) ? end($mmSegments) : 'home';
    $mmTitle = trim(\Illuminate\Support\Facades\View::yieldContent('title')) ?: ($mmLast === 'home' ? 'Dashboard' : $mmHumanize($mmLast));
    $mmCrumbs = [];
    if (count($mmSegments) > 1 || (count($mmSegments) === 1 && $mmSegments[0] !== 'home')) {
        foreach (array_slice($mmSegments, 0, -1) as $segment) {
            $mmCrumbs[] = $mmHumanize($segment);
        }
    }
    try {
        $mmBusinessDate = fdate(today_from_system(), 'd M Y');
    } catch (\Throwable $e) {
        $mmBusinessDate = now()->format('d M Y');
    }
    $mmTimezone = config('ui.timezone', config('app.timezone'));
    $mmEnvironment = app()->environment();
    $mmVersion = config('ui.version');
    $mmSupport = config('ui.support_url');
    $mmGroupName = optional(optional(optional(auth()->user())->company)->group)->name;
@endphp
<footer class="mm-shell-footer hidden-print" role="contentinfo">
    <nav class="mm-crumbs" aria-label="Breadcrumb">
        <ol>
            <li><a href="{{ url('home') }}"><i class="fa fa-home" aria-hidden="true"></i><span>Home</span></a></li>
            @foreach ($mmCrumbs as $crumb)
                <li><span>{{ $crumb }}</span></li>
            @endforeach
            <li><span aria-current="page">{{ $mmTitle }}</span></li>
        </ol>
    </nav>
    <div class="mm-footer-main">
        <span>&copy; {{ date('Y') }} <strong>{{ $mmGroupName }}</strong>@if ($mmVersion) <span class="mm-footer-version">v{{ $mmVersion }}</span>@endif</span>
        <span class="mm-footer-links">
            <a href="https://www.banglafire.com/" target="_blank" rel="noopener">Developed by Banglafire Software Ltd</a>
            <button type="button" class="mm-footer-link" data-mm-shortcuts-open hidden>Keyboard shortcuts</button>
            <button type="button" id="mm-density-toggle" class="mm-footer-density" aria-pressed="false">Compact spacing</button>
            @if ($mmSupport)
                <a href="{{ $mmSupport }}" target="_blank" rel="noopener">Help &amp; support</a>
            @endif
        </span>
    </div>
    <ul class="mm-footer-status" aria-label="System status">
        <li class="mm-status-extra"><span class="mm-status-label">Business date</span> <time datetime="{{ now()->toDateString() }}">{{ $mmBusinessDate }}</time></li>
        <li class="mm-status-extra"><span class="mm-status-label">Server time</span> <time id="mm-clock" data-server-time="{{ now()->toIso8601String() }}" data-timezone="{{ $mmTimezone }}">{{ now()->timezone($mmTimezone)->format('H:i:s') }}</time> <span class="mm-status-zone">{{ $mmTimezone }}</span></li>
        <li class="mm-status-extra"><span class="mm-status-label">Last sync</span> <time id="mm-sync" datetime="{{ now()->toIso8601String() }}">just now</time></li>
        <li><span class="mm-online" id="mm-online" data-online="true" role="status"><span class="mm-online-dot" aria-hidden="true"></span><span id="mm-online-text">Online</span></span></li>
        <li><span class="mm-env mm-env-{{ \Illuminate\Support\Str::slug($mmEnvironment) }}" title="Application environment">{{ $mmEnvironment }}</span></li>
    </ul>
</footer>
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse" aria-label="Back to top">
    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110" aria-hidden="true"></i>
</a>
