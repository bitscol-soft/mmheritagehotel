@extends('layouts.master')
@section('title','Account Controls')
@section('page-header')
    <i class="fa fa-list"></i> Account Control Edit
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush


@section('content')

<x-mm.styles />
<x-mm.page class="mm-acc mm-rst mm-rst-inv mm-rst-form" title="Account Control Edit" description="Update the account control.">
    <x-slot name="actions">
        <a class="mm-button" href="{{route('account-controls.index')}}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- INPUTS -->
        <form action="{{route('account-controls.update', $accountControl->id)}}" method="post">
            @csrf @method('put')
            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                <div class="col-sm-12 px-4">
                    <!-- Account Group -->
                    @include('includes.inputs.option-select', ['edit_id' => $accountControl->account_group_id, 'modelVariable' => 'accountGroups'])

                    <!-- Name -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name"> <b>Control Name <sup class="text-danger">
                                    *</sup></b> </label>

                        <div class="col-sm-9">
                            <input id="name" name="name" type="text" class="form-control input-sm" placeholder="Name" value="{{$accountControl->name}}">
                        </div>
                    </div>

                    <!-- Status -->
                    @include('includes.inputs.status', ['edit_id' => $accountControl->status])

                    <!-- Submit -->
                    <button class="btn btn-primary btn-sm pull-right"><i class="fa fa-edit"></i> Update</button>
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


