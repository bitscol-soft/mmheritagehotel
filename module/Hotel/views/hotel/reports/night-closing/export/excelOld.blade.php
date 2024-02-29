<table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="7">Night Closing Report {{ date('Y-m-d') }}</th>
            </tr>
        @endif
        <tr>
            <th>SL</th>
            <th class="text-center">Date</th>
            <th class="text-right">Total Check In</th>
            <th class="text-right">Total Check Out</th>
            <th class="text-right">Total Reservation</th>
            <th class="text-right">Total Booked</th>
            <th class="text-right">Total Cancelled</th>
            <th class="text-right">Total Dirty</th>
            {{-- <th class="text-right">Total Maintanence</th> --}}
            <th class="text-right">Discount</th>
            <th class="text-right">Total Amount <span class="currency-sign"></span></th>
            <th class="text-right">Due Amount <span class="currency-sign"></span></th>
        </tr>
    </thead>
    <tbody>
        @php
            $grand_total_check_in       = 0;
            $grand_total_check_out      = 0;
            $grand_total_reservation    = 0;
            $grand_total_booked         = 0;
            $grand_total_cancelled      = 0;
            $grand_total_dirty          = 0;
            $grand_total_maintanence    = 0;
            $grand_total_amount         = 0;
            $grand_total_due_amount     = 0;
            $grand_total_discount       = 0;
        @endphp
      <tbody>
        @forelse ($nightaudits as $key => $nightAuditSummary)
            @php
                $grand_total_check_in       += $nightAuditSummary->total_check_in;
                $grand_total_check_out      += $nightAuditSummary->total_check_out;
                $grand_total_reservation    += $nightAuditSummary->total_reservation;
                $grand_total_booked         += $nightAuditSummary->total_booked;
                $grand_total_cancelled      += $nightAuditSummary->total_cancelled;
                $grand_total_dirty          += $nightAuditSummary->total_dirty;
                $grand_total_maintanence    += $nightAuditSummary->total_maintanence;

            @endphp
            @forelse ($nightAuditSummary->auditTransactions as $auditTransaction)
                @forelse ($auditTransaction->transactions as $data)
                    @php
                        $grand_total_discount   += $data->discount;
                        $grand_total_amount     += $data->collection;
                        $grand_total_due_amount += $data->due_amount;
                    @endphp
                    <tr>
                        <td>{{ $paginate ? ($nightaudits->firstItem() + $key) : $loop->iteration }}</td>
                        <td>{{ $nightAuditSummary->date }}</td>
                        <td class="text-right">{{ number_format($nightAuditSummary->total_check_in) }}</td>
                        <td class="text-right">{{ number_format($nightAuditSummary->total_check_out) }}</td>
                        <td class="text-right">{{ number_format($nightAuditSummary->total_reservation) }}</td>
                        <td class="text-right">{{ number_format($nightAuditSummary->total_booked) }}</td>
                        <td class="text-right">{{ number_format($nightAuditSummary->total_cancelled) }}</td>
                        <td class="text-right">{{ number_format($nightAuditSummary->total_dirty) }}</td>
                        {{-- <td class="text-right">{{ number_format($nightAuditSummary->total_maintanence, 3) }}</td> --}}
                        <td class="text-right">{{ calculateCurrencyAmount($data->discount) }}</td>
                        <td class="text-right">{{ calculateCurrencyAmount($data->collection) }}</td>
                        <td class="text-right">{{ calculateCurrencyAmount($data->due_amount) }}</td>
                    </tr>
                @empty
                    {{-- Handle the case when there are no transactions --}}
                    <tr>
                        <td colspan="11">No transactions for this auditTransaction.</td>
                    </tr>
                @endforelse
            @empty
                {{-- Handle the case when there are no auditTransactions --}}
                <tr>
                    <td colspan="11">No auditTransactions for this NightAuditSummary.</td>
                </tr>
            @endforelse
        @empty
            {{-- Handle the case when there are no NightAuditSummaries --}}
            @if (request('export_type') != 'excel') <x-no-table-record /> @endif
        @endforelse
    </tbody>

    @if (request('export_type') != 'excel')
        <tfoot>
            <tr>
                <td colspan="2" class="text-right">
                    <strong style="font-size: 18px">Total: </strong>
                </td>
                <td class="text-right">
                    <strong style="font-size: 18px">{{ number_format($grand_total_check_in) }}</strong>
                </td>
                <td class="text-right">
                    <strong style="font-size: 18px">{{ number_format($grand_total_check_out) }}</strong>
                </td>
                <td class="text-right">
                    <strong style="font-size: 18px">{{ number_format($grand_total_reservation) }}</strong>
                </td>
                <td class="text-right">
                    <strong style="font-size: 18px">{{ number_format($grand_total_booked) }}</strong>
                </td>
                <td class="text-right">
                    <strong style="font-size: 18px">{{ number_format($grand_total_cancelled) }}</strong>
                </td>
                <td class="text-right">
                    <strong style="font-size: 18px">{{ number_format($grand_total_dirty) }}</strong>
                </td>
                <td class="text-right">
                    <strong style="font-size: 18px">{{ number_format($grand_total_discount, 2) }}</strong>
                    {{-- <strong style="font-size: 18px">{{ number_format($grand_total_maintanence, 2) }}</strong> --}}
                </td>
                <td class="text-right">
                    <strong style="font-size: 18px">{{ calculateCurrencyAmount($grand_total_amount) }}</strong>
                </td>
                <td class="text-right">
                    <strong style="font-size: 18px">{{ calculateCurrencyAmount($grand_total_due_amount) }}</strong>
                </td>
            </tr>
        </tfoot>
    @endif
</table>
