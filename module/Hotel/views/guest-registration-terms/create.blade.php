
@extends('layouts.master')
@section('title','Add Registration Terms')

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup" title="Add registration terms" description="Create the terms shown on the guest registration form.">
    <x-slot name="actions"><a class="mm-button mm-button-secondary" href="{{ route('guest-registration-terms.index') }}"><i class="fa fa-list-alt"></i> Registration Terms List</a></x-slot>
    @include('partials._alert_message')

    <x-mm.panel>
        <form class="form-horizontal" id="companyForm" action="{{ route('guest-registration-terms.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-sm-12">

                    <hr>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Title<sup class="text-danger">*</sup></label>

                        <div class="col-xs-12 col-sm-8">
                            <textarea name="title" class="form-control" cols="30" rows="10" required>{{ old('title') }}</textarea>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-actions center" style="text-align: right !important;">
                <button type="submit" class="mm-button">
                    <i class="fa fa-save"></i>
                    Save
                </button>

                <a href="{{ route('guest-registration-terms.index') }}" class="mm-button mm-button-secondary">
                    <i class="fa fa-backward"></i> Back List
                </a>
            </div>
        </form>
    </x-mm.panel>
</x-mm.page>
@endsection
