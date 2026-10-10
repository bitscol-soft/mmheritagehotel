@extends('layouts.master')
@section('title', 'Suppliers')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Suppliers" description="Suppliers and their contact details.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ route('acc-suppliers.index') }}"><i class="fa fa-refresh"></i> Refresh</a>
        <a class="mm-button" href="{{ route('acc-suppliers.create') }}"><i class="fa fa-plus"></i> Create</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- LIST -->
            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center', 'width' => '8%'],
                ['label' => 'Name'],
                ['label' => 'Mobile'],
                ['label' => 'Email'],
                ['label' => 'Opening Banalce', 'align' => 'right'],
                ['label' => 'Actions', 'align' => 'center', 'width' => '15%'],
            ]" id="data-table" table-class="table table-bordered table-striped" label="Suppliers">
                @foreach($suppliers as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="pl-3">{{ $item->name }}</td>
                        <td class="pl-3">{{ $item->mobile }}</td>
                        <td class="pl-3">{{ $item->email }}</td>
                        <td class="pl-3 text-right">{{ number_format($item->opening_balance, 2) }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-corner">
                                @include('partials._user-log', ['data' => $item])

                                @if ((hasPermission("account-suppliers.edit", $slugs)))
                                    <a href="{{route('acc-suppliers.edit', $item->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i></a>
                                @endif

                                @if ((hasPermission("account-suppliers.delete", $slugs)))
                                    <a href="#" onclick="delete_item('{{ route('acc-suppliers.destroy', $item->id) }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-mm.data-table>
    </x-mm.panel>
</x-mm.page>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
@endsection

