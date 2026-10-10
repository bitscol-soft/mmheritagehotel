@extends('layouts.master')
@section('title', 'Edit Booking Purpose')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />

@stop

@section('content')
<x-mm.styles />
<x-mm.page :title="request('type') == 'purpose' ? 'Edit booking purpose' : 'Edit booking platform'" description="Maintain booking classification labels." class="mm-booking-setup">
<x-slot name="actions"><a class="mm-button mm-button-secondary" href="{{ route('booking-purpose.index') }}?type={{ request('type') == 'purpose' ? 'purpose' : 'platform' }}">Back to list</a></x-slot>
@include('partials._alert_message')
<x-mm.panel class="tw-p-5">
<form class="form-horizontal" id="companyForm" action="{{ route('booking-purpose.update', $booking_purpose->id) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="rule" value="{{ $booking_purpose->rule }}">

                            <x-mm.field :label="request('type') == 'purpose' ? 'Purpose' : 'Platform'" id="booking-setup-name" name="name" value="{{ old('name', $booking_purpose->name) }}" :placeholder="request('type') == 'purpose' ? 'Purpose' : 'Platform'" required :error="$errors->first('name')" />

                            <div class="form-actions center" style="text-align: right !important;">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                                    Save
                                </button>
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
