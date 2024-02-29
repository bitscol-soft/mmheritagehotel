
function loadDetails(settings) {



    settings = $.extend({

        type: 'name',
        selector: '',
        url: '',
        select: function () { },
        search: function () { }

    }, settings);




    $(document).on('focus', settings.selector, function () {



        $(this).autocomplete({

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
            response: function (event, ui) {
                if (ui.content.length == 0) {
                    $('#discount, #guardian, #mobile_no, #guest_id, #disease, #patient_age').val('').attr('readonly', false);
                }
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
        value = item.name
        label = item.name
    }


    if (type == 'nameWithNumber') {
        value = item.name + ' (' + item.phone_no + ')';
        label = item.name + ' (' + item.phone_no + ')';
    }
    if (type == 'number') {
        value = item.phone_no;
        label = item.name + ' (' + item.phone_no + ')';
    }

    if (type == 'nameWithQuantity') {
        value = item.name + ' (' + item.retail_quantity + ')';
        label = item.name + ' (' + item.retail_quantity + ')';
    }

    return {
        value,
        label,
        data: item,
    }


}
