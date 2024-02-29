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
            Chart Of Account Report
        </h2>
        {{-- <h3 style="line-height: 3px; 30px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            {{ $branch }}
        </h3> --}}
    </div>

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




    <htmlpagefooter name="page-footer">
        <div align="right" style="font-size: 12px;">
            <hr>
            <i><b>{PAGENO} / {nbpg}</b></i>
        </div>
    </htmlpagefooter>
</body>

</html>
