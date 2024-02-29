@extends('layouts.master')
@section('title', 'Balance Sheet')
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
            /*size: landscape;*/
        }

        table, td, tr {
            border: none !important;
            background-color: transparent !important;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 3px;
        }
    </style>
@endpush

@section('content')
    <div class="row">

        <div class="col-sm-12" style="z-index: 999;">
            <div class="btn-group btn-corner mt-2 pull-right no-print">
                <a href="{{ route('report.transaction-ledger') }}" class="btn btn-danger btn-minier">
                    <i class="fa fa-backward"></i> Back
                </a>
                <button class="btn btn-primary btn-minier" onclick="window.print()">
                    <i class="fa fa-print"></i> Print
                </button>

            </div>
        </div>

        @php
            $company = optional($accountGroups->first())->company;
        @endphp

        <!-- PRINT CONTENT -->
        <div class="col-sm-12" style="width:100% !important" id="print">
{{--            @if (optional(optional($company)->company_details)->header)--}}
{{--                <img style="height: 80px; width: 100%; margin-bottom: 20px;"--}}
{{--                     src="{{ asset('uploads/company/extra/'.optional(optional($company)->company_details)->header) }}"--}}
{{--                     alt="{{ optional($company)->name }}">--}}
{{--            @else--}}
                <h4 style="line-height: 0;text-align: center">{{ optional($company)->name }}</h4>
{{--            @endif--}}

            <hr class="no-print">

            <h4 style="line-height: 0; text-align: center !important; background:red !important; font-weight: bolder; margin-top: 60px !important">
                <center> Balance Sheet</center>
            </h4>

            <h5 style="line-height: 0; text-align: center !important; background:red !important; margin-top: 30px !important; font-style: italic">
                <center><strong>From:</strong> {{ fdate(request('from')) }} <strong></center>
            </h5>

            @php
                $totalOwnerEquity = 0;
                $asset = 0;
            @endphp

            @foreach($accountGroups as $key => $accountGroup)
                <div class="row">
                    <div class="col-sm-12">

                        <h4 style="margin-left: 5%"><strong>{{ $accountGroup->name }}</strong></h4>
                        <table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%; border: none !important">

                            <tbody>
                            @foreach($accountGroup->accounts->where('balance', '<>', 0)->sortBy('name') as $account)
                                <tr>
                                    <td style="border: none !important">{{ $account->name }}</td>
                                    <td style="border: none !important" width="150px" class="text-right pr-1">{{ number_format($account->balance ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td style="border: none !important" class="text-right">
                                    <strong style="font-size: 17px; font-weight: bolder; letter-spacing: -1px !important;">
                                        Total {{ $accountGroup->name }}
                                    </strong>
                                </td>
                                <td style="border: none !important" class="text-right pr-1" width="20%">{{ number_format($totalBalance = $accountGroup->accounts->sum('balance'), 2) }}</td>
                            </tr>

                            @if ($loop->iteration > 1)
                                @php $totalOwnerEquity += $totalBalance; @endphp
                            @else
                                @php $asset = $totalBalance; @endphp
                            @endif

                            @if($loop->last)
                                <tr>
                                    <td class="text-right" style="border-top: 1px solid black !important; border-bottom: none !important; border-left: none !important; border-right: none !important;">
                                        <strong style="font-size: 17px; font-weight: bolder; letter-spacing: -1px !important;">
                                            Liabilities and Owners Equity
                                        </strong>
                                    </td>
                                    <td class="text-right pr-1" width="20%" style="border-top: 1px solid black !important;border-bottom: none !important; border-left: none !important; border-right: none !important;">{{ number_format($totalOwnerEquity, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="border: none !important;">
                                        <strong style="font-size: 17px; font-weight: bolder; letter-spacing: -1px !important;">
                                            Current Equity
                                        </strong>
                                    </td>
                                    <td class="text-right pr-1" width="20%" style="border: none !important;">{{ number_format($currentEquity = ($asset - $totalOwnerEquity), 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" style="border-top: 1px solid black !important;border-bottom: none !important; border-left: none !important; border-right: none !important;">
                                        <strong style="font-size: 17px; font-weight: bolder; letter-spacing: -1px !important;">
                                            Total Liabilities and Owners Equity
                                        </strong>
                                    </td>
                                    <td class="text-right pr-1" style="border-top: 1px solid black !important;border-bottom: none !important; border-left: none !important; border-right: none !important;">{{ number_format($totalOwnerEquity + $currentEquity, 2) }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
            <br>

{{--            @if (optional(optional($company)->company_details)->header)--}}
{{--                <img style="width: 100%; height: 60px;"--}}
{{--                     src="{{ asset('uploads/company/extra/'.optional(optional($company)->company_details)->footer) }}"--}}
{{--                     alt="{{ optional($company)->name }}">--}}
{{--            @endif--}}

        </div>
    </div>



@endsection

@section('js')
    
    
@endsection


