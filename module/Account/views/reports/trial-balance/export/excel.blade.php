<table class="table" style="margin-bottom: 0; width: 100% !important;">
    <thead>
        <tr class="bg-secondary"
            style="color: black !important; font-weight: bolder; font-size: 15px">
            <th colspan="4">Account Description</th>
            <th class="text-right pr-1">Dr.</th>
            <th class="text-right pr-1">Cr.</th>
        </tr>
    </thead>

    <tbody>

        @if ($accountGroups->count() == 0)
            <tr>
                <td colspan="7" style="font-size: 16px" class="text-center text-danger">NO RECORDS
                    FOUND!</td>
            </tr>
        @endif


        @php
            $totalDebit = 0;
            $totalCredit = 0;

            $totalTrialAmountDebit = 0;
            $totalTrialAmountCredit = 0;
        @endphp

        @foreach ($accountGroups as $accountGroup)

            @php
                $debitAccountGroup = $accountGroup->accountControls->sum(function ($control) {
                    return $control->accountSubsidiaries->sum(function ($item) {
                        return $item->accounts->sum('debit');
                    });
                });
                $creditAccountGroup = $accountGroup->accountControls->sum(function ($control) {
                    return $control->accountSubsidiaries->sum(function ($item) {
                        return $item->accounts->sum('credit');
                    });
                });

                if ($accountGroup->balance_type == 'Debit') {
                    $totalTrialAmountDebit += ($debitAccountGroup - $creditAccountGroup);
                } else {
                    $totalTrialAmountCredit += ($creditAccountGroup - $debitAccountGroup);
                }
            @endphp


            <tr style="background: #a8c1c3; color: white;">
                <th colspan="4">
                    <strong style="font-size: 16px">{{ $accountGroup->name }}</strong>
                </th>

                @if ($accountGroup->balance_type == 'Debit')
                    <td class="text-right pr-1">
                        <strong
                            style="font-size: 16px">{{ number_format($debitAccountGroup - $creditAccountGroup, 2) }}</strong>
                    </td>
                    <td class="text-right pr-1">
                        <strong style="font-size: 16px">0.00</strong>
                    </td>

                @else
                    <td class="text-right pr-1">
                        <strong style="font-size: 16px">0.00</strong>
                    </td>
                    <td class="text-right pr-1">
                        <strong
                            style="font-size: 16px">{{ number_format($creditAccountGroup - $debitAccountGroup, 2) }}</strong>
                    </td>
                @endif



                @php
                    $totalDebit += $creditAccountGroup;
                    $totalCredit += $creditAccountGroup;
                @endphp
            </tr>



            @foreach ($accountGroup->accountControls as $accountControl)
                @php
                    $debitAccountControl = $accountControl->accountSubsidiaries->sum(function ($item) {
                        return $item->accounts->sum('debit');
                    });

                    $creditAccountControl = $accountControl->accountSubsidiaries->sum(function ($item) {
                        return $item->accounts->sum('credit');
                    });

                @endphp

                <tr>
                    <td></td>
                    <th colspan="3">
                        <strong style="font-size: 15px">{{ $accountControl->name }}</strong>
                    </th>

                    @if ($accountGroup->balance_type == 'Debit')
                        <td class="text-right pr-1">
                            <strong class="account-control-debit-account"
                                data-id="{{ $accountControl->id }}"
                                style="font-size: 15px">{{ number_format($debitAccountControl - $creditAccountControl, 2) }}</strong>
                        </td>
                        <td class="text-right pr-1">
                            <strong style="font-size: 15px">0.00</strong>
                        </td>
                    @else
                        <td class="text-right pr-1">
                            <strong style="font-size: 15px">0.00</strong>
                        </td>
                        <td class="text-right pr-1">
                            <strong class="account-control-credit-account"
                                style="font-size: 15px">{{ number_format($creditAccountControl - $debitAccountControl, 2) }}</strong>
                        </td>
                    @endif
                </tr>
                @foreach ($accountControl->accountSubsidiaries as $accountSubsidiary)

                    <tr>
                        <td></td>
                        <td></td>
                        <th colspan="2">
                            <strong style="font-size: 14px">
                                {{ $accountSubsidiary->name }}
                            </strong>
                        </th>

                        @if ($accountGroup->balance_type == 'Debit')
                            <td class="text-right pr-1">
                                <strong
                                    class="subsidiary-debit-account account-control-debit-{{ $accountControl->id }}"
                                    data-id="{{ $accountSubsidiary->id }}"
                                    style="font-size: 14px">{{ number_format($accountSubsidiary->accounts->sum('debit') - $accountSubsidiary->accounts->sum('credit'), 2) }} </strong>
                            </td>
                            <td class="text-right pr-1">
                                <strong style="font-size: 14px">0.00</strong>
                            </td>
                        @else
                            <td class="text-right pr-1">
                                <strong style="font-size: 14px">0.00</strong>
                            </td>
                            <td class="text-right pr-1">
                                <strong
                                    class="subsidiary-credit-account account-control-credit-{{ $accountControl->id }}"
                                    style="font-size: 14px">{{ number_format($accountSubsidiary->accounts->sum('credit') - $accountSubsidiary->accounts->sum('debit'), 2) }}</strong>
                            </td>
                        @endif
                    </tr>

                    @foreach ($accountSubsidiary->accounts as $account)

                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ $account->name }}</td>

                            @if ($accountGroup->balance_type == 'Debit')
                                <td class="text-right pr-1 account-debit-{{ $accountSubsidiary->id }}">
                                    {{ number_format($account->debit - $account->credit ?? 0, 2) }}
                                </td>
                                <td class="text-right pr-1">0.00</td>
                            @else
                                <td class="text-right pr-1">0.00</td>
                                <td class="text-right pr-1 account-credit-{{ $accountSubsidiary->id }}">
                                    {{ number_format($account->credit - $account->debit ?? 0, 2) }}
                                </td>
                            @endif

                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
    </tbody>


    <thead>
        <tr class="bg-secondary" style="color: black !important; font-weight: bolder;">
            <th colspan="4">Trail</th>
            <th class="text-right pr-1" style="color: #1ba74d">
                {{ number_format($totalTrialAmountDebit, 0) }}
            </th>
            <th class="text-right pr-1" style="color: #1ba74d">
                {{ number_format($totalTrialAmountCredit, 0) }}
            </th>
        </tr>
    </thead>

</table>
