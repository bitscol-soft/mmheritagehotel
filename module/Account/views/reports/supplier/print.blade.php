@extends('layouts.master')
@section('title','Supplier Report')

@section('content')
    <x-mm.styles />

    <x-mm.print-sheet title="Supplier Report">
        <x-mm.panel class="tw-p-4">
            <h4 style="background-color: #eee; padding: 12px; text-align: center">Supplier Report</h4>
            <h5 style="text-align: center;">Date From {{fdate(request('from'),'d/m/Y')}} To {{fdate(request('to'),'d/m/Y')}}</h5>

            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center'],
                ['label' => 'Date', 'align' => 'center'],
                ['label' => 'Voucher No', 'align' => 'center'],
                ['label' => 'Description'],
                ['label' => 'Dr.', 'align' => 'right'],
                ['label' => 'Cr.', 'align' => 'right'],
                ['label' => 'Balance', 'align' => 'right'],
            ]" table-class="table table-bordered table-striped" label="Supplier report">
                @if(request('account_id'))
                    <tr>
                        <td class="text-left pl-3" colspan="6">Opening Balance</td>
                        <td class="text-right pr-1">{{ $balance }}</td>
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

                @foreach($purchases as $purchase)
                    @php
                        $totalDebit += $debit = $purchase->amount < 0 ? abs($purchase->amount) : 0;
                        $totalCredit += $credit = $purchase->amount >= 0 ? $purchase->amount : 0;
                        $balance += $purchase->amount;
                    @endphp

                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $purchase->date }}</td>
                        <td class="text-center">{{ $purchase->invoice_no }}</td>
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
