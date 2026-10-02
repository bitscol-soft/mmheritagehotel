{{--
    Public-site mm-web shell.
    Re-uses the same public_head / public_nav / public_footer partials as the legacy
    `frontend.layouts.master`, so the chrome (header, nav, footer) stays identical.
    The page body is rendered inside an `<x-mm.panel>` (the shared mm-web surface) so
    styling matches the admin `HotelWebsite` views and gets the same Tailwind tokens.

    Why panel and not page: the public site does not need the admin `<x-mm.page>` header
    row (page title + actions); that header is for "Add Banner" style admin screens. The
    panel gives the same visual surface, padding and theme tokens, without forcing a
    visible admin H1 above the marketing page.
--}}
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

    @include('frontend.layouts.includes.public_head')

    {{-- mm-web stylesheet (mirrors admin `HotelWebsite` views) --}}
    <x-mm.styles />

    {{-- Render any styles pushed by `<x-mm.styles />` from child views (see resources/views/layouts/includes/head.blade.php line 203 for the same pattern in the admin shell). --}}
    @stack('ui-styles')

    @stack('custom_css')

</head>
<body class="mm-public">

    @include('frontend.layouts.includes.public_nav')

    <!------------- BANNER ------------->
    @yield('homepage_banner')


    <!---------- SEARCH BAR AREA ---------->
    @yield('search_bar')




    <!---- YIELD FRONTEND CONTENT AREA (wrapped in shared mm-web panel) ---->
    <main class="mm-public-main">
        <x-mm.panel class="mm-web mm-public-panel tw-bg-transparent tw-shadow-none">
                @yield('frontend-content')
        </x-mm.panel>
    </main>




    <!------------- COPYRIGHT ------------->
    @php $wi = websiteInfo(); @endphp
    <div class="copy">
        <p>© {{ date('Y') }} {{ $wi->site_first_name }} {{ $wi->site_last_name }} . All Rights Reserved | Developed by <a href="https://www.banglafire.com" target="_blank">Banglafire Software Ltd.</a> </p>
    </div>


    @include('frontend.layouts.includes.public_footer')

    @yield('forntend_script')

    @stack('custom_js')

</body>
</html>