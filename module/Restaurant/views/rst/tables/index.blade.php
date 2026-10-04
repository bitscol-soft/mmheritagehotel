@extends('layouts.master')
@section('title', 'Table List')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

    </style>
@stop

@section('content')

<x-mm.styles />
@include('rst.tables.create-modal')

@include('rst.tables.edit-modal')

<x-mm.page class="mm-hotel-setup mm-rst" title="Tables" description="Dining tables used when taking restaurant orders.">
    @if (hasPermission('pharmacy.view', $slugs))
        <x-slot name="actions">
            <a class="mm-button" href="#modal-dialog" data-toggle="modal">
                <i class="fa fa-plus-circle"></i> Add New Table
            </a>
        </x-slot>
    @endif

    @include('partials._alert_message')

    <x-mm.panel>
        <x-mm.data-table :columns="[
            ['label' => 'Sl', 'width' => '5%'],
            ['label' => 'Name'],
            ['label' => 'No'],
            ['label' => 'Status', 'width' => '10%'],
            ['label' => 'Action', 'width' => '10%', 'align' => 'center'],
        ]" id="data-table" label="Tables" table-class="table table-striped table-bordered nowrap">
            @php($sl = $table_manages->firstItem())
            @forelse ($table_manages as $item)
                <tr class="odd gradeX">
                    <td>{{ $sl++ }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->table_no }}</td>
                    <td>{{ status($item->status) }}</td>
                    <td class="text-center">

                        <div class="btn-group btn-corner">

                            @if (hasPermission('pharmacy.edit', $slugs))
                                <a href="#edit-modal"
                                    onclick="editTable(`{{ route('rst.table-manages.update', $item->id) }}`,{{ $item }})"
                                    data-toggle="modal" class="btn btn-sm btn-success" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                            @endif

                            @if (hasPermission('pharmacy.delete', $slugs))
                                <button type="button"
                                    onclick="delete_item(`{{ route('rst.table-manages.destroy', $item->id) }}`)"
                                    class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            @endif

                        </div>
                    </td>

                </tr>
            @empty
                <x-no-table-record />
            @endforelse
        </x-mm.data-table>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

    <script>
        function editTable(url, item) {

            let $item = JSON.parse(JSON.stringify(item));
            let status = $item.status;

            $('.edit-name').val($item.name);
            $('.edit-table-no').val($item.table_no);

            var $radios = $('input:radio[name=status]');
            $radios.filter('[value=' + status + ']').prop('checked', true);

            $('#editForm').attr('action', url)
        }
    </script>
@endsection
