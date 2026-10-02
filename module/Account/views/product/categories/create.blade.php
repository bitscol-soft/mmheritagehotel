@extends('layouts.master')
@section('title', 'Category')
@section('page-header')
    <i class="fa fa-plus"></i> Category Create
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-acc mm-rst mm-rst-inv mm-rst-form" title="Category Create" description="Add a product category.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('categories.index') }}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- INPUTS -->
        <form action="{{ route('categories.store') }}" method="post">
            @csrf
            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                <div class="col-sm-12 px-4">
                    <!-- Name -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name">
                            <b>Name <sup class="text-danger">*</sup></b>
                        </label>

                        <div class="col-sm-9">
                            <input id="name" name="name" type="text" required class="form-control input-sm" placeholder="Name" value="{{ old('name') }}">
                        </div>
                    </div>

                    <!-- Submit -->
                    <button class="btn btn-primary btn-sm pull-right"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')



@endsection


