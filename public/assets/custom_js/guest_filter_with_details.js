$(document).on('focus', '#guest_name', function() {
    $(this).autocomplete({
        source: function(request, response) {
            $.getJSON('/hotelservice/get-guest-list', {
                name: request.term
                },
                function(data) {
                    response($.map(data, function(items) {
                        return {
                            value: items.available_quantity,
                            label: `${items.room_number}`,
                            data: items,
                        }
                    }))
                }
            )
        },
        select: function(event, ui) {
            let rooms_id = ui.item.data.id;
            GetInfoRoom(rooms_id);

        },
        search: function(event, ui) {},
        minLength: 1,
        autoFocus: true
    })
})
