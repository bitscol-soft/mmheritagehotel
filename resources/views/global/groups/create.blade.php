

@php $slugs = p_slugs(); @endphp  <!-- permission -->

@extends('layouts.master')
@section('title','Add New Group')
@section('page-header')
    <i class="fa fa-gear"></i> Add New Group
@stop
@section('css')

@stop


@section('content')

    <div class="row">

        <div class="col-sm-8 col-sm-offset-2">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    @if (hasPermission("groups.view", $slugs))
                    <span class="widget-toolbar">
                                <a href="{{ route('group.index') }}">
                                    <i class="ace-icon fa fa-list-alt"></i> Group List
                                </a>
                            </span>
                    @endif

                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <form class="form-horizontal" action="{{ route('group.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                            @include('partials._alert_message')

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Group Name </label>

                                <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                    <input type="text" class="form-control input-sm" name="name"
                                           value="{{ old('name') }}" placeholder="Group Name">

                                    @error('name')
                                    <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Group Email </label>

                                <div class="col-xs-12 col-sm-8 @error('email') has-error @enderror">
                                    <input type="text" class="form-control input-sm" name="email"
                                           value="{{ old('email') }}" placeholder="Group Email">

                                    @error('email')
                                    <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                    @enderror

                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Group Phone Number </label>

                                <div class="col-xs-12 col-sm-8 @error('phone') has-error @enderror">
                                    <input type="text" class="form-control input-sm" name="phone"
                                           value="{{ old('phone') }}" placeholder="Group Phone">

                                    @error('phone')
                                    <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                    @enderror

                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Group Address </label>

                                <div class="col-xs-12 col-sm-8 @error('address') has-error @enderror">
                                    <textarea name="address" class="form-control input-sm" placeholder="Group Address">{{ old('address') }}</textarea>

                                    @error('address')
                                    <span class="text-danger">
                                                     {{ $message }}
                                                </span>
                                    @enderror

                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Group Logo </label>

                                <div class="col-xs-12 col-sm-4">
                                    <input type="file" name="logo" id="id-input-file-3" />
                                </div>

                            </div>


                            <div class="form-group">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>

                                <div class="col-xs-12 col-sm-6">

                                    <button class="btn btn-success btn-sm"> <i class="fa fa-save"></i> Save</button>
                                    <button class="btn btn-gray btn-sm" type="Reset"> <i class="fa fa-refresh"></i> Reset</button>
                                    @if (hasPermission("groups.view", $slugs))
                                        <a href="{{ route('group.index') }}" class="btn btn-info btn-sm"> <i class="fa fa-list"></i> List</a>
                                    @endif

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
                thumbnail: 'small'//large | fit

            }).on('change', function(){
                //console.log($(this).data('ace_input_files'));
                //console.log($(this).data('ace_input_method'));
            });


        });

    </script>
@stop