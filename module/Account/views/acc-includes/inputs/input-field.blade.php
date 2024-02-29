
@php
    $isRequired = isset($is_required);
    $colSm = isset($colSm) ? $colSm : 3;
    $title = isset($title) ? $title : ucwords($name);
    $value = isset($value) ? $value : '';
    $type  = isset($type)  ? $type  : 'text';
@endphp

<div class="col-sm-{{ $colSm }}">
    <div class="input-group">

        <label class="{{ $name }} input-group-addon" for="{{ $name }}">
            {{ $title }}
            @if($isRequired) <sup class="text-danger"> *</sup> @endif
        </label>

        <div class="">
            <input id="{{ $name }}" name="{{ $name }}" {{ $isRequired ? 'required' : '' }} type="{{$type}}"

            @if(isset($is_number))
                onkeypress="return onlyNumber(event)"
            @endif
            
            class="form-control input-sm" placeholder="Type {{ ucwords($title) }}" value="{{ old($name, $value) }}">
        </div>

    </div>
</div>
