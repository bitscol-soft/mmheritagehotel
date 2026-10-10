@extends('layouts.master')
@section('title','Attendance Device Integration')
@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <style type="text/css">
        .bg-color{
            background-color: rgba(1,3,5,0.09);
        }
    </style>
@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-crud-form" title="Attendance Device Integration">
        <x-mm.panel>
                                <form action="{{ route('attendance-device.update', $attendanceDevice->id) }}" method="post">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="col-sm-3">
                                                <div>
                                                    <label for="form-field-8" class="bolder">Device Name</label>
                                                    <input type="text" class="form-control input-sm" name="device_name" value="{{ old('device_name') ?: $attendanceDevice->device_name }}" placeholder="Device Name">
                                                    @error('device_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div>
                                                    <label for="form-field-8" class="bolder">Device ID</label>
                                                    <input type="text" class="form-control input-sm" name="device_id" value="{{ old('device_id') ?: $attendanceDevice->device_id }}" placeholder="Device ID">
                                                    @error('device_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div>
                                                    <label for="form-field-8" class="bolder">API URL</label>
                                                    <input type="text" class="form-control input-sm" name="api_url" value="{{ old('api_url') ?: $attendanceDevice->api_url }}" placeholder="API URL">
                                                    @error('api_url')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div>
                                                    <label for="form-field-8" class="bolder">API KEY</label>
                                                    <input type="text" class="form-control input-sm" name="api_key" value="{{ old('api_key') ?: $attendanceDevice->api_key }}" placeholder="API KEY">
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
                                                    <input type="text" class="form-control input-sm" name="api_key_name" value="{{ old('api_key_name') ?: $attendanceDevice->api_key_name }}" placeholder="API KEY NAME">
                                                    @error('api_key_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-3">

                                            </div>
                                            <div class="col-sm-3">

                                            </div>
                                            <div class="col-sm-3">
                                                <div class="space"></div>
                                                <div class="pull-right">
                                                    <button class="btn btn-sm btn-primary"><i class="fa fa-pencil-square-o"></i> Update</button>
                                                    <a href="{{ route('attendance-device.index') }}" class="btn btn-sm btn-info"><i class="fa fa-backward"></i> Cancel</a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </form>
        </x-mm.panel>
    </x-mm.page>

@endsection

@section('js')

    

@endsection


