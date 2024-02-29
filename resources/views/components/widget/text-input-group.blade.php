<div class="input-group @isset($width) {{ $width }} @endisset">
    <label class="input-group-addon padding-right-18px">
        {{ $title }}
        @isset($isrequired)
            <sup class="text-danger">*</sup>
        @endisset
    </label>
    <input type="{{ $type ?? 'text' }}" class="form-control {{ $class ?? null }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value ?? null) }}" placeholder="{{ $placeholder ?? 'Type '. $title }}" @if($readonly ?? 0) readonly="readonly" @endif autocomplete="off">
</div>

