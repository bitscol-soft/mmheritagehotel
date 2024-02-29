@forelse ($sale->items as $item)
    <tr class="product-row">
        <td>
            <span id="product_name">{{ optional($item->product)->name }}</span>
            <input type="hidden" value="{{ $item->product->id }}" name="product_ids[]" id="productId">
            <input type="hidden" value="{{ $item->sale->id }}" name="sale_ids[]">
        </td>
        <td>
            <span class="returnable_qty">{{ $item->quantity }}</span>
        </td>
        <td>
            <input type="text" value="{{ $item->quantity }}" min="1" max="{{ $item->quantity }}"
                name="return_quantity[]" class="form-control only-number return-qty" autocomplete="off">

        </td>
        <td>
            <input type="number" min="1" name="product_cost[]"
                value="{{ optional($item->product)->sale_price }}" step="any" class="form-control product_cost"
                autocomplete="off" onkeyup="" onpaste="return false;" placeholder="Quantity">
        </td>
        <td>
            <input type="number" min="1" name="total_amount[]"
                value="{{ number_format(optional($item->product)->sale_price * $item->quantity) }}"
                class="form-control total_cost" autocomplete="off" onkeyup="" onpaste="return false;"
                placeholder="Quantity">
        </td>

        <td>
            <button class="btn btn-xs btn-danger delete" tabindex="-1" type="button">
                <i class="fa fa-times"></i>
            </button>
        </td>
    </tr>
@empty
    <tr class="product-row">
        <td colspan="6">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i>
                No product found.
            </div>
        </td>
    </tr>
@endforelse
