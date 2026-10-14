{{-- W3.5b: transaction list partial for today-activities. The first table (summary stats)
     in today-activities/index.blade.php is kept raw (it has a custom borderless layout with
     2-column label/value pairs that doesn't fit the data-table component). This partial
     covers the second table (the actual transaction list). --}}
@php
    $total_amount = $total_paid_amount = $total_due_amount = 0;
@endphp

<table class="table table-bordered table-striped table-hover" style="border: none">
    <thead>
        <tr>
            <th class="text-center">SL</th>
            <th class="text-center">Type</th>
            <th class="text-center">Invoice No</th>
            <th class="text-center">Payment Type</th>
            <th class="text-center">Room No</th>
            <th class="text-right">Total Amount<span class="currency-sign"></span></th>
            <th class="text-right">Paid Amount<span class="currency-sign"></span></th>
            <th class="text-right" style="width: 14% !important">Due Amount<span class="currency-sign"></span></th>
        </tr>
    </thead>

    <tbody>
        @foreach ($transactions as $key => $transaction)
            @php
                $total_amount = optional($transaction->transaction)->total_amount;
                $total_collection += $paid_amount = optional($transaction->transaction)->collection;
                $total_due_amount += $due_amount = $total_amount - $paid_amount;
            @endphp

            <tr>
                <td class="text-center">
                    {{ $loop->iteration }}
                    <input type="hidden" name="transaction_ids[]" value="{{ $transaction->id }}">
                </td>
                <td class="text-center">{{ $transaction->source_type }}</td>
                <td class="text-center">INV-{{ optional($transaction->transaction)->invoice_no }}</td>
                <td class="text-center">{{ optional($transaction->account)->name }}</td>
                <td class="text-center">
                    @php
                        $details = optional(optional($transaction->transaction)->source)->details;
                    @endphp
                    @foreach ($details ?? [] as $key => $room)
                        <label class="label label-success">{{ optional($room->roomNumber)->room_number }}</label>
                    @endforeach
                </td>
                <td style="text-align: right;">
                    <span class="item-total">{{ calculateCurrencyAmount($total_amount) }}</span>
                </td>
                <td style="text-align: right;">{{ calculateCurrencyAmount($paid_amount) }}</td>
                <td class="text-right">{{ calculateCurrencyAmount($due_amount) }}</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr style="border-bottom:none !important">
            <th colspan="7" class="text-right border-none">Total Collection <span class="currency-sign"></span></th>
            <th class="text-right border-none" style="padding: 3px 7px 0px 0px !important;">
                <input type="text" readonly class="footer-input"
                    style="background: white !important; padding: 0 !important"
                    name="collection" value="{{ calculateCurrencyAmount($total_collection, 1) }}">
            </th>
        </tr>
        <tr style="border-bottom:none !important">
            <th colspan="7" class="text-right border-none" style="padding: 0 7px 0 0 !important;">Total Due <span class="currency-sign"></span></th>
            <th class="text-right border-none" style="padding: 0 7px 0 0 !important;">
                <input type="text" readonly class="footer-input"
                    style="background: white !important" name="due_amount"
                    value="{{ calculateCurrencyAmount($total_due_amount, 1) }}">
            </th>
        </tr>
    </tfoot>
</table>
