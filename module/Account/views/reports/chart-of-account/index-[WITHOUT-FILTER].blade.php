@extends('layouts.master')


@section('title', 'Chart Of Account')


@section('page-header')
    <i class="fa fa-info-circle"></i> Chart Of Account
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

                    <div class="widget-toolbar border smaller">
                        <a href="{{ request()->url() }}" >
                            <i class="fa fa-refresh bigger-110"></i> Refresh
                        </a>
                    </div>

                    <div class="widget-toolbar border smaller">
                        <a href="{{ request()->getRequestUri() }}?print=print" >
                            <i class="fa fa-print bigger-110"></i> Print
                        </a>
                    </div>
                </div>

                <div class="space"></div>










                <!-- filter -->
                <div class="row px-3 pb-2 no-print">
                    <form action="" method="get">

                        <div class="col-sm-4 col-sm-offset-3">
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

                        <div class="col-sm-2">
                            <div class="btn-group btn-corner">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-search"></i>
                                    Search
                                </button>
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
                                    <th class="text-center">Opening Date</th>
                                    <th class="pl-1">Name</th>
                                    <th class="text-center">Balance Type</th>
                                    <th class="text-right pr-1">Balance</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($accounts as $account)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ fdate($account->created_at) }}</td>
                                        <td class="pl-1">{{ $account->name }}</td>
                                        <td class="text-center">{{ $account->balance_type }}</td>


                                        @if ($account->accountGroup->balance_type == 'Debit')
                                            <td width="20%" class="text-right pr-1">
                                                {{ number_format($account->debit - $account->credit ?? 0, 2) }}
                                            </td>
                                        @else
                                            <td width="20%" class="text-right pr-1">
                                                {{ number_format($account->credit - $account->debit ?? 0, 2) }}
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @include('partials._paginate', ['data' => $accounts])
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
