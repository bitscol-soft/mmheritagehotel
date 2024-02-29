<div class="col-sm-9" id="sale-form-data">
    <form action="{{ route('rst.sales-v2.store') }}" method="POST" id="sale-form">
        @csrf
        <input type="hidden" name="is_soft_save" id="is_soft_save" value="0">

        <div class="row" style="margin-top: 5px">
            <!-- Search Guest Name -->
            <div class="col-md-3">
                <x-widget.guest-select :guests="[]" />
            </div>


            <!-- Search By Room -->
            <div class="col-md-3">
                <x-widget.table-select :tables="$tables"/>
            </div>


            <!-- Search By Booking -->
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Waiter:</span>

                        <input type="text" name="waiter_no" id="waiter_no" placeholder="Waiter No"
                            class="form-control">
                    </div>
                </div>
            </div>



            <!-- Sale Invoice ID -->
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">Bill</span>
                        <input type="text" tabindex="-1" class="form-control" id="invoice_id"
                            placeholder="Invoice ID" name="invoice_no" value="{{ $invoice_id }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6">
                <x-widget.product-select name="product_search" />
            </div>
            <div class="col-md-3">
                <input type="text" name="date" class="form-control date-picker pointer sale-date" value="{{ getSaleDate() ?? date('Y-m-d') }}" readonly>
            </div>

        </div>

        <div class="row" style="margin-top: 15px">
            <div class="col-sm-12 tableContainer">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Code</th>
                            <th width="10%">Quantity</th>
                            <th width="10%">Unit</th>
                            <th width="10%">Sales Price</th>
                            <th width="10%">Vat</th>
                            <th width="15%">Total</th>
                            <th width="1%"></th>
                        </tr>
                    </thead>
                    <tbody id="product-details">
                        <tr class="table-body-rows">
                            <th class="text-center"
                                style="font-size: 18px;line-height:50px;background-color:rgb(253, 232, 232)"
                                colspan="30">
                                <div class="product">
                                    <div class="">
                                        <strong class="text-danger"><i class="fas fa-exclamation-triangle"></i> No
                                            records found !</strong>
                                        <p onclick="addRow()" class="card-overlay pointer">
                                            {{-- <i class="fa fa-plus-circle"></i> --}}
                                        </p>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-right">
                                <strong>Grand Total: </strong>
                            </td>
                            <td class="text-left"><span id="grand-total">0</span></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="col-sm-12" style="background: aliceblue; border: 2px solid;margin-top:20px">

            <div class="row mt-1 mb-1">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Payment Way</b>:</label>
                        <div class="col-md-8">
                            @foreach ($account_types as $id => $name)
                                <label>
                                    <input name="payment_way" value="{{ $id }}" type="radio"
                                        class="ace">
                                    <span class="lbl"> {{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-md-6 control-label"><b>Payment Status</b>:</label>
                        <div class="col-md-6">
                            <label>
                                <input name="payment_status" value="Due" type="radio"
                                    class="ace sale-payment-status" checked>
                                <span class="lbl"> Due</span>
                            </label>
                            <label>
                                <input name="payment_status" value="Paid" type="radio"
                                    class="ace sale-payment-status">
                                <span class="lbl"> Paid</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <!-- Discount -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Vat</b>:</label>
                        <div class="input-group col-md-8">
                            <span class="input-group-addon currency">RM</span>
                            <input value="0" type="number" class="form-control vat" name="vat"
                                id="vat" ondrop="return false;">

                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- Sub Total -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Sub Total</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-addon currency">RM</div>
                            <input value="0" type="number" name="subtotal" class="form-control"
                                id="subTotal" ondrop="return false;" onpaste="return false;" readonly="">
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-6">
                    <!-- Discount -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Discount</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-addon currency">RM</div>
                            <input value="0" type="number" min="0" step="any"
                                class="form-control changesNo discount" name="discount" id="discount"
                                placeholder="Discount" ondrop="return false;" tabindex="-1">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- Sub Total -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Payable Amount</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-addon currency">RM</div>
                            <input value="0" type="number" name="payable_amount"class="form-control"
                                id="grandTotal" ondrop="return false;" onpaste="return false;" readonly>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-6">
                    <!-- Discount -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Service Charge</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-addon currency">RM</div>
                            <input value="0" type="number" class="form-control service_charge"
                                name="service_charge" id="service_charge" placeholder="Service Charge"
                                ondrop="return false;">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- Sub Total -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Paid Amount</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-addon currency">RM</div>
                            <input value="0" type="number" name="paid_amount" class="form-control"
                                id="paid_amount" ondrop="return false;" onpaste="return false;" readonly>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-6">
                    <!-- Sub Total -->
                    <div class="form-group">
                        <label class="col-md-4 control-label"><b>Print MODE</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>
                                        <input name="print_mode" value="pos" type="radio"
                                            class="ace invoice-type" checked>
                                        <span class="lbl"> POS</span>
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <label>
                                        <input name="print_mode" value="normal" type="radio"
                                            class="ace invoice-type">
                                        <span class="lbl"> NORMAL</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- Discount -->
                    <div class="col-sm-4"></div>
                    <div class="col-sm-8">
                        <div class="btn-group">
                            <button class="btn btn-primary btn-sm save-only" type="button" name="submit" value="save">
                                <i class="fa fa-check-circle"></i> Save
                            </button>
                            <button class="btn btn-purple btn-sm save-sale" type="button" name="submit" name="save_print">
                                <i class="fad fa-file-pdf"></i> Save & Print
                            </button>
                            <button class="btn btn-success btn-sm payment-btn" type="button" name="submit" name="payment">
                                <i class="fa fa-dollar"></i> Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
