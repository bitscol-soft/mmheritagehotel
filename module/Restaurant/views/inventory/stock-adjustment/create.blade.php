@extends('layouts.master')
@section('title', 'Add New Stock Adjsutment')

@section('css')
    @include('scroll-css')
    <style>
        .stock-adjustment-table tbody th{
            word-break:  break-word;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <h4 class="pl-2"><i class="fa fa-plus-circle"></i> @yield('title')</h4>

                <ul class="breadcrumb mb-1">
                    <li><a href="{{ route('home') }}"><i class="ace-icon fa fa-home"></i></a></li>
                    <li><a class="text-muted" href="{{ route('inv.purchases.index') }}">Stock Adjsutment</a></li>
                    <li>Create</li>
                </ul>
            </div>

            <div class="widget-body">
                <div class="widget-main">

                    @include('partials._alert_message')


                    <!-- PURCHASE CREATE FORM -->
                    <form id="" class="form-horizontal" action="{{ route('inv.stock-adjustments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="Direct">

                        <div class="row" style="display: flex; justify-content: center; flex-wrap: wrap;">
                            <div class="col-md-4">
                                <div class="input-group mb-1 width-100" style="width: 100%">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Warehouse<span class="label-required"> *</span>
                                    </span>
                                    <select name="warehouse_id" id="warehouse_id" data-placeholder="- Select -" tabindex="2" class="form-control select2" style="width: 100%" required onchange="resetWarehouse()">
                                        @foreach($warehouses as $id => $name)
                                            <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }} {{ $warehouses->count() < 2 ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group mb-1 width-100" style="width: 100%">
                                    <span class="input-group-addon" style="width: 40%; text-align: left">
									    Date<span class="label-required">*</span>
                                    </span>
                                    <input type="text" name="date" id="date" tabindex="3" class="form-control {{ systemSettingValue('Change Date') == 1 ? 'date-picker' : '' }}" value="{{ old('date', date('Y-m-d')) }}" autocomplete="off" data-date-format="yyyy-mm-dd" {{ systemSettingValue('Change Date') == 1 ? '' : 'readonly' }} required>
                                </div>
                            </div>

                            <div id="searchProduct" class="col-sm-12 search-purchase-product">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2 search-any-product">
                                        <div class="input-group mb-1 width-100" style="width: 100%">
                                            <span class="input-group-addon width-10" style="text-align: left; background-color: #e1ecff; color: #000000;">
                                                Search By Barcode <span class="label-required"></span>
                                            </span>
                                            <div style="position: relative;">
                                                <input type="text" class="form-control" name="product_search" id="searchProductField"  placeholder="Scan Your Barcode or SKU" autocomplete="off">

                                                <div class="dropdown-content live-load-content">


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- PURCHASE TABLE -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered fixed-table-header stock-adjustment-table" id="purchaseTable">
                                        <thead>
                                            <tr class="table-header-bg">
                                                <th width="25%">Product</th>
                                                <th width="15%">Variation</th>
                                                <th width="7%">Sku</th>
                                                <th width="7%">Unit</th>
                                                <th width="10%">Lot</th>
                                                <th width="10%">Expire Date</th>
                                                <th width="10%" class="text-center">Unit Cost</th>
                                                <th width="10%" class="text-center">Current Stock</th>
                                                <th width="8%" class="text-center">Adjust Qty</th>
                                                <th width="10%" class="text-center">Adjust Type</th>
                                                <th width="10%">Reason</th>
                                                <th width="5%" class="text-center">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id='productTable'>
                                            @if (old('product_id'))
                                                @foreach (old('product_id') as $key => $value)
                                                    <tr>
                                                        @php $product = \Module\Product\Models\Product::with('productVariations')->find(old('product_id')[$key]); @endphp
                                                        <input type="hidden" class="product_is_variation" value="{{ count($product->productVariations) > 0 ? 'true' : 'false' }}">

                                                        <th width="25%">
                                                            <select name="product_id[]" id="product_id" class="form-control products select2" data-placeholder="- Select -" onchange="getProductVariations(this)" required>
                                                                <option></option>
                                                                @foreach ($products ?? [] as $product)
                                                                    <option value="{{ $product->id }}"
                                                                        data-category="{{ optional($product->category)->name }}"
                                                                        data-unit-measure="{{ optional($product->unitMeasure)->name }}"
                                                                        data-purchase-price="{{ number_format($product->purchase_price, 2, '.', '') }}"
                                                                        {{ old('product_id')[$key] == $product->id ? 'selected' : '' }}
                                                                    >{{ $product->name . ' - ' . $product->code }}</option>
                                                                @endforeach
                                                            </select>
                                                        </th>

                                                        <th width="10%">
                                                            <input type="text" name="unit_measure_id[]" id="unit_measure_id" class="form-control unit-measure" value="{{ old('unit_measure_id')[$key] }}" readonly>
                                                        </th>
                                                        <th width="15%">
                                                            <select name="product_variation_id[]" id="product_variation_id" class="form-control product-variations select2" onchange="checkItemExistOrNot(this)">
                                                                <option value="" selected>- Select -</option>
                                                                @php $product = \Module\Product\Models\Product::with('productVariations')->find(old('product_id')[$key]); @endphp
                                                                @foreach ($product->productVariations as $variation)
                                                                    <option value="{{ $variation->id }}" {{ old('product_variation_id')[$key] == $variation->id ? 'selected' : '' }}>{{ $variation->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </th>
                                                        <th width="10%">
                                                            <input type="text" name="lot[]" id="lot" class="form-control lot" value="{{ old('lot')[$key] }}" autocomplete="off">
                                                        </th>
                                                        <th width="10%">
                                                            <input type="text" name="purchase_price[]" id="purchase_price" class="form-control text-right only-number purchase-price" value="{{ old('purchase_price')[$key] }}" autocomplete="off" required>
                                                        </th>
                                                        <th width="10%">
                                                            <input type="number" name="quantity[]" id="quantity" class="form-control text-center quantity" value="{{ old('quantity')[$key] }}" autocomplete="off" required>
                                                        </th>
                                                        <th width="10%">
                                                            <input type="text" name="special_comment[]" id="special_comment" class="form-control special_comment" value="{{ old('special_comment')[$key] }}" autocomplete="off">
                                                        </th>
                                                        <th width="5%">
                                                            <button type="button" class="btn btn-sm btn-danger remove-row" title="Remove" {{ $loop->first ? 'disabled' : '' }}><i class="fa fa-times"></i></button>
                                                        </th>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9" class="text-center">
                                                    {{-- <a href="javascript:void(0)" type="button" class="btn btn-xs btn-block btn-light" style="color: #0084db !important" id="addrow">
                                                        <i class="fa fa-plus-circle"></i> ADD MORE
                                                    </a> --}}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-9">

                        </div>


                        <div class="col-md-3">

                            <div class="input-group mb-1 width-100">
                                <span class="input-group-addon" style="width: 40%; text-align: left">
                                    Total Quantity
                                </span>
                                <input type="text" class="form-control text-right total-quantity" name="total_quantity" id="total_quantity" value="{{ old('due_amount') }}" readonly>
                            </div>
                            <div class="input-group mb-1 width-100">
                                <span class="input-group-addon" style="width: 40%; text-align: left">
                                    Total Amount
                                </span>
                                <input type="text" class="form-control text-right total-amount" name="total_amount" id="total_amount" value="{{ old('due_amount') }}" readonly>
                            </div>
                        </div>

                        <div class="btn-group" style="float: right">
                            <button class="btn btn-sm btn-success"> <i class="fa fa-save"></i> SUBMIT </button>
                            <a class="btn btn-sm btn-info" href="{{ route('inv.purchases.index') }}"> <i class="fa fa-bars"></i> LIST </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection





@section('script')

    @include('script.common-script')
    @include('stock-adjustment._inc.script')
    @include('js.product-search-script')

@endsection
