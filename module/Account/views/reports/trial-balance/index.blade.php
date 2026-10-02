@extends('layouts.master')


@section('title', 'Trial Balance')

@section('page-header')
    <i class="fa fa-info-circle"></i> Trial Balance
@stop



@push('style')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style type="text/css">
        table,
        th,
        td,
        tr {
            border: none !important;
        }


        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            .d-print {
                display: block !important;
            }

            tr {
                page-break-after: avoid !important;
            }

            thead {
                page-break-before: avoid !important;
            }

            .widget-box {
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            .px-4 {
                padding: 0 !important;
            }
        }

        @page {
            margin: 0.5in;
        }

        .d-print {
            display: none;
        }

    </style>
@endpush


@section('content')
@php
    $from = request('from', date('Y-m-d'));
@endphp

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Trial Balance" description="Debit and credit balances.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh</a>
        <a class="mm-button" href="#" onclick="print()" style="cursor: pointer"><i class="fa fa-print"></i> Print</a>
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form" action="" method="get">

                <div class="input-group">
                    <label class="input-group-addon">Company</label>
                    <select class="form-control chosen-select-100-percent" name="company_id"
                        data-placeholder="-Select Company-">
                        <option></option>
                        @foreach ($companies as $id => $name)
                            <option value="{{ $id }}"
                                {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @include('includes.input-groups.date-field', ['date' => $from, 'is_read_only' => true])

                <div class="btn-group btn-corner">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>
                        Search</button>
                </div>

        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <h3 class="text-center d-print" style="margin-top: -30px !important;">TRIAL BALANCE</h3>
        <h4 class="text-center d-print">As On {{ fdate(request('from') ?? today(), 'd/m/Y') }}</h4>


        <!-- LIST -->
        <div class="row" style="width: 100%; margin: 0 !important; padding: 0 !important;">
            <div class="col-sm-12">
                <x-mm.table-scroll label="Trial Balance">
                    <table class="table" style="margin-bottom: 0; width: 100% !important;">
                        <thead>
                            <tr class="bg-secondary"
                                style="color: black !important; font-weight: bolder; font-size: 15px">
                                <th colspan="4">Account Description</th>
                                <th class="text-right pr-1">Dr.</th>
                                <th class="text-right pr-1">Cr.</th>
                            </tr>
                        </thead>

                        <tbody>

                            @if ($accountGroups->count() == 0)
                                <tr>
                                    <td colspan="7" style="font-size: 16px" class="text-center text-danger">NO RECORDS
                                        FOUND!</td>
                                </tr>
                            @endif


                            @php
                                $totalDebit = 0;
                                $totalCredit = 0;

                                $totalTrialAmountDebit = 0;
                                $totalTrialAmountCredit = 0;
                            @endphp

                            @foreach ($accountGroups as $accountGroup)

                                @php
                                    $debitAccountGroup = $accountGroup->accountControls->sum(function ($control) {
                                        return $control->accountSubsidiaries->sum(function ($item) {
                                            return $item->accounts->sum('debit');
                                        });
                                    });
                                    $creditAccountGroup = $accountGroup->accountControls->sum(function ($control) {
                                        return $control->accountSubsidiaries->sum(function ($item) {
                                            return $item->accounts->sum('credit');
                                        });
                                    });

                                    if ($accountGroup->balance_type == 'Debit') {
                                        $totalTrialAmountDebit += ($debitAccountGroup - $creditAccountGroup);
                                    } else {
                                        $totalTrialAmountCredit += ($creditAccountGroup - $debitAccountGroup);
                                    }
                                @endphp


                                <tr style="background: #a8c1c3; color: white;">
                                    <th colspan="4">
                                        <strong style="font-size: 16px">{{ $accountGroup->name }}</strong>
                                    </th>

                                    @if ($accountGroup->balance_type == 'Debit')
                                        <td width="20%" class="text-right pr-1">
                                            <strong
                                                style="font-size: 16px">{{ number_format($debitAccountGroup - $creditAccountGroup, 2) }}</strong>
                                        </td>
                                        <td width="20%" class="text-right pr-1">
                                            <strong style="font-size: 16px">0.00</strong>
                                        </td>

                                    @else
                                        <td width="20%" class="text-right pr-1">
                                            <strong style="font-size: 16px">0.00</strong>
                                        </td>
                                        <td width="20%" class="text-right pr-1">
                                            <strong
                                                style="font-size: 16px">{{ number_format($creditAccountGroup - $debitAccountGroup, 2) }}</strong>
                                        </td>
                                    @endif



                                    @php
                                        $totalDebit += $creditAccountGroup;
                                        $totalCredit += $creditAccountGroup;
                                    @endphp
                                </tr>



                                @foreach ($accountGroup->accountControls as $accountControl)
                                    @php
                                        $debitAccountControl = $accountControl->accountSubsidiaries->sum(function ($item) {
                                            return $item->accounts->sum('debit');
                                        });

                                        $creditAccountControl = $accountControl->accountSubsidiaries->sum(function ($item) {
                                            return $item->accounts->sum('credit');
                                        });

                                    @endphp

                                    <tr>
                                        <td></td>
                                        <th colspan="3">
                                            <strong style="font-size: 15px">{{ $accountControl->name }}</strong>
                                        </th>

                                        @if ($accountGroup->balance_type == 'Debit')
                                            <td width="20%" class="text-right pr-1">
                                                <strong class="account-control-debit-account"
                                                    data-id="{{ $accountControl->id }}"
                                                    style="font-size: 15px">{{ number_format($debitAccountControl - $creditAccountControl, 2) }}</strong>
                                            </td>
                                            <td width="20%" class="text-right pr-1">
                                                <strong style="font-size: 15px">0.00</strong>
                                            </td>
                                        @else
                                            <td width="20%" class="text-right pr-1">
                                                <strong style="font-size: 15px">0.00</strong>
                                            </td>
                                            <td width="20%" class="text-right pr-1">
                                                <strong class="account-control-credit-account"
                                                    style="font-size: 15px">{{ number_format($creditAccountControl - $debitAccountControl, 2) }}</strong>
                                            </td>
                                        @endif
                                    </tr>
                                    @foreach ($accountControl->accountSubsidiaries as $accountSubsidiary)

                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <th colspan="2">
                                                <strong style="font-size: 14px">
                                                    {{ $accountSubsidiary->name }}
                                                </strong>
                                            </th>

                                            @if ($accountGroup->balance_type == 'Debit')
                                                <td width="20%" class="text-right pr-1">
                                                    <strong
                                                        class="subsidiary-debit-account account-control-debit-{{ $accountControl->id }}"
                                                        data-id="{{ $accountSubsidiary->id }}"
                                                        style="font-size: 14px">{{ number_format($accountSubsidiary->accounts->sum('debit') - $accountSubsidiary->accounts->sum('credit'), 2) }} </strong>
                                                </td>
                                                <td width="20%" class="text-right pr-1">
                                                    <strong style="font-size: 14px">0.00</strong>
                                                </td>
                                            @else
                                                <td width="20%" class="text-right pr-1">
                                                    <strong style="font-size: 14px">0.00</strong>
                                                </td>
                                                <td width="20%" class="text-right pr-1">
                                                    <strong
                                                        class="subsidiary-credit-account account-control-credit-{{ $accountControl->id }}"
                                                        style="font-size: 14px">{{ number_format($accountSubsidiary->accounts->sum('credit') - $accountSubsidiary->accounts->sum('debit'), 2) }}</strong>
                                                </td>
                                            @endif
                                        </tr>

                                        @foreach ($accountSubsidiary->accounts as $account)

                                            <tr>
                                                <td width="5%"></td>
                                                <td width="8%"></td>
                                                <td width="8%"></td>
                                                <td width="39%">{{ $account->name }}</td>

                                                @if ($accountGroup->balance_type == 'Debit')
                                                    <td width="20%" class="text-right pr-1 account-debit-{{ $accountSubsidiary->id }}">
                                                        {{ number_format($account->debit - $account->credit ?? 0, 2) }}


                                                    </td>
                                                    <td width="20%" class="text-right pr-1">0.00</td>
                                                @else
                                                    <td width="20%" class="text-right pr-1">0.00</td>
                                                    <td width="20%" class="text-right pr-1 account-credit-{{ $accountSubsidiary->id }}">
                                                        {{ number_format($account->credit - $account->debit ?? 0, 2) }}
                                                    </td>
                                                @endif

                                                {{-- <td>{{ $account->credit . '-' .  $account->debit }}</td> --}}
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @endforeach
                        </tbody>


                        <thead>
                            <tr class="bg-secondary"
                                style="color: black !important; font-weight: bolder; font-size: 15px">
                                <th colspan="4">Trail</th>
                                <th class="text-right pr-1" style="font-size: 24px; color: #1ba74d">
                                    {{ number_format($totalTrialAmountDebit, 0) }}
                                </th>
                                <th class="text-right pr-1" style="font-size: 24px; color: #1ba74d">
                                    {{ number_format($totalTrialAmountCredit, 0) }}
                                </th>
                            </tr>
                        </thead>
                    </table>
                </x-mm.table-scroll>
                <br>


                <!-- EXCEL BUTTON -->
                <a class="hidden-print" href="{{ url()->current() }}?export_type=excel&{{ request()->getQueryString() }}" target="_blank" style="margin: 18px 0 0 20px; display: inline-block;">
                    <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                </a>

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

    <script type="text/javascript">
        $(document).ready(function() {

            calculateSubsidiaryTotal()

            calculateAccountControlTotal()
        })





        // SUBSIDIARY
        function calculateSubsidiaryTotal() {
            $('.subsidiary-debit-account').each(function() {
                let subsidiary = $(this)
                let subsidiary_id = subsidiary.data('id')

                let total_subsidiary = 0
                $(".account-debit-" + subsidiary_id).each(function() {
                    let amount = $(this).text()
                    amount = amount.replace(",", '')

                    total_subsidiary += Number(amount)
                })

                subsidiary.text(moneyFormat(total_subsidiary, 0))
            })
        }





        // SUBSIDIARY
        function calculateAccountControlTotal() {
            $('.account-control-debit-account').each(function() {
                let control_id = $(this).data('id')

                let total_control = 0
                $(".account-control-debit-" + control_id).each(function() {
                    let amount = $(this).text()
                    amount = amount.replace(",", '')

                    total_control += Number(amount)
                })

                $(this).text(moneyFormat(total_control, 0))
            })
        }
    </script>

@endsection
