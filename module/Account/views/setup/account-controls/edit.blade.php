@extends('layouts.master')
@section('title','Account Controls')
@section('page-header')
    <i class="fa fa-list"></i> Account Control Edit
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush


@section('content')
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

        @include('partials._alert_message')

        <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{route('account-controls.index')}}"
                                   class="dt-button btn btn-white btn-info btn-bold" title="List" data-toggle="tooltip"
                                   tabindex="0" aria-controls="dynamic-table">
                                    <span>
                                        <i class="fa fa-list bigger-110"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space"></div>

                <!-- INPUTS -->
                <form action="{{route('account-controls.update', $accountControl->id)}}" method="post">
                    @csrf @method('put')
                    <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                        <div class="col-sm-12 px-4">
                            <!-- Account Group -->
                            @include('includes.inputs.option-select', ['edit_id' => $accountControl->account_group_id, 'modelVariable' => 'accountGroups'])

                            <!-- Name -->
                            <div class="form-group row">
                                <label class="col-sm-3 control-label" for="name"> <b>Control Name <sup class="text-danger">
                                            *</sup></b> </label>

                                <div class="col-sm-9">
                                    <input id="name" name="name" type="text" class="form-control input-sm" placeholder="Name" value="{{$accountControl->name}}">
                                </div>
                            </div>

                            <!-- Status -->
                            @include('includes.inputs.status', ['edit_id' => $accountControl->status])

                            <!-- Submit -->
                            <button class="btn btn-primary btn-sm pull-right"><i class="fa fa-edit"></i> Update</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    
    
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
@endsection


