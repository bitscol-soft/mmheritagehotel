@extends('layouts.master')
@section('title', 'Edit Booking Purpose')
@section('page-header')
    <i class="fa fa-plus-circle"></i> Edit Booking @if (request('type') == 'purpose')
        Purpose
    @else
        Platform
    @endif
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />

@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">

                        <a
                            href="{{ route('booking-purpose.index') }}?type={{ request('type') == 'purpose' ? 'purpose' : 'platform' }}">
                            <i class="ace-icon fa fa-list-alt"></i> List
                        </a>

                    </span>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">

                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>

                        <form class="form-horizontal" id="companyForm" action="{{ route('booking-purpose.update', $booking_purpose->id) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="rule" value="{{ $booking_purpose->rule }}">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">
                                            @if (request('type') == 'purpose')
                                                Purpose
                                            @else
                                                Platform
                                            @endif <sup class="text-danger">*</sup>
                                        </label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control input-sm" name="name"
                                                value="{{ old('name', $booking_purpose->name) }}"
                                                placeholder="@if (request('type') == 'purpose') Purpose @else Platform @endif"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions center" style="text-align: right !important;">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                    Save
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/dropzone.min.js') }}"></script>

    <!--Drag and drop-->
    <script type="text/javascript">
        jQuery(function($) {
            $('.nid-front').ace_file_input({
                style: 'well',
                btn_choose: 'Upload NID / Passport (Front Side)',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit

            }).on('change', function() {});

            $('.nid-back').ace_file_input({
                style: 'well',
                btn_choose: 'Upload NID / Passport (Back Side)',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit

            }).on('change', function() {});

            $('.image').ace_file_input({
                style: 'well',
                btn_choose: 'Image upload',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit

            }).on('change', function() {});
        });
    </script>

@endsection
