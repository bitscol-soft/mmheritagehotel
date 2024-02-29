@extends('layout.app')
@section('title', 'Indoor Patient Booking Details')
@push('style')
    {{-- <link href="{{url('/')}}/css/tailwindcss.css" rel="stylesheet"> --}}
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
            border: .4px solid #bfbcbc;
            padding: 4.5px;
        }

        .admitted {
            color: #0cb634;
        }

        .company-info p {
            margin-bottom: 2px;
        }

        .patient {
            margin-bottom: 2px;
        }

        th {
            background: #efefef;
            box-shadow: none;
        }

        @media print {

            #print_body,
            h3,
            h4,
            h5,
            h6 {
                line-height: 8px !important;
                font-size: 10px !important;
                /*padding: 10px;*/
                /*width: 100%;*/
            }

            td {
                padding: 0px 2px !important;
            }

            th {
                font-size: 9px !important;
            }

            .company-info h4 {
                font-weight: bold;
                margin-bottom: 5px;
                font-size: 12px !important;
            }

            .company-info p {
                margin-bottom: 5px;
            }

            @page {
                size: auto;
                height: auto;
                width: auto;
                /* this affects the margin in the printer settings */
                margin: 0px;
            }
        }

    </style>
@endpush
@section('content')
    <div class="invoice">
        <div class="panel panel-default" style="margin-bottom: 0px;">
            <div class="panel-heading no-print" style="height: 48px;">
                <div class="panel-heading-btn pull-right">
                    @can('pharmacy-sales-create')
                        <a href="/inventory-product-sales/create" class="btn btn-sm btn-success">Create New</a>
                    @endcan
                    @can('pharmacy-sales-list')
                        <a href="/inventory-product-sales" class="btn btn-sm btn-success">All Sales</a>
                    @endcan
                    <button onclick="printPage('print_body')" class="btn btn-sm btn-success"><i
                            class="fa
                    fa-print"></i> Print</button>
                </div>
            </div>

        </div>
        <div id="print_body" class="col-lg-12">
            <div id="customer_info" style="padding: 0 10px;">
                <div class="row">
                    <div class="company-info text-center">
                        <h4>{{ $company->company_name }}</h4>
                        <p>{{ $company->address }}</p>
                        <p>{{ $company->mobile_no }}, {{ $company->email }}</p>
                    </div>
                    <hr style="margin: 1px 5px">
                    {{-- <h6 style="width: 100%;text-align: center;margin-top: 15px;"><b style="padding: 10px 20px; --}}
                    {{-- border-radius: 10px;color: #000;border:1px solid #ddd;">Sales Invoice</b></h6> --}}
                    <div class="customerInfo" style="width: 50%;float: left;">
                        {{-- <h5><b><u>Invoice to : </u></b></h5> --}}
                        <p class="patient"><b>Invoice ID : </b>
                            {{                             str_pad($inventoryProductSale->invoice_id, '3', 000, STR_PAD_LEFT) }}
                        </p>
                        {{-- <p class="patient"><b>  Date : </b> {{ $inventoryProductSale->date->format('d-m-Y') --}}
                        {{-- }}</p> --}}
                        <p class="patient"><b>Name : </b>
                            {{                             $inventoryProductSale->patinet->name ?? $inventoryProductSale->patient_name }}
                        </p>
                        {{-- <p class="patient"><b>Age : </b> {{ $inventoryProductPurchase->patient->age }} &nbsp; &nbsp; <b>Sex :</b> {{ sex($inventoryProductPurchase->patient->sex) }}</p> --}}
                        <p class="patient"><b>Mobile : </b>
                            {{ optional($inventoryProductSale->patient)->mobile_number }}</p>
                        <p class="patient"><b>Sales By : </b> {{ $inventoryProductSale->user->name }}</p>
                    </div>
                    <div class="customerInfo" style="width: 50%; float: right;">
                        <p class="pull-right"><b> Date : </b>
                            {{                             $inventoryProductSale->date->format('d-m-Y') }}</p>
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
                                <th width="50%">Product Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th width="25%" style="text-align:right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inventoryProductSale->items as $key => $item)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td class="text-center">{{ number_format($item->sales_price, 2) }}</td>
                                    <td class="text-center">{{ $item->sales_qty }}</td>
                                    <td align="right">{{ number_format($item->item_price, 2) }} &#x09F3;</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="4" style="text-align: right">Discount :</td>
                                <td style="text-align: right">{{ $inventoryProductSale->discount }} &#x09F3;</td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right">Total : </td>
                                <td style="text-align: right">
                                    {{                                     number_format($inventoryProductSale->totalAmount, 2) }}
                                    &#x09F3;
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right">Paid :</td>
                                <td style="text-align: right">{{ number_format($inventoryProductSale->paid_amount, 2) }}
                                    &#x09F3;
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right">Change :</td>
                                <td style="text-align: right">
                                    {{                                     number_format($inventoryProductSale->paid_amount - $inventoryProductSale->paid, 2) }}
                                    &#x09F3;
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right">Due :</td>
                                {{-- <th style="text-align: right">{{number_format($patientBooking->due, 2)}} &#x09F3;</th> --}}
                                <td style="text-align: right">{{ number_format($inventoryProductSale->due, 2) }}
                                    &#x09F3;</td>
                            </tr>
                        </tbody>
                    </table>
                    <h4 class="text-center">THANKS FOR COMING</h4>
                </div>
            </div>

        </div>
        <br>
        <hr>
        <br>
        <div id="second_copy" style="width: 100%;overflow: hidden;">
            <!-- Load First Copy -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.datepicker').datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true,
            changeMonth: true,
            changeYear: true
        });
        $('.datepicker').datepicker('setDate', new Date());
    </script>
    {{-- //Select Ward --}}
    <script>
        $(function() {
            $('select#ward_id').change(function(e) {
                $.getJSON('{{ url('/load-seats') }}', {
                        id: $(this).val()
                    },
                    function(seats) {
                        $('select#seat_id').html(
                            $.map(seats, function(seat) {
                                return '<option value="' + seat.id + '">' + seat
                                    .space_location + '</option>';
                            }).join('')
                        ).trigger('change')
                    }
                )
            })
        })

        $(function() {
            $('select#seat_id').change(function(e) {
                $.getJSON('{{ url('/load-price') }}', {
                        id: $(this).val()
                    },
                    function(prices) {
                        prices.forEach(function(price) {
                            $('input#price').val(price.price)
                        })
                    }
                )
            })
        })
    </script>

    <script>
        $("#patient_name").autocomplete({
            source: function(request, response) {
                $.getJSON('{{ url('/loadPatient') }}', {
                    name: $('#patient_name').val()
                }, function(data) {
                    response($.map(data, function(item) {
                        return {
                            value: item.name,
                            label: item.name,
                            data: item,
                        }
                    }))
                })
            },
            select: function(event, ui) {
                $('#customer_id_no').val(ui.item.data.customer_id);
                $('#patient_age').val(ui.item.data.age);
                $('#mobile_no').val(ui.item.data.mobile_number);
                $('#disease').val(ui.item.data.disease);
                $('#customer_id').val(ui.item.data.id);
                $('#guardian').val(ui.item.data.guardian);
                $('#blood').val(ui.item.data.blood);
                // console.log(ui.item.data);
                $('[name=sex][value=' + ui.item.data.sex + ']').attr('checked', true);
                $('[name=blood][value=' + ui.item.data.blood + ']').attr('selected', true);
            },
        });
    </script>

    <script src="{{ url('custom_js/printThis.js') }}"></script>
    <script type="text/javascript">
        function printPage(id) {
            $('#' + id).printThis({
                importStyle: true
            });
        };
        window.onload = $('#print_body').printThis({
            importStyle: true
        });
    </script>

@endsection
