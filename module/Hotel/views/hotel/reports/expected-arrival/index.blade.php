@extends('layouts.master')

@section('title', 'Expected Arrival List')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Expected Arrival List
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
                                <div class="col-sm-3 col-sm-offset-3">
                                    <div class="input-group">
                                        <label class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                        <input type="text" class="date-picker form-control text-center" name="date" value="{{ request('date', date('Y-m-d')) }}" placeholder="Date" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-search"></i> Search </button>
                                        <a href="{{ request()->url() }}" class="btn btn-sm"> <i class="fa fa-refresh"></i> </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @if (collect(request()->all())->count() > 0)
                            <div class="row">
                                <div class="col-sm-12 px-2">
                                    @include('hotel.reports.expected-arrival.export.excel')
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


