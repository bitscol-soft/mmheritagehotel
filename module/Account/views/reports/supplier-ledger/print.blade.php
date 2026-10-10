@extends('layouts.master')
@section('title','Supplier Ledger')

@section('content')
    <x-mm.styles />

    <x-mm.print-sheet title="Supplier Ledger">
        <x-mm.panel class="tw-p-4">
            <h4 style="background-color: #eee; padding: 12px; text-align: center">Customer Ledger</h4>
            <h5 style="text-align: center;">Date From {{fdate(request('from'),'d/m/Y')}} To {{fdate(request('to'),'d/m/Y')}}</h5>

            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center'],
                ['label' => 'Date', 'align' => 'center'],
                ['label' => 'Voucher No', 'align' => 'center'],
                ['label' => 'Description'],
                ['label' => 'Dr.', 'align' => 'right'],
                ['label' => 'Cr.', 'align' => 'right'],
                ['label' => 'Balance', 'align' => 'right'],
            ]" table-class="table table-bordered table-striped" label="Supplier ledger">
                @if(request('account_id'))
                    @php
                        if ($selected_account->accountGroup->balance_type == 'Debit') {
                            $balance = ($debit_balance + $paginate_debit_balance) - ($credit_balance + $paginate_credit_balance);
                        } else {
                            $balance = ($credit_balance + $paginate_credit_balance) - ($debit_balance + $paginate_debit_balance);
                        }
                    @endphp

                    <tr>
                        <td class="text-left pl-3" colspan="6">Opening Balance</td>
                        <td class="text-right pr-1">{{ number_format($balance, 2) }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="7" style="font-size: 16px" class="text-center text-danger">NO RECORDS FOUND!</td>
                    </tr>
                @endif

                @php
                    $totalDebit = 0;
                    $totalCredit = 0;
                @endphp

                @foreach($transactions as $transaction)
                    @php
                        $totalDebit += $debit = $transaction->amount < 0 ? abs($transaction->amount) : 0;
                        $totalCredit += $credit = $transaction->amount >= 0 ? $transaction->amount : 0;
                        $balance += $transaction->amount;
                    @endphp

                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $transaction->date }}</td>
                        <td class="text-center">{{ $transaction->invoice_no }}</td>
                        <td class="pl-3"></td>
                        <td class="text-right pr-1">{{ $debit }}</td>
                        <td class="text-right pr-1">{{ $credit }}</td>
                        <td class="text-right pr-1">{{ $balance }}</td>
                    </tr>
                @endforeach

                <x-slot name="footer">
                    <tr>
                        <th colspan="4">Total:</th>
                        <th class="text-right pr-1">{{$totalDebit}}</th>
                        <th class="text-right pr-1">{{$totalCredit}}</th>
                        <th></th>
                    </tr>
                </x-slot>
            </x-mm.data-table>
        </x-mm.panel>
    </x-mm.print-sheet>
@endsection
