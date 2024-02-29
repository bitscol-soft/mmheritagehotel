@extends('layouts.master')


@section('title', 'Bar New Sale')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom_css/floating-input.css') }}">
    @include('bar/sales-v2/_inc/style')

@endsection
@section('content')


    @include('bar/sales/_inc/guest-modal')
    <div class="row" style="margin: -10px -20px 0 -20px !important">
        <div class="card">
            <div id="sidebadr2" class="sidebar h-sidebar navbar-collapse collapse ace-save-state" data-sidebar="true"
                data-sidebar-scroll="true" data-sidebar-hover="true"
                style="background-color: {{ setting('topbar_background_color') }} !important">
                <ul class="nav nav-list" style="top: 0px;">

                    <li style="width: 100%">
                        <a href="{{ route('bar.sales.index') }}"
                            style="background-color: {{ setting('topbar_background_color') }} !important; color:{{ setting('topbar_text_color') }}">

                            <span class="menu-text" style="font-size: 35px;font-weight:900">Bar Sale</span>
                            {{-- <span class="menu-text" style="font-size: 35px;font-weight:900"> {{ optional(optional(optional(auth()->user())->company)->group)->name }} </span> --}}
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </div>

            <x-alert-message />

            <div class="card-body">
                <div class="col-sm-12 padding-m-none">

                    {{-- FOR UPDATING ROUTE ON [EDIT-SALE.BLADE.PHP] WHILE SAVING/SUBMITTING SALE WITHOUT ANY PAGE RELOAD --}}
                    <input type="hidden" value="{{ route('bar.sales-v2.store') }}" id="storeSaleUrl">


                    @include('bar/sales-v2/_inc/left-side')

                    @include('bar/sales-v2/_inc/right-side')

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    @include('bar/sales-v2/_inc/script')
    @include('bar/sales/_inc/script')

    <script>
        var myVar = setInterval(myTimer, 1000);

        function myTimer() {
            var d = new Date();
            document.getElementById("current-time").innerHTML = d.toLocaleTimeString();
        }
    </script>

@endsection
