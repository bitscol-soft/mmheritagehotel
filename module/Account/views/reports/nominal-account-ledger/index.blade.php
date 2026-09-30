@extends('layouts.master')

@section('title', 'Nominal Account Ledger')

@section('page-header')
    <i class="fa fa-book"></i> Nominal Account Ledger
@stop

@section('content')
    <div class="widget-box">
        <div class="widget-header">
            <h5 style="font-weight:600"><i class="fa fa-filter"></i> Nominal Accounts (Revenue &amp; Expense) — {{ $from }} → {{ $to }}</h5>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <form method="GET" class="form-inline" style="margin-bottom:12px">
                    <input type="date" name="from" value="{{ $from }}" class="form-control input-sm" style="width:160px">
                    <input type="date" name="to" value="{{ $to }}" class="form-control input-sm" style="width:160px;margin-left:6px">
                    <button type="submit" class="btn btn-sm btn-primary" style="margin-left:6px"><i class="fa fa-search"></i> Filter</button>
                </form>

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Invoice No</th>
                        <th>Account</th>
                        <th>Group</th>
                        <th class="text-right">Debit</th>
                        <th class="text-right">Credit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transaction_items as $item)
                        <tr>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->invoice_no }}</td>
                            <td>{{ optional($item->account)->name }}</td>
                            <td>{{ optional(optional($item->account)->accountGroup)->name }}</td>
                            <td class="text-right">{{ number_format($item->debit_amount, 2) }}</td>
                            <td class="text-right">{{ number_format($item->credit_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No nominal transactions found for this period.</td></tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Totals</th>
                        <th class="text-right">{{ number_format($debit_total, 2) }}</th>
                        <th class="text-right">{{ number_format($credit_total, 2) }}</th>
                    </tr>
                    </tfoot>
                </table>

                {{ method_exists($transaction_items, 'links') ? $transaction_items->links() : '' }}
            </div>
        </div>
    </div>
@stop
