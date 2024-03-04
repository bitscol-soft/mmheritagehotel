@extends('frontend.layouts.master')
@section('website_header') Booked Successfull |@endsection
@push('custom_css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">
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
                    <div id="customer_info" style="padding: 0 10px; margin-bottom: 50px;">
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
                    </div>

                </div>
            </div>

        </div>
    </section>

@endsection

@push('custom_js')
    @if (session()->has('bookingSuccessMessage'))
        Swal.fire({
            position: 'center',
            type: 'success',
            title: '<h4>Booking has been created successfully</h4>',
            showConfirmButton: false,
            timer: 1500
        })
    @endif
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
@endpush
