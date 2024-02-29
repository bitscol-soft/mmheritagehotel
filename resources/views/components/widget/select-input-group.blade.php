<div class="input-group">
    <label class="input-group-addon">{{ $title }} @if ($isrequired ?? 0) <sup class="text-danger">*</sup>@endif</label>
    <select class="form-control chosen-select-100-percent" name="{{ $name }}" id="{{ $name }}" data-placeholder="{{ $placeholder ?? 'Choose One'}}" data-selected="{{ $selected ?? null }}">
        <option value=""></option>
        @foreach ($collections ?? [] as $key => $item)
            <option value="{{ $item->id }}">
                {{ $item->name }} @isset($secondvalue)
                    - {{ $item->$secondvalue }}
                @endisset
            </option>
        @endforeach
    </select>
</div>
