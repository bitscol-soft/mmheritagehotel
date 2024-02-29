@extends('layouts.master')
@section('title', 'Add New Page')
@section('page-header')
    <i class="fa fa-gears"></i> Add New Page
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
                            <a href="{{ route('website-core.pages.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i> Page List
                            </a>
                        </span>
                    @endif

                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('website-core.pages.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf

                            @include('partials._alert_message')


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="title">Title</label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="text" id="title" name="title"
                                                        placeholder="Enter Page Title" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="sub_title">Sub Title</label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="text" id="sub_title" name="sub_title"
                                                        placeholder="Enter Page Sub Title" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Image</label>
                                                <div class="col-xs-12 col-sm-8 @error('image') has-error @enderror">
                                                    <input type="file" class="form-control-file" name="image" id="">
                                                    @error('image')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Short Desc</label>
                                                <div
                                                    class="col-xs-12 col-sm-8 @error('short_description') has-error @enderror">
                                                    <textarea name="short_description" rows="5" class="form-control" id="editor1"
                                                        placeholder="Enter Short Description">{{ old('short_description') }}</textarea>

                                                    @error('short_description')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Description</label>
                                                <div
                                                    class="col-xs-12 col-sm-8 @error('description') has-error @enderror">
                                                    <textarea name="description" rows="5" class="form-control editor" id="editor"
                                                        placeholder="Enter Description">{{ old('description') }}</textarea>

                                                    @error('description')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">


                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                                <div class="col-xs-12 col-sm-12 text-right">
                                    <button class="btn btn-xs btn-success" type="submit"> <i class="fa fa-save"></i>
                                        Save</button>
                                    <button class="btn btn-xs btn-gray" type="Reset"> <i class="fa fa-refresh"></i>
                                        Reset</button>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
    <!--Drag and drop-->
    <script type="text/javascript">
        jQuery(function($) {
            $('.banner_photos').ace_file_input({
                style: 'well',
                btn_choose: 'Upload Banner Photos',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit

            }).on('change', function() {});
        });
    </script>
    <script>
        ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .then( editor => {
                        console.log( editor );
                } )
                .catch( error => {
                        console.error( error );
                } );
    </script>
    <script>
        ClassicEditor
                .create( document.querySelector( '#editor1' ) )
                .then( editor => {
                        console.log( editor );
                } )
                .catch( error => {
                        console.error( error );
                } );
    </script>

@endsection
