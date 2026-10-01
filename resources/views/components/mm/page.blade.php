@props(['title', 'description' => null])
<section {{ $attributes->merge(['class' => 'mm-ui']) }}>
    <div class="tw-space-y-4 tw-py-2">
        <header class="mm-page-head tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-x-4 tw-gap-y-2">
            <div class="mm-page-title">
                <h1 class="tw-m-0 tw-font-semibold tw-text-ink">{{ $title }}</h1>
                @if ($description)<p class="tw-m-0 tw-text-sm tw-text-muted">{{ $description }}</p>@endif
            </div>
            @isset($actions)<div class="tw-flex tw-flex-wrap tw-gap-2 mm-no-print">{{ $actions }}</div>@endisset
        </header>
        {{ $slot }}
    </div>
</section>
