@extends('layouts.master')
@section('title','Category Wise Transaction Ledger')
@push('style')
    <style type="text/css">
        #print {
            display: block !important;
        }

        @media print {
            .no-print, .no-print * {
                display: none !important;
            }

            tr {
                page-break-after: avoid;
            }

            thead {
                page-break-before: avoid;
            }
        }

        @page {
            margin: 0.5in;
            size: landscape;
        }

        th, td {
            padding: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="row">

        <div class="col-sm-12" style="z-index: 999;">
            <div class="btn-group btn-corner mt-2 pull-right no-print">
                <button class="btn btn-primary btn-minier" onclick="window.print()">
                    <i class="fa fa-print"></i> Print
                </button>

                <a href="{{ route('report.transaction-ledger') }}" class="btn btn-danger btn-minier"><i
                        class="fa fa-backward"></i> Back</a>
            </div>
        </div>

    @php
        $company = optional($accountGroups->first())->company;
    @endphp

    <!-- PRINT CONTENT -->
        <div class="col-sm-12" style="width:100% !important" id="print">
            @if (optional(optional($company)->company_details)->header)
                <img style="height: 80px; width: 100%; margin-bottom: 20px;"
                     src="{{ asset('uploads/company/extra/'.optional(optional($company)->company_details)->header) }}"
                     alt="{{ optional($company)->name }}">
            @else
                <h4 style="line-height: 0;text-align: center">{{ optional($company)->name }}</h4>
            @endif

            <hr class="no-print">

            <h4 style="line-height: 0; text-align: center !important; background:red !important; font-weight: bolder; margin-top: 60px !important">
                <center>Category Wise Transaction Ledger</center>
            </h4>

            <h5 style="line-height: 0; text-align: center !important; background:red !important; margin-top: 30px !important; font-style: italic">
                <center><strong>From:</strong> {{fdate(request('from'))}} <strong>To:</strong> {{fdate(request('to'))}}
                </center>
            </h5>

            <table border="1" cellpadding="5" style="width: 100% !important; margin-top: 50px; margin-bottom: 20px">
                <thead>
                <tr style="font-size: 12px ">
                    <th style="text-align: center;">Sl</th>
                    <th style="">Account Group</th>
                    <th style="">Account Control</th>
                    <th style="">Account Subsidiary</th>
                    <th style="">Account Name</th>
                    <th style="text-align: right;">Opening.</th>
                    <th style="text-align: right;">Dr.</th>
                    <th style="text-align: right;">Cr.</th>
                    <th style="text-align: right;">Balance</th>
                </tr>
                </thead>


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

                <tbody>
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
                                <tr style="font-size: 13px ">
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
                </tbody>


            </table>

            @if (optional(optional($company)->company_details)->header)
                <img style="width: 100%; height: 60px;"
                     src="{{ asset('uploads/company/extra/'.optional(optional($company)->company_details)->footer) }}"
                     alt="{{ optional($company)->name }}">
            @endif

        </div>
    </div>



@endsection

@section('js')
    
    
@endsection


