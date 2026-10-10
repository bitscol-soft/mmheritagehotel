@extends('layouts.master')
@section('title', 'Hotel Service Invoice')

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

    @php
        $sub_total = $booking->sub_total;
        $adv_amount = $booking->advanced_payment;
        $vat = optional($booking->getVat)->hotel_vat;
        $vat_amount = ($sub_total / 100) * $vat;
        $total_amount = $sub_total + $vat_amount;
        $current_due = $total_amount - $adv_amount;
    @endphp


    <x-mm.styles />
    <x-mm.page class="mm-invoice-page" title="Hotel Booking Invoice">
        <x-slot name="actions">
            @if (hasPermission('service.view', $slugs))
                <a href="#" onclick="printPage('print_body')" class="btn btn-sm btn-default hidden-print">
                    <i class="fa fa-print"></i> Print
                </a>
            @endif
        </x-slot>

        <x-mm.panel>
                        <div class="row">
                            <div id="print_body">
                                <div id="customer_info" style="padding: 0 10px;">
                                    <div class="row">

                                        <div class="company-info text-center">
                                            <h4>{{ $company->name }}</h4>
                                            <p>{{ $company->head_office }}</p>
                                            <p>{{ $company->phone_number }},{{ $company->email }},</p>
                                        </div>
                                        <hr>
                                        <div class="customerInfo" style="width: 60%;float: left; ">

                                            <h5><b><u>Guest's Information : </u></b></h5>

                                            <p class="patient"><b>Name : </b>{{ $booking->guestInfo->name }}</p>

                                            <p class="patient"><b>Address : </b>{{ $booking->guestInfo->address }}
                                            </p>
                                            <p><b>Nationality :</b>
                                                {{ $booking->guestInfo->country->name }}</p>
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
                                                    <th>Room Category</th>

                                                    <th style="text-align: right">
                                                        Discount
                                                        (&#x09F3;)</th>
                                                    <th width="25%" style="text-align:right">Amount (&#x09F3;)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($booking->bookingDetails as $item)
                                                    <tr>
                                                        <td>{{ ++$loop->index }}</td>
                                                        <td>{{ $item->roomCategory->name }} -
                                                            {{ optional($item->roomNumber)->room_number }}</td>
                                                        {{-- &#x09F3; for taka symbol --}}

                                                        <td class="text-right">
                                                            {{ number_format($item->discount_amount, 2 ?? 0) }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($item->total_amount, 2) }}
                                                            &#x09F3;
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <td colspan="3" style="text-align: right; border: none !important;">
                                                        Subtotal </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($sub_total_amount = optional($booking->transection)->total_amount ?? 0, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="text-align: right; border: none !important;">
                                                        Vat({{ optional($booking->getVat)->hotel_vat }}%) </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($vat_amount, 2 ?? 0) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="text-align: right; border: none !important;">
                                                        Total </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total = $sub_total_amount - optional($booking->transection)->discount ?? 0, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="text-align: right; border: none !important;">
                                                        Paid </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_paid_amount = optional($booking->transection)->collection ?? 0, 2) }}
                                                        &#x09F3;
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="text-align: right; border: none !important;">
                                                        Due
                                                    </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total - $total_paid_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <h5 style="font-weight: 700;">Amount Paid :
                                                <span>
                                                    {{ number_format(optional($booking->transection)->collection ?? 0, 2) }}&#x09F3;
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

                                    <br>
                                </div>
                            </div>
                        </div>
        </x-mm.panel>
    </x-mm.page>
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
