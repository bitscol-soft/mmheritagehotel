<script>
    const saveGuestBtn  = $('.save-guest')
    const guestName     = $('[name=guest_name]')
    const guestMobile   = $('[name=guest_mobile]')
    const guestSaveRoute = '{{ route('rst.save-guest-data') }}'


    $(document).on('click', '.save-guest', saveGuest);

    function saveGuest() {

        if (guestName.val() == '') {
            warning('toster', 'Please enter name');
            return;
        }

        if (guestMobile.val() == '') {
            warning('toster', 'Please enter mobile');
            return;
        }

        $.post(guestSaveRoute, {
            _token: '{{ csrf_token() }}',
            guest_name  : guestName.val(),
            guest_mobile: guestMobile.val(),
            is_stuff    : $('[name="is_stuff"]:checked').val(),
        }, function(response) {
            console.log(response);
            if (response.status) {
                $('#add-guest-modal').modal('hide')
                $('#guest_name').val(response.data.name)
                $('#hotel_guest_id').val(response.data.id)
                $('#is_stuff').val(response.data.is_stuff)


                $('#guest_name').append(`<option value="${ response?.data?.id}" selected>${response?.data?.name}</option>`)

            }

        })
    }
</script>
