function loadSelect2(settings) {
    settings = $.extend({
        url:'',
        select:'',
        templateResult: '',
        templateSelection: '',
        method:'get',
    }, settings);

    $(settings.select).select2({
        ajax: {
            url: settings.url,
            type: settings.method,
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
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

function templateResult(title, subtitle) {

    var $container = $(
        "<div class='select2-result clearfix'>" +
        "<div class='select2-result__title'></div>" +
        "<div class='select2-result__description'></div>" +
        "</div>" +
        "</div>" +
        "</div>"
    );

    $container.find(".select2-result__title").text(title);
    $container.find(".select2-result__description").text(subtitle);

    return $container;
}
