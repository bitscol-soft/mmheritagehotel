@extends('layouts.master')

@section('title',' Edit Feature Header')
@section('page-header')
<i class="fa fa-gears"></i> Our Service Section Heading
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-form" title="Our services heading" description="Heading and text above the service boxes.">
    <x-alert-message />

    <x-mm.panel class="tw-p-5 mm-web-narrow">
        <form class="form-horizontal" id="companyForm" action="{{ route('website-core.our_service.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            {{-- @method('PUT') --}}
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1-1">Heading Title</label>

                        <div class="col-xs-12 col-sm-8 @error('heading_title') has-error @enderror">
                            <input type="text" class="form-control input-sm" name="heading_title" value="{{ $service->service_heading ?? '' }}" placeholder="Enter Feature Title">

                            @error('heading_title')
                            <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1-1">Section BG</label>

                        <div class="col-xs-12 col-sm-8 @error('feature_subtitle') has-error @enderror">
                            <div class="img-box">
                                <img height="300" src="{{ asset($service->service_background_img ?? '') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1-1">Section BG Upload</label>

                        <div class="col-xs-12 col-sm-8 @error('feature_subtitle') has-error @enderror">
                            <div class="form-group">
                                <div class="text-danger">*Upload size should be (1680 x 1280)px</div>
                                <input type="file" name="section_background" class="category_photos">
                            </div>
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
@stop
