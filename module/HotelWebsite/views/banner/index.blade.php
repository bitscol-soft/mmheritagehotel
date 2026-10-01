@extends('layouts.master')
@section('title','Banner List')
@section('page-header')
    <i class="fa fa-gears"></i> Banner List
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<div class="page-header">
    <a class="btn btn-xs btn-info" href="{{ route('website-core.banner.create') }}" style="float: right; margin: 0 2px;"> <i class="fa fa-plus"></i> Add Banner </a>
    <h1>
        <i class="fa fa-info-circle green"></i> Homepage Banner List
    </h1>
</div>

@include('partials._alert_message')

<div class="row">
    <div class="col-xs-12">

        <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
            <table id="data-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th class="text-center">Banner Head Title</th>
                        <th class="text-center">Banner Subtitle</th>
                        <th class="text-center">Banner Description</th>
                        <th class="text-center">Banner Image</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($data as $key => $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td class="text-center">{{ $item->banner_title }}</td>
                        <td class="text-center">{{ $item->banner_sub_title }}</td>
                        <td class="text-center">{{ $item->banner_short_desc }}</td>
                        <td class="text-center"><img height="50" src="{{ asset($item->banner_image) }}" alt=""></td>
                        <td class="text-center">
                            @if ($item->status == 1)
                                <span class="label label-success">Active</span>
                                @else
                                <span class="label label-danger">IN Active</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-corner">
                                <a href="{{ route('website-core.banner.edit',$item->id) }}" class="btn btn-xs btn-sm btn-success" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <button type="button" onclick="delete_item(`{{ route('website-core.banner.destroy', $item->id) }}`)" class="btn btn-xs btn-sm btn-danger" title="Delete">
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
