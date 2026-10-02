@extends('layouts.master')

@section('title', 'Revenue Analysis')

@section('page-header')
    <i class="fa fa-line-chart"></i> Revenue Analysis
@stop

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Revenue Analysis" description="Revenue by account.">
    <x-slot name="actions">
        <h5 style="font-weight:600"><i class="fa fa-filter"></i> Revenue Analysis ({{ $from }} → {{ $to }})</h5>
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form method="GET" class="mm-setup-filter mm-report-form form-inline" style="margin-bottom:12px">
            <input type="date" name="from" value="{{ $from }}" class="form-control input-sm" style="width:160px">
            <input type="date" name="to" value="{{ $to }}" class="form-control input-sm" style="width:160px;margin-left:6px">
            <button type="submit" class="btn btn-sm btn-primary" style="margin-left:6px"><i class="fa fa-search"></i> Filter</button>
            <a href="{{ request()->fullUrlWithQuery(['print' => 1]) }}" class="btn btn-sm btn-default" style="margin-left:6px" target="_blank"><i class="fa fa-print"></i> Print</a>
        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">

        <x-mm.table-scroll label="Revenue Analysis">
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
        </x-mm.table-scroll>

        {{ method_exists($transaction_items, 'links') ? $transaction_items->links() : '' }}
    </x-mm.panel>
</x-mm.page>

@stop
