@extends('layouts.master')
@section('title','Feature List')
@section('page-header')
    <i class="fa fa-gears"></i> Add New Aminities
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<div class="page-header">
    <a class="btn btn-xs btn-info" href="{{ route('website-core.feature_list.create') }}" style="float: right; margin: 0 2px;"> <i class="fa fa-plus"></i> Add Feature List </a>
    <h1>
        <i class="fa fa-info-circle green"></i> Homepage Feature List
    </h1>
</div>

<x-alert-message />

<div class="row">
    <div class="col-xs-12">

        <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
            <table id="data-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th class="text-center">Feature Title</th>
                        <th class="text-center">Feature Subtitle</th>
                        <th class="text-center">Feature Icon</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($feature_list as $key => $data)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td class="text-center">{{ $data->title }}</td>
                        <td class="text-center">{{ $data->sub_title }}</td>
                        <td class="text-center">{{ $data->feature_icon }}</td>
                        <td class="text-center">
                            @if ($data->status == 1)
                                <span class="label label-success">Active</span>
                                @else
                                <span class="label label-danger">IN Active</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-corner">
                                <a href="{{ route('website-core.feature_list.edit',$data->id) }}" class="btn btn-xs btn-sm btn-success" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <button type="button" onclick="delete_item(`{{ route('website-core.feature_list.destroy', $data->id) }}`)" class="btn btn-xs btn-sm btn-danger" title="Delete">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </div>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
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
@stop
