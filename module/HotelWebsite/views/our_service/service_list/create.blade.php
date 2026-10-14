@extends('layouts.master')

@section('title',' Edit Feature Header')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
<style>
    .tags{width: 100% !important;}
</style>
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-form" title="Add a service box" description="Add a box to the Our Services section.">
    <x-alert-message />

    <x-mm.panel class="tw-p-5 mm-web-narrow">
        <form class="form-horizontal" id="companyForm" action="{{ route('website-core.our_service_list.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            {{-- @method('PUT') --}}
            <div class="row">
                <div class="col-sm-12">
                    <x-mm.field label="Service Title" id="service-title" name="service_title" value="" placeholder="Enter Service Title" :error="$errors->first('service_title')" />
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
                    <x-mm.field label="Service Box Icon (FontAwsome 4.7)" id="service-icon" name="service_icon" value="" placeholder="Ex - fa fa-bed" :error="$errors->first('service_icon')" />
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1-1">Service List</label>

                        <div class="col-xs-12 col-sm-8 @error('heading_title') has-error @enderror">
                            {{-- W4.3: kept raw. The bootstrap-tag plugin binds to
                                 the #form-field-tags id and the inline tag_input.tag() call. --}}
                            <div class="inline" style="display: inline !important;">
                                <input type="text" class="form-control" name="service_list" id="form-field-tags" value="" placeholder="Enter tags ..." />
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-actions center" style="text-align: right !important; margin: 0;">
                <button type="submit" class="mm-button">
                    <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                    Save
                </button>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>
@endsection

@section('js')

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
