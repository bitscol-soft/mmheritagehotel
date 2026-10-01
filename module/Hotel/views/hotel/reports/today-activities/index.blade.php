@extends('layouts.master')

@section('title', 'Today Report')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Today Report
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report" title="Today report" description="Check-ins, check-outs and reservations for one day.">
    <x-alert-message />
    <x-mm.panel class="mm-report-filter hidden-print">
        <form action="" method="GET" class="mm-setup-filter mm-report-form">
            <div class="mm-report-field">
                <div class="input-group">
                    <label class="input-group-addon"><i class="fa fa-calendar"></i></label>
                    <input type="text" class="date-picker form-control text-center"
                        autocomplete="off" name="date" value="{{ request('date', date('Y-m-d')) }}"
                        placeholder="Date">
                </div>
            </div>
            <div class="mm-report-field">
                <div class="btn-group">
                    <button type="submit" class="mm-button">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                        <i class="fa fa-refresh"></i>
                    </a>
                </div>
            </div>

        </form>
    </x-mm.panel>
    @if (request('date'))
        <x-mm.panel>
            <x-mm.table-scroll label="Today report">
                @if ($booking_count == 0)
                    <div class="text-center">
                        <strong style="font-size: 18px" class="text-danger">
                            No data found.
                        </strong>
                    </div>
                @else

                    <input type="hidden" name="date" value="{{ $date }}">
                    <hr>

                    <table class="table" style="border: none">

                        <tr style="border-bottom:none !important">
                            <th class="text-right border-none"
                                    style="padding: 0 7px 0 0 !important; width: 20%">Total Check In :
                            </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_check_in"
                                    value="{{ $total_check_in }}">
                            </th>
                            <th style="border: none"></th>
                            <th class="text-right border-none"
                                    style="padding: 0 7px 0 0 !important; width: 20%">Total Reservation
                                : </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_reservation"
                                    value="{{ $total_reservation }}">
                            </th>
                        </tr>
                        <tr style="border-bottom:none !important">
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Total
                                Check Out : </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_check_out"
                                    value="{{ $total_check_out }}">
                            </th>

                            <th style="border: none"></th>
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Total
                                Cancelled : </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_cancelled"
                                    value="{{ $total_cancel }}">
                            </th>
                        </tr>
                        <tr style="border-bottom:none !important">
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Total Room :
                            </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_room"
                                    value="{{ $total_room }}">
                            </th>

                            <th style="border: none"></th>
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Booked Room :
                            </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_booked_room"
                                    value="{{ $total_booked_room }}">
                            </th>
                        </tr>
                        <tr style="border-bottom:none !important">
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Today Dirty Room :
                            </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_dirty_room"
                                    value="{{ $total_dirty_room }}">
                            </th>

                            <th style="border: none"></th>
                            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                                Today Maintainance Room :
                            </th>
                            <th class="border-none" style="padding: 0 7px 0 0 !important;">
                                <input type="text" readonly class="header-input"
                                    style="background: white !important" name="total_room_maintenance"
                                    value="{{ $total_maintenance_room }}">
                            </th>
                        </tr>
                    </table>

                    <table class="table table-bordered table-striped table-hover"
                            style="border: none">

                        <thead>
                            <tr>
                                <th class="text-center">SL</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Invoice No</th>
                                <th class="text-center">Payment Type</th>
                                <th class="text-center">Room No</th>
                                <th class="text-right">Total Amount<span class="currency-sign"></span></th>
                                <th class="text-right">Paid Amount<span class="currency-sign"></span></th>
                                <th class="text-right" style="width: 14% !important">Due Amount<span class="currency-sign"></span></th>
                            </tr>
                        </thead>

                        <tbody>
                            @php

                                $total_collection = $total_due_amount = 0;
                                $total_amount = $total_paid_amount = $total_due_amount = 0;

                            @endphp

                            @foreach ($transactions as $key => $transaction)
                                @php
                                    $total_amount = optional($transaction->transaction)->total_amount;
                                    $total_collection += $paid_amount = optional($transaction->transaction)->collection;

                                    $total_due_amount += $due_amount = $total_amount - $paid_amount;

                                @endphp

                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration }}

                                        <input type="hidden" name="transaction_ids[]"
                                            value="{{ $transaction->id }}">
                                    </td>
                                    <td class="text-center">{{ $transaction->source_type }}</td>
                                    <td class="text-center">
                                        INV-{{ optional($transaction->transaction)->invoice_no }}
                                    </td>
                                    <td class="text-center">
                                        {{ optional($transaction->account)->name }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $details = optional(optional($transaction->transaction)->source)->details;
                                        @endphp
                                        @foreach ($details ?? [] as $key => $room)
                                            <label
                                                    class="label label-success">{{ optional($room->roomNumber)->room_number }}</label>
                                        @endforeach
                                    </td>
                                    <td style="text-align: right;">
                                        <span
                                                class="item-total">{{ calculateCurrencyAmount($total_amount) }}</span>
                                    </td>
                                    <td style="text-align: right;">
                                        {{ calculateCurrencyAmount($paid_amount) }}
                                    </td>
                                    <td class="text-right">{{ calculateCurrencyAmount($due_amount) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr style="border-bottom:none !important">
                                <th colspan="7" class="text-right border-none">Total Collection <span class="currency-sign"></span>
                                </th>
                                <th class="text-right border-none"
                                        style="padding: 3px 7px 0px 0px !important;">
                                    <input type="text" readonly class="footer-input"
                                        style="background: white !important; padding: 0 !important"
                                        name="collection" value="{{ calculateCurrencyAmount($total_collection, 1) }}">
                                </th>
                            </tr>

                            <tr style="border-bottom:none !important">
                                <th colspan="7" class="text-right border-none"
                                        style="padding: 0 7px 0 0 !important;">Total Due <span class="currency-sign"></span></th>
                                    <th class="text-right border-none"
                                            style="padding: 0 7px 0 0 !important;">
                                        <input type="text" readonly class="footer-input"
                                            style="background: white !important" name="due_amount"
                                            value="{{ calculateCurrencyAmount($total_due_amount, 1) }}">
                                    </th>
                            </tr>

                        </tfoot>
                    </table>

                @endif

            </x-mm.table-scroll>
        </x-mm.panel>
    @endif
</x-mm.page>
@endsection


