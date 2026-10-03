@extends('layouts.master')

@section('title',' Edit Feature')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-form" title="Edit feature" description="Update the feature box.">
    <x-alert-message />

    <x-mm.panel class="tw-p-5 mm-web-narrow">
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
