{{-- Progress and stay summary for the new-booking form. Display only: it reads $booking and submits nothing. --}}
@php
    $mmStayRows = collect($booking ?? [])->values();
    $mmStayFirst = $mmStayRows->first();
    $mmNights = (int) $mmStayRows->max('nights');
@endphp
<nav class="mm-steps mm-no-print" aria-label="Booking progress">
    <ol>
        <li class="is-done"><span class="mm-step-mark" aria-hidden="true">&#10003;</span><span><span class="mm-visually-hidden">Completed: </span>Select rooms</span></li>
        <li class="is-current" aria-current="step"><span class="mm-step-mark" aria-hidden="true">2</span><span>Guest and stay details</span></li>
        <li><span class="mm-step-mark" aria-hidden="true">3</span><span>Review and save</span></li>
    </ol>
</nav>
<dl class="mm-stay-summary" aria-label="Selected stay">
    <div><dt>Rooms</dt><dd>{{ $mmStayRows->count() }}</dd></div>
    <div><dt>Check-in</dt><dd>{{ $mmStayFirst['check_in'] ?? '-' }}</dd></div>
    <div><dt>Check-out</dt><dd>{{ $mmStayFirst['check_out'] ?? '-' }}</dd></div>
    <div><dt>Nights</dt><dd>{{ $mmNights ?: '-' }}</dd></div>
</dl>
