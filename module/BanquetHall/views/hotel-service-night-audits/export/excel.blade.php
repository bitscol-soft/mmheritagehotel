@if( $checkNUll && count($nightaudits[0]->details) > 0)
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

                <th class="text-center">Total Check In</th>
                <th class="text-center">Total Check Out</th>
                <th class="text-center">Total Reservation</th>
                <th class="text-center">Total Cancelled</th>

                <th class="text-right">Total Booked Room</th>
                <th class="text-right">Total Dirty Room</th>

                <th class="text-right">Total Amount<span class="currency-sign"></span></th>
                <th class="text-right">Due Amount<span class="currency-sign"></span></th>
                @if (!request('export_type'))
                    <th class="text-center">Action</th>
                @endif
            </tr>
        </thead>



        <tbody>
            @forelse ($nightaudits->groupBy('date') as $key => $audit)
                <tr>
                    {{-- <td class="text-center">{{ $paginate ? ($nightaudits->firstItem() + $key) : $loop->iteration }}</td> --}}
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $audit->first()->date }}</td>

                    <td class="text-center">{{ number_format($audit->sum('total_check_in')) }}</td>
                    <td class="text-center">{{ number_format($audit->sum('total_check_out')) }}</td>
                    <td class="text-center">{{ number_format($audit->sum('total_reservation')) }}</td>
                    <td class="text-center">{{ number_format($audit->sum('total_cancelled')) }}</td>

                    <td class="text-center">{{ number_format($audit->sum('total_room')) }}</td>
                    <td class="text-center">{{ number_format($audit->sum('total_dirty_room')) }}</td>

                    {{-- <td class="text-right">{{ calculateCurrencyAmount($audit->sum('collection')) }}</td>
                    <td class="text-right">{{ calculateCurrencyAmount($audit->sum('due_amount')) }}</td> --}}

                    <td class="text-right">{{ calculateCurrencyAmount($audit->first()->details->sum('total_collection')) }}</td>
                    <td class="text-right">{{ calculateCurrencyAmount($audit->first()->details->sum('total_due')) }}</td>

                    @if (!request('export_type'))
                        <td class="text-center">
                            <div class="btn-group btn-corner">

                                <a href="#" data-toggle="modal" data-target="#my_Modal{{ $audit->first()->id }}" class="btn btn-sm btn-info" title="View Details">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="{{ route('hotelservice.night-audits.show', $audit->first()->date) }}" target="_blank"
                                    class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fa fa-print"></i>
                                </a>

                                {{-- <button type="button" onclick="delete_item(`{{ route('night-audits.destroy', $audit->first()->date) }}`)" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fa fa-trash-o"></i>
                                </button> --}}
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <x-no-table-record />
            @endforelse
        </tbody>

        @if(count($nightaudits) > 0)
            <tfoot>
                <tr>
                    <th colspan="2">
                        <strong>Total</strong>
                    </th>
                    <th class="text-center">{{ number_format($nightaudits->sum('total_check_in')) }}</th>
                    <th class="text-center">{{ number_format($nightaudits->sum('total_check_out')) }}</th>
                    <th class="text-center">{{ number_format($nightaudits->sum('total_reservation')) }}</th>
                    <th class="text-center">{{ number_format($nightaudits->sum('total_cancelled')) }}</th>
                    <th class="text-center">{{ number_format($nightaudits->sum('total_room')) }}</th>
                    <th class="text-center">{{ number_format($nightaudits->sum('total_dirty_room')) }}</th>

                    {{-- <th class="text-right">{{ calculateCurrencyAmount($nightaudits->sum('collection')) }}</th>
                    <th class="text-right">{{ calculateCurrencyAmount($nightaudits->sum('due_amount')) }}</th> --}}

                    <th class="text-right">{{ calculateCurrencyAmount($nightaudits->first()->details->sum('total_collection')) }}</th>
                    <th class="text-right">{{ calculateCurrencyAmount($nightaudits->first()->details->sum('total_due')) }}</th>

                    @if(!request('export_type'))<th></th> @endif
                </tr>
            </tfoot>
        @endif
    </table>
@endif
