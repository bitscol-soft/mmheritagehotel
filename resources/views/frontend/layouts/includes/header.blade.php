{{-- <head> --}}

    @php
        $group = App\Models\Group::first();
        $fav_icon = file_exists($group->fav_icon) ? asset($group->fav_icon) : '/icon.png';
    @endphp
    <title>@yield('website_header')&nbsp;{{ websiteInfo()->site_first_name. ' '.websiteInfo()->site_last_name }}</title>
    <!-- for-mobile-apps -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="{{ websiteInfo()->meta_keyword ?? '' }}" />
    <meta name="description" content="{{ websiteInfo()->meta_description ?? '' }}" />
     <!-- fav icon -->
     <link rel="icon" href="{{ $fav_icon }}" type="image/png">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" name="base_url" id="base_url" value="{{url('/')}}">
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
            function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- //for-mobile-apps -->



    <link href="{{ asset('frontend/assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all" />

    <link href="{{ asset('frontend/assets/css/font-awesome.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/chocolat.css') }}" type="text/css" media="screen">

    <link href="{{ asset('frontend/assets/css/easy-responsive-tabs.css') }}" rel='stylesheet' type='text/css'/>

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/flexslider.css') }}" type="text/css" media="screen" property="" />

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/jquery-ui.css') }}" />

    <link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet" type="text/css" media="all" />

    <script type="text/javascript" src="{{ asset('frontend/assets/js/modernizr-2.6.2.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">

     <!-- toster -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastr.min.css') }}">

    <!--fonts-->
    <link href="http://fonts.googleapis.com/css?family=Oswald:300,400,700" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Federo" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">




    <!--//fonts-->

    @stack('custom_css')
{{-- </head> --}}
