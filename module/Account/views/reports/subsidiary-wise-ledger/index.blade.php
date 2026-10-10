@extends('layouts.master')
@section('title', 'Subsidiary Wise Ledger')
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

        input[type=checkbox].ace+.lbl::before {
            margin-right: 5px !important;
        }

    </style>
@endpush

@section('content')
@php
    $total_debit = 0;
    $total_credit = 0;
@endphp

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Subsidiary Wise Ledger" description="Ledger by subsidiary account.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh Data</a>
        <a class="mm-button" href="{{ request()->getRequestUri() }}&print=print" style="color: maroon !important;"><i class="fa fa-print"></i> Print Data</a>
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form" action="" method="get">
            <div class="row px-3 pb-2" style="width: 100%; margin: 0 !important;">

                    <div class="input-group">
                        <label class="input-group-addon">Company</label>
                        <select class="form-control chosen-select-100-percent" name="company_id" data-placeholder="-Select Company-">
                            <option></option>
                            @foreach ($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @include('includes.input-groups.select-group', ['modelVariable' => 'accountSubsidiaries',
                    'edit_id' => request('account_subsidiary_id')])

                    @include('includes.input-groups.date-range', ['date1' => request('from',date('Y-m-d')), 'date2'
                    => request('to',date('Y-m-d')), 'is_read_only' => true])

                    <div class="btn-group btn-corner">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search
                        </button>
                    </div>
            </div>

            <!-- LIST -->
            <div class="row" style="width: 100%; margin: 0 !important;">
                    @if (request('account_subsidiary_id') && $accounts->count())
                        <x-mm.table-scroll label="Subsidiary Wise Ledger">
                            <table class="table table-bordered table-striped" style="margin-bottom: 0">
                                <thead>
                                    <tr class="table-header-bg">
                                        <th class="text-center" style="width: 10%">
                                            <label>
                                                <input type="checkbox" class="ace select-all">
                                                <span class="lbl bolder">Select All</span>
                                            </label>
                                        </th>
                                        <th class="text-left pl-3" style="font-size: 14px">Account Name</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($accounts as $account)
                                        <tr>
                                            <td class="text-center">
                                                <label>
                                                    <input type="checkbox" class="ace account" name="accounts[]"
                                                        value="{{ $account->id }}"
                                                        {{ in_array($account->id, request('accounts') ?? []) ? 'checked' : '' }}>
                                                    <span class="lbl bolder">{{ $loop->iteration }}</span>
                                                </label>
                                            </td>
                                            <td class="text-left pl-3">
                                                {{ $account->name }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                </tfoot>
                            </table>
                        </x-mm.table-scroll>

                        @if (!request('accounts'))
                            <div class="text-right py-2">
                                <button type="submit" class="btn btn-primary btn-sm next-btn" disabled><i
                                        class="fa fa-arrow-right"></i> Next
                                </button>
                            </div>
                        @else
                            <x-mm.table-scroll label="Subsidiary Wise Ledger">
                                <table class="table mt-2" style="margin-bottom: 10px">

                                    <tbody>

                                        @php

                                            $balance_type = $accountTransactions->first()->balance_type;
                                        @endphp

                                        @foreach ($accountTransactions as $account)

                                            <tr>
                                                <th class="text-center" style="border-top: none; width: 3%">
                                                    {{ $loop->iteration }}
                                                </th>

                                                <th class="text-left pl-2" style="border-top: none">
                                                    {{ $account->name }}
                                                </th>

                                                @php

                                                    if ($balance_type == 'Debit') {
                                                        $total = ($account->transaction_items->sum('credit_amount') - $account->transaction_items->sum('debit_amount'));
                                                    } else {
                                                        $total = ($account->transaction_items->sum('debit_amount') - $account->transaction_items->sum('credit_amount'));
                                                    }
                                                @endphp

                                                <th style="border-top: none" class="text-right pr-2">
                                                    <strong style="font-size: 15px">{{ number_format($total) }}</strong>
                                                </th>
                                            </tr>

                                            @if ($account->transaction_items->count())
                                                <tr>
                                                    <td>
                                                    </td>

                                                    <td colspan="2">
                                                        <table class="table table-bordered table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Description</th>
                                                                    <th class="text-right pr-1">Dr.</th>
                                                                    <th class="text-right pr-1">Cr.</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @foreach ($account->transaction_items as $transaction)
                                                                    <tr>
                                                                        <td>{{ $transaction->date }}</td>
                                                                        <td>{{ $transaction->getDescription() }}</td>
                                                                        <td class="text-right pr-1">{{ number_format($transaction->credit_amount, 2) }}</td>
                                                                        <td class="text-right pr-1">{{ number_format($transaction->debit_amount, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="3">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </x-mm.table-scroll>

                            <!-- EXCEL BUTTON -->
                            <a class="hidden-print" href="{{ url()->current() }}?export_type=excel&{{ request()->getQueryString() }}" target="_blank" style="margin: 18px 0 0 20px; display: inline-block;">
                                <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                            </a>

                        @endif
                    @endif
            </div>
        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
    </x-mm.panel>
</x-mm.page>

    {{-- <h1>
        <strong>Total: {{ $total_credit - $total_debit }}</strong>
    </h1> --}}
@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    <script>
        const selectAll = $('input.select-all');
        const selectAccount = $('input.account');
        const accountSubsidiaryId = $('#account_subsidiary_id');

        accountSubsidiaryId.change(function() {
            if ($(this).val() == '') {
                window.location.href = '{{ request()->url() }}';
            }
        })

        selectAll.on('click', function() {
            if ($(this).prop('checked')) {
                selectAccount.each(function() {
                    $(this).prop('checked', true);
                })
                $('.next-btn').attr('disabled', false);
            } else {
                selectAccount.each(function() {
                    $(this).prop('checked', false);
                })
                $('.next-btn').attr('disabled', true);
            }
        })

        function accountIdClickedfunction() {
            let flag = true;
            $('.next-btn').attr('disabled', true);

            selectAccount.each(function() {
                if (!$(this).prop('checked')) {
                    flag = false;
                } else {
                    $('.next-btn').attr('disabled', false);
                }
            })

            selectAll.prop('checked', flag);
        }

        selectAccount.on('click', accountIdClickedfunction)

        accountIdClickedfunction()
    </script>

@endsection
