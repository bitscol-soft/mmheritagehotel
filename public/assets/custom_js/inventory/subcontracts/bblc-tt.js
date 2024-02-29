

function addJobTypeItemForBbblOrTt()
{
    let buyer_type          = $('.select-buyer-type').val()
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
        showAlertMessage('Please Select Style !')
    } else if (!currencyRate) {
        showAlertMessage('Please Select Currency !')
    } else if (!jobTypeId) {
        showAlertMessage('Please Select Job Type !')
    } else if (!qty) {
        showAlertMessage('Please Set Qty !')
    } else if (!fc_price) {
        showAlertMessage('Please Set Fc Price !')
    } else {
        let jobTypeName = jobType.text()
        let item_unit = $(".select-item-unit option:selected")

        let style_id = style.val()
        if (buyer_type == 'Export') {
            style_id = style.data('val')
        }
        let is_added = true

        $('#job-type-detail-tbody tr').each(function() {
            let row_job_type_id = $(this).find('.select-job-type-id').val()
            let row_style_id = $(this).find('.select-style-id').val()

            if (style_id == row_style_id && jobTypeId == row_job_type_id) {
                
                showAlertMessage(jobTypeName + ' is already added for this ' + style.text() + ' style')
                is_added = false
            }
        })

        let tr = `<tr> 
                    <td class="serial"></td>
                    <td><input type="hidden" class="select-style-id" name="style_ids[]" value="${ style_id }">${ style.text() }</td>
                    <td><input type="hidden" class="select-job-type-id" name="job_type_ids[]" value="${ jobTypeId }">${ jobTypeName }</td>
                    <td><input type="text" class="form-control fc-quantity input-small text-center" style="width: 100%" name="qtys[]" readonly value="${ qty }"></td>
                    <td><input type="hidden" name="item_units[]" value="${ item_unit.val() }">${ item_unit.text() }</td>

                    <td><input type="text" class="form-control text-center input-small" style="width: 100%" name="fc_prices[]" readonly  value="${ fc_price }"></td>
                    <td><input type="text" class="form-control text-center input-small" style="width: 100%" name="bdt_prices[]" readonly  value="${ bdt_price }"></td>
                    <td><input type="text" class="form-control fc-value text-center input-small" style="width: 100%" name="fc_values[]" readonly  value="${ fc_value }"></td>
                    <td><input type="text" class="form-control sc-bdt-value text-center input-small" style="width: 100%" name="bdt_values[]" readonly  value="${ bdt_value }"></td>
                    <td><textarea name="remarks[]" class="form-control"></textarea></td>
                    <td><button type="button" class="btn btn-minier btn-danger" onclick="removeTr(this)"><i class="fa fa-times"></i></button></td>
                </tr>`



        if (is_added == true) {
            $("#job-type-detail-tbody").append(tr)
            $("#job-types").val('').trigger('chosen:updated');

            $("#qty").val('')
            $("#fc_price").val('')
            $("#bdt_price").val('')

            $('.to-bdt').attr('readonly', true)

            serial()
        }
    }
}

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



// input by keyboard enter event on chosen
$("#job-types").chosen()
container = $("#job-types").data("chosen").container
container.bind("keypress", function(event){
    if (event.keyCode == 13) {
        event.preventDefault();
        $('.subcontract-quantity').focus()
    }
});



function serial(){
    $(".serial").each(function (index){
        $(this).text(index+1)
    })

    grandTotalCal()
}

function removeTr(object)
{
    let yarn_id = $(object).closest('tr').find('.select-yarn-id').val()
    $(object).closest('tr').remove()

    serial()

    $("#job-types option[value=" + yarn_id + "]").attr('disabled',false);
    $("#job-types").val('').trigger('chosen:updated');
}



function setFcprice(evnt)
{

    // <!-- first catch key code
    let keycode = (evnt.keyCode ? evnt.keyCode : evnt.which);

    // <!-- check key is enter
    if(keycode == '13') {
        evnt.preventDefault();
        addJobTypeItemForBbblOrTt()
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
