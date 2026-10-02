@extends('layouts.master')
@section('title', 'Category List')

@section('page-header')
    <i class="fa fa-bars"></i> Category List
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
@include('bar.inventory.categories.create-modal')
@include('bar.inventory.categories.edit-modal')
<x-mm.page class="mm-hotel-setup mm-bar mm-rst mm-rst-inv" title="Product categories" description="Categories used to group bar products.">
    <x-slot name="actions">
        @if (hasPermission('pharmacy.view', $slugs))
                <a class="mm-button" href="#modal-dialog" data-toggle="modal">
                    <i class="fa fa-plus-circle"></i> Add New Category
                </a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        <x-alert-message />

        <div class="row">
            <div class="col-sm-12">
                <x-mm.table-scroll label="Categories">
                    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th>Name</th>
                                <th width="10%">Status</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($sl = $categories->firstItem())
                            @foreach ($categories as $category)
                                <tr class="odd gradeX">
                                    <td>{{ $sl++ }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ status($category->status) }}</td>
                                    <td class="text-center">

                                        <div class="btn-group btn-corner">


                                            @if (hasPermission('pharmacy.edit', $slugs))
                                                <a href="#edit-modal"
                                                    onclick="editCategory(`{{ route('bar.product-categories.update', $category->id) }}`,{{ $category }})"
                                                    data-toggle="modal" class="btn btn-sm btn-success" title="Edit">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            @endif


                                            @if (hasPermission('pharmacy.delete', $slugs))
                                                <button type="button"
                                                    onclick="delete_item(`{{ route('bar.product-categories.destroy', $category->id) }}`)"
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
        function editCategory(url, item) {

            let category = JSON.parse(JSON.stringify(item));
            let status = category.status;

            $('.edit-name').val(category.name);
            $('.edit-parent-id').val(category.parent_id).trigger('chosen:updated');

            var $radios = $('input:radio[name=status]');
            $radios.filter('[value=' + status + ']').prop('checked', true);


            $('#editForm').attr('action', url)
        }
    </script>
@endsection
