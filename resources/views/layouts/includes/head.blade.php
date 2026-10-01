<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />


    <!-- Title -->
    <title>@yield('title') - Smart ERP</title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <!-- fav icon -->
    <link rel="icon" href="{{ $fav_icon }}" type="image/png">


    <script async src="https://www.googletagmanager.com/gtag/js?id={{env('GOOGLE_ANALYTICS_ID')}}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{env('GOOGLE_ANALYTICS_ID')}}');
    </script>



    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />





    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-timepicker.min.css') }}" />


    <!-- SELECT2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- page specific plugin styles -->
    @yield('css')
    @stack('style')






    <!-- google fonts -->
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.googleapis.com.css') }}?v=0.1" />



    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/ace.min.css') }}" class="ace-main-stylesheet"
        id="main-ace-style" />


    <link rel="stylesheet" href="{{ asset('assets/css/ace-skins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/ace-rtl.min.css') }}" />



    <!-- ace settings handler -->
    <script src="{{ asset('assets/js/ace-extra.min.js') }}"></script>




    <!-- toster -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastr.min.css') }}">



    <!-- sweatalert2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.min.css') }}">




    <!-- custom and global style -->
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200;300;400;500;600;700&display=swap');

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .table>tbody>tr>td {
            vertical-align: middle;
        }

        .logo {
            height: 25px !important;
            width: 269px !important;
        }

        .no-skin .sidebar-shortcuts {
            background-color: #dfe2cd;
            padding-top: 10px;
            padding-bottom: 10px;
            font-size: 14px;
        }

        .ace-nav>li {
            border-left: 1px solid #bfc1ae !important;
        }

        .bg-dark {
            background-color: #ededed !important;
        }

        select.required:invalid {
            height: 0px !important;
            opacity: 0 !important;
            position: absolute !important;
            display: flex !important;
        }

        .btn {
            border-radius: 5px;
        }

        .ui-autocomplete {
            z-index: 9999;
        }


        /* Widget Hader Color */
        .widget-header {
            background: #eaf4fa !important;
        }

        .ui-helper-hidden-accessible>div {
            display: none !important;
        }

        .nav-list>li>.submenu li>.submenu>li a>.menu-icon {
            margin-right: 0px !important;
        }

        .nav-list>li>.submenu li>.submenu>li>a {
            padding-left: 10px !important;
        }

        .nav-list>li .submenu>li>a {
            padding: 7px 0 9px 27px !important;
        }

        .hotel-title-name {
            font-size: 34px !important;
        }

        .float-left {
            float: left;
        }

        .float-right {
            float: right;
        }
    </style>









    @if (strlen(optional(auth()->user())->name) >= 10)
        <style>
            .user-info {
                width: 350px;
                max-width: 250px;
            }
        </style>
    @endif




    <!-- bootstrap4 support css -->
    <link rel="stylesheet" href="{{ asset('assets/custom_css/color-size.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/bootstrap4.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/style.css') }}?v=20261001" />

    {{-- Scoped Tailwind layer: requested by the admin shell or an opted-in screen. --}}
    @if ($mmShell ?? false)
        <x-mm.styles />
        <link rel="stylesheet" href="{{ asset('assets/custom_css/shell.css') }}?v={{ filemtime(public_path('assets/custom_css/shell.css')) }}">
    @endif
    @stack('ui-styles')
</head>
