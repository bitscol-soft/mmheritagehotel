<table id="datatable" class="table table-striped table-bordered" width="100%">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="5">Service Report - {{ date('Y-m-d') }}</th>
            </tr>
        @endif
        <tr>
            <th width="3%">SL</th>
            <th>Year</th>
            <th>Month</th>
            <th>Source</th>
            <th>Total Amount</th>
            <th>Vat Amount<span class="currency-sign"></span></th>
        </tr>
    </thead>
    <tbody>
        @php
            $total            = 0;
            $total_vat        = 0;
            $total_collection = 0;
        @endphp
        @forelse ($monthly_vats as $item)
            @php
                 $total             += $item->count;
                 $total_vat         += $item->vat_amount;
                 $total_collection  += $item->total_amount;
            @endphp
            <tr class="odd gradeX">
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ fdate($item->date, 'Y') }}
                </td>
                <td>
                    {{ $item->month }} ({{ $item->count }})
                </td>
                <td>
                    {{ $item->source_type }}
                </td>
                <td>
                    {{ calculateCurrencyAmount($item->total_amount) }}
                </td>
                <td>
                    {{ calculateCurrencyAmount($item->vat_amount) }}
                </td>

            </tr>
        @empty
            @if (request('export_type') != 'excel') <x-no-table-record /> @endif
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-right">Total ({{ $total }}) :</th>
            <th class="text-left">
                {{ calculateCurrencyAmount($total_collection) }}
            </th>
            <th class="text-left">
                {{ calculateCurrencyAmount($total_vat) }}
            </th>
        </tr>
    </tfoot>
</table>
