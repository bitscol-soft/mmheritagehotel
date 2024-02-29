@extends('layouts.master')
@section('title', 'Booking Migration')
@section('page-header')
    <i class="fa fa-plus-circle"></i> Booking Migration
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.min.css') }}" />


    @include('booking.adjust._inc.css')
@endpush


@section('content')

    @php

        $today = date('m/d/Y');
        $tomorrow = date('m/d/Y', strtotime($today . '+1 days'));
        $compact_date = $today . ' - ' . $tomorrow;
        $availablity_check = request('booking_availabe');

    @endphp

    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('booking.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> Booking List
                        </a>
                    </span>

                </div>

                <div class="widget-body">

                    <!-- Adjust Booking Form -->
                    <form action="{{ route('booking-adjusts.store') }}" class="store-form" method="POST" id="store-form">
                        @csrf

                        <!-- BOOKING ID -->
                        <input type="hidden" name="booking_id" value="{{ request('booking_id') }}" required>
                        <input type="hidden" name="type" value="{{ request('type') }}" required>
                        <input type="hidden" name="from_booking_migration" value="1">

                        <div class="widget-main">

                            <x-alert-message />




                            <div class="row">

                                <!-- GUEST INFORMATION -->
                                <div style="padding: 20px">
                                    <div class="row" style="display: flex; justify-content: center;">
                                        <div class="col-sm-6" style="width: 40%;float: left; ">

                                            <h5><b><u>Guest's Information :</u></b></h5>

                                            <p class="guest"><b style="width: 80px; display: inline-block;">Name </b> :
                                                {{ optional($booking->guestInfo)->name }}</p>

                                            <p class="guest"><b style="width: 80px; display: inline-block;">Address </b> :
                                                {{ optional($booking->guestInfo)->address }}
                                            </p>
                                            <p class="guest"><b style="width: 80px; display: inline-block;">Mobile </b> :
                                                {{ optional($booking->guestInfo)->phone_no }}
                                            </p>
                                            <p><b style="width: 80px; display: inline-block;">Nationality </b>
                                                : {{ optional(optional($booking->guestInfo)->country)->name }}
                                            </p>

                                        </div>

                                        <div class="col-sm-6" style="width: 40%;float: left;margin-top: 5px;">
                                            <table class="table table-bordered" style="border: none !important;">
                                                <tr>
                                                    <th width="30%" style="border: none !important;"> Booking No </th>
                                                    <th style="border: none !important;">
                                                        : BK-{{ $booking->booking_number }}</th>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Booking Date </td>
                                                    <td style="border: none !important;">: {{ $booking->booking_date }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Check IN Date </td>
                                                    <td style="border: none !important;">: {{ $booking->check_in_date }}
                                                    </td>
                                                    <input type="hidden" value="{{ $booking->check_in_date }}"
                                                        class="check-in-date">
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;"> Check out Date </td>
                                                    <td style="border: none !important;">: <span
                                                            class="tr-checkout-date">{{ $booking->check_out_date }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>




                                <div class="row">
                                    <div class="col-sm-12 mb-3">
                                        <div style="border-bottom:2px solid black"></div>
                                    </div>
                                </div>


                                <!-- BOOKING ROOM INFORMATION -->
                                <input type="hidden" value="{{ $booking->hotel_transaction->first()->due_amount }}"
                                    id="previousDue">
                                <input type="hidden" value="{{ $booking->hotel_transaction->first()->collection }}"
                                    id="previousAdvance">
                                <input type="hidden" value="{{ date('Y-m-d') }}" id="currentDate">
                                <input type="hidden" name="previous_check_out_date" value="{{ $booking->check_out_date }}"
                                    id="previous_check_out_date">
                                <input type="hidden"
                                    value="{{ optional(optional($booking->bookingDetails[0])->roomNumber)->id }}"
                                    id="selectedRoom">
                                <input type="hidden"
                                    value="{{ optional(optional($booking->bookingDetails[0])->roomNumber)->room_number }}"
                                    id="selectedRoomNumber">

                                <div class="row mb-3">
                                    <div class="col-sm-8" style="margin-left: 24%">
                                        @foreach ($booking->bookingDetails as $key => $item)
                                            <div class="col-sm-4">
                                                <div class="text-danger">
                                                    {{ $booking->bookingAdjusts->where('from_room_id', $item->room_id)->count() > 0 ? 'Re-migrate is not posible' : '' }}
                                                </div>
                                                <label class="block">
                                                    <input name="room_ids" type="checkbox"
                                                        value="{{ optional($item->roomNumber)->room_number }}"
                                                        class="ace input-lg room-select"
                                                        data-guest_count="{{ $item->guest_count }}"
                                                        data-room-id="{{ optional($item->roomNumber)->id }}"
                                                        data-room-category="{{ optional(optional($item->roomNumber)->roomCategory)->name }}"
                                                        data-price="{{ optional(optional($item->roomNumber)->roomCategory)->price }}"
                                                        data-infant_count="{{ $item->infant_count }}"
                                                        data-night_count="{{ $item->night_count }}"
                                                        data-check_out_date="{{ $item->check_out_date }}"
                                                        data-allow_breakfast="{{ $item->allow_breakfast }}"
                                                        {{ request('room_id') == optional($item->roomNumber)->id ? 'checked' : '' }}
                                                        {{ $booking->bookingAdjusts->where('from_room_id', $item->room_id)->count() > 0 ? 'disabled' : '' }}
                                                        {{ $key == 0 ? 'checked' : 'disabled' }}>
                                                    <span class="lbl bigger-120"> Room ->
                                                        {{ optional($item->roomNumber)->room_number }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>



                                <!-- BOOKING DATE FILTERING -->
                                <div class="row mb-3">
                                    <div class="col-sm-6 col-sm-offset-2">
                                        <div class="text-center">
                                            <div class="input-group">
                                                <label class="input-group-addon">
                                                    Migrate Date
                                                </label>
                                                <input type="text" name="migrate_date" autocomplete="off"
                                                    class="form-control date-picker-disable-past pointer migrate_date empty-rooms"
                                                    value="{{ date('Y-m-d') }}">
                                                <label class="input-group-addon">
                                                    Check Out Date
                                                </label>
                                                <input type="text" name="check_out_date" autocomplete="off"
                                                    class="form-control date-picker-disable-past pointer empty-rooms"
                                                    value="{{ $booking->check_out_date }}">
                                                <div class="input-group-btn">
                                                    <button class="btn-outline-info btn-sm next-step-btn no-border"
                                                        type="button" id="checkRoomStatus">
                                                        <i class="fal fa-paper-plane"></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="row" style="padding: 5px; margin: 10px 0">
                                    <div class="available-rooms" style="display: none">
                                        @include('booking.adjust._inc.show-room')
                                    </div>
                                </div>


                                <div class="row" style="padding: 5px; margin: 10px 0">
                                    <div id="available-room">

                                    </div>
                                </div>



                            </div>




                            <!-- Room Information Table -->
                            <div class="row">
                                <div class="col-sm-12">

                                    <h3 class="header smaller lighter blue">Room Information</h3>

                                    <table class="table table-bordered room-list">
                                        <thead>
                                            <tr>

                                                <th class="text-center" style="width: 10%">
                                                    Category
                                                </th>
                                                <th class="text-center" style="width: 12%">
                                                    Room
                                                </th>
                                                <th class="text-center" style="width: 10%">Guest</th>
                                                <th style="width:12%">Amount <span class="currency-sign"></span></th>
                                                <th class="text-right">Infant</th>
                                                @if (request('type') != 'migrate')
                                                    <th class="text-right">Half Day</th>
                                                @endif
                                                <th class="text-right">Night</th>
                                                <th class="text-right">Discount<span class="currency-sign"></span></th>
                                                <th class="text-center">Breakfast</th>
                                                <th class="text-right" width="15%">T.Amount
                                                    <span class="currency-sign"></span>
                                                </th>
                                                <th class="text-right" style="width: 3%"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="item-details roomTbody">

                                        </tbody>
                                        @include('booking._inc.create-edit-tfoot', [
                                            'colspan' => request('type') == 'migrate' ? 8 : 9,
                                        ])

                                    </table>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-xs-12 col-sm-12 text-right mt-2">
                                    <button class="btn-sm btn-outline-success submit-form-btn" type="button">
                                        <i class="fad fa-box-check"></i> Book Now
                                    </button>
                                </div>

                            </div>


                        </div>

                    </form>

                </div>


            </div>
        </div>
    </div>


@endsection

@section('script')

    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    @include('booking.adjust._inc._script')


@endsection
