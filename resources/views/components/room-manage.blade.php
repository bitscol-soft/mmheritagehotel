@if (hasPermission('bookings.create', p_slugs()))
    <div class="room-booking-board">
        <form class="form-horizontal" id="searchForm" method="get">
            <div class="row board-toolbar">
                <div class="board-date-wrap">
                    <div class="input-group board-date-group">
                        <span class="input-group-addon board-date-icon">
                            <i class="fa fa-calendar bigger-110"></i>
                        </span>
                        <input class="form-control" type="text" name="booking_date" id="available_date"
                            value="{{ request('booking_date', $mix_date) }}" autocomplete="off"
                            data-business-date="{{ today_from_system() }}"
                            placeholder="Check-in - check-out date range" aria-label="Stay date range" />
                    </div>
                </div>
                <div class="board-quick-dates" role="group" aria-label="Quick date ranges">
                    <button type="button" class="btn btn-xs" data-mode="tonight">Tonight</button>
                    <button type="button" class="btn btn-xs" data-mode="week">Next 7 days</button>
                    <button type="button" class="btn btn-xs" data-mode="weekend">Weekend</button>
                </div>
                <div class="board-actions">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fa fa-search"></i> Check Availability
                    </button>
                </div>
                <div class="board-report">
                    <a href="{{ route('report.monthly-booking') }}" target="_blank"
                        class="btn btn-sm btn-info" title="Monthly booking report">
                        <i class="fa fa-search-plus"></i> <span class="hidden-xs">Monthly Report</span>
                    </a>
                </div>
            </div>
        </form>

        <div class="board-legend">
            <span class="label label-xs board-free arrowed arrowed-right">Free</span>
            <span class="label label-xs reservation arrowed arrowed-right">Reservation</span>
            <span class="label label-xs booked arrowed arrowed-right">Booked</span>
            <span class="label label-xs label-danger arrowed arrowed-right">Check In</span>
            <span class="label label-xs label-inverse arrowed arrowed-right">Dirty</span>
            <span class="label label-xs label-purple arrowed arrowed-right">Maintenance</span>
            <span class="label label-xs today-checkout arrowed arrowed-right">Today Checkout</span>
            <span class="label label-xs board-selected arrowed arrowed-right">Selected</span>
        </div>

        <div class="search-results">
            <form action="{{ route('booking.next.step') }}" method="get" id="booking-form">
                <input type="hidden" name="booking_availabe" value="{{ request('booking_date', $mix_date) }}">

                @if (count($categories) < 1)
                    <div class="board-empty">
                        <i class="fa fa-calendar-times-o"></i>
                        No room categories found — add rooms under Hotel &rsaquo; Room Management first.
                    </div>
                @endif

                @foreach ($categories as $category)
                    @php
                        $roomsTotal = $category->rooms->count();
                        $roomsOccupied = $category->rooms->filter(function ($r) {
                            return $r->is_booked >= 1 || $r->is_reservation >= 1 || $r->is_checkin > 0;
                        })->count();
                        $roomsFree = max($roomsTotal - $roomsOccupied, 0);
                        $occPct = $roomsTotal > 0 ? (int) round($roomsOccupied / $roomsTotal * 100) : 0;
                    @endphp
                    <div class="room-search-list panel panel-default">
                        <div class="panel-heading clearfix">
                            <span class="category-name">{{ $category->name }}</span>
                            @if (setting('room_wise_pricing_booking') != 1)
                                <span class="category-price">{{ $category->price }}</span>
                            @endif
                            <span class="category-count">
                                <span class="badge badge-success">{{ $roomsFree }} free</span>
                                <span class="badge">{{ $roomsTotal }} rooms</span>
                                <span class="badge badge-occ" title="{{ $roomsOccupied }} of {{ $roomsTotal }} occupied">{{ $occPct }}% occupied</span>
                            </span>
                        </div>

                        <div class="panel-body room-list">
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
                                    <div class="col-md-1 col-sm-4 col-xs-6 room-tile-col">
                                        <x-room-status :status="$status" :room="$room" :statusvalue="$status_val"
                                            :category="$category" />
                                    </div>
                                @empty
                                    <div class="col-sm-12">
                                        <div class="board-empty">
                                            <i class="fa fa-meh-o"></i>
                                            No rooms found under this category.
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="info-submit board-footer">
                    <span class="board-hint hidden-xs"><i class="fa fa-hand-o-up"></i>
                        Tap a room tile to add it to the selection</span>
                    <span class="selection-summary hidden">
                        <i class="fa fa-check-square-o"></i>
                        <strong><span class="sel-count">0</span> room(s)</strong> selected
                        <span class="sel-total-wrap">&middot; est. <span class="sel-total">0</span></span>
                    </span>
                    <span class="footer-buttons">
                        <button type="submit" name="submit" value="reserve"
                            class="btn btn-sm btn-white next-step-btn">
                            <i class="fa fa-bookmark"></i> Reserve
                        </button>
                        <button type="submit" name="submit" value="book"
                            class="btn btn-sm btn-primary next-step-btn">
                            <i class="fa fa-send"></i> Book Now
                        </button>
                    </span>
                </div>
            </form>
        </div>
    </div>
@endif
