<div class="col-sm-9 padding-m-none" id="sale-form-data">
    <form action="{{ route('bar.sales-v2.store') }}" method="POST" id="sale-form">
        @csrf


        <x-multi-account-pay-modal />


        <input type="hidden" name="is_soft_save" id="is_soft_save" value="0">

        <div class="row tab-d-flex" style="margin-top: 5px">
            <!-- Search Guest Name -->
            <div class="col-md-3 col-sm-6 mb-2 margin-bottom-10 width-50-per">
                <x-widget.text-input-group title="Guest" name="guest_name" id="guest_name" />
                {{-- <x-widget.guest-select :guests="[]" /> --}}
                {{-- <x-widget.guest-search /> --}}
            </div>

            {{-- <input type="hidden" name="hotel_booking_id" id="hotel_booking_id"> --}}
            <input type="hidden" name="pay_booking_id" id="pay_booking_id">
            <input type="hidden" name="customer_id" id="booking_customer_id">

            {{-- Search By Room --}}
            <div class="col-md-2 col-sm-6 mb-2 margin-bottom-10 width-50-per">
                <select name="hotel_guest_id" id="room_no" class="select2 form-control"
                    data-placeholder="--Select Room--">
                    <option value=""></option>
                    @foreach ($room_numbers as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Search By Table --}}
            <div class="col-md-2 col-sm-6 mb-2 margin-bottom-10 width-50-per">
                <x-widget.table-select :tables="$tables" />
            </div>


            {{-- Search By Booking --}}
            <div class="col-md-2 col-sm-6 mb-2 margin-bottom-10 width-50-per">
                <div class="form-group margin-bottom-0">
                    <div class="input-group">
                        <span class="input-group-addon padding-right-18px">Waiter:</span>

                        <input type="text" name="waiter_no" id="waiter_no" placeholder="Waiter No"
                            class="form-control">
                    </div>
                </div>
            </div>



            <!-- Sale Invoice ID -->
            <div class="col-md-3 col-sm-6 mb-2 margin-bottom-10 width-50-per">
                <div class="form-group margin-bottom-0">
                    <div class="input-group">
                        <span class="input-group-addon padding-right-41px">Bill</span>
                        <input type="text" tabindex="-1" class="form-control" id="invoice_id"
                            placeholder="Invoice ID" name="invoice_no" value="{{ $invoice_id }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="display: nones">
            <div class="col-md-6 col-sm-12 mb-2 margin-bottom-10 width-50-per">
                <x-widget.product-select />
            </div>
            <div
                class="col-md-3 col-sm-6 mb-2 margin-bottom-10 margin-top-40 width-50-per tab-inline-block tab-product-units">
                <div class="input-group margin-bottom-0" id="input-quantity-group">
                    <input type="text" class="form-control only-number" id="input-big-unit-id">
                    <span class="input-group-addon" id="input-unit-id"></span>
                    <input type="text" class="form-control only-number" id="input-small-qty">
                    <span class="input-group-addon" id="input-pack-unit-id"></span>

                </div>
            </div>
            <div class="col-md-3 col-sm-3 mb-2 margin-bottom-10">
                <input type="text" name="date" class="form-control date-picker pointer sale-date"
                    value="{{ getSaleDate() ?? date('Y-m-d') }}" readonly>
            </div>

        </div>

        <div class="row" style="margin-top: 15px">
            <div class="col-sm-12 tableContainer">
                <table class="table table-bordered table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Code</th>
                            <th width="20%" class="sales-quantity-th">Quantity</th>
                            <th width="14%">Complimentary</th>
                            <th width="10%" class="sales-price-th">Sales Price</th>
                            <th width="10%">Vat</th>
                            <th width="10%">Total</th>
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
                                        <strong class="text-danger"><i class="fa fa-exclamation-triangle"></i>
                                            No records found !
                                        </strong>
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

        <div class="col-sm-12 padding-bottom-15px" style="background: aliceblue; border: 2px solid;margin-top:20px">

            <div class="row mt-1 mb-1">
                {{-- <div class="col-sm-6">
                    <div class="form-group margin-bottom-10">
                        <label class="col-md-4 control-label padding-m-left-none"><b>Payment Way</b>:</label>
                        <div class="col-md-8 padding-m-left-none">
                            @foreach ($account_types as $id => $name)
                                <label>
                                    <input name="payment_way" value="{{ $id }}" type="radio" class="ace">
                                    <span class="lbl"> {{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div> --}}
                <div class="col-sm-6" hidden>
                    <div class="form-group margin-bottom-none">
                        <label class="col-md-6 control-label padding-m-left-none"><b>Payment Status</b>:</label>
                        <div class="col-md-6 padding-m-left-none">
                            <label>
                                <input name="payment_status" value="Due" type="radio"
                                    class="ace sale-payment-status due-sale-payment-status" checked>
                                <span class="lbl"> Due</span>
                            </label>
                            <label>
                                <input name="payment_status" value="Paid" type="radio"
                                    class="ace sale-payment-status paid-sale-payment-status">
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
                        <label class="col-md-4 control-label padding-m-left-none"><b>Vat</b>:</label>
                        <div class="input-group col-md-8">
                            <span class="input-group-addon currency">৳</span>
                            <input value="0" type="number" class="form-control vat" name="vat"
                                id="vat" ondrop="return false;">

                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- Sub Total -->
                    <div class="form-group">
                        <label class="col-md-4 control-label padding-m-left-none"><b>Sub Total</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-addon currency">৳</div>
                            <input value="0" type="number" name="subtotal" class="form-control"
                                id="subTotal" readonly="">
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-6">
                    <!-- Discount -->
                    <div class="form-group">
                        <label class="col-md-4 control-label padding-m-left-none"><b>Discount</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-addon currency">৳</div>
                            <input value="0" type="number" min="0" step="any"
                                class="form-control changesNo discount" name="discount" id="discount"
                                placeholder="Discount" ondrop="return false;" tabindex="-1">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- Sub Total -->
                    <div class="form-group">
                        <label class="col-md-4 control-label padding-m-left-none"><b>Payable Amount</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-addon currency">৳</div>
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
                        <label class="col-md-4 control-label padding-m-left-none"><b>Service Charge</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-addon currency">৳</div>
                            <input value="0" type="number" class="form-control service_charge"
                                name="service_charge" id="service_charge" placeholder="Service Charge"
                                ondrop="return false;">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- Sub Total -->
                    <div class="form-group">
                        <label class="col-md-4 control-label padding-m-left-none"><b>Paid Amount</b>:</label>
                        <div class="input-group col-md-8">
                            <div class="input-group-addon currency">৳</div>
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
                        <label class="col-md-4 control-label padding-m-left-none"><b>Print MODE</b>:</label>
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
                    <div class="col-sm-8 text-m-center">
                        <div class="btn-group">
                            <button class="btn btn-primary btn-sm save-only" type="button" name="submit"
                                value="save">
                                <i class="fa fa-check-circle"></i> Save
                            </button>
                            <button class="btn btn-purple btn-sm save-sale" type="button" name="submit"
                                name="save_print">
                                <i class="fa fa-file-pdf-o"></i> Save & Print
                            </button>
                            <button class="btn btn-success btn-sm " type="button" name="button"
                                data-toggle="modal" data-target="#account-type-modal" name="payment">
                                <i class="fa fa-dollar"></i> Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
