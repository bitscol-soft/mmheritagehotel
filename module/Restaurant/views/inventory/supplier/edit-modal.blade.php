<div class="modal fade" id="modal-dialog{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('rst.suppliers.store') }}" class="form-horizontal" role="form">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">
                        <i class="fa fa-pencil-circle"></i> Edit Supplier
                    </h4>
                </div>

                <div class="modal-body">


                    <!-- Category Name -->
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="name">Supplier Name <sup
                                class="text-danger">*</sup> :</label>
                        <div class="col-md-8 col-sm-8">
                            <input class="form-control" type="text" name="name"
                                value="{{ $item->name, old('name') }}" data-parsley-required="true" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="name">Phone :</label>
                        <div class="col-md-8 col-sm-8">
                            <input class="form-control" type="text" name="phone"
                                value="{{ $item->phone, old('phone') }}" data-parsley-required="true" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="name">Email:</label>
                        <div class="col-md-8 col-sm-8">
                            <input class="form-control" type="text" name="email"
                                value="{{ $item->email, old('email') }}" data-parsley-required="true" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4" for="address">Address :</label>
                        <div class="col-md-8 col-sm-8">
                            <input class="form-control" type="text" name="address"
                                value="{{ $item->address, old('address') }}" data-parsley-required="true" />
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
