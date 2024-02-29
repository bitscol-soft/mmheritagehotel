@php
    use Illuminate\Support\Str;
    $editId = isset($edit_id) ? $edit_id : null;
    $labelClass = isset($label_class) ? $label_class: 'col-sm-3';
    $divClass = isset($divClass) ? $divClass : 'col-sm-9';
    $chosenSize = isset($chosen_size) ? $chosen_size : '100-percent';
    $selectName = Str::snake(Str::singular($modelVariable)) . '_id';
    $isRequired = isset($is_required);

    $selectTitle =   ucwords(implode(" ", preg_split('/(?=[A-Z])/', Str::singular($modelVariable))));
@endphp

<div class="form-group row">
    <label class="control-label {{$labelClass}}" for="{{$selectName}}">
        <b>
            {{ $selectTitle }}
            @if($isRequired)
                <sup class="text-danger"> *</sup>
            @endif
        </b>
    </label>

    <div class="col-sm-9 {{$divClass}}">

        <select id="{{$selectName}}" name="{{$selectName}}" {{ $isRequired ? 'required' : '' }}
                class="chosen-select-{{$chosenSize}}  {{ $isRequired ? 'required' : '' }}"
                data-placeholder="- Select {{$selectTitle}} -">
            <option value=""></option>

            @foreach($$modelVariable as $item)
                <option value="{{$item->id}}" {{ oldSelect('account_group_id', $item->id, $editId)}}>{{$item->name }}</option>
            @endforeach
        </select>
    </div>
</div>
