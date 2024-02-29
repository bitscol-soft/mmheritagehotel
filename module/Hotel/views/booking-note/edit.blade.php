
@extends('layouts.master')
@section('title','Edit Booking Note')
@section('page-header')
    <i class="fa fa-gears"></i> Edit Booking Note
@stop
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    <span class="widget-toolbar">

                            <a href="{{ route('booking-note.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i> Booking Note List
                            </a>

                    </span>
                </div>

                <div class="widget-body">
                    <div class="widget-main no-padding">

                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>

                        <form class="form-horizontal" id="companyForm" action="{{ route('booking-note.update',$bookingNote->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-sm-12">

                                    <hr>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Title<sup class="text-danger">*</sup></label>

                                        <div class="col-xs-12 col-sm-8">
                                            <textarea name="title" class="form-control" cols="30" rows="10" required>{{ $bookingNote->title }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="form-actions center" style="text-align: right !important;">
                                <button type="submit" class=" btn-sm btn-outline-success">
                                    <i class="fa fa-save"></i>
                                    Save
                                </button>

                                <a href="{{ route('booking-note.index') }}" class="btn-sm btn btn-default">
                                    <i class="fa fa-backward"></i> Back List
                                </a>
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
        $('.id-input-file-3').ace_file_input({
            style: 'well',
            btn_choose: 'Upload NID / Passport Photo',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
    });
</script>
<script>
    $('.photo-remove').click(function(){
        $(this).closest('.nid_photo').remove();
        $(this).closest('.ace-file-input').show();

    });
</script>
@endsection
