@if ($room->is_booked > 0 || $room->is_reservation > 0 || $room->is_checkin > 0)
    @php
        $bgcolor = '#d15b47';
        if ($room->is_reservation > 0) {
            $bgcolor = '#9ABC32';
        }
        if ($room->is_booked > 0) {
            $bgcolor = '#D278DE';
        }
        if ($room->is_booked > 0 && $room->status == 0) {
            $bgcolor = 'linear-gradient(to bottom, #D278DE 50%, #000000 50%)';
        }
        if ($room->is_reservation > 0 && $room->status == 0) {
            $bgcolor = 'linear-gradient(to bottom, #9ABC32 50%, #000000 50%)';
        }
        if ($room->is_booked > 0 && $room->status == 2) {
            $bgcolor = 'linear-gradient(to bottom, #D278DE 50%, #000000 50%)';
        }
        if ($room->is_reservation > 0 && $room->status == 2) {
            $bgcolor = 'linear-gradient(to bottom, #9ABC32 50%, #000000 50%)';
        }
        $date = [
            date('Y-m-d'),
            Carbon\Carbon::now()
                ->addDay()
                ->format('Y-m-d'),
        ];
        if (request()->filled('booking_date')) {
            $date = array_map('trim', explode('-', request('booking_date')));
        }
        
        $guestInfo = optional(
            optional(
                optional(
                    $room->booking_dates
                        ->whereNotIn('status', [3, 4])
                        ->where('date', fdate($date[0], 'Y-m-d'))
                        ->first(),
                ),
            )->booking,
        )->guestInfo;
        $booking = optional(
            optional(
                $room->booking_dates
                    ->whereNotIn('status', [3, 4])
                    ->where('date', fdate($date[0], 'Y-m-d'))
                    ->first(),
            ),
        )->booking;
        // dd($room->booking_dates);
    @endphp

    @if ($guestInfo && $booking)
        @php
            $check_btn = '';
            $route = '';
            if ($booking->status == 0 || $booking->status == 2) {
                $check_btn = 'Check In Now';
                $route = route('check.in.update', optional($room->booking_dates->whereIn('status', [0, 2])->first())->booking_id);
            } elseif (optional($room->booking_dates->where('status', 1)->first())->booking_id) {
                $check_btn = 'Checkout Now';
                $route = route('booking.checkout', optional($room->booking_dates->where('status', 1)->first())->booking_id) . '?room_id=' . $room->id;
            }
        @endphp

        <div class="booked-room-info" style="padding-top: 18px; background: {{ $bgcolor }}; color: white;">
            <span class="popover-success" data-rel="popover" data-placement="top" data-trigger="click"
                data-original-title="<i class='fa fa-info-circle green'></i> Information"
                data-content="<p class='tool-pen'>Room Is Booked</p> 
                ">

                {{ $room->room_number }}


            </span>

        </div>
    @else
        <div class="booked-room-info" style="padding-top: 18px; background: {{ $bgcolor }}; color: white;">
            <span class="popover-success">
                {{ $room->room_number }}
            </span>
        </div>
    @endif
@else
    <div class="room-status-ui">
        <span class="room-heading-right"
            onclick="updateKeepingStatus(`{{ $room->id }}`,`{{ $status_val }}`, this)">
            <i class="fa fa-arrows-alt"></i>
        </span>
        <div class="room-info {{ $status }}">
            <input type="hidden" id="category_id" value="{{ $category->id }}">
            <input type="hidden" id="room_id" value="{{ $room->id }}">
            <p>
                {{ $room->room_number }}
            </p>
        </div>
    </div>
@endif
