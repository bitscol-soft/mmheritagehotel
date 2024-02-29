
@php
    $isRequired = isset($is_required);
    $colSm = isset($colSm) ? $colSm : 9;
    $title = isset($title) ? $title : ucwords($name);
    $value = isset($value) ? $value : '';
@endphp
<div class="form-group row">
    <label class="col-sm-{{ 12 - $colSm }} control-label" for="{{ $name }}">
        <b>{{ $title }}
            @if($isRequired)
                <sup class="text-danger">*</sup>
            @endif
        </b>
    </label>

    <div class="col-sm-{{ $colSm }}">
        <input id="{{ $name }}" name="{{ $name }}" {{ $isRequired ? 'required' : '' }} type="text"
           class="form-control input-sm date-picker" placeholder="yyyy-mm-dd" value="{{ old($name, ($value ?? date('Y-m-d'))) }}"
            autocomplete="off">
    </div>
</div>
