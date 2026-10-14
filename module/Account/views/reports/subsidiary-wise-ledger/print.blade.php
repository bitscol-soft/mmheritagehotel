@extends('layouts.master')
@section('title', 'Ledger Journal')

@section('content')
    <x-mm.styles />

    <x-mm.print-sheet title="Subsidiary-wise Ledger">
        <x-mm.panel class="tw-p-4">
            <h4 style="background-color: #eee; padding: 12px; text-align: center">Account Ledger</h4>
            @if(optional($accountTransactions->first())->account)
                <h4 style="padding: 0; margin: 0; text-align: center">{{ optional(optional($accountTransactions->first())->account)->name }}</h4>
            @endif
            <h5 style="text-align: center;">Date From {{ fdate(request('from'),'d/m/Y') }} To {{ fdate(request('to'),'d/m/Y') }}</h5>

            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center', 'width' => '5%'],
                ['label' => 'Account'],
                ['label' => 'Total', 'align' => 'right'],
            ]" table-class="table table-bordered table-striped" label="Subsidiary-wise ledger">
                @php $balance_type = $accountTransactions->first()->balance_type; @endphp

                @foreach ($accountTransactions as $account)
                    <tr>
                        <th class="text-center" style="border-top: none; width: 5%">{{ $loop->iteration }}</th>
                        <th class="text-left pl-2" style="border-top: none">{{ $account->name }}</th>
                        @php
                            if ($balance_type == 'Debit') {
                                $total = ($account->transaction_items->sum('credit_amount') - $account->transaction_items->sum('debit_amount'));
                            } else {
                                $total = ($account->transaction_items->sum('debit_amount') - $account->transaction_items->sum('credit_amount'));
                            }
                        @endphp
                        <th style="border-top: none" class="text-right pr-2">
                            <strong style="font-size: 15px">{{ number_format($total) }}</strong>
                        </th>
                    </tr>

                    @if ($account->transaction_items->count())
                        <tr>
                            <td></td>
                            <td colspan="2">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th class="text-right pr-1">Dr.</th>
                                            <th class="text-right pr-1">Cr.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($account->transaction_items as $transaction)
                                            <tr>
                                                <td>{{ $transaction->date }}</td>
                                                <td>{{ $transaction->getDescription() }}</td>
                                                <td class="text-right pr-1">{{ number_format($transaction->credit_amount, 2) }}</td>
                                                <td class="text-right pr-1">{{ number_format($transaction->debit_amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3"></td>
                        </tr>
                    @endif
                @endforeach
            </x-mm.data-table>
        </x-mm.panel>
    </x-mm.print-sheet>
@endsection
