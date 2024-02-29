@extends('layouts.pdf-master')
@section('title', 'Expected Departure List')
@section('heading')
<h2 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Expected Departure List
</h2>
<h4 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Date - {{ date('Y-m-d') }}
</h4>
@endsection
@section('table')
    @include('hotel/reports/expected-departure/export/excel')
@endsection
