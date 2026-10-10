<x-mm.panel class="guest-filter-panel">
    <form id="searchForm" action="" method="get" class="tw-grid tw-gap-4 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-items-end">
        <x-mm.field label="Name" id="guest-filter-name" name="name" :value="request('name')" />
        <x-mm.field label="Mobile" id="guest-filter-phone" name="phone_no" :value="request('phone_no')" />
        <x-mm.field label="NID / Passport" id="guest-filter-nid" name="nid_no" :value="request('nid_no')" />
        <div class="tw-flex tw-flex-wrap tw-gap-2">
            <button type="submit" class="mm-button">
                <i class="fa fa-search" aria-hidden="true"></i> Search
            </button>
            <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Clear guest filters">
                <i class="fa fa-refresh" aria-hidden="true"></i> Clear
            </a>
        </div>
    </form>
</x-mm.panel>
