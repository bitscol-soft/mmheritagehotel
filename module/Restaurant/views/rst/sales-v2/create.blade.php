@extends('layouts.master')


@section('title', 'Restaurant New Sale')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom_css/floating-input.css') }}">
    @include('rst/sales-v2/_inc/style')

@endsection
@section('content')

    <x-widget.guest-create-modal />

    <div class="row" style="margin: -10px -20px 0 -20px !important">
        <div class="card">
            <div id="sidebadr2" class="sidebar h-sidebar navbar-collapse collapse ace-save-state" data-sidebar="true"
                data-sidebar-scroll="true" data-sidebar-hover="true" bis_skin_checked="1">
                <ul class="nav nav-list" style="top: 0px;">
                    {{-- <li class="{{ request()->is('hospital-dashboard') ? 'active' : '' }}">
                        <a href="{{ route('home') }}">
                            <i class="menu-icon fa fa-tachometer"></i>
                            <span class="menu-text"> Dashboard </span>
                        </a>

                        <b class="arrow"></b>
                    </li> --}}
                    <li style="width: 100%">
                        <a href="{{ route('rst.sales.index') }}"
                            style="width: 100%; background-color: {{ setting('topbar_background_color') }} !important; color:{{ setting('topbar_text_color') }}">

                            <span class="menu-text" style="font-size: 35px;font-weight:900">Restaurant Sale</span>
                            {{-- <span class="menu-text" style="font-size: 35px;font-weight:900"> {{ optional(optional(optional(auth()->user())->company)->group)->name }} </span> --}}
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </div>

            <x-alert-message />

            <div class="card-body">
                <div class="col-sm-12">

                    {{-- FOR UPDATING ROUTE ON [EDIT-SALE.BLADE.PHP] WHILE SAVING/SUBMITTING SALE WITHOUT ANY PAGE RELOAD --}}
                    <input type="hidden" value="{{ route('rst.sales-v2.store') }}" id="storeSaleUrl">

                    @include('rst/sales-v2/_inc/left-side')

                    @include('rst/sales-v2/_inc/right-side')

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    {{-- <script src="{{ asset('assets/custom_js/guest_filter.js') }}"></script> --}}


    @include('rst/sales-v2/_inc/script')

    @include('sales/_inc/script')

    <script>
        var myVar = setInterval(myTimer, 1000);

        function myTimer() {
            var d = new Date();
            document.getElementById("current-time").innerHTML = d.toLocaleTimeString();
        }
    </script>

@endsection
