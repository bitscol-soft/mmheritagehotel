@extends('layouts.master')

@section('title', ' Night Audit')

@section('page-header')
    <i class="fa fa-info-circle"></i> Night Audit</span>
@stop
@section('css')
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
    .successs{
        background-color: #82AF6F;
        color: #fff;
        pointer-events: none;
    }

    .company-info{
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
        .company-info{
            display: block;
        }
    }

</style>
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header hidden-print">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    @if (request('date'))
                    <span class="widget-toolbar">
                        <a href="javascript:void(0)" onclick="print()">
                            <i class="ace-icon fa fa-print"></i> Print
                        </a>
                    </span>
                    @endif
                    <span class="widget-toolbar">
                        <a href="{{ route('night-audits.create') }}">
                            <i class="ace-icon fa fa-plus"></i> Generate
                        </a>
                    </span>

                </div>

                <div class="widget-body">
                    <div class="widget-main">

                        @include('night-audits._inc.room-details')

                        <div class="row">
                            <div class="company-info text-center">
                                <h4>{{ $company->name }}</h4>
                                <p>{{ $company->head_office }}</p>
                                <p>{{ $company->phone_number }},{{ $company->email }},</p>
                            </div>
                            <hr>
                            <h3 class="text-center">
                                <strong>Generate Date</strong> :
                                <span>{{ request('date', optional($audits->first())->date) }}</span>
                            </h3>

                            <hr>

                            <div class="col-sm-8 col-sm-offset-2">
                                <table class="table" style="border: none">

                                    <tr style="border-bottom:none !important">
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important; width: 30%">Total Check In : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audits->sum('total_check_in') ?? 0 }}
                                        </th>
                                        <th style="border: none" style="width: 1% !important"></th>
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important; width: 30%">Total Reservation : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audits->sum('total_reservation') ?? 0 }}
                                        </th>
                                    </tr>
                                    <tr style="border-bottom:none !important">
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">Total Check Out : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audits->sum('total_check_out') ?? 0 }}
                                        </th>

                                        <th style="border: none" style="width: 1% !important"></th>
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">Total Cancelled : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audits->sum('total_cancel') ?? 0 }}
                                        </th>
                                    </tr>
                                    <tr style="border-bottom:none !important">
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">Total Room : </th>
                                        <th class="border-none pointer" style="padding: 0 7px 0 0 !important;" title="Click to see Room Details" data-toggle="modal" data-target="#room-detail-modal">
                                            <span class="blue">{{ $audits->sum('total_room') ?? 0 }}</span>
                                        </th>

                                        <th style="border: none" style="width: 1% !important"></th>
                                        <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">Total Dirty : </th>
                                        <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                            {{ $audits->sum('total_dirty_room') ?? 0 }}
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
                                            $total_discount     =  0;

                                        @endphp

                                        @foreach ($audits as $audit)
                                            @forelse ($audit->details ?? [] as $key => $detail)
                                                @php
                                                    $total_amount     = optional($detail->transaction)->total_amount;
                                                    $total_collection += $total_paid_amount = optional($detail->transaction)->collection;

                                                    $total_due_amount += $due_amount = optional($detail->transaction)->due_amount;
                                                    $total_discount += amount(optional($detail->transaction)->discount);

                                                @endphp

                                                <tr>
                                                    <td class="text-center td-font-size">{{ $loop->iteration }}</td>
                                                    <td class="text-center td-font-size">{{ optional($detail->transaction)->source_type }}</td>
                                                    <td class="text-center td-font-size">INV-{{ optional($detail->transaction)->invoice_no }}</td>
                                                    <td class="text-center td-font-size">{{ optional(optional($detail->transaction)->account)->name ?? 'N\A' }}</td>
                                                    <td class="text-center td-font-size">
                                                        @if ($detail->transaction->source_type == 'Booking')
                                                            @php
                                                                $details = optional(optional($detail->transaction)->source)->details;
                                                            @endphp
                                                            @foreach ($details ?? [] as $key => $room)
                                                                <label class="label label-success">{{ optional($room->roomNumber)->room_number }}</label>
                                                            @endforeach
                                                        @else
                                                            <label class="label label-default">N\A</label>
                                                        @endif

                                                    </td>
                                                    <td style="text-align: right;" class="td-font-size">
                                                        <span class="item-total">{{ number_format($total_amount, 2) }}</span>
                                                    </td>
                                                    <td style="text-align: right;" class="td-font-size">{{ number_format($total_paid_amount, 2) }}</td>
                                                    {{-- <td class="text-right td-font-size">{{ $due_amount < 0 ? '('. number_format(abs($due_amount), 2) . ')' : number_format($due_amount, 2)  }}</td> --}}
                                                    <td class="text-right td-font-size">{{ number_format($due_amount, 2)  }}</td>
                                                </tr>
                                            @empty
                                                <x-no-table-record />
                                            @endforelse
                                        @endforeach


                                    </tbody>




                                    <tfoot>
                                        <tr>
                                            <th colspan="2">
                                                <div class="col-sm-6 col-lg-6 col-md-6">
                                                    <div class="invoice-price" style="width: 300px; margin-top: 5px;">
                                                        @foreach ($account_types as $id => $account_type)
                                                            <div class="row" style="display: flex; justify-content: space-between;">
                                                                <div class="left-side" style="width: 60%; text-align: left;">
                                                                    <p><b>{{ $account_type }} Sale Amount</b></p>
                                                                </div>
                                                                <div class="right-side" style="width: 40%; text-align: left;">
                                                                    <p><b>: {{ getTotalPaymentAmount($audits[0]->id, $id, 'Booking') }}</b></p>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                            </th>
                                            <th colspan="4" class="text-right" style="padding: 3px 7px 0px 0px !important; border: none !important">{{ number_format($total_collection, 2) }}</th>
                                            <th colspan="" class="text-right" style="padding: 3px 7px 0px 0px !important; border: none !important">{{ number_format($total_discount, 2) }}</th>
                                            <th colspan="" class="text-right" style="padding: 3px 7px 0px 0px !important; border: none !important">{{ number_format($total_due_amount, 2) }}</th>
                                        </tr>
                                        <tr style="border-bottom:none !important">
                                            <th colspan="7" class="text-right" style="border: none !important">Total Collection</th>
                                            <th class="text-right" style="padding: 3px 7px 0px 0px !important; border: none !important">
                                                {{ number_format($total_collection, 2) }}
                                            </th>
                                        </tr>
                                        <tr style="border-bottom:none !important">
                                            <th colspan="7" class="text-right" style="border: none !important">Total Discount</th>
                                            <th class="text-right" style="padding: 3px 7px 0px 0px !important; border: none !important">
                                                {{ number_format($total_discount, 2) }}
                                            </th>
                                        </tr>
                                        <tr style="border-bottom:none !important">
                                            <th colspan="7" class="text-right" style="padding: 0 7px 0 0 !important; border: none !important">Total Due</th>
                                            <th class="text-right" style="padding: 0 7px 0 0 !important; border: none !important">
                                                {{ number_format($total_due_amount, 2) }}
                                            </th>
                                        </tr>
                                    </tfoot>

                                </table>

                                </div>
                                <div class="pull-right hidden-print">
                                    @if ($audit)
                                    <button type="button" onclick="delete_item(`{{ route('night-audits.destroy', $audits->first()->date) }}`)" class="btn-xs btn-outline-danger" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
