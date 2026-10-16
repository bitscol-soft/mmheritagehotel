<div class="modal fade" id="modal-dialog{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('bar.product-batches.update', $item->id) }}" class="form-horizontal"
                role="form">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">
                        <i class="fa fa-pencil"></i> Edit Product Batch
                    </h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="batch_number">Batch Number <sup
                                class="text-danger">*</sup> :</label>
                        <div class="col-md-8 col-sm-8">
                            <input class="form-control" type="text" name="batch_number"
                                value="{{ old('batch_number', $item->batch_number) }}" data-parsley-required="true" />

                            @error('batch_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="btn-group btn-corner">
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-pencil-square-o"></i>
                            Update
                        </button>
                        <a href="javascript:;" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
