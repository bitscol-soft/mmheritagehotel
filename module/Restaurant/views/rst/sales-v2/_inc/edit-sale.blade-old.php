<form action="{{ route('rst.sales-v2.update', $sale->id) }}" method="POST" id="sale-form">
    @csrf
    @method('PUT')

    <div class="row" style="margin-top: 5px">
        <!-- Search Guest Name -->
        <div class="col-md-3">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Guest:</span>
                    <div class="input-group">
                        <input type="hidden" name="hotel_guest_id" id="hotel_guest_id" value="{{ $sale->guest_id }}">
                        <input type="text" name="guest_name" id="guest_name" value="{{ $sale->guest_name }}"
                            placeholder="Name/Mobile No." class="form-control" required>

                    </div>
                </div>
            </div>
        </div>


        {{-- Search By Room --}}
        <div class="col-md-3">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Table No: </span>
                    <input type="hidden" name="hotel_table_no_id" id="hotel_table_no_id" value="{{ $sale->table_id }}">

                    <input type="text" name="hotel_table_no" id="hotel_table_no"
                        value="{{ optional($sale->table)->table_no }}" placeholder="Room Number" class="form-control">
                </div>

            </div>
        </div>


        {{-- Search By Booking --}}
        <div class="col-md-3">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Waiter No:</span>

                    <input type="text" name="waiter_no" id="waiter_no" value="{{ $sale->waiter_no }}"
                        placeholder="Waiter No" class="form-control">
                </div>
            </div>
        </div>



        <!-- Sale Invoice ID -->
        <div class="col-md-3">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Bill Id</span>
                    <input type="text" tabindex="-1" class="form-control" id="invoice_id" placeholder="Invoice ID"
                        name="invoice_no" value="{{ $sale->invoice_no }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if ($sale->payment_status == 'Due')
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">Product</span>
                    <input type="text" name="product_name" class="form-control drug-name input-search"
                        placeholder="Search by Product Name / Barcode" autocomplete="off" style="z-index: 0">
                </div>
            </div>
        @endif

    </div>

    <div class="row" style="margin-top: 15px">
        <div class="col-sm-12 tableContainer">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Code</th>
                        <th width="10%">Stock Qty</th>
                        <th width="10%">Quantity</th>
                        <th width="10%">Unit</th>
                        <th width="10%">Sales Price</th>
                        <th width="10%">Vat</th>
                        <th width="15%">Total</th>
                        <th width="1%"></th>
                    </tr>
                </thead>
                <tbody id="product-details">
                    @forelse ($sale->items as $item)
                        <tr class="product-row row-{{ $item->id }}">
                            <td>
                                {{ optional($item->product)->name }}
                                <input type="hidden" value="{{ $item->product_id }}" name="product_ids[]"
                                    id="productId" class="productId">
                                <input type="hidden" value="{{ $item->quantity }}" name="old_quantity[]"
                                    id="productId" class="productId">
                            </td>
                            <td>{{ optional($item->product)->barcode }}</td>
                            <td>
                                <input type="number" min="1" value="{{ $item->quantity }}" name="sales_qty[]"
                                    max="" step="1" id="sales_qty" class="form-control sales-qty input-sm"
                                    placeholder="Quantity">
                            </td>
                            <td>
                                @if (optional($item->product)->pack_unit_id)
                                    <select class="form-control input-sm select-unit" name="unit_id[]"
                                        @if ($sale->payment_status == 'Paid') disabled @endif>
                                        <option value="{{ $item->unit_id }}"
                                            data-unit-price="{{ optional($item->product)->sale_price }}">
                                            {{ optional(optional($item->product)->unit)->name }}</option>
                                        <option value="{{ optional($item->product)->pack_unit_id }}"
                                            data-unit-price="{{ optional($item->product)->is_bar ? optional($item->product)->sale_price / optional($item->product)->pack_size : optional($item->product)->sale_price }}">
                                            {{ optional(optional($item->product)->pack_unit)->name }}</option>
                                    </select>
                                @else
                                    <input type="hidden" name="unit_id[]"
                                        value="{{ $item->unit_id }}">{{ optional($item->unit)->name }}
                                @endif
                            </td>
                            <td>
                                <input tabindex="-1" type="text" value="{{ $item->sales_price }}"
                                    name="sales_price[]" class="form-control sales-price input-sm" readonly>
                            </td>
                            <td>
                                <input tabindex="-1" type="text" value="{{ $item->vat_amount }}"
                                    data-vat-percent="{{ $vat_percent }}" name="item_vat_amounts[]"
                                    class="form-control item-vat-amount input-sm" readonly>
                            </td>
                            <td>
                                <input type="number"
                                    value="{{ $item->sales_price * $item->quantity + $item->vat_amount }}"
                                    name="item_price[]" class="form-control total-line-price input-sm"
                                    readonly="readonly">
                            </td>
                            <td>
                                @if ($sale->payment_status == 'Paid')
                                    <button class="not-delete" tabindex="-1" type="button" disabled><i
                                            class="fa fa-times text-danger"></i></button>
                                @else
                                    <button class="deletes"
                                        onclick="saleItemDelete(`{{ route('bar.sale-item-delete', $item->id) }}`, this)"
                                        tabindex="-1" type="button"><i
                                            class="fa fa-times text-danger"></i></button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="table-body-rows">
                            <th class="text-center"
                                style="font-size: 18px;line-height:50px;background-color:rgb(253, 232, 232)"
                                colspan="30">
                                <div class="product">
                                    <div class="">
                                        <strong class="text-danger"><i class="fas fa-exclamation-triangle"></i> No
                                            records found !</strong>
                                        <p onclick="addRow()" class="card-overlay pointer"><i
                                                class="fa fa-plus-circle"></i></p>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    @endforelse

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right">
                            <strong>Grand Total: </strong>
                        </td>
                        <td class="text-left"><span
                                id="grand-total">{{ number_format($sale->payable_amount, 2) }}</span></td>
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
                                <input name="payment_way" value="{{ $name }}" type="radio" class="ace"
                                    {{ $sale->payment_way == $name ? 'checked' : '' }}>
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
                                class="ace sale-payment-status" {{ $sale->payment_status == 'Due' ? 'checked' : '' }}>
                            <span class="lbl"> Due</span>
                        </label>
                        <label>
                            <input name="payment_status" value="Paid" type="radio"
                                class="ace sale-payment-status" {{ $sale->payment_status == 'Paid' ? 'checked' : '' }}>
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
                        <span class="input-group-addon currency">৳</span>
                        <input value="{{ $sale->vat_amount }}" type="number" class="form-control vat" name="vat_amount"
                            id="vat" ondrop="return false;">

                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Sub Total -->
                <div class="form-group">
                    <label class="col-md-4 control-label"><b>Sub Total</b>:</label>
                    <div class="input-group col-md-8">
                        <div class="input-group-addon currency">৳</div>
                        <input value="{{ $sale->subtotal }}" type="number" name="subtotal" class="form-control"
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
                        <div class="input-group-addon currency">৳</div>
                        <input value="{{ $sale->discount }}" type="number" min="0"
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
                        <div class="input-group-addon currency">৳</div>
                        <input value="{{ $sale->payable_amount }}" type="number"
                            name="payable_amount"class="form-control" id="grandTotal" ondrop="return false;"
                            onpaste="return false;" readonly>
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
                        <div class="input-group-addon currency">৳</div>
                        <input value="{{ $sale->service_charge }}" type="number"
                            class="form-control service_charge" name="service_amount" id="service_charge"
                            placeholder="Service Charge" ondrop="return false;">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Sub Total -->
                <div class="form-group">
                    <label class="col-md-4 control-label"><b>Paid Amount</b>:</label>
                    <div class="input-group col-md-8">
                        <div class="input-group-addon currency">৳</div>
                        <input value="{{ $sale->paid_amount }}" type="number" name="paid_amount"
                            class="form-control" id="paid_amount" ondrop="return false;" onpaste="return false;"
                            readonly>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-6">
                <!-- Sub Total -->
                @if ($sale->payment_status == 'Due')
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
                @endif
            </div>
            <div class="col-sm-6">
                <!-- Discount -->
                <div class="col-sm-4"></div>
                <div class="col-sm-8">
                    @if ($sale->payment_status == 'Paid')
                        <div class="btn-group" style="padding: 3px">
                            <a class="btn btn-danger btn-sm"
                                href="{{ route('bar.sales-v2.show', $sale->id) }}?invoice_type=pos" target="_blank">
                                <i class="fa fa-print"></i> Pos Print
                            </a>
                            <a class="btn btn-purple btn-sm"
                                href="{{ route('bar.sales-v2.show', $sale->id) }}?invoice_type=normal"
                                target="_blank">
                                <i class="fa fa-print"></i> Normal Print
                            </a>
                        </div>
                    @else
                        <div class="btn-group">
                            <button class="btn btn-danger btn-sm">
                                <i class="fa fa-times"></i> Cancel
                            </button>
                            <button class="btn btn-primary btn-sm save-sale" type="button">
                                <i class="fa fa-check-circle"></i> Save
                            </button>
                            <button class="btn btn-success btn-sm payment-btn" type="button">
                                <i class="fa fa-dollar"></i> Payment
                            </button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</form>
