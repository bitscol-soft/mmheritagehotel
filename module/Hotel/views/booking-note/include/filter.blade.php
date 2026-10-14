<x-mm.panel class="tw-p-4 tw-mb-4">
    {{-- W3.3: filter-bar component. The <x-mm.filter-bar> wires
         data-mm-filter-target="#searchForm" so the existing
         searchForm (if/when the page wraps it) submits on change.
         The form action defaults to the current URL when blank. --}}
    <x-mm.filter-bar action="" method="GET" search-form-id="searchForm">
        <x-mm.field label="Title" id="booking-note-filter" name="title" :value="request('title')" />
    </x-mm.filter-bar>
</x-mm.panel>
