@extends('layouts.master')
@section('title','Hotel Gallery')
@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
<style>
.checkbox label input[type=checkbox].ace+.lbl{
    margin-bottom: 10px;
}
</style>
@endpush

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-form" title="Edit image" description="Update the gallery image details.">
    @if (hasPermission("suppliers.view", $slugs))
        <x-slot name="actions">
            <a href="{{ route('website-core.gallery.index') }}" class="mm-button mm-button-secondary">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Gallery List
            </a>
        </x-slot>
    @endif
    <x-mm.panel class="tw-p-5">
        <form class="form-horizontal" action="{{ route('website-core.gallery.update',$gallery->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <x-alert-message />

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="gallery_title">Title</label>
                                <div class="col-xs-12 col-sm-8 ">
                                    <input type="text" id="gallery_title" name="gallery_title" placeholder="Enter Gallery Title" class="form-control" value="{{ $gallery->gallery_text }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="banner_head_title">Image</label>
                                <div class="col-xs-12 col-sm-8 ">
                                    <label class="text-danger">Upload Image file size (600 * 420)px</label>
                                    <input type="file" name="gallery_images" class="category_photos" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                <div class="col-xs-12 col-sm-12 text-right">
                    <button class="mm-button" type="submit"> <i class="fa fa-save"></i> Save</button>
                    <button class="mm-button mm-button-secondary" type="Reset"> <i class="fa fa-refresh"></i> Reset</button>
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
        $('.category_photos').ace_file_input({
            style: 'well',
            btn_choose: 'Upload Gallery Photos',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
    });
</script>

@endsection
