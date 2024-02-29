@extends('layouts.pdf-master')
@section('title', 'Monthly Vat Report')
@section('heading')
<h2 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Monthly Vat Report
</h2>
<h4 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Date - {{ date('Y-m-d') }}
</h4>
@endsection
@section('table')
    @include('hotel/reports/vat-report-monthly/export/excel')
@endsection
