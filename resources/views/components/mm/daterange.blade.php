@props([
    'name' => 'daterange',
    'from' => null,
    'to' => null,
    'quick' => ['today', 'week', 'month', 'weekend'],  // built-in presets the JS knows
])

<div {{ $attributes->merge(['class' => 'mm-daterange']) }} data-mm-daterange data-name="{{ $name }}">
    <label class="mm-daterange-field">
        <span class="mm-daterange-label">From</span>
        <input type="date" name="{{ $name }}_from" value="{{ $from }}" class="mm-input mm-daterange-from">
    </label>
    <label class="mm-daterange-field">
        <span class="mm-daterange-label">To</span>
        <input type="date" name="{{ $name }}_to" value="{{ $to }}" class="mm-input mm-daterange-to">
    </label>
    @if (!empty($quick))
        <div class="mm-daterange-quick" role="group" aria-label="Quick ranges">
            @foreach ($quick as $q)
                <button type="button" class="mm-button mm-button-secondary mm-button-small" data-mm-quick="{{ $q }}">
                    {{ ucfirst($q) }}
                </button>
            @endforeach
        </div>
    @endif
</div>
