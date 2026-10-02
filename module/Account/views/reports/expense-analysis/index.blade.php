@extends('layouts.master')
@section('title','Expense Analysis')
@section('page-header')
    <i class="fa fa-list"></i> Expense Analysis
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>

    <style type="text/css">
        .rate-entry-table td, tr {
            border: none !important;
        }

        .bg-qty {
            background: #5759604a;
        }

        .bg-value {
            background: #33712e45;
        }

        .chosen-container > .chosen-single, [class*=chosen-container] > .chosen-single {
            height: 30px !important;
        }
    </style>
@endpush


@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Expense Analysis" description="Expenses by account.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh Data</a>
        <a class="mm-button" href="{{ request()->fullUrlWithQuery(['print' => 1, 'from' => request('from') ?? date('Y-m-d'), 'to' => request('to') ?? date('Y-m-d')]) }}" style="color: maroon !important;"><i class="fa fa-print"></i> Print Data</a>
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form" action="">
            <div class="row px-3" style="width: 100%; margin: 0 !important;">
                    @include('includes.input-groups.select-group', ['modelVariable' => 'accountControls', 'edit_id' => request('account_control_id')])

                    <div class="input-group">
                        <label class="input-group-addon" for="account_subsidiary_id">
                            Account Subsidiary
                        </label>

                        <select id="account_subsidiary_id" name="account_subsidiary_id"
                                class="chosen-select-100-percent"
                                data-placeholder="- Select Account Subsidiary -">
                            <option value=""></option>

                            @foreach($accountSubsidiaries as $item)
                                <option value="{{$item->id}}" {{requestSelect('account_subsidiary_id', $item->id)}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
            </div>

            <div class="row px-3 pt-1 pb-2" style="width: 100%; margin: 0 !important;">
                    <div class="input-group">
                        <label class="input-group-addon" for="account_id">
                            Account
                        </label>

                        <select id="account_id" name="account_id"
                        class="chosen-select-100-percent"
                                data-placeholder="- Select Account -">
                            <option value=""></option>

                            @foreach($accounts as $item)
                                <option value="{{$item->id}}" {{requestSelect('account_id', $item->id)}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    @include('includes.input-groups.date-range', ['is_read_only' => 1, 'is_required' => 1, 'date1' => request('from') ?? date('Y-m-d'), 'date2' => request('to') ?? date('Y-m-d')])

                    <div class="btn-group btn-corner">
                        <button type="submit" class="btn btn-primary btn-sm" style="height: 31px"><i class="fa fa-search"></i> Search</button>
                    </div>
            </div>
        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')

        <!-- LIST -->
        <div class="row" style="width: 100%; margin: 0 !important;">
            <div class="col-sm-12 px-4">

                <x-mm.table-scroll label="Expense Analysis">
                    <table style="width: 100%">
                        <thead>
                        @php
                            $dateWiseTransactions = $transactions->groupBy('date');
                        @endphp

                        @foreach($dateWiseTransactions as $date => $dateWise)
                            <!-- DATE -->
                            <tr>
                                <th colspan="2" style="width: 10%;">
                                    <div style="border-bottom: 1px solid #000; width: 220px">
                                        Date {{fdate($date)}}
                                    </div>
                                </th>
                                <th style="width: 90%;"></th>
                            </tr>

                            @php
                                $subWiseTransactions = $dateWise->groupBy('account_subsidiary_id');
                            @endphp

                            @foreach($subWiseTransactions as $account_subsidiary_id => $subWise)
                                <!-- SUB ACCOUNT -->
                                <tr>
                                    <th style="width: 4%"></th>
                                    <th colspan="2">
                                        <table style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <th colspan="2" style="width: 10%;">
                                                    <div style="border-bottom: 1px solid #000; width: 300px">
                                                        Sub-Accounts
                                                        &emsp;{{optional(optional(optional($subWise->first())->account)->accountSubsidiary)->name}}
                                                    </div>
                                                </th>
                                                <th style="width: 70%"></th>
                                            </tr>

                                            @php
                                                $controlWiseTransactions = $dateWise->groupBy('account_control_id');
                                            @endphp

                                            <!-- CONTROL ACCOUNT -->
                                            @foreach($controlWiseTransactions as $account_control_id => $controlWise)
                                                <tr>
                                                    <th style="width: 5%"></th>
                                                    <th colspan="2">
                                                        <table style="width: 100%;">
                                                            <thead>
                                                            <tr>
                                                                <th colspan="2" style="width: 10%;">
                                                                    <div
                                                                        style="border-bottom: 1px solid #000; width: 400px">
                                                                        Control-Accounts
                                                                        &emsp;{{optional(optional(optional($controlWise->first())->account)->accountControl)->name}}
                                                                    </div>
                                                                </th>
                                                                <th style="width: 70%"></th>
                                                            </tr>

                                                            <!-- ACCOUNT -->
                                                            <tr>
                                                                <th style="width: 5%"></th>
                                                                <th colspan="2">
                                                                    <table style="width: 100%;">
                                                                        <thead>
                                                                        <tr>
                                                                            <th colspan="2"
                                                                                style="width: 30%; border-bottom: 1px solid #000;">
                                                                                Account &emsp;None
                                                                            </th>
                                                                            <th style="width: 55%; border-bottom: 1px solid #000;"></th>
                                                                            <th style="width: 15%; border-bottom: 1px solid #000; text-align: right">
                                                                                Amount
                                                                            </th>
                                                                        </tr>

                                                                        <!-- TRANSACTIONS -->
                                                                        @foreach($controlWise as $account_id => $transaction)
                                                                            <tr>
                                                                                <td
                                                                                    colspan="3"
                                                                                    style="width: 30%;">
                                                                                    {{$transaction->account->name}}
                                                                                </td>
                                                                                <td class="text-right">{{number_format($transaction->amount, 2)}}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        </thead>

                                                                        <tfoot>
                                                                        <tr>
                                                                            <th colspan="4">
                                                                                <div
                                                                                    style="border-bottom: 1px solid #000; width: 55%; margin-left: auto">
                                                                                    Total Control Account <span
                                                                                        style="float: right">{{number_format($controlWise->sum('amount'),2)}}</span>
                                                                                </div>
                                                                            </th>
                                                                        </tr>
                                                                        </tfoot>
                                                                    </table>
                                                            </tr>
                                                            </thead>

                                                            <tfoot>
                                                            <tr>
                                                                <th colspan="3">
                                                                    <div
                                                                        style="border-bottom: 1px solid #000; width: 60%; margin-left: auto">
                                                                        Total Subsidiary Account <span
                                                                            style="float: right">{{number_format($subWise->sum('amount'),2)}}</span>
                                                                    </div>
                                                                </th>
                                                            </tr>
                                                            </tfoot>
                                                        </table>
                                                </tr>
                                            @endforeach
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th colspan="3">
                                                    <div
                                                        style="border-bottom: 1px solid #000; width: 65%; margin-left: auto">
                                                        Date Wise Total Amount <span
                                                            style="float: right">{{number_format($dateWise->sum('amount'),2)}}</span>
                                                    </div>
                                                </th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </th>
                                </tr>
                            @endforeach
                        @endforeach
                        </thead>
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

    <script>
        $(document).ready(function () {
            const accountControlId = $('#account_control_id');
            const accountSubsidiaryId = $('#account_subsidiary_id');
            const accountId = $('#account_id');

            accountControlId.change(function () {
                accountSubsidiaryId.empty();
                accountSubsidiaryId.append(`<option value="">- Select Account Subsidiary -</option>`);
                accountSubsidiaryId.trigger('chosen:updated');

                accountId.empty();
                accountId.append(`<option value="">- Select Account -</option>`);
                accountId.trigger('chosen:updated');

                if ($(this).val() == '')
                    return 0;

                $.get('{{route('ajax.subsidiaries-and-accounts-by-control')}}?account_control_id='+$(this).val(), function (res) {
                    res.subsidiaries.forEach(function (item) {
                        accountSubsidiaryId.append(`<option value="${item.id}">${item.name}</option>`);
                    });
                    accountSubsidiaryId.trigger('chosen:updated');

                    res.accounts.forEach(function (item) {
                        accountId.append(`<option value="${item.id}">${item.name}</option>`);
                    });
                    accountId.trigger('chosen:updated');
                })
            });


            accountSubsidiaryId.change(function () {
                accountId.empty();
                accountId.append(`<option value="">- Select Account -</option>`);
                accountId.trigger('chosen:updated');

                if ($(this).val() == '')
                    return 0;

                $.get(`{{route('ajax.accounts-by-control-and-subsidiary')}}?account_control_id=${accountControlId.val()}&account_subsidiary_id=${$(this).val()}`, function (res) {
                    res.forEach(function (item) {
                        accountId.append(`<option value="${item.id}">${item.name}</option>`);
                    });
                    accountId.trigger('chosen:updated');
                })
            })
        })
    </script>

@endsection


