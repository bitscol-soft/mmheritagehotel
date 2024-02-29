@extends('layouts.master')

@section('title', 'Night Audit')

@section('page-header')
    <i class="fa fa-info-circle"></i> Night Audit Detail
@stop


@push('style')
    <style>
        .widget-header {
            background-color: #EAF4FA !important;
            background-image: none !important;
        }


        table thead th {
            background-color: #4d8cb3;
            color: #fff;
        }

        .border-none {
            border: none !important;
        }

        .header-input {
            background: white !important;
            border: none !important;
            font-size: 18px !important;
            font-weight: bold !important;
            padding: 0 !important;
            color: black !important;
            width: 100% !important;
        }
        .footer-input {
            background: white !important;
            border: none !important;
            text-align: right !important;
            font-size: 18px !important;
            font-weight: bold !important;
            padding: 0 !important;
            color: black !important;
            width: 100% !important;
        }

        .print-only {
            display: none;
        }

        @media print {
            @page {
                size: A4 portrait !important;
            }
            .no-print, .no-print * {
                display: none !important;
            }

            .print-only {
                display: block !important;
            }

            .widget-box {
                border: none !important;
            }

            .th-txt-size {
                font-size: 10px !important;
            }

            .td-txt-size {
                font-size: 10px !important;
            }
        }

    </style>
@endpush


@section('content')

    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header no-print">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('night-audits.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> List
                        </a>
                    </span>

                    <span class="widget-toolbar">
                        <a href="javascript:void(0)" onclick="print()">
                            <i class="ace-icon fa fa-print"></i> Print
                        </a>
                    </span>
                </div>



                <div class="widget-body">
                    <div>
                        @include('partials._alert_message')

                    </div>
                    <div class="widget-main">


                        <div class="row">
                            <h3 class="text-center">
                                <strong>Generate Date</strong> :
                                <span>{{ request('date', $audit->date) }}</span>
                            </h3>

                            <hr>

                            <div class="col-sm-8 col-sm-offset-2">
                                <table class="table" style="border: none">

                                    <tr style="border-bottom:none !important">
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important; width: 30%">Total Check In : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audit->total_check_in ?? 0 }}
                                        </th>
                                        <th style="border: none" style="width: 1% !important"></th>
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important; width: 30%">Total Reservation : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audit->total_reservation ?? 0 }}
                                        </th>
                                    </tr>
                                    <tr style="border-bottom:none !important">
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">Total Check Out : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audit->total_check_out ?? 0 }}
                                        </th>

                                        <th style="border: none" style="width: 1% !important"></th>
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">Total Cancelled : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audit->total_cancel ?? 0 }}
                                        </th>
                                    </tr>
                                    <tr style="border-bottom:none !important">
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">Total Room : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audit->total_room ?? 0 }}
                                        </th>

                                        <th style="border: none" style="width: 1% !important"></th>
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">Total Dirty : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audit->total_dirty_room ?? 0 }}
                                        </th>
                                    </tr>
                                </table>
                            </div>


                            <div class="col-md-12">

                                <table class="table table-bordered table-striped table-hover" style="border: none">

                                    <thead>
                                        <tr>
                                            <th class="text-center th-txt-size">SL</th>
                                            <th class="text-center th-txt-size">Type</th>
                                            <th class="text-center th-txt-size">Invoice No</th>
                                            <th class="text-center th-txt-size">Payment Type</th>
                                            <th class="text-center th-txt-size">Room No.</th>
                                            <th class="text-right th-txt-size">Total Amount (৳)</th>
                                            <th class="text-right th-txt-size">Paid Amount (৳)</th>
                                            <th class="text-right th-txt-size" style="width: 14% !important">Due Amount (৳)</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php

                                            $total_collection   = $total_due_amount = 0;
                                            $total_amount       = $total_paid_amount = $total_due_amount = 0;

                                        @endphp

                                        @foreach ($audit->details as $key => $detail)
                                            @php
                                                $total_amount     = optional(optional($detail->transaction)->transaction)->total_amount;
                                                $total_collection += $total_paid_amount = optional(optional($detail->transaction)->transaction)->collection;

                                                $total_due_amount += $due_amount = $total_amount - $total_paid_amount;

                                            @endphp


                                            <tr>
                                                <td class="text-center td-font-size">{{ $loop->iteration }}</td>
                                                <td class="text-center td-font-size">{{ optional(optional($detail->transaction)->transaction)->source_type }}</td>
                                                <td class="text-center td-font-size">INV-{{ optional(optional($detail->transaction)->transaction)->invoice_no }}</td>
                                                <td class="text-center td-font-size">
                                                    {{-- @foreach (optional($detail->transaction)->transaction_ledgers->unique('payment_type') ?? [] as $ledger)
                                                        {{ optional($ledger->account)->name }} @if(!$loop->last) , @endif
                                                    @endforeach --}}
                                                    {{ optional(optional($detail->transaction)->account)->name ?? 'N/A' }}
                                                </td>
                                                <td class="text-center td-font-size">
                                                    @php
                                                        $details = optional(optional($detail->transaction)->source)->bookingDetails;
                                                    @endphp
                                                    @foreach ($details ?? [] as $key => $room)
                                                        <label class="label label-success">{{ optional($room->roomNumber)->room_number }}</label>
                                                    @endforeach
                                                </td>
                                                <td style="text-align: right;" class="td-font-size">
                                                    <span class="item-total">{{ number_format($total_amount, 2) }}</span>
                                                </td>
                                                <td style="text-align: right;" class="td-font-size">{{ number_format($total_paid_amount, 2) }}</td>
                                                <td class="text-right td-font-size">{{ number_format($due_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>


                                    <tfoot>
                                        <tr style="border-bottom:none !important">
                                            <th colspan="7" class="text-right" style="border: none !important">Total Collection</th>
                                            <th class="text-right" style="padding: 3px 7px 0px 0px !important; border: none !important">
                                                {{ $total_collection }}
                                            </th>
                                        </tr>
                                        <tr style="border-bottom:none !important">
                                            <th colspan="7" class="text-right" style="padding: 0 7px 0 0 !important; border: none !important">Total Due</th>
                                            <th class="text-right" style="padding: 0 7px 0 0 !important; border: none !important">
                                                {{ $total_due_amount }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')

@stop
