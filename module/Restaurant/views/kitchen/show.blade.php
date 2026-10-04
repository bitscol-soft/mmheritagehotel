@extends('layouts.master')
@section('title', 'Order Details')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        #print_body {
            background-color: #fff;
            padding: 10px 20px;
            overflow: hidden;
        }

        .company-info {
            color: #000;
        }

        .company-info h3 {
            font-weight: bold;
            margin-bottom: 0;
        }

        .company-info p {
            margin-bottom: 2px;
        }

        .table {
            box-shadow: none !important;
        }

        .table-bordered>thead>tr>th,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>tbody>tr>td,
        .table-bordered>tfoot>tr>td {
            border: .4px solid #fff;
            padding: 4.5px;
        }

        th {
            background: #efefef;
            box-shadow: none;
        }

        .patient {
            margin: 3px;
        }

        @media print {
            .company-info h4 {
                font-weight: bold;
                margin-bottom: 0;
            }

            .company-info p {
                margin-bottom: 2px;
            }

        }
    </style>
@stop

{{-- @dd($invoice) --}}
@section('content')

<x-mm.styles />
<x-mm.page class="mm-invoice-page mm-rst" title="Order details" description="Kitchen copy of the order. Printing outputs the document only.">
    @if (hasPermission('service.view', $slugs))
        <x-slot name="actions">
            {{-- W5.1: the print button is now provided by <x-mm.print-sheet>'s footer
                 (the data-mm-print hook calls window.print()). The old jQuery
                 printPage('print_body') link is removed. The print sheet uses
                 thermal-80mm width via the existing CSS class
                 .mm-print-sheet-thermal-80mm. --}}
        </x-slot>
    @endif

    <x-mm.panel class="tw-p-4">
        <x-mm.print-sheet sheet="thermal-80mm">
        <div class="row">
            <div id="print_body">
                <div id="customer_info" style="padding: 0 10px; margin-bottom: 15px">
                    <div class="row">
                        <div class="customerInfo" style="width: 60%;float: left; ">

                            <p><b>Name : {{ $orders->customer_name ?? '' }}</b>&nbsp;

                            </p>
                            <p class=""><b>Table No : </b>
                                <b>{{ $orders->table_no }}</b>
                            </p>
                        </div>
                        <div class="invoiceInfo" style="width: 40%;float: left;margin-top: 5px;">
                            <table class="table table-bordered" style="border: none !important;">
                                <tr>
                                    <th width="50%" style="border: none !important; "> Invoice No : </th>
                                    <th style="border: none !important; ">
                                        {{ $orders->invoice_no }}
                                    </th>
                                </tr>
                                <tr>
                                    <td style="border: none !important; "> Invoice Date : </td>
                                    <td style="border: none !important; ">
                                        {{ $orders->date }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="invoice-content">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border: none !important;" align="center">
                            <thead>
                                <tr>
                                    <th width="5%">SL</th>
                                    <th>Product Name</th>
                                    {{-- <th>Price</th> --}}
                                    <th>QTY</th>
                                    {{-- <th style="text-align: right">Total (&#x09F3;)</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_amount = 0;
                                @endphp
                                {{-- orders
                                order_items --}}
                                @foreach ($orders->order_items as $key => $item)
                                    @php
                                        $total_amount = +$item->price;
                                    @endphp
                                    <tr>
                                        <td>{{ ++$loop->index }}</td>
                                        <td>{{ optional($item->product)->name }}</td>
                                        {{-- &#x09F3; for taka symbol --}}
                                        {{-- <td>{{ number_format($item->price, 2) }}</td> --}}
                                        <td class="text-left">{{ $item->qty }}</td>
                                        {{-- <td class="text-right">
                                            {{ number_format($item->price * $item->qty, 2) }} &#x09F3;
                                        </td> --}}
                                    </tr>
                                @endforeach

                            </tbody>
                            {{-- <tr>
                                <td colspan="4" style="text-align: right; border: none !important;">
                                    <strong>Total</strong> :
                                </td>
                                <th style="text-align: right; border: none !important;">
                                    {{ number_format($orders->total_amount, 2) }}
                                    &#x09F3;</th>
                            </tr> --}}
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <h5 style="font-weight: 700;">Status :
                                <span class="label label-sm label-primary">
                                    {{ $orders->order_status }}
                                </span>
                            </h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </x-mm.print-sheet>
    </x-mm.panel>
</x-mm.page>
@endsection

@section('js')
    {{-- W5.1: the printThis.js script and the printPage() function
         are no longer used. The print button is now provided by
         <x-mm.print-sheet>'s footer (data-mm-print hook calls
         window.print()). --}}
@stop
@stop
