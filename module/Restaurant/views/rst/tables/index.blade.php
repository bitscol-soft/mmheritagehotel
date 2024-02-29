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
    <div class="row">

        @include('rst.tables.create-modal')

        @include('rst.tables.edit-modal')


        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    @if (hasPermission('pharmacy.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="#modal-dialog" data-toggle="modal">
                                <i class="fa fa-plus-circle"></i> Add New Table
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
