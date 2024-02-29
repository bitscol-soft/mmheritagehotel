@extends('layouts.master')


@section('title', 'Add New Sale Return')


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
                        <a href="{{ route('rst.sale-returns.index') }}">
                            <i class="ace-icon fa fa-list-alt"></i>
                            Sale Return List
                        </a>
                    </span>
                </div>






                <!-- Body -->
                <div class="widget-body">
                    <div class="widget-main">




                        <!-- Form -->
                        <form method="POST" action="{{ route('rst.sale-returns.store') }}" accept-charset="UTF-8"
                            class="form-horizontal sales-form" role="form" data-parsley-validate novalidate>
                            @csrf


                            <div class="col-md-12">

                                {{-- <div class='row'>



                                    <!-- Search Patient Name -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">Patient Name :</label>

                                            <input type="hidden" name="customer_id" id="customer_id">

                                            <input type="text" name="patient_name" id="patient_name"
                                                placeholder="Patient/Customer's Name" class="form-control" required>
                                        </div>
                                    </div>



                                    <!-- Ward -->
                                    <div class="col-md-2 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Ward :</label>
                                            <input type="text" name="patient_ward" id="patient-ward" placeholder="Ward"
                                                class="form-control">
                                        </div>
                                    </div>



                                    <!-- Cabin -->
                                    <div class="col-md-2 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Cabin :</label>
                                            <input type="text" name="patient_cabin" id="patient-cabin" placeholder="Cabin"
                                                class="form-control">
                                        </div>
                                    </div>



                                    <!-- Sale Invoice ID -->
                                    <div class="col-md-2 ml-1">
                                        <div class="form-group">
                                            <label class="control-label">Invoice ID #</label>
                                            <input type="text" tabindex="-1" class="form-control" id="invoice_id"
                                                placeholder="Invoice ID" name="invoice_no" value="{{ $invoice_id }}"
                                                readonly>
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

                                </div> --}}





                                <div class="row">

                                    <!-- Product Name -->
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label">Invoice No :</label>
                                            <input type="text" name="invoice_no" id="invoice_no" class="form-control"
                                                placeholder="Search by Sale Invoice No" autocomplete="off">
                                        </div>
                                    </div>

                                    <!-- Return Date -->
                                    <div class="col-md-2 col-md-offset-1">
                                        <div class="form-group">
                                            <label class="control-label">Date :</label>
                                            <input type="text" name="date" value="{{ date('Y-m-d') }}"
                                                class="form-control date-picker" autocomplete="off">
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
                                            <input tabindex="-1" value="0" type="number" min="0" name="subtotal" step="any"
                                                class="form-control" id="total_amount" placeholder="Total Amount"
                                                ondrop="return false;" onpaste="return false;" readonly>
                                        </div>
                                    </div>




                                    <!-- Previous Due -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><b>Previous Due :</b></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">RM</div>
                                            <input tabindex="-1" value="0" type="number" min="0" step="any"
                                                class="form-control" name="previous_due" id="total"
                                                placeholder="Previous Due" ondrop="return false;" onpaste="return false;"
                                                readonly>
                                        </div>
                                    </div>



                                    <!-- Grand Total -->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label"><b> Grand Total: </b></label>
                                        <div class="input-group col-md-8">
                                            <div class="input-group-addon currency">RM</div>
                                            <input tabindex="-1" value="" type="number" min="0" step="any"
                                                class="form-control" name="payable_amount" id="grandTotal"
                                                placeholder="Total Amount" readonly>
                                        </div>
                                    </div>





                                    <div id="payment">
                                        <!-- Return Amount -->


                                        <div class="form-group aside_system">
                                            <label class="col-md-4 control-label">Return Amount :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">RM</div>
                                                <input value="0" type="number" min="0" step="any" class="form-control"
                                                    name="return_amount" id="returnAmount" placeholder="Return Amount"
                                                    ondrop="return false;" onpaste="return false;">
                                            </div>
                                        </div>












                                        <!-- Due Amount -->
                                        <div class="form-group aside_system">
                                            <label class="col-md-4 control-label">Amount Due :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">RM</div>
                                                <input tabindex="-1" value="" type="number" min="0" step="any"
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
    <script src="{{ asset('assets/custom_js/patient_filter.js') }}"></script>


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




        $(document).on('focus', '#invoice_no', function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.getJSON('/pharmacy/get-product-by-sale-invoice', {
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
                    appendProductRow(ui)
                },
                search: function(event, ui) {},
                minLength: 1,
                autoFocus: true
            })
        })



        $(function() {
            $("#invoice-no").focus();
        });



        $(document).on('click', '.delete', function(e) {
            $(this).parents('tr').remove();
            calculateTotal();
        })
    </script>


    <script>
        function appendProductRow(ui) {
            productRow(ui)

            setTimeout(function() {
                $('#invoice_no').val('');
            }, 1)

            calculateTotal();

        }

        function productRow(ui) {
            let html = ''
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

            $('#product-details').append(html);

        }
    </script>

    <script>
        $(document).on('keyup focus', '.return-qty, .discount, #amountPaid', function(e) {

            calculateTotal(e);
        });


        //<-- calculateTotal  -->//
        function calculateTotal(e) {

            var subTotal = linePrice(),
                total = grandTotal(subTotal);
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
