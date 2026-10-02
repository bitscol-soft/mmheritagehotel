@extends('layouts.master')

@section('title', 'Customer')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Customer Create
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-acc mm-rst mm-rst-inv mm-rst-form" title="Customer Create" description="Add a customer.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('acc-customers.index') }}"><i class="fa fa-list-alt"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- INPUTS -->
        <form action="{{ route('acc-customers.store') }}" method="post">
            @csrf
            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                <div class="col-sm-12 px-4">
                    <!-- Name -->
                    @include('includes.inputs.input-field', ['name' => 'name', 'is_required' => true])

                    <!-- Mobile -->
                    @include('includes.inputs.input-field', ['name' => 'mobile'])

                    <!-- Email -->
                    @include('includes.inputs.input-field', ['name' => 'email', 'type' => 'email'])

                    <!-- Address -->
                    @include('includes.inputs.input-field', ['name' => 'address'])

                    <!-- Opening balance -->
                    {{-- @include('includes.inputs.input-field', ['name' => 'opening_balance', 'title' => 'Opening Balance']) --}}
                    <!-- <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name">
                            <b>Opening Balance </b>
                        </label>

                        <div class="col-sm-9">
                            <input id="opening_balance" name="opening_balance" onkeypress="return event.charCode >= 46 && event.charCode <= 57" type="text" class="form-control input-sm" placeholder="Opening Balance" value="{{ old('opening_balance') }}">
                        </div>
                    </div> -->

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
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@endsection


