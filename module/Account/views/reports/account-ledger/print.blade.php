@php
    $company = \App\Models\Company::find(request('company_id'));
@endphp





@extends('layouts.master')

@section('title','Account Ledger')

@push('style')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

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

        .text-center {
            text-align: center !important;
        }

        .invoice-logo {
            width: 200px;
            height: 55px;
        }
    </style>
@endpush





@section('content')
    @include('partials._alert_message')


    <!-- BUTTON -->
    <div class="row px-1 pt-2 pb-2 text-right no-print" style="width: 100%; margin: 0 !important;">
        <div class="btn-group btn-corner">
            <button type="submit" class="btn btn-danger btn-sm" onclick="print()"><i class="fa fa-print"></i> Print</button>
        </div>
    </div>


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


    <!-- TABLE -->
    <table class="table table-bordered table-striped" style="width: 100% !important">
        <thead>
            <tr class="table-header-bg">
                <th class="text-center">Sl</th>
                <th class="text-center">Date</th>
                <th class="text-center">Voucher No</th>
                <th class="pl-3">Description</th>
                <th class="text-right pr-1">Dr.</th>
                <th class="text-right pr-1">Cr.</th>
                <th class="text-right pr-1">Balance</th>
            </tr>
        </thead>
        <tbody>
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
                    <td colspan="7" style="font-size: 16px" class="text-center text-danger">NO RECORDS
                        FOUND!
                    </td>
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
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total:</th>
                <th class="text-right pr-1">{{ number_format($total_debit, 2) }}</th>
                <th class="text-right pr-1">{{ number_format($total_credit, 2) }}</th>
                <th class="text-right pr-1">{{ number_format($balance, 2) }}</th>
            </tr>
        </tfoot>
    </table>
@endsection