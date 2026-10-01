<x-mm.panel class="tw-p-4 tw-mb-4">
<form action="" method="get" class="mm-setup-filter">
    <input type="hidden" name="type" value="{{ request('type') }}">
    <x-mm.field label="Name" id="booking-setup-filter" name="name" :value="request('name')" />
    <button type="submit" class="mm-button mm-button-primary">Search</button>
    <a href="{{ request()->url() }}?type={{ request('type') }}" class="mm-button mm-button-secondary">Reset</a>
</form>
</x-mm.panel>
