<x-mm.panel class="tw-p-4 tw-mb-4">
<form action="" method="get" class="mm-setup-filter">
    <x-mm.field label="Title" id="booking-note-filter" name="title" :value="request('title')" />
    <button type="submit" class="mm-button mm-button-primary">Search</button>
    <a href="{{ request()->url() }}" class="mm-button mm-button-secondary">Reset</a>
</form>
</x-mm.panel>
