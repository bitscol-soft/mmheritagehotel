@extends('layouts.master')

@section('title', 'Edit Product')

@section('content')

<x-mm.styles />
<x-mm.page class="mm-rst mm-rst-inv mm-rst-form" title="Edit material product" description="Update the product details.">
    <x-slot name="actions">
        <a href="{{ route('rst.products.index') }}" class="mm-button">
            <i class="ace-icon fa fa-list-alt"></i>
            Product List
        </a>

    </x-slot>
    <x-mm.panel class="tw-p-4">

        <form method="POST" action="{{ route('rst.products.update', $product->id) }}"
            class="form-horizontal" data-parsley-validate novalidate>
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="form-group">
                <div class="col-md-6 col-md-offset-1">
                    <label class="control-label" for="specification">Name <sup
                            class="text-danger">*</sup> :</label>
                    <input class="form-control" type="text" id="product_name" name="name"
                        placeholder="Product Name" value="{{ old('name', $product->name) }}"
                        data-parsley-required="true" autocomplete="off" />
                </div>
            </div>

            <!-- Barcode -->
            <div class="form-group">
                <div class="col-md-6 col-md-offset-1">
                    <label class="control-label" for="specification">Barcode <sup class="text-danger">*</sup> :</label>
                    <input class="form-control" type="text" id="product_barcode" name="barcode" placeholder="Product Barcode"
                        value="{{ old('barcode', $product->barcode) }}" data-parsley-required="true" autocomplete="off" required />
                </div>
            </div>

            <div class="form-group">

                <!-- Category -->
                <div class="col-md-3 col-md-offset-1">
                    <label class="control-label" for="specification">Category <sup
                            class="text-danger">*</sup> :</label>
                    <select class="chosen-select form-control required" required="required"
                        name="category_id" data-placeholder="--Select--">
                        <option></option>
                        @foreach ($categories ?? [] as $parentCategory)
                            <option value="{{ $parentCategory->id }}" {{ optional($product->category)->id == $parentCategory->id ? 'selected' : '' }}>{{ $parentCategory->name }}</option>
                                @foreach ($parentCategory->childCategories ?? [] as $childCategory)
                                    <option value="{{ $childCategory->id }}"
                                        {{ optional($product->category)->id == $childCategory->id ? 'selected' : '' }}>
                                        &nbsp;&raquo;&nbsp;{{ $childCategory->name }}
                                    </option>
                                    @include('inventory.categories.inc._create-options', ['childCategory' => $childCategory, 'space' => 1])
                                @endforeach
                        @endforeach
                    </select>
                </div>

                <!-- Unit -->
                <div class="col-md-3 ">
                    <label class="control-label" for="specification">Unit <sup
                            class="text-danger">*</sup> :</label>
                    <select class="chosen-select form-control required" required="required"
                        name="unit_id" data-placeholder="--Select--">
                        <option value=""></option>
                        @foreach ($units ?? [] as $id => $unit)
                            <option value="{{ old('unit_id', $id) }}"
                                {{ $id == $product->unit_id ? 'selected' : '' }}>
                                {{ $unit }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="form-group">

                <!-- Supplier Name -->
                <div class="col-md-6 col-md-offset-1">

                    <label class="control-label" for="specification">Supplier :</label>

                    <select class="chosen-select form-control" name="supplier_id"
                        data-placeholder="-Select Supplier-">
                        <option value=""></option>
                        @foreach ($suppliers as $id => $name)
                            <option value="{{ $id }}"
                                {{ old('supplier_id', $product->supplier_id) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>

                </div>

            </div>

            <div class="form-group">

                <!-- Unit Cost -->
                <div class="col-md-3 col-md-offset-1">
                    <label class="control-label" for="unit_cost">Purchase Price :</label>
                    <input class="form-control"
                        value="{{ old('unit_cost', $product->unit_cost) }}" type="number"
                        id="unit_cost" min="0" name="unit_cost" placeholder="Unit Cost"/>
                </div>

                <!-- Sale Price -->
                <div class="col-md-3">
                    <label class="control-label" for="sale_price">Sale Price :</label>
                    <input class="form-control"
                        value="{{ old('sale_price', $product->sale_price) }}" type="number"
                        id="sale_price" min="0" name="sale_price" placeholder="Sale price" />
                </div>

            </div>

            <div class="form-group">

                <!-- Alert Quantity -->
                <div class="col-md-3 col-md-offset-1">
                    <label class="control-label" for="stock_limit">
                        Alert Quantity :
                    </label>
                    <input class="form-control"
                        value="{{ old('stock_limit', $product->stock_limit) }}" type="number"
                        id="stock_limit" min="0" name="stock_limit" placeholder="Stock Limitation"
                        data-parsley-required="true" />
                </div>

            </div>

            <div class="form-group">
                <!-- VAT -->
                <div class="col-md-3 col-md-offset-1">

                    <label class="control-label" for="specification">Vat :</label>

                    <div class="input-group">
                        <span class="input-group-addon">৳</span>
                        <div class="input-group">
                            <input type="text" name="vat_amount" class="form-control only-number" value="{{ $product->vat_amount }}">
                            <span class="input-group-addon">%</span>
                            <input type="text" name="vat_percent" class="form-control only-number" value="{{ getPercentOfXAmount($product->sale_price, $product->vat_amount) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <!-- Status  -->

                <div class="col-md-6 col-md-offset-1">
                    <label class="control-label">
                        Status<sup class="text-danger">*</sup> :
                    </label>

                    <select class="form-control chosen-select" id="status" name="status"
                        data-placeholder="-Select-">
                        <option value=""></option>
                        <option value="1" {{ $product->status ? 'selected' : '' }}>Active
                        </option>
                        <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="form-group">

                <div class="col-md-6 col-sm-offset-3">
                    <button type="submit" id="submit" class="btn btn-primary">
                        <i class="fa fa-edit"></i> Edit Product
                    </button>
                </div>

            </div>

        </form>

    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

    @include('inventory/product/_inc/script')
    <script>
        // Load Batches
        $(document).ready(function() {
            loadDetails({
                type: 'batchNumber',
                selector: '#batch-number',
                url: "/pharmacy/inventory/get-product-batches",
                select: function(event, ui) {
                    $('.add-product-id').val(ui.item.data.id);
                    $('.drug-name').val(ui.item.data.batch_number);
                },
            })
        })

        $('#pack_size, #unit_cost, #sale_price').keyup(function(e) {
            let unitCost = parseFloat($('#unit_cost').val())
            let packSize = parseFloat($('#pack_size').val())
            let salesPrice = parseFloat($('#sale_price').val())
            let perPiecePrice = '';

            console.log($('#sale_price').val());

            if (packSize <= 0) {
                alert('Enter Pack Size!');
            } else {
                $('#retail_unit_cost').val((unitCost / packSize).toFixed(2));
                $('#retail_sales_price').val((salesPrice / packSize).toFixed(2));
            }
            console.log($('#retail_unit_cost').val(), $('#retail_sales_price').val())
            // if (perPackQuantity >= 0) {
            //     perPiecePrice = ((salesPrice / packSize) / perPackQuantity).toFixed(2);
            //     $('#per_piece_price').val((perPiecePrice));
            // }

        })
    </script>

@endsection
