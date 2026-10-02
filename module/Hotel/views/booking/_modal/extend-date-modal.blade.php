<div class="extra-charge">

    <div class="md-modal md-effect close-modal" id="extendDateModal{{ $data->id }}" style="max-width: 800px; min-width: 700px">

        <div class="md-content" style="border-radius: 4px;">
            <div class="main-body" style="position: relative">

                <div class="down">

                    <!------------- MODAL HEADER ------------>
                    <div class="text-center modal-header">
                        <p style="margin: 0">Extend Checkout Date For <strong class="text-primary bookingNumber">{{ $data->booking_number }}</strong></p>
                    </div>

                    <form action="{{ route('booking.update', $data->id) }}" method="POST" id="extendCheckOutDateForm{{ $data->id }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="emergency_cont_name"                        value="{{ $data->emergency_cont_name }}" >
                        <input type="hidden" name="emergency_cont_phone"                       value="{{ $data->emergency_cont_phone }}" >
                        <input type="hidden" name="booking_pax"                                value="{{ $data->booking_pax }}" >
                        <input type="hidden" name="customer_id"                                value="{{ $data->customer_id }}" >
                        <input type="hidden" name="purpose"                                    value="{{ $data->purpose }}" >
                        <input type="hidden" name="booking_id"                                 value="{{ $data->id }}" class="booking-id{{ $data->id }}" >
                        <input type="hidden" name="is_from_booking_edit"                       value="1">
                        <input type="hidden" class="form-control check-in-date{{ $data->id }}" value="{{ $data->check_in_date }}"  data-date-format="dd-mm-yyyy" readonly>
                        <input type="hidden" class="previousCheckoutDate{{ $data->id }}"       value="{{ $data->check_out_date }}">
                        <input type="hidden" class="isRoomAvailable{{ $data->id }}"            value="0">

                        <!------------- MODAL BODY ------------>
                        <div class="modal-body" style="padding: 45px">


                            <div class="d-flex" style="display: flex; justify-content: space-between">

                                <!-- BOOKING ROOM -->
                                <div class="form-group mb-1" style="width: 48%; display: flex; align-items: center;">
                                    <div class="input-group">
                                        <span class="border-none input-group-addon" style="text-align: left;">Check In Date<span style="color: red">*</span></span>
                                        <input class="form-control check-in-date{{ $data->id }}" value="{{ $data->check_in_date }}" name="check_in" type="text" data-date-format="dd-mm-yyyy" readonly>
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>


                                <!-- CHECK OUT DATE -->
                                <div class="form-group mb-1" style="width: 48%; display: flex; align-items: center;">
                                    <div class="input-group">
                                        <span class="border-none input-group-addon" style="text-align: left;">Check Out Date<span style="color: red">*</span></span>
                                        <input class="form-control date-picker check-out-date check-out-date{{ $data->id }}" onchange="changeCheckoutDate({{ $data->id }})" value="" name="check_out" type="text" data-date-format="dd-mm-yyyy">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar bigger-110"></i>
                                        </span>
                                    </div>
                                </div>

                            </div>

                            <div class="row" style="background: #f4f4f4; margin: 0; margin-top: 10px;">
                                <div class="col-lg-6" style="font-size: 14px; font-weight: bold; padding: 0">
                                    <input class="form-control" type="text" value="Room Category" readonly>
                                </div>
                                <div class="col-lg-2" style="font-size: 14px; font-weight: bold; padding: 0;">
                                    <input class="form-control" type="text" value="Room" readonly>
                                </div>
                                <div class="col-lg-2" style="font-size: 14px; font-weight: bold; padding: 0;">
                                    <input class="form-control" type="text" value="Night" readonly>
                                </div>
                                <div class="col-lg-2" style="font-size: 14px; font-weight: bold; text-align:right; padding: 0">
                                    <input class="form-control" type="text" value="T. Amount" readonly>
                                </div>
                            </div>

                            @php
                                $grand_total = 0;
                            @endphp

                            <div class="row room-details{{ $data->id }}" style="margin: 0">
                                @foreach ($data->bookingDetails as $key => $detail)

                                    @php
                                    $grand_total += calculateCurrencyAmount($detail->total_amount,1);
                                    $isMigratedRoom = $detail->bookingAdjust != null && ($detail->room_id == $detail->bookingAdjust->from_room_id) ? 1 : 0;
                                    $isCurrentRoom = $detail->bookingAdjust != null && ($detail->room_id == $detail->bookingAdjust->to_room_id) ? 1 : 0;
                                    @endphp

                                    <input type="hidden" class="room_count{{ $data->id }}" data-current-room="{{ $isCurrentRoom }}" name="" id="">

                                    {{-- ROOM CATEGORY --}}
                                    <div class="tr{{ $data->id }} col-lg-6" style="padding: 0">
                                        <select name="room_category[]" class="form-control apprearence-none" data-placeholder="--Choose Category--">
                                            <option value="{{ $detail->category_id }}">{{ optional($detail->roomNumber)->name }}</option>
                                        </select>
                                    </div>


                                    {{-- ROOM NUMBER --}}
                                    <div class="tr{{ $data->id }} col-lg-2" style="padding: 0;">
                                        <select name="room_number[]" class="form-control apprearence-none room_number{{ $data->id }}" data-placeholder="--Choose Room--">
                                            <option value="{{ $detail->room_id }}">{{ optional($detail->roomNumber)->room_number }}</option>
                                        </select>
                                    </div>

                                    {{-- NIGHT COUNT --}}
                                    <div class="tr{{ $data->id }} col-lg-2 total-night-count" style="padding: 0;">
                                        <input type="text" class="form-control night_count{{ $data->id }}" name="night[]" value="{{ $detail->night_count }}" readonly>
                                        {{-- <input type="text" class="form-control {{ $isMigratedRoom == 0 ? 'night_count'.$data->id : '' }}" name="night[]" value="{{ $detail->night_count }}" readonly> --}}
                                    </div>


                                    {{-- TOTAL AMOUNT --}}
                                    <div class="tr{{ $data->id }} col-lg-2 total-room-amount" style="padding: 0;">

                                        {{-- <input type="text" name="amount[]" style="font-size: 16px; height: 34px;" class="form-control input-sm only-number net-amount{{ $data->id }}" value="{{ calculateCurrencyAmount($detail->total_amount,1) }}" readonly>
                                        <input type="hidden" value="{{ (int) $detail->night_count > 0 ? calculateCurrencyAmount($detail->total_amount, 1) / $detail->night_count : calculateCurrencyAmount($detail->total_amount, 1) }}" class="net-amount-hidden{{ $data->id }}">
                                        <input type="hidden" name="room_services[]" class="room-wise-service-charge{{ $data->id }}" value="{{ $detail->service_charge }}"> --}}
                                        <input type="text" name="amount[]" style="font-size: 16px; height: 34px;" class="form-control input-sm only-number net-amount{{ $data->id }}{{ $key }}" value="{{ calculateCurrencyAmount($detail->total_amount,1) }}" readonly>
                                        <input type="hidden" value="{{ (int) $detail->night_count > 0 ? calculateCurrencyAmount($detail->total_amount, 1) / $detail->night_count : calculateCurrencyAmount($detail->total_amount, 1) }}" class="net-amount-hidden{{ $data->id }}{{ $key }}">
                                        <input type="hidden" name="room_services[]" class="room-wise-service-charge{{ $data->id }}{{ $key }}" value="{{ $detail->service_charge }}">

                                    </div>


                                    {{-- GUEST --}}
                                    <div style="tr{{ $data->id }} visibility: hidden; width:0%; height: 0px">
                                        <select name="guest[]" class="form-control text-center guest-number input-guest" data-placeholder="--Select Guest--" style="visibility: hidden; width:0%; height: 0px">
                                            <option value="{{ $detail->guest_count }}">
                                                    {{ $detail->guest_count }}
                                            </option>
                                        </select>
                                    </div>


                                    {{-- ROOM PRICE --}}
                                    <div style="tr{{ $data->id }} visibility: hidden; width:0%; height: 0px">
                                        <input type="hidden" name="room_price[]" value="{{  (int) $detail->night_count > 0 ? calculateCurrencyAmount($detail->total_amount, 1) / $detail->night_count : calculateCurrencyAmount($detail->total_amount,1) }}" class="form-control amount{{ $data->id }} only-number text-right" style="visibility: hidden; width:0%; height: 0px">
                                    </div>


                                    {{-- INFANT COUNT --}}
                                    <div style="tr{{ $data->id }} visibility: hidden; width:0%; height: 0px">
                                        <input type="hidden" name="infant[]" value="{{ $detail->infant_count }}" class="form-control text-right input-infant{{ $data->id }}" min="0" style="visibility: hidden; width:0%; height: 0px">
                                    </div>


                                    {{-- DISCOUNT --}}
                                    <div style="tr{{ $data->id }} visibility: hidden; width:0%; height: 0px">
                                        <input type="hidden" name="discount[]" value="{{ calculateCurrencyAmount($detail->discount_amount,1) }}" data-room-discount="{{ calculateCurrencyAmount($detail->room_discount,1) }}" class="form-control text-right discount{{ $data->id }}{{ $key }}" min="0" style="visibility: hidden; width:0%; height: 0px">
                                    </div>


                                    {{-- ALLOW BREAKFAST --}}
                                    <div style="tr{{ $data->id }} visibility: hidden; width:0%; height: 0px">
                                        <label style="visibility: hidden; width:0%; height: 0px">
                                            <input name="allow_breakfast[]" class="ace ace-switch ace-switch-6" value="{{ $detail->discount_amount == 1 ? 1 : 0}}" type="checkbox" {{ $detail->discount_amount == 1 ? 'checked' : ''}}>
                                            <span class="lbl"></span>
                                        </label>
                                    </div>

                                @endforeach
                            </div>

                            <div style="position: relative; visibility:hidden; height: 0px; width: 0%">
                                @php
                                    $subtotal    = $grand_total;
                                    $transaction = optional($data->transection);

                                @endphp
                                <div>
                                    <input class="form-control subtotal-amount text-right" value="{{ calculateCurrencyAmount($subtotal ?? 0, 1) }}" readonly style="padding-right: 5px;">
                                    <input type="hidden" name="line_total" id="line_total{{ $data->id }}">
                                </div>
                                <div>
                                    <input name="service_amount" value="{{ calculateCurrencyAmount(floor($transaction->service_charge), 1) }}" class="form-control service_amount{{ $data->id }} text-right" readonly style="padding-right: 5px;">
                                </div>
                                <div>
                                    <input type="hidden" name="vat" id="vat{{ $data->id }}" value="{{ vatSetting()->hotel_vat }}">
                                    <input type="text" name="vat_amount" value="{{ calculateCurrencyAmount($transaction->vat_amount,1) }}" class="form-control vat-amount{{ $data->id }} text-right only-number" value="" readonly>
                                </div>
                                <div>
                                    <input name="sub_total" value="{{ calculateCurrencyAmount($transaction->total_amount,1) }}" class="form-control grandtotal{{ $data->id }} text-right" readonly style="padding-right: 5px !important;">
                                    <input type="hidden" name="line_total" id="line_total{{ $data->id }}">
                                </div>

                                <div>
                                    <label class="choose-currency" id="bdtCurrency" style="margin: 0 8px 0 6px;">
                                        <input type="radio" name="currency_type" value="12"
                                            {{ setting('root_currency') == 96 ? 'checked' : '' }} >
                                            RM
                                    </label>

                                    <label class="choose-currency" id="usdCurrency">
                                        <input type="radio" name="currency_type" value="141"
                                            {{ setting('root_currency') == 141 ? 'checked' : '' }} >
                                            USD ($)
                                    </label>

                                    <input type="number" name="advanced_amount" value="{{ calculateCurrencyAmount($transaction->collection ?? 0, 1) }}" class="form-control adv-amount{{ $data->id }} text-right">
                                </div>

                                <div>
                                    <input type="number" name="due_amount" value="{{ calculateCurrencyAmount($transaction->due_amount,1) }}" class="form-control due-amount{{ $data->id }} text-right" readonly>
                                </div>
                                <div>
                                    <select class="form-control chosen-select payment-type" name="payment_type" data-placeholder="-Choose Payment Type-">
                                        <option value="{{ $transaction->account_type_id }}">{{ $transaction->account_type_id }}</option>
                                    </select>
                                </div>

                            </div>

                        </div>

                    </form>

                    <!------------- MODAL FOOTER ------------>
                    <div class="modal-action modal-footer">
                        <div class="hide-modal modal-btn btn-sm btn-outline-danger" onclick="closeExtendDateModal()">
                            <i class="fa fa-times hide-product-view" aria-hidden="true"></i> Close
                        </div>
                        <button class="modal-btn btn-sm btn-outline-success" type="button" onclick="submitExtendCheckOutDateForm({{ $data->id }})">
                            <i class="fa fa-pencil-square"></i> Submit
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="md-overlay"></div>
</div>
