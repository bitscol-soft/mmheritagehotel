@extends('layouts.master')
@section('title','About Section')
@section('page-header')
    <i class="fa fa-gears"></i> About Heading Section
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
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <form class="form-horizontal" action="{{ route('website-core.about_section.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        @include('partials._alert_message')


                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="heading_title"> Heading Title</label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <input type="text" id="heading_title" name="heading_title" placeholder="Enter Title" class="form-control" value="{{ $about->about_heading }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Heading Description</label>
                                            <div class="col-xs-12 col-sm-8 @error('heading_description') has-error @enderror">
                                                <textarea name="heading_description" rows="5" class="form-control" placeholder="Enter Description">{{ $about->about_description }}</textarea>

                                                @error('details')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="short_box_heading">Short Box Heading</label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <input type="text" id="short_box_heading" name="short_box_heading" placeholder="Enter Box Heading" class="form-control" value="{{ $about->offer_title }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="short_box_desc">Short Box Description</label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <input type="text" id="short_box_desc" name="short_box_desc" placeholder="Enter Box Description" class="form-control" value="{{ $about->offer_description }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="short_box_desc">Section First Image</label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <div class="img-box">
                                                    <img height="100" src="{{ asset($about->first_image ?? '') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="short_box_desc">Section Second Image</label>
                                            <div class="col-xs-12 col-sm-8 ">
                                                <div class="img-box">
                                                    <img height="100" src="{{ asset($about->second_image ?? '') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Section First Image</label>
                                        <div class="text-danger">*Upload size should be (1140 x 455)px</div>
                                        <input type="file" name="first_image" class="category_photos">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">Section Second Image</label>
                                        <div class="text-danger">*Upload size should be (600 x 420)px</div>
                                        <input type="file" name="second_image" class="category_photos">
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
            btn_choose: 'Upload Section Photos',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
    });
</script>

@endsection
