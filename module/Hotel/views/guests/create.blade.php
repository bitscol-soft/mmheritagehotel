
@extends('layouts.master')
@section('title','Add New Guest')
@section('page-header')
    <i class="fad fa-plus-circle"></i> Add New Guest
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <!-- page specific plugin styles -->
	<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />

    <style>

    .results img{
        width: 360px;
        height: 173px;
    }
    video{
        width: 546px;
        height: 195px;
    }
    </style>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">

                            <a href="{{ route('guests.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i> List
                            </a>

                    </span>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">

                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>
                        @if (request('type') == 'upload')
                        @include('guests.create.upload')
                        @else
                            @include('guests.create.create')
                        @endif

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
