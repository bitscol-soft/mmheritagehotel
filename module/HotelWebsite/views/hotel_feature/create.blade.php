@extends('layouts.master')

@section('title',' Edit Feature Header')
@section('page-header')
<i class="fa fa-info-circle"></i> Homepage Feature Heading
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

                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>

                        <form class="form-horizontal" id="companyForm" action="{{ route('website-core.feature.update',$feature->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Feature Title</label>

                                        <div class="col-xs-12 col-sm-8 @error('feature_title') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="feature_title" value="{{ $feature->title ?? '' }}" placeholder="Enter Feature Title">

                                            @error('feature_title')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Feature Subtitle</label>

                                        <div class="col-xs-12 col-sm-8 @error('feature_subtitle') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="feature_subtitle" value="{{ $feature->sub_title ?? '' }}" placeholder="Enter Feature Subtitle">

                                            @error('feature_subtitle')
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
