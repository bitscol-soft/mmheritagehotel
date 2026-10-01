@extends('layouts.master')
@section('title', 'House Keeping')
@section('page-header')
    <i class="fa fa-plus-circle"></i> House Keeping
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.min.css') }}" />

@endpush


@section('content')

    @php
        $today = date('m/d/Y');
        $tomorrow = date('m/d/Y', strtotime($today . '+1 days'));
        $compact_date = $today . ' - ' . $tomorrow;
        $availablity_check = request('booking_availabe');
        
    @endphp

    <x-mm.styles />
    <x-mm.page title="Housekeeping" description="Review rooms by category and manage their housekeeping status." class="mm-housekeeping">
        <x-alert-message />
        @if (hasPermission('Booking.HouseKeeping', p_slugs()))
            <aside class="mm-housekeeping-help" aria-label="Housekeeping guidance">
                <strong>Room care workspace</strong>
                <p>Use the expand control on an available room card to open the existing status dialog. Review the status and remarks before saving.</p>
                <p>Booked and reserved rooms retain their existing restricted controls. Room colours follow the current system status rules.</p>
            </aside>
        @else
            <x-mm.panel><p class="tw-m-0">You do not have permission to view housekeeping rooms.</p></x-mm.panel>
        @endif
        <x-mm.panel class="mm-housekeeping-board">
            <x-room-keeping :categories="$categories" :mixdate="$booking_date" />
        </x-mm.panel>
    </x-mm.page>


@endsection

@section('script')

    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    {{-- @include('home._script.script') --}}
    @include('house-keeping._script.script')


@endsection
