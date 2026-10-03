@props([
    'label',
    'value',
    'hint' => null,
    'variant' => 'default',  // 'default' | 'success' | 'warning' | 'danger' | 'brand'
])

<div {{ $attributes->merge(['class' => 'mm-tile mm-tile-' . $variant]) }}>
    <div class="mm-tile-label">{{ $label }}</div>
    <div class="mm-tile-value">{{ $value }}</div>
    @if ($hint)
        <div class="mm-tile-hint">{{ $hint }}</div>
    @endif
</div>
