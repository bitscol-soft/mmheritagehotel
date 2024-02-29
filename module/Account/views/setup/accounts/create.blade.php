@extends('layouts.master')
@section('title','Account')
@section('page-header')
    <i class="fa fa-list"></i> Account
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>

@endpush

@section('content')
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">

            @include('partials._alert_message')

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{route('accounts.index')}}"
                                   class="dt-button btn btn-white btn-info btn-bold" title="List" data-toggle="tooltip"
                                   tabindex="0" aria-controls="dynamic-table">
                                    <span>
                                        <i class="fa fa-list bigger-110"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space"></div>

                <!-- INPUTS -->
                <form action="{{route('accounts.store')}}" method="post">
                    @csrf
                    <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                        <div class="col-sm-12 px-4">


                            <!-- Name -->
                            @include('includes.inputs.input-field', ['name' => 'name', 'is_required' => 'required'])


                            <!-- Account Group -->
                            @include('includes.inputs.option-select', ['modelVariable' => 'accountGroups', 'is_required' => true])


                            <!-- Account Controls -->
                            @include('includes.inputs.option-select', ['modelVariable' => 'accountControls', 'is_required' => true])


                            <!-- Account Subsidiaries -->
                            @include('includes.inputs.option-select', ['modelVariable' => 'accountSubsidiaries', 'is_required' => true])


                            <!-- Opening Balance -->
                            {{-- @include('includes.inputs.input-field', ['name' => 'opening_balance', 'is_number' => true, 'title' => 'Opening Balance']) --}}


                            <!-- Remarks -->
                            @include('includes.inputs.input-field', ['name' => 'remarks'])

                            <!-- Submit -->
                            <button class="btn btn-primary btn-sm pull-right"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    
    
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <script>
        $(document).ready(function () {
            const accountControlId = $('#account_control_id');
            const accountSubsidiaryId = $('#account_subsidiary_id');

            $('#account_group_id').change(function () {
                $.get(`{{route('ajax.account-controls')}}?account_group_id=${$(this).val()}`, function (res) {
                    accountControlId.empty().append('<option></option>')

                    res.forEach(function (item) {
                        accountControlId.append(`<option value="${item.id}">${item.name}</option>`)
                    })

                    accountControlId.trigger('chosen:updated');
                    accountControlId.trigger('change')
                })
            })

            $('#account_control_id').change(function () {
                $.get(`{{route('ajax.account-subsidiaries')}}?account_control_id=${$(this).val()}`, function (res) {
                    accountSubsidiaryId.empty().append('<option></option>')

                    res.forEach(function (item) {
                        accountSubsidiaryId.append(`<option value="${item.id}">${item.name}</option>`)
                    })

                    accountSubsidiaryId.trigger('chosen:updated');
                })
            })
        });
    </script>
@endsection


