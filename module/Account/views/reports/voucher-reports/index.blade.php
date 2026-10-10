@extends('layouts.master')

@section('title', 'Voucher Reports')

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

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Voucer Reports" description="Vouchers for the period.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ request()->getRequestUri() }}&print=print"><i class="fa fa-print"></i> Print</a>
    </x-slot>
    <x-mm.panel class="mm-report-filter">
        <form class="mm-setup-filter mm-report-form" action="" method="get">

                <div class="input-group">
                    <label class="input-group-addon">Company</label>
                    <select class="form-control chosen-select-100-percent" name="company_id" data-placeholder="-Select Company-">
                        <option></option>
                        @foreach ($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                @include('includes.input-groups.select-group', ['modelVariable' => 'accounts', 'edit_id' => request('account_id')])

                <select class="form-control chosen-select-100-percent" name="voucher_type" data-placeholder="-All Type-">
                    <option></option>
                    @foreach ($voucherTypes as $name)
                        <option {{ request('voucher_type') == $name ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>

                @include('includes.input-groups.date-range', ['date1' => request('from',date('Y-m-d')), 'date2' => request('to',date('Y-m-d')), 'is_read_only' => true])

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
        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')

        <!-- LIST -->
        <div class="row" style="width: 100%; margin: 0 !important; margin-bottom: 20px !important">
            <div class="col-sm-12 px-4">
                <x-mm.table-scroll label="Voucer Reports">
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
                </x-mm.table-scroll>

                @include('partials._paginate', ['data' => $vouchers])

                <!-- EXCEL BUTTON -->
                <a class="hidden-print" href="{{ url()->current() }}?export_type=excel&{{ request()->getQueryString() }}" target="_blank" style="margin: 18px 0 0 20px; display: inline-block;">
                    <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                </a>

            </div>
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

@endsection
