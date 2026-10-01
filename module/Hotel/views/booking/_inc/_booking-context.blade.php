{{-- Round-5 UI pass: shared "at a glance" strip for booking flow pages (assaign/edit/view).
     Usage: @include('booking._inc._booking-context', ['booking' => $booking]) — display-only. --}}
@php
    $bc = $booking ?? null;
    if ($bc) {
        $bc_in   = \Carbon\Carbon::parse($bc->check_in_date)->locale('en');
        $bc_out  = \Carbon\Carbon::parse($bc->check_out_date);
        $bc_nights = max((int) $bc_in->diffInDays($bc_out), 1);
        $bc_status_map = [
            0 => ['Reservation', 'label-success'],
            1 => ['Check In', 'label-danger'],
            2 => ['Booked', 'label-pink'],
            3 => ['Check Out', 'label-info'],
            4 => ['Cancelled', 'label-yellow'],
        ];
        [$bc_status_name, $bc_status_class] = $bc_status_map[(int) $bc->status] ?? ['—', 'label-grey'];
        $bc_paid = optional($bc->transection)->collection;
        $bc_total = optional($bc->transection)->total_amount;
        $bc_due = max((float) calculateCurrencyAmount($bc_total, 1) - (float) calculateCurrencyAmount($bc_paid, 1), 0);
    }
@endphp
@if ($bc)
    <div class="board-stay-strip booking-context">
        <span class="stay-chip"><i class="fa fa-hashtag"></i> <b>{{ $bc->booking_number }}</b></span>
        <span class="stay-chip">
            <span class="label label-xs {{ $bc_status_class }} arrowed arrowed-right">{{ $bc_status_name }}</span>
        </span>
        <span class="stay-chip"><i class="fa fa-user"></i> {{ optional($bc->guestInfo)->name ?: '—' }}
            @if ($bc->bookingDetails->isNotEmpty())
                <span class="ctx-dim">&middot; {{ $bc->bookingDetails->count() }} room(s)</span>
            @endif
        </span>
        <span class="stay-chip"><i class="fa fa-sign-in"></i>
            <b>{{ $bc_in->format('d M Y') }}</b></span>
        <span class="stay-chip"><i class="fa fa-sign-out"></i>
            <b>{{ \Carbon\Carbon::parse($bc_out)->format('d M Y') }}</b></span>
        <span class="stay-chip"><i class="fa fa-moon-o"></i> <b>{{ $bc_nights }}</b>
            night{{ $bc_nights > 1 ? 's' : '' }}</span>
        @if ($bc_total !== null)
            <span class="stay-chip"><i class="fa fa-dollar"></i> <b>{{ calculateCurrencyAmount($bc_total) }}</b>
                @if ($bc_due > 0)
                    <span class="ctx-due">due {{ number_format($bc_due, 2) }}</span>
                @else
                    <span class="ctx-paid">settled</span>
                @endif
            </span>
        @endif
    </div>
@endif
