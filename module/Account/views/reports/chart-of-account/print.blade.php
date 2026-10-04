@extends('layouts.master')
@section('title','Chart Of Account')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush

@section('content')
    <x-mm.styles />

    <x-mm.print-sheet title="Chart Of Account">
        <x-mm.panel class="tw-p-4">
            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center'],
                ['label' => 'Opening Date', 'align' => 'center'],
                ['label' => 'Name'],
                ['label' => 'Balance Type', 'align' => 'center'],
                ['label' => 'Balance', 'align' => 'right'],
            ]" table-class="table table-bordered table-striped" label="Chart of account">
                @foreach($accounts as $account)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ fdate($account->created_at) }}</td>
                        <td class="pl-1">{{ $account->name }}</td>
                        <td class="text-center">{{ $account->balance_type }}</td>
                        <td class="text-right pr-1">{{ number_format($account->balance, 2) }}</td>
                    </tr>
                @endforeach
            </x-mm.data-table>
        </x-mm.panel>
    </x-mm.print-sheet>
@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>
@endsection
