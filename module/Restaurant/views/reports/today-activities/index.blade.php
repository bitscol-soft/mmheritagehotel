@extends('layouts.master')
@section('title', 'Today Report')

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
<x-mm.page class="mm-report mm-rst" title="Today's activities" description="Transactions recorded for the selected invoice and date.">
    @include('partials._alert_message')
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form">

            <div class="input-group">
                <span class="input-group-addon">Invoice</span>
                <input type="text" name="invoice_no"
                    value="{{ request('invoice_no') }}" class="form-control"
                    placeholder="Invoice No">
            </div>

            <div class="mm-report-field"><x-widget.date-filter /></div>

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
        <x-mm.table-scroll label="Today's activities">
            @include('reports.today-activities.export.excel')
            {{-- <x-paginate :data="$transactions" /> --}}
        </x-mm.table-scroll>

        <x-export-button :pdf=1 :excel=1 />
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
