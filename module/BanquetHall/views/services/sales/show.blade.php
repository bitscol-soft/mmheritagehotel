@extends('layouts.master')
@section('title', 'Hotel Service Invoice')

@section('page-header')
    <i class="fa fa-gear"></i> Hotel Service Invoice
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
    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header hidden-print">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    @if (hasPermission('service.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('hotelservice.service-sales.create') }}">
                                <i class="fa fa-plus"></i>
                                Create New
                            </a>
                            <a href="{{ route('hotelservice.service-sales.index') }}">
                                <i class="fa fa-list"></i>
                                All Sales
                            </a>
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
                                <div id="customer_info" style="padding: 0 10px;">
                                    <div class="row">


                                        <div class="company-info text-center">
                                            <h4>{{ optional($invoice->company)->name }}</h4>
                                            <p>{{ optional($invoice->company)->head_office }}</p>
                                            <p>{{ optional($invoice->company)->phone_number }},
                                                {{ optional($invoice->company)->email }}</p>
                                        </div>
                                        <hr>
                                        <div class="customerInfo" style="width: 60%;float: left; ">

                                            <h5><b><u>Guest's Information : </u></b></h5>
                                            <p class="patient"><b>ID : </b>
                                                {{ optional($invoice->hotel_guest)->id }}
                                            </p>

                                            <p class="patient"><b>Name : </b>
                                                {{ optional($invoice->hotel_guest)->name ?? ($invoice->guest_name ?? '') }}
                                            </p>
                                            <p class="patient"><b>Address : </b>
                                                {{ optional($invoice->hotel_guest)->address }} &nbsp;
                                                &nbsp; <b>Nationality :</b>
                                                {{ optional(optional($invoice->hotel_guest)->country)->name }}
                                            </p>
                                            <p class="patient"><b>Mobile : </b>
                                                {{ optional($invoice->hotel_guest)->phone_no }}
                                            </p>


                                        </div>
                                        <div class="invoiceInfo" style="width: 40%;float: left;margin-top: 5px;">
                                            <table class="table table-bordered" style="border: none !important;">
                                                <tr>
                                                    <th width="50%" style="border: none !important;"> Invoice No : </th>
                                                    <th style="border: none !important;">{{ $invoice->invoice_no }}</th>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Invoice Date : </td>
                                                    <td style="border: none !important;">
                                                        {{ $invoice->invoice_date }}</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="invoice-content">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="border: none !important;">
                                            <thead>
                                                <tr>
                                                    <th width="5%">SL</th>
                                                    <th>Name of Service</th>
                                                    <th>Item Price</th>
                                                    <th>Quantity</th>
                                                    <th width="25%" style="text-align:right">Amount (&#x09F3;)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoice->saleItems as $item)
                                                    <tr>
                                                        <td>{{ ++$loop->index }}</td>
                                                        <td>{{ optional($item->service)->name }}</td>
                                                        {{-- &#x09F3; for taka symbol --}}
                                                        <td>{{ $item->price }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($item->quantity * $item->price, 2) }}
                                                            &#x09F3;
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="4" style="text-align: right; border: none !important;">
                                                        Subtotal </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ $invoice->subtotal }}
                                                        &#x09F3;</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align: right; border: none !important;">
                                                        Discount </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ $invoice->discount }}
                                                        &#x09F3;</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align: right; border: none !important;">
                                                        Total </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($invoice->payable_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align: right; border: none !important;">
                                                        Paid </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ $invoice->paid_amount }} &#x09F3;</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" style="text-align: right; border: none !important;">Due
                                                    </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($invoice->payable_amount - $invoice->paid_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <h5 style="font-weight: 700;">Amount Paid :
                                                <span>
                                                    {{ BDT($invoice->paid_amount) }}
                                                </span>
                                            </h5>
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
                                        <div class="company_sign" style="width: 33%; float: left;">
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">
                                                &nbsp;</h5>
                                            <h5
                                                style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">
                                                Prepared By <br>{{ optional($invoice->user)->name }}</h5>
                                        </div>
                                    </div>
                                    <div class="copyright-section">

                                        <p class="text-center mt-30"><i>Treatment to the highest accuracy & excellence in
                                                education is
                                                our motto</i></p>
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
