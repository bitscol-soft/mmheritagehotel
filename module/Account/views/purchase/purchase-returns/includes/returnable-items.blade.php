@forelse($purchase->details as $detail)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            <input type="hidden" name="returnable_product_ids[]" class="form-control" value="{{ $detail->product_id }}">
            <input type="hidden" name="purchase_detail_ids[]" class="form-control" value="{{ $detail->id }}">

            {{ optional($detail->product)->name }}
        </td>
        <td class="text-center">{{ optional(optional($detail->product)->unit)->name }}</td>
        <td>
            <input type="text" class="form-control only-number text-center return-price" name="returnable_prices[]" value="{{ $detail->price }}">
        </td>
        <td>
            <input type="text" readonly class="form-control only-number text-center returnable-qty" name="returnable_qtys[]" value="{{ $detail->returnable_qty ?? $detail->quantity }}">
        </td>
        <td>
            <input name="return_quantities[]" required type="text" value="" class="form-control text-center only-number return-quantity" />
        </td>
        <td>
            <input name="return_subtotals[]" readonly type="text" value="" class="form-control only-number text-center return-subtotal" />
        </td>
    </tr>
@empty 
    <tr>
        <th></th>
    </tr>
@endforelse