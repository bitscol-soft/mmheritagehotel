@extends('layouts.master')

@section('title', 'Today In House Guest List')

@section('content')
<x-mm.styles />
<x-mm.page class="mm-report" title="Today in-house guest list" description="Guests staying in the hotel today.">
    <x-alert-message />
    <x-mm.panel>
        <x-mm.table-scroll label="In-house guests">
            @include('hotel.reports.today-in-house.export.excel')
        </x-mm.table-scroll>
        <x-paginate :data="$bookings" />

        <x-export-button :pdf=1 :excel=1 :print=1 />
    </x-mm.panel>
</x-mm.page>
@endsection
