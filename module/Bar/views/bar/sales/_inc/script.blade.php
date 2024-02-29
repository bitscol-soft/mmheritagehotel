<script>
    const saveGuestBtn  = $('.save-guest')
    const guestName     = $('[name=guest_name]')
    const guestMobile   = $('[name=guest_mobile]')
    const guestSaveRoute = '{{ route('bar.save-guest-data') }}'


    $(document).on('click', '.save-guest', saveGuest);

    function saveGuest() {

        if (guestName.val() == '') {
            warning('toster','Please enter name');
            return;
        }

        $.post(guestSaveRoute, {
            _token: '{{ csrf_token() }}',
            guest_name: guestName.val(),
            guest_mobile: guestMobile.val(),
            is_bar:1,
        },function(response){
            if (response.status) {
                $('#add-guest-modal').modal('hide')
                $('#guest_name').val(response.data.name)
                $('#hotel_guest_id').val(response.data.id)
            }
        })
    }
</script>
