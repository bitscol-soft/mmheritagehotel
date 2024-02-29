@php
$editId = isset($edit_id) ? $edit_id : null;
$labelClass = isset($label_class) ? $label_class: 'col-sm-3';
$divClass = isset($div_class) ? $div_class : 'col-sm-9';
$chosenSize = isset($chosen_size) ? $chosen_size : '100-percent';
@endphp

<div class="form-group row">
    <label class="control-label {{$labelClass}}" for="status"> <b>Status <sup class="text-danger">
                *</sup></b> </label>

    <div class="{{$divClass}}">
        <select id="status" name="status" class="chosen-select-{{$chosenSize}}"
                data-placeholder="- Select Status -">
            <option value=""></option>
            <option value="1" {{$editId == '1' ? 'selected' : ''}}>Active</option>
            <option value="0" {{$editId == '0' ? 'selected' : ''}}>Inactive</option>
        </select>
    </div>
</div>
