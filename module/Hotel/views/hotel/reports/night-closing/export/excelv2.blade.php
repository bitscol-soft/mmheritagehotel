@if (@$checkNUll && count($nightaudits[0]->details) > 0)

    <table id="data-table" class="table table-striped table-bordered table-hover">
        <thead>
            @if (request('export_type') == 'excel')
                <tr>
                    <th colspan="10">Night Audit Report - {{ date('Y-m-d') }}</th>
                </tr>
            @endif
            <tr>
                <th class="text-center">SL</th>
                <th class="text-center">Date</th>

                <th class="text-right">Total Amount<span class="currency-sign"></span></th>
                <th class="text-right">Due Amount<span class="currency-sign"></span></th>
                @if (!request('export_type'))
                    <th class="text-center">Action</th>
                @endif
            </tr>
        </thead>



        <tbody>
            @php
                $total = 0;
                $total_due = 0;
            @endphp
            @forelse ($nightaudits->groupBy('date') as $key => $audit)
                @php
                    $total += $audit->first()->details->sum('total_collection') ?? 0;
                    $total_due += $audit->first()->details->sum('total_due') ?? 0;
                @endphp

                <tr>
                    {{-- <td class="text-center">{{ $paginate ? ($nightaudits->firstItem() + $key) : $loop->iteration }}</td> --}}
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $audit->first()->date }}</td>

                    <td class="text-right">
                        {{ calculateCurrencyAmount($audit->first()->details->sum('total_collection')) }}</td>
                    <td class="text-right">{{ calculateCurrencyAmount($audit->first()->details->sum('total_due')) }}
                    </td>


                    @if (!request('export_type'))
                        <td class="text-center">
                            <div class="btn-group btn-corner">

                                <a href="#audit-view-details{{ $audit->first()->id }}" data-toggle="modal"
                                    data-target="#audit-view-details{{ $audit->first()->id }}"
                                    class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="{{ route('bar.night-audits.show', $audit->first()->date) }}" target="_blank"
                                    class="btn btn-sm btn-primary" title="Invoice">
                                    <i class="fa fa-print"></i>
                                </a>

                            </div>
                        </td>
                    @endif
                </tr>

            @empty
                <x-no-table-record />
            @endforelse
        </tbody>


        <tfoot>
            <tr>
                <th colspan="2">
                    <strong>Total</strong>
                </th>

                <th class="text-right">{{ calculateCurrencyAmount($total, 2) }}</th>
                <th class="text-right">{{ calculateCurrencyAmount($total_due, 2) }}
                </th>

                @if (!request('export_type'))
                    <th></th>
                @endif
            </tr>
        </tfoot>

    </table>

@endif
