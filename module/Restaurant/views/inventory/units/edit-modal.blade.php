<div class="modal fade" id="modal-dialog{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">


            <form method="POST" action="{{ route('rst.product-units.update', $item->id) }}">
                @csrf
                @method('PUT')


                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">
                        <i class="fa fa-pencil"></i> Edit Unit
                    </h4>
                </div>


                <!-- Modal Body -->
                <div class="modal-body">


                    <!-- Unit Name -->
                    <div class="form-group row">
                        <label class="control-label col-md-4 col-sm-4" for="category_name">Name <sup
                                class="text-danger">*</sup> :</label>
                        <div class="col-md-8 col-sm-8">
                            <input class="form-control" type="text" name="name"
                                value="{{ $item->name, old('name') }}" data-parsley-required="true" />
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Type *:</label>
                        <div class="col-md-8 col-sm-8">
                            <label><input type="radio" name="pack_or_retail" {{ $item->type == 'pack' ? 'checked' : '' }} value="pack" required> Pack</label>
                            <label><input type="radio" name="pack_or_retail" {{ $item->type == 'retail' ? 'checked' : '' }} value="retail" required> Retail</label>
                            <label><input type="radio" name="pack_or_retail" {{ $item->type == 'package' ? 'checked' : '' }} value="package" required> Package</label>
                        </div>
                    </div>



                    <!-- Status -->
                    <div class="form-group row">
                        <label class="control-label col-md-4 col-sm-4">
                            Status:
                        </label>
                        <div class="col-md-3 col-sm-3">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="status" {{ $item->status == 1 ? 'checked' : '' }}
                                        value="1" id="radio-required">
                                    Active
                                </label>
                            </div>
                        </div>


                        <div class="col-md-4 col-sm-4">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="status" {{ $item->status == 0 ? 'checked' : '' }}
                                        id="radio-required2" value="0">
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
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-pencil-square-o"></i>
                            Update
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
