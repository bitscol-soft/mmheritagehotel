<select name="hotel_table_id" id="hotel_table_id" class="form-control select2 input-search"
    data-selected="{{ $value ?? null }}" style="width: 100%" data-placeholder="--Select Table--">
    {{-- <option value=""></option> --}}
    @foreach ($tables as $item)
        <option value="{{ $item->id }}" {{ isset($value) ? ($value == $item->id ? 'selected' : '') : null }}>
            {{ $item->name }} -> {{ $item->table_no }}</option>
    @endforeach
</select>
