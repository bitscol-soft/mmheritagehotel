@extends('layouts.master')


@section('title', 'Purchase Details')



@push('style')
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

        .table {
            box-shadow: none !important;
        }

        .table-bordered>thead>tr>th,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>tbody>tr>td,
        .table-bordered>tfoot>tr>td {
            border: .4px solid #f0d9d9;
            padding: 4.5px;
        }



        .admitted {
            color: #0cb634;
        }

        .company-info p {
            margin-bottom: 2px;
        }

        .patient {
            margin: 3px;
        }

        . {
            /* background: greenyellow; */
            background: #63bee8;
            box-shadow: none;
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }

        @media print {
            .company-info h4 {
                font-weight: bold;
                margin-bottom: 0;
            }

            .company-info p {
                margin-bottom: 2px;
            }

            .widget-header {
                display: none;
            }

            .widget-box {
                border: none !important;
            }

            #price-th {
                width: 13% !important;
            }
        }

    </style>
@endpush

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-invoice-page mm-rst" title="Purchase Details">
        <x-slot name="actions">
            <a href="{{ route('rst.purchases.index') }}" class="btn btn-sm btn-default">
                <i class="ace-icon fa fa-list-alt"></i>
                All Purchases
            </a>

            <a href="javascript:void(0)" onclick="printPage('print_body')" class="btn btn-sm btn-default">
                <i class="fa fa-print"></i> Print
            </a>
        </x-slot>

        <x-mm.panel>
                        <div class="row">

                            <div id="print_body" class="col-xs-12">
                                <div id="customer_info" style="padding: 0 10px;">
                                    <div class="row">


                                        <!-- company info -->
                                        <div class="company-info text-center">
                                            <h4>{{ optional($purchases->company)->name }}</h4>
                                            <p>{{ optional($purchases->company)->head_office }}</p>
                                            <p>{{ optional($purchases->company)->phone_number }},
                                                {{ optional($purchases->company)->email }}</p>
                                        </div>



                                        <!-- panel title -->
                                        <h6 style="width: 100%;text-align: center;margin-top: 15px;">
                                            <b
                                                style="padding: 10px 20px; border-radius: 10px; color: #000; border:1px solid #ddd;">
                                                Purchase Invoice
                                            </b>
                                        </h6>


                                        <hr>



                                        <!-- Supplier info -->
                                        <div class="customerInfo" style="width: 50%;float: left;">
                                            <h5><b><u>Supplier's Information : </u></b></h5>
                                            <p class="patient"><b>ID : </b>
                                                {{ optional($purchases->supplier)->id }}</p>
                                            <p class="patient"><b>Name : </b>
                                                {{ optional($purchases->supplier)->name }}</p>
                                            <p class="patient"><b>Email : </b>
                                                {{ optional($purchases->supplier)->email }}</p>
                                            <p class="patient"><b>Mobile : </b>
                                                {{ optional($purchases->supplier)->phone }}</p>
                                        </div>






                                        <!-- invoice info -->
                                        <div class="invoiceInfo" style="width: 50%; float: left;margin-top: 5px;">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td> Invoice ID : </td>
                                                    <td>{{ $purchases->challan_no ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td> Date : </td>
                                                    <td>{{ $purchases->date }}</td>
                                                </tr>
                                            </table>
                                        </div>



                                    </div>
                                </div>





                                <br>

                                <div class="invoice-content">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="1%">SL</th>
                                                    <th width="25%">Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Sales Price</th>
                                                    <th width="15%" style="text-align:right">Total (&#x09F3;)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($purchases->purchase_details as $key => $item)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $item->product->name }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ optional($item->unit)->name }}</td>
                                                        <td>{{ $item->item_price }}</td>
                                                        <td align="right">
                                                            {{ number_format($item->subtotal, 2) }}
                                                            &#x09F3;
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <td colspan="5" style="text-align: right">Discount =</td>
                                                    <th style="text-align: right">{{ $purchases->discount }} &#x09F3;
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td colspan="5" style="text-align: right">Total VAT =</td>
                                                    <th style="text-align: right">{{ $purchases->total_vat ?? 0 }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right">Total = </td>
                                                    <th style="text-align: right">
                                                        {{ number_format($purchases->payable_amount, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right">Paid =</td>
                                                    <th style="text-align: right">
                                                        {{ number_format($purchases->paid_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" style="text-align: right">Due =</td>
                                                    <th style="text-align: right">
                                                        {{ number_format($purchases->due_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>Amount Paid : <span> {{ BDT($purchases->paid_amount) . '' }}</span></h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="print-footer"
                                    style="margin-top: 40px;overflow: hidden;width: 100%;padding: 0 10px;">
                                    {{-- <div class="sign" style="width: 100%; overflow: hidden;">
                                        <div class="company_sign" style="width: 33%; float: left;">
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">&nbsp;</h5>
                                            <h5 style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">Received By</h5>
                                        </div>
                                        <div class="company_sign" style="width: 33%; float: left;">
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">{{$purchases->user->name}}</h5>
                                            <h5 style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">Prepared By</h5>
                                        </div>

                                        <div class="company_sign" style="width: 33%; float: left;">
                                            <h5 style="width:50%; margin: 0 auto; padding: 10px 0;text-align: center;">&nbsp;</h5>
                                            <h5 style="width:50%;margin: 0 auto;border-top: 1px solid #000;padding: 10px 0;text-align: center;">Authorized By</h5>
                                        </div>
                                    </div> --}}
                                    <div class="copyright" style="padding: 0px !important;">
                                        <br>
                                        <div class="copyright-section">
                                            <p class="pull-left">NB: This is system generated report.</p>
                                            <p class="design_band pull-right">Powered By: <a href="#"> Banglafire Software
                                                    LTD.</a></p>
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <br>
                            <div id="second_copy" style="width: 100%;overflow: hidden;">
                                <!-- Load First Copy -->
                            </div>
                        </div>
        </x-mm.panel>
    </x-mm.page>
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
