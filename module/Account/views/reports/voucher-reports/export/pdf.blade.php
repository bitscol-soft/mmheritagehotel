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
        font-size: 10px;
        border: 1px solid black;
        vertical-align: middle;
        text-align: center;
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
            Voucher Report
        </h2>
        {{-- <h3 style="line-height: 3px; 30px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            {{ $branch }}
        </h3> --}}
    </div>

    <table class="table table-bordered table-striped" style="margin-bottom: 0">
        <thead>
            <tr class="table-header-bg">
                <th class="text-center">Sl</th>
                <th class="text-center">Date</th>
                <th class="text-center">Voucher No</th>
                <th class="text-center">Voucher Type</th>
                <th class="pl-3">Company</th>
                <th class="pl-3">Description</th>
                <th class="text-right pr-1">Amount</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($vouchers as $voucher)
                @php
                    $route = 'voucher-' . strtolower($voucher->voucher_type) . 's.show';
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $voucher->date }}</td>
                    <td class="text-center">{{ $voucher->invoice_no }}</td>
                    <td class="text-center">{{ $voucher->voucher_type }}</td>
                    <td class="pl-3">{{ optional($voucher->company)->name }}</td>
                    <td class="pl-3">{{ $voucher->description }}</td>
                    <td class="text-right pr-1">
                        <a href="{{ route($route, $voucher->id) }}" target="_blank">
                            {{ number_format($voucher->amount, 2) }}
                        </a>
                    </td>
                </tr>
            @endforeach

            <tr>
                <th class="text-right" colspan="6">Total In Page</th>
                <th class="text-right pr-1">{{ number_format($vouchers->sum('amount'), 2) }}</th>
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
