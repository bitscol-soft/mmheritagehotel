<table id="data-table" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th style="width: 9%" class="text-center">Booking ID</th>
            @if (url()->current() == route('booking.referred-booking'))
                <th class="text-center">Reference By</th>
            @endif
            <th class="text-center">Customer</th>
            <th class="text-center">Date</th>
            <th class="text-center">Booked Time</th>
            {{-- <th class="text-center">Booked Date</th> --}}
            <th class="text-center" style="width:12%">Hall Room</th>
            <th class="text-center">Booking From</th>
            <th class="text-right">Transaction</th>
            <th class="text-center">Status</th>
            <th class="text-center" style="width: 10%">Action</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($booking as $key => $data)
            {{-- @dd($data->bookingDetails) --}}
            {{-- @dd($data) --}}
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
                    <p><b>{{ $data->booked_time }}</b></p>
                </td>
                {{-- <td class="text-center">
                    <p><b>Date:</b> {{ $data->check_in_date }}</p>
                    @if ($data->check_in_date)
                        <p><b>Time:</b> {{ $data->check_in_date->format('H:i:s A') }}</p>
                    @endif
                </td> --}}
                <td class="text-center">
                <span class="label label-xs label-primary arrowed arrowed-right" style="margin-bottom: 3px">
                    @foreach ($data->details->take(6) as $item)
                        {{ optional($item->hall)->room_number }}
                    @endforeach
                </span>
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
                    <p><b>Subtotal:</b> <span style="font-size: 15px">
                            {{ calculateCurrencyAmount($sub_total = optional($data->transection)->total_amount) }}
                            <span class="currency-sign"></span></span>
                    </p>
                    @php
                        calculateCurrencyAmount($paid_amount = optional($data->transection)->collection);
                        // calculateCurrencyAmount($extra_amount = optional($data->bookingExtraCharge)->sum('extra_amount'));
                    @endphp
                    <p><b>Paid:</b> <span class="green" style="font-size: 15px">
                            {{ calculateCurrencyAmount($paid_amount = optional($data->transection)->collection) }}
                            <span class="currency-sign"></span></span>
                    </p>
                    <p><b>Due:</b> <span class="red" style="font-size: 15px">
                            {{ calculateCurrencyAmount($sub_total - $paid_amount - optional($data->transection)->discount) > 0 ? calculateCurrencyAmount($sub_total - $paid_amount) : '0' }}
                            <span class="currency-sign"></span></span>
                    </p>

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
                        <!------- CHECK IN + BOOKING EDIT + BOOKING CANCEL ------->
                        @if ($data->status == 0 || $data->status == 1 || $data->status == 2)
                            @if ($data->status == 0 || $data->status == 2)
                                <a href="#check-in{{ $data->id }}" role="button" data-toggle="modal"
                                    class="btn btn-xs btn-pink" title="Check IN">
                                    {{-- <i class="fa fa-check-square"></i> --}}
                                    <i class="fa fa-check-square"></i>
                                </a>

                                <a href="{{ route('banquet.booking.edit', $data->id) }}" class="btn btn-xs btn-success"
                                    title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>

                                @if (hasPermission('banquet.booking.cancel', $slugs))
                                    <button type="button"
                                        onclick="cancelBooking(`{{ route('cancel-booking', $data->id) }}`)"
                                        class="btn btn-xs btn-warning" title="Cancel Reservation">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                @endif
                            @endif

                            @if ($data->status == 1)


                                <a href="{{ route('banquet.generate.invoice-v2', $data->id) }}" target="_blank"
                                    class="btn btn-xs btn-success" title="Print Invoice">
                                    <i class="fa fa-cloud-download"></i>
                                </a>

                                {{-- <a href="{{ route('banquet.booking.show', $data->id) }}" class="btn btn-xs btn-warning"
                                    title="Check Out">
                                    <i class="fa fa-exchange"></i>
                                </a> --}}
                            @endif
                            {{-- <!------- showExtendDate ------->
                            @if ($data->booking_type != 'Bulk')
                                <button type="button" onclick="showExtendDateModal({{ $data->id }})"
                                    class="btn btn-xs btn-info extend-date-btn" title="Extend Checkout Date">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            @endif --}}
                        @endif


                        <!------- PROJECT DETAILS ------->
                        <a href="#project-details{{ $data->id }}" role="button" data-toggle="modal"
                            class="btn btn-xs btn-purple" title="View Details">
                            <i class="fa fa-eye"></i>
                        </a>




                        <!------- BOOKING INVOICE ------->
                        {{-- @if ($data->status == 3) --}}
                        <a href="{{ route('banquet.generate.invoice-v2', $data->id) }}" target="_blank"
                            class="btn btn-xs btn-success" title="Invoice">
                            <i class="fa fa-print"></i>
                        </a>
                        {{-- @endif --}}


                        <!------- RESERVATION INVOICE ------->
                        <a href="{{ route('banquet.generate.reservation-invoice', $data->id) }}"
                            class="btn btn-xs btn-success" title="Reservation Confirmation" target="_blank">
                            <i class="fa fa-file-text-o"></i>
                        </a>





                        <!------- DUE COLLECTION ------->
                        @if (hasPermission('bookings.advance', $slugs) && $sub_total - $paid_amount - optional($data->transection)->discount > 0)
                        <button type="button"
                                onclick="dueCollection(`{{ route('banquet.due-collection', $data->id) }}`, this, `{{ calculateCurrencyAmount($sub_total - $paid_amount, 1) }}`)"
                                class="btn btn-xs btn-info" title="Due Collection">
                                <i class="fa fa-dollar"></i>
                            </button>
                        @endif


                        <!------- EXTRA CHARGE ------->
                        {{-- @if ($data->status == 1)
                            <button type="button"
                                onclick="showExtraChargeModal(this, {{ $data->id }}, '{{ $data->booking_number }}')"
                                class="btn btn-xs btn-info extra-charge-btn" title="Extra Charge">
                                <i class="fa fa-money"></i>
                            </button>
                        @endif --}}

                        <!------- BOOKING DELETE ------->
                        @if (hasPermission('banquet.booking.delete', $slugs))
                            <button type="button"
                                onclick="delete_item(`{{ route('banquet.booking.destroy', $data->id) }}`)"
                                class="btn btn-xs btn-danger" title="Delete">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>

            @if ($data->booking_type != 'Bulk')
                {{-- @include('booking._modal.extend-date-modal') --}}
            @endif

        @endforeach
    </tbody>
</table>
