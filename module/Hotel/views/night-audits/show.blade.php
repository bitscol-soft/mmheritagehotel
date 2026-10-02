@extends('layouts.master')

@section('title', 'Night Audit')

@section('page-header')
    <i class="fa fa-info-circle"></i> Night Audit Detail
@stop


@push('style')
    <style>
        @media print {
            @page { size: A4 portrait !important; }
        }
    </style>
@endpush

@section('content')
<x-mm.styles />
<x-mm.page class="mm-audit-show" title="Night audit detail" description="Audit totals and the transactions closed for the day.">
    <x-slot name="actions">
        <a href="{{ route('night-audits.index') }}" class="mm-button mm-button-secondary">
            <i class="fa fa-list-alt" aria-hidden="true"></i> List
        </a>
        <a href="javascript:void(0)" onclick="print()" class="mm-button">
            <i class="fa fa-print" aria-hidden="true"></i> Print
        </a>
    </x-slot>

    @include('partials._alert_message')

    <x-mm.panel>
        <h2 class="mm-audit-date">
            <strong>Generate Date</strong> :
            <span>{{ request('date', $audit->date) }}</span>
        </h2>

        <dl class="mm-audit-summary">
            <div><dt>Total Check In</dt><dd>{{ $audit->total_check_in ?? 0 }}</dd></div>
            <div><dt>Total Reservation</dt><dd>{{ $audit->total_reservation ?? 0 }}</dd></div>
            <div><dt>Total Check Out</dt><dd>{{ $audit->total_check_out ?? 0 }}</dd></div>
            <div><dt>Total Cancelled</dt><dd>{{ $audit->total_cancel ?? 0 }}</dd></div>
            <div><dt>Total Room</dt><dd>{{ $audit->total_room ?? 0 }}</dd></div>
            <div><dt>Total Dirty</dt><dd>{{ $audit->total_dirty_room ?? 0 }}</dd></div>
        </dl>

        <x-mm.table-scroll label="Night audit transactions">
            <table class="table table-bordered table-striped table-hover">

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
        </x-mm.table-scroll>
    </x-mm.panel>
</x-mm.page>
@endsection

@section('js')

@stop
