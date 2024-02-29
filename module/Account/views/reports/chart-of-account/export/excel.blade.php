<table class="table table-bordered table-striped" style="margin-bottom: 0">

    <thead>
        <tr class="table-header-bg">
            <th class="text-center">Sl</th>
            <th class="text-center">Opening Date</th>
            <th class="pl-1">Name</th>
            <th class="text-center">Balance Type</th>
            <th class="text-right pr-1">Balance</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($accounts as $account)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">{{ fdate($account->created_at) }}</td>
                <td class="pl-1">{{ $account->name }}</td>
                <td class="text-center">{{ $account->balance_type }}</td>

                @if ($account->accountGroup->balance_type == 'Debit')
                    <td width="20%" class="text-right pr-1">
                        {{ number_format($account->debit - $account->credit ?? 0, 2) }}
                    </td>
                @else
                    <td width="20%" class="text-right pr-1">
                        {{ number_format($account->credit - $account->debit ?? 0, 2) }}
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>

</table>
