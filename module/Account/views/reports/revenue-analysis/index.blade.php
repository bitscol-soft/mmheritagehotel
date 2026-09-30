@extends('layouts.master')

@section('title', 'Revenue Analysis')

@section('page-header')
    <i class="fa fa-line-chart"></i> Revenue Analysis
@stop

@section('content')
    <div class="widget-box">
        <div class="widget-header">
            <h5 style="font-weight:600"><i class="fa fa-filter"></i> Revenue Analysis ({{ $from }} → {{ $to }})</h5>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <form method="GET" class="form-inline" style="margin-bottom:12px">
                    <input type="date" name="from" value="{{ $from }}" class="form-control input-sm" style="width:160px">
                    <input type="date" name="to" value="{{ $to }}" class="form-control input-sm" style="width:160px;margin-left:6px">
                    <button type="submit" class="btn btn-sm btn-primary" style="margin-left:6px"><i class="fa fa-search"></i> Filter</button>
                    <a href="{{ request()->fullUrlWithQuery(['print' => 1]) }}" class="btn btn-sm btn-default" style="margin-left:6px" target="_blank"><i class="fa fa-print"></i> Print</a>
                </form>

                <table class="table table-striped table-bordered table-hover" id="dataTable">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Invoice No</th>
                        <th>Account</th>
                        <th>Subsidiary</th>
                        <th>Control</th>
                        <th class="text-right">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transaction_items as $item)
                        <tr>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->invoice_no }}</td>
                            <td>{{ optional($item->account)->name }}</td>
                            <td>{{ optional(optional($item->account)->accountSubsidiary)->name }}</td>
                            <td>{{ optional(optional($item->account)->accountControl)->name }}</td>
                            <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No revenue transactions found for this period.</td></tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total ({{ $row_count }} entries)</th>
                        <th class="text-right">{{ number_format($total_amount, 2) }}</th>
                    </tr>
                    </tfoot>
                </table>

                {{ method_exists($transaction_items, 'links') ? $transaction_items->links() : '' }}
            </div>
        </div>
    </div>
@stop
