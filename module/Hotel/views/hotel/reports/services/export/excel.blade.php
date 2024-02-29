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
            <th>Created User</th>
            <th>Service Amount<span class="currency-sign"></span></th>
        </tr>
    </thead>
    <tbody>

        @forelse ($services as $item)

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
                    {{ optional($item->created_user)->name }}
                </td>
                <td>
                    {{ calculateCurrencyAmount($item->service_charge) }}
                </td>

            </tr>
        @empty
            @if (request('export_type') != 'excel') <x-no-table-record /> @endif
        @endforelse
    </tbody>
</table>
