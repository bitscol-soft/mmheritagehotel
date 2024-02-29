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
        font-family: 'Helvetica Neue, Helvetica, Arial,sans-serif, nikosh';
        font-size: 80.25%;
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
        font-size: 10px;
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
            Supplier Report
        </h2>
        <h5 style="text-align: center;">Date From {{fdate(request('from'),'d/m/Y')}} To {{fdate(request('to'),'d/m/Y')}}</h5>
    </div>

    <table class="table table-bordered table-striped" style="margin-bottom: 0">
        <thead>
            <tr class="table-header-bg">
                <th class="text-center">Sl</th>
                <th class="pl-3">Supplier</th>
                <th class="pl-3 text-right">Payment Amount</th>
                <th class="pl-3 text-right">Purchase Amount</th>
                <th class="pl-3 text-right">Balance</th>
            </tr>
        </thead>

        <tbody>

            @php
                $grand_total_amount = 0;
                $grand_total_paid = 0;
                $grand_total_purchase = 0;
                $grand_total_due_amount = 0;
            @endphp


            @foreach ($transaction_purchases as $key => $purchase)

                @php

                    $balance = abs($balances->where('account_id', $purchase->first()->account_id)->sum('debit_amount') - $balances->where('account_id', $purchase->first()->account_id)->sum('credit_amount'));

                    $grand_total_paid       += $total_paid          = $purchase->sum('debit_amount');
                    $grand_total_purchase   += $total_purchase      = $purchase->sum('credit_amount');
                    $grand_total_amount     += $total_amount        = $balance + abs($purchase->sum('debit_amount') - $purchase->sum('credit_amount'));
                    $grand_total_due_amount += $total_due_amount    = abs($total_amount - $total_paid);

                    
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="pl-3">{{ optional($purchase->first()->account)->name }}</td>
                    <td class="text-right pr-1">{{ number_format($total_paid, 2) }}</td>
                    <td class="text-right pr-1">{{ number_format($total_purchase, 2) }}</td>
                    <td class="text-right pr-1">{{ number_format($total_amount, 2) }}</td>
                </tr>
            @endforeach

            <tr>
                <th class="text-center" colspan="2">Total:</th>
                <th class="text-right pr-1">{{ number_format($grand_total_paid, 2) }}</th>
                <th class="text-right pr-1">{{ number_format($grand_total_purchase, 2) }}</th>
                <th class="text-right pr-1">{{ number_format($grand_total_amount, 2) }}</th>
            </tr>

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
