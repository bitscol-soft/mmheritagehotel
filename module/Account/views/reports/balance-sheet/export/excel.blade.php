@foreach($accountGroups->where('id', 1) as $key => $accountGroup)
    <div class="row">
        <div class="col-sm-12">

            <h4 style="margin-left: 5%"><strong>{{ $accountGroup->name }}</strong></h4>
            <table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%; border-bottom: 1px solid black !important;"">

                <tbody>
                    @php
                        $totalBalance = 0;
                    @endphp

                    @foreach($accountGroup->accountControls as $accountControl)
                        <tr>
                            <td>{{ $accountControl->name }}</td>

                            @if ($accountGroup->balance_type == 'Debit')

                                @php
                                    $totalBalance += $balance = $accountControl->accounts->sum('debit_balance') - $accountControl->accounts->sum('credit_balance');
                                @endphp
                            @else
                                @php
                                    $totalBalance += $balance = $accountControl->accounts->sum('credit_balance') - $accountControl->accounts->sum('debit_balance');
                                @endphp
                            @endif
                            <td width="150px" class="text-right pr-1">{{ number_format($balance ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right">
                            <strong style="font-size: 18px; font-weight: bolder; letter-spacing: -1px !important;">
                                Total {{ $accountGroup->name }}
                            </strong>
                        </td>
                        <td class="text-right pr-1" width="20%">
                            <strong style="font-size: 18px; font-weight: bolder; letter-spacing: -1px !important;">
                                {{ number_format($totalBalance, 2) }}
                            </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endforeach


<div class="row">
    <div class="col-sm-12">

        <h4 style="margin-left: 5%"><strong>Owners Equity</strong></h4>
        <table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%; border-bottom: 1px solid black !important;">

            <tbody>

                <tr>
                    <td class="text-right">
                        <strong style="font-size: 14px; font-weight: bolder; letter-spacing: -1px !important;">
                            Total Equity Balance
                        </strong>
                    </td>
                    <td class="text-right pr-1" width="20%">
                        <strong style="font-size: 14px; font-weight: bolder; letter-spacing: -1px !important;">
                            {{ number_format($equity_balance, 2) }}
                        </strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


@php
    $totalBalance = 0;
    $asset = 0;
@endphp


@foreach($accountGroups->whereIn('id', [2, 10]) as $key => $accountGroup)
    <div class="row">
        <div class="col-sm-12">

            @if($loop->first)
                <h4 style="margin-left: 5%"><strong>{{ $accountGroup->name }}</strong></h4>
            @endif
            <table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%">

                <tbody>

                    @foreach($accountGroup->accountControls as $accountControl)
                        <tr>
                            <td>
                                {{ $accountControl->name == 'None' && $accountGroup->id == 10 ? 'Accumulated Deprication' : $accountControl->name }}
                            </td>

                            @if ($accountGroup->balance_type == 'Debit')

                                @php
                                    $totalBalance += $balance = $accountControl->accounts->sum('debit_balance') - $accountControl->accounts->sum('credit_balance');
                                @endphp
                            @else
                                @php
                                    $totalBalance += $balance = $accountControl->accounts->sum('credit_balance') - $accountControl->accounts->sum('debit_balance');
                                @endphp
                            @endif
                            <td width="150px" class="text-right pr-1">{{ number_format($balance ?? 0, 2) }}</td>
                        </tr>
                    @endforeach




                    @if($loop->last)
                        <tr>
                            <td class="text-right" style="border-bottom: 1px solid black !important;">
                                <strong style="font-size: 14px; font-weight: bolder; letter-spacing: -1px !important;">
                                    Total Liabilities
                                </strong>
                            </td>
                            <td class="text-right pr-1" style="border-bottom: 1px solid black !important;">

                                <strong style="font-size: 14px; font-weight: bolder; letter-spacing: -1px !important;">
                                    {{ number_format($totalBalance, 2) }}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right">
                                <strong style="font-size: 18px; font-weight: bolder; letter-spacing: -1px !important;">
                                    Total Liabilities & Owners Equity
                                </strong>
                            </td>
                            <td class="text-right pr-1">

                                <strong style="font-size: 18px; font-weight: bolder; letter-spacing: -1px !important;">
                                    {{ number_format($totalBalance + $equity_balance, 2) }}
                                </strong>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endforeach
