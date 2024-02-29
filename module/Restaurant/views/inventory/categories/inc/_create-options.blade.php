@foreach ($childCategory->childCategories ?? [] as $childCategory)
    <option value="{{ $childCategory->id }}"
        {{ old('category_id') == $childCategory->id ? 'selected' : '' }}>
        @for($s = 0; $s < $space; $s++)
            &nbsp;
        @endfor
        &raquo;&nbsp;{{ $childCategory->name }}
    </option>

    @include('inventory.categories.inc._create-options', ['childCategory' => $childCategory, 'space' => $space + 1])
@endforeach
