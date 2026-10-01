@extends('layouts.master')
@section('title','Attendance Device Integration')
@section('page-header')
    <i class="fa fa-gears"></i> Attendance Device Integration
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <style type="text/css">
        .bg-color{
            background-color: rgba(1,3,5,0.09);
        }
    </style>
@endpush


@section('content')

    <div class="row">
        <div class="col-sm-12">



                <div class="col-sm-12 widget-container-col ui-sortable" id="widget-container-col-7">
                    <div class="widget-box widget-color-grey ui-sortable-handle" id="widget-box-7">
                        <div class="widget-header widget-header-small">
                            <h5 class="widget-title smaller">
                                @yield('page-header')
                            </h5>
                            <div class="widget-toolbar">

                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">

                                @if(hasPermission('attendance.devices.create', $slugs))
                                <form action="{{ route('attendance-device.store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-sm-3">
                                                <div>
                                                    <label for="form-field-8" class="bolder">Device Name</label>
                                                    <input type="text" class="form-control input-sm" name="device_name" value="{{ old('device_name') }}" placeholder="Device Name">
                                                    @error('device_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div>
                                                    <label for="form-field-8" class="bolder">Device ID</label>
                                                    <input type="text" class="form-control input-sm" name="device_id" value="{{ old('device_id') }}" placeholder="Device ID">
                                                    @error('device_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div>
                                                    <label for="form-field-8" class="bolder">API URL</label>
                                                    <input type="text" class="form-control input-sm" name="api_url" value="{{ old('api_url') }}" placeholder="API URL">
                                                    @error('api_url')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div>
                                                    <label for="form-field-8" class="bolder">API KEY</label>
                                                    <input type="text" class="form-control input-sm" name="api_key" value="{{ old('api_key') }}" placeholder="API KEY">
                                                    @error('api_key')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <br>
                                            <div class="col-sm-3">
                                                <div>
                                                    <label for="form-field-8" class="bolder">API KEY NAME</label>
                                                    <input type="text" class="form-control input-sm" name="api_key_name" value="{{ old('api_key_name') }}" placeholder="API KEY NAME">
                                                    @error('api_key_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div>
                                                    <label for="form-field-8" class="bolder">Status</label>
                                                    <div class="radio">
                                                        <label>
                                                            <input name="status" type="radio" class="ace" value="1" checked>
                                                            <span class="lbl"> Active </span>
                                                        </label>
                                                        <label>
                                                            <input name="status" type="radio" class="ace" value="0" >
                                                            <span class="lbl"> De-active</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">

                                            </div>
                                            <div class="col-sm-3">
                                                <div class="space"></div>
                                                <div class="pull-right">
                                                    <button class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Save</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                                <div class="space"></div>
                                <hr>
                                @endif

                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-color">ID</th>
                                                <th class="bg-color">Device Name</th>
                                                <th class="bg-color">Device ID</th>
                                                <th class="bg-color">API URL</th>
                                                <th class="bg-color" width="20%">API KEY</th>
                                                <th class="bg-color">API KEY NAME</th>
                                                <th class="bg-color">Status</th>
                                                <th class="bg-color">Action</th>
                                            </tr>

                                            @foreach($attendanceDeviceInfos as $attendanceDeviceInfo)
                                                <tr>
                                                    <td>{{ $attendanceDeviceInfo->id }}</td>
                                                    <td>{{ $attendanceDeviceInfo->device_name }}</td>
                                                    <td>{{ $attendanceDeviceInfo->device_id }}</td>
                                                    <td>{{ $attendanceDeviceInfo->api_url }}</td>
                                                    <td>{{ $attendanceDeviceInfo->api_key }}</td>
                                                    <td>{{ $attendanceDeviceInfo->api_key_name }}</td>
                                                    <td>
                                                        @if ($attendanceDeviceInfo->status != 0)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-warning">De-Active</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-corner">
                                                            @if ($attendanceDeviceInfo->status != 0 && hasPermission('attendance.devices.active-inactive', $slugs))
                                                                <a href="{{ route('attendance-device.de-active',$attendanceDeviceInfo->id) }}" class="btn btn-warning btn-minier btn-corner" title="De-active"><i class="fa fa-thumbs-o-down"></i></a>
                                                            @elseif($attendanceDeviceInfo->status == 0 && hasPermission('attendance.devices.active-inactive', $slugs))
                                                                <a href="{{ route('attendance-device.active',$attendanceDeviceInfo->id) }}" class="btn btn-info btn-minier btn-corner" title="Active"><i class="fa fa-thumbs-o-up"></i></a>
                                                            @endif
                                                        </div>
                                                        <div class="btn-group btn-corner">
                                                            @if(hasPermission('attendance.devices.edit', $slugs))
                                                            <a href="{{ route('attendance-device.edit', $attendanceDeviceInfo->id) }}" class="btn btn-primary btn-minier btn-corner"><i class="fa fa-pencil-square-o"></i></a>
                                                            @endif

                                                            @if(hasPermission('attendance.devices.delete', $slugs))
                                                            <button type="button" onclick="delete_check({{ $attendanceDeviceInfo->id }})" class="btn btn-minier btn-danger" title="Delete">
                                                                <i class="fa fa-trash-o"></i>
                                                            </button>
                                                            @endif
                                                        </div>

                                                        @if(hasPermission('attendance.devices.delete', $slugs))
                                                        <form action="{{ route('attendance-device.destroy', $attendanceDeviceInfo->id) }}" id="deleteCheck_{{ $attendanceDeviceInfo->id }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>




                            </div>
                        </div>
                    </div>
                </div>







        </div>
    </div>

@endsection

@section('js')

    

    <script type="text/javascript">

        function delete_check(id)
        {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width:400,
            }).then((result) =>{
                if(result.value){
                    $('#deleteCheck_'+id).submit();
                }
            })

        }


    </script>

@endsection


