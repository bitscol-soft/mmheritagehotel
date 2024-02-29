@extends('layouts.master')
@section('title','Expense Analysis')
@section('page-header')
    <i class="fa fa-list"></i> Expense Analysis
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>

    <style type="text/css">
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endpush


@section('content')
    <div class="row">
        <div class="col-sm-12" style="width: 100%">

        @include('partials._alert_message')

            <!-- Buttons -->
            <div class="row px-1 pt-2 pb-2 text-right no-print" style="width: 100%; margin: 0 !important;">
                <div class="btn-group btn-corner">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="print()"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>

            <div class="space"></div>


            <!-- Sub Header -->
            <div class="row pb-1" style="width: 100%; margin: 0 !important;">
                <div class="col-sm-12 px-1" style="width: 100%">
                    <h4 style="background-color: #eee; padding: 12px; text-align: center">Expense Analysis</h4>
                    <h5 style="text-align: center;">Date From {{ fdate(request('from'),'d/m/Y') }} To {{ fdate(request('to'),'d/m/Y') }}</h5>
                </div>
            </div>

            <!-- LIST -->
            <div class="row pb-2" style="width: 100%; margin: 0 !important;">
                <div class="col-sm-12 px-4">

                    <table style="width: 100%">
                        <thead>
                        @php
                            $dateWiseTransactions = $transactions->groupBy('date');
                        @endphp

                        @foreach($dateWiseTransactions as $date => $dateWise)
                            <!-- DATE -->
                            <tr>
                                <th colspan="2" style="width: 10%;">
                                    <div style="border-bottom: 1px solid #000; width: 220px; margin-top: {{$loop->iteration == 1 ? '10' : '30'}}px">
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    
    
@endsection


