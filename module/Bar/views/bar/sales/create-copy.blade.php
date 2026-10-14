@extends('layouts.master')


@section('title', 'Add New Sale')


@section('content')


    @include('sales/_inc/guest-modal')
    <x-mm.styles />
    <x-mm.page class="mm-sale-form mm-bar" title="New Sale">
        <x-slot name="actions">
            <a href="{{ route('bar.sales.index') }}" class="btn btn-sm btn-default">
                <i class="ace-icon fa fa-list-alt"></i>
                Sale List
            </a>
        </x-slot>

        <x-mm.panel>

                        <!-- Form -->
                        <form method="POST" action="{{ route('bar.sales.store') }}" accept-charset="UTF-8"
                            class="form-horizontal sales-form" role="form" data-parsley-validate novalidate>
                            @csrf


                            <div class="col-md-12">
                                <div class='row'>

                                    @include('partials._alert_message')

                                    <!-- Search Guest Name -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Guest:</label>

                                            <input type="hidden" name="hotel_guest_id" id="hotel_guest_id" value="">

                                            <div class="input-group">
                                                <input type="text" name="guest_name" id="guest_name"
                                                placeholder="Name/Mobile No." class="form-control" required>
                                                <span class="input-group-addon pointer" data-toggle="modal" data-target="#add-guest-modal">
                                                    <i class="fa fa-users"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- Search By Room --}}
                                    <div class="col-md-2 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Room Number :</label>

                                            <input type="hidden" name="hotel_room_id" id="hotel_room_id">

                                            <input type="text" name="room_number" id="room_number" placeholder="Room Number"
                                                class="form-control">
                                        </div>
                                    </div>


                                    {{-- Search By Booking --}}
                                    <div class="col-md-2 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Booking Number :</label>

                                            <input type="hidden" name="hotel_booking_id" id="hotel_booking_id" value="">

                                            <input type="text" name="booking_number" id="booking_number"
                                                placeholder="Booking Number" class="form-control">
                                            {{-- <p class="text-danger text-center">Not Found!</p> --}}
                                        </div>
                                    </div>



                                    <!-- Sale Invoice ID -->
                                    <div class="col-md-2 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Invoice ID #</label>
                                            <input type="text" tabindex="-1" class="form-control" id="invoice_id"
                                                placeholder="Invoice ID" name="invoice_no" value="{{ $invoice_id }}" readonly>
                                        </div>
                                    </div>




                                    <!-- Sale Date -->
                                    <div class="col-md-2 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Date :</label>
                                            <input type="text" name="date" value="{{ date('Y-m-d') }}"
                                                class="form-control date-picker" autocomplete="off">
                                        </div>
                                    </div>

                                </div>



                                <div class="row">

                                    <!-- Product Name -->
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">Product</span>
                                                <input type="text" name="product_name" id="drug-name" class="form-control"
                                                    placeholder="Search by Product Name / Barcode" autocomplete="off" style="z-index: 0">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>


                            <!-- transition -->


                            <div class='row'>
                                <div class='col-md-8'>


                                    <!-- Sale Item -->
                                    <table class="table table-bordered table-hover" id="table_auto">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th width="15%">Available Qty</th>
                                                <th width="15%">Quantity</th>
                                                <th width="15%">Sales Price</th>
                                                <th width="18%">Total</th>
                                                <th width="1%">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="product-details"></tbody>
                                    </table>


                                </div>


                                <div class='col-md-4'>


                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Payment Way:</label>
                                        <div class="col-md-8">
                                            @foreach ($account_types as $account_type)
                                                <label>
                                                    <input name="payment_way" value="{{ $account_type->id }}" class="checked-reference" type="radio">
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
                                            <input tabindex="-1" value="0" type="number" min="0" name="subtotal" step="any"
                                                class="form-control" id="subTotal" placeholder="Sub Total"
                                                ondrop="return false;" onpaste="return false;" readonly>
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




                                    <!-- Total -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><b>Total :</b></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">৳</div>
                                            <input tabindex="-1" value="0" type="number" min="0" step="any"
                                                class="form-control" name="total_amount" id="total"
                                                placeholder="Total Amount" ondrop="return false;" onpaste="return false;"
                                                readonly>
                                        </div>
                                    </div>




                                    <!-- Vat -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><b>Vat :</b></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">৳</div>
                                            <input tabindex="-1" value="0" type="number" min="0" step="any"
                                                class="form-control" name="vat_amount" id="vat" onkeyup="vatUpdate()"
                                                placeholder="Vat Amount" />
                                        </div>
                                    </div>



                                    <!-- Service Charge -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><b>Service Charge:</b></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">৳</div>
                                            <input tabindex="-1" value="0" type="number" min="0" step="any"
                                                class="form-control" name="service_amount" id="service_charge"
                                                placeholder="Service Charge" ondrop="return false;" tabindex="-1">
                                        </div>
                                    </div>



                                    <!-- Grand Total -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><b> Grand Total: </b></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">৳</div>
                                            <input tabindex="-1" value="" type="number" min="0" step="any"
                                                class="form-control" name="grand_total" id="grandTotal"
                                                placeholder="Total Amount" ondrop="return false;" onpaste="return false;"
                                                readonly>
                                        </div>
                                    </div>


                                    <div id="payment">
                                        <!-- Paid Amount -->


                                        <div class="form-group aside_system">
                                            <label class="col-md-4 control-label">Paid Amount :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">৳</div>
                                                <input value="0" type="number" min="0" step="any" class="form-control"
                                                    name="paid_amount" id="amountPaid" placeholder="Paid Amount"
                                                    ondrop="return false;" onpaste="return false;">
                                            </div>
                                        </div>


                                        <!-- Change Amount -->
                                        <div class="form-group aside_system">
                                            <label class="col-md-4 control-label">Change :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">৳</div>
                                                <input tabindex="-1" type="number" min="0" step="any"
                                                    class="form-control change" name="change_amount" id="change"
                                                    placeholder="Change Amount" ondrop="return false;"
                                                    onpaste="return false;" readonly>
                                            </div>
                                        </div>







                                        <!-- Due Amount -->
                                        <div class="form-group aside_system">
                                            <label class="col-md-4 control-label">Amount Due :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">৳</div>
                                                <input tabindex="-1" type="number" min="0" step="any"
                                                    class="form-control amountDue only-number" name="due_amount"
                                                    id="amountDue" placeholder="Amount Due" ondrop="return false;"
                                                    onpaste="return false;" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <!-- Submit Button -->
                                        <div class="col-md-8 col-sm-8 pull-right">
                                            <div class="form-group">
                                                <button type="submit" name="draft" class="btn btn-primary"
                                                    style="width: 100%;">
                                                    Confirm
                                                </button>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>


                        </form>
                        <!-- End Form -->
        </x-mm.panel>
    </x-mm.page>

@endsection

@section('js')

    <script src="{{ asset('assets/custom_js/guest_filter.js') }}"></script>
    @include('sales/_inc/script')

    <script>
        const vat_percent = "{{ $vat_percent }}"

        $(document).on('click', '.ui-menu-item', function() {
            let guest_id = $('#hotel_guest_id').val();
            getGuestInfo(guest_id)
        });

        function getGuestInfo(guest_id) {

            $.ajax({
                type: 'get',
                url: '/hotelservice/guest-d-by-name/' + guest_id,
                async: true,

                beforeSend: function() {
                    $("body").css("cursor", "progress");
                },
                success: function(data) {
                    if (data['status'] != 0) {
                        $('#room_number').val(data.room_number);
                        $('#booking_number').val(data.booking_number);
                    }
                },
                complete: function(data) {
                    $("body").css("cursor", "default");
                }
            });
        }
    </script>

    {{-- Get Room Number --}}
    <script>
        $(document).on('focus', '#room_number', function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.getJSON('/hotelservice/get-room-list', {
                            name: request.term
                        },
                        function(data) {
                            response($.map(data, function(items) {
                                return {
                                    value: items.available_quantity,
                                    label: `${items.room_number}`,
                                    data: items,
                                }
                            }))
                        }
                    )
                },
                select: function(event, ui) {
                    let rooms_id = ui.item.data.id;
                    GetInfoRoom(rooms_id);

                },
                search: function(event, ui) {},
                minLength: 1,
                autoFocus: true
            })
        })
    </script>


    {{-- Get Booking Number --}}
    <script>
        $(document).on('focus', '#booking_number', function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.getJSON('/hotelservice/get-booking-number', {
                            name: request.term
                        },
                        function(data) {
                            response($.map(data, function(items) {
                                return {
                                    value: items.available_quantity,
                                    label: `${items.booking_number}`,
                                    data: items,
                                }
                            }))
                        }
                    )
                },
                select: function(event, ui) {
                    let booking_id = ui.item.data.id;
                    getInfoByBooking(booking_id);

                },
                search: function(event, ui) {},
                minLength: 1,
                autoFocus: true
            })
        })
    </script>

    {{-- Get Info By Booking --}}

    <script>
        function getInfoByBooking(booking_id) {

            $.ajax({
                type: 'get',
                url: '/hotelservice/guest-by-booking/' + booking_id,
                async: true,

                beforeSend: function() {
                    $("body").css("cursor", "progress");
                },
                success: function(data) {
                    $('#guest_name').val(data['guest_name']);
                    $('#room_number').val(data['room_number']);
                    $('#hotel_booking_id').val(booking_id);
                    $('#hotel_guest_id').val(data['guest_id']);
                },
                complete: function(data) {
                    $("body").css("cursor", "default");
                }
            });
        }
    </script>

    {{-- Get Info By Room --}}
    <script>
        function GetInfoRoom(rooms_id) {

            $.ajax({
                type: 'get',
                url: '/hotelservice/guest-by-rooms/' + rooms_id,
                async: true,

                beforeSend: function() {
                    $("body").css("cursor", "progress");
                },
                success: function(data) {
                    $('#guest_name').val(data['guest_name']);
                    $('#booking_number').val(data['booking_number']);
                    $('#hotel_booking_id').val(data['booking_id']);

                },
                complete: function(data) {
                    $("body").css("cursor", "default");
                }
            });
        }
    </script>
    <script>
        function requestUrl(urlParts) {
            urlParts = $.extend({
                basePath: '{{ url('/') }}/',
                path: '',
                param: ''
            }, urlParts);
            return urlParts.basePath + urlParts.path + urlParts.param;
        }


        function patientFilter() {
            patientWithIndoor('indoor');

        }

        $(document).on('focus', '#drug-name', function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.getJSON('/restaurant/inventory/get-products', {
                            name: request.term
                        },
                        function(data) {
                            response($.map(data, function(items) {
                                return {
                                    value: items.available_quantity,
                                    label: `${items.name}`,
                                    data: items,
                                }
                            }))
                        }
                    )
                },
                select: function(event, ui) {
                    appendProductRow(ui)

                },
                search: function(event, ui) {},
                minLength: 1,
                autoFocus: true
            })
        })

        $(document).on('click', '.delete', function(e) {
            $(this).parents('tr').remove();
            calculateTotal();
        })
    </script>



    <script>
        function appendProductRow(ui) {
            productRow(ui)
            $('#drug-name').val('')

            calculateTotal()
            calculateVat()

        }



        function productRow(ui) {
            let html = '';

            html =
                `<tr class="product-row">
                        <td>
                            <span id="product_name">${ui.item.data.name}</span>
                            <input type="hidden" value="${ui.item.data.id}" name="product_ids[]" id="productId"
                            class="form-control small-box productId" autocomplete="off">
                        </td>
                        <td>
                            <input type="text" tabindex="-1" value="${ui.item.data.total_qty}"
                                name="available_qty[]" id="available_qty" class="form-control small-box avaliable_qty"
                                ondrop="return false;" onpaste="return false;" readonly>
                            <input type="hidden" tabindex="-1" value="${ui.item.data.total_qty}" name="old_available_qty[]" id="old_available_qty">
                            <input type="hidden" tabindex="-1" value="${ui.item.data.expiry_date}" name="expiry_date[]">
                        </td>

                        <td>
                            <input type="number" min="1" value="1"  name="sales_qty[]" max="${ui.item.data.total_qty}" step="any" id="sales_qty" class="form-control sales-qty small-box" autocomplete="off" onkeyup="" onpaste="return false;" placeholder="Quantity">
                        </td>
                        <td>
                            <input tabindex="-1" type="text" value="${parseFloat(ui.item.data.sale_price).toFixed(2)}" name="sales_price[]" class="form-control sales-price small-box" autocomplete="off" ondrop="return false;" onpaste="return false;" readonly>
                        </td>
                        <td>
                            <input tabindex="-1" type="number" min="0" value="${parseFloat(ui.item.data.sale_price).toFixed(2)}" step="any" name="item_price[]" class="form-control total-line-price small-box" autocomplete="off"  ondrop="return false;" onpaste="return false;" readonly="readonly">
                        </td>
                        <td>
                            <button class="btn btn-xs btn-danger delete" tabindex="-1" type="button"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>`



            $('#product-details').append(html);

        }
    </script>

    <script>
        $(document).on('keyup focus', '.sales-qty, .discount, #service_charge, #amountPaid', function(e) {

            calculateTotal(e)
        });


        $(document).on('keyup', '.discount', function(e) {
            calculateVat(e)
        })


        function calculateTotal(e) {

            var subTotal = linePrice(),
                total = discount(subTotal),
                total_With_Vat_Amount = Number($('#vat').val()),
                finalAmount = chargeAmount(total_With_Vat_Amount + Number(total)),
                payableAmount = grandTotal(parseFloat(finalAmount))

            dueAmount(payableAmount)
        }

        function calculateVat(e) {

            var subTotal = linePrice(),
                total = discount(subTotal),
                total_With_Vat_Amount = vatApply(total),
                finalAmount = chargeAmount(total_With_Vat_Amount),
                payableAmount = grandTotal(parseFloat(finalAmount))

            dueAmount(payableAmount)
        }


        function vatUpdate() {
            let vat_amount = Number($('#vat').val())
            let total = Number($('#total').val())

            $('#grandTotal').val(total + vat_amount)
            dueAmount(total + vat_amount)
        }


        function vatApply(amount) {
            let vatAmount = parseFloat(amount * vat_percent / 100),
                subTotal = parseFloat(amount)

            $('#vat').val(vatAmount)
            var total = (subTotal + vatAmount).toFixed(2)
            return total
        }



        function chargeAmount(amount) {

            var charge = parseFloat($('#service_charge').val()),
                subTotal = parseFloat(amount)


            if (charge > 0) {
                var total = (subTotal + charge).toFixed(2)
                $('#service_charge').val(charge);
                return total
            }
            return subTotal
        }



        function linePrice() {
            var rows = $('.product-row')
            totalDrugPrice = 0, amount = 0

            $.each(rows, function(index, row) {

                let available_qty = $(row).find('#old_available_qty').val()
                var quantity = $(row).find('.sales-qty').val(),
                    salesPrice = $(row).find('.sales-price').val(),
                    totalDrugPrice = (quantity * salesPrice)


                now_available_qty = available_qty - quantity

                $(row).find('#available_qty').val(now_available_qty)

                if (!quantity || !salesPrice) quantity = 0, salesPrice = 0
                $(row).find('.total-line-price').val(Math.round(totalDrugPrice).toFixed(2))
                amount += totalDrugPrice
            });

            $('#subTotal').val(Math.round(amount).toFixed(2))
            return Math.round(amount).toFixed(2)
        }



        function discount(stotal) {
            var discount = parseFloat($('.discount').val() | 0),
                subTotal = parseFloat(stotal);
            if (discount > subTotal) {
                $('.discount').val(0)
                alert('You can\'t pay more than grand total.');
            }
            var total = (subTotal - discount).toFixed(2)
            $('#total').val(total)
            return total
        }




        function grandTotal(total) {
            $('#grandTotal').val(parseFloat(total).toFixed(2))
            return total
        }





        function dueAmount(payableAmount) {
            let paidAmount = parseFloat($('#amountPaid').val())
            if (!paidAmount) paidAmount = 0
            if (paidAmount >= payableAmount) {
                $('#change').val(parseInt(paidAmount - payableAmount))
                $('#amountDue').val(0)
            } else {
                $('#change').val(0)
                $('#amountDue').val(payableAmount - paidAmount)
            }
        }
    </script>

@endsection
