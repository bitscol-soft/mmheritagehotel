<table id="data-table" class="table table-striped table-bordered table-hover">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="10">Night Audit Report - {{ date('Y-m-d') }}</th>
            </tr>
        @endif
        <tr>
            <th class="text-center">SL</th>
            <th class="text-center" style="width: 10%">Date</th>
            <th class="text-center">Total Sell</th>
            <th class="text-right">Total Collection<span class="currency-sign"></span></th>
            <th class="text-right">Due Amount<span class="currency-sign"></span></th>
            @if (!request('export_type'))
                <th class="text-center" style="width: 10%">Action</th>
            @endif
        </tr>
    </thead>



    <tbody>
        @php
            // @dd($nightaudits);
            $discount = 0;
            $sell = 0;
        @endphp
        @forelse ($nightaudits->groupBy('date') as $key => $audit)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ $audit->first()->date }}</td>

                <td class="text-center">{{ $sell += $nightaudits[0]->details->count() }}</td>

                <td class="text-right" style="font-size: 16px">{{ calculateCurrencyAmount($audit->sum('collection')) }}
                </td>
                <td class="text-right" style="font-size: 16px;color:rgb(105, 11, 11)">
                    {{ calculateCurrencyAmount($audit->sum('due_amount')) }}</td>

                @if (!request('export_type'))
                    <td class="text-center">
                        <div class="btn-group btn-corner">
                            <a href="{{ route('rst.night-audits.show', $audit->first()->date) }}" target="_blank"
                                class="btn btn-sm btn-primary" title="View Details">
                                <i class="fa fa-eye"></i>
                            </a>

                            <button type="button"
                                onclick="delete_item(`{{ route('night-audits.destroy', $audit->first()->date) }}`)"
                                class="btn btn-sm btn-danger" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                @endif
            </tr>
        @empty
            <x-no-table-record />
        @endforelse
    </tbody>

    @if (count($nightaudits) > 0)
        <tfoot>
            <tr>
                <th colspan="2">
                    <strong>Total</strong>
                </th>
                <th class="text-center">{{ number_format($sell) }}</th>

                <th class="text-right" style="font-size:18px">
                    {{ calculateCurrencyAmount($nightaudits->sum('collection')) }}</th>
                <th class="text-right" style="font-size:18px">
                    {{ calculateCurrencyAmount($nightaudits->sum('due_amount')) }}</th>
                @if (!request('export_type'))
                    <th></th>
                @endif
            </tr>
        </tfoot>
    @endif
</table>
