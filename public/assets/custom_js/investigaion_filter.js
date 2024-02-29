$(document).on('keyup', '#patient_name, #mobile_no', function () {
    patientFilter()
});

$(document).on('click', '#patient_name,#mobile_no', function () {
    patientFilter()
});

function checkboxInvestigaion(checkboxInvestigaionId) {
    $.getJSON(requestUrl({ path: '/hospitals/load-investigation-patient', }), {
        id: checkboxInvestigaionId
    }, function (data) {
        loadInvestigationPatient(data);
    }

    )
}

function loadInvestigationPatient(data) {
    $('#investigaion_id').val(data.id);
    $('#patient_name_investigaion').val(data.patient.name);
    $('#customer_id_no').val(data.patient.customer_id);
    $('#patient_age').val(data.patient.age);
    $('#disease').val(data.patient.disease);
    $('#guardian').val(data.patient.guardian);
    $('#mobile_no').val(data.patient.mobile_number);
    $('#subTotal').val(data.subtotal);
    $('#discount').val(data.discount);
    $('#amountPaid').val(data.advance_paid);
    $('#amountDue').val(data.due_amount);
    var total = (data.subtotal) - (data.discount);
    var payable_amount = total.toFixed(2); // 100.52
    $('#payable_amount').val(payable_amount);
    $('#customer_id').val(data.patient.id).attr('readonly', true);
    $('[name=sex][value=' + data.patient.sex + ']').attr('checked', true);
    $('[name=patient_type][value=' + data.patient.type + ']').attr('checked', true);
    $('[name=blood][value=' + data.patient.blood + ']').attr('selected', true);

    let myTable = ``;
    $.each(data.test_advices, function (key, test) {
        myTable += ` <tr class="repeat-group">
                        <td>
                            <input type="hidden" class="service-id" name="service_id[]" value="${test.id}">
                                <input class="form-control service-name" type="text" name="name[]" id="service_0_name"
                                        value="${test.name}"/>
                        </td>
                        <td>
                            <input class="form-control service-prices" type="text" name="price[]" id="" value="${test.price}"
                                readonly/>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-xs r-btnRemove" onclick= deleteRow(this) ><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>`

    });

    $('#append').html(myTable);
    totalAmount();
}



function loadDetails(settings) {

    settings = $.extend({
        type: 'name',
        selector: '',
        url: '',
        select: function () { },
        search: function () { }
    }, settings);
    $(document).on('focus', settings.selector,
        function () {
            $(this).autocomplete({
                // minChars: 1,
                width: 402,
                matchContains: "word",
                autoFill: true,
                source: function (request, response) {
                    $.getJSON(settings.url, {
                        name: request.term
                    },
                        function (data) {
                            response($.map(data, function (item) {
                                return searchLabel(item, settings.type);
                            }))
                        }
                    )
                },
                select: function (event, ui) {
                    settings.select(event, ui);
                },
                search: function (event, ui) {
                    settings.search(event);
                },
                minLength: 1,
                autoFocus: true
            })
        })
}


function searchLabel(item, type) {
    let value, label;
    if (type == 'name') {
        value = item.patient.name
        label = item.patient.name
    }


    return {
        value,
        label,
        data: item,
    }


}
