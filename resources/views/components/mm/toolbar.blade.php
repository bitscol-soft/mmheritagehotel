@props([
    'label' => null,  // aria-label for the action row
])

<div {{ $attributes->merge(['class' => 'mm-toolbar']) }} role="toolbar" @if($label) aria-label="{{ $label }}" @endif>
    @isset($leading)
        <div class="mm-toolbar-leading">{{ $leading }}</div>
    @endisset
    <div class="mm-toolbar-actions">
        {{ $slot }}
    </div>
    @isset($trailing)
        <div class="mm-toolbar-trailing">{{ $trailing }}</div>
    @endisset
</div>
