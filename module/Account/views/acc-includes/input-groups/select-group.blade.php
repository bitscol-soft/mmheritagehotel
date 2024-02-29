@php
    use Illuminate\Support\Str;
    $colSm          = isset($colSm) ? $colSm : 12;
    $labelClass     = isset($label_class) ? $label_class: '';
    $chosenSize     = isset($chosen_size) ? $chosen_size : '100-percent';
    $selectName     = Str::snake(Str::singular($modelVariable)) . '_id';
    $isRequired     = isset($is_required);
    $selectTitle    = ucwords(implode(" ", preg_split('/(?=[A-Z])/', Str::singular($modelVariable))));
    $title          = isset($title) ? $title : '';
    $name           = isset($title) ? $title.'_id' : '';
    $value          = isset($title) ? request($name) : '';
@endphp

<div class="col-sm-{{ $colSm }}">
    <div class="input-group">

        <label class="{{ $title }} input-group-addon" for="{{ $title }}">
            {{ $title }}
            @if($isRequired) <sup class="text-danger"> *</sup> @endif
        </label>

        <select id="{{ $name }}"
                name="{{ $name }}"
                {{ $isRequired ? 'required' : '' }}
                class="chosen-select-{{ $chosenSize }}  {{ $isRequired ? 'required' : '' }}"
                data-placeholder="- Select {{ $title }} -">
            <option value=""></option>

            @foreach($modelVariable as $id => $item)
                <option value="{{ $id }}" {{ oldSelect($name, $id, $value) }}>{{ $item }}</option>
            @endforeach
        </select>

    </div>
</div>
