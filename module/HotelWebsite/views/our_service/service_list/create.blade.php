@extends('layouts.master')

@section('title',' Edit Feature Header')
@section('page-header')
<i class="fa fa-gears"></i> Our Service Section Heading
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
<style>
    .tags{width: 100% !important;}
</style>
@stop

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                </div>

                <div class="widget-body">
                    <div class="no-padding">

                       <x-alert-message />

                        <form class="form-horizontal" id="companyForm" action="{{ route('website-core.our_service_list.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            {{-- @method('PUT') --}}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Service Title</label>

                                        <div class="col-xs-12 col-sm-8 @error('service_title') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="service_title" value="" placeholder="Enter Service Title">

                                            @error('service_title')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Service Short Desc</label>
                                        <div class="col-xs-12 col-sm-8 @error('service_short_desc') has-error @enderror">
                                            <textarea name="service_short_desc" rows="5" class="form-control" placeholder="Enter Description">{{ old('service_short_desc') }}</textarea>

                                            @error('details')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Service Box Icon (FontAwsome 4.7)</label>

                                        <div class="col-xs-12 col-sm-8 @error('service_icon') has-error @enderror">
                                            <input type="text" class="form-control input-sm" name="service_icon" value="" placeholder="Ex - fa fa-bed">

                                            @error('service_icon')
                                            <span class="text-danger"> {{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1">Service List</label>


                                        <div class="col-xs-12 col-sm-8 @error('heading_title') has-error @enderror">
                                            <div class="inline" style="display: inline !important;">
												<input type="text" class="form-control" name="service_list" id="form-field-tags" value="" placeholder="Enter tags ..." />
											</div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="form-actions center" style="text-align: right !important; margin: 0;">
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
<script src="{{ asset('assets/js/bootstrap-tag.min.js') }}"></script>
<script>
    var tag_input = $('#form-field-tags');
    try{
        tag_input.tag({
                placeholder:tag_input.attr('placeholder'),
            });

    }
    catch(e) {
        tag_input.after('<textarea id="'+tag_input.attr('id')+'" name="'+tag_input.attr('name')+'" rows="3">'+tag_input.val()+'</textarea>').remove();
    }
</script>

@stop
