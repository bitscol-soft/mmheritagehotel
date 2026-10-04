@php
    $company = \App\Models\Company::find(request('company_id'));
@endphp

@extends('layouts.master')

@section('title','Account Ledger')

@push('style')
    <style>
        .invoice-logo {
            width: 200px;
            height: 55px;
        }
        @media print {
            .invoice-logo {
                width: 150px !important;
                height: 40px !important;
                margin-top: 15px !important;
            }
            .heading {
                margin-top: -55px !important;
            }
            .date {
                font-size: 14px !important;
            }
        }
    </style>
@endpush

@section('content')
    <x-mm.styles />

    <x-mm.print-sheet title="Account Ledger">
        <x-mm.panel class="tw-p-4">
            <!-- HEADING -->
            <div class="row mb-1 heading">
                <div class="col-xs-4">
                    @if(request()->filled('company_id') && file_exists('uploads/company/'. optional($company)->logo))
                        <img class="invoice-logo" src="{{ asset('uploads/company/'. optional($company)->logo) }}" alt="Logo">
                    @endif
                </div>
                <div class="col-xs-4 text-center">
                    <h3 style="line-height: 15px !important; font-weight: 600 !important; color: #000000 !important">{{ optional($company)->name ?? '' }}</h3>
                    <h4 style="line-height: 15px !important; font-weight: 600 !important; color: #000000 !important">Account Ledger</h4>
                    <h4 style="line-height: 15px !important; font-weight: 600 !important; color: #000000 !important">{{ optional(optional($transactions->first())->account)->name }}</h4>
                    <h5 style="line-height: 15px !important; font-weight: 600 !important; color: #000000 !important" class="date">Date {{fdate(request('from'),'d/m/Y')}} To {{fdate(request('to'),'d/m/Y')}}</h5>
                </div>
                <div class="col-xs-4"></div>
            </div>

            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center'],
                ['label' => 'Date', 'align' => 'center'],
                ['label' => 'Voucher No', 'align' => 'center'],
                ['label' => 'Description'],
                ['label' => 'Dr.', 'align' => 'right'],
                ['label' => 'Cr.', 'align' => 'right'],
                ['label' => 'Balance', 'align' => 'right'],
            ]" table-class="table table-bordered table-striped" label="Account ledger">
                @if(request('account_id'))
                    @php
                        if ($selected_account->accountGroup->balance_type == 'Debit') {
                            $balance = $debit_balance - $credit_balance;
                        } else {
                            $balance = $credit_balance - $debit_balance;
                        }
                    @endphp
                    <tr>
                        <td class="text-left pl-3" colspan="6">Opening Balance</td>
                        <td class="text-right pr-1">{{ $balance }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="7" style="font-size: 16px" class="text-center text-danger">NO RECORDS FOUND!</td>
                    </tr>
                @endif
                @php
                    $total_debit = 0;
                    $total_credit = 0;
                    $balance = 0;
                @endphp
                @foreach ($transactions as $transaction)
                    @php
                        if ($selected_account->accountGroup->balance_type == 'Debit') {
                            $balance += ($transaction->debit_amount - $transaction->credit_amount);
                        } else {
                            $balance += ($transaction->credit_amount - $transaction->debit_amount);
                        }

                        $total_debit += $transaction->debit_amount;
                        $total_credit += $transaction->credit_amount;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $transaction->date }}</td>
                        <td class="text-center">{{ $transaction->invoice_no }}</td>
                        <td class="pl-3">{{ $transaction->getDescription() }}</td>
                        <td class="text-right pr-1">{{ number_format($transaction->debit_amount, 2) }}</td>
                        <td class="text-right pr-1">{{ number_format($transaction->credit_amount, 2) }}</td>
                        <td class="text-right pr-1">{{ number_format($balance, 2) }}</td>
                    </tr>
                @endforeach
                <x-slot name="footer">
                    <tr>
                        <th colspan="4">Total:</th>
                        <th class="text-right pr-1">{{ number_format($total_debit, 2) }}</th>
                        <th class="text-right pr-1">{{ number_format($total_credit, 2) }}</th>
                        <th class="text-right pr-1">{{ number_format($balance, 2) }}</th>
                    </tr>
                </x-slot>
            </x-mm.data-table>
        </x-mm.panel>
    </x-mm.print-sheet>
@endsection
