<!doctype html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? '' }}</title>
</head>



<style type="text/css">
    table,
    th,
    td,
    tr {
        border: none !important;
    }


    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }

        .d-print {
            display: block !important;
        }

        tr {
            page-break-after: avoid !important;
        }

        thead {
            page-break-before: avoid !important;
        }

        .widget-box {
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .px-4 {
            padding: 0 !important;
        }
    }

    @page {
        margin: 0.5in;
        /*size: landscape;*/
    }

    .d-print {
        display: none;
    }


    .header-bg {
        background: #bce4e5 !important;
        padding: 10px !important;
    }

    .odd-bg {
        background: #cecece42 !important;
    }

    .even-bg {
        background: #dadada !important;
    }

</style>

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
        /* border: 1px solid black; */
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
            Journal Report
        </h2>
        {{-- <h3 style="line-height: 3px; 30px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            {{ $branch }}
        </h3> --}}
    </div>

    <table class="table">



        <!-- table header -->
        <thead>
            <tr style="color: black !important; font-weight: bolder; font-size: 15px">
                <th class="header-bg text-center" style="width: 10%">SL</th>
                <th class="header-bg text" style="width: 20%">Account</th>
                <th class="header-bg text" style="width:20%">Description</th>
                <th class="header-bg text-right pr-1" style="width: 25%">Dr.</th>
                <th class="header-bg text-right pr-1" style="width: 25%">Cr.</th>
            </tr>
        </thead>







        <!-- body -->
        <tbody>

            @php
                $totalDebit = 0;
                $totalCredit = 0;
                $sl = 1;
            @endphp


            @forelse ($transactions->groupBy('invoice_no') as $items)

                @foreach ($items as $item)
                    @php
                        $totalDebit += $item->debit_amount;
                        
                        $totalCredit += $item->credit_amount;
                    @endphp

                    <tr class="{{ $sl % 2 == 0 ? 'even-bg' : 'odd-bg' }}">
                        <td class="text-center">
                            @if ($loop->first)
                                {{ $sl }}
                            @endif
                        </td>
                        <td>{{ optional($item->account)->name }}</td>
                        <td>{{ $item->getDescription() }}</td>
                        <td class="text-right pr-1 font-weight-bold">
                            <strong>{{ number_format($item->debit_amount ?? 0, 2) }}</strong>
                        </td>
                        <td class="text-right pr-1 font-weight-bold">
                            <strong>{{ number_format($item->credit_amount ?? 0, 2) }}</strong>
                        </td>
                    </tr>
                @endforeach

                @php
                    $sl++;
                @endphp

            @empty
                <tr>
                    <td colspan="30" style="font-size: 16px" class="text-center text-danger">
                        NO RECORDS FOUND!
                    </td>
                </tr>
            @endforelse
        </tbody>




        <!-- table footer -->
        @if (count($transactions) > 0)
            <tfoot>
                <tr class="{{ $sl % 2 == 0 ? 'even-bg' : 'odd-bg' }}">
                    <th></th>
                    <th class="text"></th>
                    <th class="text-right h4"><strong style="font-size: 16px">Total</strong></th>
                    <th class="text-right h4"><strong
                            style="font-size: 16px">{{ number_format($totalDebit, 2) }}</strong></th>
                    <th class="text-right h4"><strong
                            style="font-size: 16px">{{ number_format($totalCredit, 2) }}</strong>
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
