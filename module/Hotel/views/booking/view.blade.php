@extends('layouts.master')
@section('title', 'Booking')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        body {
            counter-reset: section;
        }
        .count:before {
            counter-increment: section;
            content: counter(section);
        }
        select:invalid {
            height: 0px !important;
            opacity: 0 !important;
            position: absolute !important;
            display: flex !important;
        }
        select:invalid[multiple] {
            margin-top: 15px !important;
        }
    </style>
@endpush
@section('content')
    @php
        $sub_total = $booking->sub_total;
        $adv_amount = $booking->advanced_payment;
        $vat = optional($booking->getVat)->hotel_vat;
        $vat_amount = ($sub_total / 100) * $vat;
        $total_amount = $sub_total + $vat_amount;
        $current_due = $total_amount - $adv_amount;
    @endphp

    <x-mm.styles />
    <x-mm.page class="mm-booking-checkout" title="Checkout and payment" description="Review charges, adjust nights, apply a discount and collect payment for this booking.">
        <x-slot name="actions">
            <a class="mm-button mm-button-secondary" href="{{ route('booking.index') }}">
                <i class="ace-icon fa fa-list-alt" aria-hidden="true"></i> Booking List
            </a>
        </x-slot>
        <form class="form-horizontal" action="{{ route('booking.checkout', $booking->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
            <x-alert-message />
            @include('booking._inc._booking-context', ['booking' => $booking])

            <div class="mm-co-grid">
                <section class="mm-panel mm-co-card" aria-labelledby="mm-co-guest">
                    <h2 id="mm-co-guest" class="mm-co-title">Guest</h2>
                    <dl class="mm-co-list">
                        <div><dt>Name</dt><dd>{{ optional($booking->guestInfo)->name }}</dd></div>
                        <div><dt>Booking number</dt><dd>{{ $booking->booking_number }}</dd></div>
                        <div><dt>Mobile</dt><dd>{{ optional($booking->guestInfo)->phone_no }}</dd></div>
                    </dl>
                </section>

                <section class="mm-panel mm-co-card" aria-labelledby="mm-co-stay">
                    <h2 id="mm-co-stay" class="mm-co-title">Stay</h2>
                    <dl class="mm-co-list">
                        <div><dt>Check in</dt><dd>{{ $booking->check_in_date }}</dd></div>
                    </dl>
                    <div class="mm-co-field">
                        <label for="id-date-picker-1">Check out date</label>
                        <input class="form-control date-picker" name="check_out_date"
                                                    id="id-date-picker-1" type="text"
                                                    value="{{ $booking->check_out_date }}" data-date-format="dd-mm-yyyy"
                                                    placeholder="Checkout Date" autocomplete="off">
                    </div>
                </section>

                <section class="mm-panel mm-co-card" aria-labelledby="mm-co-method">
                    <h2 id="mm-co-method" class="mm-co-title">Payment method</h2>
                    <div class="mm-co-field" role="group" aria-labelledby="mm-co-paytype">
                        <span id="mm-co-paytype" class="mm-co-label">Payment type</span>
                        <select class="form-control chosen-select" name="payment_type"
                                                data-placeholder="-Choose Payment Type-">
                                                <option></option>
                                                @foreach ($account_type as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ old('payment_type') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                    </div>
                    <div class="mm-co-field" role="group" aria-labelledby="mm-co-payby">
                        <span id="mm-co-payby" class="mm-co-label">Payment by</span>
                        <select class="form-control chosen-select" name="pay_by"
                                                data-placeholder="-Choose Member-">
                                                <option></option>
                                                {{-- @dd($booking); --}}
                                                @foreach ($booking->booking_members as $member)
                                                    <option value="{{ $member->id }}"
                                                        {{ old('pay_by') == $member->id ? 'selected' : '' }}>
                                                        {{ $member->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                    </div>
                </section>
            </div>

            <section class="mm-panel mm-co-charges" aria-labelledby="mm-co-charges">
                <h2 id="mm-co-charges" class="mm-co-title">Charges</h2>
                <x-mm.table-scroll label="Booking charges">
                    <table class="table table-bordered table-striped table-hover guest-detail-table">
                                            <thead>
                                                <tr>
                                                    <th width="5%" class="text-center">SL</th>
                                                    <th>Invoice</th>
                                                    <th style="width: 30%">Service</th>
                                                    <th style="width: 8%">Total Night</th>
                                                    <th class="text-right">Amount</th>
                                                    <th class="text-right">Service Charge</th>
                                                    <th class="text-right">Vat</th>
                                                    <th class="text-right">Extra Charge</th>
                                                    <th class="text-right">Total</th>
                                                    <th class="text-right">Paid</th>
                                                    <th class="text-right">Due Amount</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $net_collection = $transactions->sum('collection');
                                                $service_charge = $due_amount = 0;
                                            @endphp
                                            <tbody>
                                                @foreach ($transactions as $transaction)
                                                    @php
                                                        $service_charge += $transaction->service_amount;
                                                        $due_amount += $transaction->due_amount;
                                                        $old_dis = $transaction->discount;
                                                    @endphp
                                                    <input type="hidden" name="old_discount"
                                                        value="{{ $old_dis }}">
                                                    <tr>
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <input type="hidden" name="id[]"
                                                                value="{{ $transaction->id }}">
                                                            <input type="hidden" name="item_ids[]"
                                                                value="{{ $transaction->source_id }}">
                                                            <input type="hidden" name="item_types[]"
                                                                value="{{ $transaction->source_type }}">
                                                            <input type="hidden" name="total_amount[]"
                                                                class="input-total-amount"
                                                                value="{{ $transaction->total_amount }}">
                                                            <input type="hidden" name="item_amount[]"
                                                                class="input-due-amount"
                                                                value="{{ $transaction->due_amount }}">
                                                            <input type="hidden" name="service_charge[]"
                                                                class="input-service-amount"
                                                                value="{{ $transaction->service_charge }}">
                                                            <input type="hidden" name="vat_amount[]"
                                                                class="input-vat-amount"
                                                                value="{{ $transaction->vat_amount }}">

                                                            <input type="hidden" class="extra-charge[]"
                                                                name="extra-charge"
                                                                value="{{ $transaction->extra_charge }}">
                                                            {{ $transaction->invoice_no }}
                                                        </td>
                                                        <td>
                                                            <p style="font-weight: bold">{{ $transaction->source_type }}
                                                            </p>
                                                            @if ($transaction->source_type == 'Booking')
                                                                @foreach (optional($transaction->source)->details ?? [] as $item)
                                                                    {{ $item->roomNumber->room_number }} @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($transaction->source_type == 'Booking')
                                                                <input type="hidden" class="per-night-amount"
                                                                    value="{{ ($transaction->total_amount - $transaction->extra_charge) / $total_night }}">
                                                                <input type="hidden"
                                                                    class="per-night-amount-without-vat-service"
                                                                    value="{{ ($transaction->total_amount - ($transaction->vat_amount + $transaction->service_charge + $transaction->extra_charge)) / $total_night }}">
                                                                <input type="hidden" class="per-night-amount-without-vat"
                                                                    value="{{ ($transaction->total_amount - ($transaction->vat_amount + $transaction->extra_charge)) / $total_night }}">
                                                                <input type="hidden" class="extra-charge"
                                                                    name="extra-charge"
                                                                    value="{{ $transaction->extra_charge }}">

                                                                @if (optional($transaction->booking)->bookingAdjusts->count() > 0)
                                                                    <input type="hidden" name="is_adjust"
                                                                        value="1">
                                                                    <input type="text" name="night_count"
                                                                        class="form-control only-number night-count input-sm"
                                                                        value="{{ $total_night }}" style="height: 22px"
                                                                        readonly>
                                                                @else
                                                                    <div class="input-group">
                                                                        <div class="input-group-btn">
                                                                            <button class="btn btn-minier btn-danger"
                                                                                type="button" onclick="decrease(this)">
                                                                                <i class="fa fa-minus"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input type="text" name="night_count"
                                                                            class="form-control only-number night-count input-sm"
                                                                            value="{{ $total_night }}"
                                                                            style="height: 22px" readonly>
                                                                        <div class="input-group-btn">
                                                                            <button class="btn btn-minier btn-success"
                                                                                type="button" onclick="increase(this)">
                                                                                <i class="fa fa-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                            <input type="hidden" class="total-collection"
                                                                value="{{ $transaction->collection }}">
                                                        </td>
                                                        <td class="text-right amount">
                                                            {{ number_format($transaction->total_amount - $transaction->vat_amount - $transaction->service_charge - $transaction->extra_charge, 2) }}
                                                        </td>
                                                        <td class="text-right service-charge">
                                                            {{ number_format($transaction->service_charge, 2) }}</td>
                                                        <td class="text-right vat-amount">
                                                            {{ number_format($transaction->vat_amount, 2) }}</td>
                                                        @if ($transaction->source_type == 'Booking')
                                                            <td class="text-right">
                                                                {{ number_format($transaction->extra_charge ?? 0, 2) }}
                                                            </td>
                                                        @else
                                                            <td class="text-right"> {{ 'N/A' }}</td>
                                                        @endif
                                                        <td class="text-right total-amount">
                                                            {{ number_format($transaction->total_amount, 2) }}</td>
                                                        <td class="text-right">
                                                            {{ number_format($transaction->collection, 2) }}</td>
                                                        <td class="text-right due-amount">
                                                            {{ number_format($transaction->due_amount > 0 ? $transaction->due_amount : 0, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                            <input type="hidden" value="{{ $transactions->sum('total_amount') }}"
                                                name="payble_amount">
                                        </table>
                </x-mm.table-scroll>
            </section>

            <section class="mm-panel mm-co-summary" aria-labelledby="mm-co-summary">
                <h2 id="mm-co-summary" class="mm-co-title">Payment summary</h2>
                <div class="mm-sum-row"><span class="mm-sum-label">Sub Total</span>
                    <p class="font-18 bold grand-subtotal">
                        {{ number_format($transactions->sum('total_amount'), 2) }}
                    </p></div>
                <div class="mm-sum-row"><span class="mm-sum-label">Service Charge</span>
                    <p class="font-18 bold grand-service-charge">
                        {{ number_format($transactions->sum('service_charge'), 2) }}
                    </p></div>
                <div class="mm-sum-row"><span class="mm-sum-label">Extra Charge</span>
                    <p class="font-18 bold grand-extra-charge">
                        {{ count($transactions) > 0 ? number_format($transactions[0]->extra_charge, 2) : 0 }}
                    </p></div>
                <div class="mm-sum-row"><span class="mm-sum-label">Vat Amount</span>
                    <p class="font-18 bold grand-vat-amount">
                        {{ number_format($transactions->sum('vat_amount'), 2) }}
                    </p></div>
                <div class="mm-sum-row is-strong"><span class="mm-sum-label">Total Amount</span>
                    <p class="font-18 bold grand-total-amount">
                        {{ number_format($transactions->sum('total_amount'), 2) }}
                    </p></div>
                <div class="mm-sum-row"><span class="mm-sum-label">Advanced Paid</span>
                    <p class="font-18 bold ">
                        {{ number_format($transactions->sum('collection') - $transactions->sum('change_amount'), 2 ?? 0) }}
                    </p></div>
                <div class="mm-sum-row is-strong"><span class="mm-sum-label">Total Payable</span>
                    <p class="font-18 bold payable-amount">
                        {{ number_format($current_due_amount = $transactions->sum('due_amount') + $transactions->sum('change_amount'), 2 ?? 0) }}
                    </p></div>
                <div class="mm-sum-row"><label class="mm-sum-label" for="discount">Discount</label>
                    <p class="font-18 bold">
                        <input name="discount" id="discount"
                                                                        onkeyup="calculateAmounts()"
                                                                        class="discount only-number input-sm text-right font-18 bold"
                                                                        type="text" min="0" step="any"
                                                                        value="0">
                    </p></div>
                <div class="mm-sum-row"><label class="mm-sum-label" for="paidAmount">Paid Amount</label>
                    <p class="font-18 bold">
                        <input name="paid_amount" id="paidAmount"
                                                                        onkeyup="calculateAmounts()"
                                                                        class="paid-amount only-number input-sm text-right font-18 bold"
                                                                        type="text" min="0" step="any"
                                                                        value="0">
                    </p></div>
                <div class="mm-sum-row is-due"><span class="mm-sum-label">Current Due</span>
                    <input id="get-due" type="hidden"
                                                                    onkeyup="calculateAmounts()"
                                                                    value="{{ $current_due_amount }}">
                    <p class="current-due font-18 bold">
                        {{ number_format($current_due_amount, 2) }}</p></div>
                <div class="mm-sum-full">
                    <input id="check-full-payment" type="checkbox">
                    <label for="check-full-payment"><span>Full
                            Payment</span></label>
                </div>
                <div class="mm-form-actions">
                    <button class="mm-button" type="submit">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        Payment &amp; Checkout
                    </button>
                </div>
            </section>
        </form>
    </x-mm.page>
@endsection

@section('js')

    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <script>
        function calculateAmounts() {
            var currentDue = parseFloat(document.getElementById('get-due').value);
            var discount = parseFloat(document.getElementById('discount').value);
            var paidAmount = parseFloat(document.getElementById('paidAmount').value);

            var payableAmount = currentDue - discount;
            document.querySelector('.payable-amount').textContent = payableAmount.toFixed(2);

            var newDue = payableAmount - paidAmount;
            document.querySelector('.current-due').textContent = newDue.toFixed(2);

            if (discount > currentDue) {
                warning('toster', 'You can not discount more.')
                discount = currentDue
                $('.discount').val(currentDue)
            }
        }
    </script>

    <script>
        const room_service_amount = `{{ vatSetting()->room_service_charge }}`
        const hotel_vat_percentage = `{{ vatSetting()->hotel_vat }}`

        $(document).on("input", ".discount", function() {
            calculateDiscount()
        });

        $(document).on("input", ".paid-amount", function() {
            calculatePayment()
        });

        $(document).on("click", "#check-full-payment", function() {
            let get_due = $('#get-due').val()
            if ($('#check-full-payment').is(':checked')) {
                $('.paid-amount').val(get_due);
                $('.current-due').html(0);
                $('.discount').val(0);
                $('.discount').prop('readonly', true);
            } else {
                $('.paid-amount').val(0);
                $('.current-due').html(get_due);
                $('.discount').prop('readonly', false);

            }

        });

        function calculateDiscount() {
            let total_due = 0
            let current_due = Number($('#get-due').val())

            let get_discount = Number($('.discount').val())

            if (get_discount > current_due) {
                warning('toster', 'You can not discount more.')
                get_discount = current_due
                $('.discount').val(current_due)
            }
            total_due = current_due - get_discount

            $('.current-due').html(total_due);
            $('.paid-amount').val(total_due)
        }

        function calculatePayment() {
            let total_payment = 0
            let current_due = $('#get-due').val()
            let payment = $('.paid-amount').val()
            total_payment = current_due - payment
            $('.current-due').html(total_payment);
        }

        increase = (e) => {
            let _this = $(e);
            let night = Number(_this.closest('tr').find('.night-count').val());
            night = night + 1
            _this.closest('tr').find('.night-count').val(night)
            calculateNightWiseAmount(e);
        }
        decrease = (e) => {
            let _this = $(e);
            let night = Number(_this.closest('tr').find('.night-count').val());
            if (night <= 1) {
                return;
            }
            night = night - 1
            _this.closest('tr').find('.night-count').val(night)
            calculateNightWiseAmount(e);
        }

        function calculateNightWiseAmount(e) {
            let night = Number($(e).closest('tr').find('.night-count').val())
            let amount = Number($(e).closest('tr').find('.per-night-amount').val())
            let line_wise_amount = night * amount;
            calculateAmount()
        }

        calculateAmount = (e) => {
            let grand_total_amount = 0
            let grand_total_service_amount = 0
            let grand_total_vat_amount = 0
            let grand_total_due_amount = 0

            $('.night-count').map(function() {
                let per_night_amount = Number($(this).closest('tr').find(
                    '.per-night-amount-without-vat-service').val()) * Number($(this).val())
                let per_night_vat_amount = Number($(this).closest('tr').find('.per-night-amount-without-vat')
                    .val()) * Number($(this).val())

                let service_amount = (per_night_amount / 100) * (Number(room_service_amount))
                // let vat_amount        = (per_night_amount / 100 ) * (Number(hotel_vat_percentage))
                let vat_amount = (per_night_vat_amount / 100) * (Number(hotel_vat_percentage))

                let extra_charge = Number($('.extra-charge').val());

                let total_calc_amount = per_night_amount + service_amount + vat_amount + extra_charge;
                console.log(
                    'per_night_amount => ' + per_night_amount,
                    'service_amount => ' + service_amount,
                    'vat_amount => ' + vat_amount,
                    'extra_charge => ' + extra_charge,
                    'total_calc_amount : ' + total_calc_amount
                );
                let collected_amount = Number($(this).closest('tr').find('.total-collection').val())
                let total_due_amount = total_calc_amount - collected_amount

                grand_total_service_amount += service_amount
                grand_total_vat_amount += vat_amount
                grand_total_amount += total_calc_amount
                grand_total_due_amount += total_due_amount

                $(this).closest('tr').find('.amount').text(per_night_amount)
                $(this).closest('tr').find('.vat-amount').text(vat_amount.toFixed(2))
                $(this).closest('tr').find('.service-charge').text(service_amount)
                $(this).closest('tr').find('.total-amount').text(total_calc_amount)
                $(this).closest('tr').find('.due-amount').text(total_due_amount)

                //only for hidden input
                $(this).closest('tr').find('.input-total-amount').val(total_calc_amount)
                $(this).closest('tr').find('.input-due-amount').val(total_due_amount)
                $(this).closest('tr').find('.input-service-amount').val(service_amount)
                $(this).closest('tr').find('.input-vat-amount').val(vat_amount.toFixed(2))

            })

            $('.grand-subtotal').text(grand_total_amount);
            $('.grand-service-charge').text(grand_total_service_amount);
            $('.grand-vat-amount').text(grand_total_vat_amount.toFixed(2));
            $('.grand-total-amount').text(grand_total_amount);
            $('.payable-amount, .current-due').text(grand_total_due_amount);
            $('#get-due').val(grand_total_due_amount);

        }
    </script>
@stop
