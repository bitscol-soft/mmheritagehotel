function addItem(id, product_id, title, code, qty, price, table) {
    let is_item_added = true
    $('.tr_product_id').each(function (index, value) {
        if ($(this).val() == product_id) {
            is_item_added = false;
            let closest_tr = $(this).closest('.mgrid');
            Increase($(this))
            return false;
        }
    })

    if (is_item_added == true) {

        let tr = `<tr class="mgrid">
                <td width="3%">
                    <span class="serial">${id}</span>
                    <input type="hidden" class="tr_product_id" name="product_ids[]" value="${product_id}" />
                </td>
                <td style="width:20%"> ${title}
                <input type="hidden" name="product_titles[]" value="${title}"/>
                </td>
                <td style="width:20%"> 
                <input type="hidden" name="product_codes[]" value="${code}"/>
                ${code}</td>
                <td style="width:20%">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <a href="#" onclick="Decrease(this)"><i class="ace-icon fa fa-minus"
                                        style="color: rgb(126, 3, 3)"></i></a>
                            </span>
                            <input class="form-control product_qty" type="number" onkeyup="updateCart(this)" name="product_qty[]" value="${qty}">
                            <span class="input-group-addon">
                                <a href="#" onclick="Increase(this)"><i class="ace-icon fa fa-plus"></i></a>
                            </span>
                        </div>
                    </div>
                </td>
                <td style="width:20%">
                    <input type="text" name="product_price[]" class="form-control product-cost" onkeyup="updateCart(this)" value="${price}"
                        autocomplete="off">
                </td>

                <td style="widht:12%"><strong class="subtotal">${price}</strong></td>
                <td style="widht:5%">
                    <a href="#" class="text-danger" onclick="removeField(this)">
                        <i class="ace-icon fa fa-trash-o bigger-120"></i>
                    </a>
                </td>
            </tr>`;
        table.append(tr);
        calculate()
    }
}

$('#customer').change(function(){
    let customerDue = $(this).find(':selected').data('previous_due')
    $('#previous-due').val(customerDue)
    calculate()
})

$('#category').change(function(){
    let category = $(this).val()
    console.log(category)
    if (category != '') {
        $.ajax({
            url: '/pos_erp/product-search-by-category-ajax',
            dataType: "json",
            type: "get",
            async: true,
            data: {
                _token: '{!! csrf_token() !!}',
                search: category
            },
            beforeSend: function() {
                $('.loader').css("display", "block");
            },
            success: function(response) {
                let data = response.data;
                console.log(data)
                if (data.length == 1) {
                    let length = $('#sale-table tbody tr').length + 1;
                    addItem(length, data[0].id, data[0].product_name, data[0].product_code, 1, data[0]
                        .product_price, $('#sale-table tbody'))
                    // console.log(data[0].product_name);
                    $('.product_list').html('');
                }else
                {
                    $('#products-category').html('')
                    data.map(function(value, index) {

                        $('#products-category').append(`<div class="col-md-2 col-4 p-1 pt-1" >
                            <div style="background: white" class="single-product" onclick="GetInfo(this)">
                                    <div class="img"><img src="${value.image_url}" class="img-fluid"></div>
                                <p style="display: none" class="product_id">${ value.id }</p>
                                <div class="description">
                                    <p class="product-title"><strong>${ value.product_name } </strong></p>
                                    <div class="d-flex">
                                        <div style="display: none" class="col-12 pl-0 pt-0">Sku: <span class="sku-code">P-${ value.product_code }</span></div>
                                        <div class="col-12 pl-0 pt-0">Price:${ value.product_cost }</div>
                                    </div>
                                </div>
                                <div class="price product-price" style="display: none">${ value.product_cost }</div>
                            </div>
                        </div>`)
                    })
                }
            }
        });
    }
})

function Decrease(object) {
    let _this = $(object);
    let input = _this.closest('.mgrid').find('.product_qty');
    let qty = input.val();
    if (qty > 1) {
        input.val(Number(qty - 1));
    }
    updateCart(object);
}

function Increase(object) {
    let _this = $(object);
    let input = _this.closest('.mgrid').find('.product_qty');
    let qty = input.val();
    input.val(Number(qty) + 1);
    updateCart(object);
}
function removeField(object) {
    $(object).closest('.mgrid').remove();
    serial();
    calculate()
}

function serial() {
    $('.serial').each(function (index) {
        $(this).text(index + 1);
    })
}

function updateCart(object) {
    let _this = $(object).closest('.mgrid');
    let qty = _this.find('.product_qty').val();
    let price = _this.find('.product-cost').val();

    let total = (Number(qty) * Number(price)).toFixed(2)

    _this.find('.subtotal').text(total);
    calculate();
}

function calculate() {
    let price = 0;
    $('.subtotal').each(function () {
        price += Number($(this).text());
    });
    let vat = Number(($('#vat').val()) | 0)
    let old_price = price
    let payable_price = old_price + (old_price*vat/100)
    let discount = Number($('#discount').val());
    let customer_due = Number($('#previous-due').val() | 0);
    grand_total = Number(payable_price - discount + customer_due).toFixed(2)
    $('#grand_total').val(grand_total);
    $('#total').val(price);
    $('#payable').val(grand_total);
    $('#grand_total').val(grand_total);
}

$('#discount').keyup(function () {
    calculate()
})

$('#vat').keyup(function () {
    calculate()
})