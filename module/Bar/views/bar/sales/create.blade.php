@extends('layouts.master')


@section('title', 'Add New Sale')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom_css/floating-input.css') }}">
    @include('bar/sales-v2/_inc/style')
@endsection

@section('content')


    @include('sales/_inc/guest-modal')
    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">



                <!-- Header -->
                <div class="widget-header">
                    <h4 class="widget-title">
                        <i class="fa fa-plus-circle"></i> New Sale
                    </h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('bar.sales.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i>
                            Sale List
                        </a>
                    </span>
                </div>

                <!-- Body -->
                <div class="widget-body">
                    <div class="widget-main">

                        <!-- Form -->
                        <form method="POST" action="{{ route('bar.sales.store') }}" accept-charset="UTF-8"
                            class="form-horizontal sales-form sales-create-form" role="form" data-parsley-validate
                            novalidate>

                            @csrf


                            <div class="col-md-12">
                                <div class='row'>

                                    @include('partials._alert_message')

                                    <!-- Search Guest Name -->
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label">Guest:</label>

                                            {{-- <input type="hidden" name="hotel_guest_id" id="hotel_guest_id" value=""> --}}

                                            <div class="input-group" style="width: 100%;">
                                                {{-- <input type="text" name="guest_name" id="guest_name" --}}
                                                <input type="text" name="guest_name" placeholder="Enter Name"
                                                    class="form-control" required>
                                                <!-- <span class="input-group-addon pointer" data-toggle="modal" data-target="#add-guest-modal">
                                                            <i class="fa fa-users"></i>
                                                        </span> -->
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Search By Room --}}
                                    {{-- <div class="col-md-2 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Room Number :</label>

                                            <input type="hidden" name="hotel_room_id" id="hotel_room_id">

                                            <input type="text" name="room_number" id="room_number" placeholder="Room Number"
                                                class="form-control">
                                        </div>
                                    </div> --}}


                                    {{-- Search By Booking --}}
                                    {{-- <div class="col-md-2 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Booking Number :</label>

                                            <input type="hidden" name="hotel_booking_id" id="hotel_booking_id" value="">

                                            <input type="text" name="booking_number" id="booking_number"
                                                placeholder="Booking Number" class="form-control">
                                        </div>
                                    </div> --}}



                                    <!-- Sale Invoice ID -->
                                    <div class="col-md-3 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Invoice ID #</label>
                                            <input type="text" tabindex="-1" class="form-control" id="invoice_id"
                                                placeholder="Invoice ID" name="invoice_no" value="{{ $invoice_id }}"
                                                readonly>
                                        </div>
                                    </div>




                                    <!-- Sale Date -->
                                    <div class="col-md-3 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Date :</label>
                                            <input type="text" name="date" value="{{ date('Y-m-d') }}"
                                                class="form-control date-picker" autocomplete="off">
                                        </div>
                                    </div>

                                </div>



                                <div class="row">

                                    <!-- Product Name -->
                                    <div class="col-md-5 col-sm-12 mb-2 margin-bottom-10 width-50-per"
                                        style="padding-left: 0; padding-right: 0">
                                        <x-widget.product-select />
                                    </div>

                                    <div
                                        class="col-md-3 col-sm-6 mb-2 margin-bottom-10 margin-top-40 width-50-per tab-inline-block tab-product-units">
                                        <div class="input-group margin-bottom-0" id="input-quantity-group">

                                            <input type="text" class="form-control only-number" id="input-big-unit-id">
                                            <span class="input-group-addon" id="input-unit-id"></span>

                                            <input type="text" class="form-control only-number" id="input-small-qty">
                                            <span class="input-group-addon" id="input-pack-unit-id"></span>

                                        </div>
                                    </div>

                                </div>

                            </div>


                            <!-- transition -->


                            <div class='row'>
                                <div class='col-md-8'>


                                    <!-- Sale Item -->
                                    <table class="table table-bordered table-hover table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Code</th>
                                                <th width="24%" class="sales-quantity-th">Quantity</th>
                                                <th width="10%" class="sales-price-th">Price</th>
                                                <th width="10%">Vat</th>
                                                <th width="10%">Total</th>
                                                <th width="1%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="product-details">
                                            {{-- <tr class="table-body-rows">
                                                <th class="text-center" colspan="30">
                                                    <div class="product">

                                                    </div>
                                                </th>
                                            </tr> --}}
                                        </tbody>
                                    </table>


                                </div>


                                <div class='col-md-4' style="padding-right: 20px;">


                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Payment Way:</label>
                                        <div class="col-md-8">
                                            @foreach ($account_types as $account_type)
                                                <label>
                                                    <input name="payment_way" value="{{ $account_type->id }}"
                                                        class="checked-reference" type="radio">
                                                    {{ $account_type->name }}
                                                </label>&nbsp;&nbsp;
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Sub Total -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Sub Total :</label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">৳</div>
                                            <input value="0" type="number" name="subtotal" class="form-control"
                                                id="subTotal" ondrop="return false;" onpaste="return false;"
                                                readonly="">
                                        </div>
                                    </div>



                                    <!-- Discount -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Discount :</label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">৳</div>
                                            <input value="0" type="number" min="0" step="any"
                                                class="form-control changesNo discount" name="discount" id="discount"
                                                placeholder="Discount" ondrop="return false;" tabindex="-1">
                                        </div>
                                    </div>


                                    <!-- Vat -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Vat :</label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">৳</div>
                                            <input value="0" type="number" class="form-control vat" name="vat"
                                                id="vat" ondrop="return false;" readonly>
                                        </div>
                                    </div>



                                    <!-- Service Charge -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Service Charge:</label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">৳</div>
                                            <input value="0" type="number" class="form-control service_charge"
                                                name="service_charge" id="service_charge" placeholder="Service Charge"
                                                ondrop="return false;">
                                        </div>
                                    </div>



                                    <!-- Grand Total -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label" style="padding-left: 0">Payable
                                            Amount:</label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">৳</div>
                                            <input value="0" type="number"
                                                name="payable_amount"class="form-control" id="grandTotal"
                                                ondrop="return false;" onpaste="return false;" readonly>
                                        </div>
                                    </div>


                                    <div id="payment">
                                        <!-- Paid Amount -->


                                        <div class="form-group aside_system">
                                            <label class="col-md-4 control-label">Paid Amount :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">৳</div>
                                                <input value="0" type="number" name="paid_amount"
                                                    class="form-control" id="paid_amount" ondrop="return false;"
                                                    onpaste="return false;">
                                            </div>
                                        </div>


                                        <!-- Change Amount -->
                                        <div class="form-group aside_system">
                                            <label class="col-md-4 control-label">Change :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">৳</div>
                                                <input tabindex="-1" type="number" min="0" step="any"
                                                    class="form-control change" name="change_amount" id="change"
                                                    value="0" ondrop="return false;" onpaste="return false;"
                                                    readonly>
                                            </div>
                                        </div>







                                        <!-- Due Amount -->
                                        <div class="form-group aside_system">
                                            <label class="col-md-4 control-label">Amount Due :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">৳</div>
                                                <input tabindex="-1" type="number" min="0" step="any"
                                                    class="form-control amountDue only-number" name="due_amount"
                                                    value="0" id="amountDue" ondrop="return false;"
                                                    onpaste="return false;" readonly>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                        </form>
                        <!-- End Form -->

                        <div class="row">
                            <div class="col-lg-9 col-md-9 col-sm-9"></div>
                            <!-- Submit Button -->
                            <div class="col-md-3 col-sm-3 pull-right">
                                <div class="form-group" style="display: flex; justify-content: end;">
                                    <button type="button" name="draft" class="btn btn-primary" style="width: 93%;"
                                        onclick="saveSaleData()">
                                        Confirm
                                    </button>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script src="{{ asset('assets/custom_js/guest_filter.js') }}"></script>

    @include('sales/_inc/script')

    <script>
        const vat_percent = "{{ $vat_percent }}"
    </script>

    @include('bar.sales._inc.booking-filter-script')

    @include('bar.sales._inc.create-script')

@endsection
