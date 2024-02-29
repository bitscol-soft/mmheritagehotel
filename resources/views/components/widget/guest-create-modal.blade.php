<div class="modal fade" id="add-guest-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="" class="form-horizontal" role="form">
                @csrf

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Add Guest</h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <!-- Guest Name -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Guest Name <sup
                                class="text-danger">*</sup>:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" name="guest_name" value=""
                                placeholder="Enter Guest">
                        </div>
                    </div>

                    <!-- Guest Mobile -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Guest Mobile <sup
                                class="text-danger">*</sup>:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" name="guest_mobile" value="+880"
                                placeholder="Enter Mobile">
                        </div>
                    </div>

                    <!-- Guest Mobile -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Type :</label>
                        <div class="col-md-8 col-sm-8">
                            <div class="col-md-3 col-sm-3">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="is_stuff" value="0" id="radio-required" checked>
                                        Guest
                                    </label>
                                </div>
                            </div>


                            <div class="col-md-4 col-sm-4">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="is_stuff" id="radio-required2" value="1">
                                        Hotel Staff
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- Modal Footer -->
                <div class="modal-footer">

                    <!-- Submit -->
                    <div class="btn-group btn-corner">
                        <button type="button" class="btn btn-sm btn-success save-guest">
                            <i class="fa fa-save"></i>
                            Save
                        </button>
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger" data-dismiss="modal">
                            <i class="fa fa-times"></i> Close
                        </a>
                    </div>


                </div>


            </form>
        </div>
    </div>
</div>
