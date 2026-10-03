@extends('layouts.master')
@section('title', 'Sale List')

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
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv" title="Sales report" description="Bar invoices for the selected filters.">
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
                <span class="input-group-addon">Guest</span>
                <input type="text" name="guest_name" class="form-control"
                    placeholder="Guest/Customer">
            </div>

            <div class="mm-report-field"><x-widget.date-filter /></div>

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
                @include('bar.reports.sales.export.excel')
                <x-paginate :data="$sales" />
            </div>
            <x-export-button :pdf=1 :excel=1 />
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
