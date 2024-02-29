@extends('layouts.master')

@section('title', 'Purchase Detail')

@section('page-header')
    <i class="fa fa-info-circle"></i> Purchase Details
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




@dd('sfkfj')


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
                        <a href="{{ route('acc-purchases.index') }}" ><i class="fa fa-list-alt"></i> List</a>
                    </div>

                    <div class="widget-toolbar">
                        <a href="{{ route('acc-purchases.create') }}" ><i class="fa fa-plus"></i> Create</a>
                    </div>
                </div>


                <div class="space"></div>




                <div class="row px-2 pb-3">
                    <div class="col-md-5">
                        <p><span style="width: 130px">Date:</span> {{ $purchase->date }}</p>
                        <p><span style="width: 130px">Invoice No:</span> {{ $purchase->invoice_no }}</p>
                        <p><span style="width: 130px">Discount Amount:</span> {{ $purchase->discount_amount }}</p>
                        <p><span style="width: 130px">Payable Amount:</span> {{ $purchase->payable_amount }}</p>
                        <p><span style="width: 130px">Paid Amount:</span> {{ $purchase->paid_amount }}</p>
                        <p><span style="width: 130px">Due Amount:</span> {{ $purchase->due_amount }}</p>

                    </div>
                    <div class="col-md-7">
                        <div style=" float: right">
                            <p><span style="width: 130px">Customer Name:</span> {{ optional($purchase->supplier)->name }}</p>
                            <p><span style="width: 130px">Company Name:</span> {{ optional($purchase->company)->name }}</p>
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
                                    <th class="pl-3" style="color: white !important;" width="60%">Product</th>
                                    <th class="pl-3 text-right" style="color: white !important; text-align: center" width="10%">Quantity</th>
                                    <th class="pl-3 text-right" style="color: white !important;" width="10%">Price</th>
                                    <th class="pl-3 text-right" style="color: white !important;" width="10%">Amount</th>
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($purchase->details ?? [] as $details)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="pl-3">
                                        <p>{{ optional($details->product)->name }}</p>
                                        <p>{{ $details->description }}</p>
                                    </td>
                                    <td style="text-align: center">{{ $details->quantity }}</td>
                                    <td class="text-right">{{ $details->price }}</td>
                                    <td class="text-right">{{ $details->amount }}</td>
                                </tr>
                                @endforeach
                            </tbody>



                            <tfoot>
                                <tr>
                                    <th colspan="4"><strong class="pull-right">Total</strong></th>
                                    <th class="pl-3 text-right">{{ $purchase->total_amount }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>  
            </div>
        </div>
    </div>
@endsection
