@php
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
    <div class="mm-footer-main">
        <span>&copy; {{ date('Y') }} <strong>{{ $mmGroupName }}</strong>@if ($mmVersion) <span class="mm-footer-version">v{{ $mmVersion }}</span>@endif</span>
        <span class="mm-footer-links">
            <a href="https://www.banglafire.com/" target="_blank" rel="noopener">Developed by Banglafire Software Ltd</a>
            <button type="button" class="mm-footer-link" data-mm-shortcuts-open hidden>Keyboard shortcuts</button>
            @if ($mmSupport)
                <a href="{{ $mmSupport }}" target="_blank" rel="noopener">Help &amp; support</a>
            @endif
        </span>
    </div>
    <ul class="mm-footer-status" aria-label="System status">
        <li><span class="mm-status-label">Business date</span> <time datetime="{{ now()->toDateString() }}">{{ $mmBusinessDate }}</time></li>
        <li><span class="mm-status-label">Server time</span> <time id="mm-clock" data-server-time="{{ now()->toIso8601String() }}" data-timezone="{{ $mmTimezone }}">{{ now()->timezone($mmTimezone)->format('H:i:s') }}</time> <span class="mm-status-zone">{{ $mmTimezone }}</span></li>
        <li><span class="mm-status-label">Last sync</span> <time id="mm-sync" datetime="{{ now()->toIso8601String() }}">just now</time></li>
        <li><span class="mm-online" id="mm-online" data-online="true" role="status"><span class="mm-online-dot" aria-hidden="true"></span><span id="mm-online-text">Online</span></span></li>
        <li><span class="mm-env mm-env-{{ \Illuminate\Support\Str::slug($mmEnvironment) }}" title="Application environment">{{ $mmEnvironment }}</span></li>
    </ul>
</footer>
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse" aria-label="Back to top">
    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110" aria-hidden="true"></i>
</a>
