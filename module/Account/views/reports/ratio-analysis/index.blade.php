@extends('layouts.master')

@section('title', 'Ratio Analysis')

@section('page-header')
    <i class="fa fa-balance-scale"></i> Ratio Analysis
@stop

@section('content')
    <div class="widget-box">
        <div class="widget-header">
            <h5 style="font-weight:600"><i class="fa fa-filter"></i> Ratio Analysis — {{ $from }} → {{ $to }}</h5>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <form method="GET" class="form-inline" style="margin-bottom:12px">
                    <input type="date" name="from" value="{{ $from }}" class="form-control input-sm" style="width:160px">
                    <input type="date" name="to" value="{{ $to }}" class="form-control input-sm" style="width:160px;margin-left:6px">
                    <button type="submit" class="btn btn-sm btn-primary" style="margin-left:6px"><i class="fa fa-search"></i> Filter</button>
                </form>

                <div class="row" style="margin-bottom:12px">
                    <div class="col-sm-3"><div class="well well-sm"><b>Assets</b><br>{{ number_format($assets, 2) }}</div></div>
                    <div class="col-sm-3"><div class="well well-sm"><b>Liabilities</b><br>{{ number_format($liabilities, 2) }}</div></div>
                    <div class="col-sm-3"><div class="well well-sm"><b>Revenue</b><br>{{ number_format($revenue, 2) }}</div></div>
                    <div class="col-sm-3"><div class="well well-sm"><b>Expenses</b><br>{{ number_format($expense, 2) }}</div></div>
                </div>

                <table class="table table-striped table-bordered">
                    <thead>
                    <tr><th>Ratio</th><th class="text-right">Value</th><th>Formula (period totals)</th></tr>
                    </thead>
                    <tbody>
                    @foreach($ratios as $ratio)
                        <tr>
                            <td>{{ $ratio[0] }}</td>
                            <td class="text-right">{{ is_numeric($ratio[1]) ? number_format($ratio[1], 2) : $ratio[1] }}</td>
                            <td class="text-muted">{{ $ratio[2] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <p class="text-muted">Note: balances are aggregated from posted transactions within the selected period; group-level sub-classification (e.g. current vs. non-current) is not stored, so liquidity ratios use total assets.</p>
            </div>
        </div>
    </div>
@stop
