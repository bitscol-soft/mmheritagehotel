<!doctype html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? '' }}</title>
</head>


<style>
    body {
        font-family: 'Helvetica Neue, Helvetica, Arial,sans-serif';
    }


    @page {
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
        header: page-header;

    }

    table,
    td,
    th {
        font-size: 12px;
        border: 1px solid black;
    }

    table {
        border-top: none;
        border-left: none;
        border-right: none;
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
        width: 100%;
    }

    th.head {
        background-color: rgba(143, 175, 170, 0.35);
    }

</style>

<body>

    <div style="text-align: center;">
        <h2 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            Subsidiary Wise Ledger Report
        </h2>
        <h5 style="text-align: center;">Date From {{ fdate(request('from'), 'd/m/Y') }} To
            {{ fdate(request('to'), 'd/m/Y') }}</h5>
    </div>

    <table class="table">

        <tbody>

            @php
                
                $balance_type = $accountTransactions->first()->balance_type;
            @endphp


            @foreach ($accountTransactions as $account)
                <tr>
                    <th style="text-align: center; width:5%">
                        {{ $loop->iteration }}
                    </th>

                    <th style="width: 25%">
                        {{ $account->name }}
                    </th>

                    @php
                        
                        if ($balance_type == 'Debit') {
                            $total = $account->transaction_items->sum('credit_amount') - $account->transaction_items->sum('debit_amount');
                        } else {
                            $total = $account->transaction_items->sum('debit_amount') - $account->transaction_items->sum('credit_amount');
                        }
                    @endphp

                    <th style="width: ">
                        <strong style="font-size: 15px">{{ number_format($total) }}</strong>
                    </th>
                </tr>

                @if ($account->transaction_items->count())
                    <tr>
                        <td>
                        </td>

                        <td colspan="2">
                            <table class="table table-bordered table-striped" style="border: none !important">
                                <thead>
                                    <tr>
                                        <th style="border: none">Date</th>
                                        <th style="border: none">Description</th>
                                        <th style="border: none" class="text-right pr-1">Dr.</th>
                                        <th style="border: none" class="text-right pr-1">Cr.</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($account->transaction_items as $transaction)
                                        <tr>
                                            <td style="border: none">{{ $transaction->date }}</td>
                                            <td style="border: none">{{ $transaction->getDescription() }}</td>
                                            <td style="border: none" class="text-right pr-1">
                                                {{ number_format($transaction->credit_amount, 2) }}</td>
                                            <td style="border: none" class="text-right pr-1">
                                                {{ number_format($transaction->debit_amount, 2) }}</td>
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




    <htmlpagefooter name="page-footer">
        <div align="right" style="font-size: 12px;">
            <hr>
            <i><b>{PAGENO} / {nbpg}</b></i>
        </div>
    </htmlpagefooter>
</body>

</html>
