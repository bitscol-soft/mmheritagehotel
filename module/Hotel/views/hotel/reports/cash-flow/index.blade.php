@extends('layouts.master')
@section('title', 'Cash Flow')

@section('page-header')
    <i class="fa fa-info-circle"></i> Cash Flow
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

        table thead th {
            background-color: #4d8cb3;
            color: #fff;
        }
    </style>
@stop


@section('content')
    <div class="row">


        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    @if (hasPermission('pharmacy.view', $slugs))
                        <span class="widget-toolbar">

                        </span>
                    @endif

                </div>
                <div class="widget-body">
                    <div class="widget-main">

                        @include('partials._alert_message')

                        <!-- Search -->
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <form>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>

                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Invoice</span>
                                                        <input type="text" name="invoice_no" autocomplete="off"
                                                            value="{{ request('invoice_no') }}" class="form-control"
                                                            placeholder="Invoice No">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Date</span>
                                                        <input type="text" name="from_date"
                                                            value="{{ request('from_date') }}" autocomplete="off"
                                                            class="form-control date-picker">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-calendar"></i></span>
                                                        <input type="text" name="to_date"
                                                            value="{{ request('to_date') }}" autocomplete="off"
                                                            class="form-control date-picker">
                                                    </div>

                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Time</span>
                                                        <input type="text" class="form-control time-picker" id="time_start" name="from_time"  value="{{ request('from_time') }}">
                                                        <span class="input-group-addon"><i class="fa fa-clock"></i></span>
                                                        <input type="text" class="form-control time-picker" id="time_end" name="to_time"  value="{{ request('to_time') }}">
                                                    </div>
                                                </td>

                                                <td style="width: 15%">
                                                    <div class="btn-group" style="display: flex">
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
                        @if (collect(request()->all())->count() > 0)
                            <div class="row">
                                <div class="col-sm-12 px-4">
                                    @include('hotel/reports/cash-flow/export/excel')
                                    <x-paginate :data="$cashFlows" />
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

@section('js')
<script>
    $('.time-picker').timepicker({
            minuteStep: 1,
            showMeridian: true,
            defaultTime: '',
            icons: {
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down'
            }
        })
</script>
@endsection
