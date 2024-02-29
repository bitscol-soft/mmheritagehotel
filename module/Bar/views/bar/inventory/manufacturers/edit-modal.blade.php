<div class="modal fade" id="modal-dialog{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('bar.manufacturers.update', $item->id) }}" class="form-horizontal"
                role="form">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">
                        <i class="fa fa-pencil-circle"></i> Edit Category
                    </h4>
                </div>

                <div class="modal-body">


                    <!-- Category Name -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="category_name">Name <sup
                                class="text-danger">*</sup> :</label>
                        <div class="col-md-8 col-sm-8">
                            <input class="form-control" type="text" name="name"
                                value="{{ $item->name, old('name') }}" data-parsley-required="true" />
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
                                    <input type="radio" name="status" {{ $item->status == 1 ? 'checked' : '' }}
                                        value="1" id="radio-required">
                                    Active
                                </label>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-4">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="status" {{ $item->status == 0 ? 'checked' : '' }}
                                        id="radio-required2" value="0">
                                    Inactive
                                </label>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <div class="btn-group btn-corner">
                        <a href="javascript:;" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
