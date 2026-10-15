@extends('layouts.master')
@section('title','Payments')
@push('style')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv mm-rst-form" title="Payment" description="Update a supplier payment.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('acc-payments.index') }}"><i class="fa fa-list"></i> List</a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- INPUTS -->
        <form action="{{ route('acc-payments.update', $payment->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row" style="width: 100%; margin: 0 0 20px !important;">
                <div class="col-sm-12 px-4">

                    <!-- Invoice -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label"> <b>Invoice</b> </label>

                        <div class="col-sm-9">
                            <p class="form-control-static">{{ $payment->invoice_no }}</p>
                        </div>
                    </div>

                    <!-- Supplier -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="supplier_id"> <b>Supplier <sup class="text-danger">*</sup></b> </label>

                        <div class="col-sm-9">
                            <select required id="supplier_id" name="supplier_id" class="chosen-select-100-percent" data-placeholder="- Select Supplier -">
                                <option></option>
                                @foreach($suppliers as $key => $name)
                                <option value="{{ $key }}" {{ old('supplier_id', $payment->supplier_id) == $key ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="date"> <b>Date <sup class="text-danger">*</sup></b> </label>

                        <div class="col-sm-9">
                            <input required id="date" name="date" type="date" class="form-control input-sm" value="{{ old('date', $payment->date) }}">
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="amount"> <b>Amount <sup class="text-danger">*</sup></b> </label>

                        <div class="col-sm-9">
                            <input required id="amount" name="amount" type="number" step="0.01" min="0.01" class="form-control input-sm" placeholder="0.00" value="{{ old('amount', $payment->amount) }}">
                        </div>
                    </div>

                    <!-- Reference -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="transaction_no"> <b>Reference</b> </label>

                        <div class="col-sm-9">
                            <input id="transaction_no" name="transaction_no" type="text" class="form-control input-sm" placeholder="Cheque / payment no" value="{{ old('transaction_no', $payment->transaction_no) }}">
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="remarks"> <b>Remarks</b> </label>

                        <div class="col-sm-9">
                            <textarea id="remarks" name="remarks" class="form-control input-sm" rows="3" placeholder="Remarks">{{ old('remarks', $payment->remarks) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 offset-sm-3">
                            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Update</button>
                            <button type="Reset" class="btn btn-gray btn-sm"><i class="fa fa-refresh"></i> Reset</button>
                        </div>
                    </div>

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
