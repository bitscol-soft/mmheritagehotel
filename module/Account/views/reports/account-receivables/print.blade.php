@extends('layouts.master')

@section('title', 'Account Receivable')

@section('content')
    <x-mm.styles />

    <x-mm.print-sheet title="Account Receivable">
        <x-mm.panel class="tw-p-4">
            <h4 style="background-color: #eee; padding: 12px; text-align: center">Account Receivable</h4>

            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center'],
                ['label' => 'Account Name'],
                ['label' => 'Balance', 'align' => 'right'],
            ]" table-class="table table-bordered table-striped" label="Account receivable">
                @foreach ($transactions as $account)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $account->name }}</td>
                        <td class="text-right pr-1">{{ number_format($account->balance, 2) }}</td>
                    </tr>
                @endforeach
                <x-slot name="footer">
                    @if(count($transactions) > 0)
                        <tr>
                            <th class="text-right" colspan="2"><strong>Total=</strong></th>
                            <th class="text-right pr-1"><strong>{{ number_format($transactions->sum('balance'), 2) }}</strong></th>
                        </tr>
                    @endif
                </x-slot>
            </x-mm.data-table>
        </x-mm.panel>
    </x-mm.print-sheet>
@endsection
