@extends('layouts.master')
@section('title','Privacy Policy')
@section('page-header')
    <i class="fa fa-gears"></i> Privacy Policy
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
<x-mm.styles />
<x-mm.page class="mm-web mm-room-form" title="Privacy policy" description="Text of the website privacy policy page.">
    <x-mm.panel class="tw-p-5">
        <form class="form-horizontal" action="{{ route('website-core.privacy_poilicy.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <x-alert-message />

            {{-- Privacy & Policy Form --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="privacy_header">Privacy & Policy Header</label>
                                <div class="col-xs-12 col-sm-8 ">
                                    <input type="text" id="privacy_header" name="privacy_header" placeholder="Enter Privacy Header Title" class="form-control" value="{{ $our_privacy->privacy_header_title ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="privacy_details">Privacy & Policy Details</label>
                                <div class="col-xs-12 col-sm-8 ">
                                    <textarea name="privacy_details" class="form-control editor" id="editor" cols="30" rows="10">{{ $our_privacy->privacy_policy ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Terms & Condition Form --}}

            <div class="row">
                <div class="col-sm-12 col-sm-offset-0">
                    <h3 class="header smaller lighter blue">Terms & Condition</h3>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="terms_header">Terms & Condition Header</label>
                                    <div class="col-xs-12 col-sm-8 ">
                                        <input type="text" id="terms_header" name="terms_header" placeholder="Enter Terms Header Title" class="form-control" value="{{ $our_privacy->terms_header_title ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="terms_details">Terms & Condition Details</label>
                                    <div class="col-xs-12 col-sm-8 ">
                                        <textarea name="terms_details" id="editor1" class="form-control" cols="30" rows="10">{{ $our_privacy->terms_condition ?? '' }}</textarea>
                                    </div>
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
<script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
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
