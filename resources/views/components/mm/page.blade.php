@props(['title', 'description' => null])
<section {{ $attributes->merge(['class' => 'mm-ui']) }}>
    <div class="tw-space-y-6 tw-py-4">
        <header class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-4">
            <div>
                <p class="tw-m-0 tw-mb-1 tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-muted">MM Heritage Hotel</p>
                <h1 class="tw-m-0 tw-text-2xl tw-font-semibold tw-text-ink">{{ $title }}</h1>
                @if ($description)<p class="tw-m-0 tw-mt-2 tw-text-sm tw-text-muted">{{ $description }}</p>@endif
            </div>
            @isset($actions)<div class="tw-flex tw-flex-wrap tw-gap-2 mm-no-print">{{ $actions }}</div>@endisset
        </header>
        {{ $slot }}
    </div>
</section>
