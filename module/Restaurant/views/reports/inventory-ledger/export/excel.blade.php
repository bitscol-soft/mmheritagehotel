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
            <th rowspan="2" class="text-center">Date</th>
            <th colspan="2" class="text-center">Sale</th>
            <th colspan="2" class="text-center">Purchase</th>
        </tr>
        <tr style="background: #C9DAF8 !important; color:black !important">
            <th class="text-right">Bar</th>
            <th class="text-right">Restaurant</th>
            <th class="text-right">Bar</th>
            <th class="text-right">Restaurant</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($product_ledgers as $product)
            {{-- @dd($ledgers) --}}
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ optional($product->category)->name }}</td>
                <td class="text-center">{{ request('date') ?? date('Y-m-d') }}</td>

                <td class="text-right">
                    {{ number_format($product->total_bar_out, 2) }} {{ optional($product->pack_unit)->name }}
                </td>
                <td class="text-right">
                    {{ number_format($product->total_rst_out, 2) }} {{ optional($product->pack_unit)->name }}
                </td>
                <td class="text-right">
                    {{ number_format($product->total_bar_in, 2) }} {{ optional($product->pack_unit)->name }}
                </td>
                <td class="text-right">
                    {{ number_format($product->total_rst_in, 2) }} {{ optional($product->pack_unit)->name }}
                </td>

            </tr>

        @empty
            <x-no-table-record />
        @endforelse


    </tbody>
</table>
