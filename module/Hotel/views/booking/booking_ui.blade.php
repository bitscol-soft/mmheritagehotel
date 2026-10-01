@extends('layouts.master')
@section('title', 'Add Booking')
@section('page-header')
    <i class="fa fa-plus-circle"></i> Add New Booking
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.min.css') }}" />
    <style>
        .table {
            margin-bottom: 0 !important;
        }

        /* ── Booking board polish (scoped: only this admin page) ───────────── */
    </style>
@endpush


@section('content')

    @php
        $today        = date('m/d/Y');
        $tomorrow     = date('m/d/Y', strtotime('tomorrow'));
        $availablity_check = request('booking_date', $booking_date ?? $today . ' - ' . $tomorrow);
        // board date-range display: values are MM/DD/YYYY (daterangepicker format)
        $range_bits = array_values(array_filter(array_map('trim', explode('-', (string) $availablity_check))));
        $in_ts = strtotime($range_bits[0] ?? 'today');
        $out_ts = strtotime($range_bits[1] ?? 'tomorrow');
        if ($out_ts <= $in_ts) {
            $out_ts = strtotime('+1 day', $in_ts);
        }
        $nights = max((int) round(($out_ts - $in_ts) / 86400), 1);
    @endphp

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('booking.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> Booking List
                        </a>
                    </span>

                </div>

                <div class="widget-body">
                    <div class="widget-main">
                        <x-alert-message />

                        <div class="board-stay-strip">
                            <span class="stay-chip"><i class="fa fa-sign-in"></i> Check-in
                                <b>{{ date('D, d M Y', $in_ts) }}</b></span>
                            <span class="stay-chip"><i class="fa fa-sign-out"></i> Check-out
                                <b>{{ date('D, d M Y', $out_ts) }}</b></span>
                            <span class="stay-chip"><i class="fa fa-moon-o"></i> <b>{{ $nights }}</b>
                                night{{ $nights > 1 ? 's' : '' }}</span>
                        </div>

                        <x-room-manage :categories="$categories" :mixdate="$availablity_check" />
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')

    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    @include('home._inc.script')


@endsection
