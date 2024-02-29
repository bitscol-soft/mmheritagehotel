<table class="table table-striped table-bordered" width="100%">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="8" style="text-align: center">Sale Report</th>
            </tr>
        @endif
        <tr>
            <th width="5%">Sl</th>
            <th>Date</th>
            <th>Invoice ID</th>
            <th>Description</th>
            <th>Created User</th>
            <th class="text-right">Balance</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp

        @forelse ($cashFlows as $cashFlow)
            @php
                $total += $collection = $cashFlow->collection ?? 0;
            @endphp
            <tr class="odd gradeX">
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ $cashFlow->date }}
                </td>
                <td>
                    {{ $cashFlow->invoice_no }}
                </td>
                <td>
                    {{ $cashFlow->source_type }}
                </td>
                <td>
                    {{ optional($cashFlow->user)->name }}
                </td>
                <td class="text-right">
                    {{ number_format($collection, 2) }}
                </td>

            </tr>
        @empty
            <x-no-table-record />
        @endforelse
    </tbody>

    @if (request('export_type') != 'excel')
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Total Collection</th>
                <th class="text-right">
                    {{ number_format($total, 2) }}
                </th>
            </tr>
        </tfoot>
    @endif
</table>
