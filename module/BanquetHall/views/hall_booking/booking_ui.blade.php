@extends('layouts.master')
@section('title', 'Add Booking')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.min.css') }}" />
    <style>
        .table {
            margin-bottom: 0 !important;
        }

        body {
            counter-reset: section;
        }

        .count:before {
            counter-increment: section;
            content: counter(section);
        }

        select:invalid {
            height: 0px !important;
            opacity: 0 !important;
            position: absolute !important;
            display: flex !important;
        }

        select:invalid[multiple] {
            margin-top: 15px !important;
        }

    </style>
@endpush


@section('content')

    @php
        $today              = date('m/d/Y');
        $tomorrow           =  date('m/d/Y', strtotime($today . '+1 days'));
        $compact_date       = $today . ' - ' . $tomorrow;
        $availablity_check  = request('booking_availabe');

    @endphp

    <x-mm.styles />
    <x-mm.page class="mm-booking-form" title="Add New Booking">
        <x-slot name="actions">
            <a href="{{ route('booking.index') }}" class="btn btn-sm btn-default">
                <i class="ace-icon fa fa-list-alt"></i> Booking List
            </a>
        </x-slot>

        <x-mm.panel>
            <x-alert-message />
            <x-room-manage :categories="$categories" :mixdate="$booking_date" />
        </x-mm.panel>
    </x-mm.page>


@endsection

@section('script')

    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    @include('home._inc.script')


@endsection
