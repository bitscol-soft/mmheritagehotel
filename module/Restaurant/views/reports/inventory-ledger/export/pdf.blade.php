@extends('layouts.pdf-master')
@section('title', 'Inventory Report')

@section('heading')
<h2 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Inventory Ledger Report
</h2>
<h4 style="line-height: 3px; 40px;font-family: Helvetica Neue, Helvetica, Arial, sans-serif">
    Date - {{ request('from_date') ?? date('Y-m-d') }}
</h4>
@endsection

@section('table')
    @include('reports.inventory-ledger.export.excel')
    <div class="text-right" style="margin-top: 3px">
        Printed at {{ now() }}
    </div>
@endsection
