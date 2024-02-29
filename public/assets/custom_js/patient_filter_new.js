$(document).on('click', '#patient_name,#mobile_no', function () {
    patientFilter()
});

$(window).load(function () {
    patientFilter()
})


$(document).on('keyup', '#patient_name, .service-names, #mobile_no', function () {
    patientFilter()
});



function loadPatient(event, ui, number = null) {
    if (number) {
        $('#patient_name').val(ui.item.data.name);
    } else {
        $('#mobile_no').val(ui.item.data.mobile_number);
    }
    $('#customer_id_no').val(ui.item.data.customer_id).attr('readonly', true);
    $('#patient_age').val(ui.item.data.dob);
    $('#guardian').val(ui.item.data.guardian);
    $('#disease').val(ui.item.data.disease);
    $('#blood_pressure').val(ui.item.data.blood_pressure);
    $('#weight').val(ui.item.data.weight);
    $('#tempreture').val(ui.item.data.tempreture);
    $('#customer_id').val(ui.item.data.id).attr('readonly', true);
    $('[name=sex][value=' + ui.item.data.sex + ']').attr('checked', true);
    $('[name=patient_type][value=' + ui.item.data.type + ']').attr('checked', true);
    $('[name=blood][value=' + ui.item.data.blood + ']').attr('selected', true)
}

function resetPatient($customer_id = null) {
    $('#discount').val(0).attr('readonly', false);
    $('#customer_id_no').val($customer_id).attr('readonly', true);
    $('#patient_age').val('').attr('readonly', false);
    $('#mobile_no').val('').attr('readonly', false);
    $('#disease').val('').attr('readonly', false);
    $('#customer_id').val('').attr('readonly', false);
    // $('#commission_for_agent').prop('checked', false)
}


function discountCalculator(totalAmount, discount) {
    if (!discount) {
        discount = 0;
    }
    let amount = totalAmount - totalAmount * discount / 100;
    $('#payable_amount').val(amount);
    // $('#due_amount').val(amount);

    if ($('#paid').val() > amount) {
        $('#payable_amount').val(amount);
        // $('#due_amount').val(0);
        $('#discount').val(discount);
        $('#paid').val(amount);
        alert('You cant pay more than payable amount!');
    }
}



function selfFilter(discountType) {

    selfReset()
    loadDetails({
        type: 'nameWithNumber',
        selector: '#patient_name',
        url: requestUrl({
            path: 'hospitals/load-patient?type=',
            param: 'self',
        }),

        select: function (event, ui) {
            loadPatient(event, ui)
            if (ui.item.data.type == 'self') {
                $('.reference').removeClass('hidden');
            } else {
                $('.reference').addClass('hidden');
            }

            if (ui.item.data.corporate) {
                let corporate_client_id = ui.item.data.corporate.id;
                $.getJSON(requestUrl({ path: 'load-corporate', }), { id: corporate_client_id },
                    function (corporate_client) {
                        let corporateBasedDiscount = 0;
                        if (discountType == "outdoor") {
                            corporateBasedDiscount = ui.item.data.corporate.outdoor_discount;
                        } else {
                            corporateBasedDiscount = ui.item.data.corporate.indoor_discount;
                        }
                        $('#discount').val(corporateBasedDiscount).attr('readonly', true);
                    }
                )
            } else if (ui.item.data.cardtype) {
                let cardtype_id = ui.item.data.cardtype.id;
                $.getJSON(requestUrl({ path: 'loadCardDiscout', }), { id: cardtype_id },
                    function (cardtype_id) {
                        let cardtypeBasedDiscount = 0;
                        if (discountType == "outdoor") {
                            cardtypeBasedDiscount = ui.item.data.cardtype.outdoor_discount;
                        } else {
                            cardtypeBasedDiscount = ui.item.data.cardtype.indoor_discount;
                        }

                        $('#discount').val(cardtypeBasedDiscount).attr('readonly', true);
                    }
                )
            } else {
                $('#discount').val(0).attr('readonly', false);
            }
        }
    })
}


function mobileNumberFilter() {
    selfReset();
    // resetPatient();
    loadDetails({
        type: 'number',
        selector: '#mobile_no',
        url: requestUrl({
            path: 'hospitals/load-patient?type=',
            param: 'number',
        }),
        select: function (event, ui) {
            loadPatient(event, ui, number = 'Numeric')
        }
    })
}

// function investigaionPatientFilter() {
//     loadDetails({
//         type: 'name',
//         selector: '#patient_name',
//         url: requestUrl({
//             path: 'loadInvestigaionPatient',
//             // path: '/hospitals/load-patient',
//             // param: 'self',
//         }),
//         select: function(event, ui) {
//             // /hospitals/load-patient(event, ui)
//             console.log(ui);
//         }
//     })
// }


function corporateFilter(discountType) {
    corporateReset()

    $(document).on('change', '#corporate_client_id', function () {
        let corporate_client_id = $(this).val();
        $.getJSON(requestUrl({ path: 'load-corporate', }), {
            id: $(this).val()
        },
            function (corporate_client) {
                let corporateBasedDiscount = 0;
                if (discountType == "indoor") corporateBasedDiscount = corporate_client.indoor_discount;
                if (discountType == "outdoor") corporateBasedDiscount = corporate_client.outdoor_discount;
                $('#discount').val(corporateBasedDiscount).attr('readonly', true);
            }
        )

        resetPatient();
        if (corporate_client_id != 0) {
            $('#patient_name').val('').attr('readonly', false);
        } else {
            $('#patient_name').val('').attr('readonly', true);
        }
        loadDetails({
            type: 'nameWithNumber',
            selector: '#patient_name',
            url: requestUrl({
                path: 'hospitals/load-patient?corporate_client_id=',
                param: corporate_client_id
            }),
            select: function (event, ui) {
                loadPatient(event, ui)
            },
        })

    })
}

function cardFilter(discountType) {
    cardReset();
    loadDetails({
        type: 'cardNumber',
        selector: '#card-number',
        url: requestUrl({
            path: 'load-card-holder'
        }),
        select: function (event, ui) {
            $(this.selector).removeClass('has-error').attr('title', '');
            let cardBasedDiscount = 0;
            if (discountType == "indoor") {
                cardBasedDiscount = ui.item.data.card_type.indoor_discount;
            }
            if (discountType == "outdoor") {
                cardBasedDiscount = ui.item.data.card_type.outdoor_discount;
            }
            $('#discount').val(cardBasedDiscount).attr('readonly', true);
            $('#card_id').val(ui.item.data.card_type.id);

            // /hospitals/load-patient(event, ui)
            $('#patient_name').val('').attr('readonly', false);
            $('#customer_id').val('');

            loadDetails({
                type: 'nameWithNumber',
                selector: '#patient_name',
                url: requestUrl({
                    path: 'hospitals/load-patient?card_number=',
                    param: ui.item.data.card_number
                }),
                select: function (event, ui) {
                    loadPatient(event, ui)
                    $(this.selector).removeClass('has-error').attr('title', '');
                },
                search: function (event) {
                    // resetPatient();
                    $(this.selector).addClass('has-error').prop('title', 'Not Recognized');
                }
            })
        },
        search: function () {
            resetPatient();
            $(this.selector).addClass('has-error').prop('title', 'Not Recognized');
        }
    })
}

function cardReset() {
    $('.card-section').removeClass('hidden');
    $('.corporate-section, .self').addClass('hidden');
    // $('#reference_by_agent_id').val('');
    $('#patient_name').val('').attr('readonly', true);
    $('#discount_group').removeClass('hidden', true);
}

function corporateReset() {
    $('#card-number').val('');
    // $('#reference-by-agent, #reference_by_agent_id').val('');
    $('.corporate-section').removeClass('hidden');
    $('.card-section, .self').addClass('hidden');
    $("#corporate_client_id").val("0").change();
    $('#patient_name').val('').attr('readonly', true);
    $('#discount_group').removeClass('hidden', true);
}

function selfReset() {
    $('#card-number').val('');
    $('.self').removeClass('hidden');
    $('.card-section, .corporate-section').addClass('hidden');
}
