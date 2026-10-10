@extends('layouts.master')
@section('title', 'Fund Transfer Create')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-acc mm-rst mm-rst-inv mm-rst-form" title="Fund Transfer Create" description="Move an amount from one account to another.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('fund-transfers.index') }}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- INPUTS -->
        <form action="{{route('fund-transfers.store')}}" method="post">
            @csrf
            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                <div class="col-sm-12 px-4">

                    <!-- Date -->
                @include('includes.inputs.date-field', ['name' => 'date', 'is_required' => 'required'])

                <!-- From Account -->
                    <div class="form-group row">
                        <label class="control-label col-sm-3" for="account_id">
                            <b>
                                From Account <sup class="text-danger">*</sup>
                                <span class="text-success">(<span
                                        class="from-text-account-balance">0</span>)</span>
                            </b>
                        </label>

                        <div class="col-sm-9">
                            <select id="from_account_id" name="from_account_id"
                                    class="chosen-select-100-percent" data-placeholder="- Select Account -"
                                    onchange="onChangeAccountId(this)" required>
                                <option value=""></option>

                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}"
                                            data-balance="{{ $account->balance ?? 0 }}" {{ oldSelect('account_id', $account->id) }}>{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- To Account -->
                    <div class="form-group row">
                        <label class="control-label col-sm-3" for="account_id">
                            <b>
                                To Account <sup class="text-danger">*</sup>
                                <span class="text-success">(<span
                                        class="to-text-account-balance">0</span>)</span>
                            </b>
                        </label>

                        <div class="col-sm-9">
                            <select id="to_account_id" name="to_account_id"
                                    class="chosen-select-100-percent" data-placeholder="- Select Account -"
                                    onchange="onChangeAccountId(this)" required>
                                <option value=""></option>

                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}"
                                            data-balance="{{ $account->balance ?? 0 }}" {{ oldSelect('account_id', $account->id) }}>{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                @include('includes.inputs.input-field', ['name' => 'description', 'is_required' => 1])

                <!-- Reference -->
                @include('includes.inputs.input-field', ['name' => 'reference'])

                <!-- Amount -->
                @include('includes.inputs.input-field', ['name' => 'amount', 'is_number' => true, 'is_required' => 1])

                <!-- Submit -->
                    <button class="btn btn-primary btn-sm pull-right save-btn" disabled><i
                            class="fa fa-save"></i> Save
                    </button>
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
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    <script>
        const saveButton = $('.save-btn')

        const fromAccountId = $('#from_account_id');
        const toAccountId = $('#to_account_id');

        $(document).ready(function () {

            $('#from_account_id').change(function () {
                $('.from-text-account-balance').text($(this).find('option:selected').data('balance'))
            })

            $('#to_account_id').change(function () {
                $('.to-text-account-balance').text($(this).find('option:selected').data('balance'))
            })

            $('#amount').keyup(function () {
                const fromAccountBalance = Number($('.from-text-account-balance').text());

                if (Number($(this).val()) > fromAccountBalance) {
                    $(this).val(fromAccountBalance);
                    showAlertMessage('Insufficient balance!', 3000);
                }
            })
        });

        $('#balance_type').change(checkSaveButton)

        $('#date').change(checkSaveButton)

        $('#description').keyup(checkSaveButton)

        function checkSaveButton() {
            saveButton.attr('disabled', false)

            if ($('#date').val() == '') {
                saveButton.attr('disabled', true)
            }

            if ($('#description').val() == '') {
                saveButton.attr('disabled', true)
            }
        }

        function onChangeAccountId(el) {
            if (fromAccountId.val() == toAccountId.val() && fromAccountId.val() != '') {
                showAlertMessage('Select different account!', 3000);
                $(el).val('').trigger('chosen:updated');
            }
        }
    </script>

@endsection

