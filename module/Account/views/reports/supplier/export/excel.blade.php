<table class="table table-bordered table-striped" style="margin-bottom: 0">
    <thead>
        <tr class="table-header-bg">
            <th class="text-center">Sl</th>
            <th class="pl-3">Supplier</th>
            <th class="pl-3 text-right">Payment Amount</th>
            <th class="pl-3 text-right">Purchase Amount</th>
            <th class="pl-3 text-right">Balance</th>
        </tr>
    </thead>

    <tbody>

        @php
            $grand_total_amount = 0;
            $grand_total_paid = 0;
            $grand_total_purchase = 0;
            $grand_total_due_amount = 0;
        @endphp


        @foreach ($purchases as $key => $purchase)

            @php

                $balance = abs($balances->where('account_id', $purchase->first()->account_id)->sum('debit_amount') - $balances->where('account_id', $purchase->first()->account_id)->sum('credit_amount'));

                $grand_total_paid       += $total_paid          = $purchase->sum('debit_amount');
                $grand_total_purchase   += $total_purchase      = $purchase->sum('credit_amount');
                $grand_total_amount     += $total_amount        = $balance + abs($purchase->sum('debit_amount') - $purchase->sum('credit_amount'));
                $grand_total_due_amount += $total_due_amount    = abs($total_amount - $total_paid);


            @endphp
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="pl-3">{{ optional($purchase->first()->account)->name }}</td>
                <td class="text-right pr-1">{{ number_format($total_paid, 2) }}</td>
                <td class="text-right pr-1">{{ number_format($total_purchase, 2) }}</td>
                <td class="text-right pr-1">{{ number_format($total_amount, 2) }}</td>
            </tr>
        @endforeach

        <tr>
            <th class="text-center" colspan="2">Total</th>
            <th class="text-right pr-1">{{ number_format($grand_total_paid, 2) }}</th>
            <th class="text-right pr-1">{{ number_format($grand_total_purchase, 2) }}</th>
            <th class="text-right pr-1">{{ number_format($grand_total_amount, 2) }}</th>
        </tr>

    </tbody>

</table>
