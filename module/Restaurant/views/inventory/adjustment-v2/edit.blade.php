@extends('layouts.master')
@section('title', 'Invoice No: ' . $stockAdjustment->invoice_no)



@section('content')
    <div class="row">
        <div class="col-12">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <h4 class="pl-2"><i class="far fa-edit"></i> @yield('title')</h4>

                <ul class="breadcrumb mb-1">
                    <li><a href="{{ route('home') }}"><i class="ace-icon far fa-home-lg-alt"></i></a></li>
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


                        {{-- for Product --}}
                        <form action="{{ route('rst.stock-adjustment.update', $stockAdjustment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="adjustment_id" value="{{ $stockAdjustment->id }}">
                            <div class="btn-group" style="float: right">
                                {{-- <a class="">
                                    <i class="fa fa-check-circle">

                                    </i>
                                </a> --}}
                                <button type="submit" class="btn btn-sm btn-info btn-outline-warning">
                                    <i class="fa fa-check-circle">
                                        Click To Approve
                                    </i>
                                </button>
                            </div>
                            {{-- <div class="btn-group" style="float: right">
                            <a class="btn btn-sm btn-info" href="{{ route('rst.stock-adjustment.index') }}"> <i
                                    class="fa fa-clock"></i> Pending </a>
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
                                                <input type="hidden" name="adjustment_detail_id[]"
                                                    value="{{ $adjustmentDetail->id }}">

                                                <input type="hidden" name="product_id[]"
                                                    value="{{ optional($adjustmentDetail->product)->id }}">

                                                <input type="hidden" name="quantity[]"
                                                    value="{{ $adjustmentDetail->quantity }}">
                                                <input type="hidden" name="adjustment_reason[]"
                                                    value="{{ $adjustmentDetail->adjustment_reason }}">

                                                <input type="hidden" name="stock_type[]"
                                                    value="{{ $adjustmentDetail->stock_type }}">


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
                    </form>


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
