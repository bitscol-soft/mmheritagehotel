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

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Chart Of Account" description="Chart of accounts by group.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh</a>
        {{-- <a href="{{ request()->getRequestUri() }}?print=print" > --}}
        <a class="mm-button" href="{{ url()->current() }}?print=print&{{ request()->getQueryString() }}"><i class="fa fa-print"></i> Print</a>
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form" action="" method="get">

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
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- FILTER FORM -->


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
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>


    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

@endsection
