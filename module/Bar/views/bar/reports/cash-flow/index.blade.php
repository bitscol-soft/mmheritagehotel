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
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv" title="Cash flow" description="Cash received and paid out for the selected invoice and dates.">
    @include('partials._alert_message')
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form">
            <input type="text" name="invoice_no"
                value="{{ request('invoice_no') }}" class="form-control"
                placeholder="Invoice No">

            <input type="text" name="from_date" value="{{ request('from_date') }}"
                class="form-control date-picker">

            <input type="text" name="to_date" value="{{ request('to_date') }}"
                class="form-control date-picker">

            <div class="btn-group">
                <button class="mm-button" type="submit">
                    <i class="fa fa-search"></i> Search
                </button>
                <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        <div class="row">
            <div class="col-sm-12 px-2">
                @include('bar/reports/cash-flow/export/excel')

                <x-paginate :data="$cashFlows" />

                <x-export-button :pdf=1 :excel=1 />
            </div>
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
