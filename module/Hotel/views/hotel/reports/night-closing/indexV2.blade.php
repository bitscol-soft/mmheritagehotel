@extends('layouts.master')

@section('title', 'Booking Night Audit')
@php
    $checkNUll = $nightaudits[0] != null || $nightaudits[0] != '';
@endphp
@section('page-header')
    <i class="fa fa-info-circle"></i>Booking Night Audit <span
        class="badge badge-info">{{ $checkNUll ? $nightaudits->count() : 0 }}</span>
@stop

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                </div>

                <div class="widget-body">
                    <div class="widget-main">

                        @if ($checkNUll && count($nightaudits[0]->details) > 0)
                            <div class="row mb-2">
                                <form action="" method="GET">
                                    <div class="col-sm-3 col-sm-offset-2">
                                        <div class="input-group">
                                            <label class="input-group-addon">From</label>
                                            <input type="text" class="date-picker form-control text-center"
                                                autocomplete="off" name="from_date" value="{{ request('from_date') }}"
                                                placeholder="From Date">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <label class="input-group-addon">To</label>
                                            <input type="text" class="form-control date-picker text-center"
                                                autocomplete="off" name="to_date" value="{{ request('to_date') }}"
                                                placeholder="To Date">
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fa fa-search-plus"></i> Search
                                            </button>
                                            <a href="{{ request()->url() }}" class="btn btn-sm">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        @endif

                        <div class="row">

                            <x-alert-message />

                            <div class="col-xs-12">

                                @if (collect(request()->all())->count() > 0)

                                    @include('hotel/reports/night-closing/export/excel')

                                    <x-paginate :data="$nightaudits" />

                                    <x-export-button :pdf=1 :excel=1 />

                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    @foreach ($nightaudits as $audit)
        @include('hotel/reports/night-closing/details')
    @endforeach


@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
