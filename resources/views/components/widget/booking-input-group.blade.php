<div class="input-group width-100">
    <label class="border-none input-group-addon width-27">
        {{ $title }}
        @isset($isrequired)
            <sup class="text-danger">*</sup>
        @endisset
    </label>
    <input type="{{ $type ?? 'text' }}" class="form-control {{ $class ?? null }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value ?? null) }}" placeholder="{{ $placeholder ?? 'Type '. $title }}" @if($readonly ?? 0) readonly="readonly" @endif autocomplete="off">
    @isset($icon)
        <span class="input-group-addon"><i class="{{ $icon }} bigger-110"></i></span>
    @endisset
</div>
