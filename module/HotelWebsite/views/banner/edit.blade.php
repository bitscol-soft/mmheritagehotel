@extends('layouts.master')
@section('title','Edit Banner')
@section('page-header')
    <i class="fa fa-edit"></i> Edit Banner
@stop
@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
<style>
.checkbox label input[type=checkbox].ace+.lbl{
    margin-bottom: 10px;
}
</style>
@endpush

@section('content')
<div class="row">

    <div class="col-sm-12">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="widget-title"> @yield('page-header')</h4>

                @if (hasPermission("suppliers.view", $slugs))
                    <span class="widget-toolbar">
                        <a href="{{ route('website-core.banner.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> List
                        </a>
                    </span>
                @endif
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <form class="form-horizontal" action="{{ route('website-core.banner.update',$data->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <x-alert-message />


                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="banner_head_title"> Banner Head Title</label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <input type="text" id="banner_head_title" name="banner_head_title" placeholder="Enter Banner Title" class="form-control" value="{{ $data->banner_title }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="banner_sub_title"> Banner Sub Title</label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <input type="text" id="banner_sub_title" name="banner_sub_title" placeholder="Enter Banner Sub Title" class="form-control" value="{{ $data->banner_sub_title }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label"> Status</label>
    
                                            <div class="col-xs-12 col-sm-8 ">
                                                <select name="status">
                                                    <option value="">Select Option</option>
                                                    <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>In Active</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Banner Short Desc</label>
                                            <div class="col-xs-12 col-sm-8 @error('banner_short_desc') has-error @enderror">
                                                <textarea name="banner_short_desc" rows="5" class="form-control" placeholder="Enter Description">{{ $data->banner_short_desc }}</textarea>

                                                @error('details')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Select Photos</label>
                                        <input type="file" name="banner_photos" class="category_photos">
                                        <img style="width: 577px;" src="{{ asset($data->banner_image) }}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                            <div class="col-xs-12 col-sm-12 text-right">
                                <button class="btn btn-xs btn-success" type="submit"> <i class="fa fa-save"></i> Save</button>
                                <button class="btn btn-xs btn-gray" type="Reset"> <i class="fa fa-refresh"></i> Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
  <!--Drag and drop-->
  <script type="text/javascript">
    jQuery(function($) {
        $('.category_photos').ace_file_input({
            style: 'well',
            btn_choose: 'Upload Banner Photos',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
    });
</script>

@endsection
