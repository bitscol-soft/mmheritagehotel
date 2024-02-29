<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <title>{{ $sale->invoice_no }}</title>




    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @media print {
            #invoice {
                margin-top: 0 !important;
                margin-bottom: 0 !important;
                padding-top: 2px !important;
            }

            .company-logo {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }


            .no-print,
            .no-print * {
                display: none !important;
            }
        }

        body {
            background: #efefef;
            font-family: 'Fira Code', monospace;
            font-size: .6rem;
            font-weight: 600;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .col-2 {
            float: left;
            width: 16.6666666667%;
        }

        .col-3 {
            float: left;
            width: 25%;
        }

        .col-4 {
            float: left;
            width: 33.3333333333%;
        }

        .col-6 {
            float: left;
            width: 50%;
        }

        .col-8 {
            float: left;
            width: 66.6666666666%;
        }

        .col-9 {
            float: left;
            width: 75%;
        }

        .col-10 {
            float: left;
            width: 83.3333333333%;
        }

        #invoice {
            background: #ffffff;
            width: 302.36px;
            min-height: 100px;
            margin: 0 auto;
            margin-top: 10px;
            margin-bottom: 50px;
            padding: 5px;
        }

        .container {
            height: 100%;
        }

        .company-logo {
            width: 60px;
            margin: 0px auto;
            margin-top: 5px;
        }

        .logo {
            width: 100%;
        }

        .company-info {
            font-size: .6rem;
            font-weight: 700;
            text-align: center;
        }

        .receipt-heading {
            text-align: center;
            font-weight: 700;
            margin: 0 auto;
            margin-top: 10px;
            max-width: 450px;
            position: relative;
        }

        .receipt-heading:before {
            content: "";
            display: block;
            width: 70px;
            height: 2px;
            background: #18181b;
            left: 0;
            top: 50%;
            position: absolute;
        }

        .receipt-heading:after {
            content: "";
            display: block;
            width: 70px;
            height: 2px;
            background: #18181b;
            right: 0;
            top: 50%;
            position: absolute;
        }

        .invoice-info {
            margin-top: 10px;
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
            padding: 5px;
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
            padding: 5px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: right;
        }

        .footer {
            margin-top: 10px;
            font-size: .5rem;
            padding: 1rem;
            text-align: center;
        }


        .no-print {
            text-align: center;
            margin-top: 10px;
        }

        .no-print .btn {
            border: none;
            color: white;
            padding: 6px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        .no-print .btn.btn-print {
            background-color: #4CAF50;
        }

        .no-print .btn.btn-back {
            background-color: rgb(250, 0, 0);
        }
    </style>
</head>

<body>


    <div class="no-print">
        <a href="javascript:void(0)" onclick="window.print()" class="btn btn-print"> Print</a>
        <a href="{{ route('bar.sales.index') }}" class="btn btn-back">Back</a>
        {{-- <a href="{{ request()->url() }}" class="btn btn-back">Back</a> --}}
    </div>

    <section id="invoice">
        <div class="container">
            <div class="company-logo">
                <img src="{{ asset('uploads/company/' . $sale->company->logo) }}" alt="Logo" class="logo">
            </div>




            <div class="company-info">
                <h4 style="font-size: 18px;">{{ optional($sale->company)->name }}</h4>
                <p>Phone: {{ optional($sale->company)->phone_number }}</p>
                {{-- <p>Website: {{ optional($sale->company)->website }}</p> --}}
                <p>Website: {{ url('/') }}</p>
            </div>




            <p class="receipt-heading"> Customer Receipt </p>


            <div class="invoice-info">
                <div class="row">
                    <div class="col-8 invoice-no">
                        Invoice No: {{ $sale->invoice_no }}
                    </div>
                    <div class="col-4 date">
                        Date: {{ $sale->date }}
                    </div>
                </div>
                <p>Customer: {{ $sale->guest_name }}</p>
            </div>

            <div class="product-info">

                <p class="receipt-heading" style="margin-bottom: 10px"> Bar Items </p>
                <table>

                    <thead>
                        <tr>
                            <th>SL</th>
                            <th class="text-left">Item</th>
                            <th>Qty</th>
                            <th>Rate</th>
                            <th>C.P</th>
                            <th>Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $total_amount = 0;
                            $total_cp = 0;
                        @endphp
                        @foreach ($sale->items->where('is_bar', 1) as $key => $item)
                            @php
                                $total_amount = +$item->sales_price;
                                $total_cp     = +$item->item_discount;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($item->product)->name }}</td>
                                <td class="">{{ $item->quantity }} {{ optional($item->unit)->name }}</td>
                                <td>{{ number_format($item->sales_price, 2) }}</td>
                                <td>{{ $item->item_discount }}</td>
                                <td class="text-right">
                                    {{ number_format($item->quantity * $item->sales_price - $item->item_discount, 2) }}
                                    &#x09F3;
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

                @if ($sale->items->where('is_bar', 0)->count() > 0)
                    <p class="receipt-heading" style="margin-bottom:10px"> Restaurant Items </p>
                    <table>

                        <thead>
                            <tr>
                                <th>SL</th>
                                <th class="text-left">Item Details</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>C.P</th>
                                <th>Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $total_amount = 0;
                                $total_cp = 0;
                            @endphp
                            @foreach ($sale->items->where('is_bar', 0) as $key => $item)
                                @php
                                    $total_amount = +$item->sales_price;
                                    $total_cp     = +$item->item_discount;

                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ optional($item->product)->name }}</td>
                                    <td>{{ $item->quantity }} {{ optional($item->unit)->name }}</td>
                                    <td>{{ number_format($item->sales_price, 2) }}</td>
                                    <td class="text-right">{{ $item->quantity }}</td>
                                    <td class="text-right">
                                        {{ number_format($item->quantity * $item->sales_price - $item->item_discount, 2) }}
                                        &#x09F3;
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                @endif


                <div class="invoice-price">
                    <div class="row">
                        <div class="col-9">
                            <p>Subtotal: </p>
                            <p>VAT:</p>
                        </div>
                        <div class="col-3">
                            <p>{{ number_format($sale->subtotal, 2, '.', '') }}</p>
                            <p><span style="font-size: 7px"></span>
                                {{ number_format($sale->items->sum('vat_amount'), 2, '.', '') }}</p>
                        </div>
                    </div>
                    ------------
                    <div class="row">
                        <div class="col-9">
                            <p></p><br>
                            <p>Discount:</p>
                            {{-- <p>C.P:</p> --}}
                        </div>
                        <div class="col-3">
                            <p>
                                @php $price_after_vat = number_format($sale->subtotal + $sale->total_vat_amount, 2, '.', ''); @endphp
                                {{ $price_after_vat }}
                            </p>
                            <p>
                                <span style="font-size: 7px;">(-)</span>
                                {{ number_format($sale->discount + $sale->extra_discount_amount, 2, '.', '') }}
                            </p>
                            {{-- <p>
                                <span style="font-size: 7px;">(-)</span>
                                {{ number_format($total_cp, 2, '.', '') }}
                            </p> --}}
                        </div>
                    </div>
                    ------------
                    <div class="row">
                        <div class="col-9">
                            <p></p><br>
                            <p>Rounding:</p>
                        </div>
                        <div class="col-3">
                            <p>
                                @php $price_after_discount = number_format($price_after_vat - $sale->discount, 2, '.', ''); @endphp
                                {{ $price_after_discount }}
                            </p>
                            <p><span style="font-size: 7px">(+/-)</span>
                                {{ number_format($sale->rounding, 2, '.', '') }}</p>
                        </div>
                    </div>
                    ------------
                    <div class="row">
                        <div class="col-9">
                            <p>Total Payable:</p>
                        </div>
                        <div class="col-3">
                            <p>{{ number_format($sale->payable_amount, 2, '.', '') }}</p>
                        </div>
                    </div>
                    ------------
                    <div class="row">

                        <div class="col-9">
                            <p>Paid Amount: </p>
                        </div>
                        <div class="col-3">
                            <p>{{ number_format($sale->paid_amount, 2, '.', '') }}</p>
                        </div>

                        @foreach ($account_types as $id => $account_type)
                            @php
                                $amount = getTotalPaymentTypeInvoice($id, $sale->id, "Bar Sale");
                            @endphp
                            @if (!empty($amount))
                                <div class="col-9">
                                    <p>{{ $account_type }} :</p>
                                </div>
                                <div class="col-3">
                                    <p>{{ $amount }}</p>
                                </div>
                            @endif
                        @endforeach

                        <div class="col-9">
                            <p>Due:</p>
                        </div>
                        <div class="col-3">
                            <p> {{ $sale->payable_amount > $sale->paid_amount ? number_format(( $sale->payable_amount - $sale->paid_amount ), 2, '.', '') : '0' }}</p>
                        </div>

                        <div class="col-9">
                            <p>Change Amount:</p>
                        </div>
                        <div class="col-3">
                            <p>{{ number_format($sale->change_amount, 2, '.', '') }}</p>
                        </div>
                    </div>

                </div>

            </div>




            <div class="footer">
                <p>Thanks for shopping with {{ optional($sale->company)->name }}</p>
                <p>Please visit {{ url('/') }} for home delivery</p>
                <p>For any queries complaints or feedback.</p>
                <p>Please call {{ optional($sale->company)->phone }}</p>
            </div>
        </div>
    </section>


    <script>
        window.print()
    </script>
</body>

</html>
