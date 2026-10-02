@extends('layouts.master')

@section('title', 'Ratio Analysis')

@section('page-header')
    <i class="fa fa-balance-scale"></i> Ratio Analysis
@stop

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Ratio Analysis" description="Financial ratios.">
    <x-slot name="actions">
        <h5 style="font-weight:600"><i class="fa fa-filter"></i> Ratio Analysis — {{ $from }} → {{ $to }}</h5>
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form method="GET" class="mm-setup-filter mm-report-form form-inline" style="margin-bottom:12px">
            <input type="date" name="from" value="{{ $from }}" class="form-control input-sm" style="width:160px">
            <input type="date" name="to" value="{{ $to }}" class="form-control input-sm" style="width:160px;margin-left:6px">
            <button type="submit" class="btn btn-sm btn-primary" style="margin-left:6px"><i class="fa fa-search"></i> Filter</button>
        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">

        <div class="row" style="margin-bottom:12px">
            <div class="col-sm-3"><div class="well well-sm"><b>Assets</b><br>{{ number_format($assets, 2) }}</div></div>
            <div class="col-sm-3"><div class="well well-sm"><b>Liabilities</b><br>{{ number_format($liabilities, 2) }}</div></div>
            <div class="col-sm-3"><div class="well well-sm"><b>Revenue</b><br>{{ number_format($revenue, 2) }}</div></div>
            <div class="col-sm-3"><div class="well well-sm"><b>Expenses</b><br>{{ number_format($expense, 2) }}</div></div>
        </div>

        <x-mm.table-scroll label="Ratio Analysis">
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
        </x-mm.table-scroll>
        <p class="text-muted">Note: balances are aggregated from posted transactions within the selected period; group-level sub-classification (e.g. current vs. non-current) is not stored, so liquidity ratios use total assets.</p>
    </x-mm.panel>
</x-mm.page>

@stop
