<script>

    @if (session()->has('bookingSuccessMessage'))
        Swal.fire({
            position: 'center',
            type: 'success',
            title: '<h4>Booking has been created successfully</h4>',
            showConfirmButton: false,
            timer: 1500
        })
    @endif


    function submitAvaialbelRoom(category_id){

        let arrivalDate     = $('.arrivalDate'+category_id).val();
        let depatureDate    = $('.depatureDate'+category_id).val();
        let route           = `{{ route('check-available-room') }}`;

        if (arrivalDate == '') {
            toastr.error('Please choose a check in date!');
            return;
        }
        else if (depatureDate == '') {
            toastr.error('Please choose a check out date!');
            return;
        }
        else {

            console.log(arrivalDate, depatureDate, route);

            $.ajax({
                url: route,
                type: 'GET',
                data: { category_id: category_id, check_in: arrivalDate, check_out: depatureDate },
                success: function(response) {
                    console.log(response);
                    if (response.totalAvailableRoom > 0) {
                        $('.room-id').val(response.room_id);
                        $('.room-category').val(category_id);
                        $('.check-in').val(arrivalDate);
                        $('.check-out').val(depatureDate);

                        $('.available-room-form').submit();
                    } else {
                        Swal.fire({
                            type: 'error',
                            title: '<h4>There is no room avalibale in this category! Please select another Category</h4>',
                            timer: 1500,
                            // showConfirmButton: false
                        })
                        return;
                    }
                }
            });
        }
    }



</script>
