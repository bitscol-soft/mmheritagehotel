@props([
    'columns' => [],   // each: ['label' => 'Guest', 'width' => '20%', 'priority' => 1, 'align' => 'right']
    'rows' => null,    // optional explicit rows collection; if null we use $slot (child <tr>s)
    'empty' => null,   // ['text' => '...', 'action' => ['label' => '...', 'href' => '...']]
    'sticky' => false,
    'label' => 'Records',
    'rowClass' => null,  // callable($row, $index) → string
])

@php
    $isEmpty = $rows !== null ? count($rows) === 0 : trim((string) $slot) === '';
@endphp

<div {{ $attributes->merge(['class' => 'mm-data-table-wrap']) }} data-mm-data-table>
    @if ($isEmpty && $empty)
        <x-mm.empty :text="$empty['text'] ?? 'No records'" :action="$empty['action'] ?? null" />
    @else
        <x-mm.table-scroll :label="$label">
            <table class="mm-data-table {{ $sticky ? 'mm-sticky-header' : '' }}">
                <thead>
                    <tr>
                        @foreach ($columns as $col)
                            <th scope="col"
                                @if(!empty($col['width'])) style="width: {{ $col['width'] }}" @endif
                                @if(!empty($col['priority'])) data-priority="{{ $col['priority'] }}" @endif
                                @if(!empty($col['align'])) class="mm-text-{{ $col['align'] }}" @endif>
                                {{ $col['label'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @if ($rows !== null)
                        @foreach ($rows as $i => $row)
                            <tr @if($rowClass) class="{{ $rowClass($row, $i) }}" @endif>
                                {{-- The caller is expected to render the <td>s by passing them via a Blade partial or computed string; for now, dump the row as JSON inside a single cell when the slot is empty. --}}
                                @if (trim((string) $slot) === '')
                                    <td colspan="{{ count($columns) }}">{{ is_array($row) || is_object($row) ? json_encode($row) : $row }}</td>
                                @else
                                    {!! $slot !!}
                                @endif
                            </tr>
                        @endforeach
                    @else
                        {{ $slot }}
                    @endif
                </tbody>
            </table>
        </x-mm.table-scroll>
    @endif
</div>
