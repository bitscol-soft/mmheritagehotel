@extends('layouts.master')
@section('title','Supplier Report')
@section('page-header')
    <i class="fa fa-list"></i> Supplier Report
@stop
@push('style')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }


        .text-center {
            text-align: center !important;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12">

        @include('partials._alert_message')

            <!-- Buttons -->
            <div class="row px-1 pt-2 pb-2 text-right no-print" style="width: 100%; margin: 0 !important;">
                <div class="btn-group btn-corner">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="print()"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>

            <!-- Header image -->


            <!-- Sub Header -->
            <div class="row pb-1" style="width: 100%; margin: 0 !important;">
                <div class="col-sm-12 px-1" style="width: 100%">
                    <h4 style="background-color: #eee; padding: 12px; text-align: center">Supplier Report</h4>
                    <!-- <h4 style="padding: 0; margin: 0; text-align: center">{{ optional(optional($purchases->first())->account)->name }}</h4> -->
                    <h5 style="text-align: center;">Date From {{fdate(request('from'),'d/m/Y')}} To {{fdate(request('to'),'d/m/Y')}}</h5>
                </div>
            </div>

            <!-- LIST -->
            <div class="row" style="width: 100%; margin: 0 !important;">
                <div class="col-sm-12 px-1" style="width: 100%">
                    <table class="table table-bordered table-striped" style="margin-bottom: 0; width: 100%">
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
                        $totalDebit = 0;
                        $totalCredit = 0;
                        @endphp

                        @foreach($purchases as $purchase)
                            @php
                                $totalDebit += $debit = $purchase->amount < 0 ? abs($purchase->amount) : 0;                               $totalCredit += $credit = $purchase->amount >= 0 ? $purchase->amount : 0;
                                $balance += $purchase->amount;
                            @endphp

                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $purchase->date }}</td>
                                <td class="text-center">{{ $purchase->invoice_no }}</td>
                                <td class="pl-3"></td>
                                <td class="text-right pr-1">{{ $debit }}</td>
                                <td class="text-right pr-1">{{ $credit }}</td>
                                <td class="text-right pr-1">{{ $balance }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr>
                            <th colspan="4">Total:</th>
                            <th class="text-right pr-1">{{$totalDebit}}</th>
                            <th class="text-right pr-1">{{$totalCredit}}</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection


