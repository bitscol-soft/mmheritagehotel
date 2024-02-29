<div class="extra-charge">

    <div class="md-modal md-effect close-modal" id="extraChargeModal">

        <div class="md-content" style="border-radius: 4px;">
            <div class="main-body" style="position: relative">

                <div class="down">

                    <!------------- MODAL HEADER ------------>
                    <div class="text-center modal-header">
                        <p style="margin: 0">Add Extra Charge<span class="currency-sign"></span> For <strong
                                class="text-primary bookingNumber"></strong></p>
                    </div>

                    <form action="{{ route('booking.extra-charge') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" id="bookingId" value="">


                        <!------------- MODAL BODY ------------>
                        <div class="modal-body">
                            {{-- <div style="text-align: center; margin: 10px 0">
                                @foreach ($account_types as $account)
                                    <label class="choose-payment-method"
                                        style="margin: 0px 8px 0px 6px !important; cursor:pointer;">
                                        <input type="radio" id="payment_type" class="payment_type{{ $account->id }}"
                                            name="payment_type" value="{{ $account->id }}" required>
                                        <span style="margin-left: 2px">{{ $account->name }}</span>
                                    </label>
                                @endforeach
                            </div> --}}
                            <input name="extra_amount" id="extraAmount" type="text"
                                class="form-control charge-amount" placeholder="Enter Amount...">
                            <textarea name="reason" id="reason" class="form-control charge-reason" cols="30" rows="5"
                                placeholder="Extra Charge Reason"></textarea>
                        </div>


                        <!------------- MODAL FOOTER ------------>
                        <div class="modal-action modal-footer">
                            <div class="hide-modal modal-btn btn-sm btn-outline-danger"
                                onclick="closeExtraChargeModal()">
                                <i class="fas fa-times hide-product-view" aria-hidden="true"></i> Close
                            </div>
                            <button class="modal-btn btn-sm btn-outline-success" type="submit">
                                <i class="fas fa-pen-square"></i> Submit
                            </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
    <div class="md-overlay"></div>
</div>
