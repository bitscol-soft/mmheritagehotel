

// input by keyboard enter event on chosen
$("#job-types").chosen()
container = $("#job-types").data("chosen").container
container.bind("keypress", function(event){
    if (event.keyCode == 13) {
        event.preventDefault();
        $('.subcontract-quantity').focus()
    }
});


// <!-- added item when press enter
$('.subcontract-quantity').keypress(function(event) {
    // <!-- first catch key code
    let keycode = (event.keyCode ? event.keyCode : event.which);

    // <!-- check key is enter
    if(keycode == '13') {
        event.preventDefault();
        $('.select-fc-rate').focus()
    }
})

// <!-- added item when press enter
$('.select-fc-rate').keypress(function(event) {
    // <!-- first catch key code
    let keycode = (event.keyCode ? event.keyCode : event.which);

    // <!-- check key is enter
    if(keycode == '13') {
        event.preventDefault();
        $('#bdt_price').focus()
    }
})


function addJobTypeItemForCash()
{
    let style               = $("#select-styles option:selected")
    let currencyRate        = $("#currencyRate").val() > 0 ? parseFloat($("#currencyRate").val()) : 0
    let jobTypeId           = $("#job-types").val()
    let qty                 = $("#qty").val() > 0 ? parseFloat($("#qty").val()) : 0
    let fc_price            = $("#fc_price").val() > 0 ? parseFloat($("#fc_price").val()) : 0
    let bdt_price           = $("#bdt_price").val() > 0 ? parseFloat($("#bdt_price").val()) : 0;
    let jobType             = $("#job-types option:selected");

    let fc_value            = fc_price * qty
    let bdt_value           = bdt_price * qty



    if (!style.val()) {
        swal.fire({
            title: "Error",
            html: "<b>Please Select Style !</b>",
            type: "error",
            timer: 1000
        });
    } else if (!currencyRate) {
        swal.fire({
            title: "Error",
            html: "<b>Please Select Currency !</b>",
            type: "error",
            timer: 1000
        });
    } else if (!jobTypeId) {
        swal.fire({
            title: "Error",
            html: "<b>Please Select Job Type !</b>",
            type: "error",
            timer: 1000
        });
    } 
    // else if (!qty) {
    //     swal.fire({
    //         title: "Error",
    //         html: "<b>Please Set Qty !</b>",
    //         type: "error",
    //         timer: 1000
    //     });
    // } 
    else if (!fc_price) {
        swal.fire({
            title: "Error",
            html: "<b>Please Set Fc Price !</b>",
            type: "error",
            timer: 1000
        });
    } else {
        let jobTypeName = jobType.text()
        let item_unit = $(".select-item-unit option:selected")

        let is_added = true
        let style_id = style.data('val')

        $('#job-type-detail-tbody tr').each(function() {
            let row_job_type_id = $(this).find('.select-job-type-id').val()
            let row_style_id = $(this).find('.select-style-id').val()

            if (style_id == row_style_id && jobTypeId == row_job_type_id) {
                
                showAlertMessage(jobTypeName + ' is already added for this ' + style.text() + ' style')
                is_added = false
            }
        })

        let tr = `<tr> 
                    <td>
                        <button type="button" title="Remove Item" class="btn btn-minier btn-danger serial" onclick="removeTr(this)"></button>
                    </td>
                    <td><input type="hidden" class="select-style-id" name="style_ids[]" value="${ style_id }">${ style.text() }</td>
                    <td><input style="width: 75px" type="text" readonly name="order_qtys[]" class="order-quantity text-center" value="${ style.data('order-quantity') }"></td>
                    <td><input type="hidden" class="select-job-type-id" name="job_type_ids[]" value="${ jobTypeId }">${ jobTypeName }</td>
                    <td class="text-center">
                        <input type="hidden" class="input-done-qty" onkeypress="return event.charCode >= 46 && event.charCode <= 57" name="done_qtys[]" value="">
                        <span class="text-done-qty"></span>
                    </td>
                    <td class="text-center">
                        <input type="hidden" class="remaining-qty" onkeypress="return event.charCode >= 46 && event.charCode <= 57" name="remaining_qtys[]" value="">
                        <span class="text-remaining-qty"></span>
                    </td>
                    <td><input type="text" class="form-control fc-quantity input-small text-center" name="qtys[]" onkeyup="checkStyleQty(this)" value="${ qty }"></td>
                    <td><input style="width: 75px" type="text" name="tollereance_percent[]" class="text-center" onkeyup="calculateTolleranceQty(this)" onkeypress="return event.charCode >= 46 && event.charCode <= 57" value=""></td>
                    <td class="text-center"><input style="width: 75px" type="text" readonly name="tollerence_qtys[]" value="${ qty }" class="tollerence-qty text-center"></td>
                    <td><input type="hidden" name="item_units[]" value="${ item_unit.val() }">${ item_unit.text() }</td>

                    <td><input type="text" style="width: 75px" class="form-control item-fc-price text-center input-small" name="fc_prices[]" readonly  value="${ fc_price }"></td>
                    <td><input type="text" style="width: 75px" class="form-control item-bdt-price text-center input-small" name="bdt_prices[]" readonly  value="${ bdt_price }"></td>
                    <td><input type="text" style="width: 75px" class="form-control fc-value text-center input-small" name="fc_values[]" readonly  value="${ fc_value }"></td>
                    <td><input type="text" style="width: 75px" class="form-control job-type-bdt-value text-center input-small" name="bdt_values[]" readonly value="${ bdt_value }"></td>
                    <td><textarea name="remarks[]" class="form-control"></textarea></td>
                </tr>`



        if (is_added == true) {
            $("#job-type-detail-tbody").append(tr)
            $("#job-types").val('').trigger('chosen:updated');

            $("#qty").val('')
            $("#fc_price").val('')
            $("#bdt_price").val('')

            $('.to-bdt').attr('readonly', true)

            setItemserial()

            let trs = $("#job-type-detail-tbody tr:last")
            getPreviousQty(trs, style_id, jobTypeId)
        }
    }
}

function checkStyleQty(object)
{
    let remaining_qty = $(object).closest('tr').find('.text-remaining-qty').text()
    let fc_price = $(object).closest('tr').find('.item-fc-price').val()
    let bdt_price = $(object).closest('tr').find('.item-bdt-price').val()
    let quantity = $(object).val()


    if (Number(quantity) > Number(remaining_qty)) {
        $(object).val(0)
        showAlertMessage('Quantity can not be greater than remainging quantity')
    }

    
    $(object).closest('tr').find('.fc-value').val((Number(fc_price) * Number(quantity)).toFixed(2))
    $(object).closest('tr').find('.job-type-bdt-value').val((Number(bdt_price) * Number(quantity)).toFixed(2))

    setItemserial()
}

function getPreviousQty(tr, style_id, job_type_id)
{

    $.get('/inventory/get-subcontract-work-order-previous-quantity', { style_id : style_id, job_type_id : job_type_id }, function(res) {
        $(tr).find('.input-done-qty').val(res)
        $(tr).find('.text-done-qty').text(res)
        let order_qty = $(tr).find('.order-quantity').val()
        $(tr).find('.remaining-qty').val(Number(order_qty) - Number(res))
        $(tr).find('.text-remaining-qty').text(Number(order_qty) - Number(res))
    })
}

function setItemserial()
{
    $('.serial').each(function(index) {
        $(this).text(index + 1)
    })

    calculateGrandTotal()
}

function calculateTolleranceQty(object)
{
    let tollerencePercent = $(object).val() | 0
    let jobTypeQty = $(object).closest('tr').find('.fc-quantity').val()

    let tollerenceQuantity = (Number(jobTypeQty) * Number(tollerencePercent)) / 100

    $(object).closest('tr').find('.tollerence-qty').val(Number(jobTypeQty) + Number(tollerenceQuantity))
}


function removeTr(object)
{
    let style_id = $(object).closest('tr').find('.select-style-id').val()
    let job_type_id = $(object).closest('tr').find('.select-job-type-id').val()
    

    $("#job-types option[value=" + job_type_id + "]").removeAttr('disabled')
    $("#job-types").val('').trigger('chosen:updated')


    $("#select-styles option[value=" + style_id + "]").removeAttr('disabled')
    $("#select-styles").val('').trigger('chosen:updated');

    $(object).closest("tr").remove();

    setItemserial()
}

function setFcprice(evnt)
{

    // <!-- first catch key code
    let keycode = (evnt.keyCode ? evnt.keyCode : evnt.which);

    // <!-- check key is enter
    if(keycode == '13') {
        evnt.preventDefault()
        addJobTypeItemForCash()
    }

    let currencyBdtRate = $('#currencyRate').val() | 0
    let bdtPrice        = $('#bdt_price').val()
    let quantity        = $('#qty').val()

    if (currencyBdtRate < 1) {
        $('#bdt_price').val('')
        swal.fire({
            title: "Error",
            html: "<b>Please select currency and bdt price must be greater than 0 !</b>",
            type: "error",
            timer: 4000
        });
        
    } else {
        $('#fc_price').val(((bdtPrice / currencyBdtRate)).toFixed(2))
    }
}


function calculateGrandTotal()
{
    let total_order_quantity        = 0
    let total_quantity              = 0
    let total_tollerence_quantity   = 0
    let total_fc_value              = 0
    let total_bdt_value             = 0

    $('#job-type-detail-tbody tr').each(function(index) {
        total_order_quantity        += Number($(this).find('.order-quantity').val())
        total_quantity              += Number($(this).find('.fc-quantity').val())
        total_tollerence_quantity   += Number($(this).find('.tollerence-qty').val())
        total_fc_value              += Number($(this).find('.fc-value').val())
        total_bdt_value             += Number($(this).find('.job-type-bdt-value').val())
    })

    $('.total-order-quantity').text(total_order_quantity)
    $('.total-quantity').text(total_quantity)
    $('.total-tollerence-quantity').text(total_tollerence_quantity)
    $('.total-fc-value').text(total_fc_value)
    $('.total-bdt-value').text(total_bdt_value)
}
