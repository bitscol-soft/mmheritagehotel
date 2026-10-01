@php
    // Intermediate crumbs are plain text: a URL prefix such as /hotel is not always a page.
    $mmSegments = array_values(array_filter(request()->segments(), 'strlen'));
    $mmHumanize = function ($segment) {
        return is_numeric($segment) ? '#' . $segment : \Illuminate\Support\Str::title(str_replace(['-', '_'], ' ', $segment));
    };
    $mmLast = count($mmSegments) ? end($mmSegments) : 'home';
    $mmTitle = trim(\Illuminate\Support\Facades\View::yieldContent('title')) ?: ($mmLast === 'home' ? 'Dashboard' : $mmHumanize($mmLast));
    $mmCrumbs = [];
    if (count($mmSegments) > 1 || (count($mmSegments) === 1 && $mmSegments[0] !== 'home')) {
        foreach (array_slice($mmSegments, 0, -1) as $segment) {
            $mmCrumbs[] = $mmHumanize($segment);
        }
    }
@endphp
<div class="mm-ui mm-shell-toolbar">
    <nav class="mm-crumbs" aria-label="Breadcrumb">
        <ol>
            <li><a href="{{ url('home') }}"><i class="fa fa-home" aria-hidden="true"></i><span>Home</span></a></li>
            @foreach ($mmCrumbs as $crumb)
                <li><span>{{ $crumb }}</span></li>
            @endforeach
            <li><span aria-current="page">{{ $mmTitle }}</span></li>
        </ol>
    </nav>
    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-3">
        <time class="tw-text-sm tw-text-muted" datetime="{{ now()->toDateString() }}">{{ now()->format('D, d M Y') }}</time>
        <button type="button" id="mm-density-toggle" class="mm-button mm-button-secondary" aria-pressed="false">Compact spacing</button>
    </div>
</div>
