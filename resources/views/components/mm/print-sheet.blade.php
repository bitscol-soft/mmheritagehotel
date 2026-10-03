@props([
    'sheet' => 'a4',  // 'a4' | 'thermal-80mm' | 'thermal-58mm'
    'title' => null,
])

<article {{ $attributes->merge(['class' => 'mm-print-sheet mm-print-sheet-' . $sheet]) }} data-sheet="{{ $sheet }}">
    @if ($title)
        <header class="mm-print-sheet-head">
            <h1 class="mm-print-sheet-title">{{ $title }}</h1>
        </header>
    @endif
    <div class="mm-print-sheet-body">
        {{ $slot }}
    </div>
    <footer class="mm-print-sheet-foot mm-no-print">
        <button type="button" class="mm-button mm-button-secondary" data-mm-print>
            <span aria-hidden="true">🖨</span> Print
        </button>
    </footer>
</article>
