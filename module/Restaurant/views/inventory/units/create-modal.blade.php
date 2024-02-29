<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('rst.product-units.store') }}" accept-charset="UTF-8"
                class="form-horizontal">
                @csrf

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Small Unit</h4>
                </div>

                <!-- Modal Body -->

                <div class="modal-body">

                    <!-- Unit Name -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Unit Name *:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" name="name" value=""
                                placeholder="Enter uom Name">
                        </div>
                    </div>


                    <!-- Type -->
                    @if (setting('mother_inventory'))
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-4">For *:</label>
                            <div class="col-md-8 col-sm-8">
                                <x-bar-or-restaurant />
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Type *:</label>
                        <div class="col-md-8 col-sm-8">
                            <label><input type="radio" name="pack_or_retail" value="pack" required> Pack</label>
                            <label><input type="radio" name="pack_or_retail" value="retail" required> Retail</label>
                            <label><input type="radio" name="pack_or_retail" value="package" required> Package</label>
                            <label><input type="radio" name="pack_or_retail" value="matrial" required> Matrial</label>

                        </div>
                    </div>



                </div>

                <!-- Modal footer -->
                <div class="modal-footer">

                    <!-- Submit -->
                    <div class="btn-group btn-corner">
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-plus"></i>
                            Confirm
                        </button>
                        <a href="javascript:;" class="btn btn-sm btn-danger" data-dismiss="modal">
                            <i class="fa fa-close"></i> Close
                        </a>
                    </div>

                </div>


            </form>
        </div>
    </div>
</div>
