@extends('layouts.master')
@section('title', 'Edit Rest Material Unit')
@section('css')

@stop

@section('content')

<x-mm.styles />
<x-mm.page class="mm-rst mm-rst-inv mm-rst-form" title="Edit material unit" description="Update the unit and its conversion.">
    <x-slot name="actions">
        <a href="{{ route('rst.material-unit.index') }}" class="mm-button">
            <i class="ace-icon fa fa-list-alt"></i> Material Unit List
        </a>

    </x-slot>
    <x-mm.panel class="tw-p-4">
        <form class="form-horizontal" action="{{ route('rst.material-unit.update', $itemUnit->id) }}"
            method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('partials._alert_message')

            <input type="hidden" name="id" value="{{ $itemUnit->id }}">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="form-field-1-1"> Unit Name </label>

                <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                    <input type="text" class="form-control" name="name"
                        value="{{ old('name') ?: $itemUnit->name }}" placeholder="Material Unit Name">

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
                        value="{{ old('conversion') ?: $itemUnit->conversion }}" placeholder="Conversion">

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

                    <select name="status" class="form-control select2">
                        <option value="1" {{ (old('name') ?: $itemUnit->status) ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0" {{ (old('name') ?: $itemUnit->status) ? '' : 'selected' }}>
                            Deactive
                        </option>
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

                    <button class="btn btn-success"> <i class="fa fa-save"></i> Update </button>
                    <button class="btn btn-gray" type="Reset"> <i class="fa fa-refresh"></i> Reset
                    </button>
                    <a href="{{ route('rst.material-unit.index') }}" class="btn btn-info"> <i
                            class="fa fa-list"></i> List </a>

                </div>
            </div>

        </form>

    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>

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
