@extends('layouts.pdf-master')
@section('title', 'Today Report')

@section('heading')
<h2 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Today Report
</h2>
<h4 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Date - {{ date('Y-m-d') }}
</h4>
@endsection

@section('table')
    @include('reports.today-activities.export.excel')
    <div class="text-right" style="margin-top: 3px">
        Printed at {{ now() }}
    </div>
@endsection
