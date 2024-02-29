

@php $slugs = p_slugs(); @endphp  <!-- permission -->

@extends('layouts.master')
@section('title','Edit Group')
@section('page-header')
    <i class="fa fa-pencil-square-o"></i> Edit Group
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
                        <form class="form-horizontal" action="{{ route('group.update',$group->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @include('partials._alert_message')

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Group Name </label>

                                <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $group->name) }}" placeholder="Group Name">

                                    {!! getValidationErrorMessage('name') !!}
                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Group Email </label>

                                <div class="col-xs-12 col-sm-8 @error('email') has-error @enderror">
                                    <input type="text" class="form-control" name="email" value="{{ old('email', $group->email) }}" placeholder="Group Email">

                                    {!! getValidationErrorMessage('email') !!}

                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Group Phone Number </label>

                                <div class="col-xs-12 col-sm-8 @error('phone') has-error @enderror">
                                    <input type="text" class="form-control" name="phone"
                                           value="{{ old('phone', $group->phone) }}" placeholder="Group Phone">

                                    {!! getValidationErrorMessage('phone') !!}

                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Group Address </label>

                                <div class="col-xs-12 col-sm-8 @error('address') has-error @enderror">
                                    <textarea name="address" class="form-control" placeholder="Group Address">{{ old('address') ?: $group->address }}</textarea>

                                    {!! getValidationErrorMessage('address') !!}

                                </div>

                            </div>


                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Group Logo </label>

                                <div class="col-xs-12 col-sm-4">
                                    <input type="file" name="logo" id="id-input-file-3" />
                                </div>

                                <div class="col-xs-12 col-sm-4">
                                    @if ($group->logo == "default.png")

                                    @else
                                        <dd><img src="{{ asset('uploads/group/'.$group->logo) }}" alt="{{ $group->name }}" style="width: 60px"></dd>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="form-field-1-1"> Fav Icon </label>

                                <div class="col-xs-12 col-sm-4">
                                    <input type="file" name="fav_icon" id="id-input-file-2" />
                                </div>

                                <div class="col-xs-12 col-sm-4">
                                    @if ($group->logo == "default.png")

                                    @else
                                        <dd><img src="{{ asset($group->fav_icon) }}" alt="{{ $group->name }}" style="width: 60px"></dd>
                                    @endif
                                </div>

                            </div>


                            @if(auth()->id() == 1)
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1-1"> Login Page Background </label>

                                    <div class="col-xs-12 col-sm-4">
                                        <input type="file" name="login_background_image" id="id-input-file-1" />
                                    </div>

                                    <div class="col-xs-12 col-sm-4">
                                        @if (file_exists($group->login_background_image))
                                            <dd><img src="{{ asset($group->login_background_image) }}" alt="{{ $group->name }}" style="width: 60px"></dd>
                                        @endif
                                    </div>

                                </div>
                            @endif


                            <div class="form-group">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>

                                <div class="col-xs-12 col-sm-6">

                                    <button class="btn btn-success"> <i class="fa fa-pencil-square-o"></i> Update</button>
                                    <button class="btn btn-gray" type="Reset"> <i class="fa fa-refresh"></i> Reset</button>
                                    @if (hasPermission("groups.view", $slugs))
                                        <a href="{{ route('group.index') }}" class="btn btn-info"> <i class="fa fa-list"></i> List</a>
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


            $('#id-input-file-2').ace_file_input({
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

            $('#id-input-file-1').ace_file_input({
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
