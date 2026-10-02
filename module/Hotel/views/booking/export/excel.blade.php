<table id="data-table" class="table table-striped table-bordered table-hover">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="9" style="text-align: center">Booking Report</th>
            </tr>
        @endif
        <tr>
            <th style="width: 9%" class="text-center">Booking ID</th>
            <th class="text-center">Customer</th>
            <th class="text-center">Date</th>
            <th class="text-center">Check IN</th>
            <th class="text-center">Check Out</th>
            <th class="text-center" style="width:12%">Room</th>
            <th class="text-center">Booking From</th>
            <th class="text-right">Transaction</th>
            <th class="text-center">Status</th>
            @if (!request()->filled('export_type'))
                <th class="text-center" style="width: 10%">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>

        @foreach ($booking as $key => $data)
            <tr>
                <td class="text-center">
                    <strong>{{ $data->booking_number }}</strong>
                </td>
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
                    <span class="label label-xs label-primary arrowed arrowed-right"
                        style="margin-bottom: 3px">
                        {{ optional(optional($data->categoryName)->roomNumber)->name }}-
                        {{ optional(optional($data->categoryName)->roomNumber)->room_number }}
                    </span>
                </td>
                <td class="text-center">
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
                            style="font-size: 15px">{{ number_format($sub_total = optional($data->transection)->total_amount, 2) }}</span>
                    </p>
                    <p><b>Paid:</b> <span class="green"
                            style="font-size: 15px">{{ number_format($paid_amount = optional($data->transection)->collection, 2) }}</span>
                    </p>
                    <p><b>Due:</b> <span class="red"
                            style="font-size: 15px">{{ number_format($sub_total - $paid_amount, 2) }}</span>
                    </p>
                </td>

                <td class="text-center">

                    @if ($data->status == 1)
                        <span class="label label-success">Check In</span>
                    @elseif ($data->status == 3)
                        <span class="label label-danger">Check Out</span>
                    @elseif ($data->status == 0)
                        <span class="label label-danger">Reservation</span>
                    @elseif ($data->status == 4)
                        <span class="label label-yellow">Cancelled</span>
                    @endif
                </td>

                @if (!request()->filled('export_type'))
                <td class="text-center action-button-td">
                    <div class="action-button">

                        <!------- CHECK IN + BOOKING EDIT + BOOKING CANCEL ------->
                        @if ($data->status == 0 || $data->status == 1)

                            @if ($data->status == 0)
                                <a href="#check-in{{ $data->id }}" role="button"
                                    data-toggle="modal" class="btn btn-xs btn-pink" title="Check IN">
                                    {{-- <i class="fa fa-check-square"></i> --}}
                                    <i class="fa  fa-check-square"></i>
                                </a>

                                <a href="{{ route('booking.edit', $data->id) }}"
                                    class="btn btn-xs btn-success" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>

                                @if (hasPermission('bookings.cancel', $slugs))
                                    <button type="button"
                                        onclick="cancelBooking(`{{ route('cancel-booking', $data->id) }}`)"
                                        class="btn btn-xs btn-warning" title="Cancel Reservation">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                @endif
                            @endif

                            @if ($data->status == 1)
                                <a href="{{ route('booking-adjusts.create', ['booking_id' => $data->id]) }}"
                                    class="btn btn-xs btn-inverse" title="Adjust or Transfer">
                                    <i class="fa fa-adjust"></i>
                                </a>

                                {{-- <a href="{{ route('generate.invoice-v2', $data->id) }}" target="_blank"
                                    class="btn btn-xs btn-success" title="Print Invoice">
                                    <i class="fa fa-cloud-download"></i>
                                </a> --}}

                                <a href="{{ route('booking.show', $data->id) }}"
                                    class="btn btn-xs btn-warning" title="Check Out">
                                    <i class="fa fa-exchange"></i>
                                </a>
                            @endif

                        @endif


                        <!------- PROJECT DETAILS ------->
                        <a href="#project-details{{ $data->id }}" role="button" data-toggle="modal"
                            class="btn btn-xs btn-purple" title="View Details">
                            <i class="fa fa-eye"></i>
                        </a>


                        <!------- BOOKING INVOICE ------->
                        {{-- @if ($data->status == 3) --}}
                            <a href="{{ route('generate.invoice-v2', $data->id) }}" target="_blank"
                                class="btn btn-xs btn-success" title="Print Invoice">
                                <i class="fa fa-print"></i>
                            </a>
                        {{-- @endif --}}


                        <!------- RESERVATION INVOICE ------->
                        <a href="{{ route('generate.reservation-invoice', $data->id) }}"
                            class="btn btn-xs btn-success" title="Print Invoice" target="_blank">
                            <i class="fa fa-file-text-o"></i>
                        </a>


                        <!------- VIEW MEMBER ------->
                        @if ($data->members_count > 0)
                            <a href="#member-detail-show-modal"
                                onclick="loadMemberDetail(`{{ $data->id }}`)" data-toggle="modal"
                                role="button" class="btn btn-xs btn-info" title="View Members">
                                <i class="fa fa-users"></i>
                            </a>
                        @endif


                        <!------- DUE COLLECTION ------->
                        @if (hasPermission('bookings.advance', $slugs) && $sub_total - $paid_amount > 0)
                            <button type="button"
                                onclick="dueCollection(`{{ route('booking.due-collection', $data->id) }}`, this)"
                                class="btn btn-xs btn-info" title="Due Collection">
                                <i class="fa fa-dollar"></i>
                            </button>
                        @endif


                        <!------- BOOKING DELETE ------->
                        @if (hasPermission('bookings.delete', $slugs))
                            <button type="button"
                                onclick="delete_item(`{{ route('booking.destroy', $data->id) }}`)"
                                class="btn btn-xs btn-danger" title="Delete">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        @endif
                    </div>
                </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
