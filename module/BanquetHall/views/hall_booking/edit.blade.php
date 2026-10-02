@extends('layouts.master')

@section('title', 'Edit Booking')

@section('page-header')
    <i class="fa fa-edit"></i> Update Booking
@endsection

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

    <!-- REMOVE PHOTO CSS -->
    <style>
        .photo-remove {
            position: absolute;
            background-color: red;
            color: #fff;
            padding: 10px 15px;
            border-radius: 7px;
            top: 0;
            cursor: pointer;
            display: none;
        }

        .upload_nid .ace-file-input {
            display: block;
        }

        .nid_photo:hover .photo-remove {
            display: block;
        }

        tfoot tr td {
            border: none !important;
        }
    </style>

    @include('booking._css.css')
@endpush


@section('content')

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
                    <div class="widget-main" style="padding-bottom: 44px;">

                        <!-- Include Alert Message -->
                        <x-alert-message />


                        <!-- FORM -->
                        <form class="form-horizontal" id="submitBookingUpdateForm"
                            action="{{ route('booking.assign', $booking->id) }}" method="post"
                            enctype="multipart/form-data">

                            @csrf
                            @method('PUT')

                            <input type="hidden" value="{{ $booking->id }}" id="bookingId">
                            <input type="hidden" value="1" class="is-booking-edit" name="is_from_booking_edit">
                            <input type="hidden" value="1" name="is_from_booking">
                            <input type="hidden" value="{{ optional($booking->transection)->account_type_id }}"
                                name="previous_payment_method">

                            <!-- Basic Information -->
                            <div class="row">
                                @include('booking._inc._edit-guest-input-info')
                            </div>




                            <!-- Room Booking Configuration -->
                            <div class="row">

                                <div class="col-sm-12 col-sm-offset-0">
                                    <h3 class="header smaller lighter blue">Room Information</h3>

                                    <table id="myTable" class="table table-bordered order-list">
                                        <thead>
                                            <tr>
                                                <td width="25%">Room Category<span class="text-danger">*</span></td>
                                                <td class="text-left">Room<span class="text-danger">*</span></td>
                                                <td class="text-center" style="width: 10%">Guest</td>
                                                <td class="text-left" style="width: 12%">Amount<span
                                                        class="currency-sign"></span></td>
                                                <td class="text-right">Infant</td>
                                                <td class="text-right">Night</td>
                                                <td class="text-right">Discount<span class="currency-sign"></span></td>
                                                <td class="text-right">Discount Type</td>
                                                <td class="text-center">Breakfast</td>
                                                <td class="text-right" width="15%">T. Amount<span
                                                        class="currency-sign"></span></td>
                                                <td class="text-right"><button type="button"
                                                        class="btn btn-minier btn-success pull-right" id="addrowInEdit">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </thead>

                                        <tbody class="item-details room-details-tbody">
                                            @php
                                                $grand_total = 0;
                                            @endphp
                                            @foreach ($booking->bookingDetails as $i => $data)
                                                @php
                                                    $grand_total += calculateCurrencyAmount($data->total_amount, 1);
                                                    $category = $categories->where('id', $data->category_id)->first();
                                                @endphp

                                                <tr>
                                                    <td>
                                                        <select name="room_category[]"
                                                            class="form-control chosen-select-100-percent category"
                                                            data-placeholder="--Choose Room--"
                                                            data-selected="{{ $data->category_id }}">
                                                            <option value=""></option>
                                                            @foreach ($categories as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="room_number[]"
                                                            class="form-control chosen-select-100-percent room_number"
                                                            data-placeholder="--Choose Room--"
                                                            data-selected="{{ $data->room_id }}">
                                                            <option value=""></option>
                                                            @foreach ($rooms as $id => $name)
                                                                <option value="{{ $id }}">{{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <select name="guest[]"
                                                            class="form-control text-center guest-number input-guest"
                                                            data-placeholder="--Select Guest--"
                                                            data-selected="{{ $data->guest_count ?? 1 }}">
                                                            @for ($i = 1; $i <= $category->guest_capacity; $i++)
                                                                <option value="{{ $i }}"
                                                                    data-price="{{ calculateCurrencyAmount(optional(optional($category->roomPrices->where('capacity', $i))->first())->price, 1) }}">
                                                                    {{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>

                                                    </td>
                                                    <td>
                                                        <input type="text" name="room_price[]"
                                                            value="{{ $data->night_count != null ? calculateCurrencyAmount($data->total_amount, 1) / $data->night_count : calculateCurrencyAmount($data->total_amount, 1) }}"
                                                            class="form-control amount only-number text-right">
                                                    </td>
                                                    <td>
                                                        {!! Form::number('infant[]', $data->infant_count, [
                                                            'class' => 'form-control text-right input-infant',
                                                            'min' => 0,
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::number('night[]', $data->night_count ?? 1, [
                                                            'class' => 'form-control text-right night_count',
                                                            'readonly',
                                                        ]) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::number('discount[]', calculateCurrencyAmount($data->discount_amount, 1), [
                                                            'class' => 'form-control text-right discount',
                                                            'type' => 'number',
                                                            'min' => 0,
                                                        ]) !!}

                                                    </td>
                                                    <td>
                                                        <select name="discount_type[]" class="form-control chosen-select-100-percent discount_type">
                                                            <option value="default" selected>Default</option>
                                                            <option value="complementary">Complementary</option>
                                                        </select>

                                                    </td>
                                                    <td>
                                                        <label>
                                                            <input name="allow_breakfast[]"
                                                                class="ace ace-switch ace-switch-6" value="1"
                                                                type="checkbox" checked>
                                                            <span class="lbl"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="amount[]" style="font-size: 16px"
                                                            class="form-control input-sm text-right only-number net-amount"
                                                            value="{{ calculateCurrencyAmount($data->total_amount, 1) }}"
                                                            readonly>
                                                        <input type="hidden"
                                                            value="{{ $data->night_count != null ? calculateCurrencyAmount($data->total_amount, 1) / $data->night_count : calculateCurrencyAmount($data->total_amount, 1) }}"
                                                            class="net-amount-hidden">

                                                        <input type="hidden" name="room_services[]"
                                                            class="room-wise-service-charge"
                                                            value="{{ $data->service_charge }}">

                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn btn-xs btn-danger ibtnDel" disabled><i
                                                                class="fa fa-trash-o"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>

                                        <!-- TFOOT INCLUDE -->
                                        @include('booking._inc.create-edit-tfoot', [
                                            'subtotal' => $grand_total,
                                            'transaction' => $booking->transection,
                                            'colspan' => 9,
                                        ])


                                    </table>
                                </div>
                            </div>

                        </form>

                        <!-- SUBMIT/ACTION BUTTON -->
                        <div class="col-xs-12 col-sm-12 text-right" style="margin-top: 5px; padding-right: 50px;">
                            <button class="btn-sm btn-outline-success updateBookingBtn" onclick="submitBookingForm()"
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
                </div>
            </div>


        </div>
    </div>
    @include('partials/modal/edit_guest_modal')

@endsection

@section('script')

    @include('booking._script.update-customer-script')
    @include('booking._script.script')

    <script>
        $('.photo-remove').click(function() {
            $(this).closest('.nid_photo').remove();
            $(this).closest('.ace-file-input').show();

        });
    </script>

@endsection
