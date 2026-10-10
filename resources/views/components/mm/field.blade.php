@props(['label', 'id', 'name', 'value' => '', 'type' => 'text', 'error' => null, 'help' => null])
<div>
    <label for="{{ $id }}" class="tw-block tw-mb-2 tw-text-sm tw-font-semibold tw-text-ink">{{ $label }}</label>
    <input id="{{ $id }}" type="{{ $type }}" name="{{ $name }}" value="{!! e($value, false) !!}"
        @if($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
        {{ $attributes->merge(['class' => 'mm-input']) }}>
    @if($help) <p class="tw-mt-2 tw-text-sm tw-text-muted">{{ $help }}</p> @endif
    @if($error)<p id="{{ $id }}-error" class="tw-mt-2 tw-text-sm">{{ $error }}</p>@endif
</div>
