<div id="account-type-modal" class="modal">

    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="blue bigger"><i class="fa fa-info-circle"></i> Payment Way </h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" name="is_multiple_way" value="1">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="accountTypeTable">
                                <thead>
                                    <tr>
                                        <th width="30%">Account Type</th>
                                        <th>Amount</th>
                                        <th width="7%">
                                            <i class="ace-icon fa fa-times-circle fa-lg"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($collections->count() > 0)
                                        @foreach ($collections as $transaction)
                                        <tr>
                                            <th width="30%">
                                                <input type="hidden" name="transaction_ledger_ids[]" value="{{ $transaction->id }}">
                                                <select name="modal_account_types[]" style="width: 100%" class="form-control select2 select-account-type"
                                                    data-placeholder="- Select Type -" aria-hidden="true">
                                                    <option value=""></option>
                                                    @foreach (account_types() as $id => $name)
                                                        <option value="{{ $id }}" {{ $transaction->payment_type == $id ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </th>

                                            <th style="display: flex">
                                                <input name="modal_account_paid_amounts[]" value="{{ $transaction->in }}" type="text" placeholder="Enter Amount"
                                                    class="form-control account-way-paid-amount only-number" />
                                            </th>

                                            <th width="7%">
                                                <button type="button" class="remove-row"
                                                    style="background-color: transparent;border: none;" title="Remove"
                                                    disabled=""><i class="far fa-times-circle fa-lg text-danger"></i>
                                                </button>
                                            </th>
                                        </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <th width="30%">
                                            <input type="hidden" name="transaction_ledger_ids[]" value="">
                                            <select name="modal_account_types[]" style="width: 100%" class="form-control select2 select-account-type"
                                                data-placeholder="- Select Type -" aria-hidden="true">
                                                <option value=""></option>
                                                @foreach (account_types() as $id => $name)
                                                    <option value="{{ $id }}">
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </th>

                                        <th style="display: flex">
                                            <input name="modal_account_paid_amounts[]" type="text" placeholder="Enter Amount"
                                                class="form-control account-way-paid-amount only-number" />
                                        </th>

                                        <th width="7%">
                                            <button type="button" class="remove-row"
                                                style="background-color: transparent;border: none;" title="Remove"
                                                disabled=""><i class="far fa-times-circle fa-lg text-danger"></i>
                                            </button>
                                        </th>
                                    </tr>
                                    @endif

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td class="text-center">
                                            <input type="text" name="total_amount" class="form-control total_amount"
                                                value="0" readonly>
                                        </td>
                                        <td>
                                            <button type="button" id="addrow"
                                                style="background-color: transparent;border: none;">
                                                <i class="ace-icon fa fa-plus-circle text-success fa-lg rotate"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr style="display: none" id="card_info">
                                        <td><strong>Card Athorized No</strong></td>
                                        <td class="text-center">
                                            <input type="text" name="card_info" class="form-control card_info"
                                                value="" placeholder="XXXX-XXXX-XXXX">
                                        </td>
                                        <td>

                                        </td>

                                    </tr>
                                </tfoot>
                            </table>

                            <table style="width: 100%; background-color: #F2F2F2">
                                <tfoot>
                                    <tr>
                                        <td class="calculation_tr">

                                            <div class="row">

                                                <div class="col-md-7">
                                                    Payable Amount:
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" class="grand_total form-control" value="{{ (int)$payable }}" style="border: none; font-size:22px" readonly>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="calculation_tr">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    Paid Amount:
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control account-paid-amount" style="border: none; font-size:22px" readonly>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>

                                    <tr>

                                        <td class="calculation_tr">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    Due Amount:
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control due_amount" style="border: none; font-size:22px" readonly>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="calculation_tr">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    Change Amount:
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="change_amount" class="change_amount form-control" style="border: none; font-size:22px" readonly>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                </tfoot>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="btn-group" style="display: flex">
                    <button type="button" class="btn btn-sm btn-success btn-block"
                        style="width: 100%; border-radius: 0px !important; background-color: #ffc3be !important; border-color: #ffbebe; color: black !important;"
                        data-dismiss="modal">
                        <i class="ace-icon fa fa-times"></i> Cancel
                    </button>
                    <button type="button" name="button" value="payment"
                        class="btn btn-sm btn-primary btn-block save-button"
                        style="background-color: #0044ff !important; border-color: #0044ff !important; border-radius: 0px !important;">
                        <i class="fa fa-check-circle"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
