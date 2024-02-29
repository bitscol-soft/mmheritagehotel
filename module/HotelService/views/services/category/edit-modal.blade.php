<div class="modal fade" id="modal-dialog{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('hotelservice.services.update', $item->id) }}" class="form-horizontal"
                role="form" data-parsley-validate novalidate>
                @csrf
                @method('PUT')
                <div class="modal-header">
                    @if (hasPermission('service.view', $slugs))

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Edit Service</h4>

                    @endif
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Name *:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="text" class="form-control" name="name"
                                value="{{ $item->name, old('name') }}" placeholder="Enter service Name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Price *:</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="number" class="form-control" name="price"
                                value="{{ $item->price, old('price') }}" placeholder="Enter service price">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-pencil-square-o"></i>
                        Update
                    </button>
                    <a href="javascript:;" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
