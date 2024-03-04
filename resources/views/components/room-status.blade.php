@if ($room->is_booked > 0 || $room->is_reservation > 0 || $room->is_checkin > 0)
    @php
        $bgcolor = '#d15b47'; // Red
        if ($room->is_reservation > 0) {
            $bgcolor = '#9ABC32'; //Light Green
        }
        if ($room->is_booked > 0) {
            $bgcolor = '#D278DE'; //pink
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
                    // ->orderBy('date', 'desc')->first(),
            ),
        )->booking;

        $to_day_checkout = optional(
            optional($room->booking_dates->whereNotIn('status', [3, 4])->where('date', '>=', date('Y-m-d'))->last())
            )->booking;
        // dd($to_day_checkout);
    @endphp

    @if ($guestInfo && $booking)
        @php
            $check_btn = '';
            $route = '';

            if ($to_day_checkout->check_out_date == date('Y-m-d')) {
                $bgcolor = '#1e6b99';
            }

            if ($booking->status == 0 || $booking->status == 2) {
                $check_btn = 'Check In Now';
                $route = route('check.in.update', optional($room->booking_dates->whereIn('status', [0, 2])->first())->booking_id);
            } elseif (optional($room->booking_dates->where('status', 1)->first())->booking_id) {
                $check_btn = 'Checkout Now';
                $route = route('booking.checkout', optional($room->booking_dates->where('status', 1)->first())->booking_id) . '?room_id=' . $room->id;

            }
            // elseif (optional($room->booking_dates->where('status', 1)->first())->booking_id && $booking->check_out_date == date('Y-m-d')) {
            //     $check_btn = 'Checkout Now';
            //     $route = route('booking.checkout', optional($room->booking_dates->where('status', 1)->first())->booking_id) . '?room_id=' . $room->id;
            // }
        @endphp

        <div class="booked-room-info" style="padding-top: 0; background: {{ $bgcolor }}; color: white;">
            
            <span class="popover-success" data-rel="popover" data-placement="top" data-trigger="click"
                data-original-title="<i class='fa fa-info-circle green'></i> Guest Information"
                data-content="<p class='tool-pen'>Name: {{ $guestInfo->name }}.</p> <p class='tool-pen'> Phone No : {{ $guestInfo->phone_no }}</p>
                <p class='tool-pen'> Passport : {{ $guestInfo->nid_no }}</p>
                {!! $booking->check_in_time ? "<p class='tool-pen'>Check In: $booking->check_in_time </p>" : '' !!}
                {!! $booking->check_out_time ? "<p class='tool-pen'>Check Out: $booking->check_out_time </p>" : '' !!}
                {!! $booking->check_in_note ? "<p class='tool-pen'>Note: $booking->check_in_note </p>" : '' !!}
                @if ($check_btn != '')
                <div class='btn-group'>
                    <button class='btn btn-minier btn-danger' type='button' onclick='checkOut(`{{ $route }}`, `{{ $check_btn }}`, `{{ $booking->id }}`)'>
                        <i class='fa fa-clock'></i> {{ $check_btn }}
                    </button>
                    <a class='btn btn-minier btn-inverse' href='{{ route('booking-adjusts.create', ['booking_id' => $booking->id, 'room_id' => $room->id, 'type' => 'migrate']) }}' target='_blank'>
                        <i class='fa fa-adjust'></i> Migrate
                    </a>
                </div>
                @endif">
                <p class="pt-0 mb-0">{{ $room->room_number }}</p>
                <div>
                    @for( $i = 0; $i < $room->bed_per_room; $i++)
                        <img src="{{asset('assets/images/bed.png')}}" alt="" height="33" width="25">
                    @endfor
                </div>
                <div class="room-details">
                    <p>Price : {{ $room->room_price }}</p>
                    <p>Size : {{ $room->room_size }}</p>
                    <p>Breakfast : @if($room->is_breakfast == 1) <label>Yes</label>@else <label>No</label>@endif</p>
                </div>
                <div class="guest-info-details">
                    <p>Guest-info</p>
                </div>


            </span>

        </div>
    @else
        <div class="booked-room-info" style="padding-top: 0; background: {{ $bgcolor }}; color: white;">
            <span class="popover-success">
                <p class="pt-0 mb-0">{{ $room->room_number }}</p>
                <div>
                    @for( $i = 0; $i < $room->bed_per_room; $i++)
                        <img src="{{asset('assets/images/bed.png')}}" alt="" height="33" width="25">
                    @endfor
                </div>
                <div class="room-details">
                    <p>Price : {{ $room->room_price }}</p>
                    <p>Size : {{ $room->room_size }}</p>

                    <p>Breakfast : @if($room->is_breakfast == 1) <label>Yes</label>@else <label>No</label>@endif</p>
                </div>
            </span>
        </div>
    @endif
@else
    <div class="room-status-ui">
        <span class="room-heading-right" onclick="updateStatus(`{{ $room->id }}`,`{{ $status_val }}`, this)">
            <i class="fal fa-arrows-alt"></i>
        </span>
        <div class="room-info {{ $status }} room-price">
            <input type="hidden" id="category_id" value="{{ $category->id }}">
            <input type="hidden" id="room_id" value="{{ $room->id }}">
            <p class="pt-0 mb-0">{{ $room->room_number }}</p>
            <div>
                @if($status == 'inverse')
                    @for( $i = 0; $i < $room->bed_per_room; $i++)
                        <img src="{{asset('assets/images/bed.png')}}" style="filter: brightness(0) invert(1);" alt="" height="33" width="25">
                    @endfor
                @else
                    @for( $i = 0; $i < $room->bed_per_room; $i++)
                        <img src="{{asset('assets/images/bed.png')}}" alt="" height="33" width="25">
                    @endfor
                @endif
            </div>
            <div class="room-details">
                @if($room->room_price)
                    <p>Price : {{ $room->room_price }}</p>
                @endif
                @if ($room->room_size)
                    <p>Size : {{ $room->room_size }}</p>
                @endif
                <p>Breakfast : @if($room->is_breakfast == 1) <label>Yes</label>@else <label>No</label>@endif</p>
            </div>
            {{-- <p>
                {{ $room->room_number }}
                <span class="badge badge-info px-1">
                    <i style="color: rgb(236, 214, 14)" class="fa fa-bed fa-0">
                    </i>
                </span>
                @if (setting('room_wise_pricing_booking') == 1)
                    <span class="label label-xs reservation arrowed arrowed-right">
                        <i style="color: rgb(20, 1, 4)" class="fa fa-bed fa-0">
                            {{ $room->beds }} <br>

                            {{ $room->rent }}
                        </i>

                    </span>
                @endif
            </p> --}}
        </div>
    </div>
@endif
