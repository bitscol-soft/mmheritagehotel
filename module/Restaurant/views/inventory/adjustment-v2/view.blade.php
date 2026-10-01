@extends('layouts.master')
@section('title', 'Invoice No: ' . $stockAdjustment->invoice_no)



@section('content')
    <div class="row">
        <div class="col-12">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <h4 class="pl-2"><i class="fa fa-edit"></i> @yield('title')</h4>

                <ul class="breadcrumb mb-1">
                    <li><a href="{{ route('home') }}"><i class="ace-icon fa fa-home"></i></a></li>
                    <li><a class="text-muted" href="">Stock Adjustment</a></li>
                    <li>{{ $stockAdjustment->invoice_no }}</li>
                </ul>
            </div>

            <div style="text-align:center;" class="row">
                <h3>Stock Adjustment</h3>
                <p>Invoice :{{ $stockAdjustment->invoice_no }} </p>
            </div>


            <div class="widget-body">
                <div class="widget-main">

                    @include('partials._alert_message')

                    <div style="" class="row">
                        <div class="col-lg-6">
                            <h4>Supplier : {{ $stockAdjustment->supplier_id ?? '' }}</h4>
                            <p>Date : {{ $stockAdjustment->date }}</p>
                            <p>Approved Date : {{ $stockAdjustment->approve_date }}</p>

                        </div>




                        <div class="btn-group" style="float: right">
                            <a class="btn btn-sm btn-info btn-outline-warning"> <i class="fa fa-check-circle"></i>
                                {{ $stockAdjustment->current_status }} </a>
                        </div>
                        {{-- <div class="btn-group" style="float: right">
                            <a class="btn btn-sm btn-info" href="{{ route('rst.stock-adjustment.index') }}"> <i
                                    class="fa fa-clock-o"></i> Pending </a>
                        </div> --}}


                    </div>

                    <!-- PURCHASE TABLE -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="purchaseTable">
                                    <thead>
                                        <tr class="table-header-bg">
                                            <th width="25%">Product</th>
                                            <th width="10%">Adjust Quantity</th>
                                            <th width="10%">Adjust Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($Adjustment->adjustment_details as $adjustmentDetail)
                                            <tr>
                                                <input type="hidden" name="stock_transfer_detail_id[]"
                                                    value="{{ $adjustmentDetail->id }}">
                                                <th width="25%">
                                                    {{ optional($adjustmentDetail->product)->name }}
                                                </th>

                                                <th width="10%">
                                                    {{ number_format($adjustmentDetail->quantity, 2, '.', '') }}
                                                </th>
                                                <th width="10%">
                                                    {{ $adjustmentDetail->adjustment_reason }}
                                                </th>


                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="btn-group" style="float: right">
                        <a class="btn btn-sm btn-info" href="{{ route('rst.stock-adjustment.index') }}"> <i
                                class="fa fa-bars"></i> LIST </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection





@section('script')
@endsection
