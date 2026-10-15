@extends('layouts.master')

@section('title', 'Payment')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}"/>

@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Payment Lists" description="Supplier payments.">
    <x-slot name="actions">
        @if (hasPermission('acc_payments.create', $slugs))
            <a class="mm-button" href="{{ route('acc-payments.create') }}"><i class="fa fa-plus"></i> Add New</a>
        @endif
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form" action="" method="get">
            <div class="input-group">
                <label class="input-group-addon" for="">
                    Invoice No
                </label>

                <select name="invoice_no" class="chosen-select-100-percent"
                    data-placeholder="- Select Invoice -" style="display: none;">
                    <option></option>
                    @foreach ($payments->pluck('invoice_no')->unique()->filter() as $inv)
                        <option value="{{ $inv }}" @if (request('invoice_no') == $inv) selected @endif>{{ $inv }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group">
                <label class="input-group-addon" for="">
                    Reference
                </label>

                <select name="reference" class="chosen-select-100-percent"
                    data-placeholder="- Select Reference -" style="display: none;">
                    <option></option>
                    @foreach ($payments->pluck('transaction_no')->unique()->filter() as $ref)
                        <option value="{{ $ref }}" @if (request('reference') == $ref) selected @endif>{{ $ref }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group">
                <button type="submit" class="mm-button mm-button-primary">
                    <i class="fa fa-search"></i>
                    Search</button>
            </div>
        </form>
    </x-mm.panel>

    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        @if ($payments->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>Reference</th>
                            <th>Supplier</th>
                            <th class="text-right">Amount</th>
                            <th>Remarks</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->date }}</td>
                                <td>{{ $payment->invoice_no }}</td>
                                <td>{{ $payment->transaction_no }}</td>
                                <td>{{ optional($payment->supplier)->name }}</td>
                                <td class="text-right">{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->remarks }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        @if (hasPermission('acc_payments.edit', $slugs))
                                            <a href="{{ route('acc-payments.edit', $payment->id) }}" class="btn btn-sm btn-success" title="Edit">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                        @endif
                                        @if (hasPermission('acc_payments.delete', $slugs))
                                            <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                onclick="delete_item('{{ route('acc-payments.destroy', $payment->id) }}')">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center">
                <span class="text-warning">No Records Founds Yet!</span>
            </div>
        @endif
        <br>
        <!-- INPUTS -->
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@endsection
