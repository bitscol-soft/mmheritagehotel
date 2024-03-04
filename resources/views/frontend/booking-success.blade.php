@extends('frontend.layouts.master')
@section('website_header') Booked Successfull |@endsection
@push('custom_css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">
    <style>
        #print_body {
            background-color: #fff;
            padding: 30px 0;
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

        table tbody tr th {
            font-weight: bold!important;
        }
        table tbody tr th, table tbody tr td {
            padding: 0!important;
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
        .booking-confirm-title {
            font-size: 32px;
            color: green;
            line-height: normal;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .contact-number{
            background: #ddd;
            padding: 5px 15px;
            border-radius: 5px;
            color: #000;
            text-decoration: none;
        }
        .card{
            border: 1px solid #ddd; 
            border-radius: 8px; 
            padding: 15px;
        }
        .contact-card-body{
            display: flex; 
            justify-content: space-between; 
            align-items:center;
        }
    </style>
@endpush


@section('frontend-content')

    <section class="guest-registration">
        <div class="container">
            <div class="row">
                <a href="#" onclick="printPage('print_body')">
                    <i class="fa fa-print"></i>
                    Print
                </a>
                <div id="print_body">
                    {{-- <div id="customer_info" style="padding: 0 10px; margin-bottom: 50px;">
                        <div class="row">
                            <div class="company-info" style="display: flex; justify-content:center">
                                <div class="text-center" style="width: 50%">
                                    <h4>{{ $data['company']->name }}</h4>
                                    <p>{{ $data['company']->head_office }}</p>
                                    <p>{{ $data['company']->phone_number }}, {{ $data['company']->email }},</p>
                                </div>
                            </div>
                            <hr>
                            <div class="customerInfo" style="width: 70%;float: left; ">

                                <h5><b><u>Guest's Information : </u></b></h5>
                                <p class="patient"><b>Name : </b>{{$guest->name}}</p>
                                <p><b>Room : </b>{{ $data['booking']->bookingDetail->roomCategory->name }} -
                                    {{ $data['booking']->bookingDetail->roomNumber->room_number }}</p>
                                <p class="patient"><b>Address : </b>{{ $data['booking']->guestInfo->address }}
                                </p>
                                <p class="patient"><b>Mobile : </b>{{ $data['booking']->guestInfo->phone_no }}
                                </p>
                            </div>
                            <div class="invoiceInfo" style="width: 30%;float: left; margin-top: 5px;">
                                <table class="table table-bordered" style="border: none !important;">
                                    <tr>
                                        <th width="50%" style="border: none !important;"> Booking No : </th>
                                        <th style="border: none !important;">
                                            BK-{{ $data['booking']->booking_number }}</th>
                                    </tr>
                                    <tr>
                                        <td style="border: none !important;"> Booking Date : </td>
                                        <td style="border: none !important;">{{ $data['booking']->booking_date }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: none !important;"> Check IN Date : </td>
                                        <td style="border: none !important;">{{ $data['booking']->check_in_date }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: none !important;"> Check out Date : </td>
                                        <td style="border: none !important;">{{ $data['booking']->check_out_date }}
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
                                        <th>Room No</th>
                                        <th class="text-right">Amount</th>
                                        <th class="text-right">Vat</th>
                                        <th class="text-right">Service Charge</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Paid</th>
                                        <th class="text-right">Due Amount</th>
                                    </tr>
                                </thead>
                                @php
                                    $net_collection = $data['transactions']->sum('collection');
                                    $service_charge = $due_amount = 0;
                                @endphp
                                <tbody>
                                    @foreach ($data['transactions'] as $transaction)
                                        @php
                                            $service_charge += $transaction->service_amount;
                                            $due_amount += $transaction->due_amount;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $transaction->invoice_no }}</td>
                                            <td>
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
                    </div> --}}
                    <div style="display: flex; gap: 20px; flex-direction: column">
                        <div class="company-info" style="display: flex; justify-content:center">
                            <div class="text-center" style="width: 50%">
                                <h4>{{ $data['company']->name }}</h4>
                                <p>{{ $data['company']->head_office }}</p>
                                <p>{{ $data['company']->phone_number }}, {{ $data['company']->email }},</p>
                            </div>
                        </div>


                        <div class="card">
                            <h5 class="card-title booking-confirm-title"><svg xmlns="http://www.w3.org/2000/svg" height="32" width="32" viewBox="0 0 512 512"><path fill="#008000" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg> Booking Confirmed</h5>
                            <hr style="margin-top: 10px;">
                            <p class="card-text">Thank you for choosing  our Hotel for your upcoming visit. We eagerly await your arrival on {{ $data['booking']->check_in_date }}. Our team is dedicated to ensuring your stay is both comfortable and memorable. For any queries or special requests, please do not hesitate to contact us directly.</p>
                            <hr>
                            <h3>Booking Info : </h3>
                            <div class="company-info" style="display: flex; justify-content:center">
                                <div class="customerInfo" style="width: 70%;float: left; ">
                                    <p class="patient"><b>Name : </b>{{$guest->name}}</p>
                                    <p><b>Room : </b>{{ $data['booking']->bookingDetail->roomCategory->name }} -
                                        {{ $data['booking']->bookingDetail->roomNumber->room_number }}</p>
                                    <p class="patient"><b>Address : </b>{{ $data['booking']->guestInfo->address }}
                                    </p>
                                    <p class="patient"><b>Mobile : </b>{{ $data['booking']->guestInfo->phone_no }}
                                    </p>
                                </div>
                                <div class="invoiceInfo" style="width: 30%;float: left; margin-top: 5px;">
                                    <table class="table table-bordered" style="border: none !important;">
                                        <tr>
                                            <th width="50%" style="border: none !important;"> Booking No : </th>
                                            <th style="border: none !important;">
                                                BK-{{ $data['booking']->booking_number }}</th>
                                        </tr>
                                        <tr>
                                            <td style="border: none !important;"> Booking Date : </td>
                                            <td style="border: none !important;">{{ $data['booking']->booking_date }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border: none !important;"> Check IN Date : </td>
                                            <td style="border: none !important;">{{ $data['booking']->check_in_date }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border: none !important;"> Check out Date : </td>
                                            <td style="border: none !important;">{{ $data['booking']->check_out_date }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card" style="margin-top: 30px">
                            <div class="contact-card-body">
                                <div>
                                    <h4 class="card-title">Need Our Help ?</h4>
                                    <p class="card-text">Call us in case you face any issue in our services.</p>
                                </div>
                                <div>
                                    <a class="contact-number" href="tel:+{{ $data['company']->phone_number }}">{{ $data['company']->phone_number }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('custom_js')
    <script src="{{ asset('assets/custom_js/printThis.js') }}"></script>
    <script type="text/javascript">
        function printPage(id) {
            $('#' + id).printThis({
                importStyle: true
            });
        };
    </script>
@endpush
