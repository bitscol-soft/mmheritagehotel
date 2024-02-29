@extends('layouts.pdf-master')
@section('title', 'Cash Flow')
@section('heading')
<h2 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Room Log Report
</h2>
<h4 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Date - {{ date('Y-m-d') }}
</h4>
@endsection
@section('table')
    @include('hotel/reports/room-logs/export/excel')
@endsection
