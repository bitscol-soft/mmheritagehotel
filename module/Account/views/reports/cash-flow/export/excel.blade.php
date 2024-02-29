<table class="table table-bordered table-striped" style="margin-bottom: 0; margin-left: 10%; width: 85%;">

    <tbody>
        <tr>
            <td>Sl.</td>
            <td><strong>Particular</strong></td>
            <td width="150px" class="text-center pr-1">Tk.</td>
        </tr>
        <tr>
            <td>1</td>
            <td>
                <strong>Cash flows from operating activities: </strong>
            </td>
            <td width="150px" class="text-right pr-1"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                Net Profit/Loss
            </td>
            <td width="150px" class="text-right pr-1">{{ number_format($equity_balance, 0) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>
                Adjustment to reconcile net profit to net cash:
            </td>
            <td width="150px" class="text-right pr-1"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                Depriciation exp
            </td>
            <td width="150px" class="text-right pr-1">{{ number_format($depreciations, 0) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>
                Current Asset Increase/Decrease
            </td>
            <td width="150px" class="text-right pr-1">{{ $asset[0] >= 0 ? '(' . number_format(abs($asset[0]), 0) . ')' : number_format(abs($asset[0]), 0) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>
                Current Liabilities Increase/Decrease
            </td>
            <td width="150px" class="text-right pr-1">{{ $liabilities[0] < 0 ? '(' . number_format(abs($liabilities[0]), 0) . ')' : number_format(abs($liabilities[0]), 0) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>
                Net cash provided/used by Operating Activities
            </td>

            @php
                $new_asset = (int)((-1) * $asset[0]);

                $operating_activities = $equity_balance
                                        + $depreciations
                                        + $new_asset
                                        + ($liabilities[0] >= 0 ? $liabilities[0] : (-1 * $liabilities[0]))
                                        ;
            @endphp
            <td width="150px" class="text-right pr-1">
                <strong>{{ $operating_activities >= 0 ? '(' . number_format(abs($operating_activities), 0) . ')' : number_format(abs($operating_activities), 0) }}</strong>
            </td>
        </tr>













        <tr>
            <td>2</td>
            <td>
                <strong>Cash flows from investing activities: </strong>
            </td>
            <td width="150px" class="text-right pr-1"></td>
        </tr>

        <tr>
            <td></td>
            <td>
                Fixed Assets Increase/Decrease
            </td>
            <td width="150px" class="text-right pr-1">{{ $asset[1] >= 0 ? '(' . number_format(abs($asset[1]), 0) . ')' : number_format(abs($asset[1]), 0) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>
                Net cash provided/used by I.A
            </td>
            <td width="150px" class="text-right pr-1">
                <strong>{{ $asset[1] >= 0 ? '(' . number_format(abs($asset[1]), 0) . ')' : number_format(abs($asset[1]), 0) }}</strong>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>
                <strong>Cash flows from financing activities:</strong>
            </td>
            <td width="150px" class="text-right pr-1"></td>
        </tr>
        <tr>
            <td></td>
            <td>
                Long Time Liabilities Increase/Decrease
            </td>
            <td width="150px" class="text-right pr-1">{{ $liabilities[1] < 0 ? '(' . number_format(abs($liabilities[1]), 0) . ')' : number_format(abs($liabilities[1]), 0) }}</td>
        </tr>
        <tr>
            <td></td>
            <td>
                Net cash provided/used by F.A
            </td>
            <td width="150px" class="text-right pr-1">
                <strong>{{ $liabilities[1] < 0 ? '(' . number_format(abs($liabilities[1]), 0) . ')' : number_format(abs($liabilities[1]), 0) }}</strong>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                Net Cash Charged
                <br>
                Add Opening Balance
                <br>
                <strong>Closing Balance</strong>
            </td>
            <td width="150px" class="text-right pr-1"></td>
        </tr>
    </tbody>
</table>
