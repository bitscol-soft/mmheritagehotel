@extends('layouts.master')
@section('title', 'Add New Banner')
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
<x-mm.page class="mm-web mm-room-form" title="Add a banner" description="Title, description, status and image of a home page slide.">
    @if (hasPermission('suppliers.view', $slugs))
        <x-slot name="actions">
            <a href="{{ route('website-core.banner.index') }}" class="mm-button mm-button-secondary">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Banner List
            </a>
        </x-slot>
    @endif
    <x-mm.panel class="tw-p-5">
        <form class="form-horizontal" action="{{ route('website-core.banner.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf

            @include('partials._alert_message')

            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="banner_head_title"> Banner Head
                                    Title</label>
                                <div class="col-xs-12 col-sm-8 ">
                                    <input type="text" id="banner_head_title" name="banner_head_title"
                                        placeholder="Enter Banner Title" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="banner_sub_title"> Banner Sub
                                    Title</label>
                                <div class="col-xs-12 col-sm-8 ">
                                    <input type="text" id="banner_sub_title" name="banner_sub_title"
                                        placeholder="Enter Banner Sub Title" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"> Status</label>
        
                                <div class="col-xs-12 col-sm-8 ">
                                    <select name="status">
                                        <option>Select Option</option>
                                        <option value="1">Active</option>
                                        <option value="2">In Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Banner Short Desc</label>
                                <div
                                    class="col-xs-12 col-sm-8 @error('banner_short_desc') has-error @enderror">
                                    <textarea name="banner_short_desc" rows="5" class="form-control"
                                                        placeholder="Enter Description">{{ old('banner_short_desc') }}</textarea>

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
                            <input type="file" name="banner_photos" class="banner_photos">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                <div class="col-xs-12 col-sm-12 text-right">
                    <button class="mm-button" type="submit"> <i class="fa fa-save"></i>
                        Save</button>
                    <button class="mm-button mm-button-secondary" type="Reset"> <i class="fa fa-refresh"></i>
                        Reset</button>
                </div>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>
@endsection
@section('js')
    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
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

@endsection
