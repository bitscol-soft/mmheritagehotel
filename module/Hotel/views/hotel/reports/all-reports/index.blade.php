@extends('layouts.master')
@section('title', 'Over All Reports')

@section('page-header')
    <i class="fa fa-info-circle"></i> Over All Reports
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report" title="Over all reports" description="Every booking activity for a date range, ready to export.">
    @include('partials._alert_message')
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form">

            <div class="input-group">
                <span class="input-group-addon">Invoice</span>
                <input type="text" name="invoice_no"
                    value="{{ request('invoice_no') }}" class="form-control" autocomplete="off"
                    placeholder="Invoice No">
            </div>

            <div class="input-group">
                <span class="input-group-addon">Date</span>
                <input type="text" name="from_date"
                    value="{{ request('from_date') }}" autocomplete="off"
                    class="form-control date-picker">
                <span class="input-group-addon"><i
                            class="fa fa-calendar"></i></span>
                        <input type="text" name="to_date"
                            value="{{ request('to_date') }}" autocomplete="off"
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
    @if (collect(request()->all())->count() > 0)
        <x-mm.panel>
            <x-mm.table-scroll label="Over all report">
                @include('hotel/reports/all-reports/export/excel')
            </x-mm.table-scroll>
            <x-paginate :data="$transactions" />

            <x-export-button :pdf=1 :excel=1 />
        </x-mm.panel>
    @endif
</x-mm.page>
@endsection

@section('js')
@endsection
