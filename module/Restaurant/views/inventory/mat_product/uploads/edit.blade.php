@extends('layouts.master')


@section('title', 'Edit Product')


@section('content')
    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">



                <!-- Header -->
                <div class="widget-header">
                    <h4 class="widget-title">
                        <i class="fa fa-plus-circle"></i> Edit Product
                    </h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('rst.products.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i>
                            Product List
                        </a>
                    </span>
                </div>






                <!-- Body -->
                <div class="widget-body">
                    <div class="widget-main">



                        <div class="row">
                            <div class="col-sm-11 col-sm-offset-1">

                                <form method="POST" action="{{ route('rst.product-uploads.update', $product->id) }}"
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



                                    {{-- <!-- Batch -->
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-1">
                                            <label class="control-label" for="specification">Batch No. <sup
                                                    class="text-danger">*</sup> :</label>
                                            <input class="form-control" type="text" id="batch_number" name="batch_number"
                                                placeholder="Product Name"
                                                value="{{ old('batch_number', $product->batch_number) }}"
                                                data-parsley-required="true" autocomplete="off" />
                                        </div>
                                    </div> --}}



                                    <div class="form-group">

                                        <!-- Medicine Type -->
                                        <div class="col-md-3 col-md-offset-1">
                                            <label class="control-label" for="specification">Medicine Type <sup
                                                    class="text-danger">*</sup> :</label>

                                            <input class="form-control" type="text" id="medicine_type"
                                                name="medicine_type" placeholder="Product Name"
                                                value="{{ old('medicine_type', $product->medicine_type) }}"
                                                data-parsley-required="true" autocomplete="off" />

                                        </div>



                                        <!-- Category -->
                                        <div class="col-md-3">
                                            <label class="control-label" for="specification">Category <sup
                                                    class="text-danger">*</sup> :</label>
                                            <input class="form-control" type="text" id="category" name="category"
                                                placeholder="Category Name"
                                                value="{{ old('category', $product->category) }}"
                                                data-parsley-required="true" autocomplete="off" />
                                        </div>


                                    </div>



                                    <div class="form-group">


                                        <!-- Generic Name -->
                                        <div class="col-md-3 col-md-offset-1">
                                            <label class="control-label" for="specification">Generic Name <sup
                                                    class="text-danger">*</sup> :</label>

                                            <input class="form-control" type="text" id="generic" name="generic"
                                                placeholder="Generic Name" value="{{ old('generic', $product->generic) }}"
                                                data-parsley-required="true" autocomplete="off" />


                                        </div>


                                        <!-- Supplier Name -->
                                        <div class="col-md-3">
                                            <label class="control-label" for="specification">Supplier :</label>

                                            <input class="form-control" type="text" id="supplier" name="supplier"
                                                placeholder="Supplier Name"
                                                value="{{ old('supplier', $product->supplier) }}"
                                                data-parsley-required="true" autocomplete="off" />

                                        </div>




                                    </div>


                                    <!-- Pack -->
                                    <div class="form-group">


                                        <!-- Pack Size -->
                                        <div class="col-md-3 col-md-offset-1">
                                            <label class="control-label" for="pack_size">Pack Size :</label>
                                            <input class="form-control"
                                                value="{{ old('pack_size', $product->pack_size) }}" type="number"
                                                id="pack_size" min="0" name="pack_size" placeholder="Pack size" />
                                        </div>




                                        <!-- Small Unit -->
                                        <div class="col-md-3">
                                            <label class="control-label" for="retail_unit_id">Small Unit <sup
                                                    class="text-danger">*</sup> :</label>
                                            <input class="form-control"
                                                value="{{ old('small_unit', $product->small_unit) }}" type="number"
                                                id="small_unit" min="0" name="small_unit" placeholder="Pack size" />
                                        </div>




                                        <!-- Middle Equal(=) -->
                                        <div class="col-md-1" style="width: 4%">
                                            <h1 class="mt-20">=</h1>
                                        </div>





                                        <!-- Big Unit -->
                                        <div class="col-md-3">
                                            <label class="control-label" for="wholesale_unit_id">Big Unit <sup
                                                    class="text-danger">*</sup> :</label>
                                            <input class="form-control"
                                                value="{{ old('big_unit', $product->big_unit) }}" type="number"
                                                id="big_unit" min="0" name="big_unit" placeholder="Pack size" />
                                        </div>







                                    </div>

                                    <div class="form-group">



                                        <!-- Unit Cost -->
                                        <div class="col-md-3 col-md-offset-1">
                                            <label class="control-label" for="unit_cost">Purchase Price <sup
                                                    class="text-danger">*</sup> :</label>
                                            <input class="form-control"
                                                value="{{ old('purchase_price', $product->purchase_price) }}"
                                                type="number" id="unit_cost" min="0" name="purchase_price"
                                                placeholder="Unit Cost" data-parsley-required="true" />

                                        </div>






                                        <!-- Sale Price -->
                                        <div class="col-md-3">
                                            <label class="control-label" for="sale_price">Sale Price <sup
                                                    class="text-danger">*</sup> :</label>
                                            <input class="form-control"
                                                value="{{ old('sale_price', $product->sale_price) }}" type="number"
                                                id="sale_price" min="0" name="sale_price" placeholder="Sale price"
                                                data-parsley-required="true" />

                                        </div>




                                    </div>


                                    <div class="form-group">



                                        <!-- Unit Cost -->
                                        <div class="col-md-3 col-md-offset-1">
                                            <label class="control-label" for="unit_cost">Retail Purchase Price <sup
                                                    class="text-danger">*</sup> :</label>
                                            <input class="form-control"
                                                value="{{ old('retail_purchase_price', $product->retail_purchase_price) }}"
                                                type="number" id="retail_purchase_price" min="0"
                                                name="retail_purchase_price" placeholder="Unit Cost"
                                                data-parsley-required="true" />

                                        </div>






                                        <!-- Sale Price -->
                                        <div class="col-md-3">
                                            <label class="control-label" for="retail_sale_price">Retail Sale Price <sup
                                                    class="text-danger">*</sup> :</label>
                                            <input class="form-control"
                                                value="{{ old('retail_sale_price', $product->retail_sale_price) }}"
                                                type="number" id="retail_sale_price" min="0" name="retail_sale_price"
                                                placeholder="Sale price" data-parsley-required="true" />
                                        </div>




                                    </div>

                                    <div class="form-group">

                                        <!-- Alert Quantity -->
                                        <div class="col-md-3 col-md-offset-1">
                                            <label class="control-label" for="stock_limitation">
                                                Alert Quantity <sup class="text-danger">*</sup> :
                                            </label>
                                            <input class="form-control"
                                                value="{{ old('stock_limitation', $product->stock_limitation) }}"
                                                type="number" id="stock_limitation" min="0" name="stock_limitation"
                                                placeholder="Stock Limitation" data-parsley-required="true" />
                                        </div>


                                        <div class="col-md-3">
                                            <label class="control-label" for="status">Batch :</label>
                                            <input value="{{ old('batch_number', $product->batch_number) }}" type="text"
                                                id="batch_number" class="form-control small-label-box" name="batch_number">
                                        </div>

                                    </div>




                                    <div class="form-group">

                                        <!-- Alert Quantity -->
                                        <div class="col-md-3 col-md-offset-1">
                                            <label class="control-label" for="big_quantity">
                                                Big Quantity <sup class="text-danger">*</sup> :
                                            </label>
                                            <input class="form-control"
                                                value="{{ old('big_quantity', $product->big_quantity) }}" type="number"
                                                id="big_quantity" min="0" name="big_quantity" placeholder="Stock Limitation"
                                                data-parsley-required="true" />
                                        </div>


                                        <div class="col-md-3">
                                            <label class="control-label" for="status">Small Qty :</label>
                                            <input value="{{ old('small_quantity', $product->small_quantity) }}"
                                                type="text" id="small_quantity" class="form-control small-label-box"
                                                name="small_quantity">
                                        </div>

                                    </div>





                                    <!-- Submit Button -->
                                    <div class="form-group">

                                        <div class="col-md-6 col-sm-offset-3">
                                            <button type="submit" id="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Edit Product
                                            </button>
                                        </div>

                                    </div>




                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
