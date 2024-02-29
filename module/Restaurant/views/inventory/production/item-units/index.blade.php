@php
$permitted_user = Auth::user();
$admin_id = $permitted_user->id;
$isPermitted = $permitted_user
    ->permissions()
    ->pluck('slug')
    ->toArray();

$canCreate = in_array('items.create', $isPermitted);
$canEdit = in_array('items.edit', $isPermitted);
$canDelete = in_array('items.delete', $isPermitted);
@endphp

@extends('layouts.master')
@section('title', 'Material Unit')
@section('page-header')
    <i class="fa fa-list"></i> Material Units
@stop
@section('css')

@stop


@section('content')

    <div class="page-header">
        @if ($canCreate || $admin_id == 1)
            <a class="btn btn-xs btn-info" href="{{ route('rst.material-unit.create') }}" style="float: right; margin: 0 2px;"> <i
                    class="fa fa-plus"></i> Add @yield('title') </a>
        @endif
        <h1>
            @yield('page-header')
        </h1>
    </div>

    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12 clear-fix">

            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Unit Name</th>
                            <th>Conversion</th>
                            <th>Satatus</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($item_units as $key => $item_unit)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item_unit->name }}</td>
                                <td>{{ $item_unit->conversion }}</td>
                                <td class="text-{{ $item_unit->status ? 'success' : 'danger' }}">
                                    {{ $item_unit->status ? 'Active' : 'Deactive' }}</td>
                                <td>
                                    @if ($canEdit || $admin_id == 1)
                                        <a href="{{ route('rst.material-unit.edit', $item_unit->id) }}"
                                            class="btn btn-sm btn-success" title="Edit">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                    @endif
                                    @if ($canDelete || $admin_id == 1)
                                        <button type="button" onclick="delete_check({{ $item_unit->id }})"
                                            class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif

                                    <form action="{{ route('rst.material-unit.destroy', $item_unit->id) }}"
                                        id="deleteCheck_{{ $item_unit->id }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @include('partials._paginate', ['data' => $item_units])
            </div>

            {{-- export/print/save --}}
            {{-- <div class="pull-right" style="margin-top:20px">
                <a href="" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/excel-icon.png') }}"
                        alt="excel"></a>
                <a href="" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}"
                        alt="pdf"></a>
                <a href="" style="margin-right: 5px"><img src="{{ asset('assets/images/export-icons/word-icon.png') }}"
                        alt="word"></a>
                <a class="btnPrint" href="{{ URL::to('gs-setup/print-item-unit') }}" style="margin-right: 5px"><img
                        src="{{ asset('assets/images/export-icons/printer-icon.png') }}" alt="print"></a>
            </div> --}}
        </div>
    </div>


@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>



    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        function delete_check(id) {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('#deleteCheck_' + id).submit();
                }
            })

        }
    </script>


    <script type="text/javascript">
        jQuery(function($) {
            $('#dynamic-table').DataTable({
                "ordering": false,
                "bPaginate": false,
                "lengthChange": false,
                "info": false
            });
        })
    </script>
@stop
