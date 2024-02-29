
let baseIndex = 0;
let today     = $('.select-date').val()
// setTableId()

// submit main supplier pi form
function submitPiForm(event) {
    let checkValidation = true
    let messages = '<ul>'
    if ($('.select-date').val() == '') {
        messages += '<li> Select Date</li>'
        checkValidation = false
    }
    if ($('.select-supplier option:selected').val() == '') {
        messages += '<li> Select Supplier</li>'
        checkValidation = false
    }
    if ($('.select-company option:selected').val() == '') {
        messages += '<li> Select Company</li>'
        checkValidation = false
    }
    // if ($('.select-sid option:selected').val() == '') {
    //     messages += '<li> Select S.Id</li>'
    //     checkValidation = false
    // }
    if ($('.select-currency option:selected').val() == '') {
        messages += '<li> Select Currency</li>'
        checkValidation = false
    }
    if ($('.grand-total-amount').text() <= 0) {
        messages += '<li> Please fill data  </li>'
        checkValidation = false
    }

    messages += '</ul>'
    if (checkValidation == true) {
        $('.supplier-cash-do-form').submit()
    } else {$('.validation-alert').empty()
        $('.validation-alert').removeClass('hidden')
        $('.validation-alert').append(messages)
    }
}


// add new input panel
function addNewPanel() {
    let yarn = $('.select-yarn option:selected')

    if (yarn.val() != '') {
        let data = ""
        data += '<div class="repeater-item">'
            data += '<div class="row clearfix items-table"  style="margin: 0 10px !important;">'
                data += '<div class="col-xs-12">'
                    data += '<table class="table table-sm table-bordered">'
                        data += '<thead class="table-header">'
                            data += '<tr>'
                                data += '<td colspan="12" style="background: white; font-weight: bold; font-size: 20px; color: #564e4e !important;"> <span class="yarn-serial"></span> ' + yarn.text() + '<input type="hidden" class="yarn-id" name="yarn_ids[]" value="' + yarn.val() + '"></td>'
                            data += '</tr">'

                            data += '<tr class="table-header-bg">'
                                data += '<th>Sl</th>'
                                data += '<th class="text-center">Color Name</th>'
                                data += '<th class="text-center">Pantone</th>'
                                data += '<th class="text-center" width="120px">Lab-dip ref.</th>'
                                data += '<th class="text-center">Color No</th>'
                                data += '<th class="text-center">Date</th>'
                                data += '<th class="text-center">Shade</th>'
                                data += '<th class="text-center">Qty/Lbs</th>'
                                data += '<th class="text-center">Rate</th>'
                                data += '<th class="text-center">Total</th>'
                                data += '<th class="text-center">Remarks</th>'
                                data += '<th><button type="button" class="btn btn-xs btn-danger" onclick="removeYarnTable(this)" style="letter-spacing: 1.5px"><i class="fa fa-times"></i></button></th>'
                            data += '</tr>'
                        data += '</thead>'

                        data += '<tbody id="item_table' + baseIndex + '" class="add-item-table-thead">'
                            data += '<tr>'
                                data += '<td>1</td>'
                                data += '<td><input class="form-control input-sm text-center" name="color_names[' + baseIndex + '][]"></td>'
                                data += '<td><input class="form-control input-sm text-center" name="pantnes[' + baseIndex + '][]"></td>'
                                data += '<td><input class="form-control input-sm text-center" name="lab_dip_refs[' + baseIndex + '][]"></td>'
                                data += '<td><input class="form-control input-sm text-center" name="color_nos[' + baseIndex + '][]"></td>'
                                data += '<td><input type="text" name="dates[' + baseIndex + '][]" class="form-control input-sm date-picker text-center" autocomplete="off" value="' + today + '"></td>'
                                data += '<td><input class="form-control input-sm text-center" name="shades[' + baseIndex + '][]"></td>'
                                data += '<td><input class="form-control quantity input-sm text-center" name="quantities[' + baseIndex + '][]" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" onkeyup="calculateTableSum(this, 1)"></td>'
                                data += '<td><input class="form-control rate input-sm text-center" name="rates[' + baseIndex + '][]" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" onkeyup="calculateTableSum(this, 2)"></td>'
                                data += '<td class="text-right"><span class="amount text-right"></span></td>'
                                data += '<td><input class="form-control input-sm text-center" name="remarks[' + baseIndex + '][]"></td>'
                                data += '<td><button type="button" class="btn btn-minier btn-danger btn-round" onclick="removeTableItem(this)" style="letter-spacing: 1.5px"><i class="fa fa-times"></i></button></td>'
                            data += '</tr>'
                        data += '</tbody>'

                        data += '<tfoot>'
                            data += '<tr>'
                                data += '<th colspan="7">Total</th>'
                                data += '<th class="total-quantity text-center"></th>'
                                data += '<th class="total-rate text-center"></th>'
                                data += '<th class="total-amount text-center"></th>'
                                data += '<th></th>'
                                data += '<th></th>'
                            data += '</tr>'

                            data += '<tr>'
                                data += '<td colspan="12" class="text-right">'
                                data += '<button type="button" onclick="addNewTableRow(this)" class="btn btn-xs btn-primary" style="letter-spacing: 1.5px"><i class="fa fa-plus"></i> Add New</button>'
                                data += '</td>'
                            data += '</tr>'
                        data += '</tfoot>'
                    data += '</table>'
                data += '</div>'
            data += '</div>'
        data += '</div>'

        $('.repeater').append(data);
        updateDatePicker()

        // reset added value
        $('.select-yarn option[value=' + yarn.val() + ']').attr('disabled','disabled')
        $('.select-yarn').val('').trigger('chosen:updated')
        $('.select-yarn').trigger('chosen:activate')
        setYarnSerial()
        baseIndex++
    } else {
        alert('Please select an yarn composition')
    }
}

function setYarnSerial() {
    $('.yarn-serial').each(function (index) {
        $(this).text(index + 1 + ". ")
    })
}
// remove dynamic table
function removeYarnTable(el) {
    if($('.remove-input-panel').length != 1) {
        let yarn_id = $(el).closest('.repeater-item').find('.yarn-id').val()
        $(".select-yarn option[value=" + yarn_id + "]").removeAttr('disabled');
        $(".select-yarn").focus()
        $('.select-yarn').val('').trigger('chosen:updated')

        $(el).closest('.repeater-item').remove()
        calculateGrandTotal()
        setYarnSerial()
    }
}

// <!-- add row into table using add new button
function addNewTableRow(el) {
    let table_head = $(el).closest('table').find('tbody').attr('id')
    let r = document.getElementById(table_head).insertRow();

    let index = table_head.substring(10, table_head.length)

    r.insertCell(0).innerHTML = ''
    r.insertCell(1).innerHTML = '<input class="form-control input-sm text-center" name="color_names[' + index + '][]">'
    r.insertCell(2).innerHTML = '<input class="form-control input-sm text-center" name="pantnes[' + index + '][]">'
    r.insertCell(3).innerHTML = '<input class="form-control input-sm text-center" name="lab_dip_refs[' + index + '][]">'
    r.insertCell(4).innerHTML = '<input class="form-control input-sm text-center" name="color_nos[' + index + '][]">'
    r.insertCell(5).innerHTML = '<input type="text" name="dates[' + index + '][]" class="form-control input-sm date-picker text-center" value="' + today + '" autocomplete="off" name="date[]">'
    r.insertCell(6).innerHTML = '<input class="form-control input-sm text-center" name="shades[' + index + '][]">'
    r.insertCell(7).innerHTML = '<input class="form-control quantity input-sm text-center" name="quantities[' + index + '][]" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" onkeyup="calculateTableSum(this, 1)">'
    r.insertCell(8).innerHTML = '<input class="form-control rate input-sm text-center" name="rates[' + index + '][]" onkeypress="return event.charCode == 46 || event.charCode >= 48 && event.charCode <= 57" onkeyup="calculateTableSum(this, 2)">'
    r.insertCell(9).innerHTML = '<span class="amount text-right"></span>'
    r.insertCell(10).innerHTML = '<input class="form-control input-sm text-center" name="remarks[' + index + '][]">'
    r.insertCell(11).innerHTML = '<button type="button" class="btn btn-minier btn-danger btn-round" onclick="removeTableItem(this)" style="letter-spacing: 1.5px"><i class="fa fa-times"></i></button>'

    // set table sl of table
    setTableColumnSerialNo(table_head)
    // call to function datepicker trigger update
    updateDatePicker()
}


// set serial of a table column
function setTableColumnSerialNo(table_head) {
    $('#'+table_head).find('tr').each(function (index, item) {
        $(item).find('td:first').text(index + 1)
    })
}
// remove or delete row form table
function removeTableItem(el) {
    let table       = $(el).closest('table')
    let table_body  = $(el).closest('tbody')
    let sumQuantity = 0
    let sumAmount   = 0
    let sumRate     = 0

    // delete row
    $(el).closest('tr').remove()

    table_body.find('tr').each(function (index, item) {
        if ($(item).find('.quantity').val() != '') {
            let quantity = parseFloat($(item).find('.quantity').val())
            sumQuantity += quantity
            if ($(item).closest('tr').find('.rate').val() != '') {
                let rate     = parseFloat($(item).closest('tr').find('.rate').val())
                $(item).closest('tr').find('.amount').text(quantity*rate)
                sumAmount += quantity*rate
            } else {
                sumAmount += 0
            }
        } else {
            sumAmount += 0
        }
    })
    if (sumQuantity>0) {
        sumRate = sumAmount/sumQuantity
    }

    table.find('.total-quantity').text(sumQuantity.toFixed(2))
    table.find('.total-amount').text(sumAmount.toFixed(2))
    table.find('.total-rate').text(sumRate.toFixed(2))
    calculateGrandTotal()
}

// calculate total quantity of a table
function calculateTableSum(event, type) {
    let sumQuantity = 0;
    let sumAmount   = 0
    let sumRate     = 0

    $(event).closest('tbody').find('tr').each(function (index, item) {
        if ($(item).find('.quantity').val() != '') {
            let quantity = parseFloat($(item).find('.quantity').val())
            sumQuantity += quantity
            if ($(item).closest('tr').find('.rate').val() != '') {
                let rate     = parseFloat($(item).closest('tr').find('.rate').val())
                $(item).closest('tr').find('.amount').text(quantity*rate)
                sumAmount += parseFloat($(item).closest('tr').find('.amount').text())
            } else {
                sumAmount += 0
            }
        } else {
            sumAmount = 0
        }
    })
    $(event).closest('table').find('.total-quantity').text(sumQuantity.toFixed(2))
    if (sumQuantity>0) {
        sumRate = sumAmount/sumQuantity
    }

    $(event).closest('table').find('.total-quantity').text(sumQuantity.toFixed(2))
    $(event).closest('table').find('.total-amount').text(sumAmount.toFixed(2))
    $(event).closest('table').find('.total-rate').text(sumRate.toFixed(2))

    calculateGrandTotal()

    // prevent input if grand total greater than total remaining pi value
    let totalRemainingValue = $('.remaining-pi-value').val()
    let grandTotal = $('.grand-total-amount').text()
    if (grandTotal != '') {
        if (parseFloat(grandTotal) > parseFloat(totalRemainingValue)) {
            alert("Grand total cann't be greater than Remaining Pi Vale (" + totalRemainingValue + ")")
            $(event).val('')
            calculateTableSum(event, type)
        }
    }
}


function calculateGrandTotal() {
    let grandTotalQuantity = 0
    let grandTotalRate     = 0
    let grandTotalAmount   = 0

    $('.total-quantity').each(function (index, item) {
        if ($(item).text() != '') {
            grandTotalQuantity += parseFloat($(item).text())
            if ($(item).closest('tr').find('.total-amount').text() != '') {
                grandTotalAmount += parseFloat($(item).closest('tr').find('.total-amount').text())
            }
        }
    })

    if (grandTotalQuantity>0) {
        grandTotalRate = grandTotalAmount/grandTotalQuantity
    }
    $('.grand-total-quantity').text(grandTotalQuantity.toFixed(2))
    $('.grand-total-amount').text(grandTotalAmount.toFixed(2))
    $('.grand-total-rate').text(grandTotalRate.toFixed(2))
}
// date picker update
function updateDatePicker() {
    jQuery(function($) {
        $('.date-picker').datepicker({
            autoclose: true,
            format:'dd-mm-yyyy',
            todayHighlight: true
        }).next().on(ace.click_event, function(){
            $(this).prev().focus();
        });
    })
}

// update currency value
function setCurrencyValue(element) {
    let rate = $(element.options[element.selectedIndex]).attr('data-rate')
    $(element).parents().eq(4).find('.to-bdt').val(rate)
}
