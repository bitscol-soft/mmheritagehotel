@php
    $roomNumber = $room->room_number;
    $isOccupied = $room->is_booked > 0 || $room->is_reservation > 0 || $room->is_checkin > 0;
    $info = [
        'room_id' => $room->id,
        'category_id' => $category->id,
        'number' => (string) $roomNumber,
        'category' => (string) $category->name,
        'bed_label' => $bed['label'],
        'bed_detail' => (string) ($category->bed_details ?? ''),
        'beds' => $room->beds !== null && $room->beds !== '' ? (string) $room->beds : '',
        'max_guests' => $room->max_guests !== null && $room->max_guests !== '' ? (string) $room->max_guests : '',
        'capacity' => (string) ($category->guest_capacity ?? ''),
        'can_sleep' => (string) ($category->can_sleep ?? ''),
        'size' => (string) ($category->room_sqft ?? ''),
        'smoking' => $room->smoking_status === null || $room->smoking_status === '' ? '' : ((int) $room->smoking_status === 1 ? 'Smoking' : 'Non-smoking'),
        'rate' => $room->rent ? number_format($room->rent) : '',
        'description' => (string) ($category->description ?? ''),
        'occupied' => $isOccupied,
    ];

    $state = 'available';
    $stateLabel = 'Available';

    if ($isOccupied) {
        $date = [date('Y-m-d'), Carbon\Carbon::now()->addDay()->format('Y-m-d')];
        if (request()->filled('booking_date')) {
            $date = array_map('trim', explode('-', request('booking_date')));
        }
        $dateRow = $room->booking_dates->whereNotIn('status', [3, 4])->where('date', fdate($date[0], 'Y-m-d'))->first();
        $booking = optional($dateRow)->booking;
        $guestInfo = optional($booking)->guestInfo;
        $dueBooking = optional($room->booking_dates->whereNotIn('status', [3, 4])->where('date', '>=', date('Y-m-d'))->last())->booking;

        $state = 'inhouse';
        $stateLabel = 'In-house';
        if ($room->is_reservation > 0) { $state = 'reserved'; $stateLabel = 'Reserved'; }
        if ($room->is_booked > 0) { $state = 'booked'; $stateLabel = 'Booked'; }
        if (optional($dueBooking)->check_out_date == date('Y-m-d')) { $state = 'due'; $stateLabel = 'Due today'; }

        $info['housekeeping'] = ($room->is_booked > 0 || $room->is_reservation > 0) && in_array($room->status, [0, 2]) ? ($room->status == 0 ? 'Dirty' : 'Maintenance') : '';

        if ($guestInfo && $booking) {
            $checkLabel = '';
            $checkUrl = '';
            if ($booking->status == 0 || $booking->status == 2) {
                $checkLabel = 'Check In Now';
                $checkUrl = route('check.in.update', optional($room->booking_dates->whereIn('status', [0, 2])->first())->booking_id);
            } elseif (optional($room->booking_dates->where('status', 1)->first())->booking_id) {
                $checkLabel = 'Checkout Now';
                $checkUrl = route('booking.checkout', optional($room->booking_dates->where('status', 1)->first())->booking_id) . '?room_id=' . $room->id;
            }
            $info['guest'] = [
                'name' => (string) $guestInfo->name,
                'phone' => (string) $guestInfo->phone_no,
                'passport' => (string) $guestInfo->nid_no,
                'check_in' => (string) ($booking->check_in_time ?? ''),
                'check_out' => (string) ($booking->check_out_time ?? ''),
                'note' => (string) ($booking->check_in_note ?? ''),
                'booking_id' => $booking->id,
                'check_label' => $checkLabel,
                'check_url' => $checkUrl,
                'migrate_url' => route('booking-adjusts.create', ['booking_id' => $booking->id, 'room_id' => $room->id, 'type' => 'migrate']),
                'booking_url' => url('/hotel/booking/' . $booking->id),
            ];
        }
    } else {
        $housekeepingClass = $status === 'inverse' || $status === 'orange' || $status === 'store' ? $status : '';
        if ($status === 'inverse') { $state = 'dirty'; $stateLabel = 'Dirty'; }
        elseif ($status === 'orange') { $state = 'maintenance'; $stateLabel = 'Maintenance'; }
        elseif ($status === 'store') { $state = 'cart'; $stateLabel = 'In booking cart'; }
        elseif ($status === 'today-checkout') { $stateLabel = 'Available · checkout today'; }
        $info['status_val'] = $status_val;
    }
    $info['state'] = $state;
    $info['state_label'] = $stateLabel;
    $dirtyOrMaintenance = in_array($state, ['dirty', 'maintenance']);
@endphp
<div class="mmb-tile room-status-ui" data-state="{{ $state }}">
    @unless ($isOccupied)
        {{-- Legacy hooks: updateStatus() edits this proxy; the board mirrors it onto the visible card. --}}
        <span class="room-heading-right mmb-proxy-trigger" hidden onclick="updateStatus(`{{ $room->id }}`,`{{ $status_val }}`, this)"></span>
        <span class="room-info mmb-proxy {{ $housekeepingClass ?? '' }}" hidden aria-hidden="true"></span>
    @endunless
    <button type="button" class="mmb-card" data-room="{{ json_encode($info) }}" data-rate="{{ (float) $room->rent }}" data-state="{{ $state }}"
        aria-label="Room {{ $roomNumber }}, {{ $bed['label'] }}, {{ $stateLabel }}. Open details.">
        <span class="mmb-card-top">
            <span class="mmb-number">{{ $roomNumber }}</span>
            <span class="mmb-tick" aria-hidden="true"><i class="fa fa-check"></i></span>
        </span>
        <span class="mmb-bed" title="{{ $bed['label'] }}">@include('home._inc.bed-icon', ['type' => $bed['type'], 'count' => $bed['count']])</span>
        <span class="mmb-bed-label">{{ $bed['label'] }}</span>
        <span class="mmb-chip" data-state="{{ $state }}"><span class="mmb-chip-dot" aria-hidden="true"></span><span class="mmb-chip-text">{{ $stateLabel }}</span></span>
        @if (setting('room_wise_pricing_booking') == 1 && $room->rent)
            <span class="mmb-rate">{{ number_format($room->rent) }}</span>
        @endif
    </button>
</div>
