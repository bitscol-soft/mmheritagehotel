@extends('layouts.master')

@section('title', 'Today Check Out')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Today Check Out
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
    </style>
@endpush


@section('content')

    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                </div>



                <div class="widget-body">
                    <x-alert-message />

                    <div class="widget-main">

                        {{-- <div class="row mb-2 hidden-print">
                            <form action="" method="GET">
                                <div class="col-sm-3 col-sm-offset-3">
                                    <div class="input-group">
                                        <label class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                        <input type="text" class="date-picker form-control text-center"
                                            autocomplete="off" name="date" value="{{ request('date', date('Y-m-d')) }}"
                                            placeholder="Date">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                        <a href="{{ request()->url() }}" class="btn btn-sm">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div> --}}

                        {{-- @if (request('date')) --}}
                        {{-- @if ($booking_count == 0)
                                <div class="text-center">
                                    <strong style="font-size: 18px" class="text-danger">
                                        No data found.
                                    </strong>
                                </div>
                            @else --}}
                        <div class="row">


                            <input type="hidden" name="date" value="{{ $date }}">
                            <div class="text-center">
                                    <span class="badge badge-info" style="font-size: 17px;">Today Check-Out Report</span>
                            </div>
                            <hr>


                            <div class="col-md-12">

                                <table class="table table-bordered table-striped table-hover" style="border: none">

                                    <thead>
                                        <tr>
                                            <th class="text-center">SL</th>
                                            {{-- <th class="text-center">Type</th> --}}
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
                                            {{-- @php
                                                $total_amount = optional($transaction->transaction)->total_amount;
                                                $total_collection += $paid_amount = optional($transaction->transaction)->collection;

                                                $total_due_amount += $due_amount = $total_amount - $paid_amount;

                                            @endphp --}}


                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration }}

                                                </td>
                                                {{-- <td class="text-center">{{ optional($booked->transection)->source_type }}
                                                </td> --}}
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
                            </div>
                        </div>
                        {{-- @endif --}}

                        {{-- @endif --}}

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
