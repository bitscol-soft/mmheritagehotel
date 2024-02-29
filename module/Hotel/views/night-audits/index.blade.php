@extends('layouts.master')

@section('title', ' Night Audit')

@section('page-header')
    <i class="fa fa-info-circle"></i> Night Audit <span class="badge badge-info">{{ $nightaudits->count() }}</span>
@stop

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('night-audits.create') }}">
                            <i class="ace-icon fa fa-plus"></i> Generate
                        </a>
                    </span>

                </div>

                <div class="widget-body">
                    <div class="widget-main">
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
                        <div class="row">

                            <x-alert-message />

                            <div class="col-xs-12">


                                @include('night-audits.export.excel')

                                <x-export-button pdf="1" excel="1" />

                                <x-paginate :data="$nightaudits" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

@stop
