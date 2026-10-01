@extends('layouts.master')
@section('title', 'Service Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Service Report
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report" title="Service report" description="Services billed to guests in the selected period.">
    @include('partials._alert_message')
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form">

            <div class="input-group">
                <span class="input-group-addon">Date</span>
                <input type="text" name="from_date"
                    value="{{ request('from_date') }}"
                    class="form-control date-picker" autocomplete="off">
                <span class="input-group-addon"><i
                            class="fa fa-calendar"></i></span>
                        <input type="text" name="to_date"
                            value="{{ request('to_date') }}"
                            class="form-control date-picker" autocomplete="off">
            </div>

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
    <x-mm.panel>
        <x-mm.table-scroll label="Service report">
            @include('hotel/reports/services/export/excel')
        </x-mm.table-scroll>

        <x-paginate :data="$services" />

        <x-export-button :pdf=1 :excel=1 />
    </x-mm.panel>
</x-mm.page>
@endsection

@section('js')
@endsection
