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
<nav {{ $attributes->merge(['class' => 'mm-steps', 'aria-label' => 'Progress']) }}>
    <ol class="mm-stepper" role="tablist">
        @foreach ($steps as $i => $s)
            @php
                $state = $currentIndex === $i ? 'is-current' : ($i < $currentIndex ? 'is-done' : 'is-upcoming');
            @endphp
            <li class="mm-step {{ $state }} mm-step-{{ $state }}" role="presentation" @if($currentIndex === $i) aria-current="step" @endif>
                <a href="#{{ $s['key'] }}"
                   data-step="{{ $s['key'] }}"
                   data-mm-step="{{ $s['key'] }}"
                   class="mm-step-link"
                   role="tab"
                   aria-selected="{{ $currentIndex === $i ? 'true' : 'false' }}">
                    <span class="mm-step-mark" aria-hidden="true">@if($state === 'is-done')&#10003;@else{{ $i + 1 }}@endif</span>
                    <span class="mm-step-label">@if($state === 'is-done')<span class="mm-visually-hidden">Completed: </span>@endif{{ $s['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ol>
</nav>
