@props([
    'name',
    'options' => [],   // ['value' => 'Label'] or [['value' => 'v', 'label' => 'L']]
    'selected' => null,
    'placeholder' => 'Select…',
    'multiple' => false,
    'required' => false,
    'id' => null,
])

@php
    $id = $id ?: 'mm-select-' . md5($name);
    // Normalise options to [['value' => ..., 'label' => ..., 'disabled' => bool], ...]
    $normalised = [];
    foreach ($options as $key => $opt) {
        if (is_array($opt)) {
            $normalised[] = [
                'value' => $opt['value'] ?? $key,
                'label' => $opt['label'] ?? ($opt['value'] ?? $key),
                'disabled' => $opt['disabled'] ?? false,
            ];
        } else {
            $normalised[] = [
                'value' => $key,
                'label' => (string) $opt,
                'disabled' => false,
            ];
        }
    }
    // selected may be scalar or array
    $selectedArr = is_array($selected) ? $selected : ($selected === null ? [] : [$selected]);
    $selectedSet = array_flip(array_map('strval', $selectedArr));
@endphp

<select id="{{ $id }}"
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        class="mm-input mm-select chosen-select"
        @if($multiple) multiple @endif
        @if($required) required @endif
        data-mm-select>
    @if ($placeholder !== null && !$multiple)
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($normalised as $opt)
        <option value="{{ $opt['value'] }}"
                @if(isset($selectedSet[(string) $opt['value']])) selected @endif
                @if($opt['disabled']) disabled @endif>
            {{ $opt['label'] }}
        </option>
    @endforeach
</select>
