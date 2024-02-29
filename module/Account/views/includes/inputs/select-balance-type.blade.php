@php
$editId = isset($edit_id) ? $edit_id : null;
$labelClass = isset($label_class) ? $label_class: 'col-sm-3';
$divClass = isset($div_class) ? $div_class : 'col-sm-9';
$chosenSize = isset($chosen_size) ? $chosen_size : '100-percent';
$labelText = isset($labelText) ? $labelText : 'Balance';
@endphp

<div class="form-group row">
    <label class="control-label {{$labelClass}}" for="balance_type">
        <b>
            {{ $labelText }} Type
            <sup class="text-danger">*</sup>
        </b>
    </label>

    <div class="{{$divClass}}">
        <select id="balance_type" name="balance_type" class="chosen-select-{{$chosenSize}}" data-placeholder="- Select {{ $labelText }} Type -">
            <option value=""></option>
            <option {{$editId == 'Debit' ? 'selected' : ''}}>Debit</option>
            <option {{$editId == 'Credit' ? 'selected' : ''}}>Credit</option>
        </select>
    </div>
</div>
