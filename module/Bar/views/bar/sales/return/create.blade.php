@extends('layouts.master')


@section('title', 'New Sale Return')


@section('content')
    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">



                <!-- Header -->
                <div class="widget-header">
                    <h4 class="widget-title">
                        <i class="fa fa-plus-circle"></i> New Sale Return
                    </h4>

                    <span class="widget-toolbar">
                        <a href="{{ route('bar.sales.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i>
                            Sale Return List
                        </a>
                    </span>
                </div>






                <!-- Body -->
                <div class="widget-body">
                    <div class="widget-main">




                        <!-- Form -->
                        <form method="POST" action="{{ route('bar.sale-returns.store') }}" accept-charset="UTF-8"
                            class="form-horizontal sales-form" role="form" data-parsley-validate novalidate>
                            @csrf


                            <div class="col-md-12">
                                <div class='row'>



                                    <!-- Search Guest Name -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Guest Name<sup class="text-danger">*</sup>
                                                :</label>

                                            <input type="hidden" name="customer_id" id="hotel_guest_id" required>

                                            <input type="text" name="guest_name" id="guest_name"
                                                placeholder="Guest/Customer's Name" class="form-control" required />
                                        </div>
                                    </div>



                                    <!-- Sale Invoice ID -->
                                    <div class="col-md-3 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Invoice ID #</label>
                                            <input type="text" name="invoice_no" id="invoice_no" class="form-control"
                                                placeholder="Search by Sale Invoice No" autocomplete="off">
                                        </div>
                                    </div>




                                    <!-- Sale Date -->
                                    <div class="col-md-2 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Date :</label>
                                            <input type="text" name="date" value="{{ date('Y-m-d') }}"
                                                class="form-control date-picker" autocomplete="off" />
                                        </div>
                                    </div>

                                </div>





                                <div class="row" hidden>

                                    <!-- Product Name -->
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label">Product Name :</label>
                                            <input type="text" name="product_name" id="drug-name" class="form-control"
                                                placeholder="Search by Product Name / Barcode" autocomplete="off" />
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
                                                <th width="15%">Returnable Qty</th>
                                                <th>Return Qty</th>
                                                <th width="15%">Price</th>
                                                <th width="15%">Discount</th>
                                                <th width="15%">Amount</th>
                                                <th width="1%">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="product-details"></tbody>
                                    </table>


                                </div>


                                <div class='col-md-4'>




                                    <!-- Total Amount -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Total Amount :</label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">RM</div>
                                            <input tabindex="-1" value="0" type="number" min="0"
                                                name="subtotal" step="any" class="form-control" id="total_amount"
                                                placeholder="Total Amount" ondrop="return false;" onpaste="return false;"
                                                readonly>
                                        </div>
                                    </div>

                                    <!-- Total Amount -->
                                    {{-- <div class="form-group">
                                        <label class="col-md-4 control-label">Total Amount :</label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">RM</div>
                                            <input tabindex="-1" value="0" type="number" min="0"
                                                name="subtotal" step="any" class="form-control" id="total_amount"
                                                placeholder="Total Amount" ondrop="return false;" onpaste="return false;"
                                                readonly>
                                        </div>
                                    </div> --}}




                                    <!-- Previous Due -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><b>Previous Due :</b></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">RM</div>
                                            <input tabindex="-1" value="0" type="number" min="0"
                                                step="any" class="form-control" name="previous_due" id="total"
                                                placeholder="Previous Due" ondrop="return false;" onpaste="return false;"
                                                readonly>
                                        </div>
                                    </div>



                                    <!-- Grand Total -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><b> Grand Total: </b></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">RM</div>
                                            <input tabindex="-1" value="" type="number" min="0"
                                                step="any" class="form-control" name="payable_amount"
                                                id="grandTotal" placeholder="Total Amount" readonly>
                                        </div>
                                    </div>



                                    <div id="payment">
                                        <!-- Return Amount -->


                                        <div class="form-group aside_system">
                                            <label class="col-md-4 control-label">Return Amount :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">RM</div>
                                                <input value="0" type="number" min="0" step="any"
                                                    class="form-control" name="return_amount" id="returnAmount"
                                                    placeholder="Return Amount" ondrop="return false;"
                                                    onpaste="return false;">
                                            </div>
                                        </div>





                                        <!-- Due Amount -->
                                        <div class="form-group aside_system">
                                            <label class="col-md-4 control-label">Amount Due :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">RM</div>
                                                <input tabindex="-1" value="" type="number" min="0"
                                                    step="any" class="form-control amountDue only-number"
                                                    name="due_amount" id="amountDue" placeholder="Amount Due"
                                                    ondrop="return false;" onpaste="return false;" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">




                                        <!-- Submit Button -->
                                        <div class="col-md-8 col-sm-8 pull-right">
                                            <div class="form-group">
                                                <button type="submit" name="draft" class="btn btn-primary"
                                                    style="width: 100%;">Confirm</button>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                            </div>


                        </form>
                        <!-- End Form -->


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/guest_filter.js') }}"></script>

    <script>
        function requestUrl(urlParts) {
            urlParts = $.extend({
                basePath: '{{ url('/') }}/',
                path: '',
                param: ''
            }, urlParts);
            return urlParts.basePath + urlParts.path + urlParts.param;
        }


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
                    $('#room_number').val(data['room_number']);
                    $('#booking_number').val(data['booking_number']);
                    if (data['status'] == 0) {
                        $('#room_number').append('<p class="text-danger text-center">NOT FOUND</p>');
                    }
                },
                complete: function(data) {
                    $("body").css("cursor", "default");
                }
            });
        }


        function patientFilter() {
            patientWithIndoor('indoor');

        }

        $(document).on('focus', '#invoice_no', function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.getJSON('/bar/get-product-by-sale-invoice', {
                            invoice_no: request.term
                        },
                        function(data) {
                            response($.map(data, function(items) {
                                return {
                                    value: items.invoice_no,
                                    label: `${items.invoice_no}`,

                                    data: items,
                                }
                            }))
                        }
                    )
                },
                select: function(event, ui) {

                    $('#guest_name').val(ui.item.data.guest_name)
                    $('#hotel_guest_id').val(ui.item.data.hotel_guest_id)
                    getProducts(ui.item.data.invoice_no);
                },
                search: function(event, ui) {},
                minLength: 1,
                autoFocus: true
            })
        })




        function getProducts(invoice) {
            $.ajax({
                type: 'get',
                url: '/bar/get-saleable-products',
                async: true,
                data: {
                    invoice_no: invoice
                },

                beforeSend: function() {

                    $("body").css("cursor", "progress");

                },
                success: function(data) {

                    $('#product-details').html(data);

                    calculateTotal();
                },
                complete: function(data) {
                    $("body").css("cursor", "default");
                }
            });
        }


        $(document).on('focus', '#drug-name', function() {
            // if ($('#guest_name').val() == '') {
            //     warning('toster', 'Please select Guest/Customer')

            //     return false;
            // }
            $(this).autocomplete({

                source: function(request, response) {
                    $.getJSON('/bar/get-saleable-products', {
                            invoice_no: $('#invoice_no').val(),
                            // guest_name: $('#guest_name').val(),
                        },
                        function(data) {

                            response($.map(data[0].items, function(items) {
                                return {
                                    value: items.product.name,
                                    label: `${items.product.name}`,
                                    data: items,
                                }
                            }))
                        }
                    )
                },
                select: function(event, ui) {

                    // appendProductRow(ui, 'product')
                },
                search: function(event, ui) {},
                minLength: 1,
                autoFocus: true
            })
        })



        // $(function() {
        //     $("#drug-name").focus();
        // });



        $(document).on('click', '.delete', function(e) {
            $(this).parents('tr').remove();
            calculateTotal();
        })
    </script>


    {{-- <script>
        function appendProductRow(ui, type = 'invoice') {
            productRow(ui, 'product')

            // $('#guest_name').val(ui.item.data.guest_name);
            // $('#customer_id').val(ui.item.data.guest_id);

            setTimeout(function() {
                $('#drug-name').val('');
            }, 1)

            calculateTotal();

        }

        function productRow(ui, type) {
            let html = ''
            if (type == 'product') {
                let item = ui.item.data;
                html +=
                    `<tr class="product-row">
                        <td>
                            <span id="product_name">${item.product.name}</span>
                            <input type="hidden" value="${item.product.id}" name="product_ids[]" id="productId">
                            <input type="hidden" value="${ui.item.data.sale_id}" name="sale_ids[]" >
                        </td>
                        <td>
                        <span class="returnable_qty">${item.quantity}</span>
                        </td>
                        <td>
                            <input type="text" value="${item.quantity}" min="1" max="${item.quantity}" name="return_quantity[]" class="form-control only-number return-qty"autocomplete="off">

                        </td>
                        <td>
                            <input type="number" min="1"  name="product_cost[]" value="${item.product.sale_price}" step="any" id="sales_qty" class="form-control product_cost" autocomplete="off" onkeyup="" onpaste="return false;" placeholder="Quantity">
                        </td>
                        <td>
                            <input type="number" min="1" name="total_amount[]" value="${Number(item.product.sale_price) * Number(item.quantity)}" class="form-control total_cost" autocomplete="off" onkeyup="" onpaste="return false;" placeholder="Quantity">
                        </td>

                        <td>
                            <button class="btn btn-xs btn-danger delete" tabindex="-1" type="button"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>`
            } else {
                let data = ui.item.data.items;

                data.map(function(item, index) {

                    html +=
                        `<tr class="product-row">
                            <td>
                                <span id="product_name">${item.product.name}</span>
                                <input type="hidden" value="${item.product.id}" name="product_ids[]" id="productId">
                                <input type="hidden" value="${ui.item.data.id}" name="sale_ids[]" >
                            </td>
                            <td>
                            <span class="returnable_qty">${item.quantity}</span>
                            </td>
                            <td>
                                <input type="text" value="${item.quantity}" min="1" max="${item.quantity}" name="return_quantity[]" class="form-control only-number return-qty"autocomplete="off">

                            </td>
                            <td>
                                <input type="number" min="1"  name="product_cost[]" value="${item.product.sale_price}" step="any" id="sales_qty" class="form-control product_cost" autocomplete="off" onkeyup="" onpaste="return false;" placeholder="Quantity">
                            </td>
                            <td>
                                <input type="number" min="1" name="total_amount[]" value="${Number(item.product.sale_price) * Number(item.quantity)}" class="form-control total_cost" autocomplete="off" onkeyup="" onpaste="return false;" placeholder="Quantity">
                            </td>

                            <td>
                                <button class="btn btn-xs btn-danger delete" tabindex="-1" type="button"><i class="fa fa-trash-o"></i></button>
                            </td>
                        </tr>`

                })
            }

            $('#product-details').append(html);

        }
    </script> --}}

    <script>
        $(document).on('keyup focus', '.return-qty, .discount, #amountPaid , .total_cost, .product_cost , .item_discount',
            function(e) {

                calculateTotal(e);
            });




        //<-- calculateTotal  -->//
        function calculateTotal(e) {
            var row = $(e.target).closest('.product-row'); // Get the current product row

            var return_qty = Number(row.find('.return-qty').val()); // Get the return quantity value
            var product_cost = Number(row.find('.product_cost').val()); // Get the product cost value
            var item_discount = Number(row.find('.item_discount').val()); // Get the product cost value

            var total_cost = return_qty * product_cost - item_discount; // Calculate the total cost

            row.find('.total_cost').val(total_cost); // Update the total cost field in the current row

            var subTotal = linePrice();
            var total = grandTotal(subTotal);

            // Call the dueAmount function if needed
            // dueAmount(payableAmount);
        }



        //<--Return Amount-->//
        $('#returnAmount').keyup(function() {
            let amount = Number($(this).val());
            let grandTotal = Number($('#grandTotal').val());


            if (amount > grandTotal) {

                amount = grandTotal;

                $('#returnAmount').val(grandTotal)
            }
            amount = Number(grandTotal - amount)
            $('#amountDue').val(amount)

        })


        //<--linePrice-->//
        function linePrice() {
            var rows = $('.product-row')
            totalDrugPrice = 0, amount = 0;

            $.each(rows, function(index, row) {
                amount += Number($(row).find('.total_cost').val())
                // amount += totalDrugPrice;
            });


            $('#total_amount').val(Math.round(amount).toFixed(2));
            // $('#grandTotal').val(Math.round(amount).toFixed(2));

            return Math.round(amount).toFixed(2);
        }

        // <--discount--> //
        function discount(stotal) {
            var discount = parseFloat($('.discount').val()),
                subTotal = parseFloat(stotal);
            if (discount > subTotal) {
                $('.discount').val(0);
                alert('You can\'t pay more than grand total.');
            }
            var total = (subTotal - discount).toFixed(2)
            $('#total').val(total);
            return total;
        }

        function grandTotal(total) {
            $('#grandTotal').val(parseFloat(total).toFixed(2));
            return total;
        }

        function dueAmount(payableAmount) {
            let paidAmount = parseFloat($('#amountPaid').val());
            if (!paidAmount) paidAmount = 0;
            if (paidAmount >= payableAmount) {
                $('#change').val(paidAmount - payableAmount);
                $('#amountDue').val(0);
            } else {
                $('#change').val(0);
                $('#amountDue').val(payableAmount - paidAmount);
            }
        }
    </script>


@endsection
