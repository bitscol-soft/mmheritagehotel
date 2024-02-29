@forelse($products as $product)
    <tr class="product-row">
        <td>
            <span style="color: green">{{ $product->name }} ({{ $product->available_quantity }}/p)</span>
            <input type="hidden" value="{{ $product->id }}" name="product_id[]" tabindex="-1" class="product-ids">
        </td>

        <td>
            <input type="number" name="purchase_price[]" min="0" step="any" tabindex="-1" autocomplete="off"
                ondrop="return false;" required value="{{ $product->unit_cost }}"
                class="form-control small-label-box unit-tps">

        </td>
        <td>
            <input type="number" name="sale_price[]" min="0" step="any" tabindex="-1"
                placeholder="Sales Price" required value="{{ $product->sale_price }}"
                class="form-control small-label-box
                    sales-prices">
        </td>

        <td>
            <input type="number" name="quantity[]" min="0" value="0" autocomplete="off"
                class="form-control
            small-label-box quantities" ondrop="return false;" placeholder="Qty"
                required>
        </td>
        <td><span id="uom">{{ optional($product->unit)->name }}</span> </td>

        <td>
            <input value="0" name="unit_vat[]" onkeyup="subtotalVAT()" type="number" min="0" step="any"
                placeholder="Unit VAT" class="form-control small-label-box unit-vat">
        </td>
        <td>
            <input value="0" name="total_price[]" type="number" min="0" step="any" autocomplete="off"
                tabindex="-1" placeholder="Sub Total" class="form-control small-label-box total-line-prices" readonly>
        </td>
    </tr>
@empty
    <h5 class="text-danger text-center">No Product Found</h5>
@endforelse
