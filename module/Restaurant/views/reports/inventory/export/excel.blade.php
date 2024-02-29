<table id="datatable" class="table table-striped table-bordered table-hover mb-2">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="8" style="text-align: center">Inventory Report</th>
            </tr>
        @endif
        <tr style="background: #C9DAF8 !important; color:black !important">
            <th rowspan="2">SL</th>
            <th rowspan="2">Product Name</th>
            <th rowspan="2">Category</th>
            <th rowspan="2" class="text-right">Opening Qty</th>
            <th rowspan="2" class="text-right">Purchase Qty</th>
            <th class="text-center" colspan="2">Sold Qty</th>
            <th rowspan="2" class="text-right">Return Qty</th>
            <th rowspan="2" class="text-right">Available Qty</th>
        </tr>
        <tr style="background: #C9DAF8 !important; color:black !important">
            <th class="text-center">Bar</th>
            <th class="text-center">Restaurant</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($products as $key => $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ optional($product->category)->name }}</td>
                <td class="text-right">{{ $product->opening_quantity }}</td>
                <td class="text-right">
                    {{ $product->purchased_quantity }}
                </td>
                <td class="text-right">
                    {{ number_format($product->bar_sold_qty, 2) }} {{ optional($product->pack_unit)->name }}
                </td>
                <td class="text-right">
                    {{ number_format($product->rst_sold_qty, 2) }} {{ optional($product->pack_unit)->name }}
                </td>
                <td class="text-right">{{ $product->return_quantity }}</td>
                <td class="text-right">
                    {{ number_format($product->available_quantity, 2) < 0 ? 0 : number_format($product->available_quantity, 2) }}
                    {{ optional($product->pack_unit)->name }}
                </td>
            </tr>

        @empty
            <x-no-table-record />
        @endforelse

    </tbody>
</table>
