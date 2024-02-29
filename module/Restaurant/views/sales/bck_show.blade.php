@extends('layouts.master')
@section('title', 'Sale Details')

@section('page-header')
    <i class="fa fa-bars"></i> Sale Details
@stop

@section('css')
    <style>
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
        @media print {

            .widget-box {
                border: none !important
            }

            .no-border {
                border-left: 1px solid white !important;
                border-bottom: 1px solid white !important;
                text-align: right;
            }
        }

        .no-border {
            border-left: 1px solid white !important;
            border-bottom: 1px solid white !important;
            text-align: right;
        }
        .bg-gray{background-color: #efefef}

    </style>
@stop


@section('content')

<?php
$total_amount = 0;
?>

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <!-- Widget Header -->
                <div class="widget-header hidden-print">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    @if (hasPermission('pharmacy.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('rst.sales.create') }}">
                                <i class="fa fa-plus-circle"></i> Add New Sale
                            </a>
                            <a href="{{ route('rst.sales.index') }}">
                                <i class="fa fa-list-alt"></i> Sale List
                            </a>

                            <a href="javascript:void(0)" onclick="printPage('print_body')">
                                <i class="fa fa-print"></i>
                                Print
                            </a>
                        </span>
                    @endif

                </div>



                <!-- Widget Body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Alert Message -->
                        @include('partials._alert_message')

                        <div class="row">

                            <!-- Print This area -->
                            <div id="print_body" class="col-lg-12">



                                <!-- Invoice Header -->
                                <div id="customer_info" style="padding: 0 10px;margin-bottom:20px">
                                    <div class="row">

                                        <!-- Company Info -->
                                        <div class="company-info text-center">
                                            <h4>{{ optional($sale->company)->name }}</h4>
                                            <p>{{ optional($sale->company)->head_office }}</p>
                                            <p>{{ optional($sale->company)->phone_number }},
                                                {{ optional($sale->company)->email }}</p>
                                        </div>

                                        <!-- panel title -->
                                        <h6 style="width: 100%;text-align: center;margin-top: 15px;">
                                            <b
                                                style="padding: 10px 20px; border-radius: 10px; color: #000; border:1px solid #ddd;">
                                                Sale Invoice
                                            </b>
                                        </h6>



                                        <hr>


                                        <!-- Customer Info Right side -->
                                        <div class="customerInfo" style="width: 50%;float: left;">

                                            <p class="patient"><b>Invoice No : </b>
                                                {{ $sale->invoice_no }}
                                            </p>

                                            <p class="patient"><b>Name : </b>
                                                {{ optional($sale->guestInfo)->name ?? '' }}
                                            </p>
                                            <p class="patient"><b>Mobile : </b>
                                                {{ optional($sale->guestInfo)->phone_no }}
                                            </p>
                                            @if (isset($sale->patient_ward))
                                                <p class="patient">
                                                    <b>Ward : </b>
                                                    {{ $sale->patient_ward }}
                                                </p>
                                            @endif
                                            @if (isset($sale->patient_cabin))
                                                <p class="patient">
                                                    <b>Cabin : </b>
                                                    {{ $sale->patient_cabin }}
                                                </p>
                                            @endif
                                            <p class="patient">
                                                <b>Sales By : </b>
                                                {{ optional($sale->user)->name }}
                                            </p>
                                        </div>

                                        <!-- Customer Info Left side -->
                                        <div class="customerInfo" style="width: 50%; float: right;">
                                            <p class="pull-right"><b> Date : </b>
                                                {{ $sale->date->format('d-m-Y') }}</p>
                                        </div>
                                    </div>
                                </div>



                                <!-- Invoice Content -->
                                <div class="invoice-content">
                                    <div>



                                        <!-- Table -->
                                        {{-- <table class="table table-bordered">

                                            <!-- Table Header -->
                                            <thead>
                                                <tr>
                                                    <th width="1%">SL</th>
                                                    <th width="50%">Product Name</th>
                                                    <th class="text-center">Price</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th width="25%" style="text-align:right">Total</th>
                                                </tr>
                                            </thead>

                                            <!-- Table Body -->
                                            <tbody>
                                                @foreach ($sale->items as $key => $item)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ optional($item->product)->name }}</td>
                                                        <td class="text-center">
                                                            {{ number_format($item->sales_price, 2) }}
                                                        </td>
                                                        <td class="text-center">{{ $item->quantity }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($item->item_price, 2) }} &#x09F3;
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <td colspan="4" class="no-border">
                                                        Discount :</td>
                                                    <td style="text-align: right">{{ $sale->discount }} &#x09F3;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="no-border">
                                                        Total : </td>
                                                    <td style="text-align: right">
                                                        {{ number_format($sale->discount, 2) }}
                                                        &#x09F3;
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="no-border">Paid
                                                        :</td>
                                                    <td style="text-align: right">
                                                        {{ number_format($sale->paid_amount, 2) }}
                                                        &#x09F3;
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td colspan="4" class="no-border">Due
                                                        :</td>
                                                    <td style="text-align: right">
                                                        {{ number_format($sale->due_amount, 2) }}
                                                        &#x09F3;
                                                    </td>
                                                </tr>
                                            </tbody>

                                        </table> --}}

                                        {{-- Another Table --}}
                                        <table class="table table-bordered" style="border: none !important;">
                                            <thead>
                                                <tr>
                                                    <th width="5%">SL</th>
                                                    <th>Product Name</th>
                                                    <th>Price (&#x09F3;)</th>
                                                    <th>Qty </th>
                                                    <th width="25%" style="text-align:right">Total (&#x09F3;)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sale->items as $key => $item)

                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ optional($item->product)->name }}</td>
                                                        <td>
                                                            {{ number_format($item->sales_price, 2) }}
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($item->item_price, 2) }} &#x09F3;
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <td colspan="4" class="no-border">
                                                        Discount :</td>
                                                    <th style="text-align: right; border: none !important;">{{ $sale->discount }} &#x09F3;</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="no-border">
                                                        Total : </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_amount, 2) }}
                                                        &#x09F3;
                                                    </>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="no-border">Paid
                                                        :</td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($sale->paid_amount, 2) }}
                                                        &#x09F3;
                                                    </>
                                                </tr>

                                                <tr>
                                                    <td colspan="4" class="no-border">Due
                                                        :</td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($sale->due_amount, 2) }}
                                                        &#x09F3;
                                                    </>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">

                                                <h5 style="font-weight: 700;">Amount Paid :
                                                    {{-- <span>
                                                        {{ number_format($booking->advanced_payment, 2 ?? 0 ) }}&#x09F3;
                                                    </span> --}}
                                                </h5>
                                            </div>
                                        </div>


                                        <div class="print-footer"
                                            style="margin-top: 40px;overflow: hidden;width: 100%;padding: 0 10px;">
                                            <div class="sign" style="width: 100%; overflow: hidden;">
                                                <div class="company_sign" style="width: 33%; float: left;">
                                                    <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">&nbsp;</h5>
                                                    <h5 style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">Received By</h5>
                                                </div>
                                                {{-- <div class="company_sign" style="width: 33%; float: left;">
                                                    <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;"></h5>
                                                    <h5 style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">Prepared By</h5>
                                                </div> --}}
                                                <div class="company_sign" style="width: 33%; float: left;">
                                                    <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">&nbsp;</h5>
                                                    <h5 style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">Received By</h5>
                                                </div>
                                                <div class="company_sign" style="width: 33%; float: left;">
                                                    <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">&nbsp;</h5>
                                                    <h5 style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">Authorized By</h5>
                                                </div>
                                            </div>
                                            <div class="copyright" style="padding: 0px !important;">
                                                <br>
                                                <div class="copyright-section">
                                                    <p class="pull-left">NB: This is system generated report.</p>
                                                    <p class="design_band pull-right">Powered By: <a href="#"> Smart
                                                            Software LTD.</a></p>
                                                </div>
                                            </div>
                                            <br>
                                        </div>



                                        {{-- <h4 class="text-center">THANKS FOR COMING</h4> --}}
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')


    <script src="{{ url('assets/custom_js/printThis.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                print()
            }, 5000);
        })




        function printPage(id) {
            $('#' + id).printThis({
                importStyle: true
            });
        };
    </script>

@endsection
