@extends('layouts.master')
@section('title', 'Purchase Create')

@push('style')
    <style>
        .ui-autocomplete {
            z-index: 1999 !important;
        }

        .datepicker {
            z-index: 1999 !important;
        }

        input.form-control.small-box {
            height: 28px;
        }

        input.form-control.small-label-box {
            height: 24px;
            padding: 1.5px;
            border: 1px solid rgba(204, 204, 204, 0.51) !important;
        }

        .table tbody tr td {
            padding: 2px;
        }

    </style>
@endpush
@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-purchase-form mm-rst" title="New Purchase">
        <x-slot name="actions">
            @if (hasPermission('pharmacy.view', $slugs))
                <a href="{{ route('rst.purchases.index') }}" class="btn btn-sm btn-default">
                    <i class="ace-icon fa fa-list-alt"></i> All Purchased Products
                </a>
            @endif
        </x-slot>

        <x-mm.panel>
                    <div>



                        @include('partials._alert_message')


                        <form method="POST" action="{{ route('rst.purchases.store') }}">
                            @csrf
                            <div class="col-md-12">
                                <div class='row'>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Suppliers Name :</label>
                                            <select class="form-control chosen-select" name="supplier_id" id="supplier-id"
                                                data-placeholder="Choose Supplier">
                                                <option value=""></option>
                                                @foreach ($suppliers as $key => $supplier)
                                                    <option value="{{ $supplier->id }}">
                                                        {{ $supplier->name . '-' . $supplier->id }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Account :</label>
                                            <select class="form-control chosen-select" name="account_id" id="account_id"
                                                data-placeholder="Choose Account">
                                                <option value=""></option>
                                                @foreach ($accounts as $key => $account)
                                                    <option value="{{ $account->id }}">
                                                        {{ $account->name . '-' . $account->id }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Challan Id :</label>
                                            <input type="text" tabindex="-1" class="form-control" name="challan_id"
                                                value="{{ $challan_id ?? date('Ymd') . 1 }}">
                                        </div>
                                    </div>


                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">Date :</label>
                                            <input type="text" name="date" readonly value="{!! date('Y-m-d') !!}"
                                                class="form-control" autocomplete="off">
                                        </div>
                                    </div>

                                </div>
                                <div class="row"> <br>
                                    <div class="col-md-8">

                                        <table class="table table-bordered table-hover" id="table_auto">
                                            <thead>
                                                <th>Product Name</th>
                                                <th width="12%">Unit Cost</th>
                                                <th width="12%">Sales Price</th>
                                                <th width="10%">Qty</th>
                                                <th colspan="2" width="14%">Unit VAT</th>
                                                <th width="10%">Total</th>
                                            </thead>

                                            <tbody class="products">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group aside_system">
                                            <label class="col-md-5 control-label">Sub Total</label>
                                            <div class="input-group col-md-7">
                                                <div class="input-group-addon currency">৳</div>
                                                <input tabindex="-1" value="0" type="number" min="0" step="any"
                                                    class="form-control" name="subtotal" id="subTotal" placeholder="Sub
                                                                                            Total" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group aside_system">
                                            <label class="col-md-5">Discount</label>
                                            <div class="input-group col-md-7">
                                                <div class="input-group-addon currency">৳</div>
                                                <input tabindex="-1" value="0" type="number" min="0" step="any"
                                                    class="form-control" name="discount" id="discount"
                                                    placeholder="Discount">
                                            </div>
                                        </div>
                                        <div class="form-group aside_system">
                                            <label class="col-md-5">Total VAT</label>
                                            <div class="input-group col-md-7">
                                                <div class="input-group-addon currency">৳</div>
                                                <input type="number" min="0" class="form-control"
                                                    onkeyup="totalCalculation()" name="total_vat" id="vat">
                                            </div>
                                        </div>
                                        <div class="form-group aside_system">
                                            <label class="col-md-5">Grand Total</label>
                                            <div class="input-group col-md-7">
                                                <div class="input-group-addon currency">৳</div>
                                                <input tabindex="-1" value="" type="number" min="0" step="any"
                                                    class="form-control" name="grand_total" id="grandTotal"
                                                    placeholder="Grand Total" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group aside_system">
                                            <label class="col-md-5 control-label">Total Paid</label>
                                            <div class="input-group col-md-7">
                                                <div class="input-group-addon currency">৳</div>
                                                <input value="0" type="number" min="0" step="any" class="form-control"
                                                    name="paid_amount" onkeyup="totalCalculation()" id="totalPaid"
                                                    placeholder="Total Paid">
                                            </div>
                                        </div>
                                        <div class="form-group aside_system">
                                            <label class="col-md-5">Total Due</label>
                                            <div class="input-group col-md-7">
                                                <div class="input-group-addon currency">৳</div>
                                                <input tabindex="-1" value="" type="number" min="0" step="any"
                                                    class="form-control" name="due_amount" id="totalDue"
                                                    placeholder="Total Due" readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 col-sm-8 pull-right">
                                                <div class="form-group text-right">
                                                    <button type="submit" name="draft" class="btn btn-sm btn-primary"
                                                        style="width: 80%;">Confirm</button>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-4 col-sm-4 pull-right">
                                                    <div class="from-group">
                                                        <button type="submit" name="confirm" class="btn btn-success" style="width: 100%">Draft</button>
                                                    </div>
                                                </div> --}}
                                        </div>

                                    </div>
                                </div>
                            </div>
                    </div>
                    <br>
                    <hr>
                    <div class='row'>
                        <div class='col-xs-12 col-sm-7 col-md-7 col-lg-7'>


                        </div>

                        <div class='col-xs-12 col-sm-5 col-md-5 col-lg-5'>

                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6"></div>
                    </div>
                </div>
                </form>
        </x-mm.panel>
    </x-mm.page>
@endsection

@section('script')

    <script>
        $(document).ready(function() {
            loadDetails({
                type: 'batchNumber',
                selector: '#batch-number',
                url: "/restaurant/inventory/get-product-batches",
                select: function(event, ui) {
                    $('.add-product-id').val(ui.item.data.id);
                    $('.drug-name').val(ui.item.data.batch_number);
                },
            })
        })



        $(document).on('focus', '.purchaseDate, .add-expire-date', function() {
            $(this).datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true
            });
        })




        $(document).on('focus', '.expire-dates', function() {
            $(this).datepicker({
                dateFormat: "yy-mm-dd",
                defaultDate: "+2y",
                changeMonth: true,
                changeYear: true
            });
        })


        loadDetails({
            selector: '#drug-name',
            url: "/pharmacy/get-drugs",
            select: function(event, ui) {
                $('.add-product-id').val(ui.item.data.id);
                $('.drug-name').val(ui.item.data.name);
            }

        });



        $('#supplier-id').change(function() {
            let id = $(this).val();
            if (id) {
                $.ajax({
                    url: "/restaurant/get-purchasable-products/" + id,
                    // datatype: 'JSONP',

                }).done(function(data) {
                    console.log(data);
                    $('.products').html(data)
                })
            }

        })



        $(document).on('focus keyup', '.quantities, .pack-sizes, .unit-tps, .sales-prices, #discount, #totalPaid',
            function(e) {

                row(e).find('.retail-unit-tps').val(retailConversion(e).retailUnitCost)
                row(e).find('.retail-sales-prices').val(retailConversion(e).retailSalesPrice)
                row(e).find('.retail-quantities').val(retailConversion(e).quantities)
                row(e).find('.total-line-prices ').val(retailConversion(e).linePrice)
                $('#subTotal').val(subTotal(e));

            })

        function retailConversion(e) {
            let unitCost = parseFloat(row(e).find('.unit-tps').val())
            let salesPrice = parseFloat(row(e).find('.sales-prices').val())
            let packSize = parseFloat(row(e).find('.pack-sizes').val())
            let quantity = parseFloat(row(e).find('.quantities').val())

            if (!unitCost) unitCost = 0
            if (!salesPrice) salesPrice = 0
            if (!packSize) packSize = 0
            if (!quantity) quantity = 0

            return {
                retailUnitCost: unitCost / packSize,
                retailSalesPrice: salesPrice / packSize,
                quantities: packSize * quantity,
                linePrice: unitCost * quantity
            }

        }

        function subTotal(e) {
            let rows = $('.product-row'),
                amount = 0;
            $.each(rows, function(i, row) {
                let linePrice = $(row).find('.total-line-prices').val();
                if (!linePrice) linePrice = 0
                amount += Number(linePrice)
            });


            // discount(amount)
            let discount = amountManipulation({
                getTargetSelector: '#discount',
                putTargetSelector: '#grandTotal',
                subTotal: amount
            })

            // $('#totalDue').val(amount)


            return (Math.round(amount)).toFixed(2);
        }


        function subtotalVAT() {
            // let vat_row = $('.unit-vat'),
            var vat_amount = 0;
            // console.log(vat_row, vat_amount);\
            $('.unit-vat').each(function() {
                let vatPrice = Number($(this).val()) | 0;

                // if(!vatPrice) vatPrice = 0;

                vat_amount += vatPrice;

            });

            $('#vat').val((Math.round(vat_amount)).toFixed(2));


            let subTotal = Number($('#subTotal').val())

            grandTotal = subTotal + vat_amount
            due = subTotal + vat_amount


            $('#grandTotal').val(grandTotal);
            $('#totalDue').val(due);

        }

        function amountManipulation(settings) {
            let amount = $(settings.getTargetSelector).val()

            if (amount > settings.subTotal) {
                alert('You can\'t more than that amount')
                $(settings.getTargetSelector).val(0)
                return 0
            } else {
                let vat = Number($('#vat').val());

                if (vat) {
                    let gerandtotal = (Math.round(settings.subTotal - amount + vat)).toFixed(2)
                    $(settings.putTargetSelector).val(gerandtotal);
                    return gerandtotal;
                } else {
                    let gerandtotal = (Math.round(settings.subTotal - amount)).toFixed(2)
                    $(settings.putTargetSelector).val(gerandtotal);
                    return gerandtotal;
                }

            }
        }


        function row(e) {
            return $(e.target).parents('.product-row')
        }

        $(document).on('focus keyup', '.expire-dates', function(e) {
            $(this).datepicker({
                dateFormat: 'yy-mm-dd'
            });

            if (e.keyCode === 13) {
                $('.expire-dates').next().focus();


            }
        })



        function totalCalculation() {
            // Paid Amount
            let subtotal = Number($('#subTotal').val());
            let discount = Number($('#discount').val());
            let vat = Number($('#vat').val());
            let totalPaid = Number($('#totalPaid').val());


            let total = Number($('#grandTotal').val());
            let paid = Number($('#totalPaid').val());

            let due = ((subtotal - discount) + vat) - totalPaid;
            let grand_total = (subtotal - discount) + vat;


            $('#grandTotal').val(grand_total);


            if (due < 0) {
                warning('toster', 'You can not receive more than total amount');
                $('#totalPaid').val(total);
            }

            $('#totalDue').val(due);
        }
    </script>




@endsection
