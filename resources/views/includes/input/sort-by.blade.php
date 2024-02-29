@php
$datas = ['created_at', 'created_at_desc', 'employee_full_id', 'employee_full_id_desc', 'department_id', 'department_id_desc', 'designation_id', 'designation_id_desc'];
@endphp
<select class="chosen-select-100-percent form-control" name="sort_by_key" data-placeholder="--Sort By--">
    <option value=""></option>
    @foreach ($datas as $item)
        <option value="{{ $item }}" {{ request()->sort_by_key == $item ? 'selected' : '' }}>
            {{ str_replace('_', ' ', ucwords(str_replace('desc', '', $item))) }}({{ strpos($item, 'desc') !== false ? 'desc' : 'asc' }})
        </option>
    @endforeach
</select>
