<table class="table table-bordered table-striped" style="margin-bottom: 0; width: 100%">
    <thead>
        <tr class="table-header-bg">
            <th class="text-center">Sl</th>
            <th class="text-center">Date</th>
            <th class="text-center">Voucher No</th>
            <th class="pl-3">Description</th>
            <th class="text-right pr-1">Dr.</th>
            <th class="text-right pr-1">Cr.</th>
            <th class="text-right pr-1">Balance</th>
        </tr>
    </thead>

    <tbody>
    @if(request('account_id'))
        <tr>
            <td class="text-left pl-3" colspan="6">Opening Balance</td>
            <td class="text-right pr-1">{{ $balance }}</td>
        </tr>
    @else
        <tr>
            <td colspan="7" style="font-size: 16px" class="text-center text-danger">NO RECORDS
                FOUND!
            </td>
        </tr>
    @endif

    @php
        $totalDebit = 0;
        $totalCredit = 0;
        $totalBalance = 0;
    @endphp

    @foreach($transactions as $transaction)
        @php
                            
            $totalBalance += $balance = ($transaction->debit_amount - $transaction->credit_amount);
                
            $totalDebit += $transaction->debit_amount;
            $totalCredit += $transaction->credit_amount;
        @endphp

        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center">{{ $transaction->date }}</td>
            <td class="text-center">{{ $transaction->invoice_no }}</td>
            <td class="pl-3"></td>
            <td class="text-right pr-1">{{ number_format($transaction->credit_amount, 2) }}</td>
            <td class="text-right pr-1">{{ number_format($transaction->debit_amount, 2) }}</td>
            <td class="text-right pr-1">{{ number_format($balance, 2) }}</td>
        </tr>
    @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="4">Total:</th>
            <th class="text-right pr-1">{{ number_format($totalDebit, 2) }}</th>
            <th class="text-right pr-1">{{ number_format($totalCredit, 2) }}</th>
            <th class="text-right pr-1">{{ number_format($totalBalance, 2) }}</th>
        </tr>
    </tfoot>
</table>