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
    <div class="row">
        <div class="col-sm-12">

            @include('partials._alert_message')

            @php
                $from = request('from',date('Y-m-d'));
                $to = request('to',date('Y-m-d'));
            @endphp


            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">


                <!-- heading -->
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller">
                        <a href="{{ request()->url() }}">
                            <i class="fa fa-refresh bigger-110"></i> Refresh
                        </a>
                    </div>

                    <div class="widget-toolbar border smaller">

                        <a href="{{ request()->fullUrlWithQuery(['print' => 1, 'from' => $from, 'to' => $to]) }}" style="color: maroon !important;">
                            <i class="fa fa-print bigger-110"></i> Print
                        </a>
                    </div>
                </div>


                <div class="space"></div>




                <div class="row px-3 pb-2" style="width: 100%; margin: 0 !important;">
                    <form action="" method="get">


                        <div class="col-sm-4 col-sm-offset-1">
                            <div class="input-group">
                                <label class="input-group-addon">Company</label>
                                <select class="form-control chosen-select-100-percent" name="company_id" data-placeholder="-Select Company-">
                                    <option></option>
                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            @include('includes.input-groups.date-field', ['date' => $from, 'is_read_only' => true])
                        </div>

                        <div class="col-sm-2">
                            <div class="btn-group btn-corner">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12 px-4">

                        @foreach($accountGroups->where('id', 1) as $key => $accountGroup)
                            <div class="row">
                                <div class="col-sm-12">

                                    <h4 style="margin-left: 5%"><strong>{{ $accountGroup->name }}</strong></h4>
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
                                </div>
                            </div>
                        @endforeach


                        <div class="space-20"></div>

                        <div class="row">
                            <div class="col-sm-12">

                                <h4 style="margin-left: 5%"><strong>Owners Equity</strong></h4>
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


