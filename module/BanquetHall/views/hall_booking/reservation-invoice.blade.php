@extends('layouts.master')
@section('title', 'Invoice')

@section('page-header')
    <i class="fa fa-info-circle"></i> Invoice
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Calistoga&display=swap" rel="stylesheet">
    <style>
        @include('booking._css.invoice-sheet')

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

        .m-auto {
            margin: 0 auto;
        }

        .company-name {
            text-transform: uppercase;
            font-weight: bold;
        }

        .main-print-body {
            /* border: 10px solid gray; */
            height: 100%;
        }

        .customer-info {
            margin: 15px 20px 0 20px;
        }

        hr {
            margin-top: 10px !important;
            margin-bottom: 10px !important;
        }

        .invoice-title {
            margin: 0 0 20px 0;
        }

        .invoice-content {
            margin: 0 20px;
        }

        .invoice-content .note {
            margin-top: 1px
        }

        .col-print-1 {
            width: 8%;
            float: left;
        }

        .col-print-2 {
            width: 16%;
            float: left;
        }

        .col-print-3 {
            width: 25%;
            float: left;
        }

        .col-print-4 {
            width: 33%;
            float: left;
        }

        .col-print-5 {
            width: 42%;
            float: left;
        }

        .col-print-6 {
            width: 50%;
            float: left;
        }

        .col-print-7 {
            width: 58%;
            float: left;
        }

        .col-print-8 {
            width: 66%;
            float: left;
        }

        .col-print-9 {
            width: 75%;
            float: left;
        }

        .col-print-10 {
            width: 83%;
            float: left;
        }

        .col-print-11 {
            width: 92%;
            float: left;
        }

        .col-print-12 {
            width: 100%;
            float: left;
        }

        .m-auto {
            margin: 0 auto;
        }

        .company-name {
            text-transform: uppercase;
        }

        .invoice-title {
            text-align: center;
            font-family: 'Calistoga', cursive !important;
        }

        .main-print-body {
            /* border: 10px solid rgba(62, 78, 90, 0.7); */
            height: 100%;
        }

        .customer-info {
            margin: 15px 20px 0 20px;
        }

        hr {
            margin-top: 10px !important;
            margin-bottom: 10px !important;
        }

        .invoice-title {
            margin: 0 0 20px 0;
        }

        .invoice-content {
            margin: 0 15px;
        }

        .print-footer {
            margin-right: auto;
            margin-bottom: 0px;
            margin-left: auto;
            margin-top: 7px;
            overflow: hidden;
            width: 100%;
            padding: 0 15px;
        }

        .guest-name {
            font-size: 14px;
        }

        .guest-info {
            padding: 10px 23px;
            margin-top: 1px;
        }

        .guest-room-info {
            padding: 1px 23px;
            margin-top: 1px;
        }

        .d-flex {
            display: flex;
        }

        .note-title {
            font-size: 14px;
            font-weight: bold;
        }

        .item-icon {
            margin-right: 5px;
        }

        .footer-note {
            font-size: 12px;
            text-transform: uppercase;
        }

        .footer-hash {
            margin-right: 10px
        }

        .ending-message {
            font-size: 11px;
            margin-top: 4px;
        }

        .with-regards {
            font-size: 14px;
            margin-top: 1px
        }

        .footer-name {
            font-weight: bold;
        }

        .footer-company-address {
            padding-bottom: 7px;
        }

        .reservation-row {
            padding-left: 20px;
            padding-right: 20px;
            margin-bottom: 20px
        }


        /*----------- NEW MEDIA PRINT -----------*/
        @media print {
            .col-print-1 {
                width: 8%;
                float: left;
            }

            .col-print-2 {
                width: 16%;
                float: left;
            }

            .col-print-3 {
                width: 25%;
                float: left;
            }

            .col-print-4 {
                width: 33%;
                float: left;
            }

            .col-print-5 {
                width: 42%;
                float: left;
            }

            .col-print-6 {
                width: 50%;
                float: left;
            }

            .col-print-7 {
                width: 58%;
                float: left;
            }

            .col-print-8 {
                width: 66%;
                float: left;
            }

            .col-print-9 {
                width: 75%;
                float: left;
            }

            .col-print-10 {
                width: 83%;
                float: left;
            }

            .col-print-11 {
                width: 92%;
                float: left;
            }

            .col-print-12 {
                width: 100%;
                float: left;
            }

            body {
                font-family: 'Fira Sans', sans-serif !important;
            }

            .font-family {
                font-family: 'Fira Sans', sans-serif !important;
            }

            .company-info h4 {
                font-weight: bold;
                margin-bottom: 0;
            }

            .company-info p {
                margin-bottom: 2px;
            }

            .invoice-title {
                font-family: 'Calistoga', cursive !important;
            }

            .footer-note {
                font-size: 11px;
            }

            .reservation-row {
                margin-bottom: 0px
            }

            .guest-room-info {
                margin-top: 1px !important;
            }

            .invoice-content .note {
                margin-top: 1px !important;
            }

            .invoice-content .note .note-title {
                margin-bottom: 5px;
            }

            .print-footer {
                margin-top: 1px;
            }

            .ending-message {
                margin-top: 4px;
            }

            #print_body {
                padding: 10px 5px;
            }

            .invoice-title {
                margin: 0 0 20px 0;
            }

            .main-print-body {
                height: 985px;
            }
            .main-print-body table tr {
                page-break-inside: avoid;
            }

            #print_body {
                /* page-break-after: auto;
                    page-break-after: always;
                    page-break-after: avoid; */

                /* page-break-inside: auto;
                    page-break-inside: always;
                    page-break-inside: avoid; */
            }
        }

        /* .main-print-body{
                border: 10px solid rgba(62, 78, 90, 0.7);
            } */
        .print-body {
            border: 8px solid rgba(62, 78, 90, 0.7);
        }

        @media print {

            /* @page{
                    size: A4;
                } */
            .print-body {
                position: fixed;
                margin: 10px 12px !important;
                border: 8px solid rgba(62, 78, 90, 0.7);
            }

            .invoice-content .note {
                height: 205px;
                overflow: hidden;
            }
        }
    </style>
@stop

@section('content')

    <div class="row invoice-body">
        <div class="col-sm-12">
            <div class="widget-box">

                <!-- WIDGET HEADER -->
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
                    <div class="widget-main" style="padding: 10px 21px !important">

                        <div class="row">
                            <div id="print_body" class="print-body">

                                <div class="main-print-body">

                                    <!-- COMPANY & CUSTOMER INFO -->
                                    <div id="customer_info" class="customer-info" style="padding: 0 10px;">
                                        <div class="row">

                                            <!-- COMPANY INFO -->
                                            <div class="company-info">
                                                <div style="width: 25%" class="text-center m-auto">
                                                    {{-- @if (file_exists('uploads/company/' . $company->logo))
                                                        <img src="{{ asset('uploads/company/' . $company->logo) }}" alt="Company Logo" width="150" height="80">
                                                    @endif --}}
                                                </div>
                                                <div class="text-center m-auto" style="width: 50%">
                                                    <h4 class="company-name font-family">
                                                        {{ $company->name != null ? $company->name : '' }}</h4>
                                                    <p class="font-family">
                                                        {{ $company->head_office != null ? $company->head_office : '' }}</p>
                                                    <p class="font-family">
                                                        {{ $company->phone_number != null ? $company->phone_number : '' }},
                                                        {{ $company->email != null ? $company->email : '' }}</p>
                                                    <!-- <p>Website: </p> -->
                                                </div>
                                            </div>
                                            <hr>


                                            <!-- RESERVATION CONFIRMATION -->
                                            <h2 class="invoice-title font-family">
                                                {{ $booking->status == 0 ? 'Reservation' : 'Booking' }} Confirmation</h2>
                                            <div class="row reservation-row">

                                                <!-- LEFT SIDE -->
                                                <div class="col-print-4">
                                                    <div class="row">
                                                        <div class="col-print-3">
                                                            <div class="col-title font-family"><b>Date</b></div>
                                                        </div>
                                                        <div class="col-print-9">
                                                            <div class="col-text font-family">:
                                                                {{ $booking->booking_date != null ? $booking->booking_date : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if (optional($booking->guestInfo)->company_id != null)
                                                        <div class="row">
                                                            <div class="col-print-3">
                                                                <div class="col-title font-family"><b>Company</b></div>
                                                            </div>
                                                            <div class="col-print-9">
                                                                <div class="col-text font-family">:
                                                                    {{ getCrmCompany(optional($booking->guestInfo)->company_id) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-print-3">
                                                            <div class="col-title font-family"><b>Address</b></div>
                                                        </div>
                                                        <div class="col-print-9">
                                                            @if (optional($booking->guestInfo)->company_id != null &&
                                                                    getCrmCompanyAddress(optional($booking->guestInfo)->company_id) != null)
                                                                <div class="col-text font-family">:
                                                                    {{ getCrmCompanyAddress(optional($booking->guestInfo)->company_id) }}
                                                                </div>
                                                            @else
                                                                <div class="col-text font-family">:
                                                                    {{ optional($booking->guestInfo)->address != null ? optional($booking->guestInfo)->address : 'N\A' }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-print-3">
                                                    <span style="color: white">.</span>
                                                </div>

                                                <!-- RIGHT SIDE -->
                                                <div class="col-print-5">
                                                    <div class="row">
                                                        <div class="col-print-5">
                                                            <div class="col-title font-family"><b>Confirmation No.</b></div>
                                                        </div>
                                                        <div class="col-print-7">
                                                            <div class="col-text font-family">:
                                                                {{ $booking->booking_number != null ? $booking->booking_number : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-print-5">
                                                            <div class="col-title font-family"><b>Cell No.</b></div>
                                                        </div>
                                                        <div class="col-print-7">
                                                            <div class="col-text font-family">:
                                                                {{ optional($booking->guestInfo)->phone_no != null ? optional($booking->guestInfo)->phone_no : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-print-5">
                                                            <div class="col-title font-family"><b>E-mail</b></div>
                                                        </div>
                                                        <div class="col-print-7">
                                                            <div class="col-text font-family" style="word-break: break-all">
                                                                :
                                                                {{ optional($booking->guestInfo)->email != null ? optional($booking->guestInfo)->email : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-print-5">
                                                            <div class="col-title font-family"><b>NID/Passport No</b></div>
                                                        </div>
                                                        <div class="col-print-7">
                                                            <div class="col-text font-family">:
                                                                {{ optional($booking->guestInfo)->nid_no != null ? optional($booking->guestInfo)->nid_no : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>


                                    <!-- INVOICE CONTENT -->
                                    <div class="invoice-content">

                                        <b class="guest-name font-family">
                                            Dear
                                            {{ optional($booking->guestInfo)->name != null ? optional($booking->guestInfo)->name : 'N\A' }}
                                        </b>
                                        <div class="font-family">
                                            Seasons best greetings from <b class="font-family">{{ $company->name }}</b>, We
                                            are pleased to confirm the following reservation as per your request.
                                        </div>

                                        <!-- GUEST INFO -->
                                        <div class="guest-info">
                                            <div class="row">
                                                <div class="col-print-4">
                                                    <div class="row">
                                                        <div class="col-print-4">
                                                            <div class="col-title font-family"><b>Guest Name</b></div>
                                                        </div>
                                                        <div class="col-print-8">
                                                            <div class="col-text font-family">:
                                                                {{ optional($booking->guestInfo)->name != null ? optional($booking->guestInfo)->name : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-print-4">
                                                    <div class="row">
                                                        <div class="col-print-5">
                                                            <div class="col-title font-family"><b>Father's Name</b></div>
                                                        </div>
                                                        <div class="col-print-7">
                                                            <div class="col-text font-family">:
                                                                {{ optional($booking->guestInfo)->father_name != null ? optional($booking->guestInfo)->father_name : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-print-4">
                                                    <div class="row">
                                                        <div class="col-print-4">
                                                            <div class="col-title font-family"><b>Age</b></div>
                                                        </div>
                                                        <div class="col-print-8">
                                                            <div class="col-text font-family">:
                                                                {{ optional($booking->guestInfo)->age != null ? optional($booking->guestInfo)->age : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-print-4">
                                                    <div class="row">
                                                        <div class="col-print-4">
                                                            <div class="col-title font-family"><b>Eg.Contact</b></div>
                                                        </div>
                                                        <div class="col-print-8">
                                                            <div class="col-text font-family">:
                                                                {{ $booking->emergency_cont_name != null ? $booking->emergency_cont_name : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-print-4">
                                                    <div class="row">
                                                        <div class="col-print-5">
                                                            <div class="col-title font-family"><b>Eg.Contact</b></div>
                                                        </div>
                                                        <div class="col-print-7">
                                                            <div class="col-text font-family">:
                                                                {{ $booking->emergency_cont_phone != null ? $booking->emergency_cont_phone : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-print-4">
                                                    <div class="row">
                                                        <div class="col-print-4">
                                                            <div class="col-title font-family"><b>Profession</b></div>
                                                        </div>
                                                        <div class="col-print-8">
                                                            <div class="col-text font-family">:
                                                                {{ optional($booking->guestInfo)->profession != null ? optional($booking->guestInfo)->profession : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-print-4"></div> --}}
                                            </div>
                                            <hr>
                                        </div>

                                        <!-- ROOM INFO -->
                                        <div class="guest-room-info">
                                            <div class="row">
                                                <div class="col-print-8">
                                                    <div class="row">
                                                        <div class="col-print-3">
                                                            <div class="col-title font-family"><b>Room Type</b></div>
                                                        </div>
                                                        <div class="col-print-8" style="padding-left: 6px !important;">
                                                            <div class="col-text font-family">:
                                                                BanquetHall
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row"
                                                @if ($booking->status == 0) style="display: none" @endif>
                                                <div class="col-print-8">
                                                    <div class="row">
                                                        <div class="col-print-3">
                                                            <div class="col-title font-family"><b>Hall Room Numbers</b>
                                                            </div>
                                                        </div>
                                                        <div class="col-print-8" style="padding-left: 6px !important;">
                                                            <div class="col-text font-family">:
                                                                @foreach ($booking->bookingDetails as $item)
                                                                    {{ optional($item->hall)->room_number }}
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-print-4">
                                                    <div class="row">
                                                        <div class="col-print-5">
                                                            <div class="col-title font-family"><b>Booked Time</b></div>
                                                        </div>
                                                        <div class="col-print-7" style="padding-left: 0px !important;">
                                                            <div class="col-text font-family">:
                                                                {{ $booking->booked_time }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-print-8">
                                                    <div class="row">
                                                        <div class="col-print-3">
                                                            <div class="col-title font-family"><b>Mode of Payment</b></div>
                                                        </div>
                                                        <div class="col-print-8" style="padding-left: 6px !important;">
                                                            <div class="col-text font-family">:
                                                                {{ $booking->payment_way != null ? $booking->payment_way : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-print-8">
                                                    <div class="row">
                                                        <div class="col-print-3">
                                                            <div class="col-title font-family"><b>Reference By</b></div>
                                                        </div>
                                                        <div class="col-print-8" style="padding-left: 6px !important;">
                                                            <div class="col-text font-family">:
                                                                {{ $booking->reference != null ? $booking->reference : 'N\A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-print-4">
                                                    @if ($booking->purpose_id != null)
                                                        <div class="row">
                                                            <div class="col-print-5">
                                                                <div class="col-title font-family"><b>Booking Purpose</b>
                                                                </div>
                                                            </div>
                                                            <div class="col-print-7"
                                                                style="padding-left: 0px !important;">
                                                                <div class="col-text font-family">:
                                                                    {{ optional($booking->booking_purpose)->name }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-print-8">
                                                    <div class="row" style="margin-top: 0px;">
                                                        <div class="col-print-3">
                                                            <div class="col-title font-family"><b>Remarks</b></div>
                                                        </div>
                                                        <div class="col-print-8" style="padding-left: 6px !important;">
                                                            <div class="col-text font-family">: {{ $booking->check_in_note != null ? $booking->check_in_note : 'N\A' }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-print-8">
                                                    <div class="row" style="margin-top: 0px;">
                                                        <div class="col-print-3">
                                                            <div class="col-title font-family"><b>Check In</b></div>
                                                        </div>
                                                        <div class="col-print-8" style="padding-left: 6px !important;">
                                                            <div class="col-text font-family">:
                                                                {{ $booking->check_in_date ? date('F j, Y, g:i a', strtotime($booking->check_in_date)) : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-print-4">
                                                    <div class="row">
                                                        <div class="col-print-5">
                                                            <div class="col-title font-family"><b>Check Out</b></div>
                                                        </div>
                                                        <div class="col-print-7" style="padding-left: 0px !important;">
                                                            <div class="col-text font-family">:
                                                                {{ $booking->check_out_time ? date('F j, Y, g:i a', strtotime($booking->check_out_time)) : '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>

                                        <!-- BOOKING NOTE -->
                                        <div class="note">

                                            <p class="note-title font-family">Please Note:</p>
                                            {{-- {!! $bookingNotes->title !!} --}}
                                            @foreach ($bookingNotes as $bookingNote)
                                                <div class="item">
                                                    <div class="d-flex">
                                                        <b class="item-icon">
                                                            #
                                                        </b>
                                                        <div class="item-text">
                                                            {{ $bookingNote->title }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            {{-- @php
                                                $checkInOut = setting('date_start_end') != null ? setting('date_start_end') : '';
                                                $checkIn    = '';
                                                $checkOut   = '';

                                                if ($checkInOut != null) {
                                                    $explode    = explode("-", $checkInOut);
                                                    $checkIn    = $explode[0];
                                                    $checkOut   = $explode[1];
                                                }
                                            @endphp
                                            <div class="item">
                                                <div class="d-flex">
                                                    <b class="item-icon">
                                                        #
                                                    </b>
                                                    <div class="item-text">
                                                        Our standard check in time is {{ $checkIn }} & check out time is {{ $checkOut }}.
                                                    </div>
                                                </div>
                                            </div> --}}

                                        </div>

                                    </div>


                                    <!-- PRINT FOOTER -->
                                    <div class="print-footer">
                                        <b class="footer-note font-family"><span
                                                class="footer-hash font-family">#</span>please provide us your estimated
                                            time of arrival in order to get your room ready upon arrival</b>
                                        <div class="ending-message font-family">Thank you again for showing interest in <b
                                                class="font-family">{{ $company->name }}</b>. Is there anything we can do
                                            to make your stay more rewarding, please as</div>
                                        <div class="with-regards font-family">With Best Regards</div>
                                        <div class="footer-name font-family">{{ $company->name }}</div>
                                        @if ($company->head_office != null)
                                            <div class="font-family footer-company-address">{{ $company->head_office }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="signature-sectuion"
                                        style="display: flex; justify-content: space-evenly;    margin: 29px 0 0 0;">
                                        <div class="text-center" style="margin-right: 10px;">
                                            <span class="text-center"
                                                style="border-top: 1px solid #c3c3c3; padding: 5px 82px;">Guest</span>
                                        </div>
                                        <div class="text-center" style="margin-left: 10px;">
                                            <span class="text-center"
                                                style="border-top: 1px solid #c3c3c3; padding: 5px 82px;">{{ $company->name }}</span>
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
    {{-- <script type="text/javascript">
        function printPage(id) {
            $('#' + id).printThis({
                importStyle: true
            });
        };
        window.onreadystatechange = $('#print_body').printThis({
            importStyle: true
        });
    </script> --}}
@stop
