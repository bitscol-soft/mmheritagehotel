@extends('layouts.master')


@section('title',' Edit Feature List')
@section('page-header')
<i class="fa fa-gears"></i> Homepage Feature List
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

                        <form class="form-horizontal" id="companyForm" action="{{ route('website-core.feature_list.store') }}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Feature Title</label>

                                        <div class="col-xs-12 col-sm-8 @error('feature_list_title') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="feature_list_title" value="" placeholder="Enter Feature Title">

                                            @error('feature_list_title')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Feature Subtitle</label>

                                        <div class="col-xs-12 col-sm-8 @error('feature_list_subtitle') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="feature_list_subtitle" value="" placeholder="Enter Feature Subtitle">

                                            @error('feature_list_subtitle')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Feature Icon (FontAwsome 4.7)</label>

                                        <div class="col-xs-12 col-sm-8 @error('feature_icon') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="feature_icon" value="" placeholder="EX - fa fa-bed">

                                            @error('feature_icon')
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
