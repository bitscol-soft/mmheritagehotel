@extends('layouts.master')
@section('title','Account Subsidiaries')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Account Subsidiaries" description="Subsidiary ledgers under each account.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{request()->url()}}"><i class="fa fa-refresh"></i> Refresh Data</a>
        @if ((hasPermission("account-subsidiaries.create", $slugs)))
            <a class="mm-button" href="{{route('account-subsidiaries.create')}}"><i class="fa fa-plus"></i> Create New</a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- LIST -->
            <x-mm.data-table :columns="[
                ['label' => 'Sl', 'align' => 'center', 'width' => '8%'],
                ['label' => 'Account Group', 'width' => '20%'],
                ['label' => 'Account Control', 'width' => '20%'],
                ['label' => 'Name'],
                ['label' => 'Status', 'align' => 'center', 'width' => '15%'],
                ['label' => 'Actions', 'align' => 'center', 'width' => '15%'],
            ]" id="data-table" table-class="table table-bordered table-striped" label="Account Subsidiaries">
                @foreach($accountSubsidiaries as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="pl-3">{{ optional($item->accountGroup)->name }}</td>
                        <td class="pl-3">{{ optional($item->accountControl)->name }}</td>
                        <td class="pl-3">{{ $item->name }}</td>
                        <td class="text-center">
                            {!! $item->status == 1 ? '<span class="label label-info">Active</span>' : '<span class="label label-warning">Inactive</span>' !!}
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-corner">
                                @include('partials._user-log', ['data' => $item])

                                @if($item->id > 39)
                                    @if ((hasPermission("account-subsidiaries.edit", $slugs)))
                                        <a href="{{route('account-subsidiaries.edit', $item->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i></a>
                                    @endif

                                    @if ((hasPermission("account-subsidiaries.delete", $slugs)))
                                        <a href="#" onclick="delete_item('{{ route('account-subsidiaries.destroy', $item->id) }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                    @endif
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

