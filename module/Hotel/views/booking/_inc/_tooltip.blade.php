@if ($room->is_booked > 0 || $room->is_reservation > 0)
    @php
        $bgcolor = '#d15b47';
        if ($room->is_reservation) {
            $bgcolor = '#F89406';
        }
        $guestInfo  = optional(optional($room->booking_dates->first())->booking)->guestInfo;
        $booking    = optional($room->booking_dates->first())->booking;
    @endphp

    @if ($guestInfo)
        @php
            $check_btn  = 'Checkout Now';
            $route          = route('booking.checkout',optional($room->booking_dates->first())->booking_id );
        @endphp
    @if ($booking->status == 0)
        @php
            $check_btn   = 'Check In Now';
            $route          = route('check.in.update',optional($room->booking_dates->first())->booking_id );
        @endphp
    @endif
        <div class="booked-room-info" style="padding-top: 18px; background: {{ $bgcolor }}; color: white;">
            <span class="popover-success" data-rel="popover" data-placement="top" data-trigger="click"
                data-original-title="<i class='ace-icon fa fa-info-circle green'></i> Guest Information"
                data-content="<p class='tool-pen'>Name: {{ $guestInfo->name }}.</p> <p class='tool-pen'> Phone No : {{ $guestInfo->phone_no }}</p>
                <p class='tool-pen'> Passport : {{ $guestInfo->nid_no }}</p>
                <p class='tool-pen'>Check In: {{ $booking->check_in_time }}</p> <p class='tool-pen'> Check Out : {{ $booking->check_out_time }}</p><div class='btn-group'>
                    <button class='btn btn-xs btn-danger' type='button' onclick='checkOut(`{{ $route }}`, `{{ $check_btn }}`)'>
                        <i class='fa fa-clock'></i> {{ $check_btn }}
                    </button>
                </div>">

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
        <span class="room-heading-right" onclick="updateStatus(`{{ $room->id }}`,`{{ $status_val }}`, this)">
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
