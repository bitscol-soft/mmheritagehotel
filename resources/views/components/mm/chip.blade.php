@props([
    'variant' => 'default',  // 'default' | 'brand' | 'success' | 'warning' | 'danger' | 'muted'
    'removable' => false,
])

<span {{ $attributes->merge(['class' => 'mm-chip mm-chip-' . $variant]) }}>
    <span class="mm-chip-label">{{ $slot }}</span>
    @if ($removable)
        <button type="button" class="mm-chip-remove" data-mm-chip-remove aria-label="Remove">&times;</button>
    @endif
</span>
