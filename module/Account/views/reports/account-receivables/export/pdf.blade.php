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



    <htmlpagefooter name="page-footer">
        <div align="right" style="font-size: 12px;">
            <hr>
            <i><b>{PAGENO} / {nbpg}</b></i>
        </div>
    </htmlpagefooter>
</body>

</html>
