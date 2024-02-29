<div class="col-sm-12" style="width: 100%">
    <table class="table table-sm table-bordered detail-table">
        <thead>
            <tr>
                <th colspan="2">Particular</th>
                <th class="text-center">
                    {{ request('year') ?? now()->format('Y') }}
                </th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td colspan="2"><strong style="font-size: 15px">Sales</strong></td>
                <td class="text-center">
                    @php
                        $sale_amount = optional(optional($revenues)->accountControls)->sum(function($accountControl) {
                            return $accountControl->accounts->sum('balance');
                        });
                    @endphp
                    {{-- {{ number_format($sale_amount = optional(optional($revenues)->accountControls)->sum(function($accountControl) {
                        return $accountControl->accounts->sum('balance');
                    }), 0) }} --}}
                </td>
            </tr>


            <tr style="border:none !important" class="detail-rows">
                <td colspan="3">
                    <div class="col-sm-10 col-sm-offset-1">
                        <table class="table" style="border:none !important;margin-bottom:0%">

                            @foreach ($revenues->accountControls as $accountControl)
                                @foreach ($accountControl->accounts as $account)
                                    <tr style="border:none !important">
                                        <td style="border:none !important">{{ $account->name }}</td>
                                        <td style="border:none !important" class="text-right"
                                            style="border:none !important">

                                            {{ number_format($account->balance, 2) }}

                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                </td>

            </tr>


            @php
                $less_amount = $purchases->accountControls->sum(function($accountControl) {
                    return $accountControl->accounts->sum('balance');
                })
            @endphp
            <tr>
                <td colspan="2">Less: Cost of Goods Sold</td>
                <td class="text-center">
                    {{ number_format($less_amount, 0) }}</td>
            </tr>

            <tr style="border:none !important" class="detail-rows">
                <td colspan="3">
                    <div class="col-sm-10 col-sm-offset-1" >
                        <table class="table" style="border:none !important;margin-bottom:0%">

                            @foreach ($purchases->accountControls as $accountControl)
                                @foreach ($accountControl->accounts as $account)
                                    <tr style="border:none !important">
                                        <td style="border:none !important">{{ $account->name }}</td>
                                        <td style="border:none !important" class="text-right"
                                            style="border:none !important">
                                            {{ number_format($account->balance, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                </td>

            </tr>


            <tr>
                <td colspan="2" class="text-right">
                    <strong>Gross Profit:</strong>
                </td>
                <td class="text-center">
                    <strong>{{ number_format($gross_profit = $sale_amount - $less_amount, 0) }}</strong>
                </td>
            </tr>



            <tr>
                <td colspan="2">&nbsp;</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Operating Expenses:</strong>
                </td>
                <td></td>
            </tr>


            <tr>
                <td colspan="2">
                    Administrative Expenses
                </td>
                <td class="text-center">
                    {{ 
                        number_format( 
                            $expense = $expenses->accountControls->sum(function($accountControl) {
                                return $accountControl->accounts->sum('balance');
                            }), 
                        0) 
                    }}
                </td>
            </tr>


            <tr style="border:none !important" class="detail-rows">
                <td colspan="3">
                    <div class="col-sm-10 col-sm-offset-1" >
                        <table class="table" style="border:none !important;margin-bottom:0%">

                            @foreach ($expenses->accountControls as $accountControl)
                                @foreach ($accountControl->accounts as $account)
                                    <tr style="border:none !important">
                                        <td style="border:none !important">{{ $account->name }}</td>
                                        <td style="border:none !important" class="text-right"
                                            style="border:none !important">
                                            {{ number_format($account->balance, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                </td>

            </tr>


            <tr>
                <td colspan="2">
                    Depreciation
                </td>
                <td class="text-center">
                    {{ number_format( 
                            $depreciation = $depreciations->accountControls->sum(function($accountControl) {
                                return $accountControl->accounts->sum('balance');
                            }), 
                        0) 
                    }}
                </td>
            </tr>

            <tr style="border:none !important" class="detail-rows">
                <td colspan="3">
                    <div class="col-sm-10 col-sm-offset-1" >
                        <table class="table" style="border:none !important;margin-bottom:0%">

                            @foreach ($depreciations->accountControls as $accountControl)
                                @foreach ($accountControl->accounts as $account)
                                    <tr style="border:none !important">
                                        <td style="border:none !important">{{ $account->name }}</td>
                                        <td style="border:none !important" class="text-right"
                                            style="border:none !important">
                                            {{ number_format($account->balance, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                </td>

            </tr>


            <tr>
                <td colspan="2" class="text-right">
                    <strong>Operating Profit:</strong>
                </td>
                <td class="text-center">
                    <strong>{{ number_format($operative_profit = $gross_profit - $expense - $depreciation, 0) }}</strong>
                </td>
            </tr>


            <tr>
                <td colspan="2">
                    Add: Non-operating Income
                </td>
                <td class="text-center">
                    {{ number_format($none_operating_income = $equity->accountControls->sum(function($accountControl) {
                                return $accountControl->accounts->sum('balance');
                    }), 0) }}
                </td>
            </tr>


            <tr style="border:none !important" class="detail-rows">
                <td colspan="3">
                    <div class="col-sm-10 col-sm-offset-1" >
                        <table class="table" style="border:none !important;margin-bottom:0%">

                            @foreach ($equity->accountControls as $accountControl)
                                @foreach ($accountControl->accounts as $account)
                                    <tr style="border:none !important">
                                        <td style="border:none !important">{{ $account->name }}</td>
                                        <td style="border:none !important" class="text-right"
                                            style="border:none !important">
                                            {{ number_format($account->balance, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                </td>

            </tr>


            <tr>
                <td colspan="2" class="text-right">
                    <strong>Earnings Before Interest & Tax:</strong>
                </td>
                <td class="text-center">
                    <strong>{{ number_format($earning_interest_and_tax = $operative_profit + $none_operating_income, 0) }}</strong>
                </td>
            </tr>


            <tr>
                <td colspan="2">
                    Less: Financial Expenses
                </td>
                <td class="text-center">{{ $financial_expendex = 0 }}</td>
            </tr>



            <tr>
                <td colspan="2" class="text-right">
                    <strong>Earnings Before Tax:</strong>
                </td>
                <td class="text-center">
                    <strong>{{ number_format($earning_before_tax = $earning_interest_and_tax + $financial_expendex, 0) }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Less: Provision for Income Tax
                </td>
                <td class="text-center">{{ $income_tax = 0 }}</td>
            </tr>



            <tr>
                <td colspan="2">
                    <strong>Net Profit/(Loss) after Tax:</strong>
                </td>
                <td class="text-center">
                    <strong>{{ number_format($net_profit_and_loss = $earning_before_tax + $income_tax, 0) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
</div>
