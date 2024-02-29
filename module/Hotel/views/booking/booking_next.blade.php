@extends('layouts.master')
@section('title', 'Add Booking')

@section('page-header')
    <i class="fa fa-plus-circle"></i> Add New Booking
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .table {
            margin-bottom: 0 !important;
        }

        body {
            counter-reset: section;
            /* Set a counter named 'section', and its initial value is 0. */
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

    @include('booking._css.css')
@endpush


@section('content')

    @php
        $date = date('Y-m-d');
        $date1 = str_replace('-', '/', $date);
        $tomorrow = date('Y-m-d', strtotime($date1 . '+1 days'));
    @endphp

    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('booking.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i> Booking List
                        </a>
                    </span>

                </div>

                <div class="widget-body">
                    <div class="widget-main">

                        <x-alert-message />

                        <form class="form-horizontal" action="{{ route('booking.store') }}" id="submitBookingUpdateForm"
                            method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    @php
                                        $date = explode('-', request('booking_availabe'));
                                    @endphp



                                    <!------------ INCLUDE GUEST INPUT FIELDS ------------>
                                    <div class="row">
                                        @include('booking._inc._booking-next-input-info')
                                    </div>



                                </div>
                            </div>

                            <!------- ROOM INFO ------>
                            <div class="row">
                                <div class="col-sm-12 col-sm-offset-0">
                                    <h3 class="header smaller lighter blue">Room Information</h3>

                                    <table id="myTable" class="table table-bordered order-list">
                                        <thead>

                                            <tr>
                                                <th width="40px;">SL.</th>
                                                <th width="25%">Room Category<span class="text-danger">*</span></th>
                                                <th class="text-left">Room<span class="text-danger">*</span></th>
                                                <th class="text-center" style="width: 5%">Guest</th>
                                                <th class="text-right" style="padding-left: 3px; padding-right: 2px">Amount
                                                    <span class="currency-sign"></span>
                                                </th>
                                                <th class="text-right">Infant</th>
                                                <td class="text-right">Child</td>
                                                <th class="text-right">Night</th>
                                                <th class="text-right">Discount <span class="currency-sign"></span></th>
                                                <td class="text-right">Complementary</td>
                                                <td class="text-center breakfast_qty">BF Qty</td>
                                                <th class="text-right">Breakfast</th>
                                                <th class="text-right" style="width: 10%">T. Amount <span
                                                        class="currency-sign"></span></th>
                                                <th class="text-right" style="width: 3%"></th>
                                            </tr>

                                        </thead>

                                        <tbody class="item-details">

                                            @php
                                                $sub_total = 0;
                                            @endphp

                                            @if ($booking)

                                                @foreach ($booking as $data)
                                                    @php
                                                        // $line_total = setting('root_currency') == 141 ? convertCurrencyByBDT($data['category_price'] * $data['nights']) : $data['category_price'] * $data['nights'];
                                                        $line_total = calculateCurrencyAmount($data['category_price']['price'] * $data['nights'], 1);
                                                        $roomCategoryPrice = calculateCurrencyAmount($data['category_price']['price'], 1);
                                                        $sub_total += $line_total;
                                                    @endphp

                                                    {{-- Booking Checkin & Checkout Date --}}

                                                    <input type="hidden" name="check_in" value="{{ $data['check_in'] }}">
                                                    <input type="hidden" name="check_out" value="{{ $data['check_out'] }}">

                                                    <tr class="booking_list">
                                                        <td class="count">

                                                        </td>
                                                        <td>
                                                            @php
                                                                $roomCategory = $categories->where('id', $data['category_id'])->first();
                                                            @endphp
                                                            <input type="hidden" name="allow_guest_wise_prices[]"
                                                                class="allow-gues-wise-price"
                                                                value="{{ $roomCategory->allow_guest_wise_price }}">
                                                            <input type="hidden" name="room_category[]" data
                                                                value="{{ $data['category_id'] }}">
                                                            <p>{{ $data['category_name'] }}</p>
                                                        </td>

                                                        <td>
                                                            <input type="hidden" name="room_number[]" id="room_id"
                                                                value="{{ $data['room_id'] }}">
                                                            <p>{{ $data['room_number'] }}</p>

                                                        </td>
                                                        <td>
                                                            <select name="guest[]"
                                                                class="form-control input-guest chosen-select-100-percents"
                                                                data-placeholder="--Choose Guest--">
                                                                @for ($i = 1; $i <= $roomCategory->guest_capacity; $i++)
                                                                    <option value="{{ $i }}"
                                                                        data-price="{{ calculateCurrencyAmount(optional(optional($roomCategory->roomPrices)->where('capacity', $i)->first())->price,1) }}">
                                                                        {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="room_price[]"
                                                                class="form-control text-center only-number amount"
                                                                value="{{ $roomCategoryPrice }}">
                                                        </td>

                                                        <td>
                                                            <input type="text" name="child[]"
                                                                class="form-control text-rignt input-child only-number"
                                                                value="0">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="infant[]"
                                                                class="form-control text-rignt input-infant only-number"
                                                                value="0">
                                                        </td>

                                                        <td>
                                                            <input type="text" name="night[]"
                                                                class="form-control text-right night_count only-number"
                                                                readonly value="{{ $data['nights'] }}">

                                                        </td>

                                                        <td>
                                                            <input type="text" name="discount[]"
                                                                class="form-control text-right discount only-number"
                                                                value="0">

                                                        </td>
                                                        <td>
                                                            <select name="discount_type[]"
                                                                class="form-control chosen-select-100-percent discount_type">
                                                                <option value="0" selected>Default</option>
                                                                <option value="1">Complementary</option>
                                                            </select>

                                                        </td>
                                                        <td>
                                                            <input type="text" name="breakfast_qty[]"
                                                                class="form-control text-right breakfast_qty only-number"
                                                                value="0">

                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <label>
                                                                    <input name="allow_breakfast"
                                                                        class="ace ace-switch ace-switch-6" value="1"
                                                                        type="checkbox" checked>
                                                                    <span class="lbl"></span>
                                                                </label>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" name="amount[]"
                                                                    style="font-size: 16px"
                                                                    class="form-control input-sm text-right only-number net-amount"
                                                                    value="{{ $line_total }}" readonly>
                                                                <input type="hidden" value="{{ $line_total }}"
                                                                    class="net-amount-hidden">
                                                                <input type="hidden" name="room_services[]"
                                                                    class="room-wise-service-charge"
                                                                    value="{{ ($line_total / 100) * vatSetting()->room_service_charge }}">
                                                            </div>
                                                        </td>

                                                        <td class="text-center">
                                                            {{-- <a class="btn-sm btn-outline-danger booking_delete pointer" style="pointer-events: none"> --}}
                                                            <a class="btn-sm btn-outline-danger booking_delete pointer">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                        </tbody>

                                        <tfoot style="position: relative;">
                                            @php

                                                if (setting('use_vat_included') == 1) {
                                                    $room_rate   = $vat->room_rate;
                                                    $get_vat = $vat->hotel_vat;

                                                    $rate   = ($sub_total / $room_rate) * 100;
                                                    $service_amount = ($rate * $vat->room_service_charge) / 100;
                                                    $total_without_vat = $rate + $service_amount;
                                                    $vat_amount = ($total_without_vat * $get_vat) / 100;
                                                    // $grand_total = $total_without_vat + $vat_amount;
                                                    $grand_total = $sub_total;


                                                    // var rate = (subtotal / room_rate) * 100;
                                                    // var service_amount = (rate * service_percent) / 100;
                                                    // var total_without_vat = rate + service_amount;
                                                    // var calculate_vat = (total_without_vat * vat) / 100;
                                                    // var extra_charge = Number($('.extra-charge').val()) || 0;
                                                    // var grandtotal = total_without_vat + calculate_vat + extra_charge;

                                                }else {

                                                    $get_vat = $vat->hotel_vat;
                                                    $service_amount = ($sub_total * $vat->room_service_charge) / 100;
                                                    $vat_amount = (($sub_total + $service_amount) * $get_vat) / 100;
                                                    $grand_total = $sub_total + $service_amount + $vat_amount;

                                                }
                                            @endphp

                                            @include('booking._inc._check-sms-and-email')

                                            <tr>
                                                <td class="text-right borderless" colspan="12">
                                                    Subtotal <span class="currency-sign"></span>
                                                </td>

                                                <td class="borderless">
                                                    <input type="number" style="font-size: 16px"
                                                        class="form-control subtotal-amount text-right input-sm"
                                                        value="{{ $sub_total }}" readonly>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-right borderless" colspan="12">
                                                    Service Charge({{ (int) vatSetting()->room_service_charge }}%) <span
                                                        class="currency-sign"></span>
                                                </td>

                                                <td class="borderless">
                                                    <input type="number" name="service_amount" style="font-size: 16px"
                                                        class="form-control service_amount text-right input-sm"
                                                        value="{{ (int) $service_amount }}" readonly>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-right borderless" colspan="12">
                                                    <input type="hidden" name="vat" id="vat"
                                                        value="{{ $vat->hotel_vat }}">

                                                    Vat({{ $vat->hotel_vat }}%) <span class="currency-sign"></span>
                                                </td>
                                                <td class="borderless">
                                                    <input type="number" name="vat_amount" style="font-size: 16px"
                                                        class="form-control vat-amount text-right input-sm"
                                                        value="{{ (int) $vat_amount }}" readonly>
                                                </td>
                                            </tr>
                                            <tr style="visibility: collapse;">
                                                <td class="text-right borderless" colspan="12">
                                                    Extra Charge
                                                </td>
                                                <td class="borderless">
                                                    <input type="number" name="extra_charge" style="font-size: 16px"
                                                        class="form-control extra-charge text-right input-sm"
                                                        value="0">
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-right borderless" colspan="12">Total Payable <span
                                                        class="currency-sign"></span></td>
                                                <td class="borderless">
                                                    <input name="sub_total" style="font-size: 16px"
                                                        class="form-control grandtotal text-right input-sm"
                                                        value="{{ $grand_total }}" readonly>
                                                    <input type="hidden" name="line_total" id="line_total"
                                                        value="{{ $sub_total }}">
                                                </td>
                                            </tr>

                                            @if (hasPermission('bookings.advance', $slugs))
                                                <tr>
                                                    <td class="text-right borderless" colspan="12">Advanced Amount</td>
                                                    <td class="borderless">

                                                        {{--  @if (setting('root_currency') == 116)
                                                            <label class="choose-currency" id="bdtCurrency"
                                                                style="margin: 0 8px 0 6px;">
                                                                <input type="radio" name="currency_type" value="116"
                                                                    {{ setting('root_currency') == 116 ? 'checked' : '' }}>
                                                                SR (৳)
                                                            </label>
                                                        @elseif (setting('root_currency') == 96)
                                                            <label class="choose-currency" id="bdtCurrency"
                                                                style="margin: 0 8px 0 6px;">
                                                                <input type="radio" name="currency_type" value="12"
                                                                    {{ setting('root_currency') == 96 ? 'checked' : '' }}>
                                                                RM
                                                            </label>
                                                        @elseif (setting('root_currency') == 12)
                                                            <label class="choose-currency" id="bdtCurrency"
                                                                style="margin: 0 8px 0 6px;">
                                                                <input type="radio" name="currency_type" value="12"
                                                                    {{ setting('root_currency') == 12 ? 'checked' : '' }}>
                                                                BDT (৳)
                                                            </label>
                                                        @endif
                                                        <label class="choose-currency" id="usdCurrency">
                                                            <input type="radio" name="currency_type" value="141"
                                                                {{ setting('root_currency') == 141 ? 'checked' : '' }}>
                                                            USD ($)
                                                        </label> 

                                                        <div class="show-currency-rate"
                                                            style="display: none; color:rgb(230, 92, 115); text-align:center">
                                                        </div>  --}}

                                                        <input type="text" name="advanced_amount"
                                                            class="only-number form-control adv-amount text-right input-sm"
                                                            value="" style="font-size: 16px" autocomplete="off">
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td class="text-right borderless" colspan="12">Due Amount <span
                                                        class="currency-sign"></span></td>
                                                <td class="borderless">
                                                    <input type="number" name="due_amount" style="font-size: 16px"
                                                        class="form-control due-amount text-right input-sm"
                                                        value="{{ $grand_total }}" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right borderless" colspan="12">Payment Method <span
                                                        class="currency-sign"></span></td>
                                                <td class="borderless">
                                                    <select class="form-control chosen-select-100-percent payment-type"
                                                        name="payment_type" data-placeholder="-Choose Payment Type-">
                                                        <option></option>
                                                        @foreach ($account_types ?? [] as $id => $name)
                                                            <option value="{{ $id }}"
                                                                {{ old('payment_type') == $id ? 'selected' : '' }}>
                                                                {{ $name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>

                                            <tr class="card_info" style="display: none">
                                                <td class="text-right borderless" colspan="12">Card Authorized No</td>
                                                <td class="borderless">
                                                    <input type="text" name="card_info" value=""
                                                        class="form-control text-center" placeholder="XXXX-XXXX-XXX">
                                                </td>
                                            </tr>


                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                                <div class="col-xs-12 col-sm-12 text-right">
                                    <button class="btn-sm btn-outline-success" onclick="submitBookingForm()"
                                        type="button">
                                        <i class="fa fa-save"></i>
                                        Save
                                    </button>

                                    <button class="btn-sm btn-outline-danger" type="Reset">
                                        <i class="fa fa-refresh"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>

                            @include('booking/_modal/member-detail-modal')

                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>

    @include('partials.modal.new_guest_modal')
    @include('partials/modal/edit_v1_guest_modal')


@endsection

@section('script')

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>


    @include('booking._script.booking-next-script')


@endsection
