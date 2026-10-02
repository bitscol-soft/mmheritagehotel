@extends('layouts.master')
@section('title', 'Table List')

@section('page-header')
    <i class="fa fa-bars"></i> Table List
@stop

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
@include('bar.tables.create-modal')
@include('bar.tables.edit-modal')
<x-mm.page class="mm-hotel-setup mm-bar mm-rst mm-rst-inv" title="Tables" description="Bar tables and their seating.">
    <x-slot name="actions">
        @if (hasPermission('bar.table-manages.create', $slugs))
                <a class="mm-button" href="#modal-dialog" data-toggle="modal">
                    <i class="fa fa-plus-circle"></i> Add New Table
                </a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        <x-alert-message />

        <div class="row">
            <div class="col-sm-12">
                <x-mm.table-scroll label="Tables">
                    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th>Name</th>
                                <th>No</th>
                                <th width="10%">Status</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($sl = $table_manages->firstItem())
                            @foreach ($table_manages as $item)
                                <tr class="odd gradeX">
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->table_no }}</td>
                                    <td>{{ status($item->status) }}</td>
                                    <td class="text-center">

                                        <div class="btn-group btn-corner">

                                            @if (hasPermission('bar.table-manages.edit', $slugs))
                                                <a href="#edit-modal"
                                                    onclick="editTable(`{{ route('bar.table-manages.update', $item->id) }}`,{{ $item }})"
                                                    data-toggle="modal" class="btn btn-sm btn-success" title="Edit">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            @endif


                                            @if (hasPermission('bar.table-manages.delete', $slugs))
                                                <button type="button"
                                                    onclick="delete_item(`{{ route('bar.table-manages.destroy', $item->id) }}`)"
                                                    class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif


                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-mm.table-scroll>
            </div>
        </div>
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
