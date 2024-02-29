<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print production Receive</title>
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

    <style>
        @media print {
            .d-print-none {
                display: none !important;
            }

            .margin-top {
                margin-top: 20px !important;
            }

            .d-none {
                display: block !important;
            }
        }

        table {
            border: none !important;
        }

        tr {
            border: none !important;
        }

        .border {
            border: 1px solid gray !important;
        }

        .border-none {
            border: none !important;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 margin-top" style="margin-top: 60px">
            <div id="customer_info" style="padding: 0 10px;">
                <div class="row">

                    <div class="company-info text-center">
                        <h4>{{ optional($sale->company)->name }}</h4>
                        <p>{{ optional($sale->company)->head_office }}</p>
                        <p>{{ optional($sale->company)->phone_number }},
                            {{ optional($sale->company)->email }}</p>
                    </div>
                    <hr>
                    <div class="customerInfo" style="width: 60%;float: left; ">

                        {{-- <h5><b><u>Guest's Information : </u></b></h5> --}}

                        <p class="patient"><b>Invoice No : </b>{{ $sale->invoice_no }}</p>
                        <p><b>Name :</b>&nbsp;{{ $sale->guest_name ?? '' }}</p>
                        <p><b>Mobile :</b>&nbsp;{{ optional($sale->guestInfo)->phone_no ?? '' }}</p>
                        <p class="patient">
                            <b>Sales By : </b>
                            {{ optional($sale->user)->name }}
                        </p>
                    </div>
                    <div class="invoiceInfo" style="width: 40%;float: left;margin-top: 5px;">
                        <table class="table table-bordered" style="border: none !important;">
                            <tr>
                                <th width="50%" style="border: none !important;"> Invoice No : </th>
                                <th style="border: none !important;">INV-{{ $sale->invoice_no }}</th>
                            </tr>
                            <tr>
                                <td style="border: none !important;"> Invoice Date : </td>
                                <td style="border: none !important;">
                                    {{ $sale->date }}</td>
                            </tr>
                            <tr>
                                <td style="border: none !important;"> Vat Number : </td>
                                <td style="border: none !important;">
                                    {{ $vat_number }}</td>
                            </tr>
                            <tr>
                                <td style="border: none !important;"> Vat Amount : </td>
                                <td style="border: none !important;">
                                    {{ $sale->vat_amount }}</td>
                            </tr>
                            <tr>
                                <td style="border: none !important;"> Service Amount : </td>
                                <td style="border: none !important;">
                                    {{ $sale->service_amount }}</td>
                            </tr>
                            <tr>
                                <td style="border: none !important;"> Payment Way : </td>
                                <td style="border: none !important;">
                                    {{ $sale->payment_way }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="invoice-content">
                <div class="table-responsive">
                    <table class="table table-bordered" style="border: none !important;">
                        <thead>
                            <tr>
                                <th width="5%">SL</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>QTY</th>
                                <th style="text-align: right">Total (&#x09F3;)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_amount = 0;
                            @endphp
                            @foreach ($sale->items as $key => $item)
                                @php
                                    $total_amount = +$item->sales_price;
                                @endphp
                                <tr>
                                    <td>{{ ++$loop->index }}</td>
                                    <td>{{ optional($item->product)->name }}</td>
                                    {{-- &#x09F3; for taka symbol --}}
                                    <td>{{ number_format($item->sales_price, 2) }}</td>
                                    <td class="text-right">{{ $item->quantity }}</td>
                                    <td class="text-right">
                                        {{ number_format($item->item_price, 2) }} &#x09F3;
                                    </td>
                                </tr>
                            @endforeach


                            <tr>
                                <td colspan="4" style="text-align: right; border: none !important;">
                                    Discount : </td>
                                <th style="text-align: right; border: none !important;">
                                    {{ $sale->discount }} &#x09F3;</th>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right; border: none !important;">
                                    Total :</td>
                                <th style="text-align: right; border: none !important;">
                                    {{ number_format($total_amount, 2) }}
                                    &#x09F3;</th>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right; border: none !important;">
                                    Paid : </td>
                                <th style="text-align: right; border: none !important;">
                                    {{ number_format($sale->paid_amount, 2) }}
                                    &#x09F3;</th>
                            </tr>
                            <tr>
                                <td colspan="4" style="text-align: right; border: none !important;">
                                    Due : </td>
                                <th style="text-align: right; border: none !important;">
                                    {{ number_format($sale->due_amount, 2) }}
                                    &#x09F3;</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <h5 style="font-weight: 700;">Amount Paid :
                            {{ number_format($sale->paid_amount, 2 ?? 0) }}
                            &#x09F3;
                        </h5>
                    </div>
                </div>
            </div>

            <div class="print-footer" style="margin-top: 40px;overflow: hidden;width: 100%;padding: 0 10px;">
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
                            Prepared By <br>{{ optional($sale->user)->name }}</h5>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        window.onafterprint = window.close;
        window.print();
    </script>
</body onclick="window.close();">

</html>
