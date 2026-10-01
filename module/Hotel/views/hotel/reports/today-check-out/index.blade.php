@extends('layouts.master')

@section('title', 'Today Check Out')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Today Check Out
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report" title="Today check out" description="Guests checking out on the selected date.">
    <x-alert-message />
    <x-mm.panel>
        <x-mm.table-scroll label="Today check out">


            <input type="hidden" name="date" value="{{ $date }}">
            <div class="text-center">
                <span class="badge badge-info" style="font-size: 17px;">Today Check-Out Report</span>
            </div>
            <hr>

            <table class="table table-bordered table-striped table-hover" style="border: none">

                <thead>
                    <tr>
                        <th class="text-center">SL</th>
                        <th class="text-center">Booking No</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Room No</th>
                        <th class="text-right">Booking Date</span></th>
                        <th class="text-right">Check-in Date</span></th>
                        <th class="text-right" style="width: 14% !important">Check-Out Date</span>
                            <th class="text-center" style="width: 14% !important">Check-Out Time</span>
                            </th>
                    </tr>
                </thead>

                <tbody>
                    @php

                        $total_collection = $total_due_amount = 0;
                        $total_amount = $total_paid_amount = $total_due_amount = 0;

                    @endphp

                    @foreach ($today_booking as $key => $booked)

                        <tr>
                            <td class="text-center">
                                {{ $loop->iteration }}

                            </td>
                            <td class="text-center">
                                {{ $booked->booking_number }}
                            </td>
                            <td class="text-center">
                                {{ optional($booked->customer)->name }}
                            </td>
                            <td class="text-center">
                                @php
                                    $details = optional(optional($booked->transection)->source)->details;
                                @endphp
                                @foreach ($details ?? [] as $key => $room)
                                    <label
                                            class="label label-success">{{ optional($room->roomNumber)->room_number }}</label>
                                @endforeach
                            </td>
                            <td style="text-align: right;">
                                <span class="item-total">
                                    {{ $booked->booking_date }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                {{ $booked->check_in_date }}
                            </td>
                            <td class="text-right">
                                {{ $booked->check_out_date }}
                            </td>
                            <td class="text-center">
                                @php
                                    $checkout = Carbon\Carbon::parse($booked->check_out_time);
                                    $date = $checkout->format('H:i A');
                                @endphp
                                {{ $date }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>


        </x-mm.table-scroll>
    </x-mm.panel>
</x-mm.page>
@endsection
