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
        .payment-type-th .chosen-container.chosen-container-single{
            width: 175px !important;
            margin-left: 13px !important;
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

        .booking-view {
            padding: 50px !important;
        }

        .input-group input {
            width: 100%;
            padding-left: 10px !important;
        }

        .widget-header {
            background-color: #EAF4FA !important;
            background-image: none !important;
        }

        label.input-group-addon {
            background-color: #EAF4FA;
            color: #669fc7;
        }

        .info-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .guest-detail-table thead th {
            background-color: #4d8cb3;
            color: #fff;
        }

        .payment-info-body {
            border-bottom: 1px solid #ccc
        }

        .payment-info-body input {
            width: 100px;
            text-align: right
        }

        .payment-info .submit-btn {
            background-color: #87b87f;
            color: #fff;
            border: 1px solid #87b87f;
            padding: 5px 15px;
            border-radius: 5px;
        }

        .due-info span {
            font-size: 15px;
            font-weight: 600;
            color: #4d8cb3;
        }

    </style>
@endpush


@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                </div>

                <div class="widget-body">
                    <div class="widget-main booking-view">

                        <x-alert-message />

                        <!-- Search -->
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <form>
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td style="display: flex; justify-content: space-around">
                                                    <div class="input-group">
                                                        <select class="form-control chosen-select" name="hotel_guest_id" data-placeholder="- Choose Guest -">
                                                            <option></option>
                                                            @foreach ($hotelGuests as $guest)
                                                                <option value="{{ $guest->id }}" {{ old('hotel_guest_id') == $guest->id || request('hotel_guest_id') == $guest->id ? 'selected' : '' }}>
                                                                    {{ $guest->name }} ({{ $guest->phone_no }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="btn-group btn-corner">
                                                        <button class="btn btn-sm btn-success" type="submit">
                                                            <i class="fa fa-search"></i> Search
                                                        </button>
                                                        <a href="{{ request()->url() }}" class="btn btn-sm">
                                                            <i class="fa fa-refresh"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </form>
                            </div>

                            @if ($transactions != null && count($transactions) > 0)
                                <div class="col-md-12">
                                    <div class="info-title text-center">
                                        <span><i class="fa  fa-exclamation-circle"></i></span>
                                        <span class="title">Guest Information</span>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-4">

                                        <div class="guest-info borderless">

                                            <div class="input-group" style="width:100%">
                                                <label class="border-none input-group-addon" style="width:125px; text-align:left">
                                                    Name
                                                </label>
                                                <input type="text" value="{{ optional($hotelGuest)->name ?? 'N\A' }}" readonly>
                                            </div>

                                            <div class="input-group" style="width:100%">
                                                <label class="border-none input-group-addon" style="width:125px; text-align:left">
                                                    Email
                                                </label>
                                                <input type="text" value="{{ optional($hotelGuest)->email ?? 'N\A' }}" readonly>
                                            </div>

                                            <div class="input-group" style="width:100%">
                                                <label class="border-none input-group-addon" style="width:125px; text-align:left">
                                                    Mobile Number
                                                </label>
                                                <input type="text" value="{{ optional($hotelGuest)->phone_no ?? 'N\A' }}" readonly>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <div class="guest-info borderless">

                                            <div class="input-group" style="width:100%">
                                                <label class="border-none input-group-addon" style="width:132px; text-align:left">
                                                    NID
                                                </label>
                                                <input type="text" value="{{ optional($hotelGuest)->nid_no ?? 'N\A' }}" readonly>
                                            </div>

                                            <div class="input-group" style="width:100%">
                                                <label class="border-none input-group-addon" style="width:132px; text-align:left">
                                                    Booking Number
                                                </label>
                                                <input type="text" value="{{ optional(optional($hotelGuest->booking)->bookingInfo)->booking_number ?? 'N\A' }}" readonly>
                                            </div>
                                            <div class="input-group" style="width:100%">
                                                <label class="border-none input-group-addon" style="width:132px; text-align:left">
                                                    Address
                                                </label>
                                                <input type="text" value="{{ optional($hotelGuest)->address ?? 'N\A' }}" readonly>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            @endif
                        </div>

                        {{-- GUEST TRANSACTIONS --}}
                        <form class="form-horizontal" action="{{ route('rst.sales.store-payment-collection') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            @if ($transactions != null && count($transactions) > 0)

                                <input type="hidden" name="hotel_guest_id" value="{{ request('hotel_guest_id') }}">
                                <input type="hidden" name="is_from_due_collection" value="1">

                                <div class="guest-details" style="margin-top: 50px;">
                                    <div class="row">
                                        <div class="col-md-12">

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

                                            <div class="payment-info mt-1">
                                                <div class="row">
                                                    <div class="col-md-5 col-lg-offset-7">
                                                        <table class="table table-borderless" style="width: 85%; margin-left: auto;">
                                                            <tr>
                                                                <th>
                                                                    <p class="font-18 medium">Total Payable</p>
                                                                </th>
                                                                <th><i class="fa fa-arrow-right"></i></th>
                                                                <th class="text-right">
                                                                    <p class="font-18 bold payable-amount">{{ number_format($current_due_amount = $transactions->sum('due_amount') + $transactions->sum('change_amount'), 2 ?? 0) }}</p>
                                                                </th>
                                                            </tr>
                                                            <tr style="visibility: collapse">
                                                                <th>
                                                                    <p class="font-18 medium">Discount</p>
                                                                </th>
                                                                <th><i class="fa fa-arrow-right"></i></th>
                                                                <th class="text-right">
                                                                    <p class="font-18 bold">
                                                                        <input class="discount only-number input-sm text-right font-18 bold" type="text" min="0" step="any" value="0">
                                                                    </p>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>
                                                                    <p class="font-18 medium">Paid Amount</p>
                                                                </th>
                                                                <th><i class="fa fa-arrow-right"></i></th>
                                                                <th class="text-right">
                                                                    <p class="font-18 bold">
                                                                        <input name="total_paid_amount" class="paid-amount only-number input-sm text-right font-18 bold" type="text" min="0" step="any" value="0">
                                                                    </p>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>
                                                                    <p class="font-18 medium">Current Due</p>
                                                                </th>
                                                                <th><b><i class="fa fa-arrow-right"></i></b></th>
                                                                <th class="text-right">
                                                                    <input id="get-due" name="total_due_amount" type="hidden" value="{{ $current_due_amount }}">
                                                                    <p class="current-due font-18 bold">{{ number_format($current_due_amount, 2) }}</p>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th>
                                                                    <p class="font-18 medium">Payment Type</p>
                                                                </th>
                                                                <th><i class="fa fa-arrow-right"></i></th>
                                                                <th class="payment-type-th">
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
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th></th>
                                                                <th></th>
                                                                <th>
                                                                    <div class="text-center">
                                                                        <input id="check-full-payment" type="checkbox">
                                                                        <label for="check-full-payment" style="cursor: pointer;"><span>Full Payment</span></label>
                                                                    </div>
                                                                </th>
                                                            </tr>

                                                        </table>
                                                        <div class="row due-info">

                                                            <div class="col-md-12 text-right">
                                                                <button class="btn-outline-success btn-sm" type="submit">
                                                                    <i class="fa fa-money"></i>
                                                                    Payment
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="guest-details" style="margin-top: 50px;">
                                    <div class="row">
                                        <div class="col-md-12">

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
                                                <tbody>
                                                    <tr>
                                                        <td colspan="50" class="text-center">
                                                            <div class="alert alert-danger text-center">
                                                                No data Found!
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            @endif

                        </form>

                    </div>
                </div>
            </div>


        </div>
    </div>


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
