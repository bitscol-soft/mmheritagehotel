@extends('layouts.master')
@section('title', 'Sale Return Details')

@section('page-header')
    <i class="fa fa-bars"></i> Sale Return Details
@stop

@section('css')
    <style>
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

    </style>
@stop


@section('content')
    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">


                <!-- Widget Header -->
                <div class="widget-header hidden-print">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    @if (hasPermission('pharmacy.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('rst.sales.create') }}">
                                <i class="fa fa-plus-circle"></i> Create
                            </a>
                            <a href="{{ route('rst.sales.index') }}">
                                <i class="fa fa-list-alt"></i> Return List
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
                                                Sale Return Invoice
                                            </b>
                                        </h6>



                                        <hr>


                                        <!-- Customer Info Right side -->
                                        <div class="customerInfo" style="width: 50%;float: left;">

                                            <p class="patient"><b>Invoice No : </b>
                                                {{ $sale->invoice_no }}
                                            </p>

                                            <p class="patient"><b>Name : </b>
                                                {{ optional($sale->guest)->name ?? $sale->guest_name }}
                                            </p>
                                            <p class="patient"><b>Mobile : </b>
                                                {{ optional($sale->guest)->mobile_number }}
                                            </p>


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
                                    <div class="table-responsive">



                                        <!-- Table -->
                                        <table class="table table-bordered">

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
                                                    <td style="text-align: right">{{ number_format($sale->discount,2) }} &#x09F3;</td>
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
                                                    <td colspan="4" class="no-border">
                                                        Return :
                                                    </td>
                                                    <td style="text-align: right">
                                                        {{ number_format($sale->return_amount, 2) }}
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

                                        </table>
                                        <br>
                                        <br>

                                        <div class="print-footer"
                                            style="margin-top: 40px;overflow: hidden;width: 100%;padding: 0 10px;">

                                            <div class="copyright" style="padding: 0px !important;">
                                                <br>
                                                <div class="copyright-section">
                                                    <p class="pull-left">NB: This is system generated report.</p>
                                                    <p class="design_band pull-right">Powered By: <a href="#"> Smart
                                                            Software LTD.</a>
                                                    </p>
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
