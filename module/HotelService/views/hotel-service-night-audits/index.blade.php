@extends('layouts.master')

@section('title', 'Hotel Service Night Audit')
@php
    $checkNUll = $nightaudits[0] != null || $nightaudits[0] != '';
@endphp
@section('page-header')
    <i class="fa fa-info-circle"></i>Hotel Service Night Audit <span class="badge badge-info">{{ $checkNUll ? $nightaudits[0]->details->count() : 0 }}</span>
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report mm-hs-audit" title="Hotel service night audit" description="Service night audits closed in the selected period.">
    <x-alert-message />
    @if( $checkNUll && count($nightaudits[0]->details) > 0)
        <x-mm.panel class="mm-report-filter">
            <form action="" method="GET" class="mm-setup-filter mm-report-form">
                <div class="mm-report-field">
                    <div class="input-group">
                        <label class="input-group-addon">From</label>
                        <input type="text" class="date-picker form-control text-center" autocomplete="off" name="from_date" value="{{ request('from_date') }}" placeholder="From Date">
                    </div>
                </div>
                <div class="mm-report-field">
                    <div class="input-group">
                        <label class="input-group-addon">To</label>
                        <input type="text" class="form-control date-picker text-center" autocomplete="off" name="to_date" value="{{ request('to_date') }}" placeholder="To Date">
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

    <x-mm.panel>
        <x-mm.table-scroll label="Hotel service night audits">
            @include('hotel-service-night-audits.export.excel')
        </x-mm.table-scroll>

        @if( $checkNUll && count($nightaudits[0]->details) > 0) <x-export-button pdf="1" excel="1" /> @endif

        <x-paginate :data="$nightaudits" />
        {{-- @include('partials._paginate', ['data' => $nightaudits]) --}}
    </x-mm.panel>
</x-mm.page>

@foreach ($nightaudits as $audit)
    @include('hotel-service-night-audits.details')
@endforeach

@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
