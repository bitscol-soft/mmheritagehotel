<div class="col-md-12">
    <form method="POST" action="{{ route('rst.products.store') }}" class="form-horizontal" data-parsley-validate>
        @csrf


        <input type="hidden" name="bar_or_restaurant" value="0">

        <!-- Name -->
        <div class="form-group">
            <div class="col-md-6 col-md-offset-1">
                <label class="control-label" for="specification">Name <sup class="text-danger">*</sup> :</label>
                <input class="form-control" type="text" id="product_name" name="name" placeholder="Product Name"
                    value="{{ old('name') }}" data-parsley-required="true" autocomplete="off" />
            </div>
        </div>


        <!-- Barcode -->
        <div class="form-group">
            <div class="col-md-6 col-md-offset-1">
                <label class="control-label" for="specification">Barcode <sup class="text-danger">*</sup> :</label>
                <input class="form-control" type="text" id="product_barcode" name="barcode"
                    placeholder="Product Barcode" value="{{ old('barcode') }}" data-parsley-required="true"
                    autocomplete="off" required />
            </div>
        </div>



        <div class="form-group">

            <!-- Category -->
            <div class="col-md-3 col-md-offset-1">
                <label class="control-label" for="specification">Category <sup class="text-danger">*</sup> :</label>
                <select class="chosen-select form-control required" required="required" name="category_id"
                    data-placeholder="--Select--">
                    <option></option>
                    @foreach ($categories ?? [] as $parentCategory)
                        <option value="{{ $parentCategory->id }}"
                            {{ old('category_id') == $parentCategory->id ? 'selected' : '' }}>
                            {{ $parentCategory->name }}</option>
                        @foreach ($parentCategory->childCategories ?? [] as $childCategory)
                            <option value="{{ $childCategory->id }}"
                                {{ old('category_id') == $childCategory->id ? 'selected' : '' }}>
                                &nbsp;&raquo;&nbsp;{{ $childCategory->name }}
                            </option>
                            @include('inventory.categories.inc._create-options', [
                                'childCategory' => $childCategory,
                                'space' => 1,
                            ])
                        @endforeach
                    @endforeach
                </select>
            </div>

            <!-- Unit -->
            <div class="col-md-3 ">
                <label class="control-label" for="specification">Unit <sup class="text-danger">*</sup> :</label>
                <select class="chosen-select form-control required" required="required" name="unit_id"
                    data-placeholder="--Select--">
                    <option value=""></option>
                    @foreach ($units ?? [] as $id => $unit)
                        <option value="{{ $id }}" {{ old('medicine_type_id') == $id ? 'selected' : '' }}>
                            {{ $unit }}</option>
                    @endforeach
                </select>
            </div>




        </div>



        <div class="form-group">


            <!-- Generic Name -->
            <div class="col-md-6 col-md-offset-1">

                <label class="control-label" for="specification">Supplier :</label>

                <select class="chosen-select form-control" name="supplier_id" data-placeholder="-Select Supplier-">
                    <option value=""></option>
                    @foreach ($suppliers as $id => $name)
                        <option value="{{ $id }}" {{ old('supplier_id') == $id ? 'selected' : '' }}>
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
                <input class="form-control only-number" value="{{ old('unit_cost', 0) }}" type="text" id="unit_cost"
                    min="0" name="unit_cost" placeholder="Unit Cost" />

            </div>






            <!-- Sale Price -->
            <div class="col-md-3">
                <label class="control-label" for="sale_price">Sale Price :</label>
                <input class="form-control" value="{{ old('sale_price', 0) }}" type="number" id="sale_price"
                    min="0" name="sale_price" placeholder="Sale price" />

            </div>




        </div>

        <div class="form-group">

            <!-- Alert Quantity -->
            <div class="col-md-3 col-md-offset-1">
                <label class="control-label" for="stock_limitation">
                    Alert Quantity :
                </label>
                <input class="form-control" value="{{ old('stock_limitation', 0) }}" type="number"
                    id="stock_limitation" min="0" name="stock_limitation" placeholder="Stock Limitation"
                    data-parsley-required="true" />
            </div>






            <!-- Opening Quantity -->

            <div class="col-md-3">
                <label class="control-label" for="opening_quantity">
                    Opening Quantity :
                </label>

                <input class="form-control" value="{{ old('opening_quantity', 0) }}" type="number"
                    id="opening_quantity" min="0" name="opening_quantity" placeholder="Opening Qty"
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
                        <input type="text" name="vat_amount" class="form-control only-number">
                        <span class="input-group-addon">%</span>
                        <input type="text" name="vat_percent" class="form-control only-number">
                    </div>
                </div>
            </div>
        </div>




        <!-- Status -->
        <div class="form-group">

            <div class="col-md-6 col-sm-offset-1">
                <label class="control-label" for="status">Status <sup class="text-danger">*</sup> :</label>
                <select class="form-control chosen-select" id="status" name="status"
                    data-placeholder="-Select-">
                    <option value=""></option>
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>




        <!-- Submit Button -->
        <div class="form-group">

            <div class="col-md-6 col-sm-offset-3">
                <button type="submit" id="submit" class="btn btn-primary">
                    <i class="fa fa-plus-circle"></i> Add Product
                </button>
            </div>

        </div>




    </form>
</div>
<div class="col-md-12">
    <form class="form-horizontal" action="{{ route('rst.product-matrial.store') }}" method="post">

        @csrf
        <!-- Row Matrial Entry form -->
        <div class="row text-center">
            <div class="col-sm-12 col-sm-offset">
                <h3 class="header smaller lighter blue">
                    <b>Assign Row Materials</b>
                </h3>
                <td>
                    <select name="product_id" class="form-control item2 chosen-select"
                        onchange="load_product_stock(this)" id="select20">
                        <option value="" disabled selected>select</option>
                    </select>
                </td>
                <table id="purchase_table" class="table table-bordered edu1 container">
                    <!-- title head -->
                    <thead>
                        <tr>
                            <td rowspan="2">Material</td>
                            <td rowspan="2">Unit</td>
                            <td rowspan="2">Stock</td>
                            <td rowspan="2">Quantity</td>
                            <td rowspan="2">Remarks</td>
                            <td rowspan="2">Action</td>
                        </tr>
                    </thead>


                    <tbody class="text-left">
                        <tr>
                            <td>
                                <select name="material_id[]" class="form-control item chosen-select"
                                    onchange="load_item_stock(this)" id="select20">
                                    <option value="" disabled selected>select</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" value="" name="unit_name[]"
                                    class="form-control material_item_unit" readonly="readonly" />
                            </td>
                            <td>
                                <input type="text" value="" name="available_quantity[]"
                                    id="item_available_quantityq0"
                                    class="form-control material_current_stock material-available-qty"
                                    readonly="readonly" />

                                    <input type="hidden" class="material_unit_id" value="0"  name="unit_id[]">
                                    <input type="hidden" class="material_current_price" value="0"  name="price[]">
                                    <input type="hidden" class="material_category_id" value="0"  name="category_id[]">
                            </td>
                            <td>
                                <input type="text" id="q0" value="" onkeyup="checkQtyLimit(this)"
                                    onkeypress='return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57'
                                    name="quantity[]" class="form-control material_quantity" />
                            </td>
                            <td>
                                <input type="text" class="form-control" name="remarks[]" value="">
                            </td>


                            <td><button type="button" class="ibtnDel btn btn-sm btn-danger delete_row"
                                    onclick="removeRow(this)"><i class="fa fa-times-circle"></i></button></td>
                        </tr>

                        <tr>
                            <td colspan="7" style="text-align: right;">
                                <button type="button" onclick="insert_Row(this)"
                                    class="btn btn-xs btn-inverse add_row r-btnAdd">
                                    + Add New
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <div class="form-group">
                    <div class="pull-right" style="padding-right: 10px !important;">
                        <button class="btn btn-success btn-sm"> <i class="fa fa-save"></i>
                            Save</button>
                        <button class="btn btn-gray btn-sm" type="Reset"> <i class="fa fa-refresh"></i>
                            Reset</button>
                        <a href="{{ route('goods-requisitions.index') }}"
                            class="btn btn-info btn-sm"> <i class="fa fa-list"></i> List</a>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="total" value="0" name="total">


    </form>
</div>
