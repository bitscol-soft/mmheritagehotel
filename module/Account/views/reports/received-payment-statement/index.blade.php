@extends('layouts.master')

@section('title', 'Received Payment Statement')

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Received Payment Statement" description="Receipts and payments.">
    <x-slot name="actions">
        <h5 style="font-weight:600"><i class="fa fa-filter"></i> Received Payments — {{ $from }} → {{ $to }}</h5>
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form method="GET" class="mm-setup-filter mm-report-form form-inline" style="margin-bottom:12px">
            <input type="date" name="from" value="{{ $from }}" class="form-control input-sm" style="width:160px">
            <input type="date" name="to" value="{{ $to }}" class="form-control input-sm" style="width:160px;margin-left:6px">
            <button type="submit" class="btn btn-sm btn-primary" style="margin-left:6px"><i class="fa fa-search"></i> Filter</button>
        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">

        <x-mm.table-scroll label="Received Payment Statement">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Invoice No</th>
                    <th>Transaction No</th>
                    <th>Remarks</th>
                    <th class="text-right">Amount</th>
                </tr>
                </thead>
                <tbody>
                @forelse($collections as $collection)
                    <tr>
                        <td>{{ $collection->date }}</td>
                        <td>{{ $customers[$collection->customer_id] ?? '-' }}</td>
                        <td>{{ $collection->invoice_no }}</td>
                        <td>{{ $collection->transaction_no }}</td>
                        <td>{{ $collection->remarks }}</td>
                        <td class="text-right">{{ number_format($collection->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No payments received in this period.</td></tr>
                @endforelse
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="5" class="text-right">Total</th>
                    <th class="text-right">{{ number_format($total, 2) }}</th>
                </tr>
                </tfoot>
            </table>
        </x-mm.table-scroll>

        {{ method_exists($collections, 'links') ? $collections->links() : '' }}
    </x-mm.panel>
</x-mm.page>

@stop
