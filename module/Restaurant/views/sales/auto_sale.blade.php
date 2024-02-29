@extends('layout.app')
@push('style')
    <style>
        input.form-control.small-box {
            height: 28px;
            padding: 5px;
            border: 1px solid rgba(204, 204, 204, 0.51) !important;
        }

        .table tbody tr td {
            padding: 3px;
        }

    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @can('pharmacy-sales-list')
                        <div class="panel-heading-btn pull-right">
                            <a class="btn btn-success btn-sm" href="{{ route('inventory-product-sales.index') }}">View All</a>
                        </div>
                    @endcan
                    <h4 class="panel-title">Item Sales</h4>
                </div>
                <div class="panel-body no-padding">
                    <div class="row">
                        @forelse ($prescriptions as $prescription)
                            <div class="col-md-2">
                                <div class="checkbox">
                                    <label>
                                        <input type="radio" class="checkboxpatientId" name="checkboxpatientId"
                                            data-id="{{ $prescription->pId }}" value=""> {{ $prescription->name ?? '' }}
                                    </label>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="panel-body no-padding" id="tabArea">
                    <form method="POST" action="{{ route('inventory-product-sales.store') }}" accept-charset="UTF-8"
                        class="form-horizontal sales-form" role="form" data-parsley-validate novalidate>
                        @csrf
                        <div class="col-md-12">
                            <div class='row'>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Patient Name :</label>
                                                <input type="text" name="patient_name" id="patient_name"
                                                    placeholder="Patient/Customer's Name" class="form-control" required>
                                                <input type="hidden" name="customer_id" id="customer_id">
                                                <input type="hidden" name="prescription_id" id="prescription_id">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Invoice ID #</label>
                                                <input type="text" tabindex="-1" class="form-control" id="invoice_id"
                                                    placeholder="Invoice ID" name="invoice_id"
                                                    value="{{ $invoice_id + 1 }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Date :</label>
                                                <input type="text" value="{!! date('d/m/Y') !!}" class="form-control"
                                                    readonly autocomplete="off">
                                                <input type="hidden" name="date" value="{!! date('Y-m-d') !!}">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- transition -->

                            <div class="view_center_folwchart">
                                <div class='row'>
                                    <div class='col-md-8'>
                                        <table class="table table-bordered table-hover" id="table_auto">
                                            <thead>
                                                <tr>
                                                    <th width="15%"> Available Qty </th>
                                                    <th> Product Name </th>
                                                    <th width="15%"> Quantity </th>
                                                    <th width="15%"> Sales Price </th>
                                                    <th width="18%"> Total </th>
                                                    <th width="1%">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="product-details">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class='col-md-4'>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Sub Total :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">RM</div>
                                                <input tabindex="-1" value="0" type="number" min="0" name="subtotal"
                                                    step="any" class="form-control" id="subTotal" placeholder="Sub Total"
                                                    ondrop="return false;" onpaste="return false;" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Discount :</label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">RM</div>
                                                <input value="0" type="number" min="0" step="any"
                                                    class="form-control
                                            changesNo discount"
                                                    name="discount" id="discount" placeholder="Discount"
                                                    ondrop="return false;" tabindex="-1">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><b>Total :</b></label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">RM</div>
                                                <input tabindex="-1" value="0" type="number" min="0" step="any"
                                                    class="form-control" name="total_amount" id="total"
                                                    placeholder="Total Amount" ondrop="return false;"
                                                    onpaste="return false;" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><b> Grand Total: </b></label>
                                            <div class="input-group col-md-8">
                                                <div class="input-group-addon currency">RM</div>
                                                <input tabindex="-1" value="" type="number" min="0" step="any"
                                                    class="form-control" name="grand_total" id="grandTotal"
                                                    placeholder="Total Amount" ondrop="return false;"
                                                    onpaste="return false;" readonly>
                                            </div>
                                        </div>
                                        <div id="payment">
                                            <div class="form-group aside_system">
                                                <label class="col-md-4 control-label">Paid Amount :</label>
                                                <div class="input-group col-md-8">
                                                    <div class="input-group-addon currency">RM</div>
                                                    <input value="0" type="number" min="0" step="any" class="form-control"
                                                        name="paid_amount" id="amountPaid" placeholder="Paid Amount"
                                                        ondrop="return false;" onpaste="return false;">
                                                </div>
                                            </div>
                                            <div class="form-group aside_system">
                                                <label class="col-md-4 control-label">Change :</label>
                                                <div class="input-group col-md-8">
                                                    <div class="input-group-addon currency">RM</div>
                                                    <input tabindex="-1" value="" type="number" min="0" step="any"
                                                        class="form-control change" name="change_amount" id="change"
                                                        placeholder="Change Amount" ondrop="return false;"
                                                        onpaste="return false;" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group aside_system">
                                                <label class="col-md-4 control-label">Amount Due :</label>
                                                <div class="input-group col-md-8">
                                                    <div class="input-group-addon currency">RM</div>
                                                    <input tabindex="-1" value="" type="number" min="0" step="any"
                                                        class="form-control amountDue" name="due_amount" id="amountDue"
                                                        placeholder="Amount Due" ondrop="return false;"
                                                        onpaste="return false;" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8 col-sm-8 pull-right">
                                                <div class="form-group">
                                                    <button type="submit" name="draft" class="btn btn-primary"
                                                        style="width: 100%;">Confirm</button>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 pull-right">
                                                <div class="from-group">
                                                    <button type="submit" name="confirm" class="btn btn-success"
                                                        style="width: 100%">Draft</button>
                                                </div>
                                            </div>
                                            <div class="col-md-6"></div>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <br>
                                <br>
                                <div class='row'>
                                    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5  pull-right">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ asset('custom_js/loadDetails.js') }}"></script>
    <script>
        $('.checkboxpatientId').click(function() {
            let patientId = $(this).data('id');
            $('.checkboxpatientId').not(this).prop('checked', false);
            $.ajax({
                type: "get",
                url: `{{ route('load_selable_product_as_patient') }}`,
                data: {
                    id: patientId
                },
                success: function(datas) {

                    setPatientInfo(datas.getPatientInfo)
                    $('#product-details').html('');
                    let extra = datas.allMedicineDetials[0].medicines;
                    $.each(datas.inventoryproduct, function(index, values) {

                        $('#product-details').append(
                            productRow(values, extra, index)
                        );
                    });
                }
            });

        })

        function setPatientInfo(infos) {
            $('#patient_name').val(infos.patient.name);
            $('#customer_id').val(infos.patient.id);
            $('#prescrtion_id').val(infos.id);
        }

        loadDetails({
            type: 'name',
            selector: '#patient_name',
            url: '{{ '/loadPatient' }}',
            select: function(event, ui) {
                $('#customer_id').val(ui.item.data.id);
            },
            search: function() {
                if ($('#customer_id').val()) {
                    $('#patient_name').val('');
                }
                $('#customer_id').val('');

            }
        })
    </script>
    <script>
        $(document).on('focus', '#drug-name', function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.getJSON('/load-saleable-products', {
                            name: request.term
                        },
                        function(data) {
                            response($.map(data, function(items) {
                                return {
                                    value: items.available_quantity,
                                    label: `${items.name} - (${items.available_quantity})`,
                                    data: items,
                                }
                            }))
                        }
                    )
                },
                select: function(event, ui) {
                    appendProductRow(ui)

                },
                search: function(event, ui) {


                },
                minLength: 1,
                autoFocus: true
            })
        })



        $(function() {
            $("#drug-name").focus();
        });



        $(document).on('click', '.delete', function(e) {
            $(this).parents('tr').remove();
            calculateTotal();
        })
    </script>


    <script>
        function appendProductRow(ui) {
            if (!ui.item.data.retail_quantity == 0) {

                $('#product-details').append(
                    productRow(ui)
                );
            } else {
                alert('Product is not available in stock');
            }

            setTimeout(function() {
                $('#drug-name').val('');
            }, 1)
        }


        function productRow(ui, extra, index) {

            let html =
                `<tr class="product-row">
                        <td>
                            <input type="text" tabindex="-1" value="${ui.available_quantity}"
                                name="available_qty[]" id="available_qty" class="form-control small-box avaliable_qty"
                                ondrop="return false;" onpaste="return false;" readonly>
                            <input type="hidden" tabindex="-1" value="${ui.available_quantity}" name="old_available_qty[]" id="old_available_qty">
                        </td>
                        <td>
                            <span id="product_name">${ui.name}</span>
                            <input type="hidden" value="${ui.id}" name="product_id[]" id="productId"
                            class="form-control small-box productId" autocomplete="off"> </br>
                            <span>Dose : (${extra[index].dose.name}) | Duration : (${extra[index].duration.name})</span>
                        </td>
                        <td>
                            <input type="number" min="1" value="0"  name="sales_qty[]" max="${ui.available_quantity}" step="any" id="sales_qty"
                            class="form-control sales-qty small-box" autocomplete="off" onkeyup="" onpaste="return false;" placeholder="Quantity">
                        </td>
                        <!--td><span id="uom"></span> </td-->
                        <td>
                            <input tabindex="-1" type="text" value="${parseFloat(ui.retail_sales_price).toFixed(2)}" name="sales_price[]" class="form-control sales-price small-box" autocomplete="off" ondrop="return false;" onpaste="return false;" readonly>
                        </td>
                        <td>
                            <input tabindex="-1" type="number" min="0" value="${parseFloat(ui.retail_sales_price).toFixed(2)}" step="any" name="item_price[]" class="form-control total-line-price small-box" autocomplete="off"  ondrop="return false;" onpaste="return false;" readonly="readonly">
                        </td>
                        <td>
                            <button class="btn btn-xs btn-danger delete" tabindex="-1" type="button"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>`
            return html;
        }
    </script>

    <script>
        $(document).on('keyup focus', '.sales-qty, .discount, #amountPaid', function(e) {

            calculateTotal();
        });
        //<-- calculateTotal  -->//
        function calculateTotal() {
            var subTotal = linePrice(),
                total = discount(subTotal),
                payableAmount = grandTotal(parseFloat(total));
            dueAmount(payableAmount);
        }

        //<--linePrice-->//
        function linePrice() {
            var rows = $('.product-row'),
                totalDrugPrice = 0,
                amount = 0;
            $.each(rows, function(index, row) {

                let available_qty = $(row).find('#available_qty').val()
                var quantity = $(row).find('.sales-qty').val(),
                    salesPrice = $(row).find('.sales-price').val(),
                    totalDrugPrice = (quantity * salesPrice)
                now_available_qty = available_qty - quantity;
                $(row).find('#available_qty').val(now_available_qty);
                console.log(available_qty);
                if (!quantity || !salesPrice)
                    quantity = 0, salesPrice = 0;
                $(row).find('.total-line-price').val(parseFloat(totalDrugPrice).toFixed(2));
                amount += totalDrugPrice;
            });

            $('#subTotal').val(parseFloat(amount).toFixed(2));
            return parseFloat(amount).toFixed(2);
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
