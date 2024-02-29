@extends('layouts.master')

@section('title', 'In House Guest List')

@section('page-header')
    <i class="fa fa-plus-circle"></i> In House Guest List
@stop


@push('style')
    <style>
        .widget-header {
            background-color: #EAF4FA !important;
            background-image: none !important;
        }


        table thead th {
            background-color: #4d8cb3;
            color: #fff;
        }

        .border-none {
            border: none !important;
        }

        .header-input {
            background: white !important;
            border: none !important;
            font-size: 18px !important;
            font-weight: bold !important;
            padding: 0 !important;
            color: black !important;
            width: 100% !important;
        }

        .footer-input {
            background: white !important;
            border: none !important;
            text-align: right !important;
            font-size: 18px !important;
            font-weight: bold !important;
            padding: 0 !important;
            color: black !important;
            width: 100% !important;
        }
    </style>
@endpush


@section('content')

    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                </div>



                <div class="widget-body">
                    <x-alert-message />

                    <div class="widget-main">

                        <div class="row mb-2">
                            <form action="" method="GET">
                                {{-- <div class="col-sm-3 col-sm-offset-2">
                                    <div class="input-group">
                                        <label class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                        <input type="text" class="date-picker form-control text-center" name="from" value="{{ request('from', date('Y-m-d')) }}" placeholder="From Date" autocomplete="off">
                                    </div>
                                </div> --}}
                                <div class="col-sm-3 col-sm-offset-4">
                                    <div class="input-group">
                                        <label class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                        <input type="text" class="date-picker form-control text-center" name="to"
                                            value="{{ request('to', date('Y-m-d')) }}" placeholder="To Date"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i>
                                            Search </button>
                                        <a href="{{ request()->url() }}" class="btn btn-sm"> <i class="fa fa-refresh"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @if (collect(request()->all())->count() > 0)
                            <div class="row">
                                <div class="col-sm-12 px-2">
                                    @include('hotel.reports.in-house-guest.export.excel')
                                    <x-paginate :data="$bookings" />
                                </div>
                                <x-export-button :pdf=1 :excel=1 />
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
