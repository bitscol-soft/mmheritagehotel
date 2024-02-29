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
            let qty = Number($(this).closest('tr').find('.quantities').val());
            let subtotal = Number($(this).val()) * qty
            $(this).closest('tr').find('.total-line-prices').val(subtotal)
        })
        calculateTotal()
    }

    calculateTotal = () => {
        let total = 0;
        $('.total-line-prices').map(function(item) {
            total += Number($(this).val());
        })

        let total_vat = Number($('#total_vat').val());
        let discount = Number($('#discount').val())
        let grandtotal = (total + total_vat) - discount
        let paid_amount = Number($('#paid_amount').val())
        let due_amount = grandtotal - paid_amount

        $('#subtotal').val(total)

        $('#grand_total').val(grandtotal)
        $('#due_amount').val(due_amount)

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
        if (productTBody.find('tr').length <= 0) {
            warning('toster', 'Please add product')
            return;
        }
        $('#purchase-form').submit()
    }






    $(document).on('keyup', '#discount, #paid_amount', calculateTotal)
    $(document).on('keyup', '.quantities', rowCalculate)
    $(document).on('keyup', '.unit-vat', unitVat)

    $(document).on('click', '.save-purchase', submitForm)
</script>

<script>
    loadSelect2({
        // url: "/bar/inventory/get-products?bar=0",
        url: "{{ route('rst.getProduct') }}",

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
        console.log(data);
        var html = `<tr class="product-row product-${data.id}">
        <td>
            <span style="color: green">${data.name}</span><br>
            <span>Stock: <b>${data.total_qty}</b>(${data.pack_unit})</span>
            <input type="hidden" value="${data.id}" name="product_id[]" tabindex="-1" class="product-ids">
        </td>

        <td>
            <input type="number" name="purchase_price[]" min="0" autocomplete="off"
                ondrop="return false;" required value="${data.unit_price}"
                class="form-control small-label-box purchase-prices">

        </td>
        <td>
            <input type="number" name="sale_price[]" placeholder="Sales Price" required
                value="${data.sale_price}"
                class="form-control small-label-box
                    sales-prices">
        </td>

        <td>
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="button" class="btn spinbox-down btn-minier btn-danger" onclick="quantityManage(this, 'decrement')">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <input type="text" name="quantity[]" value="1" class="spinbox-input form-control text-center small-label-box quantities">
                <div class="input-group-btn">
                    <button type="button" class="btn spinbox-up btn-minier btn-success" onclick="quantityManage(this, 'increment')">
                        <i class="fas fa-plus "></i>
                    </button>
                </div>
            </div>
        </td>
        <td><span class="unit-name">${data.pack_unit}</span> </td>

        <td>
            <input value="${ parseInt(data.vat_amount)}" name="unit_vat[]" type="number" class="form-control small-label-box unit-vat">
        </td>
        <td>
            <input value="${data.unit_price}" name="total_price[]" type="number"class="form-control small-label-box total-line-prices" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-minier btn-warning" onclick="removeItem(this)"><i class="fas fa-trash"></i></button>
        </td>
    </tr>`
        $('#products').append(html)
    }
</script>
