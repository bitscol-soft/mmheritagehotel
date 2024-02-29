@extends('layouts.master')

@section('title', 'Voucher Reports')

@section('page-header')
    <i class="fa fa-list"></i> Voucher Reports
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



            <!-- Sub Header -->
            <div class="row pb-1" style="width: 100%; margin: 0 !important;">
                <div class="col-sm-12 px-1" style="width: 100%">
                    <h4 style="background-color: #eee; padding: 12px; text-align: center">Voucher Reports</h4>
                    <h5 style="text-align: center;">Date From {{fdate(request('from'), 'd/m/Y')}} To {{ fdate(request('to'), 'd/m/Y')}}</h5>
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
                                <th class="text-center">Voucher Type</th>
                                <th class="pl-3">Company</th>
                                <th class="pl-3">Description</th>
                                <th class="text-right pr-1">Amount</th>
                            </tr>
                        </thead>

                            <tbody>

                                @foreach ($vouchers as $voucher)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $voucher->date }}</td>
                                        <td class="text-center">{{ $voucher->invoice_no }}</td>
                                        <td class="text-center">{{ $voucher->voucher_type }}</td>
                                        <td class="pl-3">{{ optional($voucher->company)->name }}</td>
                                        <td class="pl-3">{{ $voucher->description }}</td>
                                        <td class="text-right pr-1">{{ number_format($voucher->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="6">Total:</th>
                                <th class="text-right pr-1">{{ number_format($vouchers->sum('amount'), 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection


