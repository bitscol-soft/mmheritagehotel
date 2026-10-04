@extends('layouts.master')

@section('title', 'Hotel Monthly Booking Report')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.min.css') }}" />
@endpush

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report mm-report-monthly" title="Hotel monthly report" description="Room status by day for the selected month.">
    <x-mm.panel class="mm-report-filter">
        <form class="mm-report-form">
            <div class="mm-report-field">
                <x-widget.text-input-group name="month" title="Month" class="month-picker" :value="request('month', date('Y-m'))" isrequired="1" />
            </div>
            <div class="mm-report-field">
                <x-widget.select-input-group name="room_category" title="Room Category" :collections="$room_categories" :selected="request('room_category')" />
            </div>
            <div class="mm-report-field">
                <x-widget.select-input-group name="id" title="Rooms" :collections="$room_datas" :selected="request('id')" />
            </div>
            <div class="mm-report-field">
                <x-widget.select-input-group name="guest_id" title="Guest" :collections="$guests" secondvalue="phone_no" :selected="request('guest_id')" />
            </div>
            <div class="mm-report-field">
                <div class="btn-group">
                    <button type="submit" class="mm-button">
                        <i class="fa fa-search"></i>
                        Search
                    </button>
                    <a href="{{ request()->url() }}" class="mm-button mm-button-secondary">
                        <i class="fa fa-refresh"></i>
                        Refresh
                    </a>
                </div>
            </div>
        </form>
    </x-mm.panel>

    @if (request('month'))
        <x-mm.panel>
            <p class="mm-report-legend">
                <span class="label label-xs label-warning arrowed arrowed-right">Reservation</span>
                <span class="label label-xs label-danger arrowed arrowed-right">Check In</span>
                <span class="label label-xs label-success arrowed arrowed-right">Ready Room</span>
                <span class="label label-xs label-inverse arrowed arrowed-right">Dirty</span>
                <span class="label label-xs label-purple arrowed arrowed-right">Maintenance</span>
                <span class="label label-xs today-checkout arrowed arrowed-right">Today Checkout</span>
            </p>
            <x-mm.table-scroll label="Monthly room calendar">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" class="bg-dark">Category</th>
                            <th rowspan="2" class="bg-dark">Room</th>
                            @for ($i = 1; $i <= totalDaysInMonth(request('month')); $i++)
                                <th class="bg-dark"
                                    style="@if ($i == date('d')) background-color:#ffcece !important @endif">
                                    <p style="padding-left: 10px;padding-right:10px">{{ $i }}</p>
                                </th>
                            @endfor
                            <th class="bg-dark" colspan="3" style="text-align:center">Total</th>
                        </tr>
                        <tr>
                            @for ($i = 1; $i <= totalDaysInMonth(request('month')); $i++)
                                @php
                                    $date = fdate(request('month') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT), 'Y-m-d');
                                @endphp
                                <th class="text-center"
                                    style="font-size:8px !important; @if ($i == date('d')) background-color:#ffcece !important @endif">
                                    {{ fdate($date, 'D') }}
                                </th>
                            @endfor
                            <th>Reservation</th>
                            <th>Check In</th>
                            <th>Available</th>
                        </tr>
                    </thead>
                    @php
                        $grand_total_reservation = $grand_total_check_in = $grand_total_checkout = 0
                    @endphp
                    <tbody id="schedule_table">
                        @foreach ($rooms as $key => $room)
                        @php
                            $total_reservation = $total_check_in = $total_checkout = 0
                        @endphp
                            <tr>
                                <th class="text-center" style="vertical-align: middle">{{ optional($room->roomCategory)->name }}</th>
                                <th class="text-center">
                                    <div class="room-info ">
                                        <p>{{ $room->room_number }}</p>
                                    </div>
                                </th>
                                @for ($i = 1; $i <= totalDaysInMonth(request('month')); $i++)
                                    @php
                                        $date       = fdate(request('month') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT));
                                        $booking    = optional(optional($room->booking_dates)->where('date', $date)->first())->booking;

                                        // dd(optional($room->booking_dates)->where('date', $date)->first(),$booking);

                                        $bg_class = '';
                                        $status = '';
                                        if (optional($booking)->status == 0) {
                                            $total_reservation ++;
                                            $grand_total_reservation ++;
                                            $bg_class = 'bg-1';
                                            $status = 'Reserved';
                                        } elseif(optional($booking)->status == 1)  {
                                            $total_check_in ++;
                                            $grand_total_check_in ++;
                                            $bg_class = 'bg-0';
                                            $status = 'Booked';
                                        } elseif(optional($booking)->status == 3)  {
                                            $total_checkout ++;
                                            $grand_total_checkout ++;
                                            $bg_class = 'bg-2';
                                            $status = 'CheckOut';
                                        }

                                    @endphp

                                    <td class="date-{{ $i }} {{ $bg_class }}" style="padding-top: 18px; height:50px" data-title="{{ $status }}">
                                        @if (optional($booking)->status != 0)
                                            @include('hotel/reports/monthly/inc/date-wise-room-status', ['booking'=> optional($booking)])
                                        @endif
                                    </td>
                                @endfor
                                <th class="text-right"><strong style="font-size:18px">{{ $total_reservation }}</strong></th>
                                <th class="text-right"><strong style="font-size:18px">{{ $total_check_in }}</strong></th>
                                <th class="text-right"><strong style="font-size:18px">{{ $total_checkout }}</strong></th>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right" colspan="{{ totalDaysInMonth(request('month'))+2 }}"><strong>Total:</strong></th>
                            <th class="text-right"><strong style="font-size:18px">{{ $grand_total_reservation }}</strong></th>
                            <th class="text-right"><strong style="font-size:18px">{{ $grand_total_check_in }}</strong></th>
                            <th class="text-right"><strong style="font-size:18px">{{ $grand_total_checkout }}</strong></th>
                        </tr>
                    </tfoot>
                </table>
            </x-mm.table-scroll>
        </x-mm.panel>
    @endif
</x-mm.page>
@endsection

@section('js')

    <script src="{{ asset('assets/custom_js/month-picker.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/fullcalendar.min.js') }}"></script>
    <script>
        $(".guest-info").hover(function() {
            $(this).find('.guest-popup').show();
        }, function() {
            $(this).find('.guest-popup').hide();
        });
    </script>

@endsection
