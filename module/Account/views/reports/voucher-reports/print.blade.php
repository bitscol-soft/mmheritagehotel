@extends('layouts.master')

@section('title', 'Voucher Reports')

@section('content')
    <x-mm.styles />

    <x-mm.print-sheet title="Voucher Reports">
        <x-mm.panel class="tw-p-4">
            <h4 style="background-color: #eee; padding: 12px; text-align: center">Voucher Reports</h4>
            <h5 style="text-align: center;">Date From {{fdate(request('from'), 'd/m/Y')}} To {{ fdate(request('to'), 'd/m/Y')}}</h5>

            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center'],
                ['label' => 'Date', 'align' => 'center'],
                ['label' => 'Voucher No', 'align' => 'center'],
                ['label' => 'Voucher Type', 'align' => 'center'],
                ['label' => 'Company'],
                ['label' => 'Description'],
                ['label' => 'Amount', 'align' => 'right'],
            ]" table-class="table table-bordered table-striped" label="Voucher reports">
                @foreach ($vouchers as $voucher)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $voucher->date }}</td>
                        <td class="text-center">{{ $voucher->invoice_no }}</td>
                        <td class="text-center">{{ $voucher->voucher_type }}</td>
                        <td class="pl-3">{{ optional($voucher->company)->name }}</td>
                        <td class="pl-3">{{ $voucher->description }}</td>
                        <td class="text-right pr-1">{{ number_format($voucher->amount, 2) }}</td>
                    </tr>
                @endforeach
                <x-slot name="footer">
                    <tr>
                        <th colspan="6">Total:</th>
                        <th class="text-right pr-1">{{ number_format($vouchers->sum('amount'), 2) }}</th>
                    </tr>
                </x-slot>
            </x-mm.data-table>
        </x-mm.panel>
    </x-mm.print-sheet>
@endsection
