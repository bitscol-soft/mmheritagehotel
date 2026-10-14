@extends('layouts.master')

@section('title', 'Restaurant Night Audit')

@php
    $checkNUll = $nightaudits[0] != null || $nightaudits[0] != '';
@endphp

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-hs-audit mm-rst" title="Restaurant night audit" description="Restaurant night audits closed in the selected period.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('rst.night-audits.create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Generate
        </a>
    </x-slot>

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

    <x-mm.panel>
        <x-alert-message />

        <x-mm.table-scroll label="Restaurant night audits">
            @include('restaurant-night-audits/export.excel')
        </x-mm.table-scroll>

        @if ($checkNUll && count($nightaudits[0]->details) > 0)
            <x-export-button pdf="1" excel="1" />
        @endif
        <x-paginate :data="$nightaudits" />
        {{-- @include('partials._paginate', ['data' => $nightaudits]) --}}
    </x-mm.panel>
</x-mm.page>

@foreach ($nightaudits as $audit)
    @include('restaurant-night-audits.details')
@endforeach

@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
