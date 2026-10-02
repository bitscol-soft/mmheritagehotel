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

    <x-mm.styles />
    <x-mm.page class="mm-booking-adjust" title="Booking migration" description="Move this booking to another room or date.">
        <x-slot name="actions">
            <a href="{{ route('booking.index') }}" class="mm-button mm-button-secondary">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Booking List
            </a>
        </x-slot>

        <!-- Adjust Booking Form -->
        <form action="{{ route('booking-adjusts.store') }}" class="store-form" method="POST" id="store-form">
            @csrf

            <!-- BOOKING ID -->
            <input type="hidden" name="booking_id" value="{{ request('booking_id') }}" required>
            <input type="hidden" name="type" value="{{ request('type') }}" required>
            <input type="hidden" name="from_booking_migration" value="1">

            @include('booking._inc._booking-context', ['booking' => $booking])

            <x-alert-message />

            <x-mm.panel class="mm-adjust-guest">
                <!-- GUEST INFORMATION -->
                <div class="mm-adjust-info">
                    <div>
                        <h2 class="mm-adjust-title">Guest's information</h2>

                        <p class="guest"><b>Name </b> :
                            {{ optional($booking->guestInfo)->name }}</p>

                        <p class="guest"><b>Address </b> :
                            {{ optional($booking->guestInfo)->address }}
                        </p>
                        <p class="guest"><b>Mobile </b> :
                            {{ optional($booking->guestInfo)->phone_no }}
                        </p>
                        <p><b>Nationality </b>
                            : {{ optional(optional($booking->guestInfo)->country)->name }}
                        </p>
                    </div>

                    <div>
                        <h2 class="mm-adjust-title">Booking</h2>
                        <table class="table mm-adjust-booking">
                            <tr>
                                <th> Booking No </th>
                                <th>
                                    : BK-{{ $booking->booking_number }}</th>
                            </tr>
                            <tr>
                                <td> Booking Date </td>
                                <td>: {{ $booking->booking_date }}
                                </td>
                            </tr>
                            <tr>
                                <td> Check IN Date </td>
                                <td>: {{ $booking->check_in_date }}
                                    <input type="hidden" value="{{ $booking->check_in_date }}"
                                        class="check-in-date">
                                </td>
                            </tr>
                            <tr>
                                <td> Check out Date </td>
                                <td>: <span
                                        class="tr-checkout-date">{{ $booking->check_out_date }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </x-mm.panel>

            <x-mm.panel class="mm-adjust-rooms">
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

                <h2 class="mm-adjust-title">Room to migrate</h2>
                <div class="mm-adjust-room-picks">
                    @foreach ($booking->bookingDetails as $key => $item)
                        <div>
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

                <!-- BOOKING DATE FILTERING -->
                <div class="mm-adjust-dates">
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
                            <button class="mm-button next-step-btn"
                                type="button" id="checkRoomStatus">
                                <i class="fa fa-paper-plane"></i> Search
                            </button>
                        </div>
                    </div>
                </div>

                <div class="available-rooms" style="display: none">
                    @include('booking.adjust._inc.show-room')
                </div>

                <div id="available-room">

                </div>
            </x-mm.panel>

            <!-- Room Information Table -->
            <x-mm.panel class="mm-adjust-table">
                <h2 class="mm-adjust-title">Room Information</h2>

                <x-mm.table-scroll label="Room information">
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
                </x-mm.table-scroll>

                <div class="mm-adjust-actions">
                    <button class="mm-button submit-form-btn" type="button">
                        <i class="fa fa-bookmark"></i> Book Now
                    </button>
                </div>
            </x-mm.panel>
        </form>
    </x-mm.page>

@endsection

@section('script')

    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    @include('booking.adjust._inc._script')


@endsection
