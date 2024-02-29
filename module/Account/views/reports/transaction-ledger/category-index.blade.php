@extends('layouts.master')
@section('title', 'Transaction Ledger')
@section('page-header')
    <i class="fa fa-list"></i> Transaction Ledger
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>

    <style type="text/css">
        .rate-entry-table td, tr {
            border: none !important;
        }

        .bg-qty {
            background: #5759604a;
        }

        .bg-value {
            background: #33712e45;
        }

        .chosen-container > .chosen-single, [class*=chosen-container] > .chosen-single {
            height: 30px !important;
        }
    </style>
@endpush


@section('content')
    <div class="row">
        <div class="col-sm-12">

        @include('partials._alert_message')

        @php
        $from = request('from',date('Y-m-d'));
        $to = request('to',date('Y-m-d'));
        @endphp


        <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{ request()->url() }}" class="dt-button btn btn-white btn-primary btn-bold" title="Refresh Data" data-toggle="tooltip">
                                    <span>
                                        <i class="fa fa-refresh bigger-110"></i>
                                    </span>
                                </a>

                                <a href="{{ request()->fullUrlWithQuery(['print' => 1, 'from' => $from, 'to' => $to]) }}" class="dt-button btn btn-white btn-info btn-bold" style="color: maroon !important;" title="Print Data" data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
                                    <span>
                                        <i class="fa fa-print bigger-110"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space"></div>

                <div class="row px-3 pb-2" style="width: 100%; margin: 0 !important;">
                    <form action="" method="get">

                        <div class="col-sm-4 col-sm-offset-3">
                            @include('includes.input-groups.date-range', ['date1' => $from, 'date2' => $to, 'is_read_only' => true])
                        </div>

                        <div class="col-sm-2">
                            <div class="btn-group btn-corner">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
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
                                    <th>Account Group</th>
                                    <th>Account Control</th>
                                    <th>Account Subsidiary</th>
                                    <th>Account Name</th>
                                    <th class="text-right pr-1">Opening.</th>
                                    <th class="text-right pr-1">Dr.</th>
                                    <th class="text-right pr-1">Cr.</th>
                                    <th class="text-right pr-1">Balance</th>
                                </tr>
                            </thead>

                            <tbody>

                                @if($accountGroups->count() == 0)
                                    <tr>
                                        <td colspan="7" style="font-size: 16px" class="text-center text-danger">NO RECORDS FOUND!</td>
                                    </tr>
                                @endif

                                @php
                                    $sl = 1;
                                    $totalOpeningBalance = 0;
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                @endphp

                                @foreach($accountGroups as $accountGroup)
                                    @foreach($accountGroup->accountControls as $accountControl)
                                        @foreach($accountControl->accountSubsidiaries as $accountSubsidiary)
                                            @foreach($accountSubsidiary->accounts as $account)
                                                @php
                                                    $balance = $account->credit + $account->debit + $account->opening_balance;
                                                    $totalOpeningBalance += $account->opening_balance;
                                                    $totalDebit += $account->debit;
                                                    $totalCredit += $account->credit;
                                                @endphp

                                                <tr>
                                                    <td class="text-center">{{ $sl++ }}</td>
                                                    <td>{{ $accountGroup->name }}</td>
                                                    <td>{{ $accountControl->name }}</td>
                                                    <td>{{ $accountSubsidiary->name }}</td>
                                                    <td>{{ $account->name }}</td>
                                                    <td class="text-right pr-1">{{ number_format($account->opening_balance ?? 0, 2) }}</td>
                                                    <td class="text-right pr-1">{{ number_format(abs($account->debit ?? 0), 2) }}</td>
                                                    <td class="text-right pr-1">{{ number_format($account->credit ?? 0, 2) }}</td>
                                                    <td class="text-right pr-1">{{ number_format($balance ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="5">Total:</th>
                                    <th class="text-right pr-1">{{ number_format(abs($totalOpeningBalance), 2) }}</th>
                                    <th class="text-right pr-1">{{ number_format(abs($totalDebit), 2) }}</th>
                                    <th class="text-right pr-1">{{ number_format($totalCredit, 2) }}</th>
                                    <th class="text-right pr-1">{{ number_format(($totalOpeningBalance + $totalDebit + $totalCredit), 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                        <br>
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


