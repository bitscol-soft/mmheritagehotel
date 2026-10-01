@extends('layouts.master')

@section('title', 'In House Guest List')

@section('page-header')
    <i class="fa fa-plus-circle"></i> In House Guest List
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report" title="In-house guest list" description="Guests staying in the hotel, filtered by date.">
    <x-alert-message />
    <x-mm.panel class="mm-report-filter">
        <form action="" method="GET" class="mm-setup-filter mm-report-form">
            <div class="mm-report-field">
                <div class="input-group">
                    <label class="input-group-addon"><i class="fa fa-calendar"></i></label>
                    <input type="text" class="date-picker form-control text-center" name="to"
                        value="{{ request('to', date('Y-m-d')) }}" placeholder="To Date"
                        autocomplete="off">
                </div>
            </div>
            <div class="mm-report-field">
                <div class="btn-group">
                    <button type="submit" class="mm-button"> <i class="fa fa-search"></i>
                        Search </button>
                    <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset"> <i class="fa fa-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </x-mm.panel>
    @if (collect(request()->all())->count() > 0)
        <x-mm.panel>
            <x-mm.table-scroll label="In-house guests">
                @include('hotel.reports.in-house-guest.export.excel')
            </x-mm.table-scroll>
            <x-paginate :data="$bookings" />

            <x-export-button :pdf=1 :excel=1 />
        </x-mm.panel>
    @endif
</x-mm.page>
@endsection
