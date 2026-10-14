{{--
    Auth shell for the Laravel auth scaffolding pages and the home page.

    Used by:
      - resources/views/auth/register.blade.php
      - resources/views/auth/passwords/email.blade.php
      - resources/views/auth/passwords/reset.blade.php
      - resources/views/auth/verify.blade.php
      - resources/views/home.blade.php

    The auth cluster was previously broken — the file did not exist, so any
    @extends('layouts.app') view 500'd with "View [layouts.app] not found".
    This layout is the new landing surface for those flows.

    The shell:
      - Loads the mm-web tokens via <x-mm.styles /> (forms get the .mm-input /
        .mm-button / .mm-panel treatment when views opt in).
      - Loads Bootstrap 4 CSS + jQuery + Bootstrap JS as a baseline, because
        the existing auth scaffold views (auth.register, auth.passwords.*)
        use Bootstrap 4 grid + form classes (.container / .row / .col-md-* /
        .form-control / .btn / .card) that would otherwise be unstyled.
      - Renders a centred panel surface via <x-mm.panel>; child views
        @section('content') feeds the body.
      - Exposes @stack('custom_css') and @stack('custom_js') for the login
        view, which adds Ace 4.5 + font-awesome + noty on top of the
        Bootstrap baseline.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    {{-- Bootstrap 4 baseline — required by the stock auth scaffold markup. --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    {{-- mm-web tokens + scoped mm-auth rules. Loaded after Bootstrap so the mm-auth
         styles win where they overlap (form-control focus, btn-primary fill). --}}
    <x-mm.styles />
    @stack('ui-styles')

    {{-- login.blade.php pushes Ace + font-awesome + noty here. --}}
    @stack('custom_css')
</head>
<body class="mm-ui mm-auth">

    <main class="mm-auth-main">
        <x-mm.panel class="mm-auth-panel">
            <div class="mm-auth-card">
                <h1 class="mm-auth-brand">{{ config('app.name') }}</h1>
                @yield('content')
            </div>
        </x-mm.panel>
    </main>

    {{-- jQuery + Bootstrap JS for the scaffold views (input group, error pills). --}}
    <script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    {{-- login.blade.php pushes inline box-toggle / noty / redirect scripts here. --}}
    @stack('custom_js')
</body>
</html>