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

function addCompositionItemForBbblOrTt()
{
    let style               = $("#particular")
    let currencyRate        = $("#currencyRate").val() > 0 ? parseFloat($("#currencyRate").val()) : 0
    let qty                 = $("#qty").val() > 0 ? parseFloat($("#qty").val()) : 0
    let fc_price            = $("#fc_price").val() > 0 ? parseFloat($("#fc_price").val()) : 0
    let bdt_price           = $("#bdt_price").val() > 0 ? parseFloat($("#bdt_price").val()) : 0;
    let unitId              = $('#item-unit').val();

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
    } else if (!unitId) {
        swal.fire({
            title: "Error",
            html: "<b>Please Set Unit !</b>",
            type: "error",
            timer: 1000
        });
    } else {
        let particular = $("#particular");

        let tr = `<tr>
                    <td>
                        <button type="button" title="Remove Item" class="btn btn-minier btn-danger serial" onclick="removeTr(this)"></button>
                    </td>
                    <td><input type="hidden" class="select-job-type-id" name="particulars[]" value="${ particular.val() }">${ particular.val() }</td>
                    <td><input type="text" style="width: 100%" class="form-control fc-quantity input-small text-center" name="qtys[]" readonly value="${ qty }"></td>
                    <td style="text-align: center"><input type="hidden" style="width: 100%" name="units[]" readonly value="${ unitId }"> ${$('#item-unit option:selected').text()}</td>
                    <td><input type="text" style="width: 100%" class="form-control text-center input-small" name="fc_prices[]" readonly  value="${ fc_price }"></td>
                    <td><input type="text" style="width: 100%" class="form-control text-center input-small" name="bdt_prices[]" readonly  value="${ bdt_price }"></td>
                    <td><input type="text" style="width: 100%" class="form-control fc-value text-center input-small" name="fc_values[]" readonly  value="${ fc_value }"></td>
                    <td><input type="text" style="width: 100%" class="form-control bdt-value text-center input-small" name="bdt_values[]" readonly value="${ bdt_value }"></td>
                    <td><textarea name="remarks[]" class="form-control"></textarea></td>
                </tr>`

        $("#job-type-detail-tbody").append(tr)

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
    $(object).closest("tr").remove();
    setItemserial()
}

function setFcprice(evnt)
{
    // <!-- first catch key code
    let keycode = (evnt.keyCode ? evnt.keyCode : evnt.which);

    // <!-- check key is enter
    if(keycode == '13') {
        evnt.preventDefault();
        addCompositionItemForBbblOrTt()
    }

    let currencyBdtRate = $('#currencyRate').val() | 0
    let bdtPrice        = $('#bdt_price').val()

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

        if ($('#fc_price').val() == '0.00') {
            $('#fc_price').val('')
        }
    }
}
