<table id="datatable" class="table table-striped table-bordered" width="100%">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="5">Service Report - {{ date('Y-m-d') }}</th>
            </tr>
        @endif
        <tr>
            <th width="3%">SL</th>
            <th>Date</th>
            <th>Invoice</th>
            <th>Source</th>
            <th>Total Amount</th>
            <th>Vat Amount<span class="currency-sign"></span></th>
        </tr>
    </thead>
    <tbody>


            @php
                $total_vat        = 0;
                $total_collection = 0;
            @endphp


        @forelse ($daily_vats as $item)
            @php
                $total_vat         += $item->vat_amount;
                $total_collection  += $item->total_amount;
            @endphp
            <tr class="odd gradeX">
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ fdate($item->date, 'Y-m-d') }}
                </td>
                <td>
                    {{ $item->invoice_no }}
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
            <th colspan="4" class="text-right">Total :</th>
            <th class="text-left">
                {{ calculateCurrencyAmount($total_collection) }}
            </th>
            <th class="text-left">
                {{ calculateCurrencyAmount($total_vat) }}
            </th>
        </tr>
    </tfoot>
</table>
