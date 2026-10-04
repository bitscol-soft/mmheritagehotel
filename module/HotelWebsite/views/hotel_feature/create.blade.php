@extends('layouts.master')

@section('title',' Edit Feature Header')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-form" title="Homepage feature heading" description="Heading and text above the home page feature boxes.">
    @include('partials._alert_message')

    <x-mm.panel class="tw-p-5 mm-web-narrow">
        <form class="form-horizontal" id="companyForm" action="{{ $feature->exists ? route('website-core.feature.update', $feature->id) : route('website-core.feature.store') }}" method="post">
            @csrf
            @if($feature->exists)
            @method('PUT')
            @endif
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1-1">Feature Title</label>

                        <div class="col-xs-12 col-sm-8 @error('feature_title') has-error @enderror">
                            <input type="text" class="form-control input-sm" name="feature_title" value="{{ $feature->title ?? '' }}" placeholder="Enter Feature Title">

                            @error('feature_title')
                            <span class="text-danger"> {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1-1">Feature Subtitle</label>

                        <div class="col-xs-12 col-sm-8 @error('feature_subtitle') has-error @enderror">
                            <input type="text" class="form-control input-sm" name="feature_subtitle" value="{{ $feature->sub_title ?? '' }}" placeholder="Enter Feature Subtitle">

                            @error('feature_subtitle')
                            <span class="text-danger"> {{ $message }}</span>
                            @enderror
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


@stop
