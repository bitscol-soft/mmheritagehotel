@forelse ($sales as $item)
    <tr class="pointer" onclick="saleData(`{{ $item->id }}`)" style="padding: 5px">
        <td>{{ $loop->iteration }}</td>
        <td>
            {{ $item->guest_name ?? optional($item->guest)->name }}
        </td>
        <td>{{ optional($item->table)->table_no }}</td>
        <td>{{ $item->invoice_no }}</td>
        <td class="itemAmount">
            @if (setting('use_vat_included') == 1)
                {{ (int) $item->subtotal }}
            @else
                {{ (int) $item->payable_amount }}
            @endif
        </td>
    </tr>
@empty
    <x-no-table-record />
@endforelse
