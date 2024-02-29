@extends('layouts.master')
@section('title','Upload Items')
@section('page-header')
    <i class="fa fa-upload"></i> Upload Items
@stop
@section('css')

@stop


@section('content')


    <div class="row">

        <div class="col-sm-8 col-sm-offset-2">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar no-border">
                        <a href="{{ route('items.index') }}">
                            <i class="ace-icon fa fa-list"></i> Item List
                        </a>
                    </span>
                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data">
                            @csrf

                            @include('partials._alert_message')

                            <div class="form-group">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"> Select CSV File</label>

                                <div class="col-xs-12 col-sm-6">
                                    <input type="file" id="id-input-file-3" name="item_csv_file" accept=".csv"/>
                                </div>

                                <div class="col-sm-3">
                                    <a href="{{ asset('assets/item-sample-csv.csv') }}"><i class="fa fa-download"></i> Download CSV Demo File</a>
                                </div>

                            </div>


                            <div class="form-group">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>

                                <div class="col-xs-12 col-sm-9">

                                    <button class="btn btn-xs btn-success" type="submit"> <i class="fa fa-save"></i> Save</button>
                                    <button class="btn btn-xs btn-gray" type="Reset"> <i class="fa fa-refresh"></i> Reset</button>

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


    <script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
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
                thumbnail: 'small'//large | fit

            }).on('change', function(){
                //console.log($(this).data('ace_input_files'));
                //console.log($(this).data('ace_input_method'));
            });


        });

    </script>
@stop