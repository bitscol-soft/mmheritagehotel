@extends('layout.app')
@push('style')
    <style>
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
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel">
                <div class="panel-heading">
                    @can('pharmacy-purchase-list')
                    <div class="panel-heading-btn pull-right">
                        <a class="btn btn-success btn-sm" href="{{route('inventory-product-purchases.index')}}">
                            All Added Product
                        </a>
                    </div>
                    @endcan
                    <h4 class="panel-title">Purchase Product</h4>
                </div>
                <div class="panel-body no-padding">
                    <form method="POST" action="{{route('inventory-product-purchases.update', $inventoryProductPurchase)}}" >
                        @csrf
                        @method('patch')
                        <div class="col-md-12">
                            <div class='row'>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Manufacturer Name :</label>
                                                <input type="text" class="form-control"
                                                       value="{{$inventoryProductPurchase->manufacturer->name}}"
                                                       readonly="">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Challan Id :</label>
                                                <input type="text" tabindex="-1" class="form-control" name="challan_id"
                                                       value="{{$inventoryProductPurchase->challan_id}}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Date :</label>
                                                <input type="text" name="date" readonly
                                                       value="{{$inventoryProductPurchase->date->format('d/m/Y')}}"
                                                       class="form-control" autocomplete="off">
                                            </div>
                                        </div>

                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <table class="table table-bordered table-hover" id="table_auto">
                                                <thead>
                                                <th>Product Name</th>
                                                <th width="12%">Unit TP</th>
                                                <th width="12%">Sales Price</th>
                                                <th width="11%">Pack Size</th>
                                                <th colspan="2" width="10%">Qty</th>
                                                {{--<th width="10%">Unit Vat</th>--}}
                                                <th width="12%">Expiry D.</th>
                                                <th width="10%">Total TP</th>
                                                <th></th>
                                                </thead>

                                                <tbody class="products">
                                                @php($total = 0)
                                                @forelse($inventoryProductPurchase->items as $item)

                                                    <tr class="product-row">
                                                        <td>
                                                            <span>{{$item->product->name}}</span>
                                                            {{--<input type="text" id="drug-name" value="" class="form-control small-label-box drug-name"
                                                            placeholder="Product Name" autocomplete="off">--}}
                                                            <input type="hidden" value="{{$item->product_id}}"
                                                                   name="product_id[]" tabindex="-1"
                                                                   class="product-ids"  id="product_id">
                                                            <input type="hidden" value="{{$item->id}}" name="id[]"
                                                                   class="ids">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="unit_tp[]" min="0" step="any"
                                                                   tabindex="-1" autocomplete="off" ondrop="return false;"
                                                                   required value="{{$item->product->retail_unit_tp *
                                                                   $item->product->pack_size}}" id="unit_tp"
                                                                   class="form-control small-label-box unit-tps">

                                                            <input type="hidden" name="retail_unit_tp[]" min="0"
                                                                   value="{{$item->product->retail_unit_tp}}"
                                                                   class="retail-unit-tps">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="sales_price[]"  min="0"
                                                                   step="any" tabindex="-1" placeholder="Sales Price" required
                                                                   value="{{$item->product->retail_sales_price *
                                                                   $item->product->pack_size}}" class="form-control
                                                                   small-label-box sales-prices">
                                                            <input type="hidden" name="retail_sales_price[]" min="0" required class="retail-sales-prices"
                                                                   value="{{$item->product->retail_sales_price}}">
                                                        </td>

                                                        <td>
                                                            <input type="text" name="pack_size[]" min="0"
                                                                   tabindex="-1" value="{{$item->product->pack_size}}"
                                                                   step="any"
                                                                   class="form-control small-label-box pack-sizes" placeholder="Size" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="quantity[]" min="0"
                                                                   value="{{$item->quantity}}" autocomplete="off"
                                                                   class="form-control small-label-box quantities" placeholder="Qty"
                                                                   required>
                                                            <input type="hidden" name="retail_quantity[]"
                                                                   value="{{$item->quantity * $item->pack_size}}"
                                                                   min="0" class="retail-quantities" >
                                                        </td>
                                                        <td>
                                                            <span id="uom">{{$item->product->wholesaleUnit->name}}</span>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="expiry_date[]"
                                                                   value="{{date('Y-m-d', strtotime($item->expiry_date))}}"
                                                                   class="form-control small-label-box expire-dates"
                                                                   placeholder="Expiry Date" autocomplete="off">
                                                        </td>
                                                        <td>
                                                            <input value="{!!$amount = $item->quantity *
                                                            $item->unit_tp!!}"
                                                                   name="total_price[]" type="number"
                                                                   min="0" step="any" autocomplete="off" tabindex="-1"
                                                                   placeholder="Sub Total" class="form-control
                                                                   small-label-box  total-line-prices" readonly>
                                                        </td>
                                                        <td class="text-center">
                                                            <button class="btn btn-xs btn-danger delete-product"
                                                                    type="button"> <i class="fa fa-trash-o"></i></button>
                                                        </td>
                                                    </tr>

                                                    <?php
                                                        $total +=  $amount

                                                    ?>
                                                @empty
                                                    <h5 class="text-danger text-center">No Product Found</h5>
                                                @endforelse

                                                </tbody>
                                            </table>


                                        </div>
                                        <div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>
                                            <div class="form-group aside_system">
                                                <label class="col-md-3">Sub Total :</label>
                                                <div class="input-group col-md-9">
                                                    <div class="input-group-addon currency">৳</div>
                                                    <input tabindex="-1" value="{{round($total) }}" type="number" min="0" step="any"
                                                           class="form-control" name="subtotal" id="subTotal" placeholder="Sub
                                               Total"readonly>
                                                </div>
                                            </div>
                                            <div class="form-group aside_system">
                                                <label class="col-md-3">Discount:</label>
                                                <div class="input-group col-md-9">
                                                    <div class="input-group-addon currency">৳</div>
                                                    <input tabindex="-1"
                                                           value="{{$discount = $inventoryProductPurchase->discount}}"
                                                           type="number" min="0" step="any"
                                                           class="form-control" name="discount" id="discount" placeholder="Discount">
                                                </div>
                                            </div>
                                            <div class="form-group aside_system">
                                                <label class="col-md-3">Grand Total :</label>
                                                <div class="input-group col-md-9">
                                                    <div class="input-group-addon currency">৳</div>
                                                    <input tabindex="-1" value="{{$inventoryProductPurchase->totalAmount}}"
                                                           type="number" min="0" step="any"
                                                           class="form-control"  name="grand_total" id="grandTotal"
                                                           placeholder="Grand Total" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group aside_system">
                                                <label class="col-md-3">Total Paid :</label>
                                                <div class="input-group col-md-9">
                                                    <div class="input-group-addon currency">৳</div>
                                                    <input value="{{$inventoryProductPurchase->paid_amount}}" type="number"
                                                           min="0" step="any" class="form-control" name="paid_amount"
                                                           id="totalPaid" placeholder="Total Paid">
                                                </div>
                                            </div>
                                            <div class="form-group aside_system">
                                                <label class="col-md-3">Total Due:</label>
                                                <div class="input-group col-md-9">
                                                    <div class="input-group-addon currency">৳</div>
                                                    <input tabindex="-1" value="{{$inventoryProductPurchase->totalAmount - $inventoryProductPurchase->paid_amount}}"
                                                           type="number" min="0" step="any"
                                                           class="form-control" name="due_amount" id="totalDue"
                                                           placeholder="Total Due" readonly>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-8 col-sm-8 pull-right">
                                                    <div class="form-group">
                                                        <button type="submit" name="draft" class="btn btn-primary" style="width: 100%;">Confirm</button>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-4 pull-right">
                                                    <div class="from-group">
                                                        <button type="submit" name="confirm" class="btn btn-success" style="width: 100%">Draft</button>
                                                    </div>
                                                </div>
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


                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(document).on('focus', '.purchaseDate, .expireDate, .add-expire-date', function () {
            $(this).datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true
            });
        })
    </script>

    <script src="{{asset('custom_js/loadDetails.js')}}"></script>

    <script>
        loadDetails({
            selector: '#drug-name',
            url: '{{'/load-drug'}}',
            select: function (event, ui) {
                // console.log(ui.item.data);
                $('.add-product-id').val(ui.item.data.id);
                $('.drug-name').val(ui.item.data.name);
            },
            // search: function () {
            //     $('.add-product-id').val('');
            // setTimeout(function() {
            //     $('#drug-name').val('');
            // }, 1)
            // }

        });

    </script>


    <script>
        $('#manufacturer_id').change(function () {
            let id = $(this).val();
            if (id){
                $.ajax({
                    url: '{{'restaurant/get-purchasable-products'}}/'+id,
                    datatype: 'HTML',

                }).done(function(data){
                    $('.products').html(data)
                })
            }

        })
    </script>


    <script>
        $(document).on('focus keyup', '.quantities, .pack-sizes, .unit-tps, .sales-prices, #discount, #totalPaid',
            function (e) {

                // row(e).find('.total-line-prices').val(totalLinePrice(e))

                row(e).find('.retail-unit-tps').val(retailConversion(e).retailUnitCost)
                row(e).find('.retail-sales-prices').val(retailConversion(e).retailSalesPrice)
                row(e).find('.retail-quantities').val(retailConversion(e).quantities)
                row(e).find('.total-line-prices ').val(retailConversion(e).linePrice)

                $('#subTotal').val(subTotal(e));

            })

        function retailConversion(e)
        {
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

        function subTotal(e)
        {
            let rows = $('.product-row'),
                amount = 0;
            $.each(rows, function (i, row) {
                let linePrice = $(row).find('.total-line-prices').val();
                if(!linePrice) linePrice = 0
                amount += parseFloat(linePrice)
            });

            // discount(amount)
            let discount = amountManipulation({
                getTargetSelector: '#discount',
                putTargetSelector: '#grandTotal',
                subTotal: amount
            })
            // Paid Amount
            amountManipulation({
                getTargetSelector: '#totalPaid',
                putTargetSelector: '#totalDue',
                subTotal: discount
            })
            return (Math.round(amount)).toFixed(2);
        }

        function amountManipulation(settings)
        {
            let amount = $(settings.getTargetSelector).val()

            if (amount > settings.subTotal){
                alert('You can\'t more than that amount')
                $(settings.getTargetSelector).val(0)
                return 0
            }else{
                let gerandtotal = (Math.round(settings.subTotal - amount)).toFixed(2)
                $(settings.putTargetSelector).val(gerandtotal);
                return gerandtotal;
            }
        }


        function row(e)
        {
            return $(e.target).parents('.product-row')
        }

        $(document).on('focus keyup', '.expire-dates', function (e) {
            $(this).datepicker({
                dateFormat: 'yy-mm-dd'
            });

            if (e.keyCode === 13) {
                $('.expire-dates').next().focus();


            }
        })

    </script>

    <script>
        $(document).on('click', '.delete-product', function (e) {
            let confirmed = confirm('Are you sure want to delete?')
            if (confirmed){
                $(this).parents('.product-row').remove()
                let product = $(this).parents('.product-row').find('.ids').val()
                let url = '{{url('/')}}/product-purchases/'+product+'';
                if (product){
                    $.ajax({
                        method: 'DELETE', url, data : {"_token":"{{ csrf_token() }}"},
                    })
                }

            }
        })
    </script>


@endsection
