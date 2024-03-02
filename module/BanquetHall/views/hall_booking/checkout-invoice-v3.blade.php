<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>

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
            height: 1056px;
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
            padding: 5px 50px;
            height: 100%;
            position: relative;
        }

        .company-logo {
            width: 220px;
            height: 80px;
        }

        .company-info-invoice {
            margin-bottom: 10px;
            font-size: 13px;
            /* height: 70px; */
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
            border-top: 1px solid black;
            border-bottom: 1px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
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
            margin-top: 10px;
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
            height: 22px;
            width: 120px;
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
            /* height: 125px; */
            margin-bottom: 15px;
        }

        .guest-info .guest-left-side {
            text-align: left;
            width: 31%;
        }

        .guest-info .guest-right-side {
            text-align: left;
            width: 65%;
        }

        .booking-info {
            margin: 5px 0;
            overflow: hidden;
            /* height: 145px; */
            margin-bottom: 15px;
        }

        .booking-info .info-body {
            display: flex;
            justify-content: end;
        }

        .booking-info .booking-left-side {
            text-align: left;
            width: 35%;
        }

        .booking-info .booking-right-side {
            text-align: left;
            width: 45%;
        }

        .badge {
            background: lightgreen;
            border-radius: 2px;
            padding: 1px 3px;
            font-size: 12px;
        }

        thead th {
            font-size: 15px;
        }

        tbody td {
            font-size: 15px;
        }

        tbody tr {
            /* background: #f1f1f1 !important; */
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
                font-size: 15px;
            }

            tbody td {
                font-size: 14px;
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

    @php
        $chunk = 13;
        $loop_last = false;
        $is_last = false;
        $currency_id = $transactions->first();

    @endphp

    @foreach ($transactions as $transactionKey => $transaction)

        @foreach (optional($transaction->source)->details->chunk($chunk) as $key => $bookingDetails)
        @foreach (optional($transaction->source)->ProductDetails->chunk($chunk) as $key => $ProductDetails)
        @foreach (optional($transaction->source)->ItemDetails->chunk($chunk) as $key => $ItemDetails)
            <section class="invoice">
                <div class="container">

                    <!------- INVOICE TOP PART [HEADER]------->
                    <div id="topPart">

                        <!------- INVOICE INFO ------->
                        <div class="invoice-info" style="margin-top:0 !important">
                            <div class="row">

                                <div class="col-6">

                                    <div class="company-logo">
                                        @if (file_exists('uploads/company/' . $company->logo))
                                            <img src="{{ asset('uploads/company/' . $company->logo) }}" class="logo"
                                                alt="Company Logo" width="150" height="80">
                                        @endif
                                    </div>

                                </div>

                                <div class="col-6 date">

                                    <div class="company-info-invoice">
                                        <p style="font-size: 18px" class="font-bold">{{ optional($company)->name }}</p>
                                        <p>{{ optional($company)->head_office }}</p>
                                        <p>{{ optional($company)->phone_number }}</p>
                                        <p>{{ optional($company)->email }}</p>
                                    </div>


                                </div>


                                <div class="col-12">
                                    <div class="booking-invoice">
                                        <!------- BARCODE INFO ------->
                                        <div class="text-center" style="position: relative">
                                            <p style="font-size: 16px;">Booking Invoice </p>
                                            @php
                                                $explode = explode('-', $booking->booking_number);
                                                $barcode = $explode[0] . $explode[1] . $explode[2];
                                            @endphp
                                            <img class="barcode-img pt-1"
                                                src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode, 'C128') }}"
                                                alt="barcode" />
                                            <p style="font-size: 14px;">{{ $booking->booking_number }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="guest-info">
                                        <div class="guest-left-side">
                                            <div class="left-item">Name</div>
                                            <div class="left-item">Nationality</div>
                                            <div class="left-item">Mobile</div>
                                            @if (optional($booking->guestInfo)->company_id != null)
                                                <div class="left-item">Company</div>
                                            @endif
                                            <div class="left-item">Address</div>
                                        </div>
                                        <div class="guest-right-side">
                                            <div class="right-item">: {{ optional($booking->guestInfo)->name }}</div>

                                            <div class="right-item">:
                                                {{ optional(optional($booking->guestInfo)->country)->name }}</div>
                                            <div class="right-item">: {{ optional($booking->guestInfo)->phone_no }}
                                            </div>
                                            @if (optional($booking->guestInfo)->company_id != null)
                                                <div class="right-item">:
                                                    {{ getCrmCompany(optional($booking->guestInfo)->company_id) }}
                                                </div>
                                            @endif
                                            @if (optional($booking->guestInfo)->company_id != null &&
                                                    getCrmCompanyAddress(optional($booking->guestInfo)->company_id) != null)
                                                <div class="right-item">:
                                                    {{ getCrmCompanyAddress(optional($booking->guestInfo)->company_id) }}
                                                </div>
                                            @else
                                                <div class="right-item">:
                                                    {{ optional($booking->guestInfo)->address != null ? optional($booking->guestInfo)->address : 'N\A' }}
                                                </div>
                                            @endif

                                        </div>
                                    </div>

                                </div>

                                <div class="col-6 date">
                                    <div class="booking-info">
                                        <div class="info-body">
                                            <div class="booking-left-side">
                                                <div class="left-item">Booking No</div>
                                                <div class="left-item">Booking Date</div>
                                                <div class="left-item">Booked Time</div>
                                                <div class="left-item">Payment Type</div>
                                                <div class="left-item">Pay By</div>
                                                @if (vatSetting()->vat_number != 0 && vatSetting()->vat_number != null)
                                                    <div class="left-item">Vat Number</div>
                                                @endif
                                                @if ($booking->reference != null)
                                                    <div class="left-item">Reference By</div>
                                                @endif
                                                @if ($booking->type != null)
                                                    <div class="left-item">Booking Type</div>
                                                @endif
                                            </div>
                                            <div class="booking-right-side">
                                                <div class="right-item">: {{ $booking->booking_number }}</div>
                                                <div class="right-item">: {{ $booking->booking_date }}</div>
                                                <div class="right-item">: {{ $booking->booked_time }}</div>
                                                <div class="right-item">:
                                                    @foreach ($transaction->transaction_ledgers->unique('payment_type') ?? [] as $ledger)
                                                        {{ optional($ledger->account)->name }} @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                    {{ optional($booking->paymentType)->name }}

                                                </div>
                                                {{-- @dd($booking); --}}
                                                <div class="right-item">: {{ optional($booking->payBy)->name }}</div>
                                                @if (vatSetting()->vat_number != 0 && vatSetting()->vat_number != null)
                                                    <div class="right-item">: {{ vatSetting()->vat_number }}</div>
                                                @endif
                                                @if ($booking->reference != null)
                                                    <div class="right-item">: {{ $booking->reference }}</div>
                                                @endif
                                                @if ($booking->type != null)
                                                    <div class="right-item">:
                                                        @if ($booking->type == 1)
                                                            FIT
                                                        @elseif ($booking->type == 2)
                                                            Corporate
                                                        @else
                                                            Orders
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>




                        <!------- BOOKING INFO ------->
                        <div class="product-info">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">SL</th>
                                        <th>Item</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-right">Qty</th>
                                        <th class="text-right discount">Discount</th>
                                        <th class="text-right">Service Charge</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>

                                @php
                                    $totaldiscount = 0;
                                    $roomDis = 0;
                                    $net_collection = $transactions->sum('collection');
                                    $service_charge = $due_amount = 0;
                                    $dis = $transactions->sum('discount');

                                    $skipCount = floor(count(optional($transaction->source)->details) / $chunk);
                                    $skip = $skipCount ? count(optional($transaction->source)->details) / $skipCount : $chunk;

                                    if ($loop->last) {
                                        $loop_last = true;
                                    }
                                @endphp

                                <tbody>

                                    @foreach ($bookingDetails->take($skip) as $bookingDetail)
                                    @foreach ($ProductDetails->take($skip) as $Product)
                                    @foreach ($ItemDetails->take($skip) as $Item)
                                        @php
                                            $service_charge += $transaction->service_amount;

                                            if ($loop_last) {
                                                if ($loop->count != 13 && $loop->count > 12) {
                                                    $is_last = true;
                                                }
                                            }

                                            $roomDis += $bookingDetail->room_discount;
                                        @endphp
                                        {{-- HALL COLUM --}}
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            </td>
                                            <td class="text-left">
                                                <label class="badge mb-1">Hall No : {{ $bookingDetail->hall->room_number }} (Guest : {{ $bookingDetail->guest_count }})</label>
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($bookingDetail->total_amount) }}
                                            </td>
                                            <td class="text-right">
                                                1
                                            </td>
                                            <td class="text-right">
                                                @if ($bookingDetail->discount_type == 1)
                                                    {{ calculateCurrencyAmount($bookingDetail->room_discount) }}
                                                @else
                                                    {{ calculateCurrencyAmount($bookingDetail->room_discount) }}
                                                @endif
                                            </td>

                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($bookingDetail->service_charge) }}</td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($bookingDetail->total_amount + $bookingDetail->service_charge) }}
                                            </td>
                                        </tr>
                                        {{-- PRODUCT COLUM --}}
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            </td>
                                            <td class="text-left">
                                                <label class="badge mb-1">Product :{{ optional($Product->product)->name }}</label>
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Product->total_amount) }}
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Product->qty) }}
                                            </td>
                                            <td class="text-right">
                                                @if ($Product->discount_type == 1)
                                                    {{ calculateCurrencyAmount($Product->room_discount) }}
                                                @else
                                                    {{ calculateCurrencyAmount($Product->room_discount) }}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Product->service_charge) }}</td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Product->total_amount + $Product->service_charge) }}
                                            </td>
                                        </tr>
                                        {{-- ITEM COLUM --}}
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            </td>
                                            <td class="text-left">
                                                <label class="badge mb-1">Item :{{ $Item->item_name }}</label>
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Item->total_amount) }}
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Item->qty) }}
                                            </td>
                                            <td class="text-right">
                                                @if ($Item->discount_type == 1)
                                                    {{ calculateCurrencyAmount($bookingDetail->room_discount) }}
                                                @else
                                                    {{ calculateCurrencyAmount($bookingDetail->room_discount) }}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Item->service_charge) }}</td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Item->total_amount + $Item->service_charge) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endforeach
                                    @endforeach

                                </tbody>

                            </table>
                        </div>


                        <!------- Product Info INFO ------->
                        {{-- <div class="product-info">
                            <table>
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">SL</th>
                                        <th>Product Name</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-right discount">Qty</th>
                                        <th class="text-right discount">Discount</th>
                                        <th class="text-right">Service Charge</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>

                                @php
                                    $totaldiscount = 0;
                                    $roomDis = 0;
                                    $net_collection = $transactions->sum('collection');
                                    $service_charge = $due_amount = 0;
                                    $dis = $transactions->sum('discount');

                                    $skipCount = floor(count(optional($transaction->source)->details) / $chunk);
                                    $skip = $skipCount ? count(optional($transaction->source)->details) / $skipCount : $chunk;

                                    if ($loop->last) {
                                        $loop_last = true;
                                    }
                                @endphp

                                <tbody>

                                    @foreach ($booking->ProductDetails->take($skip) as $Product)
                                        @php
                                            $service_charge += $transaction->service_amount;

                                            if ($loop_last) {
                                                if ($loop->count != 13 && $loop->count > 12) {
                                                    $is_last = true;
                                                }
                                            }

                                            $roomDis += $bookingDetail->room_discount;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            </td>
                                            <td class="text-center">
                                            <label class="badge mb-1">{{ $Product->product->name }}</label>
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Product->total_amount) }}
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Product->qty) }}
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($bookingDetail->room_discount) }}
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($bookingDetail->service_charge) }}</td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Product->total_amount + $Product->service_charge) }}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div> --}}

                        <!------- ITEM INFO ------->
                        {{-- <div class="product-info">
                            <table >
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">SL</th>
                                        <th>Item Name</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-right qty">Qty</th>
                                        <th class="text-right discount">Discount</th>
                                        <th class="text-right">Service Charge</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>

                                @php
                                    $totaldiscount = 0;
                                    $roomDis = 0;
                                    $net_collection = $transactions->sum('collection');
                                    $service_charge = $due_amount = 0;
                                    $dis = $transactions->sum('discount');

                                    $skipCount = floor(count(optional($transaction->source)->details) / $chunk);
                                    $skip = $skipCount ? count(optional($transaction->source)->details) / $skipCount : $chunk;

                                    if ($loop->last) {
                                        $loop_last = true;
                                    }
                                @endphp

                                <tbody>

                                    @foreach ($booking->ItemDetails->take($skip) as $Item)
                                        @php
                                            $service_charge += $transaction->service_amount;

                                            if ($loop_last) {
                                                if ($loop->count != 13 && $loop->count > 12) {
                                                    $is_last = true;
                                                }
                                            }

                                            $roomDis += $bookingDetail->room_discount;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            </td>
                                            <td class="text-center">
                                                @if ($transaction->source_type == 'Hall Booking')
                                                        <label
                                                            class="badge mb-1">{{ $Item->item_name }}</label>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Item->total_amount) }}
                                            </td>
                                            <td class="text-right">
                                                @if ($Item->discount_type == 1)
                                                    {{ calculateCurrencyAmount($Item->room_discount) }}
                                                @else
                                                    {{ calculateCurrencyAmount($Item->room_discount) }}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ $Item->night_count != null ? $Item->night_count : 'N\A' }}
                                            </td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Item->service_charge) }}</td>
                                            <td class="text-right">
                                                {{ calculateCurrencyAmount($Item->total_amount + $Item->service_charge) }}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div> --}}

                    </div>

                    <!----- INVOICE BOTTOM PART [FOOTER] ----->
                    <div id="bottomPart" style="{{ $loop_last && !$is_last ? '' : 'none' }}">
                        <div class="row" style="display: flex; justify-content:space-between">
                            <div class="col-sm-4 col-lg-4 col-md-4 order-note amount-paid" style="width: 50%;">
                                <p class="amount-in-words">Amount In Words :
                                    <b>{{ convert_number(calculateCurrencyAmount($transactions->sum('total_amount'), 1)) }}
                                    Ringgit Only</b>
                                </p>

                                @if ($booking->check_in_note)
                                    <p class="total-amount-paid">Check In Note :<b> {{ $booking->check_in_note }} </b>
                                    </p>
                                @endif

                                @if ($transaction->extra_charge > 0)
                                    <p class="total-amount-paid">Extra Charge Reason :
                                        @foreach ($booking->bookingExtraCharge as $charge)
                                            <b> {{ $charge->reason }} </b>
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </p>
                                @endif
                            </div>
                            <div class="col-sm-8 col-lg-8 col-md-8 ">
                                <div class="invoice-price" style="width: 280px;">
                                    <div class="row" style="display: flex; justify-content: space-between;">
                                        <div class="left-side">
                                            <p>Total: </p>
                                            {{-- <p>Service Charge: </p> --}}
                                            <p>Extra Charge: </p>
                                            <p>VAT({{ vatSetting()->hotel_vat }}%):</p>
                                            <p>
                                                Discount:(-)
                                            </p>
                                        </div>
                                        <div class="right-side">
                                            <p>{{ calculateCurrencyAmount($transaction->total_due_amount  ) }}
                                            {{-- <p>{{ calculateCurrencyAmount($bookingDetails->sum('total_amount') + $bookingDetails->sum('service_charge')  ) }} --}}
                                            </p>
                                            {{-- <p>{{ number_format($transactions->sum('service_charge'), 2) }}</p> --}}
                                            <p>{{ calculateCurrencyAmount($transaction->extra_charge) }}</p>
                                            <p>{{ calculateCurrencyAmount($transactions->sum('vat_amount')) }}</p>
                                            <p>{{ calculateCurrencyAmount($transactions->sum('discount')) }}
                                            </p>
                                        </div>
                                    </div>
                                    ------------
                                    <div class="row" style="display: flex; justify-content: space-between;">
                                        <div class="left-side">
                                            <p>Total Payable: </p>
                                            <p>Paid Amount: </p>
                                            <p>Due Amount: </p>
                                        </div>
                                        <div class="right-side">
                                            <p>{{ calculateCurrencyAmount($transactions->sum('total_amount') - $dis) }}
                                            </p>
                                            <p>{{ calculateCurrencyAmount($net_collection, 2) }}
                                            </p>
                                            <p><b>{{ calculateCurrencyAmount($transactions->sum('total_amount'), 2) > 0 ? calculateCurrencyAmount($transactions->sum('total_amount') - $transactions->sum('collection') - $transactions->sum('discount'), 2) : '0' }}</b>
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div id="block_container" class="block-container">
                            <div id="bloc1" class="block-item">
                                <p>______________________</p>
                                <p style="text-align:center;">Received By</p>
                            </div>
                            <div id="bloc2" class="block-item">
                                <p>______________________</p>
                                <p style="text-align:center;">Authorized By</p>
                            </div>
                            <div id="bloc1-5" class="block-item">
                                <p>______________________</p>
                                <p style="text-align:center;">Prepared By <br> {{ auth()->user()->name }}</p>
                            </div>
                        </div>

                        <div class="footer-message">
                            <p></p>
                        </div>
                    </div>

                </div>
            </section>
        @endforeach
        @endforeach
        @endforeach

    @endforeach

    <script type="text/javascript">
        window.print();
    </script>

</body>

</html>
