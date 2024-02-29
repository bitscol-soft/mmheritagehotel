@extends('layouts.master')
@section('title', 'Bar Sale Invoice')

@section('page-header')
    <i class="fa fa-info-circle"></i> Bar Sale Invoice
@stop

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



        .table-border>thead, .table-border>tbody, .table-border>thead>tr>th,.table-border>tbody>tr>td{
            border: 1px solid rgb(231, 219, 219) !important;
        }

        table thead th {
            background-color: #4d8cb3;
            color: #fff;
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

@section('content')

    @php

    @endphp


    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header hidden-print">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    @if (hasPermission('service.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="#" onclick="printPage('print_body')">
                                <i class="fa fa-print"></i>
                                Print
                            </a>
                        </span>
                    @endif

                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div class="row">
                            <div id="print_body">
                                <div id="customer_info" style="padding: 0 10px; margin-bottom: 15px">
                                    <div class="row">
                                        <x-company-info :company="optional($sale->company)" />
                                        <hr>
                                        <div class="customerInfo" style="width: 60%;float: left; ">

                                            {{-- <h5><b><u>Guest's Information : </u></b></h5> --}}

                                            <p class="patient"><b>Invoice No : </b>{{ $sale->invoice_no }}</p>
                                            <p class="patient"><b>Name :</b>&nbsp;{{ $sale->guest_name }}</p>
                                            <p class="patient">
                                                <b>Sales By : </b>
                                                {{ optional($sale->user)->name }}
                                            </p>
                                        </div>
                                        <div class="invoiceInfo" style="width: 40%;float: left;margin-top: 5px;">
                                            <table class="table table-bordered" style="border: none !important;">
                                                <tr>
                                                    <th width="50%" style="border: none !important; padding-top: 0; padding-bottom: 0;"> Invoice No : </th>
                                                    <th style="border: none !important; padding-top: 0; padding-bottom: 0;">INV-{{ $sale->invoice_no }}</th>
                                                </tr>
                                                <tr>
                                                    <td style="padding-top: 0; padding-bottom: 0; border: none !important;"> Invoice Date : </td>
                                                    <td style="padding-top: 0; padding-bottom: 0; border: none !important;">
                                                        {{ $sale->date }}</td>
                                                </tr>
                                                {{-- <tr>
                                                    <td style="border: none !important;"> Vat Number : </td>
                                                    <td style="border: none !important;">
                                                        {{ $vat_number }}</td>
                                                </tr> --}}
                                                {{-- <tr>
                                                    <td style="border: none !important;"> Vat Amount : </td>
                                                    <td style="border: none !important;">
                                                        {{ $sale->vat_amount }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Service Amount : </td>
                                                    <td style="border: none !important;">
                                                        {{ $sale->service_amount }}</td>
                                                </tr> --}}
                                                <tr>
                                                    <td style="border: none !important; padding-top: 0; padding-bottom: 0;"> Payment Way : </td>
                                                    <td style="border: none !important; padding-top: 0; padding-bottom: 0;">
                                                        @foreach ($sale->transaction_ledgers ?? [] as $item)
                                                            {{ optional($item->account)->name }}
                                                            @if (!$loop->last),
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="invoice-content">
                                    <div class="table-responsive">
                                        <table class="table table-border">
                                            <thead>
                                                <tr>
                                                    <th width="5%">SL</th>
                                                    <th>Product Name</th>
                                                    <th>Price</th>
                                                    <th>C.P</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th style="text-align: right">Total (&#x09F3;)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total_amount = 0;
                                                    $total_cp = 0;
                                                @endphp
                                                @foreach ($sale->items as $key => $item)
                                                    @php
                                                        $total_amount += $item->item_price;
                                                        $total_cp += $item->item_discount;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ ++$loop->index }}</td>
                                                        <td>{{ optional($item->product)->name }}</td>
                                                        <td>{{ number_format($item->sales_price, 2) }}</td>
                                                        <td>{{ number_format($item->item_discount, 2) }}</td>
                                                        <td class="text-center">
                                                            {{ $item->quantity }}
                                                            <label class="">{{ optional($item->unit)->name }}</label>
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($item->item_price, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>


                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" style="text-align: right; border: none !important;">
                                                        <strong>Sub Total</strong><span class="currency-sign"></span> : </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_amount, 2) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right; border: none !important;">
                                                        <strong>Discount</strong><span class="currency-sign"></span> : </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ $sale->discount }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right; border: none !important;">
                                                        <strong>Total C.P</strong><span class="currency-sign"></span> : </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ $total_cp }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right; border: none !important;">
                                                        <strong>Vat Amount</strong><span class="currency-sign"></span> : </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ $sale->vat_amount }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right; border: none !important;">
                                                        <strong>Service Charge</strong><span class="currency-sign"></span> : </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ $sale->service_amount }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td colspan="5" style="text-align: right; border: none !important;">
                                                        <strong>Total</strong><span class="currency-sign"></span> :</td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($sale->payable_amount, 2) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right; border: none !important;">
                                                        <strong>Paid</strong><span class="currency-sign"></span> : </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($sale->paid_amount, 2) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right; border: none !important;">
                                                        <strong>Due</strong><span class="currency-sign"></span> : </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ $sale->payable_amount > $sale->paid_amount ? number_format(( $sale->payable_amount - $sale->paid_amount ), 2) : 0 }}

                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right; border: none !important;">
                                                        <strong>Change</strong> : </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($sale->change_amount, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <h5 style="font-weight: 700;">Amount Paid :
                                                {{ number_format($sale->paid_amount, 2 ?? 0) }}
                                                &#x09F3;
                                            </h5>

                                            @foreach ($account_types as $id => $account_type)
                                                    @php
                                                    $amount = getTotalPaymentTypeInvoice($id, $sale->id, "Bar Sale");
                                                    @endphp
                                                @if (!empty($amount))
                                                            <p style="font-weight: 700;"><b> {{ $account_type }} Sale : {{ $amount }} </b> &#x09F3;</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="print-footer"
                                    style="margin-top: 40px;overflow: hidden;width: 100%;padding: 0 10px;">
                                    <div class="sign" style="width: 100%; overflow: hidden;">
                                        <div class="company_sign" style="width: 33%; float: left;">
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">
                                                &nbsp;</h5>
                                            <h5
                                                style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">
                                                Received By</h5>
                                        </div>
                                        <div class="company_sign" style="width: 33%; float: left;">
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">
                                                &nbsp;</h5>
                                            <h5
                                                style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">
                                                Authorized By</h5>
                                        </div>
                                        <div class="company_sign" style="width: 33%; float: left; text-align:center">

                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">
                                                &nbsp;
                                            </h5>
                                            {{ optional($sale->user)->name }}
                                            <h5 style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">
                                                Prepared By
                                            </h5>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/printThis.js') }}"></script>
    <script type="text/javascript">
        function printPage(id) {
            $('#' + id).printThis({
                importStyle: true
            });
        };
        window.onreadystatechange = $('#print_body').printThis({
            importStyle: true
        });
    </script>
@stop
