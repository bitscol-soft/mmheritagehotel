@extends('layouts.master')
@section('title', 'Journal Voucher Invoice')
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

            .invoice-logo {
                width: 150px !important;
                height: 40px !important;
                margin-top: 15px !important;
            }

            .heading {
                margin-top: -55px !important;
            }

            .in-words {
                width: 100% !important;
                font-size: 10px !important;
            }
        }

        .d-print {
            display: none;
        }

        .signature-row {
            display: none;
            line-height: 0px !important;
            text-align: center;
        }

        .in-words {
            width: 60%;
            margin-top: -68px;
            font-weight: 500
        }

        .note-bar {
            border-left: 5px solid #f2f2f2;
            min-height: 32px;
            line-height: 10px;
            margin-top: 15px;
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
            width: 25%;
            padding: 0px;
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

        .hr {
            width: 60% !important;
            margin: 0 auto !important;
            margin-top: 12px !important;
            margin-bottom: 12px !important;
            border-top: 1.5px solid #3a3a3a !important;
        }
    </style>
@endpush

@section('content')
    <div class="row heading">
        <div class="col-xs-4">
            @if(file_exists('uploads/company/'. optional($voucher->company)->logo))
                <img class="invoice-logo" src="{{ asset('uploads/company/'. optional($voucher->company)->logo) }}" alt="Logo">
            @endif
        </div>
        <div class="col-xs-4 text-center">
            <h3 style="line-height: 15px !important; font-weight: 600 !important; color: #000000 !important">{{ optional($voucher->company)->name ?? '' }}</h3>
            <span>{{ optional($voucher->company)->head_office }}</span><br>
            <span><strong>Email: </strong>{{ optional($voucher->company)->email }}</span><br>
            <span><strong>Phone: </strong>{{ optional($voucher->company)->phone_number }}</span>
        </div>
        <div class="col-xs-4"></div>

        <div class="col-xs-12 text-center">
            <h4 style="color: #0369a1; margin-top: -1px; margin-top: 5px; margin-bottom: -1px"><strong>Journal Voucher</strong></h4>
        </div>
    </div>
    <div class="row">
        <div class="column-a" style="text-align: left">
        </div>
        <div class="column-a" style="text-align: right">
            <span class="text-secondary">Invoice No:</span>
            {{ $voucher->invoice_no }}<br>
            @if(!empty($voucher->reference))
            <span class="text-secondary">Reference:</span>
            {{ $voucher->reference }}<br>
            @endif
            <span class="text-secondary">Date :</span>
            {{ $voucher->date }}
        </div>
    </div>
    <table class="table table-bordered border-none" style="border: 0 !important; width: 100% !important;">
        <tbody style="background-color: #7592A5 !important; color: #ffffff">
            <tr>
                <th width="5%" class="text-center">Sl</th>
                <th width="75%">Account</th>
                <th width="10%" class="text-right">Debit</th>
                <th width="10%" class="text-right">Credit</th>
            </tr>
        </tbody>
        <tbody>
        @foreach($voucher->details ?? [] as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                    {{ optional($item->account)->name }}
                </td>
                <td class="text-right">
                    @if($item->balance_type == 'Debit')
                        {{ number_format($item->amount, 2) }}
                    @endif
                </td>
                <td class="text-right">
                    @if($item->balance_type == 'Credit')
                        {{ number_format($item->amount, 2) }}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        @php
            $totalAmount = $voucher->details->sum('amount') > 0 ? $voucher->details->sum('amount') / 2 : 0;
        @endphp
        <tfoot style="font-weight: bold !important;" class="text-right">
            <tr>
                <th class="text-right" style="border: 0 !important; padding: 0 !important;" colspan="2">Total :</th>
                <th class="text-right" style="border: 0 !important; padding: 0 !important; padding-right: 8px !important;">{{ number_format($totalAmount, 2) }}</th>
                <th class="text-right" style="border: 0 !important; padding: 0 !important; padding-right: 8px !important;">{{ number_format($totalAmount, 2) }}</th>
            </tr>
        </tfoot>
    </table>
    @if(!empty($voucher->description))
        <div class="row my-5">
            <div class="col-md-12">
                <div class="in-words">
                    In words: {{ convert_number($totalAmount) . ' TK Debit & ' . convert_number($totalAmount) . ' TK Credit.' }}
                </div>
                <div class="note-bar">
                    <p><b>Note </b></p>
                    <p>{{ $voucher->description }}</p>
                </div>
            </div>
        </div>
    @endif
    <div class="row mt-5 mb-5">
        <div class="hidden-print" style="float: right; padding-right: 10px !important;">
            <div class="btn-group btn-group-xs">
                <a class="btn btn-primary btn-xs" href="javascript:window.print();"><i class="fa fa-print"></i> Print</a>
                <a class="btn btn-danger btn-xs" href="{{ route('voucher-payments.index') }}"><i class=" fa fa-backward"></i> Back To List</a>
            </div>
        </div>
    </div>
    <div class="row mt-5 mb-5 signature-row">
        <div class="column">
            {{ optional($voucher->created_user)->name }}
            <hr class="hr">
            Prepared By
        </div>
        <div class="column">
            <hr class="hr">
            Accountant By
        </div>
        <div class="column">
            <hr class="hr">
            Approved By
        </div>
        <div class="column">
            <hr class="hr">
            Received By
        </div>
    </div>
@endsection