@extends('layouts.master')
@section('title', 'Invoice No: ' . $stockAdjustment->invoice_no . ' Approve Tranfer')

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-rst-adjust mm-rst-form" :title="'Invoice No: ' . $stockAdjustment->invoice_no . ' Approve Tranfer'">
        <x-slot name="actions">
            <a class="btn btn-sm btn-default" href="{{ route('inv.purchases.index') }}">
                <i class="fa fa-bars"></i> Purchase List
            </a>
        </x-slot>

        <x-mm.panel>
                    <!-- PURCHASE APPROVE FORM -->
                    <form class="form-horizontal" action="{{ route('inv.stock-adjustments-approve', $stockAdjustment->id) }}" onsubmit="return confirm('Are You Sure to Approve This Transfer?')" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group mb-2 width-100" style="width: 100%">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
                                        Warehouse<span class="label-required"></span>
                                    </span>
                                    <select name="warehouse_id" id="warehouse_id" data-placeholder="- Select -" tabindex="2" class="form-control select2" style="width: 100%" required>
                                        <option></option>
                                        @foreach($warehouses as $id => $name)
                                            <option value="{{ $id }}" {{ $stockAdjustment->warehouse_id ==  $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group mb-2 width-100">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Date
                                    </span>
                                    <input type="text" name="date" id="date" tabindex="3" class="form-control" value="{{ $stockAdjustment->date }}" autocomplete="off" data-date-format="yyyy-mm-dd" readonly>
                                </div>
                            </div>
                        </div>

                    
                        <!-- PURCHASE TABLE -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="purchaseTable">
                                        <thead>
                                            <tr class="table-header-bg">
                                                <th width="25%">Product</th>
                                                <th width="15%">Variation</th>
                                                <th width="10%" class="text-center">Transfered Qty</th>
                                                <th width="10%" class="text-center">Approved Qty</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stockAdjustment->stock_adjustment_details as $stockAdjustmentDetail)
                                                <tr>
                                                    <input type="hidden" name="stock_transfer_detail_id[]" value="{{ $stockAdjustmentDetail->id }}">
                                                    <th width="25%">
                                                        <input type="hidden" class="product_is_variation" value="{{ $stockAdjustmentDetail->product_variation_id != null ? 'true' : 'false' }}">
                                                        {{ optional($stockAdjustmentDetail->product)->name }} - {{ optional($stockAdjustmentDetail->product)->code }}
                                                    </th>
                                                   
                                                    
                                                    <th width="15%">
                                                        {{ optional($stockAdjustmentDetail->product_variation)->name }}
                                                    </th>
                                                 
                                                    
                                                    <th width="10%">
                                                        <input type="number" name="quantity[]" id="quantity" class="form-control text-center approved-quantity" value="{{ number_format($stockAdjustmentDetail->quantity, 2, '.', '') }}" autocomplete="off" onkeyup="totalQuantity()"  required>

                                                    </th>
                                                    <th width="10%">
                                                        {{ $stockAdjustmentDetail->adjustment_reason }}
                                                    </th>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>



                            



                            <div class="col-md-9">
                                <input type="file" name="attachment" id="attachment"/>
                            </div>

                            <div class="col-md-3">
                                <div class="input-group mb-1 width-100">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Total Amount
                                    </span>
                                    <input type="text" class="form-control text-right" value="{{ number_format($stockAdjustment->total_amount, 2, '.', '') }}" readonly required>
                                </div>

                                <div class="input-group mb-1 width-100">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Total Quantity
                                    </span>
                                    <input type="text" class="form-control text-right total-quantity" name="total_quantity" id="total_quantity" value="{{ number_format($stockAdjustment->total_quantity, 2, '.', '') }}" readonly required>
                                </div>

                                <div class="input-group mb-1 width-100">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Transfer Cost
                                    </span>
                                    <input type="text" class="form-control text-right" name="subtotal" id="subtotal" value="{{ number_format($stockAdjustment->transfer_cost, 2, '.', '') }}" readonly required>
                                </div>
                           
                                


                                <div class="btn-group width-100">
                                    <button class="btn btn-sm btn-success" style="width: 70%"> <i class="fa fa-thumbs-up"></i> APPROVE</button>
                                    <a class="btn btn-sm btn-info" style="width: 29%" href="{{ route('inv.purchases.index') }}"> <i class="fa fa-bars"></i> LIST </a>
                                </div>
                            </div>
                        </div>


                    </form>
        </x-mm.panel>
    </x-mm.page>
@endsection





@section('script')
    {{-- @include('purchases._inc.script') --}}

    <script>
        function totalQuantity(){
            let totalQuantity = 0
            $('.approved-quantity').each(function(){
                totalQuantity += Number($(this).val() ?? 0);

            })

            $('.total-quantity').val(totalQuantity);

        }

    </script>
@endsection