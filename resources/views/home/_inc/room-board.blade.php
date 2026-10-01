@if (hasPermission('bookings.create', p_slugs()))
@php
    // Bed type comes from category bed details first, then the room's bed count.
    $bedProfile = function ($room, $category) {
        $text = strtolower(trim(($category->bed_details ?? '') . ' ' . ($room->beds ?? '')));
        $count = (int) preg_replace('/\D/', '', (string) ($room->beds ?? ''));
        $type = 'generic';
        if (preg_match('/twin|two single|2 single/', $text)) $type = 'twin';
        elseif (preg_match('/triple|three/', $text)) $type = 'triple';
        elseif (preg_match('/king|queen|double|full|couple|matrimonial/', $text)) $type = 'double';
        elseif (preg_match('/single|one bed|1 bed/', $text)) $type = 'single';
        elseif ($count === 1) $type = 'single';
        elseif ($count === 2) $type = 'twin';
        elseif ($count === 3) $type = 'triple';
        elseif ($count > 3) $type = 'multi';
        $labels = ['single' => 'Single bed', 'double' => 'Double bed', 'twin' => 'Twin beds', 'triple' => 'Triple beds', 'multi' => $count . ' beds', 'generic' => 'Bed not set'];
        return ['type' => $type, 'count' => $count, 'label' => $labels[$type]];
    };
    $boardDate = request('booking_date', $mix_date);
@endphp
<div class="mmb" id="mmb" data-add-url="/hotel/add_booking" data-remove-url="/hotel/remove_booking_next">
    <form class="mmb-search" id="searchForm" method="get">
        <div class="mmb-toolbar">
            <div class="mmb-date">
                <label for="available_date" class="mmb-sr">Stay date range</label>
                <span class="mmb-date-icon" aria-hidden="true"><i class="fa fa-calendar"></i></span>
                <input class="form-control" type="text" name="booking_date" id="available_date" value="{{ $boardDate }}" autocomplete="off" placeholder="Check-in - check-out date range">
            </div>
            <div class="mmb-quick" role="group" aria-label="Quick date ranges">
                <button type="button" class="mmb-pill" data-mode="tonight">Tonight</button>
                <button type="button" class="mmb-pill" data-mode="week">Next 7 days</button>
                <button type="button" class="mmb-pill" data-mode="weekend">Weekend</button>
            </div>
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Check Availability</button>
            <a href="{{ route('report.monthly-booking') }}" target="_blank" class="btn btn-sm btn-info" title="Monthly booking report"><i class="fa fa-search-plus"></i> <span class="hidden-xs">Monthly Report</span></a>
            <div class="mmb-group-tools">
                <button type="button" class="mmb-link" data-mmb-expand-all><i class="fa fa-plus-square-o" aria-hidden="true"></i> Expand all</button>
                <button type="button" class="mmb-link" data-mmb-collapse-all><i class="fa fa-minus-square-o" aria-hidden="true"></i> Collapse all</button>
            </div>
        </div>
    </form>


    <div class="mmb-legend" aria-label="Room status legend">
        <span class="mmb-chip" data-state="available"><span class="mmb-chip-dot"></span>Available</span>
        <span class="mmb-chip" data-state="reserved"><span class="mmb-chip-dot"></span>Reserved</span>
        <span class="mmb-chip" data-state="booked"><span class="mmb-chip-dot"></span>Booked</span>
        <span class="mmb-chip" data-state="inhouse"><span class="mmb-chip-dot"></span>In-house</span>
        <span class="mmb-chip" data-state="due"><span class="mmb-chip-dot"></span>Due today</span>
        <span class="mmb-chip" data-state="dirty"><span class="mmb-chip-dot"></span>Dirty</span>
        <span class="mmb-chip" data-state="maintenance"><span class="mmb-chip-dot"></span>Maintenance</span>
        <span class="mmb-chip" data-state="selected"><span class="mmb-chip-dot"></span>Selected</span>
    </div>

    <form action="{{ route('booking.next.step') }}" method="get" id="booking-form">
        <input type="hidden" name="booking_availabe" value="{{ $boardDate }}">

        @if (count($categories) < 1)
            <div class="mmb-empty"><i class="fa fa-calendar-times-o" aria-hidden="true"></i> No room categories found — add rooms under Hotel &rsaquo; Room Management first.</div>
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
            <section class="mmb-group" data-category="{{ $category->id }}">
                <h3 class="mmb-group-heading">
                    <button type="button" class="mmb-group-toggle" id="mmb-toggle-{{ $category->id }}" aria-expanded="true" aria-controls="mmb-body-{{ $category->id }}">
                        <i class="fa fa-chevron-down mmb-chevron" aria-hidden="true"></i>
                        <span class="mmb-group-name">{{ $category->name }}</span>
                        @if (setting('room_wise_pricing_booking') != 1)
                            <span class="mmb-group-price">{{ $category->price }}</span>
                        @endif
                        <span class="mmb-group-counts">
                            <span class="mmb-count mmb-count-free">{{ $roomsFree }} free</span>
                            <span class="mmb-count">{{ $roomsTotal }} rooms</span>
                            <span class="mmb-count mmb-count-occ" title="{{ $roomsOccupied }} of {{ $roomsTotal }} occupied">{{ $occPct }}% occupied</span>
                        </span>
                    </button>
                </h3>
                <div class="mmb-group-body" id="mmb-body-{{ $category->id }}" role="region" aria-labelledby="mmb-toggle-{{ $category->id }}">
                    <div class="mmb-grid">
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
                                $bed = $bedProfile($room, $category);
                            @endphp
                            @include('home._inc.room-card', ['room' => $room, 'category' => $category, 'status' => $status, 'status_val' => $status_val, 'bed' => $bed])
                        @empty
                            <div class="mmb-empty"><i class="fa fa-meh-o" aria-hidden="true"></i> No rooms found under this category.</div>
                        @endforelse
                    </div>
                </div>
            </section>
        @endforeach
    </form>

    {{-- Outside the form so position: sticky is bounded by the whole board, not the form; buttons submit it via the form attribute. --}}
    <div class="mmb-footer">
        <span class="mmb-hint hidden-xs"><i class="fa fa-hand-o-up" aria-hidden="true"></i> Select a room to see details and book.</span>
        <span class="mmb-selection" hidden aria-live="polite">
            <i class="fa fa-check-square-o" aria-hidden="true"></i>
            <strong><span class="mmb-sel-count">0</span> room(s)</strong> selected <span class="mmb-sel-total-wrap">&middot; est. <span class="mmb-sel-total">0</span></span>
        </span>
        <span class="mmb-footer-buttons">
            <button type="submit" form="booking-form" name="submit" value="reserve" class="btn btn-sm btn-white next-step-btn"><i class="fa fa-bookmark"></i> Reserve</button>
            <button type="submit" form="booking-form" name="submit" value="book" class="btn btn-sm btn-primary next-step-btn"><i class="fa fa-send"></i> Book Now</button>
        </span>
    </div>

    <div class="mmb-backdrop" hidden></div>
    <aside class="mmb-drawer" id="mmb-drawer" role="dialog" aria-modal="true" aria-labelledby="mmb-drawer-title" tabindex="-1" aria-hidden="true">
        <header class="mmb-drawer-head">
            <div>
                <p class="mmb-drawer-eyebrow" id="mmb-drawer-category"></p>
                <h3 class="mmb-drawer-title" id="mmb-drawer-title">Room</h3>
            </div>
            <button type="button" class="mmb-drawer-close" data-mmb-close aria-label="Close room details"><i class="fa fa-times" aria-hidden="true"></i></button>
        </header>
        <div class="mmb-drawer-body">
            <p class="mmb-drawer-state"><span class="mmb-chip" id="mmb-drawer-chip"><span class="mmb-chip-dot"></span><span class="mmb-chip-text"></span></span> <span class="mmb-drawer-hk" id="mmb-drawer-hk" hidden></span></p>
            <div class="mmb-drawer-bed"><span id="mmb-drawer-bed-icon"></span><strong id="mmb-drawer-bed-label"></strong></div>
            <dl class="mmb-drawer-list" id="mmb-drawer-list"></dl>
            <p class="mmb-drawer-description" id="mmb-drawer-description" hidden></p>
            <section class="mmb-drawer-guest" id="mmb-drawer-guest" hidden aria-labelledby="mmb-guest-title">
                <h4 id="mmb-guest-title">Guest &amp; stay</h4>
                <dl class="mmb-drawer-list" id="mmb-drawer-guest-list"></dl>
            </section>
            <p class="mmb-drawer-stay" id="mmb-drawer-stay"></p>
        </div>
        <footer class="mmb-drawer-actions" id="mmb-drawer-actions"></footer>
    </aside>
</div>

<script>
    // Quick date chips reuse the daterangepicker apply flow (sets the input and submits #searchForm).
    (function () {
        if (typeof jQuery === 'undefined' || typeof moment === 'undefined') return;
        $('#mmb').on('click', '.mmb-quick .mmb-pill', function () {
            var mode = $(this).data('mode');
            try {
                var s = moment().startOf('day');
                var e = moment().startOf('day').add(1, 'days');
                if (mode === 'week') {
                    e = s.clone().add(6, 'days');
                } else if (mode === 'weekend') {
                    s = moment().isoWeekday ? moment().isoWeekday(6) : moment().day(6);
                    if (s.isBefore(moment().startOf('day'))) s.add(7, 'days');
                    e = s.clone().add(2, 'days');
                }
                var $inp = $('input[name="booking_date"]').first();
                if (!$inp.length) return;
                var dp = $inp.data('daterangepicker');
                if (dp) {
                    dp.setStartDate(s);
                    dp.setEndDate(e);
                    dp.apply();
                } else {
                    $inp.val(s.format('MM/DD/YYYY') + ' - ' + e.format('MM/DD/YYYY'));
                    $inp.closest('form').trigger('submit');
                }
            } catch (err) { /* fall back to the manual picker */ }
        });
    })();
</script>
@endif
