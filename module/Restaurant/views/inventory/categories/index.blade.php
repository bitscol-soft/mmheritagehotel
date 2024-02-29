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
    <div class="row">

        @include('inventory.categories.create-modal')

        @include('inventory.categories.edit-modal')


        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    @if (hasPermission('resturant.inventories.create', $slugs))
                        <span class="widget-toolbar">
                            <a href="#modal-dialog" data-toggle="modal">
                                <i class="fa fa-plus-circle"></i> Add New Category
                            </a>
                        </span>
                    @endif

                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        @include('partials._alert_message')

                        <div class="row">
                            <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th>Name</th>
                                        @if (setting('mother_inventory'))
                                            <th>Type</th>
                                        @endif
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
                                            @if (setting('mother_inventory'))
                                                <td>{{ $category->is_bar ? 'Bar' : 'Restaurant' }}</td>
                                            @endif
                                            <td>{{ status($category->status) }}</td>
                                            <td class="text-center">

                                                <div class="btn-group btn-corner">


                                                    @if (hasPermission('resturant.inventories.edit', $slugs))
                                                        <a href="#edit-modal"
                                                            onclick="editCategory(`{{ route('rst.product-categories.update', $category->id) }}`,{{ $category }})"
                                                            data-toggle="modal" class="btn btn-sm btn-success" title="Edit">
                                                            <i class="fa fa-pencil-square-o"></i>
                                                        </a>
                                                    @endif


                                                    @if (hasPermission('resturant.inventories.delete', $slugs))
                                                        <button type="button"
                                                            onclick="delete_item(`{{ route('rst.product-categories.destroy', $category->id) }}`)"
                                                            class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif


                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


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
