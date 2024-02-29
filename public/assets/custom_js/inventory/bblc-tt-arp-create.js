

$(document).ready(function () {
    $('.trim-panel').hide()
})

// submit main supplier pi form
function submitPiForm(event) {

    $("#changeValue").val(0);

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
        messages = '<li> Select Company</li>'
        checkValidation = false
    }

    if (checkValidation == true) {
        $('.trim-item').each(function () {
            if($(this).prop('checked') && $(this).closest('tr').find('.work_order_qty').val() == '') {
                messages = '<li> Please Set W/O Qty</li>'
                checkValidation = false
            }
        })
    }

    // if ($('.work_order_qty').val() == '') {
    //     messages = '<li> Please Set W/O Qty</li>'
    //     checkValidation = false
    // }


    if ($('.pi_reference').val() == '') {
        messages += '<li> Pi reference can not be empty</li>'
        checkValidation = false
    }
    messages += '</ul>'

    if (checkValidation == true) {
        $('.supplier-pi-form').submit()
    } else {
        $('.validation-alert').removeClass('hidden')
        $('.validation-alert').empty().append(messages)
    }
}


// trim details change event
$(".chosen-select-230").chosen()
if ($(".chosen-select-230").data("chosen")) {
    container = $(".chosen-select-230").data("chosen").container;
    container.bind("keypress", function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            $('.item-quantity').focus()
        }
    });
}

$(".chosen-select-100-percent").chosen()
$(".chosen-select-100-percent").data("chosen")
{
    container = $(".chosen-select-100-percent").data("chosen").container;
    container.bind("keypress", function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            $('.item-quantity').focus()
        }
    });
}



// <!-- added item when press enter
$('.item-quantity').keypress(function(event) {
    let key_code = (event.keyCode ? event.keyCode : event.which);

    if (key_code == '13') {
        event.preventDefault();
        if ($('.item-quantity').val() != '') {
            $('.fc-rate').focus()
        }
    }
});

// <!-- added item when press enter
$('.fc-rate').keypress(function(event) {
    let key_code = (event.keyCode ? event.keyCode : event.which);

    if (key_code == '13') {
        event.preventDefault();
        $('.item-rate').focus()
    }
});



// delete trim details item row from table
function deleteItem(el) {
    let trim_detail_id = $(el).closest('tr').find('.trim-detail-id').val();
    $(el).closest('tr').remove();
    calculateGrandTotal();

    $(".chosen-select-230 option[value=" + trim_detail_id + "]").removeAttr('disabled');
    $(".chosen-select-230").focus();
    $('.chosen-select-230').val('').trigger('chosen:updated')
}

// <!-- set currency value when change currency -->
function setCurrencyValue(element) {
    let rate = $(element.options[element.selectedIndex]).attr('data-rate');
    $(element).parents().eq(4).find('.to-bdt').val(rate)
}

// <!-- added item when press enter



// display image full screen when click on image
function fullScreenImageDisplay(object) {
    // modal info
    let modal   = $("#myModal");
    let img     = $(object).attr('src');

    modal.css("display", "block");
    $("#img01").attr('src', img);

    // When the user clicks on <span> (x), close the modal
    $('.close').click(function () {
        modal.css('display', 'none')
    })
}


$('.rate-vice-versa').keyup(function () {
    if ($(this).val() != '') {
        let fc_bdt_text = $(this).attr('placeholder');
        let to_bdt_amount = $('.to-bdt').val();

        let rate = $(this).val()
        if (fc_bdt_text == 'FC') {
            $('.item-rate').val(Number(rate) * Number(to_bdt_amount))
        } else {
            $('.fc-rate').val(Number(rate) / Number(to_bdt_amount))
        }
    }
})

// <!-- calculate trim detail table total -->
function calculateGrandTotal() {

    let count           = 0;
    let average_rate    = 0;
    let average_fc_rate = 0;
    let total_quantity  = 0;
    let total_amount    = 0;
    let total_fc_amount = 0;

    $('.quantity').each(function (i, item) {
        $(item).closest('tr').find('td:first').text(i + 1);
        total_quantity  += parseFloat($(item).text());
        total_fc_amount += parseFloat($(item).closest('tr').find('.fc_amount').text());
        total_amount    += parseFloat($(item).closest('tr').find('.amount').text());
        count++
    })

    if (count > 0) {
        average_fc_rate = (total_fc_amount / total_quantity);
        average_rate    = (total_amount / total_quantity)
    }

    // <!-- set grand total value -->
    $('.total-quantity').text(total_quantity.toFixed(2));
    $('#total_qty').val(total_quantity.toFixed(2));
    $('.average-rate').text(average_rate.toFixed(2));
    $('.average-fc-rate').text(average_fc_rate.toFixed(4));
    $('.total-fc-amount').text(total_fc_amount.toFixed(2));
    $('#total_fcAmount').val(total_fc_amount.toFixed(4));
    $('.total-amount').text(total_amount.toFixed(2))
}


// get sid when change buyer id or order type id by chosen select
function getSids() {
    let order_type_id = $('.select-order-type').val();
    let buyer_id = $('.select-buyer').val();

    $.ajax({
        url: $('.get_sid_route').val(),
        type: 'GET',
        data: {
            buyer_id : buyer_id,
            order_type_id: order_type_id
        },
        success: function(res) {
            $('.select-sid').empty();
            let options = '<option value="">select</option>';

            $.each(res, function(index, item) {
                options += '<option value="' + item.id + '">' + item.sid + '</option>'
            });
            $('.select-sid').append(options).trigger('chosen:updated');
            $('.sid-style-details-panel').text('');
            empty_pi_details_panel()
        }
    });
}



// get sid when change buyer id or order type id by chosen select
function getEditSids() {
    let order_type_id = $('.select-order-type').val();
    let buyer_id = $('.select-buyer').val();

    $.ajax({
        url: $('.get_sid_route').val(),
        type: 'GET',
        data: {
            buyer_id : buyer_id,
            order_type_id: order_type_id
        },
        success: function(res) {
            $('.select-sid').empty();
            let options = '<option value="">select</option>';

            $.each(res, function(index, item) {
                options += '<option value="' + item.id + '">' + item.sid + '</option>'
            });
            $('.select-sid').append(options).trigger('chosen:updated');
            $('.sid-style-details-panel').text('');
            empty_pi_details_panel()
        }
    });
}

// get style numbers when change sid
function getStylesBySid()
{
    let sid = $('.select-sid').val();
    let sid_text = $('.select-sid option:selected').text();
    let is_sid_added = false;
    let sid_count = 0;

    $('.selected-sids').each(function () {
        sid_count++
        if($(this).text() == sid_text) {
            is_sid_added = true
        }
    });

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
                let sl = 1;

                $(".selected-sids").each(function () {
                    sl ++
                });

                // if (style_numbers == '') {
                //     alert('Style not found for this sid')
                //     if (sid_count == 0) {
                //         empty_pi_details_panel()
                //     }
                // } else {
                    let sid = '<span class="" style="font-size: 14px"><span class="btn btn-xs btn-danger" title="Remove This Sid" style="cursor: pointer" onclick="removeSidDetails(this)"><i class="fa fa-times"></i></span> <strong class="selected-sids">' + sid_text + '</strong><input name="sids[]" type="hidden" id="selected_sid_'+sl+'" value="'+ $('.select-sid').val() + '"></span>: '
                    style_numbers = '<span id="style_number">' + res + '</span>'
                    let row = '<p class="selected-style-numbers px-1 py-1" style="background: #c4c82e45;">' + sid + style_numbers + '</p>'

                    $('.sid-style-details-panel').append(row);
                    $('.buyer-style-numbers').html(style_numbers);
                    $('.no-style-found').hide();
                    $('.trim-panel').show();
                    if ($('.trim-panel').is(":hidden")) {
                        $(".select-currency").attr('disabled', false);
                        $('.select-currency').val($('.input-currency-id').val()).trigger('chosen:updated');
                        $('.to-bdt').attr('readonly', false)
                    }

                // }
            }
        });
    } else if(sid_count == 0) {
        $('.no-style-found').show()
    }
}


// get style numbers when change sid
function getEditStylesBySid()
{
    let sid = $('.select-sid').val();
    let sid_text = $('.select-sid option:selected').text();
    let is_sid_added = false;
    let sid_count = 0;

    $('.selected-sids').each(function () {
        sid_count++
        if($(this).text() == sid_text) {
            is_sid_added = true
        }
    });

    if (is_sid_added == true) {
        swal.fire({
            title: "Error",
            html: "<b>Sid Already Selected, Please try another</b>",
            type: "error",
            timer: 3000
        });

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


                    let sid = '<span class="" style="font-size: 14px"><span class="btn btn-xs btn-danger" title="Remove This Sid" style="cursor: pointer" onclick="removeSidDetails(this)"><i class="fa fa-times"></i></span> <strong class="selected-sids">' + sid_text + '</strong><input name="sids[]" type="hidden" value="' + $('.select-sid').val() + '"></span>: '
                    style_numbers = '<span id="style_number">' + res + '</span>'
                    let row = '<p class="selected-style-numbers px-1 py-1" style="background: #c4c82e45;">' + sid + style_numbers + '</p>'

                    $('.sid-style-details-panel').append(row);
                    $('.buyer-style-numbers').html(style_numbers);
                    $('.no-style-found').hide();
                    $('.trim-panel').show();
                    if ($('.trim-panel').is(":hidden")) {
                        $(".select-currency").attr('disabled', false);
                        $('.select-currency').val($('.input-currency-id').val()).trigger('chosen:updated');
                        $('.to-bdt').attr('readonly', false)
                    }

            }
        });
    } else if(sid_count == 0) {
        $('.no-style-found').show()
    }
}

function empty_pi_details_panel()
{
    $('.no-style-found').show();
    $('.pi_details_table_body').empty();
    $('.trim-panel').hide();
    calculateGrandTotal()
}


function removeSidDetails(object) {
    $(object).closest('.selected-style-numbers').remove()
}



