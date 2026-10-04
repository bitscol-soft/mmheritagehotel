@extends('layouts.master')

@section('title', 'Booking Night Audit')
@php
    $checkNUll = $nightaudits[0] != null || $nightaudits[0] != '';
@endphp

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report" title="Booking night audit report" description="Night audits closed in the selected period.">
    <x-alert-message />
    @if ($checkNUll && count($nightaudits[0]->details) > 0)
        <x-mm.panel class="mm-report-filter">
            <form action="" method="GET" class="mm-setup-filter mm-report-form">
                <div class="mm-report-field">
                    <div class="input-group">
                        <label class="input-group-addon">From</label>
                        <input type="text" class="date-picker form-control text-center"
                            autocomplete="off" name="from_date" value="{{ request('from_date') }}"
                            placeholder="From Date">
                    </div>
                </div>
                <div class="mm-report-field">
                    <div class="input-group">
                        <label class="input-group-addon">To</label>
                        <input type="text" class="form-control date-picker text-center"
                            autocomplete="off" name="to_date" value="{{ request('to_date') }}"
                            placeholder="To Date">
                    </div>
                </div>
                <div class="mm-report-field">
                    <div class="btn-group">
                        <button type="submit" class="mm-button">
                            <i class="fa fa-search-plus"></i> Search
                        </button>
                        <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                            <i class="fa fa-refresh"></i>
                        </a>
                    </div>
                </div>
            </form>
        </x-mm.panel>
    @endif
    @if (collect(request()->all())->count() > 0)
        <x-mm.panel>
            <x-mm.table-scroll label="Night audit report">
                @include('hotel/reports/night-closing/export/excel')
            </x-mm.table-scroll>
            <x-paginate :data="$nightaudits" />

            <x-export-button :pdf=1 :excel=1 :print=1 />
        </x-mm.panel>
    @endif
</x-mm.page>

@foreach ($nightaudits as $audit)
    @include('hotel/reports/night-closing/details')
@endforeach
@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
