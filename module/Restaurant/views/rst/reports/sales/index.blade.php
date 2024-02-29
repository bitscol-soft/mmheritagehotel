@extends('layouts.master')
@section('title', 'Sale List')

@section('page-header')
    <i class="fa fa-info-circle"></i> Sale List
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
                                                <th>
                                                    Invoice Number
                                                </th>
                                                <th>Guest/Customer</th>
                                                <th>Date</th>
                                                <th>Outdoor Sale</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>

                                                <td>
                                                    <input type="text" name="invoice_no"
                                                        value="{{ request('invoice_no') }}" class="form-control"
                                                        placeholder="Invoice No">
                                                </td>
                                                <td>
                                                    <input type="text" name="guest_name" class="form-control"
                                                        placeholder="Guest/Customer">
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Date</span>
                                                        <input type="text" name="date" value="{{ request('date') }}"
                                                            class="form-control date-picker" placeholder="Date" autocomplete="off">
                                                    </div>
                                                </td>
                                                <td>
                                                    <label>
                                                        <input type="checkbox" name="outdoor_sale" value="1"
                                                            {{ request('outdoor_sale') == 1 ? 'checked' : '' }}>
                                                        <span class="lbl"><b>Yes</b></span>
                                                    </label>

                                                </td>
                                                <td width="20%">
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
                                @include('rst/reports/sales/export/excel')
                                {{-- rst/reports/sales/export/excel --}}
                                <x-paginate :data="$sales" />
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
