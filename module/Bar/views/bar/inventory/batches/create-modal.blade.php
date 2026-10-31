<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('bar.product-batches.store') }}" class="form-horizontal" role="form"
                data-parsley-validate novalidate>
                @csrf

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Add Product Batch</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Batch Number <sup
                                class="text-danger">*</sup>:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" value="{{ old('batch_number') }}" class="form-control" name="batch_number"
                                placeholder="Enter Batch Number" autocomplete="off" required>

                            @error('batch_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
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
