@extends('layouts.master')
@section('title', 'Journal Voucher Details')
@section('page-header')
    <i class="fa fa-info-circle"></i> Journal Voucher Details
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        p>span {
            width: 130px;
            display: inline-block;
            font-weight: bold
        }

        p {
            margin-bottom: 0 !important;
        }

    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @include('partials._alert_message')

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7"
                style="width: 90%; margin-left: 5%; margin-top: 30px">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{ route('voucher-journals.index') }}"
                                    class="dt-button btn btn-white btn-primary btn-bold" title="Refresh Data"
                                    data-toggle="tooltip">
                                    <span>
                                        <i class="fa fa-list-alt bigger-110"></i>
                                    </span>
                                </a>

                                <a href="{{ route('voucher-journals.create') }}"
                                    class="dt-button btn btn-white btn-info btn-bold" title="Create New"
                                    data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
                                    <span>
                                        <i class="fa fa-plus bigger-110"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space"></div>

                <div class="row px-2 pb-3">
                    <div class="col-md-6">
                        <p><span style="width: 100px">Voucher Type</span> {{ $voucher->voucher_type }}</p>
                        <p><span style="width: 100px">Description</span> {{ $voucher->description }}</p>
                    </div>
                    <div class="col-md-6">
                        <div style="width: 255px; float: right">
                            <p><span style="width: 85px">Invoice No</span> {{ $voucher->invoice_no }}</p>
                            <p><span style="width: 85px">Date</span> {{ $voucher->date }}</p>
                        </div>
                    </div>
                </div>

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header-bg">
                                    <!-- <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                                <th class="pl-3" style="color: white !important;" width="60%">Account Name</th>
                                                <th class="pl-3" style="color: white !important;" width="20%">Balance Type</th>
                                                <th class="pr-3 text-right" style="color: white !important;">Amount</th> -->
                                    <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                    <th class="pl-3" style="color: white !important;" width="60%">Account Name
                                    </th>
                                    <th class="pl-3 text-right" style="color: white !important;" width="10%">Debit</th>
                                    <th class="pl-3 text-right" style="color: white !important;" width="10%">Credit</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($voucher->details as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="pl-3">{{ optional($item->account)->name }}</td>
                                        <td class="pl-3 text-right">
                                            @if ($item->balance_type == 'Debit')
                                                {{ number_format($item->amount, 2) }}
                                            @endif
                                        </td>
                                        <td class="pl-3 text-right">
                                            @if ($item->balance_type == 'Credit')
                                                {{ number_format($item->amount, 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="2"><strong class="pull-right">Total</strong></th>
                                    <th class="pl-3 text-right">{{ number_format($voucher->amount, 2) }}</th>
                                    <th class="pl-3 text-right">{{ number_format($voucher->amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                @if (request()->routeIs('journal.approve.show') && !$voucher->is_approved)
                    <div class="row" style="width: 100%; margin: 0 !important; padding-bottom: 15px">
                        <div class="col-md-12 text-right">
                            <form action="{{ route('voucher-journals.approve.update', $voucher->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check-circle"></i>
                                    Approve</button>
                            </form>
                        </div>
                    </div>
                @endif
                <div class="row" style="width: 100%; margin: 0 !important; padding-bottom: 15px">
                    <div class="col-md-12 text-right">
                        <a href="" target="_blank" class="btn btn-sm btn-primary"><i
                                class="ace-icon fa fa-print bigger-100"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
@endsection
