@once
    @push('custom_js')
        {{-- mm-ui.js wires up the interactive behaviour for every x-mm component
             (modal, daterange quick chips, chip remove, data-table priority fold,
             print-sheet). Loaded with filemtime cache-bust to match the
             pattern used by <x-mm.styles /> for tokens.css / ui.css. --}}
        <script defer src="{{ asset('assets/custom_js/mm-ui.js') }}?v={{ filemtime(public_path('assets/custom_js/mm-ui.js')) }}"></script>
    @endpush
@endonce
