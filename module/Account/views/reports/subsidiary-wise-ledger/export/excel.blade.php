<table class="table table-bordered table-striped" style="margin-bottom: 0">

    <tbody>

        @php

            $balance_type = $accountTransactions->first()->balance_type;
        @endphp


        @foreach ($accountTransactions as $account)

            <tr>
                <th class="text-center" style="border-top: none; width: 3%">
                    {{ $loop->iteration }}
                </th>

                <th class="text-left pl-2" style="border-top: none">
                    {{ $account->name }}
                </th>

                @php

                    if ($balance_type == 'Debit') {
                        $total = ($account->transaction_items->sum('credit_amount') - $account->transaction_items->sum('debit_amount'));
                    } else {
                        $total = ($account->transaction_items->sum('debit_amount') - $account->transaction_items->sum('credit_amount'));
                    }
                @endphp

                <th style="border-top: none" class="text-right pr-2">
                    <strong style="font-size: 15px">{{ number_format($total) }}</strong>
                </th>
            </tr>

            @if ($account->transaction_items->count())
                <tr>
                    <td>
                    </td>

                    <td colspan="2">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th class="text-right pr-1">Dr.</th>
                                    <th class="text-right pr-1">Cr.</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($account->transaction_items as $transaction)
                                    <tr>
                                        <td>{{ $transaction->date }}</td>
                                        <td>{{ $transaction->getDescription() }}</td>
                                        <td class="text-right pr-1">{{ number_format($transaction->credit_amount, 2) }}</td>
                                        <td class="text-right pr-1">{{ number_format($transaction->debit_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="3">
                    </td>
                </tr>
            @endif
        @endforeach
    </tbody>
    
</table>
