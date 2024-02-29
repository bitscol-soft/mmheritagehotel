@php
    $company        = \App\Models\Company::first();
    $bookingNotes   = \Module\Hotel\Models\BookingNote::where('status', 1)->get();
    $transactions   = \Module\Hotel\Models\HotelTransection::with('source')->where('booking_id', $bookingId)->get();
    $booking        = \Module\Hotel\Models\Booking::with(['bookingDetails' => function($q){
                                                            $q->with('roomNumber')->with('roomCategory');
                                                        }])->with('getVat')->find($bookingId);
@endphp

<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Booking Reservation Alert</title>
    <meta name="description" content="Product Stock In Alert">
    <link href="https://fonts.googleapis.com/css2?family=Calistoga&display=swap" rel="stylesheet">

    <style type="text/css">
        @import  url('https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200;300;400;500;600;700&display=swap');
        a:hover {
            text-decoration: underline !important;
        }
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
        /*----------- NEW CSS ----------*/
        .m-auto{
            margin: 0 auto;
        }
        .company-name{
            text-transform: uppercase;
            font-weight: bold;
        }
        .main-print-body {
            border: 10px solid gray;
            height: 100%;
        }
        .customer-info{
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
        .invoice-content .note{
            margin-top: 20px
        }
        .col-print-1 {width:8%;  float:left; }
        .col-print-2 {width:16%; float:left; }
        .col-print-3 {width:25%; float:left; }
        .col-print-4 {width:33%; float:left; }
        .col-print-5 {width:42%; float:left; }
        .col-print-6 {width:50%; float:left; }
        .col-print-7 {width:58%; float:left; }
        .col-print-8 {width:66%; float:left; }
        .col-print-9 {width:75%; float:left; }
        .col-print-10{width:83%; float:left; }
        .col-print-11{width:92%; float:left; }
        .col-print-12{width:100%; float:left;}
        .m-auto{
            margin: 0 auto;
        }
        .company-name{
            text-transform: uppercase;
        }
        .invoice-title{
            text-align: center;
            font-family: 'Calistoga', cursive !important;
        }
        .main-print-body {
            border: 10px solid rgba(62, 78, 90, 0.7);
            height: 100%;
        }
        .customer-info{
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
        .print-footer{
            margin-right: auto;
            margin-bottom: 0px;
            margin-left: auto;
            margin-top: 40px;
            overflow: hidden;
            width: 100%;
            padding: 0 15px;
        }
        .guest-name{
            font-size: 16px;
        }
        .guest-info {
            padding: 10px 23px;
            margin-top: 10px;
        }
        .guest-room-info{
            padding: 10px 23px;
            margin-top: 20px;
        }
        .d-flex{
            display: flex;
        }
        .note-title{
            font-size: 16px;
            font-weight: bold;
        }
        .item-icon{
            margin-right: 5px;
        }
        .footer-note{
            font-size: 15px;
            text-transform: uppercase;
        }
        .footer-hash{
            margin-right: 10px
        }
        .ending-message{
            font-size: 12px;
            margin-top: 15px;
        }
        .with-regards{
            font-size: 17px;
            margin-top: 10px
        }
        .footer-name{
            font-weight: bold;
            padding-bottom: 7px;
        }
        .reservation-row{
            padding-left: 20px;
            padding-right: 20px;
            margin-bottom: 20px
        }
    </style>
    <style>
        .text-center{
            text-align: center;
        }
        .m-auto{
            margin: 0 auto;
        }
        .d-flex{
            display: flex;
        }
        .row {
            margin-left: -12px;
            margin-right: -12px;
        }
        .company-info p {
            margin-top: 5px !important;
        }
        .break-word{
            word-break: break-all;
        }
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">

    <div id="print_body">

        <div class="main-print-body">

            <!-- COMPANY & CUSTOMER INFO -->
            <div id="customer_info" class="customer-info" style="padding: 0 10px;">
                <div class="row">

                    <!-- COMPANY INFO -->
                    <div class="company-info" style="padding: 0; margin: 0 15px;">
                        <div style="width: 25%" class="text-center m-auto">
                            @if (file_exists('uploads/company/' . $company->logo))
                                <img src="{{ asset('uploads/company/' . $company->logo) }}" alt="Company Logo" width="150" height="80">
                            @endif
                        </div>
                        <div class="text-center m-auto" style="width: 50%">
                            <h4 class="company-name font-family">{{ $company->name != null ? $company->name : '' }}</h4>
                            <p class="font-family">{{ $company->head_office != null ? $company->head_office : '' }}</p>
                            <p class="font-family">{{ $company->phone_number != null ? 'Contact Number: '.$company->phone_number : '' }}</p>
                            <p class="font-family">{{ $company->email != null ? 'Email: '. $company->email : '' }}</p>
                            <!-- <p>Website: </p> -->
                        </div>
                    </div> <hr>


                    <!-- RESERVATION CONFIRMATION -->
                    <h2 class="invoice-title font-family">Reservation Confirmation</h2>
                    <div class="row reservation-row">

                        <!-- LEFT SIDE -->
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Date</b></div>
                                </div>
                                <div class="col-print-9">
                                    <div class="col-text font-family">: {{ $booking->booking_date != null ? $booking->booking_date : 'N\A' }}</div>
                                </div>
                            </div>
                            @if (optional($booking->guestInfo)->company_id != null)
                                <div class="row">
                                    <div class="col-print-3">
                                        <div class="col-title font-family"><b>Company</b></div>
                                    </div>
                                    <div class="col-print-9">
                                        <div class="col-text font-family">: {{ getCrmCompany(optional($booking->guestInfo)->company_id) }}</div>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-print-3">
                                    <div class="col-title font-family"><b>Address</b></div>
                                </div>
                                <div class="col-print-9">
                                    @if (optional($booking->guestInfo)->company_id != null && getCrmCompanyAddress(optional($booking->guestInfo)->company_id) != null)
                                        <div class="col-text font-family">: {{ getCrmCompanyAddress(optional($booking->guestInfo)->company_id) }}</div>
                                    @else
                                        <div class="col-text font-family">: {{ optional($booking->guestInfo)->address != null ? optional($booking->guestInfo)->address : 'N\A' }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-print-4">
                            <span style="color: white">.</span>
                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="col-print-4">
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Confirmation No.</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ $booking->booking_number != null ? $booking->booking_number : 'N\A' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Cell No.</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ optional($booking->guestInfo)->phone_no != null ? optional($booking->guestInfo)->phone_no : 'N\A' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>E-mail</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family break-word">: {{ optional($booking->guestInfo)->email != null ? optional($booking->guestInfo)->email : 'N\A' }}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-print-5">
                                    <div class="col-title font-family"><b>Passport No</b></div>
                                </div>
                                <div class="col-print-7">
                                    <div class="col-text font-family">: {{ optional($booking->guestInfo)->nid_no != null ? optional($booking->guestInfo)->nid_no : 'N\A' }}</div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


            <!-- INVOICE CONTENT -->
            <div class="invoice-content">

                <b class="guest-name font-family">
                    Dear {{ optional($booking->guestInfo)->name != null ? optional($booking->guestInfo)->name : 'N\A' }}
                </b>
                <div class="font-family">
                    Seasons best greetings from <b class="font-family">{{ $company->name }}</b>, We are pleased to confirm the following reservation as per your request.
                </div>

            </div>


            <!-- GUEST INFO -->
            <div class="guest-info" style="margin: 0 15px !important; height: 80px;">
                <div class="row" style="height: 22px;">
                    <div class="col-print-5">
                        <div class="row">
                            <div class="col-print-7">
                                <div class="col-title font-family"><b>Name of The Guest</b></div>
                            </div>
                            <div class="col-print-5">
                                <div class="col-text font-family">: {{ optional($booking->guestInfo)->name != null ? optional($booking->guestInfo)->name : 'N\A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-print-4"></div>
                </div>
                <div class="row">
                    <div class="col-print-5">
                        <div class="row">
                            <div class="col-print-7">
                                <div class="col-title font-family"><b>Expected Arrival Date</b></div>
                            </div>
                            <div class="col-print-5">
                                <div class="col-text font-family">: {{ $booking->check_in_date != null ? $booking->check_in_date : 'N\A' }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-print-7">
                                <div class="col-title font-family"><b>Expected Departure Date</b></div>
                            </div>
                            <div class="col-print-5">
                                <div class="col-text font-family">: {{ $booking->check_out_date != null ? $booking->check_out_date : 'N\A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-print-3">
                        <div class="row">
                            <div class="col-print-4">
                                <div class="col-title font-family"><b>Pickup</b></div>
                            </div>
                            <div class="col-print-8">
                                <div class="col-text font-family">: {{ $booking->pickup != null ? $booking->pickup : 'N\A' }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-print-4">
                                <div class="col-title font-family"><b>Drop</b></div>
                            </div>
                            <div class="col-print-8">
                                <div class="col-text font-family">: {{ $booking->drop != null ? $booking->drop : 'N\A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-print-4">
                        <div class="row">
                            <div class="col-print-5">
                                <div class="col-title font-family"><b>Flight No./Time</b></div>
                            </div>
                            <div class="col-print-7">
                                <div class="col-text font-family">: {{ $booking->pickup_flight != null ? $booking->pickup_flight : 'N\A' }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-print-5">
                                <div class="col-title font-family"><b>Flight No./Time</b></div>
                            </div>
                            <div class="col-print-7">
                                <div class="col-text font-family">: {{ $booking->drop_flight != null ? $booking->drop_flight : 'N\A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- ROOM INFO -->
            <div class="guest-room-info" style="margin: 0 15px !important; height: 150px;">
                <div class="row">
                    <div class="col-print-8">
                        <div class="row">
                            <div class="col-print-3">
                                <div class="col-title font-family"><b>Room Type</b></div>
                            </div>
                            <div class="col-print-9">
                                <div class="col-text font-family" style="margin-left: 6px;">:
                                    @foreach ($booking->bookingDetails->unique('category_id') as $item)
                                        {{ optional($item->roomCategory)->name }}
                                        @if(!$loop->last),@endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-print-4">
                        <div class="row">
                            <div class="col-print-5">
                                <div class="col-title font-family"><b>PAX</b></div>
                            </div>
                            <div class="col-print-7">
                                <div class="col-text font-family">: {{ 'N\A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" @if ($booking->status == 0) style="display: none" @endif>
                    <div class="col-print-12">
                        <div class="row">
                            <div class="col-print-2" style="padding-right: 12px;">
                                <div class="col-title font-family"><b>Room Numbers</b></div>
                            </div>
                            <div class="col-print-9" style="padding-left: 0px;">
                                <div class="col-text font-family">:
                                    @foreach ($booking->bookingDetails as $item)
                                        {{ optional($item->roomNumber)->room_number }}
                                        @if(!$loop->last),@endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-print-4">
                        <div class="row">
                            <div class="col-print-6">
                                <div class="col-title font-family"><b>Mode of Payment</b></div>
                            </div>
                            <div class="col-print-6">
                                <div class="col-text font-family">: {{ $booking->payment_way != null ? $booking->payment_way : 'N\A' }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-print-6">
                                <div class="col-title font-family"><b>Room Rent</b></div>
                            </div>
                            <div class="col-print-6">
                                <div class="col-text font-family">:
                                    @foreach ($booking->bookingDetails as $item)
                                        {{ optional($item->roomCategory)->price }}
                                        @if(!$loop->last),@endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-print-6">
                                <div class="col-title font-family"><b>Total Amount</b><span class="currency-sign"></span></div>
                            </div>
                            <div class="col-print-6">
                                <div class="col-text font-family">:
                                    {{ calculateCurrencyAmount($transactions->sum('total_amount')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-print-6">
                                <div class="col-title font-family"><b>Smoking Status</b></div>
                            </div>
                            <div class="col-print-6">
                                <div class="col-text font-family">: {{ $item->roomNumber != null ? optional($item->roomNumber)->smoking_status : 'N\A' }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-print-6">
                                <div class="col-title font-family"><b>Reference By</b></div>
                            </div>
                            <div class="col-print-6">
                                <div class="col-text font-family">: {{ $booking->reference != null ? $booking->reference : 'N\A' }}</div>
                            </div>
                        </div>
                        @if ($booking->type != null)
                        <div class="row">
                            <div class="col-print-2">
                                <div class="col-title font-family"><b>Booking Type</b></div>
                            </div>
                            <div class="col-print-10" style="padding-left: 13px !important;">
                                <div class="col-text font-family">:
                                    @if ($booking->type == 1)
                                        FIT
                                    @elseif ($booking->type == 2)
                                        Corporate
                                    @else
                                        Orders
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row" style="margin-top: 0px;">
                            <div class="col-print-6">
                                <div class="col-title font-family"><b>Remarks</b></div>
                            </div>
                            <div class="col-print-6">
                                <div class="col-text font-family">: {{ $booking->check_in_note != null ? $booking->check_in_note : 'N\A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- BOOKING NOTE -->
            <div class="note" style="margin: 0 15px !important;">

                {{-- {!! $bookingNote->title !!} --}}

                <p class="note-title font-family">Please Note:</p>
                @foreach ($bookingNotes as $item)
                    <div class="item">
                        <div class="d-flex">
                            <b class="item-icon font-family">
                                #
                            </b>
                            <div class="item-text font-family">
                                {{ $item->title }}
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>


            <!-- PRINT FOOTER -->
            <div class="print-footer">
                <b class="footer-note font-family"><span class="footer-hash font-family">#</span>please provide us your estimated time of arrival in order to get your room ready upon arrival</b>
                <div class="ending-message font-family">Thank you again for showing interest in <b class="font-family">{{ $company->name }}</b>. Is there anything we can do to make your stay more rewarding, please as</div>
                <div class="with-regards font-family">With Best Regards</div>
                <div class="font-family" style="font-weight: bold !important">{{ $company->name }}</div>
                @if ($company->head_office != null) <div style="font-weight: normal !important" class="footer-name font-family footer-company-address">{{ $company->head_office }}</div> @endif
            </div>

        </div>
    </div>

</body>

</html>
