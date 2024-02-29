function calculateTableTotal(tbody)
{
    let tableTotalQuantity  = 0
    let tableTotalFcValue   = 0
    let fcPrice             = $(tbody).closest('.yarn-root').find('.yarn-fc-price').text() | 0


    $(tbody).find('tr').each(function (index) {
        $(this).find('.item-serial').text(index + 1)
        let fcQuantity = Number($(this).find('.item-quantity').val() | 0)

        let subTotal = fcQuantity * (fcPrice | 0)
        $(this).find('.item-subtotal').text(subTotal)

        tableTotalQuantity  += fcQuantity
        tableTotalFcValue   += subTotal
    })

    // set table total value
    let tfoot = tbody.closest('table').find('tfoot')

    tfoot.find('.tableTotalQuantity').text(tableTotalQuantity)
    tfoot.find('.tableTotalFcValue').text(tableTotalFcValue)

    calculateGrandTotal()
}

function calculateGrandTotal() {
    let grandTotalQuantity  = 0
    let grandTotalValue     = 0

    $('.tableTotalQuantity').each(function () {
        grandTotalQuantity += Number($(this).text() | 0)
        grandTotalValue += Number($(this).closest('tfoot').find('.tableTotalFcValue').text() | 0)
    })

    $('.grandTotalQuantity').text(grandTotalQuantity)
    $('.grandTotalValue').text(grandTotalValue)
}


function addNewYarnItem(object)
{
    let yarn_id  = $(object).closest('.yarn-root').find('.yarn-id').val()
    let tbody    = $(object).closest('.yarn-root').find('.yarn-table')
    let fc_price = $(object).closest('.yarn-root').find('.yarn-fc-price').text()

    tbody.append(`<tr>
                    <td class="item-serial"></td>
                    <td>
                        <input type="hidden" name="old_sweater_yarn_composition_detail_ids[${ yarn_id }][]" >
                        <input type="text" class="form-control input-sm" name="color_names[${ yarn_id }][]">
                    </td>
                    <td><input type="text" class="form-control input-sm" name="pantones[${ yarn_id }][]"></td>
                    <td><input type="text" class="form-control input-sm" name="lab_dip_refs[${ yarn_id }][]"></td>
                    <td><input type="text" class="form-control input-sm" name="color_numbers[${ yarn_id }][]"></td>
                    <td><input type="text" class="form-control input-sm date-picker" autocomplete="off" name="yarn_dates[${ yarn_id }][]"></td>
                    <td><input type="text" class="form-control input-sm" name="shades[${ yarn_id }][]"></td>
                    <td><input type="text" class="form-control item-quantity text-center" name="qtys[${ yarn_id }][]" onkeypress="return event.charCode >= 46 && event.charCode <= 57" onkeyup="checkQty(this)"></td>
                    <td><input type="text" name="rates[${ yarn_id }][]" class="form-control text-center" value="${ fc_price }" readonly></td>
                    <td><input type="text" name="subtotals[${ yarn_id }][]" class="form-control input-sm item-subtotal text-center" readonly></td>
                    <td><textarea name="remarks[${ yarn_id }][]" class="form-control"></textarea></td>
                    <td><button type="button" class="ibtnDel btn btn-minier btn-danger" onclick="removeTableTr(this)"><i class="fa fa-times-circle"></i></button></td>
                </tr>`)


    datepicker()
    calculateTableTotal(tbody)
}




function removeTableTr(object)
{
    let table = $(object).closest('table')

    $(object).closest("tr").remove();

    calculateTableTotal(table.find('tbody'))
}


function checkAndSubmitForm(object)
{
    if ($('.select-company').val() == '') {
        showAlertMessage('Please Select Company')
    } else if ($('.grandTotalQuantity').text() < 1 || $('.grandTotalValue').text() < 1) {
        showAlertMessage('Please check yarn input')
    } else {
        $(object).closest('form').submit()
    }
}


function showAlertMessage(message) {
    swal.fire({
        title: "Warning",
        html: `<b>${ message } !</b>`,
        type: "warning",
        timer: 4000
    });
}
