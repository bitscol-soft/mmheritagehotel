{{-- Progress and stay summary for the new-booking form. Display only: it reads $booking and submits nothing. --}}
@php
    $mmStayRows = collect($booking ?? [])->values();
    $mmStayFirst = $mmStayRows->first();
    $mmNights = (int) $mmStayRows->max('nights');
@endphp
{{-- W3.9: was a hand-rolled <nav class="mm-steps"> <ol> <li>...</ol>. Now uses the shared <x-mm.stepper> component. The 3 steps map to the existing flow: rooms → guest → review. --}}
<x-mm.stepper
    :steps="[
        ['key' => 'rooms',   'label' => 'Select rooms'],
        ['key' => 'guest',   'label' => 'Guest and stay details'],
        ['key' => 'review',  'label' => 'Review and save'],
    ]"
    current="guest"
    class="mm-no-print"
    aria-label="Booking progress"
/>
<dl class="mm-stay-summary" aria-label="Selected stay">
    <div><dt>Rooms</dt><dd>{{ $mmStayRows->count() }}</dd></div>
    <div><dt>Check-in</dt><dd>{{ $mmStayFirst['check_in'] ?? '-' }}</dd></div>
    <div><dt>Check-out</dt><dd>{{ $mmStayFirst['check_out'] ?? '-' }}</dd></div>
    <div><dt>Nights</dt><dd>{{ $mmNights ?: '-' }}</dd></div>
</dl>
