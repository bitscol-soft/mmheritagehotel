@extends('layouts.master')
@section('title', 'Sale Invoice')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
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

        .table {
            box-shadow: none !important;
        }

        .table-bordered>thead>tr>th,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>tbody>tr>td,
        .table-bordered>tfoot>tr>td {
            border: .4px solid #fff;
            padding: 4.5px;
        }

        th {
            background: #efefef;
            box-shadow: none;
        }

        .patient {
            margin: 3px;
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
@stop

{{-- @dd($invoice) --}}
@section('content')

<x-mm.styles />
<x-mm.page class="mm-invoice-page mm-rst" title="Sale invoice" description="Restaurant invoice. Printing outputs the document only.">
    @if (hasPermission('service.view', $slugs))
        <x-slot name="actions">
            <a href="#" class="mm-button" onclick="printPage('print_body'); return false;">
                <i class="fa fa-print" aria-hidden="true"></i> Print
            </a>
        </x-slot>
    @endif

    <x-mm.panel class="tw-p-4">
        <div class="row">
            <div id="print_body">
                <div id="customer_info" style="padding: 0 10px; margin-bottom: 15px">
                    <div class="row">

                       <x-company-info :company="$sale->company" />
                        <hr>

                        <div class="invoiceInfo" style="width: 40%;float: left; margin-right:20%">
                            <table class="table table-bordered" style="border: none !important;">
                                {{-- <div class="customerInfo" style="width: 60%;float: left; ">
                                </div> --}}
                                <tr>
                                    <h5><b><u>Guest's Information : </u></b></h5>
                                </tr>
                                <tr>
                                    <td style="border: none !important; "> Name : </td>
                                    <td style="border: none !important; ">
                                        {{ $sale->guest_name ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none !important;"> Phone No : </td>
                                    <td style="border: none !important;">
                                        {{ optional($sale->guestInfo)->phone_no ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none !important;"> Room No : </td>
                                    <td style="border: none !important;">
                                        {{ optional(optional($sale->booking)->bookingDetails)->first()->roomNumber->room_number ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none !important;"> Person : </td>
                                    <td style="border: none !important;">
                                        {{ "N/A" }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none !important;"> Address : </td>
                                    <td style="border: none !important;">
                                        {{ $sale->guestInfo->address ?? "N/A" }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="invoiceInfo" style="width: 40%;float: left;margin-top: 5px;">
                            <table class="table table-bordered" style="border: none !important;">
                                <tr>
                                    <th width="50%" style="border: none !important; "> Invoice No : </th>
                                    <th style="border: none !important; ">INV-{{ $sale->invoice_no }}</th>
                                </tr>
                                <tr>
                                    <td style="border: none !important;"> BIN : </td>
                                    <td style="border: none !important;">
                                        {{ @$sale->company->company_details->vat_no }}</td>
                                </tr>
                                <tr>
                                    <td style="border: none !important; "> Invoice Date : </td>
                                    <td style="border: none !important; ">
                                        {{ $sale->date }}</td>
                                </tr>
                                {{-- <tr>
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
                                </tr> --}}
                                <tr>
                                    <td style="border: none !important; "> Payment Way : </td>
                                    <td style="border: none !important; ">
                                        @foreach ($sale->transaction_ledgers ?? [] as $item)
                                            {{ optional($item->account)->name }}
                                            @if (!$loop->last),
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none !important;"> Waiter No : </td>
                                    <td style="border: none !important;">
                                        {{ $sale->waiter_no }}</td>
                                </tr>
                                <tr>
                                    <td style="border: none !important;"> Table No : </td>
                                    <td style="border: none !important;">
                                        {{ optional($sale->table)->table_no ?? "N/A" }}
                                    </td>
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
                                        <strong>Vat Amount</strong><span class="currency-sign"></span> : </td>
                                    <th style="text-align: right; border: none !important;">
                                        {{ $sale->guestInfo->is_stuff == 1 ? 0 : $sale->vat_amount }}
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align: right; border: none !important;">
                                        <strong>Service Charge</strong><span class="currency-sign"></span> : </td>
                                    <th style="text-align: right; border: none !important;">
                                        {{ $sale->guestInfo->is_stuff == 1 ? 0 : $sale->service_amount }}
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align: right; border: none !important;">
                                        <strong>Discount</strong> : </td>
                                    <th style="text-align: right; border: none !important;">
                                        {{ $sale->discount }} &#x09F3;</th>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align: right; border: none !important;">
                                        <strong>Total</strong> :</td>
                                    <th style="text-align: right; border: none !important;">
                                        @if (setting('use_vat_included') == 1)
                                        {{ number_format($sale->subtotal, 2) }}
                                        @else
                                        {{ number_format($sale->payable_amount, 2) }}
                                        @endif
                                        &#x09F3;
                                    </th>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align: right; border: none !important;">
                                        <strong>Paid</strong> :</td>
                                    <th style="text-align: right; border: none !important;">
                                        {{ number_format($sale->paid_amount, 2) }}
                                        &#x09F3;</th>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align: right; border: none !important;">
                                        <strong>Due</strong> : </td>
                                    <th style="text-align: right; border: none !important;">
                                        @if (setting('use_vat_included') == 1)
                                        {{ $sale->subtotal > $sale->paid_amount ? number_format(( $sale->subtotal - $sale->paid_amount ), 2) : '0' }}
                                        @else
                                        {{ $sale->payable_amount > $sale->paid_amount ? number_format(( $sale->payable_amount - $sale->paid_amount ), 2) : '0' }}
                                        @endif
                                        &#x09F3;</th>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align: right; border: none !important;">
                                        <strong>Change</strong> : </td>
                                    <th style="text-align: right; border: none !important;">
                                        {{ number_format($sale->change_amount, 2) }}
                                        &#x09F3;
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="amount-in-words" style="padding: inherit">Amount In Words :
                                <b>{{ convert_number(calculateCurrencyAmount($sale->payable_amount, 1)) }}
                                    Taka Only</b>
                            </p>
                            {{-- <h5 style="font-weight: 700;">Amount Paid :
                                {{ number_format($sale->paid_amount, 2 ?? 0) }}
                                &#x09F3;
                            </h5> --}}
                        </div>
                    </div>
                </div>

                <div class="print-footer"
                    style="margin-top: 40px;overflow: hidden;width: 100%;padding: 0 10px;">
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
                    <div class="copyright-section">

                        <p class="text-center mt-30"><i>Treatment to the highest accuracy & excellence in
                                education is
                                our motto</i></p>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </x-mm.panel>
</x-mm.page>
@endsection

@section('js')
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
@stop
