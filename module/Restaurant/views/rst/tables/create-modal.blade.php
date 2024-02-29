<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('rst.table-manages.store') }}" class="form-horizontal"
                role="form" data-parsley-validate novalidate>
                @csrf

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Add Table</h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">




                    <!-- Name -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Name:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" name="name" value=""
                                placeholder="Enter Name">
                        </div>
                    </div>


                    <!-- No -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">No <sup
                                class="text-danger">*</sup>:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" name="table_no" value=""
                                placeholder="Enter Table No">
                        </div>
                    </div>

                </div>


                <!-- Modal Footer -->
                <div class="modal-footer">

                    <!-- Submit -->
                    <div class="btn-group btn-corner">
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-plus"></i>
                            Save
                        </button>
                        <a href="javascript:;" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
                    </div>


                </div>


            </form>
        </div>
    </div>
</div>
