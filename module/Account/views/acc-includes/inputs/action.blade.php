@php
    $colSm          = isset($colSm) ? $colSm : 12;
    $textPosition   = isset($textPosition)  ? $textPosition  : 'text-left';
    $marginTop      = isset($marginTop)  ? $marginTop  : '';
    $search         = isset($search)  ? $search : 0;
    $refresh        = isset($refresh)  ? $refresh : 0;
@endphp

<div class="col-sm-{{ $colSm }} {{ $marginTop }} {{ $textPosition }}">
    <div class="btn-group btn-corner">
        @if ($search == 1)
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa fa-search"></i> Search
            </button>
        @endif
        @if ($refresh == 1)
            <a href="{{ request()->url() }}" class="btn btn-sm btn-default">
                <i class="fa fa-refresh"></i> Refresh
            </a>
        @endif
    </div>
</div>


