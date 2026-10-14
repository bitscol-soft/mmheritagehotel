@once
    @push('ui-styles')
        {{-- Design tokens must load BEFORE ui.css so every consumer rule can
             reference --mm-brand, --mm-radius, etc. tokens.css is a pure
             declaration file (no rules) and is safe to load first. --}}
        <link rel="stylesheet" href="{{ asset('assets/custom_css/tokens.css') }}?v={{ filemtime(public_path('assets/custom_css/tokens.css')) }}">
        <link rel="stylesheet" href="{{ asset('assets/custom_css/ui.css') }}?v={{ filemtime(public_path('assets/custom_css/ui.css')) }}">
    @endpush
@endonce
