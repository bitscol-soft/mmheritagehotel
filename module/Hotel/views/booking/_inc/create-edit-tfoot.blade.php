@php
    if (!isset($transaction)) {
        $transaction = optional([]);
    }
@endphp
<tfoot style="position: relative;">

    @include('booking._inc._check-sms-and-email')

    <tr>

        <td class="text-right borderless" colspan="{{ $colspan ?? 11 }}">Subtotal <span class="currency-sign"></span></td>
        <td class="borderless">
            <input class="form-control subtotal-amount text-right"
                value="{{ calculateCurrencyAmount($subtotal ?? 0, 1) }}" readonly style="padding-right: 5px;">
            <input type="hidden" name="line_total" id="line_total">
        </td>

    </tr>

    <tr>
        <td class="text-right borderless" colspan="{{ $colspan ?? 11 }}">Service Charge <span
                class="currency-sign"></span></td>
        <td class="borderless">
            <input name="service_amount" value="{{ calculateCurrencyAmount($transaction->service_charge ?? 0, 1) }}"
                class="form-control service_amount text-right" readonly style="padding-right: 5px;">
        </td>
    </tr>
    <tr>
        <td class="text-right borderless" colspan="{{ $colspan ?? 11 }}">
            <input type="hidden" name="vat" id="vat" value="{{ vatSetting()->hotel_vat ?? 0 }}">

            Vat({{ vatSetting()->hotel_vat }}%) <span class="currency-sign"></span>
        </td>
        <td class="borderless">
            <input type="text" name="vat_amount"
                value="{{ calculateCurrencyAmount($transaction->vat_amount ?? 0, 1) }}"
                class="form-control vat-amount text-right only-number" value="" readonly>
        </td>
    </tr>
    {{-- <tr style="visibility: collapse;">
        <td class="text-right borderless" colspan="8">
            Extra Charge <span class="currency-sign"></span>
        </td>
        <td class="borderless">
            <input type="number" name="extra_charge" style="font-size: 14px"
                class="form-control extra-charge text-right input-sm" value="0">
        </td>
    </tr> --}}

    <tr>
        <td class="text-right borderless" colspan="{{ $colspan ?? 11 }}">Grand Total <span class="currency-sign"></span>
        </td>
        <td class="borderless">
            <input name="sub_total" value="{{ calculateCurrencyAmount($transaction->total_amount, 1) }}"
                class="form-control grandtotal text-right" readonly style="padding-right: 5px !important;">
            <input type="hidden" name="line_total" id="line_total">
        </td>

    </tr>

    @if (request()->routeIs('booking-adjusts.create'))
        <tr>
            <td class="text-right borderless" colspan="{{ $colspan ?? 11 }}">Prev. Adv Amount <span
                    class="currency-sign"></span></td>
            <td class="borderless">
                <input type="number" name="previous_advance" value=""
                    class="form-control previous-advance text-right" readonly>
            </td>
        </tr>
    @endif

    <tr>
        <td class="text-right borderless" colspan="{{ $colspan ?? 11 }}">Advanced Amount RM</td>
        <td class="borderless" style="display: none;">
            @if (setting('root_currency') == 116)
            <label class="choose-currency" id="bdtCurrency" style="margin: 0 8px 0 6px;">
                <input type="radio" name="currency_type" value="116"
                {{ setting('root_currency') == 116 ? 'checked' : '' }}>
                SR (৳)
            </label>
            @elseif (setting('root_currency') == 12)
            <label class="choose-currency" id="bdtCurrency" style="margin: 0 8px 0 6px;">
                <input type="radio" name="currency_type" value="12"
                {{ setting('root_currency') == 12 ? 'checked' : '' }}>
                BDT (৳)
            </label>
            @elseif (setting('root_currency') == 96)
            <label class="choose-currency" id="myrCurrency" style="margin: 0 8px 0 6px;">
                <input type="radio" name="currency_type" value="96"
                {{ setting('root_currency') == 96 ? 'checked' : '' }}>
                RM
            </label>
            @endif
            <label class="choose-currency" id="usdCurrency">
                <input type="radio" name="currency_type" value="141"
                {{ setting('root_currency') == 141 ? 'checked' : '' }}>
                USD ($)
            </label>
            <div class="show-currency-rate" style="display: none; color:rgb(230, 92, 115); text-align:center"></div>
        </td>
        <td class="borderless">
            <input type="number" name="advanced_amount"
            value="{{ calculateCurrencyAmount($transaction->collection ?? 0, 1) }}"
            class="form-control adv-amount text-right">
        </td>
    </tr>

    @if (request()->routeIs('booking-adjusts.create'))
        <tr>
            <td class="text-right borderless" colspan="{{ $colspan ?? 11 }}">Prev. Due Amount <span
                    class="currency-sign"></span></td>
            <td class="borderless">
                <input type="number" value="" class="form-control previous-due text-right" readonly>
            </td>
        </tr>
    @endif

    <tr>

        <td class="text-right borderless" colspan="{{ $colspan ?? 11 }}">Due Amount <span class="currency-sign"></span>
        </td>
        <td class="borderless">
            <input type="number" name="due_amount" value="{{ calculateCurrencyAmount($transaction->due_amount, 1) }}"
                class="form-control due-amount text-right" readonly>
        </td>
    </tr>
    <tr>
        <td class="text-right borderless" colspan="{{ $colspan ?? 11 }}">Payment Method</td>
        <td class="borderless">
            <select class="form-control chosen-select payment-type" name="payment_type"
                data-placeholder="-Choose Payment Type-" data-selected="{{ $transaction->account_type_id }}">
                <option></option>
                @foreach ($account_types as $id => $name)
                    <option value="{{ $id }}" {{ old('payment_type') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </td>
    </tr>
    <tr class="card_info" style="display: none">
        <td class="text-right borderless" colspan="{{ $colspan ?? 11 }}">Card Authorized No</td>
        <td class="borderless">
            <input type="text" name="card_info" value=""
                class="form-control text-center" placeholder="XXXX-XXXX-XXX">
        </td>
    </tr>

</tfoot>
