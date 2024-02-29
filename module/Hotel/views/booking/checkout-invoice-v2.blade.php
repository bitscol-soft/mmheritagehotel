@extends('layouts.master')
@section('title', 'Booking Invoice')

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
                                        <div class="customerInfo" style="width: 70%;float: left; ">

                                            <h5><b><u>Guest's Information : </u></b></h5>
                                            <p class="patient"><b>Name : </b>{{ $booking->guestInfo->name }}</p>
                                            <p><b>Room : </b>{{ $booking->bookingDetail->roomCategory->name }} -
                                                {{ $booking->bookingDetail->roomNumber->room_number }}</p>
                                            <p class="patient"><b>Address : </b>{{ $booking->guestInfo->address }}
                                            </p>
                                            <p><b>Nationality :</b>{{ $booking->guestInfo->country->name }}</p>
                                            <p class="patient"><b>Mobile : </b>{{ $booking->guestInfo->phone_no }}
                                            </p>
                                        </div>
                                        <div class="invoiceInfo" style="width: 30%;float: left; margin-top: 5px;">
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
                                        <table class="table table-borderless">
                                            <thead>
                                                <tr>
                                                    <th width="5%" class="text-center">SL</th>
                                                    <th>Invoice</th>
                                                    <th>Service</th>
                                                    <th class="text-right">Amount</th>
                                                    <th class="text-right">Vat</th>
                                                    <th class="text-right">Service Charge</th>
                                                    <th class="text-right">Total</th>
                                                    <th class="text-right">Paid</th>
                                                    <th class="text-right">Due Amount</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $net_collection = $transactions->sum('collection');
                                                $service_charge = $due_amount = 0;
                                            @endphp
                                            <tbody>
                                                @foreach ($transactions as $transaction)
                                                    @php
                                                        $service_charge += $transaction->service_amount;
                                                        $due_amount += $transaction->due_amount;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>{{ $transaction->invoice_no }}</td>
                                                        <td>
                                                            <p>{{ $transaction->source_type }}</p>
                                                            @if ($transaction->source_type == 'Booking')
                                                                @foreach (optional($transaction->source)->details ?? [] as $item)
                                                                    <label
                                                                        class="label label-xs label-success arrowed arrowed-right mb-1">{{ $item->roomNumber->room_number }}</label>
                                                                @endforeach
                                                            @endif
                                                        </td>

                                                        <td class="text-right">
                                                            {{ number_format($transaction->total_amount - $transaction->vat_amount - $transaction->service_charge, 2) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($transaction->vat_amount, 2) }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($transaction->service_charge, 2) }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($transaction->total_amount, 2) }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($transaction->collection, 2) }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($transaction->due_amount, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="8" style="text-align: right; border: none !important;">
                                                        Subtotal </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($transactions->sum('total_amount') - $transactions->sum('service_charge') - $transactions->sum('vat_amount'), 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td colspan="8" style="text-align: right; border: none !important;">
                                                        Service Charge </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($transactions->sum('service_charge'), 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td colspan="8" style="text-align: right; border: none !important;">
                                                        Vat({{ vatSetting()->hotel_vat }}%) </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($transactions->sum('vat_amount'), 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td colspan="8" style="text-align: right; border: none !important;">
                                                        Total </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($transactions->sum('total_amount'), 2 ?? 0) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="8" style="text-align: right; border: none !important;">
                                                        @if ($transactions->discount_type == 1)
                                                            Complementary
                                                        @else
                                                            Discount
                                                        @endif
                                                    </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        0.00
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="8" style="text-align: right; border: none !important;">
                                                        Paid</td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($net_collection, 2) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td colspan="8" style="text-align: right; border: none !important;">
                                                        Due
                                                    </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($transactions->sum('total_amount') - $transactions->sum('collection'), 2) }}
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <h5 style="font-weight: 700;">Amount Paid :
                                                <span>
                                                    {{ number_format($net_collection, 2 ?? 0) }}
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
