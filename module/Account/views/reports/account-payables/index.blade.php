@extends('layouts.master')

@section('title', 'Account Payable')


@section('page-header')
    <i class="fa fa-info-circle"></i> Account Payable
@stop


@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />

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
                        <a href="{{ request()->url() }}?print=print&company_id={{ request('company_id') }}&account_id={{ request('account_id') }}"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>


                <div class="space"></div>

                <div class="row px-3 pb-2" style="width: 100%; margin: 0 !important;">
                    <form action="" method="get">

                        <div class="col-sm-3 mt-1 col-sm-offset-2">
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
                            @include('includes.input-groups.select-group', ['modelVariable' => 'accounts', 'edit_id' =>
                            request('account_id')])
                        </div>

                        <div class="col-sm-3 mt-1">
                            <div class="btn-group btn-corner">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-search"></i>
                                    Search
                                </button>
                                <a href="{{ request()->url() }}" class="btn btn-default btn-sm">
                                    <i class="fa fa-refresh"></i>
                                    Refresh
                                </a>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12 px-4">
                        <table class="table table-bordered table-striped" style="margin-bottom: 0">
                            <thead>
                                <tr class="table-header-bg">
                                    <th class="text-center">Sl</th>
                                    <th>Account Name</th>
                                    <th class="text-right pr-1">Balance</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($transactions as $account)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $account->name }}</td>
                                        <td class="text-right pr-1">
                                            @if($account->balance <> 0)
                                                <a target="_blank" href="{{ route('report.account-ledger') }}?company_id={{ request('company_id') }}&account_id={{ $account->id }}&from=2010-01-01">
                                                    {{ number_format($account->balance, 2) }}
                                                </a>
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            @if(count($transactions) > 0)
                                <tfoot>
                                    <tr style="font-size: 18px">
                                        <th class="text-right" colspan="2">
                                            <strong>Total=</strong>
                                        </th>
                                        <th class="text-right pr-1">
                                            <strong>{{ number_format($transactions->sum('balance'), 2) }}</strong>
                                        </th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>

                        @include('partials._paginate', ['data' => $transactions])


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
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
@endsection
