<!---------- CALCULATE SCRIPT --------->
@include('_inc.calculate-script')


<!---------- SCRIPT 1 --------->
<script>
    $(document).on('change', '.select-unit', loadUnitWisePrice)

    const vat_percent       = "{{ vatSetting()->resturent_vat }}"

    const service_charge    = "{{ vatSetting()->rst_service_charge }}"

    const use_vat_included  = {{ setting('use_vat_included') }}






    function productRow(data) {

        console.log('Product Current Stock', data.available);

        let big_unit_price = Number($('#input-big-unit-id').val())
        let small_unit_price = Number($('#input-small-qty').val())
        let row = $('.product-row').length;


        let available = data.available;

        if (available <= 0 ) {
            warning('toster', 'Product Stock Limit Out Update Stock')
            return false;
        }else{


        let bar_unit = `<input type="number" value="${small_unit_price}" name="small_qty[]" step="1" class="form-control small-unit-qty input-sm" autocomplete="off" onpaste="return false;" readonly>
                        <span class="input-group-addon custom-addon-width">${data.pack_unit}</span>`


        let html = `<tr class="product-row row-${row} tr-product-${data.id}" >
                <td>
                    <span class="product_name">${data.name}</span>
                    <input type="hidden" value="${data.id}" name="product_ids[]" class="productId">
                </td>

                <td>
                    <span class="product_barcode">${data.barcode}</span>
                    <input type="hidden" value="${data.total_qty}"
                        name="available_qty[]" class="form-control input-sm available_qty" readonly>
                    <input type="hidden" value="${data.total_qty}" name="old_available_qty[]" class="old_available_qty">
                    <input type="hidden" value="${data.unit_id}" name="unit_id[]">
                    <input type="hidden" value="${data.pack_unit_id}" name="small_unit_id[]">
                </td>

                <td>
                    <div class="input-group">
                        @if (hasPermission('rst.sales.quantity-update', $slugs))
                            <div class="spinbox-buttons input-group-btn">
                                <button type="button" class="btn table-qty-decrease-btn spinbox-down btn-xs btn-danger qty-decrease" onclick="manageQty('decrease', this)">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        @endif

                        ${ (data.pack_unit != null && data.unit != data.pack_unit) ? bar_unit : '' }
                        <input type="number" value="${big_unit_price}" data-pack-size=${data.pack_size} name="sales_qty[]" max="${data.total_qty}" class="form-control big-unit-qty input-sm" autocomplete="off"placeholder="Quantity" readonly>
                        <span class="input-group-addon custom-addon-width">${data.unit}</span>
                        @if (hasPermission('rst.sales.quantity-update', $slugs))
                            <div class="spinbox-buttons input-group-btn">
                                <button type="button" class="btn table-qty-decrease-btn spinbox-down btn-xs btn-success qty-increase" onclick="manageQty('increase', this)">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </td>
                <td>
                    <input tabindex="-1" type="text" value="0" name="item_discount[]" class="form-control item_discount input-sm width-80px">
                </td>

                <td>
                    <input tabindex="-1" type="text" value="${parseFloat(data.sale_price).toFixed(2)}" name="sales_price[]" class="form-control sales-price input-sm width-80px" readonly>
                </td>
                <td>
                    <input tabindex="-1" type="text" value="${ data.vat_amount }" data-vat-percent="${ data.vat_percent}" name="item_vat_amounts[]" class="form-control item-vat-amount input-sm width-60px" readonly>
                </td>
                <td>
                    <input tabindex="-1" type="number" min="0" value="${parseFloat(data.sale_price * big_unit_price).toFixed(2)}" name="item_price[]" class="form-control total-line-price input-sm width-70px" readonly="readonly">
                </td>
                <td>
                    <button class="delete" tabindex="-1" type="button"><i class="fa fa-times text-danger"></i></button>
                </td>
                </tr>`


        if ($('.product-row').length == 0) {
            $('#product-details').empty();
        }


        $('#product-details').append(html)
        calculate()
        }

    }



    function loadUnitWisePrice() {

        let price = Number($(this).find('option:selected').data('unit-price')).toFixed(2)
        let qty = Number($(this).closest('tr').find('.sales-qty').val())

        let vat_percent = Number($(this).closest('tr').find('.item-vat-amount').data('vat-percent'))

        let vat_amount = Number(price / 100 * vat_percent);

        $(this).closest('tr').find('.item-vat-amount').val(vat_amount.toFixed(2));
        $(this).closest('tr').find('.sales-price').val(price);


        serviceCalculate()
        calculate()
        if (use_vat_included == 1){
            vatCalculate()
        }

    }
</script>



<!---------- SCRIPT 2 --------->
<script>
    // const paymentBtn    = $('.payment-btn')
    const paymentBtn = $('.save-button')
    const paymentStatus = $('.sale-payment-status')
    const saveSaleRoute = `{{ route('rst.sales-v2.store') }}`
    let is_print = 0;

    $(document).on('click', '.save-sale', saveSaleData)
    $(document).on('click', '.save-only', saveSaleWithoutPrint)

    $(document).on('click', '.save-button', saveSaleDataWithPayment)

    function clearInputs() {

        $('#subTotal, #discount, #vat, #service_charge, #grandTotal, .grand_total, #paid_amount, .account-paid-amount, .due_amount, .change_amount, .total_amount')
            .prop('readonly', false);

        $('#subTotal, #discount, #vat, #service_charge, #grandTotal, .grand_total, #paid_amount, .account-paid-amount, .due_amount, .change_amount, .account-way-paid-amount, .total_amount')
            .val(0);

        $('#subTotal, #discount, #vat, #service_charge, #grandTotal, .grand_total, #paid_amount, .account-paid-amount, .due_amount, .change_amount, .total_amount')
            .prop('readonly', true);

        $("#accountTypeTable tbody").find("tr:gt(0)").remove();
        $('#account-type-modal').modal('hide');
    }




    $(document).on('click', '.sale-payment-status', function() {
        if ($(this).val() == 'Paid') {
            $('#paid_amount').val($('#grandTotal').val())
        } else {
            $('#paid_amount').val(0)
        }
    })

    function saveSaleWithoutPrint() {
        is_print = 0;
        if ($('#guest_name').val() == '') {
            warning('toster', 'Please select Guest')
            return;
        }

        if ($('.product-row').length == 0) {
            warning('toster', 'Please add some product.')
            return;
        }

        saleAjax()
    }

    function saveSaleData() {

        // manually check the paid option
        $('.sale-payment-status').val('Due').attr('checked', 'checked');
        is_print = 1;

        if ($('#guest_name').val() == '') {
            warning('toster', 'Please select Guest')
            return;
        }

        if ($('#hotel_table_no_id').val() == '') {
            warning('toster', 'Please select Table')
            return;
        }

        if ($('.product-row').length == 0) {
            warning('toster', 'Please add some product.')
            return;
        }

        saleAjax()
    }


    function saveSaleDataWithPayment() {

        // manually check the paid option
        $('.sale-payment-status').val('Paid').attr('checked', true);
        //$('#paid_amount').val($('#grandTotal').val())

        let status = $('.sale-payment-status:checked').val()

        if ($('#guest_name').val() == '') {
            warning('toster', 'Please select Guest')
            return;
        }

        if ($('#hotel_table_no_id').val() == '') {
            warning('toster', 'Please select Table')
            return;
        }

        if ($('.product-row').length == 0) {
            warning('toster', 'Please add some product.')
            return;
        }


        if (status != 'Paid') {
            warning('toster', 'Please select Paid as Payment status !')
            return;
        }
        saleAjax()
    }


    function saleAjax() {

        $.LoadingOverlay("show")

        $.ajax({
            url: $('#sale-form').attr('action'),
            method: 'post',
            data: $('#sale-form').serialize(),
            success: function(data) {
                if (data.status) {
                    $('#product-details').empty();
                    $('#guest_name, #hotel_table_no, #waiter_no').val('');
                    $('#invoice_id').val(data.invoice_no);

                    clearInputs();


                    // GETTING SALE STORE ROUTE WHILE SAVING/SUBMITTING SALE WITHOUT ANY PAGE RELOAD
                    let storeSaleUrl = $('#storeSaleUrl').val();
                    $('#sale-form').attr('action', storeSaleUrl);
                    $('#forSaleUpdateMethod input[name=_method]').val('POST');


                    $.LoadingOverlay("hide")
                    serviceCalculate()
                    calculate()
                    if (use_vat_included == 1){
                        vatCalculate()
                    }

                    success('toster', 'Sale have been created.');

                    if (is_print == 1) {
                        setTimeout(() => {
                            window.open('/restaurant/sales-v2/' + data.data.id + '?invoice_type=' +
                                $(
                                    '.invoice-type:checked').val(), '_blank')
                        }, 2000);

                    }
                    load_data()

                } else {
                    warning('toster', 'Something wrong in this sale.');
                    $.LoadingOverlay("hide")
                }

                $('#is_soft_save').val(0);
            }
        });

    }


    function saleItemDelete(url, obj) {
        let _this = $(obj);

        Swal.fire({
            title: 'Are you sure ?',
            html: "<div style='margin: 10px 0'><b>You will delete this record permanently !</b></div>",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            width: 400,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    method: 'delete',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.status) {

                            _this.closest('tr').remove();
                            serviceCalculate()
                            calculate()
                            if (use_vat_included == 1){
                                vatCalculate()
                            }

                            success('toster', 'data have been deleted.');


                        } else {
                            warning('toster', 'Something wrong in this sale.');
                        }
                    }
                });
            }
        })

    }

    $(document).on('click', '.delete', function(e) {
        linePrice();
        $(this).parents('tr').remove();

        var rowCount = $('#product-details tr').length;

        if (rowCount == 0) {
            clearInputs();
        }

        calculate()
    })

    function saleData(id, e) {

        $('.sale-data').removeClass('tr-bg')

        $(e).addClass('tr-bg')
        $.LoadingOverlay("show")

        let url = `{{ route('rst.sales-v2.edit', ':id') }}`;
        url = url.replace(":id", id);
        $.ajax({
            url: url,
            method: 'get',
            success: function(data) {
                $('#sale-form-data').html(data)
                $.LoadingOverlay("hide")
                $('.select2').select2()
                loadSelect2DOM()
            },
            error: function() {
                $.LoadingOverlay("hide")
            }
        });
    }

    function manageQty(type, obj) {
        let _this = $(obj).closest('tr').find('.big-unit-qty')
        let small_unit_qty = Number($(obj).closest('tr').find('.small-unit-qty').val())
        let qty = Number(_this.val());

        let pack_size = _this.data('pack-size')
        if (type == 'decrease') {
            if (qty > 1) {
                qty -= 1;
            }
        } else {
            qty += 1;
        }
        small_unit_qty = qty * pack_size

        _this.val(qty)
        $(obj).closest('tr').find('.small-unit-qty').val(small_unit_qty)
        if (use_vat_included == 1){
            vatCalculate()
        }
        serviceCalculate()
        calculate()
    }
</script>



<!---------- SCRIPT 3 --------->
<script>
    const saleRoute = `{{ route('rst.get-sale-data') }}`

    let product_data;

    var getDateSystemSetting = `{{ setting('bar_due_list_date') }}`;

    $(document).ready(function() {
        if (getDateSystemSetting == 0) {
            $('.sale-list-date').hide();
        }
    });

    function load_data(type) {

        $.LoadingOverlay("show")

        $.get(saleRoute, {
            payment_status: $('.payment_status:checked').val(),
            date: $('.invoice-date').val(),
            invoice_no: $('.invoice_no').val()

        }, function(data) {
            $('#invoice-list').html(data.sale_view);
            $('.paid-by-payment-method').html(data.transaction_view);
            // $('#invoice-list').html(data);

            // CALCULATE TOTAL DUE & PAID
            isPaidOrDueSelected()

            $.LoadingOverlay("hide")
        })
    }





    onkeydown = (e) => {
        if (e.ctrlKey && e.keyCode == 'S'.charCodeAt(0)) {
            e.preventDefault()

            $('#is_soft_save').val(1);
            saleAjax();

        }
    }



    $(function() {
        loadSelect2DOM()
    })

    function loadSelect2DOM() {
        loadSelect2({
            url: "/hotelservice/get-guest-list",
            select: '#guest_name',
            templateResult: formatGuest,
            templateSelection: formatGuestSelection
        })


        function formatGuest(guest) {
            if (guest.loading) {
                return guest.text;
            }

            $('#is_stuff').val(guest.is_stuff);

            return templateResult(guest.name, guest.phone_no)
        }

        function formatGuestSelection(guest) {
            return guest.name || guest.text;
        }


        loadSelect2({
            url: "/restaurant/inventory/get-products?bar=0",
            select: '#product-search',
            templateResult: formatProduct,
            templateSelection: formatSelection
        })
    }



    $('#guest_name').on('change', function() {

        $('#hotel_guest_id').val($(this).val())
    })


    function appendProductRow(data) {

        if ($('.tr-product-' + data.id).length > 0) {
            let small_unit_qty = $('.tr-product-' + data.id).find('.small-unit-qty');
            let big_unit_qty = $('.tr-product-' + data.id).find('.big-unit-qty');

            small_unit_qty.val(Number(small_unit_qty.val()) + Number($('#input-small-qty').val()))
            big_unit_qty.val(Number(big_unit_qty.val()) + Number($('#input-big-unit-id').val()))

        } else {
            productRow(data)
        }
        $('#input-big-unit-id, #input-small-qty').val('')
        loadSelect2DOM()
        serviceCalculate()
        calculate()
        if (use_vat_included == 1){
            vatCalculate()
        }
    }

    $(document).on('select2:select', '#product-search', function(e) {
        var data = e.params.data;

        product_data = data
        let inputQuantity = `<input type="text" class="form-control only-number" id="input-big-unit-id">
                            <span class="input-group-addon" id="input-unit-id">${data.unit}</span>`
        if (data.pack_unit != null && data.unit != data.pack_unit) {
            inputQuantity += `<input type="text" class="form-control only-number" id="input-small-qty">
                            <span class="input-group-addon" id="input-pack-unit-id">${data.pack_unit}</span>`
        }
        inputQuantity += `<div class="input-group-btn">
                                <button class="btn btn-info btn-sm" id="add-product" type="button">
                                    <i class="fa fa-plus-square"></i>
                                </button>
                            </div>`
        $('#input-quantity-group').html(inputQuantity)

        $(document).find('#input-big-unit-id').focus();
    })

    $(document).on('keypress', '#input-big-unit-id', function(e) {
        if (e.keyCode == 13 || e.which == 13) {
            if ($('#input-small-qty').length > 0) {
                $('#input-small-qty').focus();
                measureCalc()
            } else {
                appendProductRow(product_data)
            }
        }

    })

    $(document).on('keypress', '#input-small-qty', function(e) {
        if (e.keyCode == 13 || e.which == 13) {

            appendProductRow(product_data)
        }
    })


    $(document).on('click', '#add-product', function() {
        measureCalc()
        appendProductRow(product_data)
    })


    function measureCalc() {

        let big_qty = $('#input-big-unit-id').val();
        // let small_qty = $('#input-small-qty').val();
        let small_qty = Number(big_qty * product_data.pack_size)

        $('#input-small-qty').val(small_qty.toFixed(2));

    }


    function calculate() {
        let total = 0;

        $('.sales-price').map(function() {
            let sale_price = Number($(this).val());
            let quantity = Number($(this).closest('tr').find('.big-unit-qty').val());
            let item_discount = Number($(this).closest('tr').find('.item_discount').val());
            let linetotal = sale_price * quantity - item_discount;
            $(this).closest('tr').find('.total-line-price').val(linetotal);
            total += linetotal;
        });

        let discount    = Number($('#discount').val());
        let charge      = Number($('#service_charge').val());
        let vat         = Number($('#vat').val());
        let is_stuff    = $('#is_stuff').val()




        if (use_vat_included == 1) {
            let discountPercAmount = (total * discount) / 100;
            let amountWithdiscount = total - discountPercAmount;
            let payable_amount     = amountWithdiscount;

            $('#total_discount').val(discountPercAmount.toFixed(2));
            $('#subTotal').val(payable_amount.toFixed(2));
            $('#grandTotal, .grand_total').val(payable_amount.toFixed(2));
            $('#grand-total').text(total.toFixed(2));

            return payable_amount;
        } else {

            let set_service        = (total  * service_charge) / 100
            let set_vat            = ((total + set_service) * vat_percent) / 100
            let discountPercAmount = (total * discount) / 100;
            let amountWithdiscount = total - discountPercAmount;

            if(is_stuff == 1){
                 payable_amount = total - discountPercAmount;
            }else{
                 payable_amount = ( total + set_vat + set_service ) - discountPercAmount;

            }

            $('#total_discount').val(discountPercAmount.toFixed(2));
            $('#subTotal').val(total.toFixed(2));
            $('#grandTotal, .grand_total').val(payable_amount.toFixed(2));
            $('#grand-total').text(total.toFixed(2));
            $('#vat').val(is_stuff == 1 ? 0 : set_vat.toFixed(2))

            return payable_amount;
        }
    }


    function vatCalculate() {

        let discount = Number($('#discount').val())
        let subTotal = Number($('#subTotal').val());
        let discountPercAmount = ((subTotal * discount) / 100) || 0;
        let service_amount = ((subTotal - discountPercAmount)  * service_charge) / 100
        let vat_amount     = ((subTotal - discountPercAmount + service_amount) * vat_percent) / 100
        let is_stuff       = $('#is_stuff').val()
        $('#vat').val(is_stuff == 1 ? 0 : vat_amount.toFixed(2))

        return Number(vat_amount)
    }

    function serviceCalculate() {
        let discount = Number($('#discount').val())
        let subTotal = Number($('#subTotal').val());
        let is_stuff = $('#is_stuff').val()
        let discountPercAmount = ((subTotal * discount) / 100) || 0;
        let service_amount = ((subTotal - discountPercAmount)  * service_charge) / 100
        $('#service_charge').val(is_stuff == 1 ? 0 : service_amount.toFixed(2))
        return Number(service_amount)
    }

    function discount() {
        var discount = Number($('#discount').val()),
            subTotal = Number($('#subTotal').val());
        if (discount > subTotal) {
            $('#discount').val(0)
            discount = subTotal.toFixed(2)
            warning('toster', 'You can\'t pay more than grand total.');
        }
        $('#discount').val(discount)
        calculate()
    }


    $(document).on('keyup', '#vat, #service_charge, #amountPaid, .item_discount', function(e){
        calculate(e)
    });

    $(document).on('keyup', '#discount', function (e) {
        let vat      = vatCalculate(e);
        let service  = serviceCalculate(e);
        let discount = Number($('#discount').val());
        let subTotal = Number($('#subTotal').val());
        let discountPercAmount = ((subTotal * discount) / 100) || 0;
        let is_stuff = $('#is_stuff').val()

        let grandTotal;

        if (is_stuff == 1) {
            grandTotal = subTotal - discountPercAmount;
        } else {
            grandTotal = (subTotal - discountPercAmount) + vat + service;
        }

        $('#grandTotal').val(grandTotal.toFixed(2));
        $('#total_discount').val(discountPercAmount.toFixed(2));
    });


    $(document).on('keyup', '.sales-qty', linePrice);

    $(document).on('change', '.sale-date', function() {
        $.ajax({
            url: '/restaurant/inventory/get-date',
            method: 'get',
            data: {
                date: $(this).val()
            },
            success: function(data) {},
        });
    })






    function linePrice() {
        var rows = $('.product-row')
        totalDrugPrice = 0, amount = 0

        $.each(rows, function(index, row) {

            let available_qty = $(row).find('.old_available_qty').val()
            var quantity = $(row).find('.sales-qty').val(),
                salesPrice = $(row).find('.sales-price').val(),
                vatAmount = $(row).find('.item-vat-amount').val(),
                totalDrugPrice = (quantity * salesPrice) + Number(vatAmount)


            now_available_qty = available_qty - quantity

            $(row).find('.available_qty').val(now_available_qty)

            if (!quantity || !salesPrice) quantity = 0, salesPrice = 0
            $(row).find('.total-line-price').val(Math.round(totalDrugPrice).toFixed(2))
            amount += totalDrugPrice
        });

        $('#subTotal').val(Math.round(amount).toFixed(2))
        $('#grandTotal').text(Math.round(amount).toFixed(2))
        return Math.round(amount).toFixed(2)
    }








    //get inhouse room guest if exists

    inHouseGuest = () => {

        let room_id = $('#room_no').find('option:selected').val();

        $.ajax({
            url: '{{ route('inhotel-guest-information') }}',
            method: 'GET',
            data: {
                room_id: room_id
            },
            success: function(res) {

                if (res != '') {
                    $('#guest_name').append(`<option value="${res.customer?.id}" selected>${res.customer?.name}</option>`)
                    // $('#guest_name').val(res.customer.name);
                    // $('#booking_customer_id').val(res.customer.id);
                    // $('#pay_booking_id').val(res.booking.booking_info.id);
                    // $('#booking_payment_type').val(res.booking.booking_info.id);
                } else {
                    $('#guest_name').empty()
                }
            },
            error: function(res) {
                console.log(res);
            }
        })
    }

    $(document).on('change', '#room_no', inHouseGuest);
</script>

<x-multi-account-pay-script />
