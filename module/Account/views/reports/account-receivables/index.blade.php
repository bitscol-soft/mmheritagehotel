@extends('layouts.master')

@section('title', 'Account Receivable')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />

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
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Account Receivable" description="Balances receivable by account.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ request()->url() }}?print=print&company_id={{ request('company_id') }}&account_id={{ request('account_id') }}"><i class="fa fa-print"></i> Print</a>
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

                @include('includes.input-groups.select-group', ['modelVariable' => 'accounts', 'edit_id' =>
                request('account_id')])

                <div class="btn-group btn-corner">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-search"></i>
                        Search
                    </button>
                    <a href="{{ request()->url() }}" class="btn btn-default btn-sm">
                        <i class="fa fa-refresh"></i>
                        Refresh
                    </a>
                </div>

        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')

        <!-- LIST -->
        <div class="row" style="width: 100%; margin: 0 !important;">
            <div class="col-sm-12 px-4">
                <x-mm.table-scroll label="Account Receivable">
                    <table class="table table-bordered table-striped" style="margin-bottom: 0">
                        <thead>
                            <tr class="table-header-bg">
                                <th class="text-center">Sl</th>
                                <th>Account Name</th>
                                <th class="text-right pr-1">Balance</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($transactions as $account)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $account->name }}</td>
                                    <td class="text-right pr-1">
                                        @if($account->balance <> 0)
                                            <a target="_blank" href="{{ route('report.account-ledger') }}?company_id={{ request('company_id') }}&account_id={{ $account->id }}&from=2010-01-01">
                                                {{ number_format($account->balance, 2) }}
                                            </a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        @if(count($transactions) > 0)
                            <tfoot>
                                <tr style="font-size: 18px">
                                    <th class="text-right" colspan="2">
                                        <strong>Total=</strong>
                                    </th>
                                    <th class="text-right pr-1">
                                        <strong>{{ number_format($transactions->sum('balance'), 2) }}</strong>
                                    </th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </x-mm.table-scroll>

                @include('partials._paginate', ['data' => $transactions])

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
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@endsection
