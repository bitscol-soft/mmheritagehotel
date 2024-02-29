@extends('layouts.master')


@section('title',' Edit Feature')
@section('page-header')
<i class="fa fa-edit"></i> Edit Feature
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')

<div class="row">
    <div class="col-xs-6" style="margin-left: 25%; margin-top:100px;">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                </div>

                <div class="widget-body">
                    <div class="no-padding">

                        <x-alert-message />

                        <form class="form-horizontal" id="companyForm" action="{{ route('website-core.feature_list.update',$data->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Feature Title</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control input-sm" name="feature_list_title" value="{{ $data->title }}" placeholder="Enter Feature Title">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Feature Subtitle</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control input-sm" name="feature_list_subtitle" value="{{ $data->sub_title }}" placeholder="Enter Feature Subtitle">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Feature Icon (FontAwsome 4.7)</label>

                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control input-sm" name="feature_icon" value="{{ $data->feature_icon }}" placeholder="EX - fa fa-bed">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"> Status</label>

                                        <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                                            <select name="status">
                                                <option value="">Select Option</option>
                                                <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>In Active</option>
                                            </select>
                                            @error('status')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-actions center" style="text-align: right !important; margin: 0;">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                    Save
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection

@section('js')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

@stop
