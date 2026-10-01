@extends('layouts.master')
@section('title', 'Payment Collection')

@section('page-header')
    <i class="fa fa-info-circle"></i> Payment Collection
@stop

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

    <x-mm.styles />
    <x-mm.page class="mm-payment-collection mm-rst" title="Restaurant payment collection" description="Find a guest, review unpaid invoices and collect a payment.">
        <x-alert-message />

        <x-mm.panel class="mm-co-card">
            <h2 class="mm-co-title">Find unpaid invoices</h2>
            <form>
                <div class="mm-pc-search">
                    <div class="mm-co-field" role="group" aria-labelledby="mm-pc-guest">
                        <span id="mm-pc-guest" class="mm-co-label">Guest</span>
                        <select class="form-control chosen-select" name="hotel_guest_id" data-placeholder="- Choose Guest -">
                                <option></option>
                                @foreach ($hotelGuests as $guest)
                                    <option value="{{ $guest->id }}" {{ old('hotel_guest_id') == $guest->id || request('hotel_guest_id') == $guest->id ? 'selected' : '' }}>
                                        {{ $guest->name }} ({{ $guest->phone_no }})
                                    </option>
                                @endforeach
                            </select>
                    </div>
                    <div class="mm-pc-search-actions">
                        <button class="mm-button" type="submit">
                            <i class="fa fa-search" aria-hidden="true"></i> Search
                        </button>
                        <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Reset search">
                            <i class="fa fa-refresh" aria-hidden="true"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </x-mm.panel>

        @if ($transactions != null && count($transactions) > 0)
            <section class="mm-panel mm-co-card" aria-labelledby="mm-pc-info">
                <h2 id="mm-pc-info" class="mm-co-title">Guest information</h2>
                <dl class="mm-co-list mm-pc-info">
                        <div><dt>Name</dt><dd>{{ optional($hotelGuest)->name ?? 'N\A' }}</dd></div>
                        <div><dt>Email</dt><dd>{{ optional($hotelGuest)->email ?? 'N\A' }}</dd></div>
                        <div><dt>Mobile number</dt><dd>{{ optional($hotelGuest)->phone_no ?? 'N\A' }}</dd></div>
                        <div><dt>NID</dt><dd>{{ optional($hotelGuest)->nid_no ?? 'N\A' }}</dd></div>
                        <div><dt>Booking number</dt><dd>{{ optional(optional($hotelGuest->booking)->bookingInfo)->booking_number ?? 'N\A' }}</dd></div>
                        <div><dt>Address</dt><dd>{{ optional($hotelGuest)->address ?? 'N\A' }}</dd></div>
                </dl>
            </section>
        @endif

        {{-- GUEST TRANSACTIONS --}}
        <form class="form-horizontal" action="{{ route('rst.sales.store-payment-collection') }}" method="post" enctype="multipart/form-data">
            @csrf

            @if ($transactions != null && count($transactions) > 0)

                <input type="hidden" name="hotel_guest_id" value="{{ request('hotel_guest_id') }}">
                                <input type="hidden" name="is_from_due_collection" value="1">

                <section class="mm-panel mm-co-charges" aria-labelledby="mm-co-charges">
                    <h2 id="mm-co-charges" class="mm-co-title">Unpaid invoices</h2>
                    <x-mm.table-scroll label="Unpaid invoices">
<table class="table table-bordered table-striped table-hover guest-detail-table">
                                                <thead>
                                                    <tr>
                                                        <th width="5%" class="text-center">SL</th>
                                                        <th width="10%" class="text-center">Invoice No</th>
                                                        <th width="10%" class="text-center">Date</th>
                                                        <th class="text-right">Service Charge</th>
                                                        <th class="text-right">Vat Amount</th>
                                                        <th class="text-right">Discount</th>
                                                        <th class="text-right">Total</th>
                                                        <th class="text-right">Advance Paid</th>
                                                        <th class="text-right">Due Amount</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $net_collection = $transactions->sum('collection');
                                                    $service_charge = $due_amount = 0;
                                                @endphp
                                                <tbody>
                                                    @foreach ($transactions ?? [] as $transaction)
                                                        @php
                                                            $service_charge += $transaction->service_amount;
                                                            $due_amount     += $transaction->due_amount;
                                                        @endphp
                                                        <tr>
                                                            <!-- INDEX NO -->
                                                            <td class="text-center"> {{ $loop->iteration }}

                                                                <input type="hidden" name="item_ids[]" value="{{ $transaction->source_id }}">
                                                                <input type="hidden" name="item_types[]" value="{{ $transaction->source_type }}">
                                                                <input type="hidden" name="total_amount[]" class="input-total-amount" value="{{ $transaction->total_amount }}">
                                                                <input type="hidden" name="item_amount[]" class="input-due-amount" value="{{ $transaction->due_amount }}">
                                                                <input type="hidden" name="service_charge[]" class="input-service-amount" value="{{ optional($transaction->source)->service_amount }}">
                                                                <input type="hidden" name="vat_amount[]" class="input-vat-amount" value="{{ optional($transaction->source)->vat_amount }}">
                                                                <input type="hidden" class="total-collection" value="{{ $transaction->collection }}">
                                                                <input type="hidden" name="invoice_no[]" value="{{ $transaction->invoice_no }}">
                                                                <input type="hidden" name="discount[]" value="{{ $transaction->discount }}">
                                                                <input type="hidden" name="date[]" value="{{ optional($transaction->source)->date }}">
                                                                <input type="hidden" name="created_by[]" value="{{ optional($transaction->source)->created_by }}">
                                                                <input type="hidden" name="company_id[]" value="{{ optional($transaction->source)->company_id }}">

                                                            </td>

                                                            <!-- INVOICE NO -->
                                                            <td class="text-center"><strong>{{ $transaction->invoice_no }}</strong></td>

                                                            <!-- SALE DATE -->
                                                            <td class="text-center">{{ optional($transaction->source)->date }}</td>

                                                            <!-- SERVICE AMOUNT -->
                                                            <td class="text-right service-charge">{{ number_format(optional($transaction->source)->service_amount, 2) }}</td>

                                                            <!-- VAT AMOUNT -->
                                                            <td class="text-right vat-amount">{{ number_format(optional($transaction->source)->vat_amount, 2) }}</td>

                                                            <!-- DISCOUNT -->
                                                            <td class="text-right">{{ number_format(optional($transaction->source)->discount, 2) }}</td>

                                                            <!-- TOTAL AMOUNT -->
                                                            <td class="text-right total-amount">{{ number_format(optional($transaction)->total_amount, 2) }}</td>

                                                            <!-- COLLECTION -->
                                                            <td class="text-right">
                                                                {{ number_format($transaction->collection, 2) }}
                                                                <input type="hidden" name="previous_collection[]" value="{{ $transaction->collection }}">
                                                            </td>

                                                            <!-- DUE AMOUNT -->
                                                            <td class="text-right due-amount"><strong>{{ number_format($transaction->due_amount > 0 ? $transaction->due_amount : 0, 2) }}</strong></td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                    </x-mm.table-scroll>
                </section>

                <section class="mm-panel mm-co-summary" aria-labelledby="mm-co-summary">
                    <h2 id="mm-co-summary" class="mm-co-title">Payment summary</h2>
                    <div class="mm-sum-row is-strong"><span class="mm-sum-label">Total Payable</span>
                        <p class="font-18 bold payable-amount">{{ number_format($current_due_amount = $transactions->sum('due_amount') + $transactions->sum('change_amount'), 2 ?? 0) }}</p></div>
                    <div class="mm-sum-row" hidden><span class="mm-sum-label">Discount</span>
                        <p class="font-18 bold">
                            <input class="discount only-number input-sm text-right font-18 bold" type="text" min="0" step="any" value="0">
                        </p></div>
                    <label class="mm-sum-row"><span class="mm-sum-label">Paid Amount</span>
                        <span class="font-18 bold">
                            <input name="total_paid_amount" class="paid-amount only-number input-sm text-right font-18 bold" type="text" min="0" step="any" value="0">
                        </span></label>
                    <div class="mm-sum-row is-due"><span class="mm-sum-label">Current Due</span>
                        <input id="get-due" name="total_due_amount" type="hidden" value="{{ $current_due_amount }}">
                        <p class="current-due font-18 bold">{{ number_format($current_due_amount, 2) }}</p></div>
                    <div class="mm-co-field" role="group" aria-labelledby="mm-pc-paytype">
                        <span id="mm-pc-paytype" class="mm-co-label">Payment type</span>
                        <select class="form-control chosen-select" name="payment_type"
                            data-placeholder="- Choose Type -">
                            <option></option>
                            @foreach ($account_type as $id => $name)
                                <option value="{{ $id }}"
                                    {{ old('payment_type') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mm-sum-full">
                        <input id="check-full-payment" type="checkbox">
                        <label for="check-full-payment"><span>Full Payment</span></label>
                    </div>
                    <div class="mm-form-actions">
                        <button class="mm-button" type="submit">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            Payment
                        </button>
                    </div>
                </section>
            @else
                <x-mm.panel class="mm-co-card">
                    <p class="mm-pc-empty" role="status">No unpaid invoices found. Choose a guest or company above and search.</p>
                </x-mm.panel>
            @endif

        </form>
    </x-mm.page>

@endsection

@section('js')

    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <script>

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
            let total_due       = 0
            let current_due     = Number($('#get-due').val())
            let get_discount    = Number($('.discount').val())
            let paidAmount      = Number($('.paid-amount').val())

            if (get_discount > current_due) {
                warning('toster', 'You can not discount more.')
                get_discount = current_due
                $('.discount').val(current_due)
            }

            total_due = current_due - paidAmount - get_discount

            $('.current-due').html(total_due);
        }



        function calculatePayment() {
            let total_payment   = 0
            let current_due     = $('#get-due').val()
            // let payment         = $('.paid-amount').val()
            let payment         = Number($('.paid-amount').val())
            let getDiscount     = Number($('.discount').val())

            if (payment > current_due) {
                warning('toster', 'You can not paid more due amount.')
                payment = current_due
                $('.paid-amount').val(payment)
            }

            total_payment       = current_due - payment -getDiscount
            $('.current-due').html(total_payment);
        }

    </script>
@stop
