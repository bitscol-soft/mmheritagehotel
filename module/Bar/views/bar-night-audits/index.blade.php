@extends('layouts.master')

@section('title', 'Bar Night Audit')
@php
    $checkNUll = $nightaudits[0] != null || $nightaudits[0] != '';
@endphp

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv" title="Bar night audit" description="Bar night audits closed in the selected period.">
    <x-slot name="actions">
        {{-- <span class="widget-toolbar">
            <a href="{{ route('bar.night-audits.create') }}">
                <i class="ace-icon fa fa-plus"></i> Generate
            </a>
        </span> --}}
    </x-slot>
    @if ($checkNUll && count($nightaudits[0]->details) > 0)
            <x-mm.panel class="mm-report-filter">
                <form action="" method="GET" class="mm-setup-filter mm-report-form">
                    <div class="input-group">
                        <label class="input-group-addon">From</label>
                        <input type="text" class="date-picker form-control text-center"
                            autocomplete="off" name="from_date" value="{{ request('from_date') }}"
                            placeholder="From Date">
                    </div>

                    <div class="input-group">
                        <label class="input-group-addon">To</label>
                        <input type="text" class="form-control date-picker text-center"
                            autocomplete="off" name="to_date" value="{{ request('to_date') }}"
                            placeholder="To Date">
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="mm-button">
                            <i class="fa fa-search-plus"></i> Search
                        </button>
                        <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                            <i class="fa fa-refresh"></i>
                        </a>
                    </div>
                </form>
            </x-mm.panel>
    @endif
    <x-mm.panel class="tw-p-4">

        <div class="row">

            <x-alert-message />

            <div class="col-xs-12">

                @include('bar-night-audits.export.excel')

                @if ($checkNUll && count($nightaudits[0]->details) > 0)
                    <x-export-button pdf="1" excel="1" />
                @endif

                <x-paginate :data="$nightaudits" />
                {{-- @include('partials._paginate', ['data' => $nightaudits]) --}}
            </div>
        </div>
    </x-mm.panel>

    @foreach ($nightaudits as $audit)
        @include('bar-night-audits.details')
    @endforeach

</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
