@props([
    'action' => '',         // form action (defaults to current URL when blank)
    'method' => 'GET',
    'searchFormId' => 'searchForm',  // the form this filter applies to; default legacy id
    'submitOnChange' => true,
])

<form {{ $attributes->merge(['class' => 'mm-filter-bar', 'method' => strtoupper($method) === 'GET' ? 'GET' : $method, 'action' => $action]) }}
      @if($submitOnChange) data-mm-filter-bar @endif
      data-mm-filter-target="#{{ $searchFormId }}"
      role="search">
    <div class="mm-filter-bar-fields">
        {{ $slot }}
    </div>
    <div class="mm-filter-bar-actions">
        <button type="submit" class="mm-button">
            <span aria-hidden="true">🔍</span> Apply
        </button>
        @isset($reset)
            {{ $reset }}
        @endisset
    </div>
</form>
