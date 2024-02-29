$(document).on('click', '#guest_name,#mobile_no', function () {
    patientFilter()
});

$(window).load(function () {
    if ($('#guest_name').length > 0) {
        patientFilter()
        referenceFilter()
    }
})


$(document).on('keyup', '#guest_name, .service-names, #mobile_no', function () {
    patientFilter()
});



function loadPatient(event, ui, number = null) {
    
    if (number) {
        $('#guest_name_with_phone').val(ui.item.data.name);
        $('#guest_name').val(ui.item.data.name + ' -> ' + ui.item.data.phone_no);
    } else {
        $('#mobile_no').val(ui.item.data.phone_no);
    }
    $('#disease').val(ui.item.data.disease);
    $('#address').val(ui.item.data.address);
    $('#nid_or_password').val(ui.item.data.nid_no);
    $('#hotel_guest_id').val(ui.item.data.id).attr('readonly', true);
    $('#guest_id_no').val(ui.item.data.id).attr('readonly', true);
    // $('[name=blood][value=' + ui.item.data.blood + ']').attr('selected', true)

    // call load patient due bill when load patient
    if (typeof loadPatientDueBill === "function") {
        loadPatientDueBill()
    }

}

function resetPatient($customer_id = null) {
    $('#discount').val(0).attr('readonly', false);
    $('#customer_id_no').val($customer_id).attr('readonly', true);
    $('#patient_ages').val('').attr('readonly', false);
    $('#mobile_no').val('').attr('readonly', false);
    $('#disease').val('').attr('readonly', false);
    $('#customer_id').val('').attr('readonly', true);
}


function discountCalculator(totalAmount, discount) {
    if (!discount) {
        discount = 0;
    }
    let amount = totalAmount - totalAmount * discount / 100;
    $('#payable_amount').val(amount | 0);
    // $('#due_amount').val(amount);

    if ($('#paid').val() > amount) {
        $('#payable_amount').val(amount);
        // $('#due_amount').val(0);
        // $('#discount').val(discount);
        $('#paid').val(amount);
        alert('You cant pay more than payable amount!');
    }
}


function patientWithIndoor(type) {
    loadDetails({
        type: 'name',
        selector: '#guest_name',


        url: requestUrl({
            path: 'hotelservice/get-guest-list?type=',
            param: type,
        }),

        select: function (event, ui) {
            loadPatient(event, ui)
            if (ui.item.data.indoor_bookings) {
                let booking_info = ui.item.data.indoor_bookings;
                let ward = booking_info.ward.name;
                let cabin = booking_info.cabin.space_location;

                $('#patient-ward').val(ward)
                $('#patient-cabin').val(cabin)
            } else {
                $('#patient-ward').prop('value', '');
                $('#patient-cabin').prop('value', '');
            }
        }
    })
}


function selfFilter(discountType) {

    selfReset()



    loadDetails({
        type: 'nameWithNumber',
        selector: '#guest_name',


        url: requestUrl({
            path: 'hotelservice/get-guest-list?type=',
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
                        }

                        else if (discountType == "indoor") {
                            corporateBasedDiscount = ui.item.data.corporate.indoor_discount;
                        }

                        else if (discountType == "admission") {
                            corporateBasedDiscount = ui.item.data.corporate.admission_discount;
                        }

                        else if (discountType == "service") {
                            corporateBasedDiscount = ui.item.data.corporate.service_discount;
                        }

                        else {
                            corporateBasedDiscount = ui.item.data.corporate.ot_discount;
                        }
                        // $('#discount').val(corporateBasedDiscount).attr('readonly', true);
                    }
                )
            } else if (ui.item.data.cardtype) {
                let cardtype_id = ui.item.data.cardtype.id;
                $.getJSON(requestUrl({ path: 'hospitals/get-card-discount', }), { id: cardtype_id },
                    function (cardtype_id) {
                        let cardtypeBasedDiscount = 0;
                        if (discountType == "outdoor") {
                            cardtypeBasedDiscount = ui.item.data.cardtype.outdoor_discount;
                        }

                        else if (discountType == "indoor") {
                            cardtypeBasedDiscount = ui.item.data.cardtype.indoor_discount;
                        }


                        else if (discountType == "admission") {
                            cardtypeBasedDiscount = ui.item.data.cardtype.admission_discount;
                        }


                        else if (discountType == "service") {
                            cardtypeBasedDiscount = ui.item.data.cardtype.service_discount;
                        }


                        else {
                            cardtypeBasedDiscount = ui.item.data.cardtype.ot_discount;
                        }

                        if (discountType == "service") {
                            let serviceDiscount = '(' + cardtypeBasedDiscount + '%)';
                            $('#service-discount').text(serviceDiscount);
                            $('#service-discount-val').val(cardtypeBasedDiscount);
                        } else {
                            $('#discount').val(cardtypeBasedDiscount).attr('readonly', true);
                        }

                    }
                )
            } else if (ui.item.data.indoor_bookings) {
                let indoor_info = ui.item.data.indoor_bookings;


            }

            else {

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
            path: 'hotelservice/get-guest-list?type=',
            param: 'number',
        }),
        select: function (event, ui) {
            loadPatient(event, ui, number = 'Numeric')
        }
    })
}




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
            $('#guest_name').val('').attr('readonly', false);
        } else {
            $('#guest_name').val('').attr('readonly', true);
        }
        loadDetails({
            type: 'nameWithNumber',
            selector: '#guest_name',
            url: requestUrl({
                path: 'hotelservice/get-guest-list?corporate_client_id=',
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

            // loadPatient(event, ui)
            $('#guest_name').val('');
            $('#customer_id').val('');

            loadDetails({
                type: 'nameWithNumber',
                selector: '#guest_name',
                url: requestUrl({
                    path: 'hotelservice/get-guest-list?card_number=',
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
    $('#guest_name').val('').attr('readonly', true);
    $('#room_number').val('').attr('readonly', true);
    $('#discount_group').removeClass('hidden', true);
}

function corporateReset() {
    $('#card-number').val('');
    // $('#reference-by-agent, #reference_by_agent_id').val('');
    $('.corporate-section').removeClass('hidden');
    $('.card-section, .self').addClass('hidden');
    $("#corporate_client_id").val("0").change();
    $('#guest_name').val('').attr('readonly', true);
    $('#room_number').val('').attr('readonly', true);
    $('#discount_group').removeClass('hidden', true);
}

function selfReset() {
    $('#card-number').val('');
    $('.self').removeClass('hidden');
    $('.card-section, .corporate-section').addClass('hidden');
}
