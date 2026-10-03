@extends('layouts.master')

@section('title', ' Night Audit')

@section('content')

    <x-mm.styles />
    <x-mm.page class="mm-night-audit" title="Night audit" description="Closed audit days with their reservations, rooms, collections and dues.">
        <x-slot name="actions">
            <a class="mm-button" href="{{ route('night-audits.create') }}">
                <i class="ace-icon fa fa-plus" aria-hidden="true"></i> Generate
            </a>
        </x-slot>
        <x-alert-message />

        <x-mm.panel class="mm-co-card">
            <h2 class="mm-co-title">Filter by date</h2>
            <form action="" method="GET">
                <div class="mm-pc-search">
                    <div class="mm-co-field" role="group" aria-labelledby="mm-na-from">
                        <span id="mm-na-from" class="mm-co-label">From</span>
                        <input type="text" class="date-picker form-control text-center"
                                            autocomplete="off" name="from_date" value="{{ request('from_date') }}"
                                            placeholder="From Date">
                    </div>
                    <div class="mm-co-field" role="group" aria-labelledby="mm-na-to">
                        <span id="mm-na-to" class="mm-co-label">To</span>
                        <input type="text" class="form-control date-picker text-center"
                                            autocomplete="off" name="to_date" value="{{ request('to_date') }}"
                                            placeholder="To Date">
                    </div>
                    <div class="mm-pc-search-actions">
                        <button type="submit" class="mm-button">
                            <i class="fa fa-search-plus" aria-hidden="true"></i> Search
                        </button>
                        <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset">
                            <i class="fa fa-refresh" aria-hidden="true"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </x-mm.panel>

        <x-mm.panel class="mm-co-charges">
            <x-mm.table-scroll label="Night audits">
                @include('night-audits.export.excel')
            </x-mm.table-scroll>

            <x-export-button pdf="1" excel="1" />

            <x-paginate :data="$nightaudits" />
        </x-mm.panel>
    </x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
