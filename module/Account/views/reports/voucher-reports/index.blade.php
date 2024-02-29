@extends('layouts.master')

@section('title', 'Voucher Reports')


@section('page-header')
    <i class="fa fa-info-circle"></i> Voucer Reports
@stop


@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style type="text/css">
        .rate-entry-table td,
        tr {
            border: none !important;
        }

        .bg-qty {
            background: #5759604a;
        }

        .bg-value {
            background: #33712e45;
        }

        .chosen-container>.chosen-single,
        [class*=chosen-container]>.chosen-single {
            height: 30px !important;
        }

    </style>
@endpush


@section('content')
    <div class="row">
        <div class="col-sm-12">

            @include('partials._alert_message')

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">

                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>


                    <div class="widget-toolbar">
                        <a href="{{ request()->getRequestUri() }}&print=print"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>


                <div class="space"></div>

                <div class="row px-3 pb-2" style="width: 100%; margin: 0 !important;">
                    <form action="" method="get">

                        <div class="col-sm-3 mt-1">
                            <div class="input-group">
                                <label class="input-group-addon">Company</label>
                                <select class="form-control chosen-select-100-percent" name="company_id" data-placeholder="-Select Company-">
                                    <option></option>
                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3 mt-1">
                            @include('includes.input-groups.select-group', ['modelVariable' => 'accounts', 'edit_id' => request('account_id')])
                        </div>


                        <div class="col-sm-2 mt-1">
                            <select class="form-control chosen-select-100-percent" name="voucher_type" data-placeholder="-All Type-">
                                <option></option>
                                @foreach ($voucherTypes as $name)
                                    <option {{ request('voucher_type') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4 mt-1">
                            @include('includes.input-groups.date-range', ['date1' => request('from',date('Y-m-d')), 'date2' => request('to',date('Y-m-d')), 'is_read_only' => true])
                        </div>

                        <div class="col-sm-12 mt-1 text-right">
                            <div class="btn-group btn-corner">
                                <button type="submit" class="btn btn-primary btn-xs">
                                    <i class="fa fa-search"></i>
                                    Search
                                </button>
                                <a href="" class="btn btn-xs">
                                    <i class="fa fa-refresh"></i>
                                    Refresh
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important; margin-bottom: 20px !important">
                    <div class="col-sm-12 px-4">
                        <table class="table table-bordered table-striped" style="margin-bottom: 0">
                            <thead>
                                <tr class="table-header-bg">
                                    <th class="text-center">Sl</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Voucher No</th>
                                    <th class="text-center">Voucher Type</th>
                                    <th class="pl-3">Company</th>
                                    <th class="pl-3">Description</th>
                                    <th class="text-right pr-1">Amount</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($vouchers as $voucher)
                                    @php
                                        $route = 'voucher-' . strtolower($voucher->voucher_type) . 's.show';
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $voucher->date }}</td>
                                        <td class="text-center">{{ $voucher->invoice_no }}</td>
                                        <td class="text-center">{{ $voucher->voucher_type }}</td>
                                        <td class="pl-3">{{ optional($voucher->company)->name }}</td>
                                        <td class="pl-3">{{ $voucher->description }}</td>
                                        <td class="text-right pr-1">
                                            <a href="{{ route($route, $voucher->id) }}" target="_blank">
                                                {{ number_format($voucher->amount, 2) }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <th class="text-right" colspan="6">Total In Page</th>
                                    <th class="text-right pr-1">{{ number_format($vouchers->sum('amount'), 2) }}</th>
                                </tr>

                                @if($vouchers->currentPage() == $vouchers->lastPage())
                                    <tr style="font-size: 18px">
                                        <th class="text-right" colspan="6">Grand Total</th>
                                        <th class="text-right pr-1">{{ number_format($grand_total, 2) }}</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        @include('partials._paginate', ['data' => $vouchers])


                        <!-- EXCEL BUTTON -->
                        <a class="hidden-print" href="{{ url()->current() }}?export_type=excel&{{ request()->getQueryString() }}" target="_blank" style="margin: 18px 0 0 20px; display: inline-block;">
                            <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>


    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>
@endsection
