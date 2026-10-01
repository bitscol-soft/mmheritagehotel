<div class="mm-ui mm-shell-toolbar">
    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-3">
        <span class="mm-badge">Hotel workspace</span>
        <span class="tw-text-sm tw-text-muted">@yield('title', 'Dashboard')</span>
    </div>
    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-3">
        <time class="tw-text-sm tw-text-muted" datetime="{{ now()->toDateString() }}">{{ now()->format('D, d M Y') }}</time>
        <button type="button" id="mm-density-toggle" class="mm-button mm-button-secondary" aria-pressed="false">Compact spacing</button>
    </div>
</div>
