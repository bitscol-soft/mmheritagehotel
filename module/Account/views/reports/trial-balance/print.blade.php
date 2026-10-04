@extends('layouts.master')
@section('title','Trial Balance')

@push('style')
    <style type="text/css">
        @page { size: landscape; margin: 0.5in; }
    </style>
@endpush

@section('content')
    <x-mm.styles />

    <div class="mm-no-print" style="text-align: right; margin-bottom: 12px">
        <a href="{{ route('report.transaction-ledger') }}" class="mm-button mm-button-secondary">
            <i class="fa fa-backward"></i> Back
        </a>
    </div>

    @php
        $company = optional($accountGroups->first())->company;
    @endphp

    <x-mm.print-sheet sheet="a4-landscape" title="Trial Balance">
        <x-mm.panel class="tw-p-4">
            @if (optional(optional($company)->company_details)->header)
                <img style="height: 80px; width: 100%; margin-bottom: 20px;"
                     src="{{ asset('uploads/company/extra/'.optional(optional($company)->company_details)->header) }}"
                     alt="{{ optional($company)->name }}">
            @else
                <h4 style="line-height: 0;text-align: center">{{ optional($company)->name }}</h4>
            @endif

            <hr>

            <h4 style="line-height: 0; text-align: center !important; font-weight: bolder; margin-top: 20px !important">
                <center>Trial Balance</center>
            </h4>

            <h5 style="line-height: 0; text-align: center !important; margin-top: 10px !important; font-style: italic">
                <center><strong>From:</strong> {{fdate(request('from'))}} <strong>To:</strong> {{fdate(request('to'))}}
                </center>
            </h5>

            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center'],
                ['label' => 'Account Group'],
                ['label' => 'Account Control'],
                ['label' => 'Account Subsidiary'],
                ['label' => 'Account Name'],
                ['label' => 'Opening.', 'align' => 'right'],
                ['label' => 'Dr.', 'align' => 'right'],
                ['label' => 'Cr.', 'align' => 'right'],
                ['label' => 'Balance', 'align' => 'right'],
            ]" table-class="table table-bordered table-striped" label="Trial balance">
                @if($accountGroups->count() == 0)
                    <tr>
                        <td colspan="9" style="font-size: 16px" class="text-center text-danger">NO RECORDS FOUND!</td>
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
                                <tr style="font-size: 13px">
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

                <tr>
                    <th colspan="5">Total:</th>
                    <th style="text-align: right">{{ number_format(abs($totalOpeningBalance), 2) }}</th>
                    <th style="text-align: right">{{ number_format(abs($totalDebit), 2) }}</th>
                    <th style="text-align: right">{{ number_format($totalCredit, 2) }}</th>
                    <th style="text-align: right">{{ number_format(($totalOpeningBalance + $totalDebit + $totalCredit), 2) }}</th>
                </tr>
            </x-mm.data-table>

            @if (optional(optional($company)->company_details)->footer)
                <img style="width: 100%; height: 60px; margin-top: 20px"
                     src="{{ asset('uploads/company/extra/'.optional(optional($company)->company_details)->footer) }}"
                     alt="{{ optional($company)->name }}">
            @endif
        </x-mm.panel>
    </x-mm.print-sheet>
@endsection
