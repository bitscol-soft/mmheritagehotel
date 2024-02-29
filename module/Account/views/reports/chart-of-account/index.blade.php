@extends('layouts.master')

@section('title', 'Chart Of Account')

@section('page-header')
    <i class="fa fa-info-circle"></i> Chart Of Account
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style type="text/css">
        .rate-entry-table td,
        tr {
            border: none !important;
        }

        .bg-qty {
            background: #5759604a;
        }

        .bg-value {
            background: #33712e45;
        }

        .chosen-container>.chosen-single,
        [class*=chosen-container]>.chosen-single {
            height: 30px !important;
        }

    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @include('partials._alert_message')


            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">

                <!-- HEADING -->
                <div class="widget-header widget-header-small mb-2">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller">
                        <a href="{{ request()->url() }}" >
                            <i class="fa fa-refresh bigger-110"></i> Refresh
                        </a>
                    </div>

                    <div class="widget-toolbar border smaller">
                        {{-- <a href="{{ request()->getRequestUri() }}?print=print" > --}}
                        <a href="{{ url()->current() }}?print=print&{{ request()->getQueryString() }}">
                            <i class="fa fa-print bigger-110"></i> Print
                        </a>
                    </div>
                </div>


                <!-- FILTER FORM -->
                <form action="" method="get">

                    <div class="row px-3 pb-2 no-print">

                        <!-- COMPANY -->
                        @include('acc-includes/input-groups/select-group', ['modelVariable' => $companies, 'title' => 'company', 'colSm' => 3])


                        <!-- BALANCE TYPE -->
                        @include('acc-includes/inputs/select-balance-type', ['value' => request('balance_type'), 'colSm' => 3])


                        <!-- NAME -->
                        @include('acc-includes/inputs/input-field', ['name' => 'name', 'value' => request('name'), 'title' => 'Name', 'colSm' => 3])


                        <!-- DATE -->
                        @include('acc-includes/input-groups/date-range', ['date1' => request('from'), 'date2' => request('to'), 'title' => 'Date', 'is_read_only' => true,'colSm' => 3])


                        <!-- ACTION -->
                        @include('acc-includes/inputs/action', ['colSm' => '12', 'marginTop' => 'mt-1', 'textPosition' => 'text-center', 'search' => 1, 'refresh' => 1,'colSm' => 12])


                    </div>

                </form>


                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12 px-4">

                        @include('reports.chart-of-account.export.excel')


                        @include('partials._paginate', ['data' => $accounts])


                        <!-- EXCEL BUTTON -->
                        <a class="hidden-print" href="{{ url()->current() }}?export_type=excel&{{ request()->getQueryString() }}" target="_blank" style="margin: 18px 0 0 20px; display: inline-block;">
                            <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                        </a>


                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>


    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>
@endsection
