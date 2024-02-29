@extends('layouts.master')

@section('title',' Edit Aminities')
@section('page-header')
<i class="fa fa-gears"></i> Create Aminities
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop


@section('content')

<div class="page-header">
    {{-- <h1>
        <i class="fa fa-info-circle green"></i> Projects Name List
    </h1> --}}
</div>

@include('partials._alert_message')

<div class="row">
    <div class="col-xs-12">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                </div>

                <div class="widget-body">
                    <div class="no-padding">

                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>

                        <form class="form-horizontal" id="companyForm" action="{{ route('aminities.store') }}" method="get" enctype="multipart/form-data">
                            @csrf


                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Aminities Name</label>

                                        <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="name" value="" placeholder="Aminities Name">

                                            @error('name')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Aminities icon</label>

                                        <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                            <label class="text-danger">use flaticon icon/png size (38 * 40)px</label>
                                            <input type="file" name="aminiti_icon" class="category_photos" multiple>

                                            @error('name')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Status</label>

                                        <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">
                                            <select name="status">
                                                <option value="">Select Option</option>
                                                <option value="1">Active</option>
                                                <option value="0">In Active</option>
                                            </select>
                                            @error('status')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
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
</div>

@endsection

@section('js')

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>




<!-- inline scripts related to this page -->
<script type="text/javascript">
    function delete_check(id) {
        Swal.fire({
            title: 'Are you sure ?',
            html: "<b>You want to delete permanently !</b>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            width: 400,
        }).then((result) => {
            if (result.value) {
                $('#deleteCheck_' + id).submit();
            }
        })

    }
</script>

  <!--Drag and drop-->
<script type="text/javascript">
    jQuery(function($) {
        $('.category_photos').ace_file_input({
            style: 'well',
            btn_choose: 'Upload Aminities Icon',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
    });
</script>
@stop
