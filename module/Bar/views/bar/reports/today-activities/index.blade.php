@extends('layouts.master')
@section('title', 'Today Report')

@section('page-header')
    <i class="fa fa-info-circle"></i> Today Report
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

    </style>
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
                        @include('partials._alert_message')

                        <!-- Search -->
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <form>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <x-widget.date-filter />
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-sm btn-success" type="submit">
                                                            <i class="fa fa-search"></i> Search
                                                        </button>
                                                        <a href="{{ request()->url() }}" class="btn btn-sm">
                                                            <i class="fa fa-refresh"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 px-2">
                                @include('bar.reports.today-activities.export.excel')
                                {{-- <x-paginate :data="$transactions" /> --}}
                            </div>
                            <x-export-button :pdf=1 :excel=1 />
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('js')
@endsection
