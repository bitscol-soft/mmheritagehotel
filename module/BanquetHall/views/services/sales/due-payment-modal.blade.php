<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="payment-form" action="" method="post">
                @csrf
                @method('PUT')

                <input type="hidden" name="is_from_due_collection" value="1">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Due Receive</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="previous-due" class="control-label">Due amount : </label>
                        <input class="form-control" id="previous-due" name="previous_due" type="number" readonly>
                    </div>

                    <div class="form-group">
                        <label for="payable-amount" class="control-label">Pay amount : </label>
                        <input class="form-control" id="payable-amount" name="payable_amount" type="number">
                    </div>

                    <div class="form-group">
                        <label for="payable-amount" class="control-label">Current due amount : </label>
                        <input class="form-control" id="current-due" name="current_due" type="number">
                    </div>

                    <div class="form-group">
                        <label for="payable-amount" class="control-label">Payment Type : </label>
                        <select name="account_type_id" class="form-control chosen-select-100-percent" data-placeholder="--Select Type--" required>
                            <option></option>
                            @foreach ($account_types as $id => $name)
                                <option value="{{ $id }}" {{ $id==1 ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-corner btn-group">
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                            <i class="fa fa-times"></i> Close
                        </button>
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="fa fa-check"></i> Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
