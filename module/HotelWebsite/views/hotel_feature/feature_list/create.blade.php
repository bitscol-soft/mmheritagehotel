@extends('layouts.master')

@section('title',' Edit Feature List')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-form" title="Add a feature" description="Add a feature box to the home page.">
    <x-alert-message />

    <x-mm.panel class="tw-p-5 mm-web-narrow">
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
                <button type="submit" class="mm-button">
                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                    Save
                </button>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>
@endsection

@section('js')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

@stop
