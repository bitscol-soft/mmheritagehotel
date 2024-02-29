@php
    $colSm          = isset($colSm) ? $colSm : 12;
    $value          = isset($value) ? $value : '';
    $labelClass     = isset($label_class) ? $label_class: '';
    $divClass       = isset($div_class) ? $div_class : 'col-sm-9';
    $chosenSize     = isset($chosen_size) ? $chosen_size : '100-percent';
    $labelText      = isset($labelText) ? $labelText : 'Balance';
    $isRequired     = isset($is_required) ?? '';
@endphp

<div class="col-sm-{{ $colSm }}">
    <div class="input-group">

        <label class="{{ $labelClass }} input-group-addon" for="{{ $labelClass }}">
            {{ $labelText }} Type
            @if($isRequired) <sup class="text-danger"> *</sup> @endif
        </label>

        <select id="balance_type" name="balance_type" class="chosen-select-{{$chosenSize}}" data-placeholder="- Select Type -">
            <option value=""></option>
            <option value="Debit" {{ $value == 'Debit' ? 'selected' : '' }}>Debit</option>
            <option value="Credit" {{ $value == 'Credit' ? 'selected' : '' }}>Credit</option>
        </select>

    </div>
</div>
