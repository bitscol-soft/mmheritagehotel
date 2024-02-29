@extends('layouts.master')
@section('title', 'Add New Item')
@section('page-header')
    <i class="fa fa-gear"></i> Add New Item Unit
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

    </style>
@stop


@section('content')

    <div class="row">

        <div class="col-sm-8 col-sm-offset-2">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('item-units.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> Unit List
                        </a>
                    </span>

                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('item-units.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf

                            @include('partials._alert_message')



                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Unit Name </label>

                                <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                        placeholder="Item Name">

                                    @error('name')
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Conversion </label>

                                <div class="col-xs-12 col-sm-8 @error('conversion') has-error @enderror">
                                    <input type="number" class="form-control" name="conversion"
                                        value="{{ old('conversion') }}" placeholder="Conversion">

                                    @error('conversion')
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Status </label>

                                <div class="col-xs-12 col-sm-8 @error('status') has-error @enderror">

                                    <select name="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                    </select>

                                    @error('status')
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span>
                                    @enderror

                                </div>

                            </div>

                            <div class="form-group">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>

                                <div class="col-xs-12 col-sm-6">

                                    <button class="btn btn-success"> <i class="fa fa-save"></i> Save</button>
                                    <button class="btn btn-gray" type="Reset"> <i class="fa fa-refresh"></i>
                                        Reset</button>
                                    <a href="{{ route('item-units.index') }}" class="btn btn-info"> <i
                                            class="fa fa-list"></i> List</a>

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

    <script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>



    <!--Drag and drop-->
    <script type="text/javascript">
        jQuery(function($) {


            $('#id-input-file-3').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small' //large | fit

            }).on('change', function() {
                //console.log($(this).data('ace_input_files'));
                //console.log($(this).data('ace_input_method'));
            });


        });
    </script>
@stop
