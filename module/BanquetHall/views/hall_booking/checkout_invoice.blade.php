@extends('layouts.master')
@section('title', 'Service Invoice')

@section('page-header')
    <i class="fa fa-info-circle"></i> Booking Invoice
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

@section('content')

    @php
        $sub_total = $booking->sub_total;
        $adv_amount = $booking->advanced_payment;
        $vat = optional($booking->getVat)->hotel_vat;
        $vat_amount = ($sub_total / 100) * $vat;
        $total_amount = $sub_total + $vat_amount;
        $current_due = $total_amount - $adv_amount;
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
                                <div id="customer_info" style="padding: 0 10px;">
                                    <div class="row">
                                        <x-company-info :company="$company" />
                                        <hr>
                                        <div class="customerInfo" style="width: 60%;float: left; ">

                                            <h5><b><u>Guest's Information : </u></b></h5>
                                            <p class="patient"><b>Name : </b>{{ $booking->guestInfo->name }}</p>
                                            <p><b>Room :</b>{{ $booking->bookingDetail->roomCategory->name }} -
                                                {{ $booking->bookingDetail->roomNumber->room_number }}</p>
                                            <p class="patient"><b>Address : </b>{{ $booking->guestInfo->address }}
                                            </p>
                                            <p><b>Nationality :</b>{{ $booking->guestInfo->country->name }}</p>
                                            <p class="patient"><b>Mobile : </b>{{ $booking->guestInfo->phone_no }}
                                            </p>
                                        </div>
                                        <div class="invoiceInfo" style="width: 40%;float: left;margin-top: 5px;">
                                            <table class="table table-bordered" style="border: none !important;">
                                                <tr>
                                                    <th width="50%" style="border: none !important;"> Booking No : </th>
                                                    <th style="border: none !important;">
                                                        BK-{{ $booking->booking_number }}</th>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Booking Date : </td>
                                                    <td style="border: none !important;">{{ $booking->booking_date }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Check IN Date : </td>
                                                    <td style="border: none !important;">{{ $booking->check_in_date }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Check out Date : </td>
                                                    <td style="border: none !important;">{{ $booking->check_out_date }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Payment Type : </td>
                                                    <td style="border: none !important;">
                                                        {{ $booking->paymentType->name ?? 'None' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Service Charge: </td>
                                                    <td style="border: none !important;">
                                                        {{ number_format($booking->service_amount, 2) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Vat Number: </td>
                                                    <td style="border: none !important;">
                                                        {{ vatSetting()->vat_number }}
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="invoice-content">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" style="border:none !important">
                                            <thead>
                                                <tr>
                                                    <th width="5%" class="text-center">SL</th>
                                                    <th>Service/Category</th>
                                                    <th class="text-center">Room/Booking Number</th>
                                                    <th class="text-right">Total Amount (&#x09F3;)</th>
                                                    <th class="text-right">Paid Amount (&#x09F3;)</th>
                                                    <th class="text-right">Due Amount (&#x09F3;)</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $total_amount = 0;
                                                    $total_paid_amount = 0;
                                                    $total_due_amount = 0;
                                                    $total_vat_amount = 0;
                                                    $sl = 1;
                                                    $net_collection = 0;

                                                @endphp


                                                {{-- Booking Service --}}
                                                @foreach ($booking->bookingDetails as $item)

                                                    @php

                                                        if ($booking->vat_amount > 0) {
                                                            $total_vat_amount = $applicable_vat = $booking->vat_amount;
                                                            $line_amount = $item->total_amount;
                                                        } else {
                                                            $total_vat_amount = $applicable_vat = getAmountTotalOfXPercent($total_amount, $vat);
                                                            $line_amount = $item->total_amount;
                                                        }

                                                        $total_amount += $item->bookingTransection->total_amount;
                                                        $net_collection = $total_paid_amount = $item->bookingTransection->collection;

                                                        $vat = (int) optional($booking->getVat)->hotel_vat;

                                                        $total_due_amount = $booking->transection->due_amount;
                                                        if ($total_due_amount > $line_amount) {
                                                            $total_due_amount = $total_due_amount - $total_paid_amount;
                                                        }else{
                                                            $total_due_amount = $total_paid_amount - $total_due_amount;
                                                        }

                                                    @endphp

                                                    <tr style="background: #fbeeec">
                                                        <td class="text-center">
                                                            {{ $sl++ }}
                                                            <input type="hidden" name="item_ids[]"
                                                                value="{{ $booking->id }}">
                                                            <input type="hidden" name="item_types[]" value="Booking">
                                                            <input type="hidden" name="item_amount[]"
                                                                value="{{ $total_due_amount }}">
                                                        </td>

                                                        <td>{{ optional($item->roomCategory)->name }}</td>
                                                        <td class="text-center">
                                                            {{ optional($item->roomNumber)->room_number }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($line_amount, 2 ?? 0) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($total_paid_amount, 2 ?? 0) }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($total_due_amount, 2 ?? 0) }}</td>
                                                    </tr>
                                                @endforeach






                                                {{-- Booking Adjust Service --}}
                                                @foreach ($booking->bookingAdjusts as $bookingAdjust)
                                                    @php
                                                        $payable_amount = $bookingAdjust->total_amount;
                                                        $paid_amount = optional($bookingAdjust->transactions)->sum('collection');
                                                        $due_amount = $payable_amount - $paid_amount;

                                                        $total_amount += $payable_amount;
                                                        $total_paid_amount += $paid_amount;
                                                        $total_due_amount += $due_amount;
                                                        $net_collection = $total_paid_amount;
                                                    @endphp



                                                    <tr style="background: #fbeeec">
                                                        <td class="text-center">
                                                            {{ $sl++ }}

                                                            <input type="hidden" name="item_ids[]"
                                                                value="{{ $bookingAdjust->id }}">
                                                            <input type="hidden" name="item_types[]"
                                                                value="Booking Adjust">
                                                            <input type="hidden" name="item_amount[]"
                                                                value="{{ $due_amount }}">
                                                        </td>

                                                        <td>Booking Adjust</td>

                                                        <td class="text-center">
                                                            INV-{{ optional($bookingAdjust->transactions->first())->invoice_no }}
                                                        </td>

                                                        <td style="text-align: right; padding-right: 15px !important">
                                                            {{ number_format($payable_amount, 2) }}
                                                        </td>

                                                        <td style="text-align: right; padding-right: 15px !important">
                                                            {{ number_format($paid_amount, 2) }}
                                                        </td>

                                                        <td class="text-right">
                                                            {{ $due_amount }}
                                                        </td>
                                                    </tr>
                                                @endforeach





                                                {{-- Hotel Service --}}
                                                @foreach ($booking->hotelServiceSale ?? [] as $service)
                                                    @php
                                                        $payable_amount = $service->payable_amount;
                                                        $paid_amount = optional($service->transactions)->sum('collection');
                                                        $due_amount = $payable_amount - $paid_amount;

                                                    @endphp


                                                    {{-- @if ($due_amount > 0) --}}

                                                    @php
                                                        $total_amount += $payable_amount;
                                                        $total_paid_amount += $paid_amount;
                                                        $total_due_amount += $due_amount;
                                                        $net_collection = $total_paid_amount;
                                                    @endphp


                                                    <tr style="background: #e9e3e2">
                                                        <td class="text-center">
                                                            {{ $sl++ }}
                                                            <input type="hidden" name="item_ids[]"
                                                                value="{{ $service->id }}">
                                                            <input type="hidden" name="item_types[]"
                                                                value="Hotel Service Sale">
                                                            <input type="hidden" name="item_amount[]"
                                                                value="{{ $due_amount }}">
                                                        </td>

                                                        {{-- <td>Hotel Service</td> --}}
                                                        <td>{{ optional($service)->getServiceNames() }}</td>

                                                        <td class="text-center">
                                                            INV-{{ $service->invoice_no }}
                                                        </td>

                                                        <td style="text-align: right">
                                                            {{ number_format($payable_amount, 2) }}
                                                        </td>

                                                        <td style="text-align: right">
                                                            {{ number_format($paid_amount, 2) }}
                                                        </td>

                                                        <td class="text-right">
                                                            {{ number_format($due_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach


                                                {{-- Resturent Service --}}
                                                @foreach ($booking->resturentServiceSale as $resturent)
                                                    @php
                                                        $payable_amount = $resturent->subtotal;
                                                        $paid_amount = optional($resturent->RstTransactions)->sum('collection');
                                                        $due_amount = $payable_amount - $paid_amount;
                                                    @endphp

                                                    {{-- @if ($due_amount > 0) --}}

                                                    @php
                                                        $total_amount += $payable_amount;
                                                        $total_paid_amount += $paid_amount;
                                                        $total_due_amount += $due_amount;
                                                        $net_collection = $total_paid_amount;
                                                    @endphp


                                                    <tr style="background: #fbeeec">
                                                        <td class="text-center">
                                                            {{ $sl++ }}

                                                            <input type="hidden" name="item_ids[]"
                                                                value="{{ $resturent->id }}">
                                                            <input type="hidden" name="item_types[]"
                                                                value="{{ $resturent->is_bar ? 'Bar' : 'Restaurant' }} Sale">
                                                            <input type="hidden" name="item_amount[]"
                                                                value="{{ $due_amount }}">
                                                        </td>

                                                        <td>{{ $resturent->is_bar ? 'Bar' : 'Restaurant' }} Sale</td>

                                                        <td class="text-center">
                                                            INV-{{ $resturent->invoice_no }}
                                                        </td>

                                                        <td style="text-align: right;">
                                                            {{ number_format($payable_amount, 2) }}
                                                        </td>

                                                        <td style="text-align: right;">
                                                            {{ number_format($paid_amount, 2) }}
                                                        </td>

                                                        <td class="text-right">
                                                            {{ number_format($due_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Subtotal </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_amount - $total_vat_amount - $booking->service_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr>
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Service Charge </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($booking->service_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr>
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Vat({{ vatSetting()->hotel_vat }}%) </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_vat_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr>
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Total </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_amount, 2 ?? 0) }}
                                                        &#x09F3;</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Discount </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        0.00
                                                        &#x09F3;</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Paid</td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($net_collection, 2) }} &#x09F3;</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">Due
                                                    </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_due_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <h5 style="font-weight: 700;">Amount Paid :
                                                <span>
                                                    {{ number_format($net_collection, 2 ?? 0) }}&#x09F3;
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
                                                Prepared By <br>{{ auth()->user()->name }}</h5>
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
