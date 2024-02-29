<script>

    $(document).ready(function() {
        // $('#sidebar').addClass('menu-min');
    });

    /*
    ||============================================================
    ||      APPEND FROM LIVE SEARCH CLICK
    ||============================================================
    */
    function appendData(product, variation, isReadonly, stock, sku){

        let lot = product.stock_summaries;

        if(variation != 0){
            lot = variation.stock_summaries;
        }

        let lotOption = `<option value="">Select</option>`;

        $.each(lot, function(key, val){
            lotOption += `<option value="${ val.id }" data-supplier_id="${val.supplier_id}">${ val.lot != null && val.lot != '' ? val.lot : 'N/A' }</option>`;
        })

        console.log(stock);

        if(stock != 'null'){

            let tr = `<tr class="new-tr">

                    <th width="25%" style="position: relative;">
                        <input type="hidden" name="supplier_id[]" class="" value="${ product.supplier_id }">
                        <input type="hidden" class="product-is-variation" value="${ variation != '' ? "true" : "false" }">
                        <input type="hidden" class="product-is-measurement" value="${ product.product_measurements != '' ? "true" : "false" }">
                        <input type="hidden" class="products product-id" name="product_id[]" value="${ product.id }">
                        ${ product.name } &mdash; ${ product.code ?? '' }
                    </th>

                    <th width="15%">
                        ${ variation.name ?? '' }
                        <input type="hidden" name="product_variation_id[]" value="${ variation.id ?? '' }" class="form-control product-variation-id" readonly>
                    </th>

                    <th width="7%">
                        ${ variation != '' ? variation.sku : product.sku }
                        <input type="hidden" name="" id="sku[]" value="${ variation != '' ? variation.sku : product.sku }" class="form-control sku-code" readonly>
                    </th>

                    <th width="7%">
                        ${ product.unit_measure?.name }
                    </th>
                    <th width="10%">
                        <select class="form-control select2 lot-number" name="lot[]" onchange="getExpireDate2(this)" ${ product.is_lot_required == 'Yes' ? 'required' : ''}>
                            ${ lotOption }
                        </select>
                    </th>
                    <th width="10%">
                        <select name="expire_date[]"  class="form-control select2 expire-date" onchange="expireDateWiseInfo(this)">

                        </select>
                    </th>

                    <th width="10%">
                        <input type="text" name="purchase_price[]" id="purchase_price" class="form-control text-right only-number purchase-price" autocomplete="off" value="${ product.purchase_price }" required>
                    </th>
                    <th width="10%">
                        <input type="number" name="" id="current_stock" class="form-control text-center current-stock" autocomplete="off" value="${ Number(stock).toFixed(2) }" readonly required>
                    </th>
                    <th width="8%">
                        <input type="number" name="quantity[]" id="quantity" class="form-control text-center adjust-quantity" autocomplete="off" onkeyup="checkStockValidity(this)" required>
                    </th>
                    <th width="10%">
                        <select  class="form-control select2 adjustment-type" name="stock_type[]" ${ product.is_lot_required == 'Yes' ? 'required' : ''}>
                            <option value="Out">Out</option>
                            <option value="In">In</option>
                        </select>
                    </th>
                    <th width="10%">
                        <select  class="form-control select2 adjustment-reason" name="adjustment_reason[]" ${ product.is_lot_required == 'Yes' ? 'required' : ''}>
                            <option>- Select -</option>
                            <option value="Lost">Lost</option>
                            <option value="Damaged">Damaged</option>
                            <option value="Thieft">Thieft</option>
                            <option value="Missing">Missing</option>
                            <option value="Extra">Extra</option>
                            <option value="Free">Free Quantity</option>
                        </select>
                    </th>
                    <th width="5%" class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-row" title="Remove" onclick="removeRow(this)"><i class="fa fa-times"></i></button>
                    </th>

                </tr>`




            $("#purchaseTable").append(tr);
            $('.lot-number').select2();
            $('.adjustment-type').select2();
            $('.adjustment-reason').select2();
        }



    }

    /*-------------- END APPEND FROM LIVE SEARCH CLICK ----------*/








    /*
    ||============================================================
    ||      AJAX SEARCH EXPIRE DATE & INFO
    ||============================================================
    */
    function getExpireDate(obj){

        let _this                = $(obj).closest('tr');
        let product_id           = _this.find('.product-id').val();
        let product_variation_id = _this.find('.product-variation-id').val();
        let lot                  = _this.find('.lot-number').val();
        let supplierId           = _this.find('.lot-number option:selected').data('supplier_id');
        let warehouse_id         = $('#warehouse_id').val();

        _this.find("input[name='supplier_id[]']").val(supplierId);

        _this.find('.adjust-quantity').val('');
        _this.find('.current-stock').val('');
        _this.find('.purchase-price').val('');

        // if(lot != ''){
            let route  = `{{ route('pdt.get-expire-date') }}`;

            axios.get(route, {
                params: {
                    product_id : product_id,
                    product_variation_id : product_variation_id,
                    lot : lot,
                    warehouse_id : warehouse_id,
                }
            })
            .then(function (response) {

                console.log(response);

                let exp_date    = response.data.expire_date
                let stock       = response.data.expire_date
                let expire_date = `<option>Select</option>`;

                if(stock != ''){
                    $.each(exp_date, function(key, value){
                        expire_date += `<option data-current_stock="${ value.balance_qty }" value="${ value.expire_date }">${ value.expire_date }</option>`;
                    })
                    _this.find('.expire-date').html(expire_date);
                }else{
                    _this.find('.current-stock').val(parseInt(response.data.quantity));
                    _this.find('.purchase-price').val(parseInt(response.data.unit_cost.stock_in_value));
                    checkDuplicate(obj)
                }

                $('.expire-date').select2();


            }).catch(function (error) {

            });

        // }else{
        //     _this.find('.expire-date').html('');
        // }

    }



    /*
    ||============================================================
    ||      AJAX SEARCH EXPIRE DATE & INFO
    ||============================================================
    */
    function getExpireDate2(obj){

        let _this                = $(obj).closest('tr');
        let product_id           = _this.find('.product-id').val();
        let product_variation_id = _this.find('.product-variation-id').val();
        let lot                  = _this.find('.lot-number').val();
        let supplierId           = _this.find('.lot-number option:selected').data('supplier_id');
        let warehouse_id         = $('#warehouse_id').val();

        _this.find("input[name='supplier_id[]']").val(supplierId);

        _this.find('.adjust-quantity').val('');
        _this.find('.current-stock').val('');
        _this.find('.purchase-price').val('');

        // if(lot != ''){
            let route  = `{{ route('pdt.get-expire-date2') }}`;

            axios.get(route, {
                params: {
                    product_id : product_id,
                    product_variation_id : product_variation_id,
                    lot : lot,
                    warehouse_id : warehouse_id,
                }
            })
            .then(function (response) {

                console.log(response);

                let exp_date    = response.data.expire_date
                let stock       = response.data.expire_date
                let expire_date = `<option>Select</option>`;

                if(stock != ''){
                    $.each(exp_date, function(key, value){
                        expire_date += `<option data-current_stock="${ value.balance_qty }" value="${ value.expire_date }">${ value.expire_date }</option>`;
                    })
                    _this.find('.expire-date').html(expire_date);
                }else{
                    _this.find('.current-stock').val(parseInt(response.data.quantity));
                    _this.find('.purchase-price').val(parseInt(response.data.unit_cost.stock_in_value));
                    checkDuplicate(obj)
                }

                $('.expire-date').select2();


            }).catch(function (error) {

            });

        // }else{
        //     _this.find('.expire-date').html('');
        // }

    }




    function expireDateWiseInfo(obj){

        let _this           = $(obj).closest('tr');
        let current_stock   = parseInt($(obj).find(':selected').data('current_stock'));
        let purchase_price  = 100;

        _this.find('.purchase-price').val(purchase_price);
        _this.find('.current-stock').val(current_stock);

        checkDuplicate(obj);


    }

    /*-------------- END AJAX SEARCH EXPIRE DATE & INFO ----------*/







    /*
    ||============================================================
    ||      CHECK DUPLICATE & CALCULATE
    ||============================================================
    */
    function checkDuplicate(obj){
        let _obj               = $(obj).closest('tr');
        let newProductId          = _obj.find('.product-id').val();
        let newProductVariationId = _obj.find('.product-variation-id').val();
        let newLot                = _obj.find('.lot-number').val();
        let newExpireDate         = _obj.find('.expire-date').val();

        let isDuplicate = 0;

        $('.product-id').each(function(){
            let _this              = $(this).closest('tr');
            let productId          = _this.find('.product-id').val();
            let productVariationId = _this.find('.product-variation-id').val();
            let lot                = _this.find('.lot-number').val();
            let expireDate         = _this.find('.expire-date').val();

            if(productId == newProductId && productVariationId == newProductVariationId && lot == newLot && expireDate==newExpireDate){
                isDuplicate++;
            }
            if(isDuplicate > 1){
                warning('toastr', 'Duplicate data !');
                _obj.remove();
                calculate();

            }

        })
    }


    function checkStockValidity(obj){

        let _this           = $(obj);
        let current_qty     = Number(_this.closest('tr').find('.current-stock').val());
        let adjustmentType  = _this.closest('tr').find('.adjustment-type option:selected').val();
        let quantity        = Number(_this.val());


        if (quantity < 0 || quantity == 0) {
            _this.val('');
            warning('toastr', 'Negative Stock not allowed!');
            return;
        }


        if (adjustmentType == 'Out') {
            if(quantity > current_qty){
                _this.val('');
                warning('toastr', 'Insufficiant stock!');
            }
            calculate();
        }


        if (adjustmentType == 'In') {
            calculate();
        }


    }


    function calculate(){
        let totalAmount = 0;
        let totalQuantity = 0;

        $('.product-id').each(function(){
            let _this              = $(this).closest('tr');
            let quantity           = Number(_this.find('.adjust-quantity').val() != '' ? _this.find('.adjust-quantity').val() : 0);
            let cost               = _this.find('.purchase-price').val();

            totalQuantity += quantity;
            totalAmount   += quantity * cost;

        });

        $('#total_quantity').val(totalQuantity);
        $('#total_amount').val(totalAmount);
    }


    function calculateAllAmount(){
        calculate();
    }

    /*-------------- END CHECK DUPLICATE & CALCULATE----------*/



    function resetWarehouse(){
        $('#productTable').html('');
    }

</script>
