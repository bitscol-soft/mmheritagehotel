@extends('layouts.master')

@section('title', 'Hotel Service Night Audit')
@php
    $checkNUll = $nightaudits[0] != null || $nightaudits[0] != '';
@endphp

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-night-audit" title="Hotel Service Night Audit" :subtitle="($checkNUll ? $nightaudits[0]->details->count() : 0) . ' records'">
        <x-mm.panel>
                        @if( $checkNUll && count($nightaudits[0]->details) > 0)
                            <div class="row mb-2">
                                <form action="" method="GET">
                                    <div class="col-sm-3 col-sm-offset-2">
                                        <div class="input-group">
                                            <label class="input-group-addon">From</label>
                                            <input type="text" class="date-picker form-control text-center" autocomplete="off" name="from_date" value="{{ request('from_date') }}" placeholder="From Date">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <label class="input-group-addon">To</label>
                                            <input type="text" class="form-control date-picker text-center" autocomplete="off" name="to_date" value="{{ request('to_date') }}" placeholder="To Date">
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


                                @include('hotel-service-night-audits.export.excel')

                                @if( $checkNUll && count($nightaudits[0]->details) > 0) <x-export-button pdf="1" excel="1" /> @endif

                                <x-paginate :data="$nightaudits" />
                                {{-- @include('partials._paginate', ['data' => $nightaudits]) --}}
                            </div>
                        </div>
        </x-mm.panel>
    </x-mm.page>

    @foreach ($nightaudits as $audit)
        @include('hotel-service-night-audits.details')
    @endforeach

@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
