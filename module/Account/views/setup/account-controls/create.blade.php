@extends('layouts.master')
@section('title','Account Controls')
@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-acc mm-rst mm-rst-inv mm-rst-form" title="Account Controls" description="Add an account control.">
    <x-slot name="actions">
        <a class="mm-button" href="{{route('account-controls.index')}}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- INPUTS -->
        <form action="{{route('account-controls.store')}}" method="post">
            @csrf
            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                <div class="col-sm-12 px-4">
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name"> <b>Company Name</b> </label>

                        <div class="col-sm-9">
                            <select required name="company_id" class="chosen-select-100-percent" data-placeholder="- Select Account -">
                                <option></option>
                                @foreach($company as $key => $name)
                                <option value="{{ $key }}" {{auth()->user()->company->id == $key ? 'selected' : ''}}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Account Group -->
                    @include('includes.inputs.option-select', ['modelVariable' => 'accountGroups'])

                    <!-- Name -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name"> <b>Control Name <sup class="text-danger">
                                    *</sup></b> </label>

                        <div class="col-sm-9">
                            <input id="name" name="name" type="text" class="form-control input-sm" placeholder="Name" value="{{old('name')}}">
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
<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

<script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@endsection