@props(['label' => 'Records'])
<div {{ $attributes->merge(['class' => 'mm-table-scroll']) }} role="region" aria-label="{{ $label }}" tabindex="0">{{ $slot }}</div>
