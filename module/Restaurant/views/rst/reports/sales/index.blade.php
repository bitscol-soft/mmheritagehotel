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
<x-mm.page class="mm-report mm-rst" title="Sales report" description="Restaurant invoices for the selected filters.">
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

            <div class="input-group">
                <span class="input-group-addon">Date</span>
                <input type="text" name="date" value="{{ request('date') }}"
                    class="form-control date-picker" placeholder="Date" autocomplete="off">
            </div>

            <label class="mm-report-check">
                <input type="checkbox" name="outdoor_sale" value="1"
                    {{ request('outdoor_sale') == 1 ? 'checked' : '' }}>
                <span class="lbl"><b>Outdoor sale</b></span>
            </label>

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
        <x-mm.table-scroll label="Sales">
            @include('rst/reports/sales/export/excel')
            {{-- rst/reports/sales/export/excel --}}
        </x-mm.table-scroll>

        <x-paginate :data="$sales" />

        <x-export-button :pdf=1 :excel=1 />
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
