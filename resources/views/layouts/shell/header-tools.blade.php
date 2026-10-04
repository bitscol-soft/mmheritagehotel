{{-- Shell-only header actions. Behaviour lives in assets/custom_js/shell-tools.js; links reuse existing routes and permissions. --}}
<li class="mm-hdr-item mm-hdr-search-item">
    <button type="button" class="mm-hdr-search" data-mm-palette-open aria-label="Search screens and actions" aria-haspopup="dialog" aria-keyshortcuts="Control+K Meta+K" hidden>
        <i class="fa fa-search" aria-hidden="true"></i>
        <span class="mm-hdr-search-text">Search screens &amp; actions</span>
        <kbd class="mm-kbd" data-mm-mod-key>Ctrl K</kbd>
    </button>
</li>
@if (hasPermission('bookings.create', $slugs))
    <li class="mm-hdr-item">
        <a class="mm-hdr-cta" href="{{ route('booking.create') }}" data-mm-action="new-booking" aria-label="New booking" title="New booking">
            <i class="fa fa-plus" aria-hidden="true"></i><span class="mm-hdr-label">New booking</span>
        </a>
    </li>
@endif
<li class="mm-hdr-item mm-hdr-tools">
    {{-- W2.1: nav-mode toggle. Cycles the sidebar through
         full / rail / collapsed. The current mode is reflected
         on aria-pressed; the JS in mm-ui.js persists the
         choice in localStorage. --}}
    <button type="button" class="mm-hdr-btn" data-mm-nav-toggle aria-pressed="false" aria-label="Toggle sidebar layout" title="Toggle sidebar layout">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </button>
    <button type="button" class="mm-hdr-btn" data-mm-theme-toggle aria-pressed="false" aria-label="Dark theme" title="Dark theme" hidden>
        <i class="fa fa-moon-o" aria-hidden="true"></i>
    </button>
    <button type="button" class="mm-hdr-btn mm-hdr-optional" data-mm-fullscreen aria-pressed="false" aria-label="Full screen" title="Full screen" hidden>
        <i class="fa fa-expand" aria-hidden="true"></i>
    </button>
    <button type="button" class="mm-hdr-btn mm-hdr-optional" data-mm-shortcuts-open aria-haspopup="dialog" aria-keyshortcuts="?" aria-label="Keyboard shortcuts" title="Keyboard shortcuts" hidden>
        <i class="fa fa-keyboard-o" aria-hidden="true"></i>
    </button>
</li>
