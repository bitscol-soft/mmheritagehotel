@extends('layouts.master')
@section('title','Account Subsidiary Edit')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-acc mm-rst mm-rst-inv mm-rst-form" title="Account Subsidiary Edit" description="Update the subsidiary ledger.">
    <x-slot name="actions">
        <a class="mm-button" href="{{route('account-subsidiaries.index')}}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- INPUTS -->
        <form action="{{route('account-subsidiaries.update', $accountSubsidiary->id)}}" method="post">
            @csrf @method('put')
            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                <div class="col-sm-12 px-4">
                    <!-- Account Group -->
                    @include('includes.inputs.option-select', ['modelVariable' => 'accountGroups', 'is_required' => true, 'edit_id' => $accountSubsidiary->account_group_id])

                    <!-- Account Controls -->
                    @include('includes.inputs.option-select', ['modelVariable' => 'accountControls', 'is_required' => true, 'edit_id' => $accountSubsidiary->account_control_id])

                    <!-- Name -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name">
                            <b>Subsidiary Name <sup class="text-danger">*</sup></b>
                        </label>

                        <div class="col-sm-9">
                            <input id="name" name="name" required type="text" class="form-control input-sm" placeholder="Name" value="{{$accountSubsidiary->name}}">
                        </div>
                    </div>

                    <!-- Status -->
                    @include('includes.inputs.status', ['edit_id' => $accountSubsidiary->status])

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

    <script>
        $(document).ready(function () {
            const accountControlId = $('#account_control_id');

            $('#account_group_id').change(function () {
                $.get(`{{route('ajax.account-controls')}}?account_group_id=${$(this).val()}`, function (res) {
                    accountControlId.empty().append('<option></option>')

                    res.forEach(function (item) {
                        accountControlId.append(`<option value="${item.id}">${item.name}</option>`)
                    })

                    accountControlId.trigger('chosen:updated');
                })
            })
        });
    </script>

@endsection

