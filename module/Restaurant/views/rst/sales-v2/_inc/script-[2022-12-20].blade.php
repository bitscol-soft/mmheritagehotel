<script>
    $(document).on('change', '.select-unit', loadUnitWisePrice)
    const vat_percent = "{{ vatSetting()->resturent_vat }}"

    function productRow(data) {

        let big_unit_price   = Number($('#input-big-unit-id').val())
        let small_unit_price = Number($('#input-small-qty').val())
        let row              = $('.product-row').length;

        let bar_unit = `<input type="number" value="${small_unit_price}" name="small_qty[]" step="1" class="form-control small-unit-qty input-sm" autocomplete="off" onpaste="return false;" readonly>
                        <span class="input-group-addon custom-addon-width">${data.pack_unit}</span>`


        let html = `<tr class="product-row row-${row} tr-product-${data.id}" >
                <td width="25%">
                    <span class="product_name">${data.name}</span>
                    <input type="hidden" value="${data.id}" name="product_ids[]" class="productId">
                </td>

                <td width="10%">
                    <span class="product_barcode">${data.barcode}</span>
                    <input type="hidden" value="${data.total_qty}" name="available_qty[]" class="form-control input-sm available_qty" readonly>
                    <input type="hidden" value="${data.total_qty}" name="old_available_qty[]" class="old_available_qty">
                    <input type="hidden" value="${data.expiry_date}" name="expiry_date[]">
                    <input type="hidden" value="${data.unit_id}" name="unit_id[]">
                    <input type="hidden" value="${data.pack_unit_id}" name="small_unit_id[]">
                </td>

                <td width="28%">
                    <div class="input-group">

                        @if(hasPermission('bar.sales.quantity-update', $slugs))
                            <div class="spinbox-buttons input-group-btn">
                                <button type="button" class="btn table-qty-decrease-btn spinbox-down btn-xs btn-danger qty-decrease" onclick="manageQty('decrease', this)">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        @endif

                        ${ (data.pack_unit != null && data.unit != data.pack_unit) ? bar_unit : '' }

                        <input type="number" value="${big_unit_price}" data-pack-size=${data.pack_size} name="sales_qty[]" max="${data.total_qty}" class="form-control big-unit-qty input-sm" autocomplete="off"placeholder="Quantity" readonly>

                        <span class="input-group-addon custom-addon-width">${data.unit}</span>

                        @if(hasPermission('bar.sales.quantity-update', $slugs))
                            <div class="spinbox-buttons input-group-btn">
                                <button type="button" class="btn table-qty-decrease-btn spinbox-down btn-xs btn-success qty-increase" onclick="manageQty('increase', this)">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        @endif

                    </div>
                </td>

                <td width="10%">
                    <input tabindex="-1" type="text" value="${parseFloat(data.sale_price).toFixed(2)}" name="sales_price[]" class="form-control sales-price input-sm">
                </td>
                <td width="10%">
                    <input tabindex="-1" type="text" value="${data.vat_amount}" data-vat-percent="${data.vat_percent}" name="item_vat_amounts[]" class="form-control item-vat-amount input-sm" readonly>
                </td>
                <td width="15%">
                    <input tabindex="-1" type="number" min="0" value="${parseFloat(data.sale_price * big_unit_price).toFixed(2)}" name="item_price[]" class="form-control total-line-price input-sm" readonly="readonly">
                </td>
                <td width="2%">
                    <button class="delete" tabindex="-1" type="button"><i class="fa fa-times text-danger"></i></button>
                </td>
                </tr>`


        if ($('.product-row').length == 0) {
            $('#product-details').empty();
        }


        $('#product-details').append(html)
        calculate()
    }



    function loadUnitWisePrice() {

        let price = Number($(this).find('option:selected').data('unit-price')).toFixed(2)
        let qty = Number($(this).closest('tr').find('.sales-qty').val())

        let vat_percent = Number($(this).closest('tr').find('.item-vat-amount').data('vat-percent'))

        let vat_amount = Number(price / 100 * vat_percent);

        $(this).closest('tr').find('.item-vat-amount').val(vat_amount.toFixed(2));
        $(this).closest('tr').find('.sales-price').val(price);


        vatCalculate()
        calculate()

    }
</script>

<script>
    const paymentBtn = $('.payment-btn')
    const paymentStatus = $('.sale-payment-status')
    const saveSaleRoute = `{{ route('rst.sales-v2.store') }}`
    let is_print = 0;

    $(document).on('click', '.save-sale', saveSaleData)
    $(document).on('click', '.save-only', saveSaleWithoutPrint)

    $(document).on('click', '.payment-btn', saveSaleDataWithPayment)

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

        // if ($('#hotel_table_id').val() == '') {
        //     warning('toster', 'Please select Table')
        //     return;
        // }

        if ($('.product-row').length == 0) {
            warning('toster', 'Please add some product.')
            return;
        }

        saleAjax()
    }

    function saveSaleData() {

        // manually check the paid option
        paymentStatus.val('Due').attr('checked', 'checked');
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
        paymentStatus.val('Paid').attr('checked', true);
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

                    $.LoadingOverlay("hide")
                    vatCalculate()
                    calculate()

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
                            vatCalculate()
                            calculate()

                            success('toster', 'data have been deleted.');


                        } else {
                            warning('toster', 'Something wrong in this sale.');
                        }
                    }
                });
            }
        })

    }

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
        let _this           = $(obj).closest('tr').find('.big-unit-qty')
        let small_unit_qty  = Number($(obj).closest('tr').find('.small-unit-qty').val())
        let qty             = Number(_this.val());

        let pack_size       = _this.data('pack-size')
        if (type == 'decrease') {
            if(qty > 1){
                qty -=1;
            }
        }else{
            qty                 +=1;
        }
        small_unit_qty      = qty * pack_size

       _this.val(qty)
       $(obj).closest('tr').find('.small-unit-qty').val(small_unit_qty)
       vatCalculate()
       calculate()
    }

</script>


<script>
    const saleRoute = `{{ route('rst.get-sale-data') }}`

    var getDateSystemSetting = `{{ setting('bar_due_list_date') }}`;

    $(document).ready(function(){
        if (getDateSystemSetting == 0) {
            $('.sale-list-date').hide();
        }
    });

    function load_data() {
        $.LoadingOverlay("show")
        $.get(saleRoute, {
            payment_status: $('.payment_status:checked').val(),
            date: $('.invoice-date').val(),
            invoice_no: $('invoice_no').val()
        }, function(data) {
            $('#invoice-list').html(data);
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



    $(function(){
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

            return templateResult(guest.name, guest.phone_no)
        }

        function formatGuestSelection(guest) {
            return guest.name || guest.text;
        }

        loadSelect2({
            url: "/restaurant/inventory/get-products",
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
        vatCalculate()
        calculate()

    }

    $(document).on('select2:select', '#product-search', function(e){
        var data = e.params.data;

        product_data =  data
        let inputQuantity = `<input type="text" class="form-control only-number" id="input-big-unit-id">
                            <span class="input-group-addon" id="input-unit-id">${data.unit}</span>`
        if (data.pack_unit != null && data.unit != data.pack_unit) {
            inputQuantity += `<input type="text" class="form-control only-number" id="input-small-qty">
                            <span class="input-group-addon" id="input-pack-unit-id">${data.pack_unit}</span>`
        }
        inputQuantity   += `<div class="input-group-btn">
                                <button class="btn btn-info btn-sm" id="add-product" type="button">
                                    <i class="fa fa-plus-square"></i>
                                </button>
                            </div>`
        $('#input-quantity-group').html(inputQuantity)

        $(document).find('#input-big-unit-id').focus();

    })

    $(document).on('keypress', '#input-big-unit-id', function(e){
        if(e.keyCode == 13 ||e.which == 13){
            if($('#input-small-qty').length > 0){
                $('#input-small-qty').focus();
                measureCalc()
            }else{
                appendProductRow(product_data)
            }
        }

    })


    $(document).on('keypress', '#input-small-qty', function(e){
        if(e.keyCode == 13 ||e.which == 13){

            appendProductRow(product_data)
        }
    })


    $(document).on('click', '#add-product', function(){
        measureCalc()
        appendProductRow(product_data)
    })


    function measureCalc() {

        let big_qty = $('#input-big-unit-id').val();

        let small_qty = Number(big_qty * product_data.pack_size)

        $('#input-small-qty').val(small_qty.toFixed(2));

    }




    function calculate() {
        let total = 0;
        // $('.total-line-price').map(function(item) {

        $('.sales-price').map(function(item) {
            let sale_price = Number($(this).val());
            let quantity = Number($(this).closest('tr').find('.sales-qty ').val())
            let linetotal = sale_price * quantity
            $(this).closest('tr').find('.total-line-price').val(linetotal)
            total += linetotal
        })

        let discount = Number($('#discount').val())
        let charge = Number($('#service_charge').val())
        let vat = Number($('#vat').val())

        let payable_amount = (total + vat + charge) - discount

        $('#subTotal').val(total)
        $('#grandTotal').val(payable_amount)

        return total;
    }

    function vatCalculate() {
        let total_amount = calculate()
        let vat_amount = (total_amount * vat_percent) / 100
        $('#vat').val(vat_amount)
        return Number(vat_amount)
    }




    $(document).on('keyup', '#vat, #discount, #service_charge, #amountPaid', function(e) {
        calculate(e)
    });

    $(document).on('keyup', '.sales-qty', linePrice);
    $(document).on('keyup', '.sales-price', salePrice);

    $(document).on('change', '.sale-date', function(){
        $.ajax({
            url: '/restaurant/inventory/get-date',
            method: 'get',
            data:{
                date: $(this).val()
            },
            success: function(data) {
            },
        });
    })





    function linePrice() {
        var rows = $('.product-row')
        totalDrugPrice = 0, amount = 0

        $.each(rows, function(index, row) {

            let available_qty   = $(row).find('.old_available_qty').val()
            var quantity        = $(row).find('.sales-qty').val(),
            salesPrice          = $(row).find('.sales-price').val(),
            vatAmount           = $(row).find('.item-vat-amount').val(),
            totalDrugPrice      = (quantity * salesPrice) + Number(vatAmount)

            now_available_qty   = available_qty - quantity

            $(row).find('.available_qty').val(now_available_qty)

            if (!quantity || !salesPrice) quantity = 0, salesPrice = 0
            $(row).find('.total-line-price').val(Math.round(totalDrugPrice).toFixed(2))
            amount += totalDrugPrice
        });

        let editableVat = $('#vat').val();
        grandAmount = parseFloat(editableVat) + parseFloat(amount);

        $('#subTotal').val(Math.round(amount).toFixed(2))
        $('#grandTotal').val(Math.round(grandAmount).toFixed(2))

        return Math.round(amount).toFixed(2)
    }



    function salePrice() {
        let salePrice        = $(this).val();
        let salesQty         = Number($(this).closest('tr').find('.sales-qty').val())

        totalLinePrice       = salePrice * salesQty;

        $(this).closest('tr').find('.total-line-price').val(totalLinePrice)

        linePrice()
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

</script>
