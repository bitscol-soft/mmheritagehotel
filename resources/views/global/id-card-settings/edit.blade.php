
@extends('layouts.master')
@section('title','Edit Setting')
@push('style')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@endpush


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-crud-form" title="Edit Id Card Setting">
        <x-slot name="actions">
            @if (hasPermission("company.infos.view", $slugs))
                <a href="{{ route('id-card-settings.index') }}" class="btn btn-sm btn-default">
                    <i class="ace-icon fa fa-list-alt"></i> Setting List
                </a>
            @endif
        </x-slot>

        <x-mm.panel>
                        <div style="margin: 20px;">
                            @include('partials._alert_message')
                        </div>

                        <form class="form-horizontal" action="{{ route('id-card-settings.update', $idCardSetting->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')


                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Mobile </label>

                                        <div class="col-xs-12 col-sm-8 @error('mobile') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="mobile" required value="{{ old('mobile', $idCardSetting->mobile) }}" placeholder="Mobile Numbers">

                                            @error('mobile')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>






                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Email </label>

                                        <div class="col-xs-12 col-sm-8 @error('email') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="email" required value="{{ old('email', $idCardSetting->email) }}" placeholder="Email Address">
                                        </div>
                                    </div>
                                </div>



                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Website </label>

                                        <div class="col-xs-12 col-sm-8 @error('web_url') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="web_url" required value="{{ old('web_url', $idCardSetting->web_url) }}" placeholder="Wensite Address">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Issue Date </label>

                                        <div class="col-xs-12 col-sm-8 ">
                                            <input type="text" class="form-control input-sm date-picker" name="issue_date" required value="{{ old('issue_date', $idCardSetting->issue_date) }}" placeholder="Issue Date">
                                        </div>
                                    </div>
                                </div>


                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Address </label>

                                        <div class="col-xs-12 col-sm-8 @error('address') has-error @enderror">
                                            <textarea class="form-control" name="address" required>{{ old('address', $idCardSetting->address) }}</textarea>

                                            @error('address')
                                                <span class="text-danger">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>



                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="form-field-1-1"> Logo </label>

                                                <div class="col-xs-12 col-sm-8">
                                                    <input type="file" name="logo" id="logo" />

                                                    <h3 class="text-right">
                                                        @if (file_exists($idCardSetting->logo))
                                                            <img src="{{ asset($idCardSetting->logo) }}" style="height: 100px; width: 200px">
                                                        @endif
                                                    </h3>
                                                </div>
                                            </div>

                                        </div>



                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label" for="form-field-1-1"> Signature </label>

                                                <div class="col-xs-12 col-sm-8">
                                                    <input type="file" name="signature" id="signature" />

                                                    <h3 class="text-right">
                                                        @if (file_exists($idCardSetting->signature))
                                                            <img src="{{ asset($idCardSetting->signature) }}" style="height: 100px; width: 150px">
                                                        @endif
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row clearfix">

                                    <div class="col-sm-12 px-3">
                                        Group Info:
                                        <hr style="height: 5px; background: #00BE67; margin-top: 0">

                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1"> Corporate Address </label>

                                            <div class="col-xs-12 col-sm-8">
                                                <textarea class="form-control" style="height: 130px" name="corporate_address" required>{{ old('corporate_address', $idCardSetting->corporate_address) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="form-field-1-1"> Group Logo </label>

                                            <div class="col-xs-12 col-sm-8">
                                                <input type="file" name="group_logo" id="group_logo" />

                                                <h3 class="text-right">
                                                    @if (file_exists($idCardSetting->group_logo))
                                                        <img src="{{ asset($idCardSetting->group_logo) }}" style="height: 100px; width: 150px">
                                                    @endif
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="form-actions center" style="text-align: right !important;">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="ace-icon fa fa-edit icon-on-right bigger-110"></i>
                                    Update
                                </button>

                                @if (hasPermission("company.infos.view", $slugs))
                                    <a href="{{ route('id-card-settings.index') }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-backward"></i> Back List
                                    </a>
                                @endif
                            </div>
                        </form>
        </x-mm.panel>
    </x-mm.page>




@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>
    




    <!--Drag and drop-->
    <script type="text/javascript">
        jQuery(function($) {
            $('#logo').ace_file_input({
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

            // Start header, footer
            $('#signature').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small'//large | fit
            });

            // Start header, footer
            $('#group_logo').ace_file_input({
                style: 'well',
                btn_choose: 'Drop files here or click to choose',
                btn_change: null,
                no_icon: 'ace-icon fa fa-cloud-upload',
                droppable: true,
                thumbnail: 'small'//large | fit
            });
        });

    </script>
@stop
