@extends('layouts.master')

@section('title', 'Cash Flow')

@push('style')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>

    <style type="text/css">
        table, td, tr {
            /* border: none !important; */
            background-color: transparent !important;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 3px;
        }
    </style>
@endpush

@section('content')
@php
    $from = request('from',date('Y-m-d'));
    $to = request('to',date('Y-m-d'));
@endphp

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Cash Flow Report" description="Cash movements for the period.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh</a>
        <a class="mm-button" href="{{ request()->fullUrlWithQuery(['print' => 1, 'from' => $from, 'to' => $to]) }}" style="color: maroon !important;"><i class="fa fa-print"></i> Print</a>
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form" action="" method="get">

                <div class="input-group">
                    <label class="input-group-addon">Company</label>
                    <select class="form-control chosen-select-100-percent" name="company_id" data-placeholder="-Select Company-">
                        <option></option>
                        @foreach ($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                @include('includes.input-groups.date-field', ['date' => $from, 'is_read_only' => true])

                <div class="btn-group btn-corner">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
                </div>

        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')

        <!-- LIST -->
        <div class="row" style="width: 100%; margin: 0 !important;">
            <div class="col-sm-12 px-4">

                <div class="row">
                    <div class="col-sm-12">

                        <x-mm.table-scroll label="Cash Flow Report">
                            <table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%;">

                                <tbody>
                                    <tr>
                                        <td>Sl.</td>
                                        <td><strong>Particular</strong></td>
                                        <td width="150px" class="text-center pr-1">Tk.</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <strong>Cash flows from operating activities: </strong>
                                        </td>
                                        <td width="150px" class="text-right pr-1"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Net Profit/Loss
                                        </td>
                                        <td width="150px" class="text-right pr-1">{{ number_format($equity_balance, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Adjustment to reconcile net profit to net cash:
                                        </td>
                                        <td width="150px" class="text-right pr-1"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Depriciation exp
                                        </td>
                                        <td width="150px" class="text-right pr-1">{{ number_format($depreciations, 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Current Asset Increase/Decrease
                                        </td>
                                        <td width="150px" class="text-right pr-1">{{ $asset[0] >= 0 ? '(' . number_format(abs($asset[0]), 0) . ')' : number_format(abs($asset[0]), 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Current Liabilities Increase/Decrease
                                        </td>
                                        <td width="150px" class="text-right pr-1">{{ $liabilities[0] < 0 ? '(' . number_format(abs($liabilities[0]), 0) . ')' : number_format(abs($liabilities[0]), 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Net cash provided/used by Operating Activities
                                        </td>

                                        @php
                                            $new_asset = (int)((-1) * $asset[0]);

                                            $operating_activities = $equity_balance
                                                                    + $depreciations
                                                                    + $new_asset
                                                                    + ($liabilities[0] >= 0 ? $liabilities[0] : (-1 * $liabilities[0]))
                                                                    ;
                                        @endphp
                                        <td width="150px" class="text-right pr-1">
                                            <strong>{{ $operating_activities >= 0 ? '(' . number_format(abs($operating_activities), 0) . ')' : number_format(abs($operating_activities), 0) }}</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>2</td>
                                        <td>
                                            <strong>Cash flows from investing activities: </strong>
                                        </td>
                                        <td width="150px" class="text-right pr-1"></td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td>
                                            Fixed Assets Increase/Decrease
                                        </td>
                                        <td width="150px" class="text-right pr-1">{{ $asset[1] >= 0 ? '(' . number_format(abs($asset[1]), 0) . ')' : number_format(abs($asset[1]), 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Net cash provided/used by I.A
                                        </td>
                                        <td width="150px" class="text-right pr-1">
                                            <strong>{{ $asset[1] >= 0 ? '(' . number_format(abs($asset[1]), 0) . ')' : number_format(abs($asset[1]), 0) }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>
                                            <strong>Cash flows from financing activities:</strong>
                                        </td>
                                        <td width="150px" class="text-right pr-1"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Long Time Liabilities Increase/Decrease
                                        </td>
                                        <td width="150px" class="text-right pr-1">{{ $liabilities[1] < 0 ? '(' . number_format(abs($liabilities[1]), 0) . ')' : number_format(abs($liabilities[1]), 0) }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Net cash provided/used by F.A
                                        </td>
                                        <td width="150px" class="text-right pr-1">
                                            <strong>{{ $liabilities[1] < 0 ? '(' . number_format(abs($liabilities[1]), 0) . ')' : number_format(abs($liabilities[1]), 0) }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Net Cash Charged
                                            <br>
                                            Add Opening Balance
                                            <br>
                                            <strong>Closing Balance</strong>
                                        </td>
                                        <td width="150px" class="text-right pr-1"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </x-mm.table-scroll>

                        <!-- EXCEL BUTTON -->
                        <a class="hidden-print" href="{{ url()->current() }}?export_type=excel&{{ request()->getQueryString() }}" target="_blank" style="margin: 18px 0 0 20px; display: inline-block;">
                            <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

@endsection

