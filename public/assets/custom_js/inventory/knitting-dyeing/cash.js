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


function addCompositionItemForCash()
{
    let style               = $("#select-composition")
    let currencyRate        = $("#currencyRate").val() > 0 ? parseFloat($("#currencyRate").val()) : 0
    let qty                 = $("#qty").val() > 0 ? parseFloat($("#qty").val()) : 0
    let fc_price            = $("#fc_price").val() > 0 ? parseFloat($("#fc_price").val()) : 0
    let bdt_price           = $("#bdt_price").val() > 0 ? parseFloat($("#bdt_price").val()) : 0;

    let fc_value            = roundLikePHP(fc_price * qty)
    let bdt_value           = roundLikePHP(bdt_price * qty)

    if (!style.val()) {
        swal.fire({
            title: "Error",
            html: "<b>Please Select Yarn !</b>",
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
    } else if (!qty) {
        swal.fire({
            title: "Error",
            html: "<b>Please Set Qty !</b>",
            type: "error",
            timer: 1000
        });
    } else if (!fc_price) {
        swal.fire({
            title: "Error",
            html: "<b>Please Set Fc Price !</b>",
            type: "error",
            timer: 1000
        });
    } else {
        let composition = $("#select-composition option:selected");

        let tr = `<tr>
                    <td>
                        <button type="button" title="Remove Item" class="btn btn-minier btn-danger serial" onclick="removeTr(this)"></button>
                    </td>
                    <td><input type="hidden" class="select-job-type-id" name="composition_ids[]" value="${ composition.val() }">${ composition.text() }</td>
                    <td><input type="text" style="width: 100%" class="form-control fc-quantity input-small text-center" name="qtys[]" readonly value="${ qty }"></td>
                    <td><input type="text" style="width: 100%" class="form-control text-center input-small" name="fc_prices[]" readonly  value="${ fc_price }"></td>
                    <td><input type="text" style="width: 100%" class="form-control text-center input-small" name="bdt_prices[]" readonly  value="${ bdt_price }"></td>
                    <td><input type="text" style="width: 100%" class="form-control fc-value text-center input-small" name="fc_values[]" readonly  value="${ fc_value }"></td>
                    <td><input type="text" style="width: 100%" class="form-control bdt-value text-center input-small" name="bdt_values[]" readonly value="${ bdt_value }"></td>
                    <td><textarea name="remarks[]" class="form-control"></textarea></td>
                </tr>`

        $("#job-type-detail-tbody").append(tr)
        $("#select-composition option[value=" + composition.val() + "]").attr('disabled',true)
        $("#select-composition").val('').trigger('chosen:updated');

        $("#qty").val('')
        $("#fc_price").val('')
        $("#bdt_price").val('')

        $('.to-bdt').attr('readonly', true)
        setItemserial()
    }
}

function setItemserial()
{
    $('.serial').each(function(index) {
        $(this).text(index + 1)
    })
    calculateGrandTotal()
}

function calculateGrandTotal()
{
    let total_quantity              = 0
    let total_fc_value              = 0
    let total_bdt_value             = 0

    $('#job-type-detail-tbody tr').each(function(index) {
        total_quantity              += Number($(this).find('.fc-quantity').val())
        total_fc_value              += Number($(this).find('.fc-value').val())
        total_bdt_value             += Number($(this).find('.bdt-value').val())
    })

    $('.total-quantity').text(total_quantity)
    $('.total-fc-value').text(total_fc_value)
    $('.total-bdt-value').text(total_bdt_value)
    $("#grand_total_qty").text(total_quantity + ' kgs');
    $("#grand_total_value").text('USD ' + total_fc_value);
}

function removeTr(object)
{
    let style_id = $(object).closest('tr').find('.select-job-type-id').val()
    $("#select-composition option[value=" + style_id + "]").removeAttr('disabled')
    $("#select-composition").val('').trigger('chosen:updated');
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
        addCompositionItemForCash()
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
