function loadSelect2(settings) {
    settings = $.extend({
        url:'',
        select:'',
        templateResult: '',
        templateSelection: '',
        method:'get',
    }, settings);

    $(select).select2({
        ajax: {
            url: settings.url,
            type: settings.method,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    page: params.current_page
                };
            },
            processResults: function(data, params) {
                params.current_page = params.current_page || 1;

                return {
                    results: data,
                    pagination: {
                        more: (params.current_page * 30) < data.total
                    }
                };
            },
            autoWidth: true,
            cache: true
        },
        placeholder: 'Search for a data',
        minimumInputLength: 1,
        templateResult: settings.templateResult,
        templateSelection: settings.templateSelection
    });
}





function loadDetails(settings){

    settings = $.extend({
        type: 'name',
        selector: '',
        url: '',
        select: function () {},
        search: function () {}
    }, settings);

    $(document).on('focus', settings.selector, function () {
        $(this).autocomplete({
            source: function(request, response){
                $.getJSON(settings.url,
                    {name: request.term},
                    function (data) {
                        response($.map(data, function (item) {
                            return searchLabel(item, settings.type);
                        }))
                    }
                )
            },
            select: function(event, ui){
                settings.select(event, ui);
            },
            search: function( event, ui ) {
                settings.search(event);
            },
            minLength: 1,
            autoFocus: true
        })
    })
}



function searchLabel(item, type)
{
    let value, label;
    if (type == 'name'){
        value = item.name
        label = item.name
    }
    if (type == 'nameWithNumber') {
        value =  item.name +' ('+item.mobile_number +')';
        label =  item.name +' ('+item.mobile_number +')';
    }

    if (type == 'nameWithQuantity') {
        value =  item.name +' ('+item.retail_quantity +')';
        label =  item.name +' ('+item.retail_quantity +')';
    }

    if (type == 'cardNumber') {
        value = item.card_number;
        label = item.card_number;
    }

    return {
        value,
        label,
        data: item,
    }

}
