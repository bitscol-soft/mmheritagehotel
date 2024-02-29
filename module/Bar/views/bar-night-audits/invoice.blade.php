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
        body{ font-family: 'Lato', sans-serif !important; }
        .font-family{ font-family: 'Fira Sans', sans-serif !important; }
        .font-bold{
            font-weight: bold;
        }
        @media print {
            body{
                header: page-header;
                footer: page-footer;
                sheet-size: Letter;
                margin: 0 !important;
                background: #efefef;
                font-family: 'Fira Code';
                font-size: 15px;
            }
            /* body{ font-family: 'Fira Sans', sans-serif !important; } */
            body{ font-family: 'Lato', sans-serif !important; }
            .font-family{ font-family: 'Fira Sans', sans-serif !important; }
            .invoice {
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }
            .font-bold{
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
        .print-copy-info{
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
        table, th {
            border-collapse: collapse;
        }
        th, td {
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
        .barcode-img{
            height : 50 px;
            width: 100 px;
        }
        tr:nth-child(even) {
            background: #f1f1f1
        }
        .copy {
            font-size:20px;
        }

        .hr {
            width: 50%;
            float: right;
            /* width: 130px; */
            height: 2px;
            background: #000000;
        }
        #block_container
        {
            padding-top: 15px;
        }
        #bloc1, #bloc2
        {
            display:inline;
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
            tr.page-break  { display: block; page-break-after: always; }
        }
    </style>

    <style>
        .block-container{
            display: flex;
            justify-content: space-between;
        }
        .amount-paid{
            display: flex;
            flex-direction: column;
            /* justify-content: space-between; */
        }
        .total-amount-paid{
            margin-top: 10px;
        }
        .guest-info {
            display: flex;
            margin: 5px 0;
            overflow: hidden;
            height: 70px;
            margin-top: 40px;
        }
        .guest-info .guest-left-side{
            text-align: left;
            width: 45%;
            font-size: 13px;
        }
        .guest-info .guest-right-side {
            text-align: left;
            width: 75%;
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
        .booking-info .info-body{
            display: flex;
            justify-content: end;
        }
        .booking-info .booking-left-side{
            text-align: left;
            width: 42%;
            font-size: 13px;
        }
        .booking-info .booking-right-side{
            text-align: left;
            width: 25%;
            font-size: 13px;
        }
        .badge{
            background: lightgreen;
            border-radius: 2px;
            padding: 1px 3px;
            font-size: 12px;
        }
        thead th{
            font-size: 13px;
            font-weight: bold
        }
        tbody td{
            font-size: 13px;
        }
        tbody tr{
            /* background: #f1f1f1 !important; */
        }
        .invoice-price .left-side{
            font-size: 14px
        }
        .invoice-price .right-side{
            font-size: 14px
        }
        .booking-table-title{
            margin-top: 30px
        }
        .restourant-sale-info-title{
            margin-top: 30px
        }
        .booking-table{
            margin-top: 10px
        }
        .restourant-sale-info{
            margin-top: 10px
        }
        .display-none{
            display: none
        }
        @media print{
            .block-container{
                display: flex;
                justify-content: space-between;
            }
            .amount-paid{
                display: flex;
                flex-direction: column;
                /* justify-content: space-between; */
            }
            .total-amount-paid{
                margin-top: 10px;
            }
            .badge{
                background: lightgreen;
                border-radius: 2px;
                padding: 1px 3px;
                font-size: 12px;
            }
            thead th{
                font-size: 13px;
            }
            tbody td{
                font-size: 13px;
            }
            tbody tr{
                /* background: #f1f1f1 !important; */
            }
            .source-type{
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
                                    <img src="{{ asset('uploads/company/' . $company->logo) }}" class="logo" alt="Company Logo" width="150" height="80">
                                @endif
                            </div>

                            {{-- <div class="guest-info">
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
                            </div> --}}

                        </div>

                        <div class="col-6 date">

                            <div class="company-info-invoice">
                                <p style="font-size: 18px" class="font-bold">{{ optional($company)->name }}</p>
                                <p>{{ optional($company)->head_office }}</p>
                                <p>{{ optional($company)->phone_number }}</p>
                                <p>{{ optional($company)->email }}</p>
                            </div>

                            {{-- <div class="booking-info">
                                <div class="info-body">
                                    <div class="booking-left-side">
                                        <div class="left-item"><b>Total Reservation</b></div>
                                        <div class="left-item"><b>Total Cancelled</b></div>
                                        <div class="left-item"><b>Total Dirty</b></div>
                                    </div>
                                    <div class="booking-right-side">
                                        <div class="right-item">: <b>{{ $audits->sum('total_reservation') ?? 0 }}</b></div>
                                        <div class="right-item">: <b>{{ $audits->sum('total_cancel') ?? 0 }}</b></div>
                                        <div class="right-item">: <b>{{ $audits->sum('total_dirty_room') ?? 0 }}</b></div>
                                    </div>
                                </div>
                            </div> --}}

                        </div>

                    </div>
                </div>


                <br><br><br>
                <br><br><br>

                <!------- BARCODE INFO ------->
                <div class="text-center generate-date">
                    <p style="font-size: 20px;">Bar Night Audit/ Day Closing Report: {{ request('date', optional($audits->first())->date) }}</p>
                </div>


                <!------- RESTOURANT SALE ------->

                <div class="text-center restourant-sale-info-title" style="{{ $audits[0]->restourantCount > 0 ? 'display: block' : 'display: none' }}">
                    <p style="font-size: 18px; text-transform: uppercase;">BAR SALE</p>
                </div>
                <div class="restourant-sale-info" style="{{ $audits[0]->restourantCount > 0 ? 'display: block' : 'display: none' }}">
                    <table>
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">SL</th>
                                <th>Type</th>
                                <th>Invoice No</th>
                                <th>Payment Type</th>
                                <th class="text-right">Total Amount (৳)</th>
                                <th class="text-right">Paid Amount (৳)</th>
                                <th class="text-right">Due Amount (৳)</th>
                            </tr>
                        </thead>

                        @php
                            $total_collection   = $total_due_amount = 0;
                            $total_amount       = $total_paid_amount = $total_due_amount = 0;
                        @endphp

                        <tbody>

                            @foreach ($audits as $audit)

                                @forelse ($audit->details ?? [] as $key => $detail)

                                    @php
                                        $total_amount     = amount($detail->total_amount, optional($detail->transaction)->total_amount);
                                        $total_collection += $total_paid_amount = amount($detail->collection, optional($detail->transaction)->collection);
                                        $total_due_amount += $due_amount = amount($detail->due, optional($detail->transaction)->due_amount);
                                    @endphp
                                    {{-- @if ($detail->transaction->source_type == 'Bar Sale') --}}
                                        <tr>
                                            <td class="text-center ">{{ $loop->iteration }}</td>
                                            <td class="text-center ">{{ optional($detail->transaction)->source_type }}</td>
                                            <td class="text-center ">INV-{{ optional($detail->transaction)->invoice_no }}</td>
                                            <td class="text-center ">{{ optional(optional($detail->transaction)->account)->name ?? 'N\A' }}</td>

                                            <td style="text-align: right;" class="">
                                                <span class="item-total">{{ number_format($total_amount, 2) }}</span>
                                            </td>
                                            <td style="text-align: right;" class="">{{ number_format($total_paid_amount, 2) }}</td>
                                            <td class="text-right ">{{ number_format($due_amount, 2)  }}</td>
                                        </tr>
                                    {{-- @endif --}}
                                @empty
                                    <x-no-table-record />
                                @endforelse

                            @endforeach

                        </tbody>

                    </table>
                </div>

            </div>

            <!----- INVOICE BOTTOM PART [FOOTER] ----->
            <div id="bottomPart" style="{{ $audits[0]->restourantCount > 0 ? 'display: block' : 'display: none' }}">
                <div class="row" style="display: flex; justify-content: space-between">

                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <div class="invoice-price" style="width: 300px; margin-top: 5px;">

                            @foreach ($account_types as $id => $account_type)
                                <div class="row" style="display: flex; justify-content: space-between;">
                                    <div class="left-side" style="width: 60%; text-align: left;">
                                        <p><b>{{ $account_type }} Sale Amount</b></p>
                                    </div>
                                    <div class="right-side" style="width: 40%; text-align: left;">
                                        <p><b>: {{ getTotalPaymentAmount($audits[0]->id, $id, 'Bar Sale') }}</b></p>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-6 col-md-6">
                        <div class="invoice-price" style="width: 280px;">
                            <div class="row" style="display: flex; justify-content: space-between;">
                                <div class="left-side">
                                    <p><b>Total Collection</b></p>
                                    <p><b>Extra Due</b></p>
                                </div>
                                <div class="right-side">
                                    <p><b>: {{ number_format($total_collection, 2) }}</b></p>
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

    <script type="text/javascript">
        window.print();
    </script>

</body>
</html>
