@extends('layouts.master')
@section('title','Account')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-acc mm-rst mm-rst-inv mm-rst-form" title="Account" description="Add an account to the chart of accounts.">
    <x-slot name="actions">
        <a class="mm-button" href="{{route('accounts.index')}}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
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
    </x-mm.panel>
</x-mm.page>

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

