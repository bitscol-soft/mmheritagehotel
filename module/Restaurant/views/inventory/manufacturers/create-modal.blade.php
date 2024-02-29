<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('rst.manufacturers.store') }}" class="form-horizontal" role="form"
                data-parsley-validate novalidate>
                @csrf

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Add Manufacturer</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Name <sup class="text-danger">*</sup>:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" name="name" value="" placeholder="Enter  Name">
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
