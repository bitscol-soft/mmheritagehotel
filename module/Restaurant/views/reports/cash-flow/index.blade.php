@extends('layouts.master')
@section('title', 'Cash Flow')

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
    <x-mm.styles />
    <x-mm.page class="mm-report-page mm-rst" title="Cash Flow">
        <x-mm.panel>
                        @include('partials._alert_message')

                        <!-- Search -->
                        <div class="row">
                            <div class="col-sm-8 col-sm-offset-2">
                                <form>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td>Invoice No</td>
                                                <td class="text-right">From</td>
                                                <td>To</td>
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
                                                    <input type="text" name="from_date" value="{{ request('from_date') }}"
                                                        class="form-control date-picker">
                                                </td>
                                                <td>
                                                    <input type="text" name="to_date" value="{{ request('to_date') }}"
                                                        class="form-control date-picker">
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
                                @include('bar/reports/cash-flow/export/excel')

                                <x-paginate :data="$cashFlows" />

                                <x-export-button :pdf=1 :excel=1 />
                            </div>
                        </div>
        </x-mm.panel>
    </x-mm.page>


@endsection

@section('js')
@endsection
