@extends('layouts.master')

@section('title', 'Product Damage Invoice')

@push('style')
    <style type="text/css">
        @media print {
            .d-none {
                display: block !important;
            }

            .d-print {
                display: block !important;
            }

            .signature-row {
                display: unset !important; ;
            }
        }

        @page {
            margin: 1in;
        }

        .d-print {
            display: none;
        }

        .signature-row {
            display: none;
        }

        .note-bar {
            border-left: 5px solid #f2f2f2;
            min-height: 35px;
            line-height: 15px;
            width: 60%;
            margin-top: -190px;
        }

        .note-bar p {
            padding-top: 1px;
            margin-left: 10px !important;
        }

        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 33.33%;
            padding: 10px;
        }

        .column-a {
            float: left;
            width: 50%;
            padding: 10px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

    </style>
@endpush

@section('content')
    <main class="app-content">
        <div class="row" style="display: inline">
            <div class="col-md-12 text-center">
                <h1><strong>{{ optional($damage->company)->name }}</strong></h1>
                <span>{{ optional($damage->company)->head_office }}</span><br>
                <span><strong>Email: </strong>{{ optional($damage->company)->email }}</span><br>
                <span><strong>Phone: </strong>{{ optional($damage->company)->phone_number }}</span>
            </div>
        </div>

        <div class="row" id="printDiv">
            <div class="col-md-12">
                <div class="tile" style="border: 0 !important;">

                    <div class="row mb-1">
                        <div class="column-a" style="text-align: left">
                            <span class="text-secondary">Invoice No:</span>
                            {{ $damage->invoice_no }}<br>
                            <span class="text-secondary">Date :</span>
                            {{ $damage->date }}
                        </div>
                    </div>



                    <table class="table table-bordered table-sm border-none" style="border: 0 !important; width: 100% !important;">
                        <tbody style="background-color: #7592A5 !important; color: #ffffff">
                            <tr>
                                <th width="5%">Sl</th>
                                <th width="55%">Product Name</th>
                                <th width="10%">Unit</th>
                                <th width="10%" class="text-center">Quantity</th>
                                <th width="10%" class="text-right">Price</th>
                                <th width="10%" class="text-right">Amount</th>
                            </tr>
                        </tbody>

                        <tbody>
                            @foreach($damage->details ?? [] as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ optional($detail->product)->name }}</td>
                                    <td>{{ optional(optional($detail->product)->unit)->name }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-right">{{ $detail->price }}</td>
                                    <td class="text-right">{{ $detail->subtotal }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <th class="text-center">{{ $damage->details->sum('quantity') }}</th>
                                <th></th>
                                <th class="text-right">{{ $damage->details->sum('subtotal') }}</th>
                            </tr>
                        </tfoot>
                    </table>







                    <div class="row mt-5 mb-5" style="margin-top: 20px">
                        <div class="hidden-print" style="float: right; padding-right: 10px !important;">
                            <div class="btn-group btn-group-xs">
                                <a class="btn btn-primary btn-xs" href="javascript:window.print();">
                                    <i class="fa fa-print"></i> Print
                                </a>
                                <a class="btn btn-danger btn-xs" href="{{ route('damages.index') }}">
                                    <i class=" fa fa-backward"></i> 
                                    Back To List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

