<div id="guest-modal" class="guest modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: rgb(41, 4, 77)">
                <button type="button" class="close white" data-dismiss="modal">&times;</button>
                <h4 class="white bigger"><i class="fa fa-users"></i>Guest</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12">

                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-addon">Guest Name</span>
                                <select name="customer_id" class="form-control chosen-select-100-percent" id="customer_id" data-placeholder="Choose Guest" data-selected="{{ old('customer_id') }}" required>
                                    <option></option>
                                    @foreach ($guest as $id => $data)
                                        <option value="{{ $data->id }}">
                                            {{ $data->name }} -> {{ $data->phone_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="form-group mb-3 mt-2">
                        <div class="pull-right">
                            <div class="btn-group btn-corner">
                                <button class="btn btn-sm" data-dismiss="modal">
                                    <i class="fa fa-times"></i>
                                    Cancel
                                </button>
                                <button type="button" class="btn-sm btn-outline-success">
                                    <i class="fa fa-save"></i>
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>



                </div>



            </div>
        </div>
    </div>
</div>
