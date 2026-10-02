@extends('layouts.master')
@section('title', 'Cash Flow')

@section('page-header')
    <i class="fa fa-info-circle"></i> Cash Flow
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

    </style>
@stop


@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-rst" title="Cash flow" description="Cash received and paid out for the selected invoice and dates.">
    @include('partials._alert_message')
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form">

            <div class="input-group">
                <span class="input-group-addon">Invoice</span>
                <input type="text" name="invoice_no"
                    value="{{ request('invoice_no') }}" class="form-control"
                    placeholder="Invoice No">
            </div>

            <div class="input-group">
                <span class="input-group-addon">From</span>
                <input type="text" name="from_date" value="{{ request('from_date') }}"
                    class="form-control date-picker">
            </div>

            <div class="input-group">
                <span class="input-group-addon">To</span>
                <input type="text" name="to_date" value="{{ request('to_date') }}"
                    class="form-control date-picker">
            </div>

            <div class="btn-group" style="display: flex">
                <button class="mm-button" type="submit">
                    <i class="fa fa-search"></i> Search
                </button>
                <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
        </form>
    </x-mm.panel>
    <x-mm.panel>
        <x-mm.table-scroll label="Cash flow">
            @include('bar/reports/cash-flow/export/excel')
        </x-mm.table-scroll>

        <x-paginate :data="$cashFlows" />

        <x-export-button :pdf=1 :excel=1 />
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
