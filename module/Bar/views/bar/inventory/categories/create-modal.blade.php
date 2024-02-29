<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('bar.product-categories.store') }}" class="form-horizontal"
                role="form" data-parsley-validate novalidate>
                @csrf

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Add Category</h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">




                    <!-- Category Name -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Category Name <sup
                                class="text-danger">*</sup>:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" name="name" value=""
                                placeholder="Enter category Name">
                        </div>
                    </div>

                     <!-- Category Parent -->
                     <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Parent Category:</label>
                        <div class="col-md-8 col-sm-8">
                            <select name="parent_id" class="form-control chosen-select-100-percent" data-placeholder="--Select--">
                                <option value=""></option>
                                @foreach ($parent_categories ?? [] as $parentCategory)
                                    <option value="{{ $parentCategory->id }}" {{ old('category_id') == $parentCategory->id ? 'selected' : '' }}>{{ $parentCategory->name }}</option>
                                        @foreach ($parentCategory->childCategories ?? [] as $childCategory)
                                            <option value="{{ $childCategory->id }}"
                                                {{ old('category_id') == $childCategory->id ? 'selected' : '' }}>
                                                &nbsp;&raquo;&nbsp;{{ $childCategory->name }}
                                            </option>
                                            @include('inventory.categories.inc._create-options', ['childCategory' => $childCategory, 'space' => 1])
                                        @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>


                <!-- Modal Footer -->
                <div class="modal-footer">

                    <!-- Submit -->
                    <div class="btn-group btn-corner">
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-plus"></i>
                            Confirm
                        </button>
                        <a href="javascript:;" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
                    </div>


                </div>


            </form>
        </div>
    </div>
</div>
