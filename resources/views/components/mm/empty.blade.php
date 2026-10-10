@props([
    'text' => 'No records yet',
    'action' => null,  // ['label' => '...', 'href' => '...']
])

<div {{ $attributes->merge(['class' => 'mm-empty']) }}>
    <p class="mm-empty-text">{{ $text }}</p>
    @if ($action)
        <a href="{{ $action['href'] }}" class="mm-button mm-button-secondary mm-empty-action">
            {{ $action['label'] }}
        </a>
    @endif
    {{ $slot }}
</div>
