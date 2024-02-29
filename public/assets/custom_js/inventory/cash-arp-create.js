
// global js variable for this page
let trim_details            = ''
let new_item_row            = ''
let trim_option_select      = ''
let database_style_response = ''

// when page load run this method
$(document).ready(function () {
    if ($('.edit-create').val() == 'create') {
        $('.store-data-info').hide()
        $('.save-arp-cash-order-btn').attr('disabled', true)
    } else {
        $.ajax({
            url     : $('.trim-details-url').val(),
            data    : {
                year            : $('.select-year').val(),
                season_id       : $('.select-season').val(),
                buyer_id        : $('.select-buyer').val(),
                order_type_id   : $('.select-order-type').val(),
                // sid : '',
            },
            type    : 'GET',
            success : function (res) {
                trim_details = res

                trim_option_select =    '<div>' +
                    '<input name="old_work_order_detail_ids[]" type="hidden" value="">' +
                    '<select style="width: 250px !important;" onchange="selectTrimDetailsItem(this)" name="trim_ids[]" class="chosen-select-100-percent input-sm select-trim-details">'+
                    '<option value="">-Select-</option>'
                $(trim_details).each(function (index, item) {
                    trim_option_select += '<option value=" '+ item.id + '" data-order-quantity="' + item.total_arp_ordered_quantity + '" data-image="' + item.image + '" data-trim-type="' + item.trim_type.name + '" data-unit="' + item.item_unit.name + '">' + item.name + '(' + item.trim_type.name + '/' + item.item_unit.name + ')' + '</option>\n'
                })
                trim_option_select += '</select>'
                trim_option_select += '</div>'


                let url = $('.buyer-style-url').val()
                $.ajax({
                    url: url.replace('buyer_id', $('.select-buyer').val()),
                    type: 'GET',
                    success: function (res) {
                        let styles = ''
                        if(res.styles.length > 0) {
                            database_style_response = res
                            let buyer_id = res.id
                            if(res.styles.length > 2) {
                                styles += '<span style="margin-right: 10px !important;">'
                                styles += '<label>'
                                styles += '<input type="checkbox" class="ace styles" value="">'
                                styles += '<span class="lbl" onclick="checkboxClickAll(this)" style="font-weight:800"> Check All </span>'
                                styles += '</label>'
                                styles += '</span> '
                            }
                            $(res.styles).each(function (index, item) {
                                if (index > 0) {
                                    styles += ', '
                                }


                                styles += '<span class="order-style" data-order-qty="' + item.order_qty + '">'
                                styles += '<label>'
                                if (item.order_qty > 0) {
                                    styles += '<input name="old_styles[][]" class="old-name-input-checkbox" type="hidden" value="">'
                                    styles += '<input type="checkbox" class="ace styles name-input-checkbox input-checkbox" name="styles[][]" value="' + item.id + '">'
                                } else {
                                    styles += '<input type="checkbox" disabled="disabled" class="ace name-input-checkbox styles" name="styles[][]" value="' + item.id + '">'
                                }
                                styles += '<span class="lbl" onclick="checkboxClick(this)" > ' + item.style_number + '</span>'
                                styles += '</label>'
                                styles += '</span>'
                            })
                        }

                        new_item_row =   '<tr>' +
                            '<td class="serial">1</td>' +
                            '<td>' + trim_option_select + '</td>' +
                            '<td class="trim-image"></td>' +
                            '<td class="row-style" style="min-width: 200px !important;">'+ styles + '</td>' +
                            '<td class="total-style-count"></td>' +
                            '<td class="order-quantity"></td>' +
                            '<td class="order-quantity-done"></td>' +

                            '<td><input style="width: 80px !important;" onkeyup="workOrderQuantityClick(this)" class="form-control text-center work-order-quantity" name="work_order_quantities[]" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57"></td>' +
                            '<td class="remark"><input style="width: 120px !important;" class="form-control text-center" name="remarks[]" type="text" ></td>' +
                            '<td class=""><input style="width: 80px !important;" onkeyup="calculateFcPrice(this)" name="fc_values[]" class="form-control text-center fc-price" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57"></td>' +
                            '<td class=""><input style="width: 80px !important;" onkeyup="calculateBdtPrice(this)" name="bdt_values[]" class="form-control text-center bdt-price" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57"></td>' +
                            '<td class=""><input style="width: 80px !important;" readonly class="form-control text-center total-fc-price" name="total_fc_values[]" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57"></td>' +
                            '<td class=""><input style="width: 80px !important;" readonly class="form-control text-center total-bdt-price" name="total_bdt_values[]" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57"></td>' +
                            '<td class="text-center"><button type="button" onclick="removeTrimItemRow(this)" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button></td>' +
                            '</tr>'
                    },
                    error: function (err) {
                        alert('Style not found')
                    }
                })
            }
        })
    }

})


// when submit store form on click Save button
$('.save-arp-cash-order-btn').click(function () {
    let tr = $('.buyer-tbody').find('tr')

    $(tr).each(function (index, item) {
        let c = 0
        $(item).find('.name-input-checkbox').each(function (i, checkboxItem) {
            if ($(checkboxItem).prop('checked')) {
                $(checkboxItem).attr('name', 'styles[' + index + '][' + (c++) + ']' )
            }
        })
    })
    let is_submit = true
    if ($('.buyer-tbody').find('tr').length < 1) {
        is_submit = false
        alert('At least 1 trim you have to add')
    } else if($('.select-company').val() == '') {
        is_submit = false
        alert('Please select company')
    } else if($('.work-order-date').val() == '') {
        is_submit = false
        alert('Please select work order date')
    } else if($('.select-supplier').val() == '') {
        is_submit = false
        alert('Please select supplier')
    } else if($('.total-selected-styles').text() < 1) {
        is_submit = false
        alert('Please check styles')
    } else {
        let count_trim = 0
        $('.select-trim-details').each(function (index, item) {
            if ($(item).val() != '') {
                count_trim++
                let tr = $(item).closest('tr')
                if ($(tr).find('.work-order-quantity').val() == '' || $(tr).find('.fc-price').val() == '' || $(tr).find('.bdt-price').val() == '') {
                    is_submit = false
                    alert('Fill work order quantity, fc price and bdt price for ' +  $(item).find('option:selected').text())
                    return false
                }
            }
        })

        if (count_trim == 0) {
            alert('Please select at least one trim details')
            is_submit = false
        }
    }
    if (is_submit) {
        $('#arp_order_store_form').submit()
    }
})

// when add buyer info data using Set button
$('.add-buyer-item').click(function () {
    let sid             = $('.select-sid').val()
    let buyer_id        = $('.select-buyer').val()
    let select_year     = $('.select-year').val()
    let order_type_id   = $('.select-order-type').val()
    let select_season   = $('.select-season').val()

    if (buyer_id != '' && order_type_id != '' && select_year != '' && select_season != '') {
        $('.input-buyer-id').val(buyer_id)
        $('.input-order-type-id').val(order_type_id)
        $('.input-year').val(select_year)
        $('.input-season-id').val(select_season)
        $('.input-sid').val(sid)

        populateBuyerInfoTable();
        $('.store-data-info').show()
        $('.save-arp-cash-order-btn').attr('disabled', false)

        loadTrimToChosen()
    } else {
        alert('Select all field properly')
    }
})

// load all trim from database using ajax request
function loadTrimToChosen()
{
    $.ajax({
        url     : $('.trim-details-url').val(),
        data    : {
            year            : $('.select-year').val(),
            season_id       : $('.select-season').val(),
            buyer_id        : $('.select-buyer').val(),
            order_type_id   : $('.select-order-type').val(),
            // sid : 'will be add ',
        },
        type    : 'GET',
        success : function (res) {
            trim_details = res

            trim_option_select =    '<div>' +
                '<input name="old_work_order_detail_ids[]" type="hidden" value="">' +
                '<select style="width: 250px !important;" onchange="selectTrimDetailsItem(this)" name="trim_ids[]" class="chosen-select-100-percent input-sm select-trim-details">'+
                '<option value="">-Select-</option>'
            $(trim_details).each(function (index, item) {
                trim_option_select += '<option value=" '+ item.id + '" data-order-quantity="' + item.total_arp_ordered_quantity + '" data-image="' + item.image + '" data-trim-type="' + item.trim_type.name + '" data-unit="' + item.item_unit.name + '">' + item.name + '(' + item.trim_type.name + '/' + item.item_unit.name + ')' + '</option>\n'
            })
            trim_option_select += '</select>'
            trim_option_select += '</div>'

        }
    })
}

// populate buyer info into working table
function populateBuyerInfoTable() {
    let buyer_id = $('.select-buyer option:selected').val()
    // buyer info table
    let buyer_tfoot =   '<tfoot>' +
                            '<tr>' +
                                '<th colspan="4"><strong>Total: </strong></th>' +
                                '<th class="total-selected-styles"></th>' +
                                '<th class="total-order-quantity"></th>' +
                                '<th class="total-order-quantity-done"></th>' +
                                '<th class="total-work-order-quantity"></th>' +
                                '<th colspan="3"></th>' +
                                '<th class="total-fc-amount text-center"></th>' +
                                '<th class="total-bdt-amount text-center"></th>' +
                                '<th>' +
                                    '<button type="button" onclick="addTrimItem()" class="add-new-trim-item btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add</button>' +
                                '</th>' +
                            '</tr>' +
                        '</tfoot>'

    let buyer_table =  '<table class="table table-sm table-responsive table-bordered">\n' +
                            '<thead>\n' +
                                '<tr>\n' +
                                    '<th rowspan="2">Sl</th>\n' +
                                    '<th rowspan="2">Trim Name</th>\n' +
                                    '<th rowspan="2">Image</th>\n' +
                                    '<th rowspan="2">Styles</th>\n' +
                                    '<th rowspan="2">TTL. Style</th>\n' +
                                    '<th rowspan="2">Order Qty</th>\n' +
                                    '<th rowspan="2" title="Work Order Qty (already done)">W/O Qty Done</th>\n' +
                                    '<th rowspan="2">W/O Qty</th>\n' +
                                    '<th rowspan="2" class="text-center">Remarks</th>\n' +
                                    '<th colspan="2" class="text-center">Unit Price</th>\n' +
                                    '<th colspan="2" class="text-center">Total Price</th>\n' +
                                    '<th rowspan="2" class="text-center" width="30px">Action</th>\n' +
                                '</tr>\n' +

                                '<tr>' +
                                    '<td class="text-center">FC</td>' +
                                    '<td class="text-center">BDT</td>' +
                                    '<td class="text-center">FC</td>' +
                                    '<td class="text-center">BDT</td>' +
                                '</tr>' +
                            '</thead>\n' +

                            '<tbody class="buyer-tbody">' +
                            '</tbody>' +
                            buyer_tfoot +
                        '</table>'

    // $('.buyer-style-area').empty().append(buyer_info)
    $('.buyer-style-area').empty().append(buyer_table)
    getBuyerStyles(buyer_id);
}

// when change trim dropdown on buyer info table
function selectTrimDetailsItem (object) {
    let trim = $(object).children("option:selected")
    let trim_id = trim.val()
    let count = 0
    $('.select-trim-details').each(function (index, item) {
        if (parseInt($(item).val()) == parseInt(trim_id)) {
            count++
        }
    })

    if (count == 1) {
        $(object).closest('tr').find('.trim-unit').text(trim.data('unit'))
        $(object).closest('tr').find('.order-quantity-done').text(trim.data('order-quantity'))
        if (trim.data('image') != null) {
            $(object).closest('tr').find('.trim-image').html('<img class="trim-image" onclick="fullScreenImageDisplay(this)" width="50px" height="50px" src="' + $('.image-asset-url').val() + trim.data('image').substring(2) + '">')
        } else  {
            $(object).closest('tr').find('.trim-image').html('')
        }
    } else if (count > 1) {
        alert($(object).children("option:selected").text() + ' Already added')
        $(object).val('')
        $(object).closest('tr').find('.trim-image').html('')
        $('.select-trim-details').trigger('chosen:updated')
    }

}

// display image full screen when click on image
function fullScreenImageDisplay(object) {
    // modal info
    let modal   = $("#myModal")
    let img     = $(object).attr('src')

    modal.css("display", "block")
    $("#img01").attr('src', img)

    // When the user clicks on <span> (x), close the modal
    $('.close').click(function () {
        modal.css('display', 'none')
    })
}


// get buyers style from database using ajax
function getBuyerStyles(buyer_id) {
    let url = $('.buyer-style-url').val()
    $.ajax({
        url: url.replace('buyer_id', buyer_id),
        type: 'GET',
        success: function (res) {
            populateBuyerStyles(res)
        },
        error: function (err) {
            alert('Style not found')
        }
    })
}

// count total checked styles
function countTotalStyles() {
    let total = 0
    let total_order_quantity = 0

    $('.total-style-count').each(function (index, item) {
        if ($(item).text() != '') {
            total += parseInt($(item).text())
            total_order_quantity += parseInt($(item).closest('tr').find('.order-quantity').text())
        }
    })
    $('.total-selected-styles').text(total)
    $('.total-order-quantity').text(total_order_quantity)
}

// populate styles of selected buyers
function populateBuyerStyles(res) {

    let styles = ''
    if(res.styles.length > 0) {
        database_style_response = res
        let buyer_id = res.id
        if(res.styles.length > 2) {
            styles += '<span style="margin-right: 10px !important;">'
            styles += '<label>'
            styles += '<input type="checkbox" class="ace styles" value="">'
            styles += '<span class="lbl" onclick="checkboxClickAll(this)" style="font-weight:800"> Check All </span>'
            styles += '</label>'
            styles += '</span> '
        }
        $(res.styles).each(function (index, item) {
            if (index > 0) {
                styles += ', '
            }


            styles += '<span class="order-style" data-order-qty="' + item.order_qty + '">'
                styles += '<label>'
                    if (item.order_qty > 0) {
                        styles += '<input type="checkbox" class="ace styles name-input-checkbox input-checkbox" name="styles[][]" value="' + item.id + '">'
                    } else {
                        styles += '<input type="checkbox" disabled="disabled" class="ace name-input-checkbox styles" name="styles[][]" value="' + item.id + '">'
                    }
                    styles += '<span class="lbl" onclick="checkboxClick(this)" > ' + item.style_number + '</span>'
                styles += '</label>'
            styles += '</span>'
        })
    }

    new_item_row =   '<tr>' +
                        '<td class="serial">1</td>' +
                        '<td>' + trim_option_select + '</td>' +
                        '<td class="trim-image"></td>' +
                        '<td class="row-style" style="min-width: 200px !important;">'+ styles + '</td>' +
                        '<td class="total-style-count"></td>' +
                        '<td class="order-quantity"></td>' +
                        '<td class="order-quantity-done"></td>' +

                        '<td><input style="width: 80px !important;" onkeyup="workOrderQuantityClick(this)" class="form-control text-center work-order-quantity" name="work_order_quantities[]" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57"></td>' +
                        '<td class="remark"><input style="width: 120px !important;" class="form-control text-center" name="remarks[]" type="text" ></td>' +
                        '<td class=""><input style="width: 80px !important;" onkeyup="calculateFcPrice(this)" name="fc_values[]" class="form-control text-center fc-price" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57"></td>' +
                        '<td class=""><input style="width: 80px !important;" onkeyup="calculateBdtPrice(this)" name="bdt_values[]" class="form-control text-center bdt-price" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57"></td>' +
                        '<td class=""><input style="width: 80px !important;" readonly class="form-control text-center total-fc-price" name="total_fc_values[]" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57"></td>' +
                        '<td class=""><input style="width: 80px !important;" readonly class="form-control text-center total-bdt-price" name="total_bdt_values[]" type="text" onkeypress="return event.charCode >= 46 && event.charCode <= 57"></td>' +
                        '<td class="text-center"><button type="button" onclick="removeTrimItemRow(this)" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button></td>' +
                    '</tr>'

    addTrimItem()
}


// when click on Check All checkbox
function checkboxClickAll(object) {
    let is_checked = !$(object).closest('label').find('input:checkbox').is(':checked')

    if (is_checked) {
        let style_count = $(object).closest('tr').find('.order-style').length
        let total_order_quantity = 0
        $(object).closest('tr').find('.order-style').each(function (index, item) {
            total_order_quantity += parseInt($(item).data('order-qty'))
        })
        $(object).closest('tr').find('.total-style-count').text(style_count)
        $(object).closest('tr').find('.order-quantity').text(total_order_quantity)
    } else {
        $(object).closest('tr').find('.total-style-count').text('')
        $(object).closest('tr').find('.order-quantity').text('')
    }

    $(object).closest('tr').find('.input-checkbox').prop('checked', is_checked);
    countTotalStyles()
}

// when click on single checkbox of style
function checkboxClick(object) {
    let checkboxes                      = $(object.closest('tr')).find('.input-checkbox')
    let this_item_value                 = 0;
    let this_item_amount                = 0
    let total_check_without_this        = 0
    let total_check_without_this_amount = 0

    if($(object).closest('.order-style').find('input:checkbox').is(':checked')) {
        this_item_value = 1
    }
    this_item_amount = $(object).closest('.order-style').data('order-qty')

    $(checkboxes).each(function(index, item) {
        if (item.checked) {
            total_check_without_this++
            total_check_without_this_amount += parseInt($(item).closest('.order-style').data('order-qty'))
        }
    })
    let total_item_checked      = 0
    let total_order_quantity    = 0

    if (this_item_value == 1) {
        total_item_checked = total_check_without_this - 1
        total_order_quantity = parseInt(total_check_without_this_amount) - parseInt(this_item_amount)
    } else {
        total_item_checked = total_check_without_this + 1
        total_order_quantity = parseInt(total_check_without_this_amount) + parseInt(this_item_amount)
    }
    $(object).closest('tr').find('.total-style-count').text(total_item_checked)
    $(object).closest('tr').find('.order-quantity').text(total_order_quantity)
    countTotalStyles()
}

function calculateFcPrice(object) {
    if ($(object).val() != '') {
        let to_bdt_amount = $('.to-bdt').val()
        let work_order_quantity = $(object).closest('tr').find('.work-order-quantity').val()

        if (to_bdt_amount == '') {
            alert('Please select currency conversion')
        } else if (work_order_quantity == '') {
            alert('Please add work order quantity')
        } else {

            let fc_amount = Number($(object).val())
            let bdt_amount = Number(to_bdt_amount) * fc_amount
            $(object).closest('tr').find('.bdt-price').val(bdt_amount)
            $(object).closest('tr').find('.total-fc-price').val(Number(work_order_quantity) * fc_amount)
            $(object).closest('tr').find('.total-bdt-price').val(Number(work_order_quantity) * bdt_amount)
        }
    }
    calculateGrandTotal()
}

function calculateBdtPrice(object) {
    if ($(object).val() != '') {
        let to_bdt_amount = $('.to-bdt').val()
        let work_order_quantity = $(object).closest('tr').find('.work-order-quantity').val()
        if (to_bdt_amount == '') {
            alert('Please select currency conversion')
        } else if (work_order_quantity == '') {
            alert('Please add work order quantity')
        } else {
            let bdt_amount = Number($(object).val())
            let fc_amount =  bdt_amount / Number(to_bdt_amount)
            $(object).closest('tr').find('.fc-price').val(fc_amount)
            $(object).closest('tr').find('.total-fc-price').val(Number(work_order_quantity) * fc_amount)
            $(object).closest('tr').find('.total-bdt-price').val(Number(work_order_quantity) * bdt_amount)
        }
    }
    calculateGrandTotal()
}

function workOrderQuantityClick(object) {
    if ($(object).val() != '') {
        let work_order_quantity = $(object).val()
        let fc = $(object).closest('tr').find('.fc-price').val()
        let bdt = $(object).closest('tr').find('.bdt-price').val()
        let to_bdt_amount = $('.to-bdt').val()

        if (fc != '' && bdt != '' && to_bdt_amount != '') {
            $(object).closest('tr').find('.total-fc-price').val(Number(work_order_quantity) * Number(fc))
            $(object).closest('tr').find('.total-bdt-price').val(Number(work_order_quantity) * Number(bdt))
        }
    }
    calculateGrandTotal()
}

function calculateGrandTotal() {
    let total_bdt_amount = 0
    let total_fc_amount = 0
    let total_work_order_quantity = 0

    $('.work-order-quantity').each(function (index, item) {
        if ($(item).val() != '') {
            total_work_order_quantity += parseInt($(item).val())
        }
        if ($(item).closest('tr').find('.total-bdt-price').val() != '' ) {
            total_bdt_amount += Number($(item).closest('tr').find('.total-bdt-price').val())
        }
        if ($(item).closest('tr').find('.total-fc-price').val() != '' ) {
            total_fc_amount += Number($(item).closest('tr').find('.total-fc-price').val())
        }
    })
    $('.total-work-order-quantity').text(total_work_order_quantity)
    $('.total-bdt-amount').text(total_bdt_amount)
    $('.total-fc-amount').text(total_fc_amount)
}

function updateTrimDetailsTableRowSerial() {
    $('.serial').each(function (index, item) {
        $(item).text(index+1)
    })
}

function addTrimItem() {
    $('.buyer-tbody').append(new_item_row)
    updateTrimDetailsTableRowSerial()
    $('.select-trim-details').chosen({allow_single_deselect:true})
    $('.select-trim-details').trigger('chosen:updated')
}

function removeTrimItemRow(object) {
    $(object).closest('tr').remove()
    updateTrimDetailsTableRowSerial(object)
    countTotalStyles()
    calculateGrandTotal()
}



function setCurrencyValue(element) {
    let rate = $(element.options[element.selectedIndex]).attr('data-rate')
    $(element).parents().eq(4).find('.to-bdt').val(rate)
}



// get sid when change buyer id or order type id by chosen select
function getSids() {
    let order_type_id = $('.select-order-type').val()
    let buyer_id = $('.select-buyer').val()

    $.ajax({
        url: $('.get_sid_route').val(),
        type: 'GET',
        data: {
            buyer_id : buyer_id,
            order_type_id: order_type_id
        },
        success: function(res) {
            $('.select-sid').empty();
            let options = '<option value="">selects</option>'

            $.each(res, function(index, item) {
                options += '<option value="' + item.id + '">' + item.sid + '</option>'
            });
            $('.select-sid').append(options).trigger('chosen:updated');
            $('.sid-style-details-panel').text('')
            empty_pi_details_panel()
        }
    });
}


// get style numbers when change sid
function getStylesBySid()
{
    let sid = $('.select-sid').val()
    let sid_text = $('.select-sid option:selected').text()
    let is_sid_added = false
    let sid_count = 0

    $('.selected-sids').each(function () {
        sid_count++
        if($(this).text() == sid_text) {
            is_sid_added = true
        }
    })

    if (is_sid_added == true) {
        alert('Sid Already Selected, Please try another')
    } else if (sid) {
        $.ajax({
            url: $('.get_style_number_route').val(),
            type: 'GET',
            data: {
                sid : sid,
            },
            success: function(res) {

                $('.buyer-style-numbers').empty()
                let style_numbers = '';

                $.each(res, function (index, item) {
                    if (index > 0) {
                        style_numbers += ', '
                    }
                    style_numbers += (index + 1) + '.'
                    style_numbers += item
                })

                if (style_numbers == '') {
                    alert('Style not found for this sid')
                    if (sid_count == 0) {
                        empty_pi_details_panel()
                    }
                } else {
                    let sid = '<sapan class="selected-sids" style="font-size: 14px"><span class="btn btn-xs btn-danger" title="Remove This Sid" style="cursor: pointer" onclick="removeSidDetails(this)"><i class="fa fa-times"></i></span> <strong>' + sid_text + '</strong><input name="sids[]" type="hidden" value="' + $('.select-sid').val() + '"></sapan>: '
                    style_numbers = '<span>' + style_numbers + '</span>'
                    let row = '<p class="selected-style-numbers px-1 py-1" style="background: #c4c82e45;">' + sid + style_numbers + '</p>'

                    $('.sid-style-details-panel').append(row)
                    $('.buyer-style-numbers').text(style_numbers)
                    $('.no-style-found').hide()
                    $('.trim-panel').show()
                    if ($('.trim-panel').is(":hidden")) {
                        $(".select-currency").attr('disabled', false)
                        $('.select-currency').val($('.input-currency-id').val()).trigger('chosen:updated')
                        $('.to-bdt').attr('readonly', false)
                    }

                }
            }
        });
    } else if(sid_count == 0) {
        $('.no-style-found').show()
    }
}

function empty_pi_details_panel()
{
    $('.no-style-found').show()
    $('.pi_details_table_body').empty()
    $('.trim-panel').hide()
    calculateGrandTotal()
}


function removeSidDetails(object) {
    $(object).closest('.selected-style-numbers').remove()
}
