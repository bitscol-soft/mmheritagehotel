<script>
    const store_route = document.getElementById('purchase-form')
    const subtotal = document.getElementById('subtotal')
    const discount = document.getElementById('discount')
    const productTBody = $('#products')
    const purchasePrices = $('.purchase-prices')





    removeItem = (obj) => {
        $(obj).closest('tr').remove()
    }




    quantityManage = (obj, type) => {
        let _this = $(obj).closest('tr')
        let qty = parseInt(_this.find('.quantities').val())
        if (type == 'increment') {
            qty += 1
        } else {
            qty -= 1;
            if (qty < 1) {
                qty = 1;
            }
        }
        _this.find('.quantities').val(qty)
        rowCalculate()
    }


    rowCalculate = () => {

        $('.purchase-prices').map(function(item) {

            let qty             = Number($(this).closest('tr').find('.quantities').val());
            let purchase        = Number($(this).closest('tr').find('.purchase-prices').val());
            let pack            = Number($(this).closest('tr').find('.pack-size').val());
            let unit            = $(this).closest('tr').find('.unit-name').text();

            let singleUnitCost  = pack ? purchase/pack : purchase;
            let subtotal        = Math.round(singleUnitCost * qty);

            $(this).closest('tr').find('.total-line-prices').val(subtotal)
        })
        calculateTotal()
    }

    calculateTotal = () => {
        let total = 0;
        let total_quantity = 0;
        $('.total-line-prices').map(function(item) {
            total += Number($(this).val());
        })

        $('.quantities').map(function(item) {
            total_quantity += Number($(this).val());
        })

        let total_vat = Number($('#total_vat').val());
        let discount = Number($('#discount').val())
        let grandtotal = (total + total_vat) - discount
        let paid_amount = Number($('#paid_amount').val())
        let due_amount = grandtotal - paid_amount

        // let total_quantity = qty

        $('#subtotal').val(total)

        $('#grand_total').val(grandtotal)
        $('#due_amount').val(due_amount)
        $('#total_quantity').val(total_quantity)

    }

    unitVat = () => {
        let total = 0;
        $('.unit-vat').map(function() {
            total += Number($(this).val())
        })
        $('#total_vat').val(total)
        calculateTotal()
    }




    submitForm = (e) => {
        e.preventDefault()
        let total_bal = $('#total_quantity').val()
        if (productTBody.find('tr').length <= 0) {
            warning('toster', 'Please add product')
            return;
        }
        if (total_bal <= 0) {
            warning('toster', 'Please Checked Before Submit')
            return;
        }
        $('#purchase-form').submit()
    }

    submitAndApproved = (e) => {
        e.preventDefault()
        let total_bal = $('#total_quantity').val()
        if (productTBody.find('tr').length <= 0) {
            warning('toster', 'Please add product')
            return;
        }
        if (total_bal <= 0) {
            warning('toster', 'Please Checked Before Submit')
            return;
        }
        $('#current_status').val('Approved')
        $('#purchase-form').submit()
    }






    $(document).on('keyup', '#discount, #paid_amount', calculateTotal)
    $(document).on('keyup', '.quantities', rowCalculate)
    $(document).on('keyup', '.unit-vat', unitVat)

    $(document).on('click', '.save-stock', submitForm)
    $(document).on('click', '.save-and-approved', submitAndApproved)
</script>

<script>
    loadSelect2({
        url: "/bar/inventory/get-products-all",
        select: '#product-search',
        templateResult: formatProduct,
        templateSelection: formatSelection
    })


    function formatProduct(data) {
        if (data.loading) {
            return data.text;
        }
        var $container = $(
            `<div class='select2-result clearfix'>
                    <div class='select2-result__title'>${data.name}</div>
                        <div class='select2-result__description'>${data.sale_price}</div>
                        </div>
                    </div>
                </div>`
        );

        return $container;
    }

    function formatSelection(data) {
        return data.name;
    }



    $(document).on('select2:select', '#product-search', function(e) {
        var data = e.params.data;
        if ($('.product-' + data.id).length > 0) {
            quantityManage($('.product-' + data.id), 'increment')
        } else {
            appendProductRow(data)
        }
        $(this).val('')
    })
</script>

<script>
    function appendProductRow(data) {
        // console.log(date);
        var html = `<tr class="product-row product-${data.id}">
        <td>
            <input type="hidden" name="current_status" value="" id="current_status">
            <span style="color: green">${data.name}</span>
            <b>
                    ${data.total_qty < 0 ? 0 : data.total_qty} -   (${data.pack_unit})
                </b>
            <br>
            <input type="hidden" value="${data.id}" name="product_id[]" tabindex="-1" class="product-ids">
            <input type="hidden" value="${data.category_id}" name="category_id[]" tabindex="-1" class="category_id">
            <input value="${data.unit_price}" name="total_price[]" type="hidden"class="form-control small-label-box total-line-prices" >
        </td>

        <td>
            <input type="number" name="purchase_price[]" min="0" autocomplete="off"
                ondrop="return false;" required value="${data.sale_price}"
                class="form-control small-label-box purchase-prices" readonly>

        </td>
        <td>
            <span style="">
                <b>
                    <input type="hidden" value="${data.total_qty < 0 ? 0 : data.total_qty}" name="qty[]" tabindex="-1" class="total_qty">
                    <input value="${ data.pack_size}" type="hidden" class="form-control pack-size">
                    (${data.pack_unit})
                </b>
            </span>
        </td>

        <td>
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn spinbox-down btn-minier btn-danger" onclick="quantityManage(this, 'decrement')">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <input type="text" name="approvrd_quantity[]" onkeyup="checkStockValidity(this)" value="1" class="spinbox-input form-control text-center small-label-box quantities">
                <div class="input-group-btn">
                    <button type="button" class="btn spinbox-up btn-minier btn-success" onclick="quantityManage(this, 'increment')">
                        <i class="fa fa-plus "></i>
                    </button>
                </div>
            </div>
        </td>

        <td>
            <select class="form-control select2 stock_type" id="stock_type" name="stock_type[]">
                <option value="Out">Out</option>
                <option value="In">In</option>
            </select>
        </td>
        <td>
            <select class="form-control select2 adjustment_reason" id="adjustment_reason" name="adjustment_reason[]">
                <option value="Lost">Lost</option>
                <option value="Damaged">Damaged</option>
                <option value="Thieft">Thieft</option>
                <option value="Missing">Missing</option>
                <option value="Extra">Extra</option>
                <option value="Free">Free Quantity</option>
            </select>
        <td>
            <button type="button" class="btn btn-minier btn-warning" onclick="removeItem(this)"><i class="fa fa-trash-o"></i></button>
        </td>
    </tr>`
        $('#products').append(html)
        $('.stock_type').select2();
        $('.adjustment_reason').select2();
        $.LoadingOverlay("hide")
    }
</script>


<script>
    function checkStockValidity(obj) {
        let _this = $(obj);
        let current_qty = Number(_this.closest('tr').find('.total_qty').val());
        let adjustmentType = _this.closest('tr').find('.stock_type option:selected').val();
        let quantity = Number(_this.val());


        if (quantity < 0 || quantity == 0) {
            _this.val('');
            warning('toster', 'Invalid Entry!');
            return;
        }


        if (adjustmentType == 'Out') {
            if (quantity > current_qty) {
                _this.val('');
                warning('toster', 'Insufficiant stock!');
                return;

            }
            // calculate();
        }


        if (adjustmentType == 'In') {
            // calculate();
        }


    }
</script>



Claculate Extra Make No Need
<script>
    function calculate() {
        let totalAmount = 0;
        let totalQuantity = 0;

        $('.product-id').each(function() {
            let _this = $(this).closest('tr');
            let quantity = Number(_this.find('.quantities').val() != '' ? _this.find('.quantities')
                .val() : 0);
            let cost = _this.find('.purchase-prices').val();

            totalQuantity += quantity;
            totalAmount += quantity * cost;

        });

        $('#total_quantity').val(totalQuantity);
        $('#total_amount').val(totalAmount);
    }
</script>
