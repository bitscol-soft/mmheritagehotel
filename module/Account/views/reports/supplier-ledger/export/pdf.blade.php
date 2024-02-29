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
            Supplier Ledger Report
        </h2>
        <h5 style="text-align: center;">Date From {{fdate(request('from'),'d/m/Y')}} To {{fdate(request('to'),'d/m/Y')}}</h5>
    </div>

    <table class="table table-bordered table-striped" style="margin-bottom: 0">
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

            @if (request('account_id'))


                @php
                    if ($selected_account->accountGroup->balance_type == 'Debit') {


                        $balance = ($debit_balance + $paginate_debit_balance) - ($credit_balance + $paginate_credit_balance);

                    } else {

                        $balance = ($credit_balance + $paginate_credit_balance) - ($debit_balance + $paginate_debit_balance);
                    }

                @endphp
                <tr>
                    <td class="text-left pl-3" colspan="6">Opening Balance</td>
                    <td class="text-right pr-1">{{ number_format($balance, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="7" style="font-size: 16px" class="text-center text-danger">
                        NO RECORDS FOUND!
                    </td>
                </tr>
            @endif

            @php
                $total_debit = 0;
                $total_credit = 0;
            @endphp

            @foreach ($transactions as $transaction)
                @php
                    
                    if ($selected_account->accountGroup->balance_type == 'Debit') {
                        $balance += ($transaction->debit_amount - $transaction->credit_amount);
                    } else {
                        $balance += ($transaction->credit_amount - $transaction->debit_amount);
                    }
                    
                    $total_debit += $transaction->debit_amount;
                    $total_credit += $transaction->credit_amount;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $transaction->date }}</td>
                    <td class="text-center">{{ $transaction->invoice_no }}</td>
                    <td class="pl-3">{{ $transaction->description }}</td>
                    <td class="text-right pr-1">{{ number_format($transaction->debit_amount, 2) }}</td>
                    <td class="text-right pr-1">{{ number_format($transaction->credit_amount, 2) }}</td>
                    <td class="text-right pr-1">{{ number_format($balance, 2) }}</td>
                </tr>
            @endforeach

            <tr>
                <th class="text-center" colspan="4">Total In Page</th>
                <th class="text-right pr-1">{{ number_format($total_debit, 2) }}</th>
                <th class="text-right pr-1">{{ number_format($total_credit, 2) }}</th>
                <th></th>
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
