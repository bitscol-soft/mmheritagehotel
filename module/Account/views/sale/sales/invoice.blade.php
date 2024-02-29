@extends('layouts.master')
@section('title', 'Sale Invoice')
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

        .heading {
            margin-top: -55px !important;
        }

        .invoice-logo {
            width: 150px !important;
            height: 40px !important;
            margin-top: 20px !important;
        }
    }

    /* @page {
        margin: 1in;
    } */

    .invoice-logo {
        margin-top: 20px !important;
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

    .invoice-logo {
        width: 200px;
        height: 55px;
    }

</style>
@endpush

@section('content')
    <div class="row heading">
        <div class="col-xs-4">
            @if(file_exists('uploads/company/'. optional($sale->company)->logo))
                <img class="invoice-logo" src="{{ asset('uploads/company/'. optional($sale->company)->logo) }}" alt="Logo">
            @endif
        </div>
        <div class="col-xs-4 text-center">
            <h3 style="line-height: 15px !important; font-weight: 600 !important; color: #000000 !important">{{ optional($sale->company)->name ?? '' }}</h3>
            <span>{{ optional($sale->company)->head_office }}</span><br>
            <span><strong>Email: </strong>{{ optional($sale->company)->email }}</span><br>
            <span><strong>Phone: </strong>{{ optional($sale->company)->phone_number }}</span>
        </div>
        <div class="col-xs-4"></div>
    </div>

    <div class="row mb-3">
        <div class="column-a" style="text-align: left">
            Bill To
            <address>
                <strong>{{ optional($sale->customer)->name }}</strong><br>
                <span>{{ optional($sale->customer)->address }}</span>
                <span>{{ optional($sale->customer)->mobile }}</span>
                <span>{{ optional($sale->customer)->email }}</span>
            </address>
        </div>
        <div class="column-a" style="text-align: right">
            <span class="text-secondary">Invoice No:</span>
            {{ $sale->invoice_no }}<br>
            <span class="text-secondary">Date :</span>
            {{ $sale->date }}
        </div>
    </div>



    <table class="table table-bordered table-sm border-none" style="border: 0 !important; width: 100% !important;">
        <tbody style="background-color: #7592A5 !important; color: #ffffff">
        <tr>
            <th width="5%">Sl</th>
            <th width="55%">Product</th>
            <th width="10%">Unit</th>
            <th width="10%" class="text-center">Quantity</th>
            <th width="10%" class="text-right">Price</th>
            <th width="10%" class="text-right">Amount</th>
        </tr>
        </tbody>

        <tbody>
        @foreach($sale->details ?? [] as $details)
           
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    <p>{{ optional($details->product)->name }}</p>
                    <p>{{ $details->description }}</p>
                </td>
                <td>
                    {{ optional(optional($details->product)->unit)->name }}
                </td>
                <td class="text-center">
                    {{ $details->quantity }}
                </td>
                <td class="text-right">
                    {{ $details->price }}
                </td>
                <td class="text-right">
                    {{ $details->amount }}
                </td>
            </tr>
        @endforeach
        </tbody>

        <tfoot style="font-weight: bold !important;" class="text-right">
            <tr>
                <th style="border: 0 !important; padding: 0 !important;" colspan="3"></th>
                <th class="text-right" style="border: 0 !important; padding: 0 !important;" colspan="2">Discount Amount :</th>
                <th class="text-right" style="border: 0 !important; padding: 0 !important; padding-right: 8px !important;">{{ $sale->discount_amount }}</th>
            </tr>
            <tr>
                <th style="border: 0 !important; padding: 0 !important;" colspan="3"></th>
                <th class="text-right" style="border: 0 !important; padding: 0 !important;" colspan="2">Receivable Amount :</th>
                <th class="text-right" style="border: 0 !important; padding: 0 !important; padding-right: 8px !important;">{{ $sale->total_amount }}</th>
            </tr>
            <tr>
                <th style="border: 0 !important; padding: 0 !important;" colspan="3"></th>
                <th class="text-right" style="border: 0 !important; padding: 0 !important;" colspan="2">Paid Amount :</th>
                <th class="text-right" style="border: 0 !important; padding: 0 !important; padding-right: 8px !important;">{{ $sale->paid_amount }}</th>
            </tr>
            <tr>
                <th style="border: 0 !important; padding: 0 !important;" colspan="3"></th>
                <th class="text-right" style="border: 0 !important; padding: 0 !important;" colspan="2">Due Amount :</th>
                <th class="text-right" style="border: 0 !important; padding: 0 !important; padding-right: 8px !important;">{{ $sale->due_amount }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-5 mb-5">
        <div class="hidden-print" style="float: right; padding-right: 10px !important;">
            <div class="btn-group btn-group-xs">
                <a class="btn btn-primary btn-xs" href="javascript:window.print();"><i class="fa fa-print"></i> Print</a>
                <a class="btn btn-danger btn-xs" href="{{ route('acc-sales.index') }}"><i class=" fa fa-backward"></i> Back To List</a>
            </div>
        </div>
    </div>

    <div class="row mt-5 mb-5 signature-row">
        <div class="column" style="text-align: left">
            Customer <br><b>{{ optional($sale->customer)->name }} </b><br>
            <hr>
            Signature and Date
        </div>
        <div class="column">
        </div>
        <div class="column" style="text-align: right">
            Issued By <br><b>{{ optional($sale->company)->name }} </b> <br>
            <hr>
            Signature and Date
        </div>
    </div>
@endsection
