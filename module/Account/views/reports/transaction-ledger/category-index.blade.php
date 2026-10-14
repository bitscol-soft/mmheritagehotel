@extends('layouts.master')
@section('title', 'Transaction Ledger')
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
@php
$from = request('from',date('Y-m-d'));
$to = request('to',date('Y-m-d'));
@endphp

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Transaction Ledger" description="Transactions by category.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh Data</a>
        <a class="mm-button" href="{{ request()->fullUrlWithQuery(['print' => 1, 'from' => $from, 'to' => $to]) }}" style="color: maroon !important;"><i class="fa fa-print"></i> Print Data</a>
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form" action="" method="get">

                @include('includes.input-groups.date-range', ['date1' => $from, 'date2' => $to, 'is_read_only' => true])

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
                <x-mm.table-scroll label="Transaction Ledger">
                    <table class="table table-bordered table-striped" style="margin-bottom: 0">
                        <thead>
                            <tr class="table-header-bg">
                                <th class="text-center">Sl</th>
                                <th>Account Group</th>
                                <th>Account Control</th>
                                <th>Account Subsidiary</th>
                                <th>Account Name</th>
                                <th class="text-right pr-1">Opening.</th>
                                <th class="text-right pr-1">Dr.</th>
                                <th class="text-right pr-1">Cr.</th>
                                <th class="text-right pr-1">Balance</th>
                            </tr>
                        </thead>

                        <tbody>

                            @if($accountGroups->count() == 0)
                                <tr>
                                    <td colspan="7" style="font-size: 16px" class="text-center text-danger">NO RECORDS FOUND!</td>
                                </tr>
                            @endif

                            @php
                                $sl = 1;
                                $totalOpeningBalance = 0;
                                $totalDebit = 0;
                                $totalCredit = 0;
                            @endphp

                            @foreach($accountGroups as $accountGroup)
                                @foreach($accountGroup->accountControls as $accountControl)
                                    @foreach($accountControl->accountSubsidiaries as $accountSubsidiary)
                                        @foreach($accountSubsidiary->accounts as $account)
                                            @php
                                                $balance = $account->credit + $account->debit + $account->opening_balance;
                                                $totalOpeningBalance += $account->opening_balance;
                                                $totalDebit += $account->debit;
                                                $totalCredit += $account->credit;
                                            @endphp

                                            <tr>
                                                <td class="text-center">{{ $sl++ }}</td>
                                                <td>{{ $accountGroup->name }}</td>
                                                <td>{{ $accountControl->name }}</td>
                                                <td>{{ $accountSubsidiary->name }}</td>
                                                <td>{{ $account->name }}</td>
                                                <td class="text-right pr-1">{{ number_format($account->opening_balance ?? 0, 2) }}</td>
                                                <td class="text-right pr-1">{{ number_format(abs($account->debit ?? 0), 2) }}</td>
                                                <td class="text-right pr-1">{{ number_format($account->credit ?? 0, 2) }}</td>
                                                <td class="text-right pr-1">{{ number_format($balance ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="5">Total:</th>
                                <th class="text-right pr-1">{{ number_format(abs($totalOpeningBalance), 2) }}</th>
                                <th class="text-right pr-1">{{ number_format(abs($totalDebit), 2) }}</th>
                                <th class="text-right pr-1">{{ number_format($totalCredit, 2) }}</th>
                                <th class="text-right pr-1">{{ number_format(($totalOpeningBalance + $totalDebit + $totalCredit), 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </x-mm.table-scroll>
                <br>
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

