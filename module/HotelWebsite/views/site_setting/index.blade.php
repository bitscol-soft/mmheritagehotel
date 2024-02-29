@extends('layouts.master')
@section('title', 'Website Setting')
@section('page-header')
    <i class="fa fa-info-circle"></i> Website Setting
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
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('website-core.settings.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf

                            <x-alert-message />

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="website_first_name">Website First
                                                    Name</label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="text" id="website_first_name" name="website_first_name"
                                                        placeholder="Enter website first name" class="form-control"
                                                        value="{{ $setting->site_first_name ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="website_last_name">Website Last
                                                    Name</label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="text" id="website_last_name"
                                                        value="{{ $setting->site_last_name ?? '' }}"
                                                        name="website_last_name" placeholder="Enter website last name"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="website_slogan">Website
                                                    Slogan</label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <input type="text" id="website_slogan"
                                                        value="{{ $setting->site_slogan ?? '' }}" name="website_slogan"
                                                        placeholder="Enter website slogan" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="meta_keyword">Meta
                                                    Keyword</label>
                                                <div class="col-xs-12 col-sm-8 ">
                                                    <textarea name="meta_keywords" rows="2" class="form-control" placeholder="Enter Keywords">{{ $setting->meta_keyword ?? '' }}</textarea>

                                                    @error('details')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Meta Description</label>
                                                <div
                                                    class="col-xs-12 col-sm-8 @error('meta_description') has-error @enderror">
                                                    <textarea name="meta_description" rows="3" class="form-control" placeholder="Enter Description">{{ $setting->meta_description ?? '' }}</textarea>

                                                    @error('details')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Contact Information Form --}}

                            <div class="row">
                                <div class="col-sm-12 col-sm-offset-0">
                                    <h3 class="header smaller lighter blue">Contact Information</h3>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="phone_no">Phone No</label>
                                                    <div class="col-xs-12 col-sm-8 ">
                                                        <input type="text" id="phone_no" name="phone_no"
                                                            placeholder="Enter phone no" class="form-control"
                                                            value="{{ $setting->phone_no ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="email">Email</label>
                                                    <div class="col-xs-12 col-sm-8 ">
                                                        <input type="text" id="email" name="email"
                                                            placeholder="Enter email" class="form-control"
                                                            value="{{ $setting->email ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="address">Address</label>
                                                    <div class="col-xs-12 col-sm-8 ">
                                                        <input type="text" id="address" name="address"
                                                            placeholder="Enter website address" class="form-control"
                                                            value="{{ $setting->address ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="location_map">Location
                                                        Map</label>
                                                    <div class="col-xs-12 col-sm-8 ">
                                                        <textarea name="location_map" id="location_map" cols="30" rows="5"
                                                            placeholder="Enter Embed iframe map link" class="form-control">{{ $setting->location_map }}</textarea>
                                                        {{-- <input type="text" id="location_map" name="location_map" placeholder="Enter Embed iframe map link" class="form-control" value="{{ $setting->location_map ?? '' }}"> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Social Information Form --}}
                            <div class="row">
                                <div class="col-sm-12 col-sm-offset-0">
                                    <h3 class="header smaller lighter blue">Social Information</h3>

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="facebook_url">Facebook
                                                        Url</label>
                                                    <div class="col-xs-12 col-sm-8 ">
                                                        <input type="text" id="facebook_url" name="facebook_url"
                                                            placeholder="Enter facebook url" class="form-control"
                                                            value="{{ $setting->facebook_url ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="twitter_url">Instagram
                                                        Url</label>
                                                    <div class="col-xs-12 col-sm-8 ">
                                                        <input type="text" id="twitter_url" name="twitter_url"
                                                            placeholder="Enter instagram url" class="form-control"
                                                            value="{{ $setting->twitter_url ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="linkedin_url">Tiktok
                                                        Url</label>
                                                    <div class="col-xs-12 col-sm-8 ">
                                                        <input type="text" id="linkedin_url" name="linkedin_url"
                                                            placeholder="Enter tiktok url" class="form-control"
                                                            value="{{ $setting->linkedin_url ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="youtube_url">Youtube
                                                        Url</label>
                                                    <div class="col-xs-12 col-sm-8 ">
                                                        <input type="text" id="youtube_url" name="youtube_url"
                                                            placeholder="Enter youtube url" class="form-control"
                                                            value="{{ $setting->youtube_url ?? '' }}">
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
                                    <button class="btn-sm btn-outline-success" type="submit"> <i class="fa fa-save"></i>
                                        Save</button>
                                    <button class="btn btn-sm btn-gray" type="Reset"> <i class="fa fa-refresh"></i>
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

            }).on('change', function() {});
        });
    </script>

@endsection
