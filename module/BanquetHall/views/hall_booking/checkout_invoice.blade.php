@extends('layouts.master')
@section('title', 'Service Invoice')

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

    @include('booking._css.invoice-sheet')
@stop

@section('content')

    @php
        $sub_total = $booking->sub_total;
        $adv_amount = $booking->advanced_payment;
        $vat = optional($booking->getVat)->hotel_vat;
        $vat_amount = ($sub_total / 100) * $vat;
        $total_amount = $sub_total + $vat_amount;
        $current_due = $total_amount - $adv_amount;
    @endphp


    <x-mm.styles />
    <x-mm.page class="mm-invoice-page" title="Booking Invoice">
        <x-slot name="actions">
            @if (hasPermission('service.view', $slugs))
                <a href="#" onclick="printPage('print_body')" class="btn btn-sm btn-default hidden-print">
                    <i class="fa fa-print"></i> Print
                </a>
            @endif
        </x-slot>

        <x-mm.panel>
                        <div class="row">
                            <div id="print_body" class="invoice-doc">
                                <div class="inv-head">
                                    <div class="inv-brand">
                                        <h3>{{ $company->name }}</h3>
                                        <p>{{ $company->head_office }}</p>
                                        <p>{{ $company->phone_number }}@if (!empty($company->email)), {{ $company->email }}@endif</p>
                                    </div>
                                    <div class="inv-doctitle">
                                        <div class="inv-kind">Invoice</div>
                                        <div class="inv-no">BK-{{ $booking->booking_number }}</div>
                                        <div class="inv-printed">Printed {{ date('d M Y, h:i A') }}</div>
                                    </div>
                                </div>

                                <div class="inv-panels">
                                    <div class="inv-panel">
                                        <p class="inv-panel-title">Guest</p>
                                        <p class="inv-defrow"><span>Name</span><span>{{ optional($booking->guestInfo)->name ?: '—' }}</span></p>
                                        <p class="inv-defrow"><span>Address</span><span>{{ optional($booking->guestInfo)->address ?: '—' }}</span></p>
                                        <p class="inv-defrow"><span>Mobile</span><span>{{ optional($booking->guestInfo)->phone_no ?: '—' }}</span></p>
                                        <p class="inv-defrow"><span>Nationality</span><span>{{ optional(optional($booking->guestInfo)->country)->name ?: '—' }}</span></p>
                                        <p class="inv-defrow"><span>Room</span><span>{{ optional(optional($booking->bookingDetail)->roomCategory)->name }} - {{ optional(optional($booking->bookingDetail)->roomNumber)->room_number }}</span></p>
                                    </div>
                                    <div class="inv-panel">
                                        <p class="inv-panel-title">Booking</p>
                                        <p class="inv-defrow"><span>Booking No</span><span>BK-{{ $booking->booking_number }}</span></p>
                                        <p class="inv-defrow"><span>Booking Date</span><span>{{ $booking->booking_date }}</span></p>
                                        <p class="inv-defrow"><span>Check In</span><span>{{ $booking->check_in_date }}</span></p>
                                        <p class="inv-defrow"><span>Check Out</span><span>{{ $booking->check_out_date }}</span></p>
                                        <p class="inv-defrow"><span>Payment Type</span><span>{{ $booking->paymentType->name ?? 'None' }}</span></p>
                                        <p class="inv-defrow"><span>Service Charge</span><span>{{ number_format($booking->service_amount, 2) }} &#x09F3;</span></p>
                                        <p class="inv-defrow"><span>VAT Number</span><span>{{ vatSetting()->vat_number }}</span></p>
                                    </div>
                                </div>

                                <div class="invoice-content">
                                    <div class="table-responsive">
                                        <table class="table inv-lines">
                                            <thead>
                                                <tr>
                                                    <th width="5%" class="text-center">SL</th>
                                                    <th>Service/Category</th>
                                                    <th class="text-center">Room/Booking Number</th>
                                                    <th class="text-right">Total Amount (&#x09F3;)</th>
                                                    <th class="text-right">Paid Amount (&#x09F3;)</th>
                                                    <th class="text-right">Due Amount (&#x09F3;)</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $total_amount = 0;
                                                    $total_paid_amount = 0;
                                                    $total_due_amount = 0;
                                                    $total_vat_amount = 0;
                                                    $sl = 1;
                                                    $net_collection = 0;

                                                @endphp


                                                {{-- Booking Service --}}
                                                @foreach ($booking->bookingDetails as $item)

                                                    @php

                                                        if ($booking->vat_amount > 0) {
                                                            $total_vat_amount = $applicable_vat = $booking->vat_amount;
                                                            $line_amount = $item->total_amount;
                                                        } else {
                                                            $total_vat_amount = $applicable_vat = getAmountTotalOfXPercent($total_amount, $vat);
                                                            $line_amount = $item->total_amount;
                                                        }

                                                        $total_amount += $item->bookingTransection->total_amount;
                                                        $net_collection = $total_paid_amount = $item->bookingTransection->collection;

                                                        $vat = (int) optional($booking->getVat)->hotel_vat;

                                                        $total_due_amount = $booking->transection->due_amount;
                                                        if ($total_due_amount > $line_amount) {
                                                            $total_due_amount = $total_due_amount - $total_paid_amount;
                                                        }else{
                                                            $total_due_amount = $total_paid_amount - $total_due_amount;
                                                        }

                                                    @endphp

                                                    <tr style="background: #fbeeec">
                                                        <td class="text-center">
                                                            {{ $sl++ }}
                                                            <input type="hidden" name="item_ids[]"
                                                                value="{{ $booking->id }}">
                                                            <input type="hidden" name="item_types[]" value="Booking">
                                                            <input type="hidden" name="item_amount[]"
                                                                value="{{ $total_due_amount }}">
                                                        </td>

                                                        <td>{{ optional($item->roomCategory)->name }}</td>
                                                        <td class="text-center">
                                                            {{ optional($item->roomNumber)->room_number }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($line_amount, 2 ?? 0) }}
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($total_paid_amount, 2 ?? 0) }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($total_due_amount, 2 ?? 0) }}</td>
                                                    </tr>
                                                @endforeach






                                                {{-- Booking Adjust Service --}}
                                                @foreach ($booking->bookingAdjusts as $bookingAdjust)
                                                    @php
                                                        $payable_amount = $bookingAdjust->total_amount;
                                                        $paid_amount = optional($bookingAdjust->transactions)->sum('collection');
                                                        $due_amount = $payable_amount - $paid_amount;

                                                        $total_amount += $payable_amount;
                                                        $total_paid_amount += $paid_amount;
                                                        $total_due_amount += $due_amount;
                                                        $net_collection = $total_paid_amount;
                                                    @endphp



                                                    <tr style="background: #fbeeec">
                                                        <td class="text-center">
                                                            {{ $sl++ }}

                                                            <input type="hidden" name="item_ids[]"
                                                                value="{{ $bookingAdjust->id }}">
                                                            <input type="hidden" name="item_types[]"
                                                                value="Booking Adjust">
                                                            <input type="hidden" name="item_amount[]"
                                                                value="{{ $due_amount }}">
                                                        </td>

                                                        <td>Booking Adjust</td>

                                                        <td class="text-center">
                                                            INV-{{ optional($bookingAdjust->transactions->first())->invoice_no }}
                                                        </td>

                                                        <td style="text-align: right; padding-right: 15px !important">
                                                            {{ number_format($payable_amount, 2) }}
                                                        </td>

                                                        <td style="text-align: right; padding-right: 15px !important">
                                                            {{ number_format($paid_amount, 2) }}
                                                        </td>

                                                        <td class="text-right">
                                                            {{ $due_amount }}
                                                        </td>
                                                    </tr>
                                                @endforeach





                                                {{-- Hotel Service --}}
                                                @foreach ($booking->hotelServiceSale ?? [] as $service)
                                                    @php
                                                        $payable_amount = $service->payable_amount;
                                                        $paid_amount = optional($service->transactions)->sum('collection');
                                                        $due_amount = $payable_amount - $paid_amount;

                                                    @endphp


                                                    {{-- @if ($due_amount > 0) --}}

                                                    @php
                                                        $total_amount += $payable_amount;
                                                        $total_paid_amount += $paid_amount;
                                                        $total_due_amount += $due_amount;
                                                        $net_collection = $total_paid_amount;
                                                    @endphp


                                                    <tr style="background: #e9e3e2">
                                                        <td class="text-center">
                                                            {{ $sl++ }}
                                                            <input type="hidden" name="item_ids[]"
                                                                value="{{ $service->id }}">
                                                            <input type="hidden" name="item_types[]"
                                                                value="Hotel Service Sale">
                                                            <input type="hidden" name="item_amount[]"
                                                                value="{{ $due_amount }}">
                                                        </td>

                                                        {{-- <td>Hotel Service</td> --}}
                                                        <td>{{ optional($service)->getServiceNames() }}</td>

                                                        <td class="text-center">
                                                            INV-{{ $service->invoice_no }}
                                                        </td>

                                                        <td style="text-align: right">
                                                            {{ number_format($payable_amount, 2) }}
                                                        </td>

                                                        <td style="text-align: right">
                                                            {{ number_format($paid_amount, 2) }}
                                                        </td>

                                                        <td class="text-right">
                                                            {{ number_format($due_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach


                                                {{-- Resturent Service --}}
                                                @foreach ($booking->resturentServiceSale as $resturent)
                                                    @php
                                                        $payable_amount = $resturent->subtotal;
                                                        $paid_amount = optional($resturent->RstTransactions)->sum('collection');
                                                        $due_amount = $payable_amount - $paid_amount;
                                                    @endphp

                                                    {{-- @if ($due_amount > 0) --}}

                                                    @php
                                                        $total_amount += $payable_amount;
                                                        $total_paid_amount += $paid_amount;
                                                        $total_due_amount += $due_amount;
                                                        $net_collection = $total_paid_amount;
                                                    @endphp


                                                    <tr style="background: #fbeeec">
                                                        <td class="text-center">
                                                            {{ $sl++ }}

                                                            <input type="hidden" name="item_ids[]"
                                                                value="{{ $resturent->id }}">
                                                            <input type="hidden" name="item_types[]"
                                                                value="{{ $resturent->is_bar ? 'Bar' : 'Restaurant' }} Sale">
                                                            <input type="hidden" name="item_amount[]"
                                                                value="{{ $due_amount }}">
                                                        </td>

                                                        <td>{{ $resturent->is_bar ? 'Bar' : 'Restaurant' }} Sale</td>

                                                        <td class="text-center">
                                                            INV-{{ $resturent->invoice_no }}
                                                        </td>

                                                        <td style="text-align: right;">
                                                            {{ number_format($payable_amount, 2) }}
                                                        </td>

                                                        <td style="text-align: right;">
                                                            {{ number_format($paid_amount, 2) }}
                                                        </td>

                                                        <td class="text-right">
                                                            {{ number_format($due_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <tr class="inv-sumrow">
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Subtotal </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_amount - $total_vat_amount - $booking->service_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr class="inv-sumrow">
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Service Charge </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($booking->service_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr class="inv-sumrow">
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Vat({{ vatSetting()->hotel_vat }}%) </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_vat_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>

                                                <tr class="inv-sumrow inv-grand">
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Total </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_amount, 2 ?? 0) }}
                                                        &#x09F3;</th>
                                                </tr>
                                                <tr class="inv-sumrow">
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Discount </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        0.00
                                                        &#x09F3;</th>
                                                </tr>
                                                <tr class="inv-sumrow">
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">
                                                        Paid</td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($net_collection, 2) }} &#x09F3;</th>
                                                </tr>
                                                <tr class="inv-sumrow inv-grand inv-due">
                                                    <td colspan="5"
                                                        style="text-align: right; border: none !important;">Due
                                                    </td>
                                                    <th style="text-align: right; border: none !important;">
                                                        {{ number_format($total_due_amount, 2) }}
                                                        &#x09F3;</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
</div>

                                <div class="inv-sign">
                                    <div class="sig">Received By</div>
                                    <div class="sig">Authorized By</div>
                                    <div class="sig">Prepared By<br>{{ optional(auth()->user())->name }}</div>
                                </div>
                                <div class="inv-foot">
                                    <span>Thank you for staying with us.</span>
                                    <span>{{ $company->name }}</span>
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
        // round-6: print-on-open kept, but fired after load (old version ran at parse time
        // against a non-existent window.onreadystatechange event)
        window.addEventListener('load', function () {
            setTimeout(function () { $('#print_body').printThis({ importStyle: true }); }, 400);
        });
    </script>
@stop
