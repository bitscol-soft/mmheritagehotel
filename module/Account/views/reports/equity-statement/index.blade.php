@extends('layouts.master')

@section('title', 'Equity Statement')

@push('style')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style type="text/css">
        th,
        td {
            background: white;
            color: black !important;
        }

        table,
        th,
        td,
        tr {
            border: 1px solid black !important;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            .d-print {
                display: block !important;
            }

            tr {
                page-break-after: avoid !important;
            }

            thead {
                page-break-before: avoid !important;
            }

            .widget-box {
                border: none !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            .px-4 {
                padding: 0 !important;
            }
        }

        @page {
            margin: 0.5in;
            /*size: landscape;*/
        }

        .d-print {
            display: none;
        }

    </style>
@endpush

@section('content')
@php
    $from = request('from', date('Y-m-d'));
@endphp

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Equity Statement" description="Changes in equity.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh</a>
        <a class="mm-button mm-button-secondary" href="javascript:void(0)" onclick="print()"><i class="fa fa-print"></i> Print</a>
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

                <div class="btn-group btn-corner">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>
                        Search</button>
                </div>

        </form>
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <h3 class="text-center d-print" style="margin-top: -30px !important;">EQUITY STATEMENT</h3>
        <h4 class="text-center d-print">As On {{ fdate(request('from') ?? today(), 'd/m/Y') }}</h4>

        @php
            $previous_year_share_capital = 0;
            $previous_year_retained_earnings = 0;
        @endphp

        <!-- DETAIL -->
        <div class="row" style="width: 100%; margin: 0 !important; padding: 0 !important;">

            <div class="col-sm-12">
                <x-mm.table-scroll label="Equity Statement">
                    <table class="table table-sm table-bordered">

                        <thead>
                            <tr>
                                <th>Particular</th>
                                <th class="text-center">Share Capital</th>
                                <th class="text-center">Retained Earnings</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Opening Balance</td>
                                <td class="text-right capital">
                                    {{ number_format($previous_year_share_capital, 0) }}
                                </td>
                                <td class="text-right retained-earnings">
                                    {{ number_format($previous_year_retained_earnings, 0) }}
                                </td>
                                <td class="text-right item-total"></td>
                            </tr>

                            <tr>
                                <td>Add : Profit/Loss during the year</td>
                                <td class="text-right capital">
                                    {{ number_format($profit_loss_share_capital = 0, 0) }}
                                </td>
                                <td class="text-right retained-earnings">
                                    {{ number_format($profit_los_retained_earnings = $profit_and_loss, 0) }}
                                </td>
                                <td class="text-right item-total"></td>
                            </tr>
                            <tr>
                                <td>Add : addition during the year</td>
                                <td class="text-right capital">
                                    {{ number_format($addition_share_capital = 0, 0) }}
                                </td>
                                <td class="text-right retained-earnings">
                                    {{ number_format($addition_retained_earnings = $equity > 0 ? $equity : 0, 0) }}
                                </td>
                                <td class="text-right item-total"></td>
                            </tr>
                            <tr>
                                <td>Less : adjustment during the year</td>
                                <td class="text-right capital">
                                    {{ number_format($adjustment_share_capital = 0, 0) }}
                                </td>
                                <td class="text-right retained-earnings">
                                    {{ number_format($adjusement_retained_earnings = $equity < 0 ? $equity : 0, 0) }}
                                </td>
                                <td class="text-right item-total"></td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Closing Balance</strong>
                                </td>
                                <td class="text-right">
                                    <strong class="capital">{{ number_format($previous_year_share_capital + $profit_loss_share_capital + $addition_share_capital - $adjustment_share_capital, 0) }}</strong>
                                </td>
                                <td class="text-right">
                                    <strong class="retained-earnings">{{ number_format($previous_year_retained_earnings + $profit_los_retained_earnings + $addition_retained_earnings - $adjusement_retained_earnings, 0) }}</strong>
                                </td>
                                <td class="text-right">
                                    <strong class="item-total"></strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Previous Year Balance</td>
                                <td class="text-right capital">
                                    {{ number_format($previous_year_share_capital, 0) }}
                                </td>
                                <td class="text-right retained-earnings">
                                    {{ number_format($previous_year_retained_earnings, 0) }}
                                </td>
                                <td class="text-right item-total"></td>
                            </tr>
                        </tbody>
                    </table>
                </x-mm.table-scroll>

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

    <script>

        let item_total = 0

        $(document).ready(function() {

            $('.capital').each(function() {

                capital = Number($(this).text().replace(',', ''))
                retained_earnings = Number($(this).closest('tr').find('.retained-earnings').text().replace(',', ''))

                let total = capital + retained_earnings

                $(this).closest('tr').find('.item-total').text(moneyFormat(total, 0))
            })
        })
    </script>

@endsection
