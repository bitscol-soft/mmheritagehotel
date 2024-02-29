@extends('layouts.master')


@section('title', 'Receive Voucher Detail')


@section('page-header')
    <i class="fa fa-info-circle"></i> Receive Voucher Detail
@stop


@push('style')
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

            
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7" style="width: 90%; margin-left: 5%; margin-top: 30px">


                <!-- heading -->
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar">
                        <span style="cursor: pointer" onclick="print()" class="text-danger"><i class="fa fa-print"></i> Print</span>
                    </div>

                    <div class="widget-toolbar">
                        <a href="{{ route('voucher-receives.index') }}" ><i class="fa fa-list-alt"></i> List</a>
                    </div>

                    <div class="widget-toolbar">
                        <a href="{{ route('voucher-receives.create') }}" ><i class="fa fa-plus"></i> Create</a>
                    </div>
                </div>
                




                <div class="space"></div>




                <!-- Top Heading -->
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
                                    <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                    <th class="pl-3" style="color: white !important;" width="60%">Account</th>
                                    <th class="pl-3 text-right" style="color: white !important;" width="10%">Debit</th>
                                    <th class="pl-3 text-right" style="color: white !important;" width="10%">Credit</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($voucher->details as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="pl-3">{{ optional($item->account)->name }}</td>
                                        <td class="pl-3 text-right">
                                            @if($item->balance_type == 'Debit')
                                            {{ number_format($item->amount, 2) }}
                                            @endif
                                        </td>
                                        <td class="pl-3 text-right">
                                            @if($item->balance_type == 'Credit')
                                            {{ number_format($item->amount, 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>


                            @php 
                                $totalAmount = $voucher->details->sum('amount') > 0 ? $voucher->details->sum('amount') / 2 : 0;
                            @endphp


                            <tfoot>
                                <tr>
                                    <th colspan="2"><strong class="pull-right">Total</strong></th>
                                    <th class="pl-3 text-right">{{ number_format($totalAmount, 2) }}</th>
                                    <th class="pl-3 text-right">{{ number_format($totalAmount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>





                <!-- Approve Button -->
                @if(request('type') == 'approve' && !$voucher->is_approved)
                    <div class="row" style="width: 100%; margin: 0 !important; padding-bottom: 15px">
                        <div class="col-md-12 text-right">
                            <form action="{{ route('voucher-receives.approve', $voucher->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-check-circle"></i> Approve</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
