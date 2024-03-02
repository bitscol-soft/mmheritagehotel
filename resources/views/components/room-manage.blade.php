@if (hasPermission('bookings.create', p_slugs()))
    <form class="form-horizontal" id="searchForm" method="get">

        <div class="col-md-10">
            <div class="row">
                <label class="col-sm-3 control-label">Check Availability</label>
                <div class="col-xs-7 col-sm-7">
                    <div class="input-group">

                        <span class="input-group-addon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>

                        <input class="form-control" type="text" name="booking_date" id="available_date"
                            value="{{ request('booking_date', $mix_date) }}" autocomplete="off" />

                    </div>
                </div>
                <div class="col-xs-4">

                </div>

            </div>
        </div>
        <div class="col-md-2">
            <a href="{{ route('report.monthly-booking') }}" target="_blank" class="btn btn-sm btn-info pull-right">
                <i class="fa fa-search-plus"></i>
            </a>
        </div>
    </form>

    <div class="col-sm-9 col-sm-offset-3 my-2">
        <p>
            <span class="label label-xs reservation arrowed arrowed-right">Reservation</span>
            <span class="label label-xs booked arrowed arrowed-right">Booked</span>
            <span class="label label-xs label-danger arrowed arrowed-right">Check In</span>
            {{-- <span class="label label-xs label-success arrowed arrowed-right">Ready Room</span> --}}
            <span class="label label-xs label-inverse arrowed arrowed-right">Dirty</span>
            <span class="label label-xs label-purple arrowed arrowed-right">Maintenance</span>
            {{-- <span class="label label-xs arrowed arrowed-right" style="background: #0c284f">Ready For Checkout</span> --}}
            <span class="label label-xs today-checkout arrowed arrowed-right">Today Checkout</span>

        </p>
    </div>

    <div class="search-results mt-5">

        <form action="{{ route('booking.next.step') }}" method="get" id="booking-form">

            <input type="hidden" name="booking_availabe" value="{{ request('booking_date', $mix_date) }}">

            @foreach ($categories as $category)
                <div class="room-search-list mt-1">
                    <strong style="font-size:20px">{{ $category->name }}  @if (setting('room_wise_pricing_booking') != 1)
                        - {{ $category->price }}
                    @endif</strong>

                    <div class="row action-detail p-1">
                        <div
                            style="width: 25%; height: 3px; background: #609660; float: left;margin-left:1%;margin-bottom:10px">
                            &nbsp;
                        </div>
                    </div>

                    <div class="room-list">
                        <div class="row">
                            @forelse ($category->rooms as $room)
                                @php
                                    $status = '';
                                    $status_val = '';
                                    if ($room->is_booked >= 1) {
                                        $status = 'booked';
                                    } elseif ($room->status == 2) {
                                        $status = 'orange';
                                        $status_val = 2;
                                    } elseif ($room->status == 1) {
                                        $status_val = 1;
                                    } elseif ($room->status == 0) {
                                        $status = 'inverse';
                                        $status_val = 0;
                                    } elseif ($room->booking_cart_count >= 1) {
                                        $status = 'store';
                                    } elseif ($room->is_reservation >= 1) {
                                        $status = 'reservation';
                                    }
                                    if ($room->today_checkout >= 1) {
                                        $status = 'today-checkout';
                                    }
                                @endphp
                                <div class="col-lg-2 col-md-3 col-sm-4" style="border-radius: 15px">
                                    <x-room-status :status="$status" :room="$room" :statusvalue="$status_val"
                                        :category="$category" />
                                </div>
                            @empty
                                <div class="col-md-5">
                                    <strong class="text-danger">
                                        No room found under this category!
                                    </strong>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="info-submit mt-3 text-right">
                <button type="submit" name="submit" value="reserve"
                    class="btn-outline-primary btn-sm next-step-btn no-border">
                    <i class="fad fa-box-check"></i> Reserve
                </button>
                <button type="submit" name="submit" value="book"
                    class="btn-outline-info btn-sm next-step-btn no-border">
                    <i class="fal fa-paper-plane"></i> Book Now
                </button>
            </div>
        </form>
    </div>

@endif
