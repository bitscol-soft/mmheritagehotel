
@php
    $isRequired = isset($is_required);
    $colSm = isset($colSm) ? $colSm : 9;
    $title = isset($title) ? $title : ucwords($name);
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
           @if(isset($is_number))
            onkeypress="return onlyNumber(event)"
           @endif
           class="form-control input-sm" placeholder="Type {{ ucwords($title) }}" value="{{ old($name) }}">
    </div>
</div>
