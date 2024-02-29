<script>
    var price
    var room_id
    var room_number
    var allow_breakfast

    var is_added = true
    var is_available = true

    const vat = '{{ vatSetting()->hotel_vat }}'

    var nightCount = calculateDays();

    var check_out_date = $('input[name=check_out_date]').val();
    var guest_count = $(".choose-room").find(":selected").data("guest_count");
    var infant_count = $(".choose-room").find(":selected").data("infant_count");


    //----------- GET PREVOIUS ADV & DUE -----------//
    var previousDue = Number($('#previousDue').val(), 2);
    $('.previous-due').val(previousDue)

    var previousAdv = Number($('#previousAdvance').val(), 2);
    $('.previous-advance').val(previousAdv)





    // $(document).on('change','.empty-rooms', function(){
    //     // $(".available-rooms").empty();
    //     // $(".roomTbody").empty();
    // });




    /*
     *---------------------------------------------
     * CHECK ROOM STATUS
     *---------------------------------------------
     */
    $(document).on('click', '#checkRoomStatus', function() {

        $(".roomTbody").empty();

        let _selected = $('.room-select:checked')

        room_id = _selected.data('room-id')
        room_number = _selected.text().trim()
        guest_count = _selected.data('guest_count')
        infant_count = _selected.data('infant_count')
        allow_breakfast = _selected.data('allow_breakfast')
        nightCount = calculateDays();
        price = _selected.data('price');

        check_out_date = $('input[name=check_out_date]').val()


        if (room_id == '') {
            warning('toster', 'Please select room !')
        } else if (check_out_date == '') {
            warning('toster', 'Please select adjust date !')
        } else {

            checkRoom()

            if (is_added == true) {

                $('.available-rooms').hide();

                checkAvailableRoomByDate()

                // _selected.prop('disabled', true)

                chosenSelectInit()

            }
        }
    })





    /*
     *---------------------------------------------
     * CHECK AVAILABLE ROOM BY DATE
     *---------------------------------------------
     */
    function checkAvailableRoomByDate() {

        $.LoadingOverlay('show')
        console.log(room_id);
        $.ajax({
            type: 'get',
            url: '/hotel/check-available-room',
            async: true,
            data: {
                'check_out_date': check_out_date,
                'room_id': room_id,
            },

            beforeSend: function() {
                $("body").css("cursor", "progress")
            },

            success: function(data) {
                $.LoadingOverlay('hide')
                console.log(data);
                if (data[0].is_booked > 0) {
                    is_available = false
                    warning('toster', 'This room is Booked already !')
                } else if (data[0].is_reservation > 0) {

                    is_available = false
                    warning('toster', 'This room is Reserved for this day !')
                }

                // if (is_available) {
                //     $('.room-list').append(addRow())
                //     calculateAmount()

                // } else{
                //     getAvailableRoomByDate()
                // }

                getAvailableRoomByDate()
            },
            complete: function(data) {
                $("body").css("cursor", "default")

            },
            error: function(error) {
                console.log(error);
                $.LoadingOverlay('hide')
            }
        })
    }






    //---------------------------------------------//
    //         GET AVAILABLE ROOM BY DATE          //
    //---------------------------------------------//
    function getAvailableRoomByDate() {
        $.ajax({
            type: 'get',
            url: '/hotel/get-available-room',
            async: true,
            data: {
                'check_out_date': check_out_date,
                'room_id': room_id,
            },

            beforeSend: function() {
                $.LoadingOverlay("show")
                $("body").css("cursor", "progress")
            },

            success: function(data) {
                $('#available-room').html(data)
            },
            complete: function(data) {
                $("body").css("cursor", "default")
                $.LoadingOverlay("hide")
            },
            error: function(data) {
                $.LoadingOverlay("hide")
            }
        })
    }






    //---------------------------------------------//
    //                   ADD ROW                   //
    //---------------------------------------------//
    function addRow(index, roomCategory) {

        let $from_room_number = $('.room-select').val()
        let selectedRoomNumber = $('#selectedRoomNumber').val();

        roomCategory = $('.room-select:checked').data('room-category')
        let $room_id = $('.room-select:checked').data('room-id')

        let servicePercent = Number(`{{ vatSetting()->room_service_charge }}`)
        roomWiseServiceCharge = (price / 100) * servicePercent

        return `<tr class=tr-${index}>

                    <td class="text-center">
                        ${roomCategory}
                    </td>
                    <td class="text-center">
                        <input type="hidden" value="${$room_id}" name="previous_room_ids[]" class="previousRoomIdNo">
                        <input type="hidden" value="${index ?? $room_id}" name="room_ids[]" class=".room-id">

                        <span class="">Migrate From: </span>
                        <label class="label label-success selectedRoomNumber">${selectedRoomNumber}</label>
                        <span class="">Migrate To: </span>
                        <label class="label label-success">${room_number ?? $from_room_number }</label>
                    </td>

                    <td>
                        <input type="text" name="guest_count[]" value="${guest_count | 1}" class="form-control input-sm text-right">
                    </td>
                    <td>
                        <input type="text" name="room_price[]" value="${price}" class="form-control room-price amount text-right">
                        <input type="hidden" value="${roomWiseServiceCharge}" name="service_charge[]">
                    </td>
                    <td class="text-right">
                        <input type="text" name="infant_count[]" value="${infant_count | 1}" class="form-control input-sm text-right">
                    </td>
                    @if (request('type') != 'migrate')
                    <td>
                        <label>
                            <input name="is_half_day[]" onclick="enableHalfDay(this)" value="1" class="ace ace-switch ace-switch-6" type="checkbox">
                            <span class="lbl"></span>
                        </label>
                    </td>
                    @endif
                    <td>
                        <div class="input-group">
                            <input class="form-control text-right night_count" readonly="" name="night[]" type="number" value="${calculateDays()}">
                        </div>
                    </td>
                    <td class="text-right">
                        <input name="discounts[]" class="form-control only-number discount text-right" value="0" type="text">
                    </td>
                    <td>
                        <label>
                            <input name="allow_breakfast[]"
                                class="ace ace-switch ace-switch-6" value="1" type="checkbox"
                                checked>
                            <span class="lbl"></span>
                        </label>
                    </td>
                    <td class="text-right">
                        <input type="text" value="${price}" class="form-control text-right net-amount only-number" name="amounts[]" readonly>
                        <input type="hidden" value="${price}" class="form-control text-right net-amount-hidden">
                    </td>
                    <td class="text-center">
                        <a class="btn btn-xs btn-danger ibtnDel" href="javascript:void(0)" onclick="deleteRow(this)"><i class="fa fa-times"></i></a>
                    </td>
                </tr>`
    }





    //---------------------------------------------//
    //                  CHECK ROOM                 //
    //---------------------------------------------//
    function checkRoom() {
        $('.room_ids').each(function() {
            if ((this).val() == room_id) {
                is_added = false
                warning('toster', 'Room already added')
            } else {
                is_added = true
            }
        })
    }


    function calculateNight() {

        $('.item-details tr').each(function() {
            let main_price = 0

            let amount = $(this).find('.net-amount').val()
            let night_count = $(this).find('.night_count').val()
            main_price = night_count * amount
            $(this).find('.net-amount').val(main_price);

        });
    }




    $(document).on("keyup", ".amount", function() {

        $(this).closest('tr').find('.net-amount, .net-amount').val($(this).val())

        calculateAmount()

    });

    //---------------------------------------------//
    //        CALCULATE DISCOUNT - ON KEY UP       //
    //---------------------------------------------//
    $(document).on("keyup", ".discount", calculateAmount);







    //---------------------------------------------//
    //                  DELETE ROW                 //
    //---------------------------------------------//
    function deleteRow(object) {

        $(object).closest('tr').remove()
        toastr.warning('Room Removed');

        calculateAmount()
    }





    //---------------------------------------------//
    //                  PICK ROOM                  //
    //---------------------------------------------//
    function pickRoom(object, roomCategory) {

        // start
        let isRoomChecked = $('#selectedRoom').val();

        if (isRoomChecked == 0) {
            toastr.warning('Check a Room First');
            return;
        }



        let countRoomNo = 0;

        $(".previousRoomIdNo").each(function() {

            let itemRoomId = $(this).val();

            if (isRoomChecked == itemRoomId) {
                countRoomNo = 1;
            }
        });

        if (countRoomNo == 1) {
            toastr.warning('You already add one room');
            return;
        }
        // end


        let _selected = $(object)

        room_id = _selected.find('.room-id').val()
        room_number = _selected.find('.room-number').val()
        nightCount = calculateDays();


        if (_selected.hasClass('active')) {
            _selected.removeClass('active')
            deleteRow($('.tr-' + room_id))
            return;
        }


        price = _selected.find('.room-price').val()
        _selected.addClass('active');

        toastr.success('Room Added');

        $('.room-list').append(addRow(room_id, roomCategory))

        calculateAmount()
    }





    //---------------------------------------------//
    //               ENABLE HALF DAY               //
    //---------------------------------------------//
    function enableHalfDay(object) {

        // let amount = $(object).closest('tr').find('.amounts').val()
        let amount = $(object).closest('tr').find('.net-amount').val()

        if ($(object).is(':checked')) {

            amount = $(object).closest('tr').find('.net-amount').val(Number(amount) / 2)

        } else {

            amount = $(object).closest('tr').find('.net-amount').val(Number(amount) * 2)
        }

        calculateAmount()
    }





    $(document).on('click', '#usdCurrency, #bdtCurrency', function() {
        if ($(this).find('input[name=currency_type]').val() != `{{ setting('root_currency') }}`) {
            $('.show-currency-rate').show();
        } else {
            $('.show-currency-rate').hide();
        }

        calculateAmount()
    });

    function currencyConversion() {
        let currenty_rate_text = ''
        let _this = $(document).find('input[name=currency_type]:checked');
        let bdt_rate = `{{ getCurrentCurrencyRate('bdt') }}`
        let usd_rate = `{{ getCurrentCurrencyRate('usd') }}`
        let myr_rate = `{{ getCurrentCurrencyRate('myr') }}`

        if (_this.val() == 141) {
            dueAmountForCurrency = Number(usd_rate) * (Number($('.grandtotal').val()))

            currenty_rate_text = 'USD Amount ' + dueAmountForCurrency.toFixed(2);
        } else if (_this.val() == 96) {
            dueAmountForCurrency = Number(myr_rate) * Number($('.grandtotal').val())
            currenty_rate_text = 'RM Amount ' + dueAmountForCurrency.toFixed(2);
        } else if (_this.val() == 12) {

            dueAmountForCurrency = Number(bdt_rate) * (Number($('.grandtotal').val()))
            currenty_rate_text = 'BDT Amount ' + dueAmountForCurrency.toFixed(2);

        }
        $('.show-currency-rate').text(currenty_rate_text);

        return dueAmountForCurrency;
    }


    //---------------------------------------------//
    //               CALCULATE AMOUNT              //
    //---------------------------------------------//
    function calculateAmount() {

        var subtotal = 0;
        var vat = Number($('#vat').val());
        let service_percent = Number(`{{ vatSetting()->room_service_charge }}`)
        let roomWiseServiceCharge = 0

        $('.item-details tr').each(function() {
            let roomPrice = 0

            let amount = Number($(this).find('.net-amount').val())
            let night_count = Number($(this).find('.night_count').val())
            let discount = Number($(this).find('.discount').val())

            roomPrice = (night_count * amount) - discount

            // $(this).closest('tr').find('.net-amount').val(roomPrice.toFixed(2));

            subtotal += roomPrice;
            roomWiseServiceCharge = (roomPrice / 100) * service_percent

            $(this).closest('tr').find('.room-wise-service-charge').val(roomWiseServiceCharge)
        });

        var service_amount = (subtotal / 100) * service_percent;

        var calculate_vat = ((subtotal + service_amount) / 100) * vat;

        var extra_charge = Number($('.extra-charge').val() | 0);

        let grandtotal = subtotal + service_amount + calculate_vat + extra_charge


        let currentCurrency = `{{ setting('root_currency') }}`;
        let advAmount = Number($('.adv-amount').val());



        $(".grandtotal").val(grandtotal.toFixed(2));

        let due_amount = currencyConversion() - advAmount;

        $("#line_total, .subtotal-amount").val(subtotal.toFixed(2));
        $(".vat-amount").val(calculate_vat.toFixed(2));
        $(".service_amount").val(service_amount.toFixed(2));
        console.log(due_amount);
        $(".due-amount").val(due_amount.toFixed(2));
    }



    $(document).on('keyup', '.migrate_date', calculateDays);


    //---------------------------------------------//
    //                CALCULATE DAYS               //
    //---------------------------------------------//
    function calculateDays() {

        var check_in_date = $('.migrate_date').val()
        var check_out_date = $('input[name=check_out_date]').val();

        var start_date = new Date(check_in_date);
        var end_date = new Date(check_out_date);

        diff = new Date(end_date - start_date),
            days = diff / 1000 / 60 / 60 / 24;

        return days;

    }





    //---------------------------------------------//
    //         ADVANCE AMOUNT - ON KEY UP          //
    //---------------------------------------------//
    $(document).on('keyup', '.adv-amount', function() {
        calculateAmount()
    })





    //---------------------------------------------//
    //            SUBMIT FORM - ON CLICK           //
    //---------------------------------------------//
    $(document).on('click', '.room-select', function() {

        if ($(this).is(':checked') == true) {

            // $('.room-select').prop('checked', false); // Unchecks all
            // $('.room-select').prop('disabled', true); // disabled all

            $(this).prop('checked', true); // Checks only this
            $(this).prop('disabled', false); // enable only this

            let selectedRoom = $(this).data('room-id');
            let selectedRoomNumber = $(this).val();
            console.log(selectedRoomNumber);

            $('#selectedRoom').val(selectedRoom);
            $('#selectedRoomNumber').val(selectedRoomNumber);
        } else {
            $('.room-select').prop('disabled', false); // enable all of this
            $('.room-select').prop('checked', false); // Unchecks all

            $('#selectedRoom').val('');
            $('#selectedRoomNumber').val('');
        }

    });






    //---------------------------------------------//
    //            SUBMIT FORM - ON CLICK           //
    //---------------------------------------------//
    $(document).on('click', '.submit-form-btn', function() {

        if ($('.room-list tbody tr').length == 0) {
            warning('toster', 'Please add room');
            return;
        } else if ($('input[name=advanced_amount]').val() == '') {
            warning('toster', 'Enter advance amount');
            return;
        } else if ($('.room-select').is(':checked') == false) {
            warning('toster', 'Please checked any previous room');
            return;
        } else {
            $('#store-form').submit()
        }
    })







    //---------------------------------------------//
    //        CHECK FOR CHECKOUT DATE EXPIRE       //
    //---------------------------------------------//
    $(document).ready(function() {
        let tr_checkout_date = $('.tr-checkout-date').text();
        let current_date = $('#currentDate').val();

        if (current_date > tr_checkout_date) {
            toastr.error('Your Checkout Date is expired');
        }

    });
</script>
