@extends('layouts.master')


@section('title', 'Create New Package')

@section('css')
    <style>
        @media (max-width:575px) {
            select.chosen-select.form-control.required:invalid {
                height: 100% !important;
                opacity: 1 !important;
                position: unset !important;
                display: unset !important;
            }
        }
    </style>
@endsection

@section('content')

<x-mm.styles />
<x-mm.page class="mm-bar mm-rst mm-rst-inv mm-rst-form" title="Create package" description="Combine bar products into a package.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('bar.packages.index') }}">
            <i class="ace-icon fa fa-list-alt"></i>
            Package List
        </a>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')

        <div class="row">
            <div class="col-sm-11 col-sm-offset-1">

                <form method="POST" action="{{ route('bar.packages.store') }}" class="form-horizontal"
                    data-parsley-validate>
                    @csrf

                    <input type="hidden" name="bar_or_restaurant" value="1">

                    <!-- Name -->
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-1">
                            <label class="control-label" for="specification">Name<sup
                                    class="text-danger">*</sup> :</label>
                            <input class="form-control" type="text" id="name" name="name"
                                placeholder="Package Name" value="{{ old('name') }}"
                                data-parsley-required="true" autocomplete="off" />
                        </div>
                    </div>


                    <!-- Barcode -->
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-1">
                            <label class="control-label" for="specification">Barcode<sup
                                    class="text-danger">*</sup> :</label>
                            <input class="form-control" type="text" id="barcode" name="barcode"
                                placeholder="Product Barcode" value="{{ old('barcode') }}"
                                data-parsley-required="true" autocomplete="off" required />
                        </div>
                    </div>



                    <div class="form-group">

                        <!-- Category -->
                        <div class="col-md-3 col-md-offset-1">
                            <label class="control-label" for="specification">Category<sup
                                    class="text-danger">*</sup> :</label>
                            <select class="chosen-select form-control required" required="required"
                                name="category_id" data-placeholder="--Select--">
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
                                        @include(
                                            'inventory.categories.inc._create-options',
                                            ['childCategory' => $childCategory, 'space' => 1]
                                        )
                                    @endforeach
                                @endforeach
                            </select>
                        </div>

                        <!-- Supplier -->
                        <div class="col-md-3 ">
                            <label class="control-label" for="specification">Supplier: </label>

                            <select class="chosen-select form-control" name="supplier_id"
                                data-placeholder="-Select Supplier-">
                                <option value=""></option>
                                @foreach ($suppliers as $id => $name)
                                    <option value="{{ $id }}"
                                        {{ old('supplier_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>



                    <!-- Pack -->
                    {{-- <div class="form-group">


                        <!-- Pack Size -->
                        <div class="col-md-3 col-md-offset-1">
                            <label class="control-label" for="pack_size">Size :</label>
                            <input class="form-control" value="{{ old('pack_size', 0) }}" type="number"
                                id="pack_size" min="0" name="pack_size" placeholder="Pack size" />

                        </div>


                        <div class="col-md-1" style="width: 4%;margin-top:25px">
                            <h4>X</h4>
                        </div>

                        <!-- Small Unit -->
                        <div class="col-md-2">
                            <label class="control-label" for="pack_unit_id">Unit <sup
                                    class="text-danger">*</sup> :</label>
                            <select class="form-control chosen-select" id="pack_unit_id" name="pack_unit_id"
                                data-placeholder="-Select Pack Unit-" required>
                                <option value=""></option>

                                @foreach ($units->where('type', 'pack') as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('pack_unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>




                        <!-- Middle Equal(=) -->
                        <div class="col-md-1" style="width: 4%;margin-top:7px">
                            <h1>=</h1>
                        </div>



                        <!-- Big Unit -->
                        <div class="col-md-2">
                            <label class="control-label" for="unit_id">Unit(<small>big unit</small>) <sup
                                    class="text-danger">*</sup> :</label>
                            <div class="input-group">
                                <span class="input-group-addon" style="font-size: 18px">1</span>
                                <select class="form-control chosen-select" id="unit_id" name="unit_id"
                                    required data-placeholder="-Select Unit-">
                                    <option value=""></option>

                                    @foreach ($units->where('type', 'retail') as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>


                    </div> --}}

                    <!-- Unit -->
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-1">
                            <label class="control-label" for="specification">Unit <sup class="text-danger">*</sup> :</label>
                            <select class="chosen-select form-control required" required="required" name="unit_id"
                                data-placeholder="--Select--">
                                <option value=""></option>
                                @foreach ($units ?? [] as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="form-group">



                        <!-- Unit Cost -->
                        <div class="col-md-3 col-md-offset-1">
                            <label class="control-label" for="unit_cost">Purchase Price<sup
                                    class="text-danger">*</sup> :</label>
                            <input class="form-control only-number" value="{{ old('unit_cost', 0) }}"
                                type="text" id="unit_cost" min="0" name="unit_cost"
                                placeholder="Unit Cost" data-parsley-required="true" />

                        </div>






                        <!-- Sale Price -->
                        <div class="col-md-3">
                            <label class="control-label" for="sale_price">Sale Price(<small>big
                                    unit</small>)<sup class="text-danger">*</sup> :</label>
                            <input class="form-control" value="{{ old('sale_price', 0) }}" type="number"
                                id="sale_price" min="0" name="sale_price" placeholder="Sale price"
                                data-parsley-required="true" />

                        </div>




                    </div>

                    <div class="form-group">

                        <!-- Alert Quantity -->
                        <div class="col-md-3 col-md-offset-1">
                            <label class="control-label" for="stock_limitation">
                                Alert Quantity :
                            </label>
                            <input class="form-control" value="{{ old('stock_limitation', 0) }}"
                                type="number" id="stock_limitation" name="stock_limitation"
                                placeholder="Stock Limitation" />
                        </div>






                        <!-- Opening Quantity -->

                        <div class="col-md-3">
                            <label class="control-label" for="opening_quantity">
                                Opening Quantity :
                            </label>

                            <input class="form-control" value="{{ old('opening_quantity', 0) }}"
                                type="number" id="opening_quantity" name="opening_quantity"
                                placeholder="Opening Qty" />

                        </div>


                    </div>





                    <div class="form-group">
                        <!-- VAT -->
                        <div class="col-md-3 col-md-offset-1">

                            <label class="control-label" for="specification">Vat: </label>

                            <div class="input-group">
                                <span class="input-group-addon">৳</span>
                                <div class="input-group">
                                    <input type="text" name="vat_amount"
                                        class="form-control only-number">
                                    <span class="input-group-addon">%</span>
                                    <input type="text" name="vat_percent"
                                        class="form-control only-number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- select products -->
                    <div class="col-md-10 col-sm-offset-1">
                        <div class="input-group mb-2">
                            <span class="input-group-addon">Product</span>
                            <select name="" id="product-search" class="form-control select2"
                                data-placeholder="--Choose Product--">
                                <option value=""></option>
                                @foreach ($products as $key => $product)
                                    <option value="{{ $key }}" data-name="{{ $product }}">
                                        {{ $product }}</option>
                                @endforeach
                            </select>
                        </div>

                        <x-mm.table-scroll label="Package">
                            <table class="table table-striped table-bordered nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="60%">Product</th>
                                        <th class="text-center" width="20%">Quantity</th>
                                        <th width="20%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="added_itmes">
                                    {{-- <tr>
                                        <td>name
                                            <input type="hidden" value="" name="product_ids[]">
                                        </td>
                                        <td>
                                            <input type="text" value="0" name="quantity[]">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="remove_item btn btn-danger">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </x-mm.table-scroll>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">

                        <div class="col-md-6 col-sm-offset-3">
                            <button type="submit" id="submit" class="btn btn-primary">
                                <i class="fa fa-plus-circle"></i> Add Package
                            </button>
                        </div>

                    </div>
                </form>


            </div>
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection



@section('js')

    <script>
        $(document).on('select2:select', '#product-search', function(e) {
            let val = $(this).val();
            let name = $('#product-search option:selected').data('name');
            let html = `
            <tr>
                <td>${name}
                    <input type="hidden" value="${val}" name="product_ids[]">
                </td>
                <td>
                    <input type="text" value="0" name="quantity[]">
                </td>
                <td>
                    <a href="javascript:void(0)" class="remove_item" onclick="removeItem(this)" style="color:red">
                        <i class="fa fa-trash-o"></i>
                    </a>
                </td>
            </tr>
        `;
            $('.added_itmes').append(html);
        })


        function removeItem(obj){
            $(obj).closest('tr').remove();
        }
    </script>

    @include('inventory/product/_inc/script')
    <script>
        $('#pack_size, #unit_cost, #sale_price').keyup(function(e) {
            let unitCost = parseFloat($('#unit_cost').val())
            let packSize = parseFloat($('#pack_size').val())
            let salesPrice = parseFloat($('#sale_price').val())
            let perPiecePrice = '';


            if (packSize <= 0) {
                alert('Enter Pack Size!');
            } else {
                $('#retail_unit_cost').val((unitCost / packSize).toFixed(2));
                $('#retail_sales_price').val((salesPrice / packSize).toFixed(2));
            }

        })
    </script>



@endsection
