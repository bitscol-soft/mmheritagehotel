<table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="7">Cash Flow {{ date('Y-m-d') }}</th>
            </tr>
        @endif
        <tr>
            <th width="3%">SL</th>
            <th>Date</th>
            <th>Invoice</th>
            <th>Payment Way</th>
            <th>Source Type</th>
            <th>Created User</th>
            <th>Time</th>
            <th class="text-right">Balance<span class="currency-sign"></span></th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
    {{-- @dd($cashFlows) --}}
        @forelse ($cashFlows as $cashFlow)
            @if ($cashFlow->collection > 0)
            @php
                $total += $collection = $cashFlow->collection ?? 0;
            @endphp
            <tr class="odd gradeX">
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ fdate($cashFlow->date, 'Y-m-d') }}
                </td>
                <td>
                    {{ $cashFlow->invoice_no }}
                <td>
                    {{ optional($cashFlow->account)->name ?? 'N/A' }}
                </td>
                <td>
                    {{ $cashFlow->source_type }}
                </td>
                <td>
                    {{ optional($cashFlow->created_user)->name }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($cashFlow->datetime)->format('h:i  A') }}
                </td>
                <td class="text-right">
                    {{ calculateCurrencyAmount($collection) }}
                </td>

            </tr>
            @endif
        @empty
            @if (request('export_type') != 'excel') <x-no-table-record /> @endif
        @endforelse
    </tbody>
    @if (request('export_type') != 'excel')
    <tfoot>
        <tr>
            <th colspan="7" class="text-right">Total This Page:</th>
            <th class="text-right">
                {{ calculateCurrencyAmount($total) }}
            </th>
        </tr>
    </tfoot>
    @endif
</table>
