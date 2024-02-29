@extends('layouts.master')
@section('title', 'Hotel Gallery')
@section('page-header')
    <i class="fa fa-gears"></i> Add New Image
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
    <style>
        .checkbox label input[type=checkbox].ace+.lbl {
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

                    @if (hasPermission('suppliers.view', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('website-core.gallery.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i> Gallery List
                            </a>
                        </span>
                    @endif

                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('website-core.gallery.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf

                            <x-alert-message />


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="gallery_title">
                                                    Gallery Title
                                                </label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="text" id="gallery_title" name="gallery_title" placeholder="Enter Gallery Title" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="banner_head_title">
                                                    Gallery Image
                                                </label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <label class="text-danger">Upload Image file size (600 *
                                                        420)px
                                                    </label>
                                                    <input type="file" name="gallery_images" class="banner_photos" required>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                                <div class="col-xs-12 col-sm-12 text-right">
                                    <button class="btn btn-xs btn-success" type="submit">
                                        <i class="fa fa-save"></i>
                                        Save
                                    </button>
                                    <button class="btn btn-xs btn-gray" type="Reset">
                                        <i class="fa fa-refresh"></i>
                                        Reset
                                    </button>
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
            $('.banner_photos').ace_file_input({
                style: 'well',
                btn_choose: 'Upload Gallery Photos',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit

            }).on('change', function() {});
        });
    </script>

@endsection
