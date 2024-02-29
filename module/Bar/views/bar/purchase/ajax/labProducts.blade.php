@forelse($products as $product)
    <tr class="product-row">
        <td>
            <span style="color: green">{{optional($product->meterial)->name??''}} ({{ $product->available_quantity}}/p)</span>
            <input type="hidden" value="{{$product->id}}" name="lab_id[]" tabindex="-1" class="lab_id">
            <input type="hidden" value="{{optional($product->meterial)->id??''}}" name="material_id[]" tabindex="-1" class="material_id">
        </td>
        <td>
            <input type="number" name="unit_tp[]" min="0" step="any" tabindex="-1" autocomplete="off" ondrop="return false;"required
                   value="{{$product->retail_unit_tp * $product->pack_size}}" class="form-control
                   small-label-box unit-tps">

            <input type="hidden" name="retail_unit_tp[]" min="0" value="{{$product->retail_unit_tp}}" class="retail-unit-tps">

        </td>

        <td>
            <input type="text" name="pack_size[]" min="0" tabindex="-1" value="{{$product->pack_size}}" step="any"
                   class="form-control small-label-box pack-sizes" placeholder="Size" required>
        </td>
        <td>
            <input type="number" name="quantity[]" min="0" value="0" autocomplete="off" class="form-control
            small-label-box quantities"  ondrop="return false;" placeholder="Qty" required>
            <input type="hidden" name="retail_quantity[]" min="0" class="retail-quantities" >
        </td>
        <td><span id="uom">{{optional($product->wholesaleUnit)->name??""}}</span> </td>
        <td>
            <input type="text" name="expiry_date[]" class="form-control small-label-box expire-dates"
                   placeholder="Expiry Date" autocomplete="off">
        </td>
        <td>
            <input value="0" name="total_price[]" type="number" min="0" step="any" autocomplete="off" tabindex="-1"
                   placeholder="Sub Total" class="form-control small-label-box total-line-prices" readonly>
        </td>
    </tr>
@empty
    <h5   class="text-danger text-center d-block">No Product Found</h5>
@endforelse
