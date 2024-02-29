@extends('layouts.pdf-master')
@section('title', 'Sale Report')

@section('heading')
<h2 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Sale Report
</h2>
<h4 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Date - {{ date('Y-m-d') }}
</h4>
@endsection

@section('table')
    @include('rst.reports.sales.export.excel')
    <div class="text-right" style="margin-top: 3px">
        Printed at {{ now() }}
    </div>
@endsection
