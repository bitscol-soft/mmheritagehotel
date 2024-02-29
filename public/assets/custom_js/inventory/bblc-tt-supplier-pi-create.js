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

    messages += '</ul>'
    if (checkValidation == true) {
        $('.supplier-pi-form').submit()
    } else {
        $('.validation-alert').removeClass('hidden')
        $('.validation-alert').empty().append(messages)
    }
}

$(".chosen-select-230").chosen()
container = $(".chosen-select-230").data("chosen").container
container.bind("keypress", function(event){
    if (event.keyCode == 13) {
        event.preventDefault();
        $('.item-quantity').focus()
    }
});

// <!-- added item when press enter
$('.item-quantity').keypress(function(event) {
    let keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
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

function deleteItem(el) {
    let yarn_id = $(el).closest('tr').find('.yarn-id').val()
    $(el).closest('tr').remove()
    calculateTotal()
    $(".chosen-select-230 option[value=" + yarn_id + "]").removeAttr('disabled');
    $(".chosen-select-230").focus()
    $('.select-yarn').val('').trigger('chosen:updated')
}

function setCurrencyValue(element) {
    let rate = $(element.options[element.selectedIndex]).attr('data-rate')
    $(element).parents().eq(4).find('.to-bdt').val(rate)
}
// <!-- added item when press enter
function addItem(el, event) {
    // <!-- check key is enter
    if ((event.keyCode ? event.keyCode : event.which) == '13') {
        // <!-- prevent form submit
        event.preventDefault()

        let rate        = Number($('.item-rate').val())
        let fc_rate     = Number($('.fc-rate').val())


        let yarn          = $('.select-yarn option:selected')
        let quantity    = $('.item-quantity').val()
        let amount      = parseFloat(quantity) * parseFloat(rate)
        let fc_amount   = parseFloat(quantity) * parseFloat(fc_rate)

        if (yarn.val() != '' &&  quantity != '' && rate != '') {
            let row =   '<tr>' +
                            '<td class="serial">1</td>' +
                            '<td>' + yarn.text() + '<input name="yarn_ids[]" class="yarn-id" type="hidden" value="' + yarn.val() + '"></td>' +
                            '<td class="text-center"><span class="quantity">' + quantity + '</span><input name="quantities[]" type="hidden" value="' + quantity + '"></td>' +
                            '<td class="text-right"><span class="fd_rate">' + fc_rate + '</span><input name="fc_rates[]" type="hidden" value="' + fc_rate + '"></td>' +
                            '<td class="text-right"><span class="rate">' + rate + '</span><input name="rates[]" type="hidden" value="' + rate + '"></td>' +
                            '<td class="text-right" style="text-align: right !important;"><span class="fc_amount" style="background: transparent !important; border: none !important">' + fc_amount + '</sapan></td>' +
                            '<td class="text-right"><span class="amount">' + amount + '</sapan></td>' +
                            '<td class="text-center"><button type="button" onclick="deleteItem(this)" class="btn btn-round btn-danger btn-xs"><i class="fa fa-times"></i></button></td>' +
                        '</tr>'

            $('#pi_details_table_head').append(row)


            // set empty rate and quantity field
            $('.item-rate').val('')
            $('.fc-rate').val('')
            $('.item-quantity').val('')

            // <!-- update trim details chosen trigger -->
            if ($('.input-currency-id').val() == '') {
                $('.input-currency-id').val($(".select-currency").val())
            }
            $(".select-currency").attr('disabled','disabled')
            $('.select-currency').val('').trigger('chosen:updated')
            $('.to-bdt').attr('readonly', true)

            // <!-- update trim details chosen trigger -->
            $(".chosen-select-230 option[value=" + yarn.val() + "]").attr('disabled','disabled')
            $(".chosen-select-230").focus()
            $('.chosen-select-230').val('').trigger('chosen:updated')
            $(".chosen-select-230").trigger('chosen:activate')


            // total amount calculate and set serial
            calculateTotal()
        }
    }
}


$('.rate-vice-versa').keyup(function () {
    if ($(this).val() != '') {
        let fc_bdt_text = $(this).attr('placeholder')
        let to_bdt_amount = $('.to-bdt').val()

        let rate = $(this).val()
        if (fc_bdt_text == 'FC') {
            $('.item-rate').val(Number(rate) * Number(to_bdt_amount))
        } else {
            $('.fc-rate').val(Number(rate) / Number(to_bdt_amount))
        }
    }
})

function calculateTotal() {
    let total_quantity  = 0
    let count           = 0
    let averageRate     = 0
    let averageFcRate   = 0
    let total_fc_amount = 0
    let total_amount    = 0

    $('.quantity').each(function (i, item) {
        $(item).closest('tr').find('td:first').text(i + 1)
        total_quantity  += parseFloat($(item).text())
        total_fc_amount += parseFloat($(item).closest('tr').find('.fc_amount').text())
        total_amount    += parseFloat($(item).closest('tr').find('.amount').text())
        count++
    })

    if (count>0) {
        averageRate = (total_amount/total_quantity)
        averageFcRate = (total_fc_amount/total_quantity)
    }

    $('.total-quantity').text(total_quantity.toFixed(2))
    $('.average-rate').text(averageRate.toFixed(2))
    $('.average-fc-rate').text(averageFcRate.toFixed(2))
    $('.total-amount').text(total_amount.toFixed(2))
    $('.total-fc-amount').text(total_fc_amount.toFixed(2))
}
