@extends('layouts.pdf-master')
@section('title', 'Night Closing Report')
@section('heading')
<h2 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Night Closing Report
</h2>
<h4 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Date - {{ date('Y-m-d') }}
</h4>
@endsection

@section('table')

    @include('hotel.reports.night-closing.export.excel')

    <div class="text-right" style="margin-top: 3px">
        Printed at {{ now() }}
    </div>
@endsection

