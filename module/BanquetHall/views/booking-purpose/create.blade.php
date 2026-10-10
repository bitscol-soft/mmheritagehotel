@extends('layouts.master')
@section('title', 'Add New Booking Purpose')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />

@stop

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-crud-form" :title="'Add New Booking ' . (request('type') == 'purpose' ? 'Purpose' : 'Platform')">
        <x-slot name="actions">
            <a href="{{ route('booking-purpose.index') }}?type={{ request('type') == 'purpose' ? 'purpose' : 'platform' }}" class="btn btn-sm btn-default">
                <i class="ace-icon fa fa-list-alt"></i> List
            </a>
        </x-slot>

        <x-mm.panel>
                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>

                        <form class="form-horizontal" id="companyForm" action="{{ route('booking-purpose.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            @if (request('type') == 'purpose')
                            <input type="hidden" name="rule" value="1">
                            @else
                            <input type="hidden" name="rule" value="2">
                            @endif

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"> @if (request('type') == 'purpose') Purpose @else Platform @endif <sup class="text-danger">*</sup></label>
                                        <div class="col-xs-12 col-sm-8">
                                            <input type="text" class="form-control input-sm" name="name"
                                                value="{{ old('name') }}" placeholder="@if (request('type') == 'purpose') Purpose @else Platform @endif" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions center" style="text-align: right !important;">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                    Save
                                </button>
                                <a href="{{ route('company.index') }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-backward"></i> Back List
                                </a>
                            </div>
                        </form>
        </x-mm.panel>
    </x-mm.page>
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
