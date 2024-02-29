<form method="POST" action="{{ route('rst.mat-products.store') }}" class="form-horizontal" data-parsley-validate>
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
            <input class="form-control" type="text" id="product_barcode" name="barcode" placeholder="Product Barcode"
                value="{{ old('barcode') }}" data-parsley-required="true" autocomplete="off" required />
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
                        {{ old('category_id') == $parentCategory->id ? 'selected' : '' }}>{{ $parentCategory->name }}
                    </option>
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
                    <option value="{{ $id }}" {{ old('unit_id') == $id ? 'selected' : '' }}>
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
            <input class="form-control" value="{{ old('stock_limitation', 0) }}" type="number" id="stock_limitation"
                min="0" name="stock_limitation" placeholder="Stock Limitation" data-parsley-required="true" />
        </div>






        <!-- Opening Quantity -->

        <div class="col-md-3">
            <label class="control-label" for="opening_quantity">
                Opening Quantity :
            </label>

            <input class="form-control" value="{{ old('opening_quantity', 0) }}" type="number" id="opening_quantity"
                min="0" name="opening_quantity" placeholder="Opening Qty" data-parsley-required="true" />

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


    <input type="hidden" name="is_matrial" value="1">

    <!-- Status -->
    <div class="form-group">

        <div class="col-md-6 col-sm-offset-1">
            <label class="control-label" for="status">Status <sup class="text-danger">*</sup> :</label>
            <select class="form-control chosen-select" id="status" name="status" data-placeholder="-Select-">
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
