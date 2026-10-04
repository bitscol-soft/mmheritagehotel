@extends('layouts.master')

@section('title',' Edit Feature List')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-form" title="Add a feature" description="Add a feature box to the home page.">
    <x-alert-message />

    <x-mm.panel class="tw-p-5 mm-web-narrow">
        <form class="form-horizontal" id="companyForm" action="{{ route('website-core.feature_list.store') }}" method="post">
            @csrf

            <div class="row">
                <div class="col-sm-12">
                    <x-mm.field label="Feature Title" id="feature-list-title" name="feature_list_title" value="" placeholder="Enter Feature Title" :error="$errors->first('feature_list_title')" />
                </div>
                <div class="col-sm-12">
                    <x-mm.field label="Feature Subtitle" id="feature-list-subtitle" name="feature_list_subtitle" value="" placeholder="Enter Feature Subtitle" :error="$errors->first('feature_list_subtitle')" />
                </div>
                <div class="col-sm-12">
                    <x-mm.field label="Feature Icon (FontAwsome 4.7)" id="feature-icon" name="feature_icon" value="" placeholder="EX - fa fa-bed" :error="$errors->first('feature_icon')" />
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

<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

@stop
