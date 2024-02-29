@if (hasPermission('Booking.HouseKeeping', p_slugs()))

    <div class="col-sm-9 col-sm-offset-3 my-2">
        <p>
            <span class="label label-xs reservation arrowed arrowed-right">Reservation</span>
            <span class="label label-xs booked arrowed arrowed-right">Booked</span>
            <span class="label label-xs label-danger arrowed arrowed-right">Check In</span>
            {{-- <span class="label label-xs label-success arrowed arrowed-right">Ready Room</span> --}}
            <span class="label label-xs label-inverse arrowed arrowed-right">Dirty</span>
            <span class="label label-xs label-purple arrowed arrowed-right">Maintenance</span>
            <span class="label label-xs today-checkout arrowed arrowed-right">Today Checkout</span>

        </p>
    </div>

    <div class="search-results mt-5">

        @foreach ($categories as $category)
            <div class="room-search-list mt-1">
                <strong style="font-size:20px">{{ $category->name }} - {{ $category->price }}</strong>

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
                            <div class="col-md-1 col-sm-4" style="border-radius: 15px">
                                <x-room-status-keeping :status="$status" :room="$room" :statusvalue="$status_val"
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

    </div>

@endif
