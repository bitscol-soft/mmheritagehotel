@extends('layouts.master')
@section('title','Customer Ledger')
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
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Customer Ledger" description="Customer transactions and balance.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh Data</a>
        <a class="mm-button" href="{{ request()->getRequestUri() }}&print=print" style="color: maroon !important;"><i class="fa fa-print"></i> Print Data</a>
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
                <x-mm.table-scroll label="Customer Ledger">
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
                </x-mm.table-scroll>

                @include('partials._paginate', ['data' => $transactions])

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