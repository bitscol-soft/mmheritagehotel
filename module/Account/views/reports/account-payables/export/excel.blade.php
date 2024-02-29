<table class="table table-bordered table-striped" style="margin-bottom: 0">
    <thead>
        <tr class="table-header-bg">
            <th class="text-center">Sl</th>
            <th>Account Name</th>
            <th class="text-right pr-1">Balance</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($transactions as $account)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $account->name }}</td>
                <td class="text-right pr-1">
                    @if($account->balance <> 0)
                        <a target="_blank" href="{{ route('report.account-ledger') }}?company_id={{ request('company_id') }}&account_id={{ $account->id }}&from=2010-01-01">
                            {{ number_format($account->balance, 2) }}
                        </a>
                    @else 
                        0
                    @endif 
                </td>
            </tr>
        @endforeach
    </tbody>

    @if(count($transactions) > 0)
        <tfoot>
            <tr style="font-size: 18px">
                <th class="text-right" colspan="2">
                    <strong>Total=</strong>
                </th>
                <th class="text-right pr-1">
                    <strong>{{ number_format($transactions->sum('balance'), 2) }}</strong>
                </th>
            </tr>
        </tfoot>
    @endif 
</table>