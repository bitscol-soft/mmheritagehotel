@props([
    'steps' => [],   // [['key' => 'rooms', 'label' => 'Rooms'], ['key' => 'guest', 'label' => 'Guest'], ...]
    'current' => null,  // step key; default = first
])

@php
    $current = $current ?: (old('step') ?: ($steps[0]['key'] ?? null));
    $currentIndex = -1;
    foreach ($steps as $i => $s) {
        if (($s['key'] ?? null) === $current) { $currentIndex = $i; break; }
    }
@endphp

<ol {{ $attributes->merge(['class' => 'mm-stepper']) }} role="tablist" aria-label="Progress">
    @foreach ($steps as $i => $s)
        @php
            $state = $currentIndex === $i ? 'is-current' : ($i < $currentIndex ? 'is-done' : 'is-upcoming');
        @endphp
        <li class="mm-step mm-step-{{ $state }}" role="presentation">
            <a href="#{{ $s['key'] }}"
               data-step="{{ $s['key'] }}"
               data-mm-step="{{ $s['key'] }}"
               class="mm-step-link"
               role="tab"
               aria-selected="{{ $currentIndex === $i ? 'true' : 'false' }}">
                <span class="mm-step-mark" aria-hidden="true">{{ $i + 1 }}</span>
                <span class="mm-step-label">{{ $s['label'] }}</span>
            </a>
        </li>
    @endforeach
</ol>
