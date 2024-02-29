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
                        <i class="fa fa-edit"></i> Edit Table
                    </h4>
                </div>



                <!-- Modal Body -->
                <div class="modal-body">


                    <!-- Name -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="name">Name: </label>
                        <div class="col-md-8 col-sm-8">
                            <input class="form-control edit-name" type="text" name="name" value="{{ old('name') }}"
                                data-parsley-required="true" />
                        </div>
                    </div>


                    <!-- Name -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="name">Table No: </label>
                        <div class="col-md-8 col-sm-8">
                            <input class="form-control edit-table-no" type="text" name="table_no" value="{{ old('table_no') }}"
                                data-parsley-required="true" />
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
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-sm btn-success">Update</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
