<table id="data-table" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th style="width: 9%" class="text-center">Booking ID</th>
            @if (url()->current() == route('booking.referred-booking'))
                <th class="text-center">Reference By</th>
            @endif
            <th class="text-center">Customer</th>
            <th class="text-center">Date</th>
            <th class="text-center">Check IN</th>
            <th class="text-center">Check Out</th>
            <th class="text-center" style="width:12%">Room</th>
            <th class="text-center">Booking From</th>
            <th class="text-right">Transaction</th>
            <th class="text-center">Status</th>
            <th class="text-center" style="width: 10%">Action</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($booking as $key => $data)
            <tr>
                <td class="text-center">

                    <strong>{{ $data->booking_number }}</strong>
                </td>
                @if (url()->current() == route('booking.referred-booking'))
                    <td class="text-center">
                        <strong>{{ $data->reference }}</strong>
                    </td>
                @endif
                <td>
                    <p>{{ optional($data->guestInfo)->name }}</p>
                    <p>{{ optional($data->guestInfo)->phone_no }}</p>
                </td>
                <td class="text-center">{{ $data->booking_date }}</td>
                <td class="text-center">
                    <p><b>Date:</b> {{ $data->check_in_date }}</p>
                    @if ($data->check_in_time)
                        <p><b>Time:</b> {{ $data->check_in_time->format('H:i:s A') }}</p>
                    @endif
                </td>
                <td class="text-center">
                    <p><b>Date:</b> {{ $data->check_out_date }}</p>
                    @if ($data->check_out_time)
                        <p><b>Time:</b> {{ $data->check_out_time->format('H:i:s A') }}</p>
                    @endif
                </td>
                <td class="text-center">
                    @if ($data->booking_type == 'Bulk')
                        <span class="label label-xs label-primary arrowed arrowed-right">All Room</span>
                    @else
                        @foreach ($data->bookingDetails->sortByDesc('id')->take(1) as $bookingDetail)
                            @if ($bookingDetail->roomNumber == null)
                                {{-- <span class="label label-xs booked arrowed arrowed-right" style="margin-bottom: 5px"> --}}
                                <a href="{{ route('booking.edit', $data->id) }}" class="btn btn-xs btn-success"
                                    title="Assign">
                                    Assign Room
                                </a>
                                {{-- </span> --}}
                            @else
                                {{-- <span class="label label-xs label-primary arrowed arrowed-right"
                                    style="margin-bottom: 3px">
                                    {{ optional($bookingDetail->roomNumber)->name }}-
                                    {{ optional($bookingDetail->roomNumber)->room_number }}
                                </span> --}}

                                @php
                                    $details = optional(optional($data->transection)->source)->details;
                                @endphp

                                @foreach ($details->take(6) as $item)
                                    <label class="label label-success"
                                        style="margin-top: 2px">{{ optional($item->roomNumber)->room_number }}</label>
                                @endforeach
                                @if ($details->count() >= 6)
                                    {{-- <td class="text-center"> --}}
                                    <span data-rel="popover" data-trigger="hover"
                                        data-content="
                                    @foreach ($details ?? [] as $key => $room)
                                    {{ optional($room->roomNumber)->room_number }}, @endforeach
                                    "
                                        data-placement="top" data-title="Room No">
                                        <i class="fas fa-info-circle fa-2x text-info"></i>
                                    </span>
                                    {{-- </td> --}}
                                @endif
                            @endif
                        @endforeach
                    @endif
                </td>
                <td class="text-center">
                    {{-- {{ $data->booking_from == 1 ? 'Front Desk' : 'Official' }} --}}
                    @if ($data->booking_from == 1)
                        Front Desk
                    @elseif ($data->booking_from == 3)
                        Website
                    @else
                        Official
                    @endif
                </td>

                <td class="text-right transactions">
                    <p><b>Subtotal:</b> <span
                            style="font-size: 15px"><span
                                class="currency-sign"></span> {{ calculateCurrencyAmount($sub_total = optional($data->transection)->total_amount) }}</span>
                    </p>
                    @php
                        calculateCurrencyAmount($paid_amount = optional($data->transection)->collection);
                        $extra_amount = optional($data->bookingExtraCharge)->sum('extra_amount');
                    @endphp
                    <p><b>Paid:</b> <span class="green"
                            style="font-size: 15px"><span
                                class="currency-sign"></span> {{ calculateCurrencyAmount($paid_amount = optional($data->transection)->collection) }}</span>
                    </p>
                    <p><b>Due:</b> <span class="red" style="font-size: 15px"><span class="currency-sign"></span></span>
                            {{ calculateCurrencyAmount($sub_total - $paid_amount - optional($data->transection)->discount) > 0 ? calculateCurrencyAmount($sub_total - $paid_amount) : '0' }}

                    </p>
                    @if ($data->bookingExtraCharge->count() > 0)
                        <p>
                            <b>Extra Charge:</b>
                            <span class="green"
                                style="font-size: 15px"><span class="currency-sign"></span> {{ calculateCurrencyAmount(optional($data->bookingExtraCharge)->sum('extra_amount')) }}

                            </span>
                        </p>
                    @endif

                    @if (count($data->bar_pay_booking) > 0 &&
                            $data->bar_pay_booking->sum('subtotal') - $data->bar_pay_booking->sum('paid_amount') > 0)
                        <p>
                            <b>Bar Due:</b>
                            <span class="green"
                                style="font-size: 15px"><span class="currency-sign"></span> {{ calculateCurrencyAmount($data->bar_pay_booking->sum('subtotal') - $data->bar_pay_booking->sum('paid_amount'), 1) }}

                            </span>
                        </p>
                    @endif
                </td>

                <td class="text-center">

                    @if ($data->status == 1)
                        <span class="label label-danger">Check In</span>
                    @elseif ($data->status == 2)
                        <span class="label booked">Booked</span>
                    @elseif ($data->status == 3)
                        <span class="label today-checkout">Check Out</span>
                    @elseif ($data->status == 0)
                        <span class="label reservation">Reservation</span>
                    @elseif ($data->status == 4)
                        <span class="label label-yellow">Cancelled</span>
                    @endif
                </td>

                <td class="text-center action-button-td">
                    <div class="action-button">
                        <!------- CHECK IN + BOOKING CANCEL ------->
                        @if ($data->status == 0 || $data->status == 1 || $data->status == 2)
                            @if ($data->status == 0 || $data->status == 2)
                                <a onclick="showCheckInModal({{ $data->id }})" role="button" data-toggle="modal"
                                    class="btn btn-xs btn-pink" title="Check IN">
                                    {{-- <i class="far fa-check-square"></i> --}}
                                    <i class="fa fa-check-square"></i>
                                </a>

                                {{-- EDIT ROUTE OLD LOCATION --}}
                                @if (hasPermission('bookings.cancel', $slugs))
                                    <button type="button"
                                        onclick="cancelBooking(`{{ route('cancel-booking', $data->id) }}`)"
                                        class="btn btn-xs btn-warning" title="Cancel Reservation">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                @endif
                            @endif

                            @if ($data->status == 1)
                                @if (count($data->bookingAdjusts->toArray()) != 0)
                                    <a href="javascript:void(0)" class="btn btn-xs btn-inverse adjustBtn"
                                        title="Adjust or Transfer">
                                        <i class="fa fa-adjust"></i>
                                    </a>
                                @else
                                    <a href="{{ route('booking-adjusts.create', ['booking_id' => $data->id]) }}"
                                        class="btn btn-xs btn-inverse" title="Adjust or Transfer">
                                        <i class="fa fa-adjust"></i>
                                    </a>
                                @endif

                                {{-- <a href="{{ route('generate.invoice-v2', $data->id) }}" target="_blank"
                                    class="btn btn-xs btn-success" title="Print Invoice">
                                    <i class="fa fa-cloud-download"></i>
                                </a> --}}

                                <a href="{{ route('booking.show', $data->id) }}" class="btn btn-xs btn-warning"
                                    title="Check Out">
                                    <i class="fa fa-exchange"></i>
                                </a>
                            @endif
                            <!------- EDIT ROUTE ------->
                            <a href="{{ route('booking.edit', $data->id) }}" class="btn btn-xs btn-success"
                                title="Edit">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <!------- showExtendDate ------->
                            @if ($data->booking_type != 'Bulk')
                                <button type="button" onclick="showExtendDateModal({{ $data->id }})"
                                    class="btn btn-xs btn-info extend-date-btn" title="Extend Checkout Date">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                            @endif
                        @endif


                        <!------- PROJECT DETAILS ------->
                        <a onclick="showDetailsModal({{ $data->id }})" role="button" data-toggle="modal"
                            class="btn btn-xs btn-purple" title="View Details">
                            <i class="fa fa-eye"></i>
                        </a>

                        <!------- GUEST IMAGE CHANGE ------->
                        <a onclick="showImagesModal({{ $data->id }})" role="button" data-toggle="modal"
                            class="btn btn-xs btn-blue" title="Change Guest Image">
                            <i class="fa fa-camera"></i>
                        </a>


                        <!------- BOOKING INVOICE ------->
                        {{-- @if ($data->status == 3) --}}
                        <a href="{{ route('generate.invoice-v2', $data->id) }}" target="_blank"
                            class="btn btn-xs btn-success" title="Invoice">
                            <i class="far fa-print"></i>
                        </a>
                        {{-- @endif --}}


                        <!------- RESERVATION INVOICE ------->
                        <a href="{{ route('generate.reservation-invoice', $data->id) }}"
                            class="btn btn-xs btn-success" title="Reservation Confirmation" target="_blank">
                            <i class="far fa-receipt"></i>
                        </a>


                        <!------- VIEW MEMBER ------->
                        @if ($data->members_count > 0)
                            <a href="#member-detail-show-modal" onclick="loadMemberDetail(`{{ $data->id }}`)"
                                data-toggle="modal" role="button" class="btn btn-xs btn-info" title="View Members">
                                <i class="fa fa-users"></i>
                            </a>
                        @endif

                        @php
                            $extra_charge = optional($data->bookingExtraCharge)->sum('extra_amount');
                        @endphp
                        <!------- DUE COLLECTION ------->
                        @if (hasPermission('bookings.advance', $slugs) && $sub_total + $extra_charge - $paid_amount - optional($data->transection)->discount > 0)
                        {{-- multi pay --}}
                        {{-- <form action=""> --}}
                            <button class="btn btn-success btn-sm " type="button" name="button" title="Due Collection"
                                data-toggle="modal" data-target="#account-type-modal" onclick="dueCollectMulti(`{{ route('booking.due-collection') }}`, this, `{{ calculateCurrencyAmount($sub_total - $paid_amount + $extra_charge, 1) }}`, `{{ $data->id }}`)" name="payment">
                                <i class="fas fa-usd-circle"></i>
                            </button>
                        {{-- </form> --}}

                        {{-- <button type="button"
                            onclick="dueCollection(`{{ route('booking.due-collection', $data->id) }}`, this, `{{ calculateCurrencyAmount($sub_total - $paid_amount, 1) }}`)"
                            class="btn btn-xs btn-info" title="Due Collection">
                            <i class="fas fa-usd-circle"></i>
                        </button> --}}
                        @endif

                        <!------- BAR PAYMENT ------->
                        @if (count($data->bar_pay_booking) > 0 &&
                                $data->bar_pay_booking->sum('subtotal') - $data->bar_pay_booking->sum('paid_amount') > 0)
                            <button type="button"
                                onclick="barDueCollection(`{{ route('bar.due-collection', $data->id) }}`, this, `{{ calculateCurrencyAmount($data->bar_pay_booking->sum('subtotal') - $data->bar_pay_booking->sum('paid_amount'), 1) }}`)"
                                class="btn btn-xs btn-info bar-charge-btn" title="Bar Payment">
                                <i class="fas fa-beer"></i>
                            </button>
                        @endif

                        <!------- EXTRA CHARGE ------->
                        @if ($data->status == 1)
                            <button type="button"
                                onclick="showExtraChargeModal(this, {{ $data->id }}, '{{ $data->booking_number }}')"
                                class="btn btn-xs btn-info extra-charge-btn" title="Extra Charge">
                                <i class="fas fa-money-check-edit-alt"></i>
                            </button>
                        @endif

                        <!------- BOOKING DELETE ------->
                        @if (hasPermission('bookings.delete', $slugs))
                            <button type="button" onclick="delete_item(`{{ route('booking.destroy', $data->id) }}`)"
                                class="btn btn-xs btn-danger" title="Delete">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>

            @if ($data->booking_type != 'Bulk')
                @include('booking._modal.extend-date-modal')
            @endif

        @endforeach
    </tbody>
</table>
