<div class="mm-ui mm-shell-nav-tools">
    <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
        <span class="tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-muted">Workspace</span>
        <button type="button" class="mm-shell-icon mm-shell-close" data-mm-close aria-label="Close navigation">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>
    <div class="mm-shell-nav-mode tw-mb-4" role="group" aria-label="Sidebar layout">
        <span class="tw-block tw-text-sm tw-font-semibold tw-mb-2">Sidebar layout</span>
        <div class="tw-flex tw-gap-1" role="group">
            <button type="button" class="mm-button mm-button-secondary" data-mm-nav-toggle data-mm-nav-mode="mm-nav-full" aria-label="Full sidebar with text labels">
                <i class="fa fa-list" aria-hidden="true"></i> Full
            </button>
            <button type="button" class="mm-button mm-button-secondary" data-mm-nav-toggle data-mm-nav-mode="mm-nav-rail" aria-label="Icon-only rail (68px)">
                <i class="fa fa-bars" aria-hidden="true"></i> Rail
            </button>
            <button type="button" class="mm-button mm-button-secondary" data-mm-nav-toggle data-mm-nav-mode="mm-nav-collapsed" aria-label="Hide the sidebar">
                <i class="fa fa-times-circle" aria-hidden="true"></i> Hidden
            </button>
        </div>
    </div>
    <label for="mm-menu-filter" class="tw-block tw-text-sm tw-font-semibold tw-mb-2">Find a menu</label>
    <div class="mm-shell-search-row">
    <input id="mm-menu-filter" class="mm-input" type="search" placeholder="Type a screen name…" autocomplete="off" aria-controls="mm-primary-menu" aria-describedby="mm-menu-help">
    <button type="button" class="mm-shell-icon" id="mm-menu-clear" aria-label="Clear menu search" hidden><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>
    <p id="mm-menu-help" class="tw-mt-2 tw-mb-0 tw-text-xs tw-text-muted">Search the menus available to you.</p>
    <p id="mm-menu-empty" class="tw-mt-3 tw-mb-0 tw-text-sm" role="status" hidden>No matching menus.</p>
</div>
