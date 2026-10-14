@extends('layouts.master')

@section('title', 'Nominal Account Ledger')

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Nominal Account Ledger" description="Nominal accounts (revenue and expense) for the period.">
        <x-mm.panel class="mm-report-filter">
            <form method="GET" class="mm-setup-filter mm-report-form form-inline" style="margin-bottom:12px">
                <input type="date" name="from" value="{{ $from }}" class="form-control input-sm" style="width:160px">
                <input type="date" name="to" value="{{ $to }}" class="form-control input-sm" style="width:160px;margin-left:6px">
                <button type="submit" class="btn btn-sm btn-primary" style="margin-left:6px"><i class="fa fa-search"></i> Filter</button>
            </form>
        </x-mm.panel>
        <x-mm.panel class="tw-p-4">
            <p class="text-muted"><i class="fa fa-filter"></i> Nominal Accounts (Revenue &amp; Expense) — {{ $from }} → {{ $to }}</p>

            <x-mm.table-scroll label="Nominal Account Ledger">
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
            </x-mm.table-scroll>

            {{ method_exists($transaction_items, 'links') ? $transaction_items->links() : '' }}
        </x-mm.panel>
    </x-mm.page>
@stop
