<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>

    //---------------------------------------------------------//
    //              EXTEND CHECKOUT DATE METHOD                //
    //---------------------------------------------------------//
    function showExtendDateModal(booking_id){
        $('#extendDateModal'+booking_id).toggleClass("md-show");

        $('.check-out-date'+booking_id).val($('.previousCheckoutDate'+booking_id).val());
    }





    //---------------------------------------------------------//
    //              EXTEND CHECKOUT DATE METHOD                //
    //---------------------------------------------------------//
    function closeExtendDateModal(){
        $(".close-modal").removeClass("md-show")
    }






    //---------------------------------------------------------//
    //              EXTEND CHECKOUT DATE METHOD                //
    //---------------------------------------------------------//
    function changeCheckoutDate(id){
        if ($('.check-out-date'+id).val() != '') {
            let check_in        = $('.check-in-date'+id).val();
            let check_out       = $('.check-out-date'+id).val();
            let actualCheckOut  = $('.previousCheckoutDate'+id).val();
            let booking_id      = $('.booking-id'+id).val();

            var promises = [];

            $(".room_number"+id).each(function() {

                let room_id      = $(this).find('option:selected').val();
                let room_name    = $(this).find('option:selected').text();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var request = $.ajax({
                    type: 'GET',
                    url: '{{ route("check-room-availability") }}',
                    data: {booking_id: booking_id, room_id: room_id, check_in_date: check_in, check_out_date: check_out},
                    success: function (response) {

                        if (response != null && response != '') {

                            if (response.is_booked > 0 || response.is_reservation > 0) {

                                $('.isRoomAvailable'+id).val(1);

                                $('.check-out-date'+id).val(actualCheckOut);
                                toastr.error('Room no '+ room_name +' not available!');

                            }

                        }
                        else{
                            $('.check-out-date'+id).val(actualCheckOut);
                            toastr.error('Not working, Please try again!');
                        }


                    }, error: function () {
                        $('.check-out-date'+id).val(actualCheckOut);
                        toastr.error('Something Went Wrong!');
                    }
                });
                promises.push( request);
            });


            $.when.apply(null, promises).done(function(){
                if (check_out <= actualCheckOut) {
                    toastr.error('New checkout date can not be same or less than previous checkout date!');
                    return;
                }
                if ($('.isRoomAvailable'+id).val() == 0) {

                    toastr.success('Room are available!');

                    calculateDays(id, check_in, check_out);

                }
            })


        }
    }

    function calculateDays(id, check_in, check_out) {

        var start_date = new Date(check_in);
        var end_date = new Date(check_out);

        diff = new Date(end_date - start_date),
            days = diff / 1000 / 60 / 60 / 24;

        $('.night_count'+id).val(days);

        calculateAmount(id);

    }

    function calculateAmount(id) {

        var subtotal                = 0;
        var vat                     = Number($('#vat'+id).val());
        let service_percent         = Number(`{{ vatSetting()->room_service_charge }}`)
        let roomWiseServiceCharge   = 0

        // $('.room-details'+id).each(function() {
        $('.room_count'+id).each(function(i, value) {
            let roomPrice = 0

            let amount = Number($('.net-amount-hidden'+id+''+[i]).val())
            let night_count = Number($('.night_count'+id).val())
            let discount = Number($('.discount'+id+''+[i]).data('room-discount'))
            if (discount == 0) {
                discount = Number($('.discount'+id+''+[i]).val())
            }

            roomPrice = (night_count * amount)// - discount

            $('.net-amount'+id+''+[i]).val(roomPrice.toFixed(2));

            subtotal                += roomPrice;
            roomWiseServiceCharge   = (roomPrice / 100) * service_percent

            $('.room-wise-service-charge'+id+''+[i]).val(roomWiseServiceCharge)
            $('.discount'+id+''+[i]).val(discount * night_count)
        });

        var service_amount  = (subtotal / 100) * service_percent;

        var calculate_vat   = ((subtotal + service_amount) / 100) * vat;

        var extra_charge    = Number($('.extra-charge'+id).val() | 0);

        let grandtotal      = subtotal + service_amount + calculate_vat + extra_charge


        let currentCurrency   = `{{ setting('root_currency') }}`;
        let advAmount         = Number($('.adv-amount'+id).val());


        $(".grandtotal"+id).val(grandtotal.toFixed(2));


        let _this = `{{ setting('root_currency') }}`;
        let bdt_rate = `{{ getCurrentCurrencyRate('bdt') }}`
        let usd_rate = `{{ getCurrentCurrencyRate('usd') }}`

        if (_this == 141) {
            dueAmountForCurrency            = Number(usd_rate) * Number($('.grandtotal'+id).val())
        }else if (_this == 12){
            dueAmountForCurrency            = Number(bdt_rate) * Number($('.grandtotal'+id).val())
        }

        let due_amount      = dueAmountForCurrency - advAmount;

        $("#line_total"+id).val(subtotal.toFixed(2));
        $(".subtotal-amount"+id).val(subtotal.toFixed(2));
        $(".vat-amount"+id).val(calculate_vat.toFixed(2));
        $(".service_amount"+id).val(service_amount.toFixed(2));
        $(".due-amount"+id).val(due_amount.toFixed(2));

    }


    function submitExtendCheckOutDateForm(id){


        $('#extendCheckOutDateForm'+id).submit()


    }

</script>

