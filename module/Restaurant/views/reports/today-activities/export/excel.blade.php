<table class="table table-striped table-bordered" width="100%">
    <thead>
        @if (request('export_type') == 'excel')
        <tr>
            <th colspan="8" style="text-align: center">Today Report</th>
        </tr>
        @endif
        <tr>
            <th width="3%" class="text-center">Sl</th>
            <th class="text-left">Date</th>
            <th class="text-left">Invoice</th>
            <th class="text-left">Type</th>
            <th class="text-right">Total Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_amount = $total_discount = $total_paid = $total_due = 0;
        @endphp
        @forelse ($transactions as $data)
        @php
            $total_amount += $amount = $data->collection;
            $total_discount += $discount = 0;
        @endphp
            <tr class="odd gradeX">
                <td class="text-center">
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ fdate($data->date,'Y-m-d') }}
                </td>
                <td>
                    @if ($data->invoice_no)
                        <a href="{{ route('rst.sales.show', $data->source_id) }}" target="_blank">{{ $data->invoice_no }}</a>
                    @endif
                </td>
                <td>
                    {{ $data->source_type }}
                </td>
                <td class="text-right">
                    {{ number_format($amount, 2) }}
                </td>
            </tr>
        @empty
            <x-no-table-record />
        @endforelse
        @if (request('export_type') != 'excel')
        <tfoot>
            <tr>
                <th colspan="4" class="text-right" style="font-size: 18px">Total:</th>
                <th class="text-right"><strong style="font-size:18px">{{ number_format($total_amount, 2) }}</strong></th>
            </tr>
        </tfoot>
        @endif
    </tbody>
</table>
