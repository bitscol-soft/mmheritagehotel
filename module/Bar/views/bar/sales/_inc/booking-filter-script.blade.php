<!-- Get Info By Guest -->
<script>
    $(document).on('click', '.ui-menu-item', function() {
        let guest_id = $('#hotel_guest_id').val();
        getGuestInfo(guest_id)
    });

    function getGuestInfo(guest_id) {

        $.ajax({
            type: 'get',
            url: '/hotelservice/guest-d-by-name/' + guest_id,
            async: true,

            beforeSend: function() {
                $("body").css("cursor", "progress");
            },
            success: function(data) {
                // console.log(data);

                if (data['status'] != 0) {
                    $('#room_number').val(data.room_number);
                    $('#booking_number').val(data.booking_number);
                    $('#guest_name').val(data.guest_name);
                    $('#hotel_guest_id').val(data.guest_id);
                }

            },
            complete: function(data) {
                $("body").css("cursor", "default");
            }
        });
    }
</script>



<!-- Get Room Number -->
<script>
    $(document).on('focus', '#room_number', function() {
        $(this).autocomplete({
            source: function(request, response) {
                $.getJSON('/hotelservice/get-room-list', {
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
</script>



<!-- Get Booking Number -->
<script>
    $(document).on('focus', '#booking_number', function() {
        $(this).autocomplete({
            source: function(request, response) {
                $.getJSON('/hotelservice/get-booking-number', {
                        name: request.term
                    },
                    function(data) {
                        response($.map(data, function(items) {
                            return {
                                value: items.available_quantity,
                                label: `${items.booking_number}`,
                                data: items,
                            }
                        }))
                    }
                )
            },
            select: function(event, ui) {
                let booking_id = ui.item.data.id;
                getInfoByBooking(booking_id);

            },
            search: function(event, ui) {},
            minLength: 1,
            autoFocus: true
        })
    })
</script>



<!-- Get Info By Booking -->
<script>
    function getInfoByBooking(booking_id) {

        $.ajax({
            type: 'get',
            url: '/hotelservice/guest-by-booking/' + booking_id,
            async: true,

            beforeSend: function() {
                $("body").css("cursor", "progress");
            },
            success: function(data) {
                $('#guest_name').val(data['guest_name']);
                $('#room_number').val(data['room_number']);
                $('#hotel_booking_id').val(booking_id);
                $('#hotel_guest_id').val(data['guest_id']);
            },
            complete: function(data) {
                $("body").css("cursor", "default");
            }
        });
    }
</script>



<!-- Get Info By Room -->
<script>
    function GetInfoRoom(rooms_id) {

        $.ajax({
            type: 'get',
            url: '/hotelservice/guest-by-rooms/' + rooms_id,
            async: true,

            beforeSend: function() {
                $("body").css("cursor", "progress");
            },
            success: function(data) {

                $('#guest_name').val(data['guest_name']);
                $('#booking_number').val(data['booking_number']);
                $('#hotel_booking_id').val(data['booking_id']);
                $('#hotel_guest_id').val(data['guest_id']);

            },
            complete: function(data) {
                $("body").css("cursor", "default");
            }
        });

    }
</script>



<script>
    function requestUrl(urlParts) {
        urlParts = $.extend({
            basePath: '{{ url('/') }}/',
            path: '',
            param: ''
        }, urlParts);
        return urlParts.basePath + urlParts.path + urlParts.param;
    }


    function patientFilter() {
        patientWithIndoor('indoor');

    }
</script>
