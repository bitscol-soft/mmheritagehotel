<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Night Audit Invoice</title>

    <style>
        @page {
            header: page-header;
            footer: page-footer;
            sheet-size: Letter;
            margin: 0 !important;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 816px;
            /* height: 1056px; */
            margin: 0px auto;
            background: rgb(224, 224, 224)
        }

        /* body{ font-family: 'Fira Sans', sans-serif !important; } */
        body {
            font-family: 'Lato', sans-serif !important;
        }

        .font-family {
            font-family: 'Fira Sans', sans-serif !important;
        }

        .font-bold {
            font-weight: bold;
        }

        @media print {
            body {
                header: page-header;
                footer: page-footer;
                sheet-size: Letter;
                margin: 0 !important;
                background: #efefef;
                font-family: 'Fira Code';
                font-size: 15px;
            }

            /* body{ font-family: 'Fira Sans', sans-serif !important; } */
            body {
                font-family: 'Lato', sans-serif !important;
            }

            .font-family {
                font-family: 'Fira Sans', sans-serif !important;
            }

            .invoice {
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }

            .font-bold {
                font-weight: bold;
            }
        }

        /* .row:after {
            content: "";
            display: table;
            clear: both;
        } */
        .col-2 {
            float: left;
            width: 16.6666666667%;
        }

        .col-3 {
            float: left;
            width: 25%;
        }

        .col-6 {
            float: left;
            width: 50%;
        }

        .col-9 {
            float: left;
            width: 75%;
        }

        .col-10 {
            float: left;
            width: 83.3333333333%;
        }

        .invoice {
            background: #ffffff;
            width: 100%;
            height: 100%;
            margin: 0 auto;
            margin-top: 50px;
            margin-bottom: 50px;
            padding: 20px;
        }

        .container {
            padding: 5px 15px;
            height: 100%;
            position: relative;
        }

        .company-logo {
            width: 220px;
            height: 80px;
        }

        .company-info-invoice {
            margin-bottom: 18px;
            font-size: 13px;
            height: 70px;
        }

        .print-copy-info {
            margin-bottom: 15px;
            font-size: 13px;
        }

        .logo {
            width: 100%;
            height: 100%;
        }

        .company-info {
            font-size: 15px;
            text-align: center;
        }

        .receipt-heading {
            text-align: center;
            font-weight: 700;
            margin: 0 auto;
            margin-top: 10px;
            margin-bottom: 5px;
            max-width: 450px;
            position: relative;
        }

        .receipt-heading:before {
            content: "";
            display: block;
            width: 130px;
            height: 2px;
            background: #18181b;
            left: 0;
            top: 50%;
            position: absolute;
        }

        .receipt-heading:after {
            content: "";
            display: block;
            width: 130px;
            height: 2px;
            background: #18181b;
            right: 0;
            top: 50%;
            position: absolute;
        }

        .invoice-info {
            margin-bottom: 10px;
        }

        .invoice-info .date {
            text-align: right;
        }

        table {
            width: 100%;
        }

        table,
        th {
            border-collapse: collapse;
        }

        th,
        td {
            padding: 7px 2px;
            border: 1px solid #ccc;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .invoice-price {
            padding: 10px;
            margin-top: 30px;
            margin-bottom: 10px;
            text-align: right;
            padding-right: 0 !important;
        }

        .order-note {
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: left;
        }

        .footer {
            position: absolute;
            right: 0;
            bottom: 0 !important;
            left: 0;
            padding: 1rem;
            text-align: center;
        }

        .footer-message {
            position: absolute;
            right: 0;
            bottom: 0 !important;
            left: 0;
            padding: 1rem;
            text-align: center;
        }

        .barcode-img {
            height: 50 px;
            width: 100 px;
        }

        tr:nth-child(even) {
            background: #f1f1f1
        }

        .copy {
            font-size: 20px;
        }

        .hr {
            width: 50%;
            float: right;
            /* width: 130px; */
            height: 2px;
            background: #000000;
        }

        #block_container {
            padding-top: 15px;
        }

        #bloc1,
        #bloc2 {
            display: inline;
        }

        .right-side {
            width: 50%;
        }

        .left-side {
            width: 50%;
        }
    </style>

    <style>
        @media print {
            tr.page-break {
                display: block;
                page-break-after: always;
            }
        }
    </style>

    <style>
        .block-container {
            display: flex;
            justify-content: space-between;
        }

        .amount-paid {
            display: flex;
            flex-direction: column;
            /* justify-content: space-between; */
        }

        .total-amount-paid {
            margin-top: 10px;
        }

        .guest-info {
            display: flex;
            margin: 5px 0;
            overflow: hidden;
            height: 70px;
            margin-top: 40px;
        }

        .guest-info .guest-left-side {
            text-align: left;
            width: 45%;
            font-size: 13px;
        }

        .guest-info .guest-right-side {
            text-align: left;
            width: 50%;
            font-size: 13px;
        }

        .booking-info {
            margin: 5px 0;
            overflow: hidden;
            height: 70px;
            margin-top: 50px;
        }

        .generate-date {
            margin: 15px 0;
            border-bottom: 4px solid #f4f4f4;
            padding-bottom: 8px;
            text-transform: uppercase;
        }

        .booking-info .info-body {
            display: flex;
            justify-content: end;
        }

        .booking-info .booking-left-side {
            text-align: left;
            width: 42%;
            font-size: 13px;
        }

        .booking-info .booking-right-side {
            text-align: left;
            width: 25%;
            font-size: 13px;
        }

        .badge {
            background: lightgreen;
            border-radius: 2px;
            padding: 1px 3px;
            font-size: 12px;
        }

        thead th {
            font-size: 13px;
            font-weight: bold
        }

        tbody td {
            font-size: 13px;
        }

        tbody tr {
            /* background: #f1f1f1 !important; */
        }

        .invoice-price .left-side {
            font-size: 14px
        }

        .invoice-price .right-side {
            font-size: 14px
        }

        .booking-table-title {
            margin-top: 30px
        }

        .restourant-sale-info-title {
            margin-top: 30px
        }

        .booking-table {
            margin-top: 10px
        }

        .restourant-sale-info {
            margin-top: 10px
        }

        .display-none {
            display: none
        }

        @media print {
            .block-container {
                display: flex;
                justify-content: space-between;
            }

            .amount-paid {
                display: flex;
                flex-direction: column;
                /* justify-content: space-between; */
            }

            .total-amount-paid {
                margin-top: 10px;
            }

            .badge {
                background: lightgreen;
                border-radius: 2px;
                padding: 1px 3px;
                font-size: 12px;
            }

            thead th {
                font-size: 13px;
            }

            tbody td {
                font-size: 13px;
            }

            tbody tr {
                /* background: #f1f1f1 !important; */
            }

            .source-type {
                margin-left: 1px;
            }
        }
    </style>

</head>

<body>

    <section class="invoice">
        <div class="container">

            <!------- INVOICE TOP PART [HEADER]------->
            <div id="topPart">



                <!------- INVOICE INFO ------->
                <div class="invoice-info" style="margin-top: 0 !important">
                    <div class="row">

                        <div class="col-6">

                            <div class="company-logo">
                                @if (file_exists('uploads/company/' . $company->logo))
                                    <img src="{{ asset('uploads/company/' . $company->logo) }}" class="logo"
                                        alt="Company Logo" width="150" height="80">
                                @endif
                            </div>

                            <div class="guest-info">
                                <div class="guest-left-side">
                                    <div class="left-item"><b>Total Check In</b></div>
                                    <div class="left-item"><b>Total Check Out</b></div>
                                    <div class="left-item"><b>Total Room</b></div>
                                </div>
                                <div class="guest-right-side">
                                    <div class="right-item">: <b>{{ $audits->sum('total_check_in') ?? 0 }}</b></div>
                                    <div class="right-item">: <b>{{ $audits->sum('total_check_out') ?? 0 }}</b></div>
                                    <div class="right-item">: <b>{{ $audits->sum('total_room') ?? 0 }}</b></div>
                                </div>
                            </div>

                        </div>

                        <div class="col-6 date">

                            <div class="company-info-invoice">
                                <p style="font-size: 18px" class="font-bold">{{ optional($company)->name }}</p>
                                <p>{{ optional($company)->head_office }}</p>
                                <p>{{ optional($company)->phone_number }}</p>
                                <p>{{ optional($company)->email }}</p>
                            </div>

                            <div class="booking-info">
                                <div class="info-body">
                                    <div class="booking-left-side">
                                        <div class="left-item"><b>Total Booked</b></div>
                                        <div class="left-item"><b>Total Reservation</b></div>
                                        <div class="left-item"><b>Total Cancelled</b></div>
                                    </div>
                                    <div class="booking-right-side">
                                        <div class="right-item">:
                                            <b>
                                                <span class="showCountRoom">

                                                </span>
                                            </b>
                                        </div>
                                        <div class="right-item">: <b>{{ $audits->sum('total_reservation') ?? 0 }}</b>
                                        </div>
                                        <div class="right-item">: <b>{{ $audits->sum('total_cancel') ?? 0 }}</b></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>





                <!------- AUDIT INFO ------->
                <div class="text-center generate-date">
                    <p style="font-size: 20px;"> Night Audit/ Day Closing Report:
                        {{ request('date', optional($audits->first())->date) }}</p>
                </div>



                <!------- BOOKING START ------->
                <div class="text-center booking-table-title"
                    style="{{ $audits[0]->bookingCount > 0 ? 'display: block' : 'display: none' }}">
                    <p style="font-size: 18px; text-transform: uppercase;">BOOKING</p>
                </div>

                <div class="booking-table-info"
                    style="{{ $audits[0]->bookingCount > 0 ? 'display: block' : 'display: none' }}">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-center">SL</th>
                                <th>Type</th>
                                <th>Invoice No</th>
                                <th>Name</th>
                                <th>Payment Type</th>
                                <th class="text-center">Room</th>
                                <th class="text-center">Extra(৳)</th>
                                <th class="text-center">Discount(৳)</th>
                                <th class="text-center">Total(৳)</th>
                                <th class="text-center">Paid(৳)</th>
                                <th class="text-center">Due(৳)</th>
                            </tr>
                        </thead>

                        @php
                            $total_collection = $total_due_amount = 0;
                            $total_discount = 0;
                            $total_amount = $total_paid_amount = $total_due_amount = 0;
                            $grand_total_amount = 0;
                            $countRoom = 0;
                            $i = 1;
                            $totalBookingDue = 0;
                            $totalBookingCollection = 0;
                            $totalBookingAmount = 0;
                            $BookingDiscount = 0;
                            $iv1discount = 0;
                        @endphp

                        <tbody>

                            @foreach ($audits as $audit)
                                @forelse ($audit->details ?? [] as $key => $detail)
                                    @php
                                        // $total_amount      = optional($detail->transaction)->total_amount;
                                        $total_amount = $detail->total_amount;
                                        $grand_total_amount += $total_amount;
                                        // $total_collection += $total_paid_amount = optional($detail->transaction)->collection;
                                        $total_collection += $total_paid_amount = $detail->collection;
                                        $total_due_amount += $due_amount = optional($detail->transaction)->due_amount;
                                        $total_discount += amount(optional($detail->transaction)->discount);
                                        $iv1discount += amount(optional($detail->transaction)->discount);

                                    @endphp

                                    @if (optional($detail->transaction)->source_type == 'Booking')
                                        @php
                                            $totalBookingDue += amount($detail->due, optional($detail->transaction)->due_amount);
                                            $totalBookingCollection += amount($detail->collection, optional($detail->transaction)->collection);
                                            $totalBookingAmount += amount($detail->total_amount, optional($detail->transaction)->total_amount);
                                            $BookingDiscount += amount(optional($detail->transaction)->discount);
                                        @endphp

                                        <tr>
                                            <td class="text-center ">{{ $i }}</td>
                                            <td class="text-center ">{{ optional($detail->transaction)->source_type }}
                                            </td>
                                            <td class="text-center ">
                                                INV-{{ optional($detail->transaction)->invoice_no }}</td>
                                            <td class="text-center ">
                                                {{ optional(optional(optional($detail->transaction)->source)->guestInfo)->name }}
                                            </td>
                                            <td class="text-center ">
                                                @foreach (optional($detail->transaction)->transaction_ledgers->unique('payment_type') as $ledger)
                                                    {{ optional($ledger->account)->name ?? 'N\A' }}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach

                                            </td>
                                            <td class="text-center ">
                                                {{-- @if ($detail->transaction->source_type == 'Booking') --}}
                                                @php
                                                    $details = optional(optional($detail->transaction)->source)->details;
                                                    $countRoom += $details->count();

                                                @endphp
                                                @foreach ($details ?? [] as $key => $room)
                                                    <label
                                                        class="label label-success">{{ optional($room->roomNumber)->room_number }}</label>
                                                @endforeach
                                                {{-- @else
                                                    <label class="label label-default">N\A</label>
                                                @endif --}}

                                            </td>
                                            <td style="text-align: right;" class="">
                                                <span
                                                    class="item-total">{{ number_format(optional($detail->transaction)->extra_charge, 2) }}</span>
                                            </td>
                                            <td style="text-align: right;" class="">
                                                <span class="item-total">
                                                    {{ number_format($detail->transaction->discount, 2) }}
                                                </span>
                                            </td>
                                            <td style="text-align: right;" class="">
                                                <span class="item-total">{{ number_format($total_amount, 2) }}</span>
                                            </td>
                                            <td style="text-align: right;" class="">
                                                {{ number_format($total_paid_amount, 2) }}</td>
                                            <td class="text-right ">
                                                {{ number_format($due_amount - $detail->transaction->discount, 2) }}
                                            </td>
                                        </tr>
                                        @php
                                            $i = $i + 1;
                                        @endphp
                                    @endif
                                @empty
                                    <x-no-table-record />
                                @endforelse
                            @endforeach

                        </tbody>

                    </table>
                </div>

                <div id="bottomPart" style="{{ $audits[0]->bookingCount > 0 ? 'display: block' : 'display: none' }}">
                    <div class="row" style="display: flex; justify-content: space-between">
                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="invoice-price" style="width: 300px; margin-top: 5px;">
                                @foreach ($account_types as $id => $account_type)
                                    <div class="row" style="display: flex; justify-content: space-between;">
                                        <div class="left-side" style="width: 60%; text-align: left;">
                                            <p><b>{{ $account_type }} Sale Amount</b></p>
                                        </div>
                                        <div class="right-side" style="width: 40%; text-align: left;">
                                            @php
                                                if($account_type == 'Cash'){
                                                    $accountType = getTotalPaymentAmountBooking($audits[0]->id, $id, 'Booking') + getTotalPaymentAmountBooking($audits[0]->id, null, 'Booking');
                                                }else{
                                                    $accountType = getTotalPaymentAmountBooking($audits[0]->id, $id, 'Booking');
                                                }
                                            @endphp
                                            <p><b>: {{ $accountType }}</b></p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="invoice-price" style="width: 320px; margin-top: 5px;">
                                <div class="row" style="display: flex; justify-content: space-between;">
                                    <div class="left-side" style="width: 60%; text-align: left;">
                                        <p><b>Total Amount</b></p>
                                        <p><b>Total Discount</b></p>
                                        <p><b>Total Collection</b></p>
                                        <p><b>Total Due</b></p>
                                    </div>

                                    <div class="right-side" style="width: 35%; text-align: left;">
                                        <p><b>: {{ number_format($totalBookingAmount, 2) }}</b></p>
                                        <p><b>: {{ number_format($iv1discount, 2) }}</b></p>
                                        <p><b>: {{ number_format($totalBookingCollection, 2) }}</b></p>
                                        <p><b>: {{ number_format($totalBookingDue, 2) }}</b></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="footer-message">
                        <p></p>
                    </div>
                </div>
                <!------- BOOKING END ------->



                <!------- RESTOURANT SALE START ------->
                <div class="text-center restourant-sale-info-title"
                    style="{{ $audits[0]->restourantCount > 0 ? 'display: block' : 'display: none' }}">
                    <p style="font-size: 18px; text-transform: uppercase;">RESTAURANT SALE</p>
                </div>

                <div class="restourant-sale-info"
                    style="{{ $audits[0]->restourantCount > 0 ? 'display: block' : 'display: none' }}">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-center">SL</th>
                                <th>Type</th>
                                <th>Invoice No</th>
                                <th>Name</th>
                                <th>Payment Type</th>
                                <th class="text-center">Total(৳)</th>
                                <th class="text-center">Discount(৳)</th>
                                <th class="text-center">Paid(৳)</th>
                                <th class="text-center">Due(৳)</th>
                            </tr>
                        </thead>

                        @php
                            $total_collection = $total_due_amount = 0;
                            $total_discount = 0;
                            $total_amount = $total_paid_amount = $total_due_amount = 0;
                            $i = 1;
                            $totalRestaurantDue = 0;
                            $totalRestaurantCollection = 0;
                            $totalRestaurantAmount = 0;
                            $RestaurantDiscount = 0;
                        @endphp

                        <tbody>

                            @foreach ($audits as $audit)
                                @forelse ($audit->details ?? [] as $key => $detail)
                                    @php
                                        $total_amount = optional($detail->transaction)->total_amount;
                                        $total_collection += $total_paid_amount = optional($detail->transaction)->collection;
                                        $total_due_amount += $due_amount = optional($detail->transaction)->due_amount;
                                        $total_discount += amount(optional($detail->transaction)->discount);
                                    @endphp

                                    @if (optional($detail->transaction)->source_type == 'Restaurant Sale')
                                        @php
                                            $totalRestaurantDue += amount($detail->due, optional($detail->transaction)->due_amount);
                                            $totalRestaurantCollection += amount($detail->collection, optional($detail->transaction)->collection);
                                            $totalRestaurantAmount += amount($detail->total_amount, optional($detail->transaction)->due_amount);
                                            $RestaurantDiscount += amount(optional($detail->transaction)->discounts);
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $i }}</td>
                                            <td class="text-center">{{ optional($detail->transaction)->source_type }}
                                            </td>
                                            <td class="text-center">
                                                INV-{{ optional($detail->transaction)->invoice_no }}</td>
                                            <td class="text-center">
                                                {{ optional(optional(optional($detail->transaction)->source)->guestInfo)->name }}
                                            </td>
                                            <td class="text-center">
                                                @foreach (optional($detail->transaction)->transaction_ledgers->unique('payment_type') as $ledger)
                                                    {{ optional($ledger->account)->name ?? 'N\A' }}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                                {{-- {{ optional(optional($detail->transaction)->account)->name ?? 'N\A' }} --}}
                                            </td>

                                            <td style="text-align: right;" class="">
                                                <span class="item-total">{{ number_format($total_amount, 2) }}</span>
                                            </td>
                                            <td style="text-align: right;" class="">
                                                <span class="item-total">
                                                    {{ number_format($RestaurantDiscount, 2) }}
                                                </span>
                                            </td>
                                            <td style="text-align: right;" class="">
                                                {{ number_format($total_paid_amount, 2) }}</td>
                                            <td class="text-right ">
                                                {{ $due_amount > 0 ? number_format($due_amount, 2) : 0 }}</td>
                                        </tr>

                                        @php
                                            $i = $i + 1;
                                        @endphp
                                    @endif
                                @empty
                                    <x-no-table-record />
                                @endforelse
                            @endforeach

                        </tbody>

                    </table>
                </div>

                <div id="bottomPart"
                    style="{{ $audits[0]->restourantCount > 0 ? 'display: block' : 'display: none' }}">
                    <div class="row" style="display: flex; justify-content: space-between">

                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="invoice-price" style="width: 300px; margin-top: 5px;">

                                @foreach ($account_types as $id => $account_type)
                                    <div class="row" style="display: flex; justify-content: space-between;">
                                        <div class="left-side" style="width: 60%; text-align: left;">
                                            <p><b>{{ $account_type }} Sale Amount</b></p>
                                        </div>
                                        <div class="right-side" style="width: 40%; text-align: left;">
                                            <p><b>:
                                                    {{ getTotalPaymentAmount($audits[0]->id, $id, 'Restaurant Sale') }}</b>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="invoice-price" style="width: 315px; margin-top: 5px;">
                                <div class="row" style="display: flex; justify-content: space-between;">
                                    <div class="left-side" style="width: 60%; text-align: left;">
                                        <p><b>Total Amount</b></p>
                                        <p><b>Total Discount</b></p>
                                        <p><b>Total Collection</b></p>
                                        <p><b>Total Due</b></p>
                                    </div>
                                    <div class="right-side" style="width: 35%; text-align: left;">
                                        <p><b>: {{ number_format($totalRestaurantAmount, 2) }}</b></p>
                                        <p><b>: {{ number_format($RestaurantDiscount, 2) }}</b></p>
                                        <p><b>: {{ number_format($totalRestaurantCollection, 2) }}</b></p>
                                        <p><b>: {{ number_format($totalRestaurantDue - $total_discount, 2) }}</b></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="footer-message">
                        <p></p>
                    </div>
                </div>
                <!------- RESTOURANT SALE END ------->







                <!------- BAR SALE START ------->
                <div class="text-center restourant-sale-info-title"
                    style="{{ $audits[0]->barSaleCount > 0 ? 'display: block' : 'display: none' }}">
                    <p style="font-size: 18px; text-transform: uppercase;">BAR SALE</p>
                </div>

                <div class="restourant-sale-info"
                    style="{{ $audits[0]->barSaleCount > 0 ? 'display: block' : 'display: none' }}">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-center">SL</th>
                                <th>Type</th>
                                <th>Invoice No</th>
                                <th>Name</th>
                                <th>Payment Type</th>
                                <th class="text-center">Total(৳)</th>
                                <th class="text-center">Discount(৳)</th>
                                <th class="text-center">Paid(৳)</th>
                                <th class="text-center">Due(৳)</th>
                            </tr>
                        </thead>

                        @php
                            $total_collection = $total_due_amount = 0;
                            $total_discount = 0;
                            $total_amount = $total_paid_amount = $total_due_amount = 0;
                            $i = 1;
                            $totalBarDue = 0;
                            $totalBarCollection = 0;
                            $totalBarAmount = 0;
                            $BarDiscount = 0;
                        @endphp

                        <tbody>

                            @foreach ($audits as $audit)
                                @forelse ($audit->details ?? [] as $key => $detail)
                                    @php
                                        $total_amount = optional($detail->transaction)->total_amount + amount(optional($detail->transaction)->discount);
                                        $total_collection += $total_paid_amount = optional($detail->transaction)->collection;
                                        $total_due_amount += $due_amount = optional($detail->transaction)->due_amount;
                                        $total_discount += amount(optional($detail->transaction)->discount);

                                    @endphp
                                    @if (optional($detail->transaction)->source_type == 'Bar Sale')
                                        @php
                                            $totalBarDue += amount($detail->due, optional($detail->transaction)->due_amount);
                                            $totalBarCollection += amount($detail->collection, optional($detail->transaction)->collection);
                                            $totalBarAmount += amount($detail->total_amount, optional($detail->transaction)->total_amount);
                                            // $BarDiscount += amount($detail->item_discount);
                                            $BarDiscount += amount(optional($detail->transaction)->discount);
                                            // @dd($detail);
                                        @endphp

                                        <tr>
                                            <td class="text-center ">{{ $i }}</td>
                                            <td class="text-center ">{{ optional($detail->transaction)->source_type }}
                                            </td>
                                            <td class="text-center ">
                                                INV-{{ optional($detail->transaction)->invoice_no }}</td>
                                            <td class="text-center ">
                                                {{ optional(optional($detail->transaction)->source)->guest_name }}</td>
                                            <td class="text-center ">
                                                @foreach (optional($detail->transaction)->transaction_ledgers->unique('payment_type') as $ledger)
                                                    {{ optional($ledger->account)->name ?? 'N\A' }}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </td>

                                            <td style="text-align: right;" class="">
                                                <span class="item-total">{{ number_format($total_amount, 2) }}</span>
                                            </td>
                                            <td style="text-align: right;" class="">
                                                <span class="item">
                                                    {{ number_format(optional($detail->transaction)->discount, 2) }}
                                                </span>
                                            </td>
                                            <td style="text-align: right;" class="">
                                                {{ number_format($total_paid_amount, 2) }}</td>
                                            <td class="text-right ">{{ number_format($due_amount, 2) }}</td>
                                        </tr>
                                        @php
                                            $i = $i + 1;
                                        @endphp
                                    @endif
                                @empty
                                    <x-no-table-record />
                                @endforelse
                            @endforeach

                        </tbody>

                    </table>
                </div>

                <div id="bottomPart" style="{{ $audits[0]->barSaleCount > 0 ? 'display: block' : 'display: none' }}">
                    <div class="row" style="display: flex; justify-content: space-between">

                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="invoice-price" style="width: 300px; margin-top: 5px;">

                                @foreach ($account_types as $id => $account_type)
                                    <div class="row" style="display: flex; justify-content: space-between;">
                                        <div class="left-side" style="width: 60%; text-align: left;">
                                            <p><b>{{ $account_type }} Sale Amount</b></p>
                                        </div>
                                        <div class="right-side" style="width: 40%; text-align: left;">
                                            <p><b>: {{ getTotalPaymentAmount($audits[0]->id, $id, 'Bar Sale') }}</b>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="invoice-price" style="width: 300px; margin-top: 5px;">
                                <div class="row" style="display: flex; justify-content: space-between;">
                                    <div class="left-side" style="width: 60%; text-align: left;">
                                        <p><b>Total Amount</b></p>
                                        <p><b>Total Discount</b></p>
                                        <p><b>Total Collection</b></p>
                                        <p><b>Total Due</b></p>
                                    </div>
                                    <div class="right-side" style="width: 40%; text-align: left;">
                                        <p><b>: {{ number_format($totalBarAmount, 2) }}</b></p>
                                        <p><b>: {{ number_format($BarDiscount, 2) }}</b></p>
                                        <p><b>: {{ number_format($totalBarCollection - $BarDiscount, 2) }}</b></p>
                                        <p><b>: {{ number_format($totalBarDue, 2) }}</b></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="footer-message">
                        <p></p>
                    </div>
                </div>
                <!------- BAR SALE END ------->




                <!------- HOTEL SERVICE SALE START ------->
                <div class="text-center restourant-sale-info-title"
                    style="{{ $audits[0]->hotelServiceCount > 0 ? 'display: block' : 'display: none' }}">
                    <p style="font-size: 18px; text-transform: uppercase;">HOTEL SERVICE SALE</p>
                </div>

                <div class="restourant-sale-info"
                    style="{{ $audits[0]->hotelServiceCount > 0 ? 'display: block' : 'display: none' }}">
                    <table>
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">SL</th>
                                <th>Type</th>
                                <th>Invoice No</th>
                                <th>Payment Type</th>
                                <th class="text-center">Room No.</th>
                                <th class="text-right">Total Amount (৳)</th>
                                <th class="text-right">Paid Amount (৳)</th>
                                <th class="text-right">Due Amount (৳)</th>
                            </tr>
                        </thead>

                        @php
                            $total_collection = $total_due_amount = 0;
                            $total_discount = 0;
                            $total_amount = $total_paid_amount = $total_due_amount = 0;
                            $i = 1;
                            $totalServiceDue = 0;
                            $totalServiceCollection = 0;
                        @endphp

                        <tbody>
                            @foreach ($audits as $audit)
                                @forelse ($audit->details ?? [] as $key => $detail)
                                    @php
                                        $total_amount = optional($detail->transaction)->total_amount;
                                        $total_collection += $total_paid_amount = optional($detail->transaction)->collection;
                                        $total_due_amount += $due_amount = optional($detail->transaction)->due_amount;
                                        $total_discount += amount(optional($detail->transaction)->discount);

                                    @endphp
                                    @if (optional($detail->transaction)->source_type == 'Hotel Service Sale')
                                        @php
                                            $totalServiceDue += amount($detail->due, optional($detail->transaction)->due_amount);
                                            $totalServiceCollection += amount($detail->collection, optional($detail->transaction)->collection);
                                        @endphp

                                        <tr>
                                            <td class="text-center ">{{ $i }}</td>
                                            <td class="text-center ">{{ optional($detail->transaction)->source_type }}
                                            </td>
                                            <td class="text-center ">
                                                INV-{{ optional($detail->transaction)->invoice_no }}</td>
                                            <td class="text-center ">
                                                {{ optional(optional($detail->transaction)->account)->name ?? 'N\A' }}
                                            </td>
                                            <td class="text-center ">

                                                <label class="label label-default">N\A</label>

                                            </td>
                                            <td style="text-align: right;" class="">
                                                <span class="item-total">{{ number_format($total_amount, 2) }}</span>
                                            </td>
                                            <td style="text-align: right;" class="">
                                                {{ number_format($total_paid_amount, 2) }}</td>
                                            <td class="text-right ">{{ number_format($due_amount, 2) }}</td>
                                        </tr>
                                        @php
                                            $i = $i + 1;
                                        @endphp
                                    @endif
                                @empty
                                    <x-no-table-record />
                                @endforelse
                            @endforeach

                        </tbody>

                    </table>
                </div>


                <div id="bottomPart"
                    style="{{ $audits[0]->hotelServiceCount > 0 ? 'display: block' : 'display: none' }}">
                    <div class="row" style="display: flex; justify-content: space-between">

                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="invoice-price" style="width: 300px; margin-top: 5px;">

                                @foreach ($account_types as $id => $account_type)
                                    <div class="row" style="display: flex; justify-content: space-between;">
                                        <div class="left-side" style="width: 60%; text-align: left;">
                                            <p><b>{{ $account_type }} Sale Amount</b></p>
                                        </div>
                                        <div class="right-side" style="width: 40%; text-align: left;">
                                            <p><b>:
                                                    {{ getTotalPaymentAmount($audits[0]->id, $id, 'Hotel Service Sale') }}</b>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-6 col-md-6">
                            <div class="invoice-price" style="width: 300px; margin-top: 5px;">
                                <div class="row" style="display: flex; justify-content: space-between;">
                                    <div class="left-side" style="width: 60%; text-align: left;">
                                        <p><b>Total Service Collection</b></p>
                                        <p><b>Total Service Due</b></p>
                                    </div>
                                    <div class="right-side" style="width: 40%; text-align: left;">
                                        <p><b>: {{ number_format($totalServiceCollection, 2) }}</b></p>
                                        <p><b>: {{ number_format($totalServiceDue, 2) }}</b></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="footer-message">
                        <p></p>
                    </div>
                </div>
                <!------- HOTEL SERVICE SALE END ------->



            </div>

            <!----- INVOICE BOTTOM PART [FOOTER] ----->
            <div id="bottomPart">
                <div class="row" style="display: flex; justify-content: end">
                    <div class="col-sm-8 col-lg-8 col-md-8 ">
                        <div class="invoice-price" style="width: 300px;">
                            <div class="row" style="display: flex; justify-content: space-between;">
                                <div class="left-side" style="width: 60%">
                                    <p><b>Total Collection</b></p>
                                    <p><b>Total Discount</b></p>
                                    <p><b>Total Due</b></p>
                                </div>
                                <div class="right-side" style="width: 40%">
                                    <p><b>: {{ number_format($total_collection - $total_discount, 2) }}</b></p>
                                    <p><b>: {{ number_format($total_discount, 2) }}</b></p>
                                    <p><b>: {{ number_format($total_due_amount, 2) }}</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-message">
                    <p></p>
                </div>
            </div>

        </div>

    </section>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        let countRoom = '{{ $countRoom }}';
        $('.showCountRoom').text(countRoom);


        window.print();
    </script>

</body>

</html>
