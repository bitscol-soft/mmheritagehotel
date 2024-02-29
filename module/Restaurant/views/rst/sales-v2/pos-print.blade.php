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
        /* .pos-table-body tr{
            display: table-row;
        } */
        /* .pos-table-body td{
            position: relative;
        } */
        /* .pos-table-body td > div{
            position: absolute;
            top: 0;
        } */

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
            /* width: 60px; */
            margin: 1px auto;
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
            padding: 4px;
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
        <a href="{{ route('rst.sales.index') }}" class="btn btn-back">Back</a>
    </div>

    <section id="invoice">
        <div class="container">
            <p style="text-align: center">
                {{ $sale->company->name }}
            </p>
            @if (setting('enable_only_image_for_pos_print') == 1)
            <div class="company-logo">
                {{-- <img src="{{ asset('uploads/company/' . $sale->company->logo) }}" alt="Logo" class="logo"> --}}
                @if($sale->company->logo && file_exists('uploads/company/' . $sale->company->logo))
                    <img src="{{ asset('uploads/company/' . $sale->company->logo) }}" alt="Logo" class="logo">
                @endif
            </div>
            @endif


            <div class="company-info">
                @if (setting('enable_only_company_name_for_pos_print') == 1)
                <h4 style="font-size: 18px;">{{ optional($sale->company)->name }}</h4>
                @endif
                <span style="font-size: 6.2px;">{{ optional($sale->company)->head_office }}</span>
                <br>
                <span>{{ "MUSHAK-6.3" }}</span>
                <br>
                <span>{{ @$sale->company->company_details->vat_no }}</span>
                {{-- <p>Website: {{ optional($sale->company)->website }}</p> --}}
                {{-- <p>Website: {{ url('/') }}</p> --}}
            </div>


            <p class="receipt-heading"> Customer Receipt </p>


            <div class="invoice-info">
                <div class="row">
                    <div class="col-8 invoice-no">
                        Invoice No: {{ $sale->invoice_no }}
                    </div>
                    <div class="col-4">
                        Date: {{ $sale->date }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-8 invoice-no">
                        Customer: {{ optional($sale->guestInfo)->name }}
                    </div>
                    <div class="col-4">
                        Table No: {{ optional($sale->table)->table_no ?? "N/A" }}
                        {{-- Table: {{ str_replace(' Restaurant Table', '', optional($sale->table)->name . ' ' . optional($sale->table)->table_no) }} --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        Waiter: {{ $sale->waiter_no ?? "N/A" }}
                    </div>
                    <div class="col-4 invoice-no">
                        Room: {{ optional(optional($sale->booking)->bookingDetails)->first()->roomNumber->room_number ?? 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <p> Address: {{ $sale->guestInfo->address ?? "N/A" }}</p>
                    </div>
                    <div class="col-4 invoice-no">
                        Person:
                    </div>
                </div>
            </div>

            <div class="product-info">

                <table>

                    <thead>
                        <tr>
                            <th width="2%">SL</th>
                            <th width="40%" class="text-left">Item Details</th>
                            <th width="15%">Unit</th>
                            <th width="3%">Qty</th>
                            <th width="15%">Rate</th>
                            <th width="25%">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="pos-table-body">
                        @php
                            $total_amount = 0;
                        @endphp
                        @foreach ($sale->items as $key => $item)
                            @php
                                $total_amount = +$item->sales_price;
                            @endphp
                            <tr>
                                <td>
                                    <div>{{ $loop->iteration }}</div>
                                </td>
                                <td>
                                    {{ optional($item->product)->name }}
                                </td>
                                <td>
                                    <div>{{ optional($item->unit)->name }}</div>
                                </td>
                                <td class="text-right">
                                    <div>{{ $item->quantity }}</div>
                                </td>
                                <td>
                                    <div>{{ number_format($item->sales_price) }}</div>
                                </td>
                                <td class="text-right">
                                    <div>{{'৳ '. number_format($item->quantity * $item->sales_price, 2) }}</div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>


                <div class="invoice-price">
                    <div class="row">
                        <div class="col-9">
                            <p>Subtotal: </p>

                        </div>
                        <div class="col-3">
                            <p>{{ number_format($sale->subtotal, 2, '.', '') }}</p>
                        </div>
                    </div>
                    ------------
                    <div class="row">
                        <div class="col-9">
                            {{-- <p></p><br> --}}
                            <p>Discount:</p>
                            <p>Service Charge:</p>
                            <p>VAT:</p>
                        </div>
                        <div class="col-3">
                            <p>
                                @php
                                    $price_after_vat = number_format($sale->subtotal + $sale->total_vat_amount, 2, '.', '');
                                @endphp
                                {{-- {{ $price_after_vat }} --}}
                            </p>
                            <p><span style="font-size: 7px;">(-)</span>
                                {{ number_format($sale->discount, 2, '.', '') }}
                            </p>
                            <p>
                                <span style="font-size: 7px"></span>
                                {{ number_format($sale->guestInfo->is_stuff == 1 ? 0 : $sale->service_amount, 2, '.', '') }}
                            </p>
                            <p>
                                <span style="font-size: 7px"></span>
                                @if (setting('use_vat_included') == 1)
                                    {{ number_format($sale->vat_amount, 2, '.', '') }}
                                @else
                                    {{-- {{ number_format($sale->items->sum('vat_amount'), 2, '.', '') }} --}}
                                    {{ number_format($sale->guestInfo->is_stuff == 1 ? 0 : $sale->vat_amount, 2, '.', '') }}
                                @endif
                            </p>
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
                                {{-- @php
                                    $price_after_discount = number_format($price_after_vat - $sale->total_discount_amount, 2, '.', '');
                                    @endphp
                                {{ $price_after_discount }} --}}
                                {{ number_format($sale->payable_amount, 2, '.', '') }}
                            </p>
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
                            @if (setting('use_vat_included') == 1)
                                <p>{{ number_format($sale->subtotal, 2, '.', '') }}</p>
                            @else
                                <p>{{ number_format($sale->payable_amount, 2, '.', '') }}</p>
                            @endif
                        </div>
                    </div>
                    ------------
                    <div class="row">
                        <div class="col-9">
                            <p>Paid Amount: </p>
                            <p>Due:</p>
                            <p>Change Amount:</p>
                        </div>
                        <div class="col-3">
                            <p>{{ number_format($sale->paid_amount, 2, '.', '') }}</p>
                            @if (setting('use_vat_included') == 1)
                            <p> {{ $sale->subtotal > $sale->paid_amount ? number_format(( $sale->subtotal - $sale->paid_amount ), 2) : '0' }}</p>
                            @else
                            <p> {{ $sale->payable_amount > $sale->paid_amount ? number_format(( $sale->payable_amount - $sale->paid_amount ), 2) : '0' }}</p>
                            @endif
                            <p>{{ number_format($sale->change_amount, 2, '.', '') }}</p>
                        </div>
                    </div>

                </div>
                <div class="col-12">
                    <p class="text-left" style="font-size: smaller">
                        <p class="amount-in-words" style="padding: inherit; font-size: smaller; font-weight: 700;">InWord:
                            {{ convert_number(calculateCurrencyAmount($sale->payable_amount, 1)) }}
                                Taka Only
                        </p>
                    </p>
                </div>
            </div>


            <div class="footer">
                <p>Thanks for stay with {{ optional($sale->company)->name }}</p>
                <p>Please visit {{ url('/') }} for home delivery</p>
                <p>For any queries complaints or feedback.</p>
                <p>Please call {{ optional($sale->company)->phone_number }}</p>
            </div>
            <div class="bottom-footer"  style="display: flex; justify-content:space-between; padding: 0 10px 10px 10px">
                <div style="text-align: center">
                    <div style="font-size: 11px">------------</div>
                    <span>Cashier Sign</span>
                </div>
                <div style="text-align: center">
                    <div style="font-size: 11px">------------</div>
                    <span>Guest Sign</span>
                </div>
            </div>
        </div>
    </section>


    <section id="invoice">
        <div class="container">
            @if (setting('enable_only_image_for_pos_print') == 1)
            <div class="company-logo">
                {{-- <img src="{{ asset('uploads/company/' . $sale->company->logo) }}" alt="Logo" class="logo"> --}}
                @if($sale->company->logo && file_exists('uploads/company/' . $sale->company->logo))
                    <img src="{{ asset('uploads/company/' . $sale->company->logo) }}" alt="Logo" class="logo">
                @endif
            </div>
            @endif



            <div class="company-info">
                @if (setting('enable_only_company_name_for_pos_print') == 1)
                <h4 style="font-size: 18px;">{{ optional($sale->company)->name }}</h4>
                @endif
                <span style="font-size: 6.2px;">{{ optional($sale->company)->head_office }}</span>
                <br>
                <span>{{ "MUSHAK-6.3" }}</span>
                <br>
                <span>{{ @$sale->company->company_details->vat_no }}</span>
                {{-- <p>Website: {{ optional($sale->company)->website }}</p> --}}
                {{-- <p>Website: {{ url('/') }}</p> --}}
            </div>




            <p class="receipt-heading"> Office Receipt </p>


            <div class="invoice-info">
                <div class="row">
                    <div class="col-8 invoice-no">
                        Invoice No: {{ $sale->invoice_no }}
                    </div>
                    <div class="col-4">
                        Date: {{ $sale->date }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-8 invoice-no">
                        Customer: {{ optional($sale->guestInfo)->name }}
                    </div>
                    <div class="col-4">
                        Table No: {{ optional($sale->table)->table_no ?? "N/A" }}
                        {{-- Table: {{ str_replace(' Restaurant Table', '', optional($sale->table)->name . ' ' . optional($sale->table)->table_no) }} --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        Waiter: {{ $sale->waiter_no ?? "N/A" }}
                    </div>
                    <div class="col-4 invoice-no">
                        Room: {{ optional(optional($sale->booking)->bookingDetails)->first()->roomNumber->room_number ?? 'N/A' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <p> Address: {{ $sale->guestInfo->address ?? "N/A" }}</p>
                    </div>
                    <div class="col-4 invoice-no">
                        Person:
                    </div>
                </div>
            </div>

            <div class="product-info">

                <table>

                    <thead>
                        <tr>
                            <th width="2%">SL</th>
                            <th width="40%" class="text-left">Item Details</th>
                            <th width="15%">Unit</th>
                            <th width="3%">Qty</th>
                            <th width="15%">Rate</th>
                            <th width="25%">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="pos-table-body">
                        @php
                            $total_amount = 0;
                        @endphp
                        @foreach ($sale->items as $key => $item)
                            @php
                                $total_amount = +$item->sales_price;
                            @endphp
                            <tr>
                                <td>
                                    <div>{{ $loop->iteration }}</div>
                                </td>
                                <td>
                                    {{ optional($item->product)->name }}
                                </td>
                                <td>
                                    <div>{{ optional($item->unit)->name }}</div>
                                </td>
                                <td class="text-right">
                                    <div>{{ $item->quantity }}</div>
                                </td>
                                <td>
                                    <div>{{ number_format($item->sales_price) }}</div>
                                </td>
                                <td class="text-right">
                                    <div>{{'৳ '. number_format($item->quantity * $item->sales_price, 2) }}</div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>


                <div class="invoice-price">
                    <div class="row">
                        <div class="col-9">
                            <p>Subtotal: </p>

                        </div>
                        <div class="col-3">
                            <p>{{ number_format($sale->subtotal, 2, '.', '') }}</p>
                        </div>
                    </div>
                    ------------
                    <div class="row">
                        <div class="col-9">
                            {{-- <p></p><br> --}}
                            <p>Discount:</p>
                            <p>Service Charge:</p>
                            <p>VAT:</p>
                        </div>
                        <div class="col-3">
                            <p>
                                @php
                                    $price_after_vat = number_format($sale->subtotal + $sale->total_vat_amount, 2, '.', '');
                                @endphp
                                {{-- {{ $price_after_vat }} --}}
                            </p>
                            <p><span style="font-size: 7px;">(-)</span>
                                {{ number_format($sale->discount, 2, '.', '') }}
                            </p>
                            <p>
                                <span style="font-size: 7px"></span>
                                {{ number_format($sale->service_amount, 2, '.', '') }}
                            </p>
                            <p>
                                <span style="font-size: 7px"></span>
                                @if (setting('use_vat_included') == 1)
                                    {{ number_format($sale->vat_amount, 2, '.', '') }}
                                @else
                                    {{-- {{ number_format($sale->items->sum('vat_amount'), 2, '.', '') }} --}}
                                    {{ number_format($sale->vat_amount, 2, '.', '') }}
                                @endif
                            </p>
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
                                {{-- @php
                                    $price_after_discount = number_format($price_after_vat - $sale->total_discount_amount, 2, '.', '');
                                    @endphp
                                {{ $price_after_discount }} --}}
                                {{ number_format($sale->payable_amount, 2, '.', '') }}
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
                            @if (setting('use_vat_included') == 1)
                                <p>{{ number_format($sale->subtotal, 2, '.', '') }}</p>
                            @else
                                <p>{{ number_format($sale->payable_amount, 2, '.', '') }}</p>
                            @endif
                        </div>
                    </div>
                    ------------
                    <div class="row">
                        <div class="col-9">
                            <p>Paid Amount: </p>
                            <p>Due:</p>
                            <p>Change Amount:</p>
                        </div>
                        <div class="col-3">
                            <p>{{ number_format($sale->paid_amount, 2, '.', '') }}</p>
                            @if (setting('use_vat_included') == 1)
                            <p> {{ $sale->subtotal > $sale->paid_amount ? number_format(( $sale->subtotal - $sale->paid_amount ), 2) : '0' }}</p>
                            @else
                            <p> {{ $sale->payable_amount > $sale->paid_amount ? number_format(( $sale->payable_amount - $sale->paid_amount ), 2) : '0' }}</p>
                            @endif
                            <p>{{ number_format($sale->change_amount, 2, '.', '') }}</p>
                        </div>
                    </div>

                </div>

            </div>


            <div class="col-12">
                <p class="text-left" style="font-size: smaller">
                    <p class="amount-in-words" style="padding: inherit; font-size: smaller; font-weight: 700;">InWord:
                        {{ convert_number(calculateCurrencyAmount($sale->payable_amount, 1)) }}
                            Taka Only
                    </p>
                </p>
            </div>

            <div class="footer">
                <p>Thanks for stay with {{ optional($sale->company)->name }}</p>
                <p>Please visit {{ url('/') }} for home delivery</p>
                <p>For any queries complaints or feedback.</p>
                <p>Please call {{ optional($sale->company)->phone_number }}</p>
            </div>
            <div class="bottom-footer"  style="display: flex; justify-content:space-between; padding: 0 10px 10px 10px">
                <div style="text-align: center">
                    <div style="font-size: 11px">------------</div>
                    <span>Cashier Sign</span>
                </div>
                <div style="text-align: center">
                    <div style="font-size: 11px">------------</div>
                    <span>Guest Sign</span>
                </div>
            </div>
        </div>
    </section>

    <script>
        // window.print()
    </script>
</body>

</html>
