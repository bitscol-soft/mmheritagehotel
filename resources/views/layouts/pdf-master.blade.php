<!doctype html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Pdf')</title>
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
        font-size: 12px;
        border: 0.8px solid rgb(190, 186, 186);
        padding: 3px;
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


    thead.tr.th {
        background-color: rgba(143, 175, 170, 0.35) !important;
    }

    .text-right{
        text-align: right;
    }

    .text-left{
        text-align: left;
    }

    .text-center{
        text-align: center;
    }

</style>

<body>


    <div style="text-align: center;">
        {{-- <h2 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
            {{ $company }}
        </h2> --}}
        @yield('heading')
    </div>





    @yield('table')





    <htmlpagefooter name="page-footer">
        <div align="right" style="font-size: 12px;">
            <hr>
            <i><b>{PAGENO} / {nbpg}</b></i>
        </div>
    </htmlpagefooter>
</body>

</html>
