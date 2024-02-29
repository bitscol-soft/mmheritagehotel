<table class="table">


    <!-- table header -->
    <thead>
        <tr style="color: black !important; font-weight: bolder; font-size: 15px">
            <th>SL</th>
            <th>Account</th>
            <th>Description</th>
            <th>Dr.</th>
            <th>Cr.</th>
        </tr>
    </thead>


    <!-- body -->
    <tbody>

        @php
            $totalDebit = 0;
            $totalCredit = 0;
            $sl = 1;
        @endphp


        @forelse ($transactions->groupBy('invoice_no') as $items)

            @foreach ($items as $item)
                @php
                    $totalDebit += $item->debit_amount;

                    $totalCredit += $item->credit_amount;
                @endphp

                <tr>
                    <td>
                        @if ($loop->first)
                            {{ $sl }}
                        @endif
                    </td>
                    <td>{{ optional($item->account)->name }}</td>
                    <td>{{ $item->getDescription() }}</td>
                    <td>
                        <strong>{{ number_format($item->debit_amount ?? 0, 2) }}</strong>
                    </td>
                    <td>
                        <strong>{{ number_format($item->credit_amount ?? 0, 2) }}</strong>
                    </td>
                </tr>
            @endforeach

            @php
                $sl++;
            @endphp

        @empty

        @endforelse
    </tbody>




    <!-- table footer -->
    @if (count($transactions) > 0)
        <tfoot>
            <tr>
                <th></th>
                <th class="text"></th>
                <th class="text-right h4"><strong style="font-size: 16px">Total</strong></th>
                <th class="text-right h4"><strong
                        style="font-size: 16px">{{ number_format($totalDebit, 2) }}</strong></th>
                <th class="text-right h4"><strong
                        style="font-size: 16px">{{ number_format($totalCredit, 2) }}</strong>
                </th>
            </tr>
        </tfoot>
    @endif
</table>
