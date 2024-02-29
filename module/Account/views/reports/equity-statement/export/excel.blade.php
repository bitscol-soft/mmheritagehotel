<table class="table table-sm table-bordered">



    <thead>
        <tr>
            <th>Particular</th>
            <th class="text-center">Share Capital</th>
            <th class="text-center">Retained Earnings</th>
            <th class="text-center">Total</th>
        </tr>
    </thead>

    @php
        $previous_year_share_capital = 0;
        $previous_year_retained_earnings = 0;
    @endphp


    <tbody>
        <tr>
            <td>Opening Balance</td>
            <td class="text-right capital">
                {{ number_format($previous_year_share_capital, 0) }}
            </td>
            <td class="text-right retained-earnings">
                {{ number_format($previous_year_retained_earnings, 0) }}
            </td>
            <td class="text-right item-total"></td>
        </tr>

        <tr>
            <td>Add : Profit/Loss during the year</td>
            <td class="text-right capital">
                {{ number_format($profit_loss_share_capital = 0, 0) }}
            </td>
            <td class="text-right retained-earnings">
                {{ number_format($profit_los_retained_earnings = $profit_and_loss, 0) }}
            </td>
            <td class="text-right item-total"></td>
        </tr>
        <tr>
            <td>Add : addition during the year</td>
            <td class="text-right capital">
                {{ number_format($addition_share_capital = 0, 0) }}
            </td>
            <td class="text-right retained-earnings">
                {{ number_format($addition_retained_earnings = $equity > 0 ? $equity : 0, 0) }}
            </td>
            <td class="text-right item-total"></td>
        </tr>
        <tr>
            <td>Less : adjustment during the year</td>
            <td class="text-right capital">
                {{ number_format($adjustment_share_capital = 0, 0) }}
            </td>
            <td class="text-right retained-earnings">
                {{ number_format($adjusement_retained_earnings = $equity < 0 ? $equity : 0, 0) }}
            </td>
            <td class="text-right item-total"></td>
        </tr>
        <tr>
            <td>
                <strong>Closing Balance</strong>
            </td>
            <td class="text-right">
                <strong class="capital">{{ number_format($previous_year_share_capital + $profit_loss_share_capital + $addition_share_capital - $adjustment_share_capital, 0) }}</strong>
            </td>
            <td class="text-right">
                <strong class="retained-earnings">{{ number_format($previous_year_retained_earnings + $profit_los_retained_earnings + $addition_retained_earnings - $adjusement_retained_earnings, 0) }}</strong>
            </td>
            <td class="text-right">
                <strong class="item-total"></strong>
            </td>
        </tr>
        <tr>
            <td>Previous Year Balance</td>
            <td class="text-right capital">
                {{ number_format($previous_year_share_capital, 0) }}
            </td>
            <td class="text-right retained-earnings">
                {{ number_format($previous_year_retained_earnings, 0) }}
            </td>
            <td class="text-right item-total"></td>
        </tr>
    </tbody>
</table>
