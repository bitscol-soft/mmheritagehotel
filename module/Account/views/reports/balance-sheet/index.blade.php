@extends('layouts.master')


@section('title', 'Balance Sheet')


@section('page-header')
    <i class="fa fa-info-circle"></i> Balance Sheet
@stop


@push('style')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>

    <style type="text/css">
        table, td, tr {
            border: none !important;
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
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Balance Sheet" description="Assets, liabilities and equity.">
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

                @foreach($accountGroups->where('id', 1) as $key => $accountGroup)
                    <div class="row">
                        <div class="col-sm-12">

                            <h4 style="margin-left: 5%"><strong>{{ $accountGroup->name }}</strong></h4>
                            <x-mm.table-scroll label="Balance Sheet">
                                <table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%; border-bottom: 1px solid black !important;"">

                                    <tbody>
                                        @php
                                            $totalBalance = 0;
                                        @endphp

                                        @foreach($accountGroup->accountControls as $accountControl)
                                            <tr>
                                                <td>{{ $accountControl->name }}</td>

                                                @if ($accountGroup->balance_type == 'Debit')

                                                    @php
                                                        $totalBalance += $balance = $accountControl->accounts->sum('debit_balance') - $accountControl->accounts->sum('credit_balance');
                                                    @endphp
                                                @else
                                                    @php
                                                        $totalBalance += $balance = $accountControl->accounts->sum('credit_balance') - $accountControl->accounts->sum('debit_balance');
                                                    @endphp
                                                @endif
                                                <td width="150px" class="text-right pr-1">{{ number_format($balance ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-right">
                                                <strong style="font-size: 18px; font-weight: bolder; letter-spacing: -1px !important;">
                                                    Total {{ $accountGroup->name }}
                                                </strong>
                                            </td>
                                            <td class="text-right pr-1" width="20%">
                                                <strong style="font-size: 18px; font-weight: bolder; letter-spacing: -1px !important;">
                                                    {{ number_format($totalBalance, 2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </x-mm.table-scroll>
                        </div>
                    </div>
                @endforeach


                <div class="space-20"></div>

                <div class="row">
                    <div class="col-sm-12">

                        <h4 style="margin-left: 5%"><strong>Owners Equity</strong></h4>
                        <x-mm.table-scroll label="Balance Sheet">
                            <table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%; border-bottom: 1px solid black !important;">

                                <tbody>

                                    <tr>
                                        <td class="text-right">
                                            <strong style="font-size: 14px; font-weight: bolder; letter-spacing: -1px !important;">
                                                Total Equity Balance
                                            </strong>
                                        </td>
                                        <td class="text-right pr-1" width="20%">
                                            <strong style="font-size: 14px; font-weight: bolder; letter-spacing: -1px !important;">
                                                {{ number_format($equity_balance, 2) }}
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </x-mm.table-scroll>
                    </div>
                </div>


                @php
                    $totalBalance = 0;
                    $asset = 0;
                @endphp

                @foreach($accountGroups->whereIn('id', [2, 10]) as $key => $accountGroup)
                    <div class="row">
                        <div class="col-sm-12">

                            @if($loop->first)
                                <h4 style="margin-left: 5%"><strong>{{ $accountGroup->name }}</strong></h4>
                            @endif
                            <x-mm.table-scroll label="Balance Sheet">
                                <table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%">

                                    <tbody>

                                        @foreach($accountGroup->accountControls as $accountControl)
                                            <tr>
                                                <td>
                                                    {{ $accountControl->name == 'None' && $accountGroup->id == 10 ? 'Accumulated Deprication' : $accountControl->name }}
                                                </td>

                                                @if ($accountGroup->balance_type == 'Debit')

                                                    @php
                                                        $totalBalance += $balance = $accountControl->accounts->sum('debit_balance') - $accountControl->accounts->sum('credit_balance');
                                                    @endphp
                                                @else
                                                    @php
                                                        $totalBalance += $balance = $accountControl->accounts->sum('credit_balance') - $accountControl->accounts->sum('debit_balance');
                                                    @endphp
                                                @endif
                                                <td width="150px" class="text-right pr-1">{{ number_format($balance ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach




                                        @if($loop->last)
                                            <tr>
                                                <td class="text-right" style="border-bottom: 1px solid black !important;">
                                                    <strong style="font-size: 14px; font-weight: bolder; letter-spacing: -1px !important;">
                                                        Total Liabilities
                                                    </strong>
                                                </td>
                                                <td class="text-right pr-1" style="border-bottom: 1px solid black !important;">

                                                    <strong style="font-size: 14px; font-weight: bolder; letter-spacing: -1px !important;">
                                                        {{ number_format($totalBalance, 2) }}
                                                    </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right">
                                                    <strong style="font-size: 18px; font-weight: bolder; letter-spacing: -1px !important;">
                                                        Total Liabilities & Owners Equity
                                                    </strong>
                                                </td>
                                                <td class="text-right pr-1">

                                                    <strong style="font-size: 18px; font-weight: bolder; letter-spacing: -1px !important;">
                                                        {{ number_format($totalBalance + $equity_balance, 2) }}
                                                    </strong>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </x-mm.table-scroll>
                        </div>
                    </div>
                @endforeach

                <br>


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


