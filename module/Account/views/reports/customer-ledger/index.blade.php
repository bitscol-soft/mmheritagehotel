@extends('layouts.master')
@section('title','Customer Ledger')
@section('page-header')
<i class="fa fa-info-circle"></i> Customer Ledger
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

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{ request()->url() }}" class="dt-button btn btn-white btn-primary btn-bold" title="Refresh Data" data-toggle="tooltip">
                                    <span>
                                        <i class="fa fa-refresh bigger-110"></i>
                                    </span>
                                </a>

                                <a href="{{ request()->getRequestUri() }}&print=print" class="dt-button btn btn-white btn-info btn-bold" style="color: maroon !important;" title="Print Data" data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
                                    <span>
                                        <i class="fa fa-print bigger-110"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space"></div>

                <div class="row px-3 pb-2" style="width: 100%; margin: 0 !important;">
                    <form action="" method="get">

                        <div class="col-sm-3">
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
                            <div class="input-group">
                                <label class=" input-group-addon" for="account_id">
                                    Account
                                    <sup class="text-danger"> *</sup>
                                </label>
                                <select id="account_id" name="account_id" required="" class="chosen-select-100-percent  required" data-placeholder="- Select Account -" style="display: none;">
                                    <option value=""></option>
                                    @foreach($customer as $value)
                                        <option value="{{ $value->account_id }}" {{ request('account_id') == $value->account_id ? 'selected' : '' }}>{{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="input-daterange input-group">
                                <span class="input-group-addon" style="border-left: 1px solid #ccc;">
                                    Date
                                </span>
                                <input type="text" name="from" class="input-sm form-control date-picker" value="{{ request('from') ?? date('Y-m-d') }}" autocomplete="off" placeholder="From" style="cursor: pointer" readonly="">
                                <span class="input-group-addon">
                                    <i class="fa fa-exchange"></i>
                                </span>
                                <input type="text" name="to" class="input-sm form-control date-picker" value="{{ request('to') ?? date('Y-m-d') }}" autocomplete="off" placeholder="To" style="cursor: pointer" readonly="">
                            </div>
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
                        <table class="table table-bordered table-striped" style="margin-bottom: 0">
                            <thead>
                                <tr class="table-header-bg">
                                    <th class="text-center">Sl</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Voucher No</th>
                                    <th class="text-right pr-1">Dr.</th>
                                    <th class="text-right pr-1">Cr.</th>
                                    <th class="text-right pr-1">Balance</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(request('account_id'))
                                    <tr>
                                        <td class="text-left pl-3" colspan="5">Opening Balance</td>
                                        <td class="text-right pr-1">{{ number_format($balance, 2) }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="6" style="font-size: 16px" class="text-center text-danger">NO RECORDS FOUND!</td>
                                    </tr>
                                @endif

                                @php
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                    $totalbalance = 0;
                                @endphp



                                @foreach($transactions as $transaction)
                                    @php
                                        
                                        $balance += ($transaction->debit_amount - $transaction->credit_amount);
                                            
                                        $totalDebit += $transaction->debit_amount;
                                        $totalCredit += $transaction->credit_amount;
                                    @endphp

                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $transaction->date }}</td>
                                        <td class="text-center">{{ $transaction->invoice_no }}</td>
                                        <td class="text-right pr-1">{{ number_format($transaction->credit_amount, 2) }}</td>
                                        <td class="text-right pr-1">{{ number_format($transaction->debit_amount, 2) }}</td>
                                        <td class="text-right pr-1">{{ number_format($balance, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>



                            <tfoot>
                                @if(request('account_id'))
                                    <tr>
                                        <th colspan="3">Total:</th>
                                        <th class="text-right pr-1">{{ number_format($totalDebit, 2) }}</th>
                                        <th class="text-right pr-1">{{ number_format($totalCredit, 2) }}</th>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>



                        @include('partials._paginate', ['data' => $transactions])

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