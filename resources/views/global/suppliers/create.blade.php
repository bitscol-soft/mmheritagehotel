
@extends('layouts.master')
@section('title','Add New Supplier')
@section('page-header')
<i class="fa fa-gear"></i> Add New Supplier
@stop
@section('css')


<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
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

    <div class="col-sm-12">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="widget-title"> @yield('page-header')</h4>

                @if (hasPermission("suppliers.view", $slugs))
                    <span class="widget-toolbar">
                        <a href="{{ route('suppliers.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> Supplier List
                        </a>
                    </span>
                @endif

            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <form class="form-horizontal" action="{{ route('suppliers.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        @include('partials._alert_message')

                        <input type="hidden" name="group_id" value="1">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label add_asterisk" for="form-field-1-1"> Supplier Name </label>

                                    <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Supplier Name">

                                        @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1-1"> Attention </label>

                                    <div class="col-xs-12 col-sm-8">
                                        <input type="text" class="form-control" name="attention" value="{{ old('attention') }}" placeholder="Supplier Attention">
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label add_asterisk" for="form-field-1-1"> Type </label>

                                    <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                                        <select name="supplier_type_id" class="form-control" id="supplier_type_id">
                                            @foreach($supplier_types as $id => $name)
                                            <option value="{{ $id }}" {{ $id == old('supplier_type_id') ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>

                                        @error('supplier_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label add_asterisk" for="form-field-1-1"> Country </label>

                                    <div class="col-xs-12 col-sm-8 @error('country_id') has-error @enderror">
                                        <select name="country_id" class="form-control" id="country_id">
                                            @foreach($countries as $id => $name)
                                            <option value="{{ $id }}" {{ $id == 18 ? 'selected':'' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>

                                        @error('country_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Phone</label>

                                    <div class="col-xs-12 col-sm-8 @error('phone') has-error @enderror">
                                        <input type="number" class="form-control" name="phone" value="" placeholder="Phone">

                                        @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Email</label>

                                    <div class="col-xs-12 col-sm-8 @error('email') has-error @enderror">
                                        <input type="email" class="form-control" name="email" placeholder="Email">

                                        @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Website</label>

                                    <div class="col-xs-12 col-sm-8 @error('website') has-error @enderror">
                                        <input type="text" class="form-control" name="website" placeholder="Website">

                                        @error('website')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>


                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Fax</label>

                                    <div class="col-xs-12 col-sm-8 @error('fax') has-error @enderror">
                                        <input type="fax" class="form-control" name="fax" placeholder="Fax">

                                        @error('fax')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Address</label>

                                    <div class="col-xs-12 col-sm-8 @error('address') has-error @enderror">
                                        <textarea name="address" id="" class="form-control"></textarea>

                                        @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">


                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Head Office</label>

                                    <div class="col-xs-12 col-sm-8 @error('head_office') has-error @enderror">
                                        <input type="text" class="form-control" name="head_office" placeholder="Head Office">

                                        @error('head_office')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>


                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Factory 1</label>

                                    <div class="col-xs-12 col-sm-8 @error('factory_1') has-error @enderror">

                                        <textarea name="factory_1" class="form-control"></textarea>

                                        @error('factory_1')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Factory 2</label>

                                    <div class="col-xs-12 col-sm-8 @error('email') has-error @enderror">
                                        <textarea name="factory_2" class="form-control"></textarea>

                                        @error('factory_2')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>

                            <div class="col-xs-12 col-sm-6">

                                <button class="btn btn-success"> <i class="fa fa-save"></i> Save</button>
                                <button class="btn btn-gray" type="Reset"> <i class="fa fa-refresh"></i> Reset</button>
                                @if (hasPermission("suppliers.view", $slugs))
                                    <a href="{{ route('suppliers.index') }}" class="btn btn-info"> <i class="fa fa-list"></i> List</a>
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
<script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
<script src="{{ asset('assets/js/ace.min.js') }}"></script>

<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>

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




        if (!ace.vars['touch']) {
            $('#company_id').chosen({
                allow_single_deselect: true
            });
            //resize the chosen on window resize

            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function() {
                    $('#company_id').each(function() {
                        var $this = $(this);
                        $this.next().css({
                            'width': $this.parent().width()
                        });
                    })
                }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if (event_name != 'sidebar_collapsed') return;
                $('#company_id').each(function() {
                    var $this = $(this);
                    $this.next().css({
                        'width': $this.parent().width()
                    });
                })
            });


            $('#chosen-multiple-style .btn').on('click', function(e) {
                var target = $(this).find('input[type=radio]');
                var which = parseInt(target.val());
                if (which == 2) $('#form-field-select-4').addClass('tag-input-style');
                else $('#form-field-select-4').removeClass('tag-input-style');
            });
        }

        if (!ace.vars['touch']) {
            $('#supplier_type_id').chosen({
                allow_single_deselect: true
            });
            //resize the chosen on window resize

            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function() {
                    $('#supplier_type_id').each(function() {
                        var $this = $(this);
                        $this.next().css({
                            'width': $this.parent().width()
                        });
                    })
                }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if (event_name != 'sidebar_collapsed') return;
                $('#supplier_type_id').each(function() {
                    var $this = $(this);
                    $this.next().css({
                        'width': $this.parent().width()
                    });
                })
            });


            $('#chosen-multiple-style .btn').on('click', function(e) {
                var target = $(this).find('input[type=radio]');
                var which = parseInt(target.val());
                if (which == 2) $('#form-field-select-4').addClass('tag-input-style');
                else $('#form-field-select-4').removeClass('tag-input-style');
            });
        }

        if (!ace.vars['touch']) {
            $('#country_id').chosen({
                allow_single_deselect: true
            });
            //resize the chosen on window resize

            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function() {
                    $('#country_id').each(function() {
                        var $this = $(this);
                        $this.next().css({
                            'width': $this.parent().width()
                        });
                    })
                }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if (event_name != 'sidebar_collapsed') return;
                $('#country_id').each(function() {
                    var $this = $(this);
                    $this.next().css({
                        'width': $this.parent().width()
                    });
                })
            });


            $('#chosen-multiple-style .btn').on('click', function(e) {
                var target = $(this).find('input[type=radio]');
                var which = parseInt(target.val());
                if (which == 2) $('#form-field-select-4').addClass('tag-input-style');
                else $('#form-field-select-4').removeClass('tag-input-style');
            });
        }


    });
</script>
@stop
