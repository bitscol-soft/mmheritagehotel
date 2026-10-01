<div class="mm-dashboard-grid">
<article class="mm-dashboard-stat">
    <h2>Bookings</h2>
    <p class="mm-dashboard-value">{{ $today_booking ?? '0' }} <span>today</span></p>
    <dl><div><dt>Last day</dt><dd>{{ $yesterday_booking ?? '0' }}</dd></div><div><dt>Last 7 days</dt><dd>{{ $last_7_days_booking ?? '0' }}</dd></div></dl>
</article>
<article class="mm-dashboard-stat">
    <h2>Check-ins</h2>
    <p class="mm-dashboard-value">{{ $today_checkin ?? 0 }} <span>today</span></p>
    <dl><div><dt>Last day</dt><dd>{{ $yesterday_checkin ?? '0' }}</dd></div><div><dt>Last 7 days</dt><dd>{{ $last_7_days_checkin ?? 0 }}</dd></div></dl>
</article>
<article class="mm-dashboard-stat">
    <h2>Check-outs</h2>
    <p class="mm-dashboard-value">{{ $today_checkout ?? 0 }} <span>today</span></p>
    <dl><div><dt>Last day</dt><dd>{{ $yesterday_checkout ?? 0 }}</dd></div><div><dt>Last 7 days</dt><dd>{{ $last_7_days_checkout ?? 0 }}</dd></div></dl>
</article>
<article class="mm-dashboard-stat mm-dashboard-rooms">
    <h2>Room overview</h2>
    <p class="mm-dashboard-value">{{ $total_room }} <span>total rooms</span></p>
    <dl><div><dt>Booked</dt><dd>{{ $today_room_booked ?? 0 }}</dd></div><div><dt>Ready room*</dt><dd>{{ $total_room - $today_room_booked ?? 0 }}</dd></div></dl>
</article>
</div>
<p class="tw-m-0 tw-mt-2 tw-text-xs tw-text-muted">*Ready room is the existing total-minus-booked figure, not a housekeeping readiness check.</p>
