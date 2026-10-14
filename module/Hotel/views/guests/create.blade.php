
@extends('layouts.master')
@section('title','Add New Guest')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <!-- page specific plugin styles -->
	<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />

@stop

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-guest-form" :title="request('type') == 'upload' ? 'Import guests' : 'Add a guest'" description="Manage guest details, contact information and identity documents.">
        <x-slot name="actions">
            <a href="{{ route('guests.index') }}" class="mm-button mm-button-secondary">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Guest directory
            </a>
        </x-slot>
        <x-mm.panel>
        @include('partials._alert_message')
        @if (request('type') == 'upload')
            @include('guests.create.upload')
        @else
            @include('guests.create.create')
        @endif

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

        }).on('change', function() {
        });

        $('.nid-back').ace_file_input({
            style: 'well',
            btn_choose: 'Upload NID / Passport (Back Side)',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });

        $('.image').ace_file_input({
            style: 'well',
            btn_choose: 'Image upload',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
    });
</script>

<script src="{{ asset('assets/js/webcam.min.js') }}"></script>

<script language="JavaScript">
    function configure(){
        Webcam.set({
            width: 300,
            height: 135,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');
    }

    function reset(){
        Webcam.reset();
    }

    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            // document.querySelector('.image-tag').setAttribute('src', "data:image/jpg;base64," + data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
            $('.image-tag').val(data_uri);
            $('.is_web_cam_or_not').val(1);
            $('.image').prop('disabled', true);

            Webcam.reset();
            document.getElementById('my_camera').innerHTML = '<img src="'+data_uri+'"/>';
            $('.delete-snap').show();
        } );
    }

    $('.delete-snap').on('click', function(){
        $('#results').empty();
        $('.delete-snap').hide();
        $('.image').prop('disabled', false);
    })

</script>

@endsection
