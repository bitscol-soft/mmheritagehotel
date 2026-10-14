@extends('layouts.master')
@section('title', 'Update Page')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
    <style>
        .checkbox label input[type=checkbox].ace+.lbl {
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-form" title="Edit page" description="Update the page content.">
    @if (hasPermission('suppliers.view', $slugs))
        <x-slot name="actions">
            <a href="{{ route('website-core.pages.index') }}" class="mm-button mm-button-secondary">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Page List
            </a>
        </x-slot>
    @endif
    <x-mm.panel class="tw-p-5">
        <form class="form-horizontal" action="{{ route('website-core.pages.update', $page->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('partials._alert_message')

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="title">Title</label>
                                <div class="col-xs-12 col-sm-8 ">
                                    <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}" placeholder="Enter Page Title" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="sub_title">Sub Title</label>
                                <div class="col-xs-12 col-sm-8 ">
                                    <input type="text" id="sub_title" name="sub_title" value="{{ old('sub_title', $page->sub_title) }}" placeholder="Enter Page Sub Title" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Image</label>
                                <div class="col-xs-12 col-sm-8 @error('image') has-error @enderror">
                                    <input type="file" class="form-control-file" name="image" id="">
                                    <img src="{{ asset($page->image) }}"  style="height: 80px; width: 150px" alt="">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Short Desc</label>
                                <div class="col-xs-12 col-sm-8 @error('short_description') has-error @enderror">
                                    <textarea name="short_description" rows="5" class="form-control" id="editor1" placeholder="Enter Short Description">{{ old('short_description', $page->short_description) }}</textarea>
                                    @error('short_description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Description</label>
                                <div class="col-xs-12 col-sm-8 @error('description') has-error @enderror">
                                    <textarea name="description" rows="5" class="form-control editor" id="editor" placeholder="Enter Description">{{ old('description', $page->description) }}</textarea>

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
                    <button class="mm-button" type="submit"><i class="fa fa-save"></i>Save</button>
                    <a href="{{ url()->current() }}" class="mm-button mm-button-secondary" type="Reset"><i class="fa fa-refresh"></i>Reset</a>
                </div>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>
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
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor1'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>

@endsection
