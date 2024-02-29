<form action="{{ route('bar.sales-v2.update', $sale->id) }}" method="POST" id="sale-form">
    @csrf

    <x-multi-account-pay-modal :payable="$sale->payable_amount" :sourceid="$sale->id" sourcetype="Bar Sale" />

    {{-- FOR UPDATING ROUTE ON [EDIT-SALE.BLADE.PHP] WHILE SAVING/SUBMITTING SALE WITHOUT ANY PAGE RELOAD --}}
    <div id="forSaleUpdateMethod">
        @method('PUT')
    </div>



    <input type="hidden" name="is_soft_save" id="is_soft_save" value="0">

    <div class="row" style="margin-top: 5px">
        <!-- Search Guest Name -->
        <div class="col-md-3">
            <x-widget.text-input-group title="Guest" name="guest_name" :value="$sale->guest_name" />
            {{-- <x-widget.guest-select :guests="$guests" /> --}}
        </div>

        <input type="hidden" name="pay_booking_id" id="pay_booking_id">
        <input type="hidden" name="customer_id" id="booking_customer_id">

        {{-- Search By Room --}}
        <div class="col-md-2 col-sm-6 mb-2 margin-bottom-10 width-50-per">
            <select name="hotel_guest_id" id="room_no" class="select2 form-control"
                data-placeholder="--Select Room--">
                <option value=""></option>
                @foreach ($room_numbers as $id => $name)
                    <option {{ isset($sale) && $sale->hotel_guest_id == $id ? 'selected' : '' }}
                        value="{{ $id }}">
                        {{ $name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Search By Table --}}
        <div class="col-md-2">
            <x-widget.table-select :tables="$tables" :value="$sale->table_id" />
        </div>


        {{-- Search By Booking --}}
        <div class="col-md-2">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Waiter:</span>

                    <input type="text" name="waiter_no" id="waiter_no" value="{{ $sale->waiter_no }}"
                        placeholder="Waiter No" class="form-control">
                </div>
            </div>
        </div>



        <!-- Sale Invoice ID -->
        <div class="col-md-3">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Bill</span>
                    <input type="text" tabindex="-1" class="form-control" id="invoice_id" placeholder="Invoice ID"
                        name="invoice_no" value="{{ $sale->invoice_no }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if ($sale->payment_status == 'Due')
            <div class="col-md-6">
                <x-widget.product-select />
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <input type="text" class="form-control only-number" id="input-big-unit-id">
                    <span class="input-group-addon" id="input-unit-id"></span>

                    <input type="text" class="form-control only-number" id="input-small-qty">
                    <span class="input-group-addon" id="input-pack-unit-id"></span>

                </div>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control date-picker pointer sale-date pointer" name="date"
                    value="{{ fdate($sale->date, 'Y-m-d') }}" readonly>
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
                        <th width="24%">Quantity</th>
                        <th width="10%">Complimentary</th>
                        <th width="10%">Sales Price</th>
                        <th width="10%">Vat</th>
                        <th width="12%">Total</th>
                        <th width="1%"></th>
                    </tr>
                </thead>
                <tbody id="product-details">
                    @forelse ($sale->items as $item)
                        <tr class="product-row row-{{ $item->id }} tr-product-{{ $item->product_id }}">
                            <td>
                                {{ optional($item->product)->name }}
                                <input type="hidden" value="{{ $item->product_id }}" name="product_ids[]"
                                    class="productId">
                                <input type="hidden" value="{{ $item->quantity }}"
                                    name="old_quantity[]"class="old_quantity">
                                <input type="hidden" name="unit_id[]" value="{{ $item->unit_id }}">
                                <input type="hidden" name="small_unit_id[]" value="{{ $item->small_unit_id }}">
                            </td>
                            <td>{{ optional($item->product)->barcode }}</td>

                            <td>
                                <div class="input-group">
                                    @if (hasPermission('bar.sales.quantity-update', $slugs))
                                        <div class="spinbox-buttons input-group-btn">
                                            <button type="button"
                                                class="btn table-qty-decrease-btn spinbox-down btn-xs btn-danger qty-decrease"
                                                onclick="manageQty('decrease', this)">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                    @endif

                                    @if (optional($item->product)->pack_unit_id)
                                        <input type="number" value="{{ $item->small_quantity }}" name="small_qty[]"
                                            class="form-control small-unit-qty input-sm" autocomplete="off" readonly>
                                        <span class="input-group-addon">{{ optional($item->small_unit)->name }}</span>
                                    @endif
                                    <input type="number" value="{{ $item->quantity }}" name="sales_qty[]"
                                        class="form-control big-unit-qty input-sm" autocomplete="off" readonly>
                                    <span class="input-group-addon">{{ optional($item->unit)->name }}</span>

                                    @if (hasPermission('bar.sales.quantity-update', $slugs))
                                        <div class="spinbox-buttons input-group-btn">
                                            <button type="button"
                                                class="btn table-qty-decrease-btn spinbox-down btn-xs btn-success qty-increase"
                                                onclick="manageQty('increase', this)">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    @endif

                                </div>
                            </td>
                            <td>
                                <input tabindex="-1" type="text" value="{{ $item->item_discount }}"
                                    name="item_discount[]" class="form-control item_discount input-sm">
                            </td>
                            <td>
                                <input tabindex="-1" type="text" value="{{ $item->sales_price }}"
                                    name="sales_price[]" class="form-control sales-price input-sm" readonly>
                            </td>
                            <td>
                                <input tabindex="-1" type="text" value="{{ $item->vat_amount }}"
                                    data-vat-percent="{{ vatSetting()->resturent_vat }}" name="item_vat_amounts[]"
                                    class="form-control item-vat-amount input-sm" readonly>
                            </td>
                            <td>
                                <input type="number"
                                    value="{{ $item->sales_price * $item->quantity + $item->vat_amount - $item->item_discount }}"
                                    name="item_price[]" class="form-control total-line-price input-sm"
                                    readonly="readonly">
                            </td>
                            <td>
                                @if ($sale->payment_status == 'Paid')
                                    <button class="not-delete" tabindex="-1" type="button" disabled><i
                                            class="fa fa-times text-danger"></i></button>
                                @else
                                    @if (hasPermission('bar.sales.delete', $slugs))
                                        <button class="deletes"
                                            onclick="saleItemDelete(`{{ route('bar.sale-item-delete', $item->id) }}`, this)"
                                            tabindex="-1" type="button">
                                            <i class="fa fa-times text-danger"></i>
                                        </button>
                                    @endif
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
                                        <strong class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                            No records found !
                                        </strong>
                                        <p onclick="addRow()" class="card-overlay pointer">
                                            {{-- <i class="fa fa-plus-circle"></i> --}}
                                        </p>
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
            {{-- <div class="col-sm-6">
                <div class="form-group">
                    <label class="col-md-4 control-label"><b>Payment Way</b>:</label>
                    <div class="col-md-8">
                        @foreach ($account_types as $id => $name)
                            <label>
                                <input name="payment_way" value="{{ $id }}" type="radio" class="ace"
                                    {{ $sale->payment_way == $name ? 'checked' : '' }}>
                                <span class="lbl"> {{ $name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div> --}}
            <div class="col-sm-6" hidden>
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
                                class="ace sale-payment-status"
                                {{ $sale->payment_status == 'Paid' ? 'checked' : '' }}>
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
                        <input value="{{ $sale->vat_amount ?? 0 }}" type="number" class="form-control vat"
                            name="vat_amount" id="vat" ondrop="return false;">

                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Sub Total -->
                <div class="form-group">
                    <label class="col-md-4 control-label"><b>Sub Total</b>:</label>
                    <div class="input-group col-md-8">
                        <div class="input-group-addon currency">RM</div>
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
                        <div class="input-group-addon currency">RM</div>
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
                        <div class="input-group-addon currency">RM</div>
                        <input value="{{ (int) $sale->payable_amount }}" type="number"
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
                        <div class="input-group-addon currency">RM</div>
                        <input value="{{ $sale->service_amount }}" type="number"
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
                        <div class="input-group-addon currency">RM</div>
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
                            <button class="btn btn-primary btn-sm save-only" type="button" name="submit"
                                value="save">
                                <i class="fa fa-check-circle"></i> Save
                            </button>
                            <button class="btn btn-purple btn-sm save-sale" type="button" name="submit"
                                name="save_print">
                                <i class="fad fa-file-pdf"></i> Save & Print
                            </button>
                            <button class="btn btn-success btn-sm payment-btn" type="button" data-toggle="modal"
                                data-target="#account-type-modal" name="submit" name="payment">
                                <i class="fa fa-dollar"></i> Payment
                            </button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</form>
