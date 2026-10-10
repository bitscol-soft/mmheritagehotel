@extends('layouts.master')
@section('title', 'Invoice No: '.$stockAdjustment->invoice_no)



@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-rst-adjust" :title="'Invoice No: ' . $stockAdjustment->invoice_no">
        <x-slot name="actions">
            <a class="btn btn-sm btn-default" href="{{ route('inv.purchases.index') }}">
                <i class="fa fa-bars"></i> Purchase
            </a>
        </x-slot>

        <x-mm.panel>
            <div style="text-align:center;" class="row">
                <h3>Stock Adjustment</h3>
                <p>Invoice :{{ $stockAdjustment->invoice_no }} </p>
            </div>

                    @include('partials._alert_message')

                    <div style="" class="row">
                        <div class="col-lg-6">
                            <h4>Warehouse : {{ $stockAdjustment->warehouse->name }}</h4>
                            <p>Transfer Date : {{ $stockAdjustment->date }}</p>
                            <p>Approved Date : {{ $stockAdjustment->approve_date }}</p>

                        </div>
                      
                   
                    </div>
                        
                        <!-- PURCHASE TABLE -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="purchaseTable">
                                        <thead>
                                            <tr class="table-header-bg">
                                                <th width="25%">Product</th>
                                                <th width="10%">SKU</th>
                                                <th width="10%">Adjust Quantity</th>
                                                <th width="10%">Adjust Reason</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                            @foreach ($stockAdjustment->stock_adjustment_details as $adjustmentDetail)
                                                <tr>
                                                    <input type="hidden" name="stock_transfer_detail_id[]" value="{{ $adjustmentDetail->id }}">
                                                    <th width="25%">
                                                        {{ optional($adjustmentDetail->product)->name }} - {{ optional($adjustmentDetail->product_variation)->name }} 
                                                    </th>
                                                    <th width="15%">
                                                        {{ $adjustmentDetail->product_variation_id != null ? optional($adjustmentDetail->product_variation)->sku : optional($adjustmentDetail->product)->sku  }}
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
                            <a class="btn btn-sm btn-info" href="{{ route('inv.stock-transfer.index') }}"> <i class="fa fa-bars"></i> LIST </a>
                        </div>
        </x-mm.panel>
    </x-mm.page>
@endsection





@section('script')
@endsection
