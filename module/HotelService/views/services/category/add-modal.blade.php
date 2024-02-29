<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('hotelservice.services.store') }}" accept-charset="UTF-8"
                class="form-horizontal author_form" id="commentForm" role="form" data-parsley-validate novalidate
                enctype="multipart/form-data">
                @csrf

                <div class="modal-header">
                    @if (hasPermission('service.view', $slugs))
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Add New Service</h4>
                    @endif
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Name *:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                placeholder="Enter service Name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Price *:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="number" class="form-control" name="price" value="{{ old('price') }}"
                                placeholder="Enter service price">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    @if (hasPermission('service.create', $slugs))
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-plus"></i>
                            Confirm
                        </button>
                    @endif
                    <a href="javascript:;" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
