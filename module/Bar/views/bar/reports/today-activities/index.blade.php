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
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv" title="Today's activities" description="Transactions recorded for the selected invoice and date.">
    @include('partials._alert_message')
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form">
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
                @include('bar.reports.today-activities.export.excel')
                {{-- <x-paginate :data="$transactions" /> --}}
            </div>
            <x-export-button :pdf=1 :excel=1 />
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
