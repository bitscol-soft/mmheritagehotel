@extends('layouts.master')
@section('title', 'Add Booking')
@section('page-header')
    <i class="fa fa-plus-circle"></i> Add New Booking
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.min.css') }}" />

@endpush


@section('content')

    @php
        $today        = date('m/d/Y');
        $tomorrow     = date('m/d/Y', strtotime('tomorrow'));
        $availablity_check = request('booking_date', $booking_date ?? $today . ' - ' . $tomorrow);
        // board date-range display: values are MM/DD/YYYY (daterangepicker format)
        $range_bits = array_values(array_filter(array_map('trim', explode('-', (string) $availablity_check))));
        $in_ts = strtotime($range_bits[0] ?? 'today');
        $out_ts = strtotime($range_bits[1] ?? 'tomorrow');
        if ($out_ts <= $in_ts) {
            $out_ts = strtotime('+1 day', $in_ts);
        }
        $nights = max((int) round(($out_ts - $in_ts) / 86400), 1);
    @endphp

    <x-mm.styles />
    <x-mm.page class="mm-booking-board-page" title="Room availability" description="Choose your stay dates, review room availability and select rooms for a booking.">
        <x-slot name="actions">
            <a class="mm-button mm-button-secondary" href="{{ route('booking.index') }}">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Booking List
            </a>
        </x-slot>
        <x-mm.panel>
                        <x-alert-message />

                        @php
                            // round-9: board totals chips (same occupancy rule as the room grid)
                            $boardRoomsTotal = 0;
                            $boardRoomsFree  = 0;
                            foreach ($categories as $cat) {
                                $boardRoomsTotal += $cat->rooms->count();
                                $boardRoomsFree  += $cat->rooms->filter(function ($r) {
                                    return !($r->is_booked >= 1 || $r->is_reservation >= 1 || $r->is_checkin > 0);
                                })->count();
                            }
                        @endphp

                        <div class="board-stay-strip" role="group" aria-label="Stay and room availability summary">
                            <span class="stay-chip"><i class="fa fa-sign-in"></i> Check-in
                                <b>{{ date('D, d M Y', $in_ts) }}</b></span>
                            <span class="stay-chip"><i class="fa fa-sign-out"></i> Check-out
                                <b>{{ date('D, d M Y', $out_ts) }}</b></span>
                            <span class="stay-chip"><i class="fa fa-moon-o"></i> <b>{{ $nights }}</b>
                                night{{ $nights > 1 ? 's' : '' }}</span>
                            <span class="stay-chip stay-chip-right"><i class="fa fa-home"></i> <b>{{ $boardRoomsTotal }}</b>
                                rooms</span>
                            <span class="stay-chip stay-chip-right"><i class="fa fa-check-circle"></i> <b>{{ $boardRoomsFree }}</b>
                                free now</span>
                        </div>

                        <x-room-manage :categories="$categories" :mixdate="$availablity_check" />
        </x-mm.panel>
    </x-mm.page>

@endsection

@section('script')

    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    <script src="{{ asset('assets/custom_js/stay-range.js') }}"></script>
    @include('home._inc.script')


@endsection
