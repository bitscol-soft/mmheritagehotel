<div class="modal fade" id="edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" class="form-horizontal" role="form" id="editForm">
                @csrf
                @method('PUT')


                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">
                        <i class="fa fa-edit"></i> Edit Category
                    </h4>
                </div>



                <!-- Modal Body -->
                <div class="modal-body">


                    <!-- Category Name -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="category_name">Name <sup
                                class="text-danger">*</sup> :</label>
                        <div class="col-md-8 col-sm-8">
                            <input class="form-control edit-name" type="text" name="name"
                                value="{{ old('name') }}" data-parsley-required="true" />
                        </div>
                    </div>



                     <!-- Category Parent -->
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4">Parent Category:</label>
                    <div class="col-md-8 col-sm-8">
                        <select name="parent_id" class="form-control chosen-select-100-percent edit-parent-id"
                            data-placeholder="--Select--">
                            <option value=""></option>
                            @foreach ($parent_categories ?? [] as $parentCategory)
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
                </div>



                    <!-- Status -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">
                            Status:
                        </label>
                        <div class="col-md-3 col-sm-3">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="status" value="1" id="radio-required">
                                    Active
                                </label>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-4">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="status" id="radio-required2" value="0">
                                    Inactive
                                </label>
                            </div>
                        </div>

                    </div>


                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">

                    <!-- Submit -->
                    <div class="btn-group btn-corner">
                        <a href="javascript:;" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-save"></i> Update
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
