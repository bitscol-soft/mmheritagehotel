<script>
    let dueAmountForCurrency = 0;
    let day_count = daysdifference($('input[name=check_in_date]').val(), $('input[name=check_out_date]').val());

    $(document).on('keyup', '.net-amount', calculateDayLongAmount)
    $(document).on('keyup', '.input-guest', countNumberOfGuest)
    $(document).on('keyup', '.input-infant', countNumberOfGuest)


    addMemberDetialItem()


    //------------------------------------------//
    //         ADD MEMBER DETAILS ITEM          //
    //------------------------------------------//
    function addMemberDetialItem() {
        $('.member-detail-body').append(`
        <div class="row member-detail-item">
            <!-- Guest Name -->
            <div class="col-sm-12 mb-1">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Name</label>
                    <div class="col-xs-12 col-sm-8">
                        <input type="text" class="form-control input-sm" name="member_names[]" placeholder="Member Name">
                    </div>
                </div>
            </div>






            <!-- Phone No -->
            <div class="col-sm-12 mb-1">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Phone No<sub class="text-danger">*</sub> </label>
                    <div class="col-xs-12 col-sm-8 @error('phone_no') has-error @enderror">
                        <input type="text" class="form-control input-sm" name="member_phone_nos[]" placeholder="Enter Phone no">
                    </div>
                </div>
            </div>






            <!-- Email -->
            <div class="col-sm-12 mb-1">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Email </label>
                    <div class="col-xs-12 col-sm-8">
                        <input type="text" class="form-control input-sm" name="member_emails[]" placeholder="Enter Email">
                    </div>
                </div>
            </div>






            <!-- Gender -->
            <div class="col-sm-12 mb-1">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Gender</label>

                    <div class="col-xs-12 col-sm-8">
                        <select name="member_genders[]" class="form-control chosen-select-100-percent" data-placeholder="--Select Gender--">
                            <option></option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                        </select>

                    </div>
                </div>
            </div>


            <!-- Age -->
            <div class="col-sm-12 mb-1">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Age</label>
                    <div class="col-xs-12 col-sm-8">
                        <input type="text" class="form-control input-sm" name="member_age[]" placeholder="Enter Age">
                    </div>
                </div>
            </div>






            <!-- NID/PASSPORT -->
            <div class="col-sm-12 mb-1">
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        NID/Passport/Birth Registration
                    </label>

                    <div class="col-xs-12 col-sm-8">
                        <input type="text" class="form-control input-sm" name="registration_nos[]" placeholder="Enter NID or Passport Number">
                    </div>
                </div>
            </div>


            <div class="col-sm-12 mb-1">
                <hr >
            </div>
        </div>
        `)

        chosenSelectInit()
    }




    //------------------------------------------//
    //          CALCULATE DAYS METHOD           //
    //------------------------------------------//
    function calculateDays() {
        var check_in_date = $('.check_in').val()
        var check_out_date = $('.check_out').val();

        var start_date = new Date(check_in_date);
        var end_date = new Date(check_out_date);

        diff = new Date(end_date - start_date),
            days = diff / 1000 / 60 / 60 / 24;

        $('.night_count').val(days);
        calculateNight();
        calculateAmount();

    }




    //------------------------------------------//
    //       ON-CHANGE - CHECK OUT BUTTON       //
    //------------------------------------------//
    $(document).on('change', '.check_out', function() {
        calculateDays();

    });





    //------------------------------------------//
    //        ON-KEYUP - ADVANCE AMOUNT         //
    //------------------------------------------//
    $(document).on('keyup', '.adv-amount', calculateAmount)





    //------------------------------------------//
    //          ON-KEYUP - VAT AMOUNT           //
    //------------------------------------------//
    $(document).on('keyup', '.vat-amount', function() {

        let total = 0;
        $('.net-amount').each(function() {
            total += Number($(this).val());
        })


        let vat_amount = $(this).val();

        total = Number(vat_amount) + Number(total);

        $('input[name=sub_total]').val(total);

    })





    //------------------------------------------//
    //         ON-KEYUP - SERVICE AMOUNT        //
    //------------------------------------------//
    $(document).on('keyup', '.service_amount, .extra-charge', calculateAmount)





    //------------------------------------------//
    //            ON-CLICK - ADD ROW            //
    //------------------------------------------//
    $(document).ready(function() {
        var i = 0;
        $("#addrow").on("click", function() {
            $("table.order-list").append(rowItem)
            calculateDays();
            chosenSelectInit();
            i++;
        });

        $("table.order-list").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();
            i -= 1
            calculateAmount()
        });
    });




    //------------------------------------------//
    //          ON-CHAGE - DISPLAY SHOW         //
    //------------------------------------------//
    $('.display_show').change(function() {
        $('.show-div').show();
    })




    //------------------------------------------//
    //          ON-INPUT - NIGHT COUNT          //
    //------------------------------------------//
    $(document).on("input", ".night_count", function() {
        calculateNight()
        calculateAmount()
    });




    //------------------------------------------//
    //              ON-KEYUP - AMOUNT           //
    //------------------------------------------//
    $(document).on("keyup", ".amount", function() {

        let night_count = $(this).closest('tr').find('.night_count').val();
        let value = night_count * Number($(this).val());

        $(this).closest('tr').find('.net-amount, .net-amount-hidden').removeAttr('value');
        $(this).closest('tr').find('.net-amount, .net-amount-hidden').attr('value', value);

        // calculateNight()
        $('.item-details tr').each(function() {

            let main_price = $(this).find('.net-amount-hidden').val()

            $(this).find('.net-amount').val(main_price);

        });

        calculateAmount()
        calculateDiscount()
    });





    //------------------------------------------//
    //             ON-INPUT - DISCOUNT          //
    //------------------------------------------//
    $(document).on("input", ".discount", function() {
        calculateDiscount()
        calculateAmount()
    });




    //---------------------------------------------//
    //            CALCULATE NIGHT METOD            //
    //---------------------------------------------//
    function calculateNight() {

        $('.item-details tr').each(function() {

            let main_price = 0
            let amount = $(this).find('.net-amount-hidden').val()
            let night_count = $(this).find('.night_count').val()

            main_price = night_count * amount

            $(this).find('.net-amount').val(main_price);

        });
    }



    //---------------------------------------------//
    //          CALCULATE DISCOUNT METOD           //
    //---------------------------------------------//
    function calculateDiscount() {

        $('.item-details tr').each(function() {

            let main_price = 0
            let amount = $(this).find('.net-amount-hidden').val()
            let discount = $(this).find('.discount').val()

            main_price = amount - discount

            $(this).closest('tr').find('.net-amount').val(main_price);

        });
    }




    //---------------------------------------------//
    //           CALCULATE AMOUNT METOD            //
    //---------------------------------------------//
    function calculateAmount() {

        var subtotal = 0;
        var vat = Number($('#vat').val());
        let service_percent = Number(`{{ vatSetting()->room_service_charge }}`)
        let roomWiseServiceCharge = 0

        $('.item-details tr').each(function() {
            let roomPrice = 0

            let amount = Number($(this).find('.net-amount-hidden').val())
            let night_count = Number($(this).find('.night_count').val())
            let discount = Number($(this).find('.discount').val())
            let discount_type = Number($(this).find('.discount_type').val())
            // console.log(complementary);
            // console.log(discount_type);

            roomPrice = (night_count * amount) - discount

            $(this).closest('tr').find('.net-amount').val(roomPrice.toFixed(2));

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
        $(".due-amount").val(due_amount.toFixed(2));
    }




    //---------------------------------------------//
    //       CALCULATE DAY LONG AMOUNT METOD       //
    //---------------------------------------------//
    function calculateDayLongAmount() {

        // Sum all Sub-total
        if (day_count == 0) {

            countNumberOfGuest()

            calculateAmount()
        }
    }




    //---------------------------------------------//
    //          COUNT NUMBER OF GUEST METOD        //
    //---------------------------------------------//
    function countNumberOfGuest() {

        if (day_count == 0) {

            let guest = $(this).closest('tr').find('.input-guest').val();
            let infant = $(this).closest('tr').find('.input-infant').val();
            let amount = $(this).closest('tr').find('.amount').val();

            let totalAmount = (Number(guest) * Number(amount)) + (Number(infant) * (Number(amount) / 2));

            $(this).closest('tr').find('.net-amount').val(totalAmount)
            $(this).closest('tr').find('.net-amount-hidden').val(totalAmount)
            calculateAmount()
        }
    }




    //---------------------------------------------//
    //            CALCULATE AMOUNT METOD           //
    //---------------------------------------------//
    function daysdifference(firstDate, secondDate) {
        var startDay = new Date(firstDate);
        var endDay = new Date(secondDate);

        var millisBetween = startDay.getTime() - endDay.getTime();

        var days = millisBetween / (1000 * 3600 * 24);

        return Math.round(Math.abs(days));
    }
</script>








<!------------- Booking Next Step Custom JS ------------->
<script>
    //------------------------------------------//
    //         ON-CLICK - BOOKING DELETE        //
    //------------------------------------------//
    $(document).ready(function() {
        $(".booking_delete").on('click', function(e) {
            e.preventDefault();

            if (!confirm("Do you want to delete")) {
                return false;
            }

            var the = $(this).closest('.booking_list');
            var room_id = $(this).closest(".booking_list").find('#room_id').val();
            var data = {
                // '_token': $('input[name=_token]').val(),
                "product_id": room_id,
            };

            $.ajax({

                url: '/hotel/remove_booking_next',
                method: "get",
                data: data,
                success: function(data) {
                    the.remove();
                    toastr.success(data.message);
                    calculateAmount();
                }

            });


        })
    });




    //------------------------------------------//
    //       ON-CLICK - SUBMIT STORE FORM       //
    //------------------------------------------//
    $(document).on('click', '#save-btn', function() {
        let name = $('input[name=guest_name]').val();
        let phone = $('input[name=phone_no]').val();

        if (name == '' || phone == '') {
            toastr.error('Enter Customer Name!');
            toastr.error('Enter Phone Number!');
        } else {
            $.ajax({
                url: $('#guestAddForm').attr('action'),
                method: 'post',
                data: $('#guestAddForm').serialize(),
                success: function(response) {
                    let data = response.data;
                    $('#customer_id').append(
                        `<option value="${data.id}" selected>${data.name} -> ${data.phone_no}</option>`
                    ).trigger('chosen:updated')
                    $('#add_guest1').modal('hide');
                }
            })
        }
    })





    //------------------------------------------//
    //         ON-CHANGE - CHECK IN STATUS      //
    //------------------------------------------//
    $(document).on('change', '[name=status]', function() {
        $('.check-in-note').toggle()
    })




    //------------------------------------------//
    //    ON-CHANGE - ALLOW GUEST WISE PRICE    //
    //------------------------------------------//
    $(document).on('change', '.input-guest', function() {

        let _this = $(this).closest('tr')
        let guest_wise_pricing = _this.find('.allow-gues-wise-price').val()

        if (guest_wise_pricing == 1) {

            let price = Number($(this).find('option:selected').data('price'))
            $(this).closest('tr').find('.amount').val(price)
            $(this).closest('tr').find('.net-amount, .net-amount-hidden').val(price)

            calculateNight()
            calculateAmount()
            calculateDiscount()
        }
    });




    //------------------------------------------//
    //         ON-CLICK - ADVANCE AMOUNT        //
    //------------------------------------------//
    $(document).on('click', '#usdCurrency, #bdtCurrency', function() {
        if ($(this).find('input[name=currency_type]').val() != `{{ setting('root_currency') }}`) {
            $('.show-currency-rate').show();
        } else {
            $('.show-currency-rate').hide();
        }

        calculateAmount()
    });




    //------------------------------------------//
    //         CURRENCY CONVERSION METOD        //
    //------------------------------------------//
    function currencyConversion() {

        let currenty_rate_text = ''
        let _this = $(document).find('input[name=currency_type]:checked');
        let bdt_rate = `{{ getCurrentCurrencyRate('bdt') }}`
        let usd_rate = `{{ getCurrentCurrencyRate('usd') }}`
        let sr_rate = `{{ getCurrentCurrencyRate('sr') }}`

        if (_this.val() == 141) {
            dueAmountForCurrency = Number(usd_rate) * Number($('.grandtotal').val())
            currenty_rate_text = 'USD Amount ' + dueAmountForCurrency.toFixed(2);
        } else if (_this.val() == 12) {

            dueAmountForCurrency = Number(bdt_rate) * Number($('.grandtotal').val())
            currenty_rate_text = 'BDT Amount ' + dueAmountForCurrency.toFixed(2);

        } else if (_this.val() == 116) {

            dueAmountForCurrency = Number(sr_rate) * Number($('.grandtotal').val())
            currenty_rate_text = 'SR Amount ' + dueAmountForCurrency.toFixed(2);

        }
        $('.show-currency-rate').text(currenty_rate_text);

        return dueAmountForCurrency;
    }








    //---------------------------------------------------------------//
    //                      SUBMIT BOOKING FORM                      //
    //---------------------------------------------------------------//
    function submitBookingForm() {

        let haveAdvanceAmount = $('.adv-amount').val();
        let isPaymentMethodSelected = $('.payment-type').val();

        if ($('.booking-date-picker').val() == '') {
            toastr.error('Please select a booking date');
            return;
        }

        if ($('#customer_id').val() == '') {
            toastr.error('Please select a guest');
            return;
        }

        if ($('.check-in-date').val() == '') {
            toastr.error('Please choose check in date');
            return;
        }

        if ($('.check-out-date-picker').val() == '') {
            toastr.error('Please choose check out date');
            return;
        }

        if ($('.category').val() == '') {
            toastr.error('Please select a room category');
            return;
        }

        if ($('.room_number').val() == '') {
            toastr.error('Please select a room');
            return;
        }

        if (haveAdvanceAmount > 0) {
            if (isPaymentMethodSelected == null || isPaymentMethodSelected == '') {
                toastr.error('Please select an payment method');
                return;
            }
        }

        if (isPaymentMethodSelected != '') {
            if (haveAdvanceAmount <= 0) {
                toastr.error('Please pay first.');
                return;
            }
        }

        $('#submitBookingUpdateForm').submit();

    }
</script>


<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    //------------------------------------------//
    //              SHOW COMPANY METOD          //
    //------------------------------------------//
    function showCompany(obj) {

        $("#company_id option:selected").prop("selected", false)
        $('#referenceName').prop('readonly', false).val('');

        let customer_id = $(obj).val();

        const route = "{{ route('get-customer-info') }}";

        axios.get(route, {
                params: {
                    customer_id: customer_id,
                }
            })
            .then(function(response) {

                let data = response.data.data.customer;

                if (data.reference != null && data.reference != '') {
                    $('#referenceName').prop('readonly', true).val(data.reference);
                }

                if (data.company_id != null && data.company_id != '') {
                    $('#company_id').val(data.company_id);
                }
            })
            .catch(function(error) {
                toastr.error('Something went wrong :(');
                return;
            });

    }



    //------------------------------------------//
    //              SHOW CURRENCY ICON          //
    //------------------------------------------//
    $(document).ready(function() {
        $(".currency-sign").each(function() {
            $(this).text(`{!! currencySign() !!}`);
        });
    });
</script>

<script>
    function editGuestInfo(obj) {

        let customer_id = $(obj).val();

        if (customer_id != '') {
            $('.edit_guest_info').show()
            $('.add_guest_info').hide()

        } else {
            $('.edit_guest_info').hide()
            $('.add_guest_info').show()
        }
        let route = `{{ route('get-guest-info') }}`;

        axios.get(route, {
                params: {
                    customer_id: customer_id,
                }
            })
            .then(function(response) {
                let data = response.data.data.customer;
                // console.log(data);
                let editForm = $('#guestEditForm');
                editForm.find('input[name=guest_id]').val(data.id);
                editForm.find('input[name=guest_name]').val(data.name);
                editForm.find('input[name=phone_no]').val(data.phone_no);
                editForm.find('input[name=email]').val(data.email);
                editForm.find('input[name=nid_no]').val(data.nid_no);
                editForm.find('input[name=age]').val(data.age);
                editForm.find('input[name=profession]').val(data.profession);
                editForm.find('input[name=father_name]').val(data.father_name);
                editForm.find('input[name=passport_expiry_date]').val(data.passport_expiry_date);
                editForm.find('input[name=spouse_name]').val(data.spouse_name);
                editForm.find('input[name=address]').val(data.address);

                editForm.find('.edit_guest_gender').html(`
                                    <select name="gender" class="form-control chosen-select-100-percent" data-placeholder="--Select Gender--">
                                        <option></option>
                                        <option value="1" ${data.gender == 1 ? 'selected' : ''}>Male</option>
                                        <option value="2" ${data.gender == 2 ? 'selected' : ''}>Female</option>
                                        <option value="0" ${data.gender == 0 ? 'selected' : ''}>Others</option>
                                    </select>`);


                let image = data.image?.replace("./", "/");
                let nid_front = data.nid_front?.replace("./", "/");
                let nid_back = data.nid_back?.replace("./", "/");
                let spouse_nid_front = data.spouse_nid_front?.replace("./", "/");
                let spouse_nid_back = data.spouse_nid_back?.replace("./", "/");
                editForm.find('.edit_guest_image').html(`
            <img src="${image != 'http://127.0.0.1:8000/' ? image : ''}" class="" width="100px" height="90px" alt="">
            `);
                editForm.find('.guest_nid_front_view').html(`
            <img src="${nid_front != 'http://127.0.0.1:8000/' ? nid_front : ''}" class="" width="100px" height="90px" alt="">
            `)
                editForm.find('.guest_nid_back_view').html(`
            <img src="${nid_back != 'http://127.0.0.1:8000/' ? nid_back : ''}" class="" width="100px" height="90px" alt="">
            `)
                editForm.find('.guest_spouse_nid_front_view').html(`
            <img src="${spouse_nid_front != 'http://127.0.0.1:8000/' ? spouse_nid_front : ''}" class="" width="100px" height="90px" alt="">
            `);
                editForm.find('.guest_spouse_nid_back_view').html(`
            <img src="${spouse_nid_back != 'http://127.0.0.1:8000/' ? spouse_nid_back : ''}" class="" width="100px" height="90px" alt="">
            `);
            })
            .catch(function(error) {
                toastr.error('Something went wrong !');
                return;
            });
    }


    // SUBMIT GUEST EDIT FORM
    $('#edit_guest_submit').on('click', function() {
        let guestId = $('.edit_guest_id').val();

        let route = `{{ route('guests.update', ':id') }}`;
        route = route.replace(':id', guestId);

        let name = $('#guestEditForm').find('input[name=guest_name]').val();
        let phone = $('#guestEditForm').find('input[name=phone_no]').val();

        if (name == '' || phone == '') {
            toastr.error('Enter Customer Name!');
            toastr.error('Enter Phone Number!');
        } else {
            $.ajax({
                url: route,
                method: 'PUT',
                data: $('#guestEditForm').serialize(),
                success: function(response) {
                    // let data = response
                    console.log(response);
                    toastr.success('Guest Update Success');
                    $('#edit_guest_info').modal('hide');
                },
                error: function(error) {
                    console.log(error);
                }
            })
        }

    })
</script>


<script src="{{ asset('assets/js/webcam.min.js') }}"></script>

<script language="JavaScript">
    function editGuestWebCam() {
        Webcam.set({
            width: 250,
            height: 135,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#edit_guesr_my_camera');
        $('.edit_guest_take_snapshot').show();
    }

    function reset() {
        Webcam.reset();
    }

    function edit_guest_take_snapshot() {
        Webcam.snap(function(data_uri) {
            $('.edit_guest_image-tag').val(data_uri);
            $('.edit_guest_is_web_cam_or_not').val(1);
            $('.image').prop('disabled', true);

            document.getElementById('edit_guesr_my_camera').innerHTML = '<img src="' + data_uri + '"/>';
            $('.edit_guest_delete-snap').show();
            // Webcam.reset();

        });
    }

    $('.edit_guest_delete-snap').on('click', function() {
        $('#edit_guest_results').empty();
        $('#edit_guesr_my_camera').empty();
        $('.edit_guest_delete-snap').hide();
        $('.edit_guest_image-tag').val('');
        $('.edit_guest_take_snapshot').hide();
        $('.edit_guest_profile_image').prop('disabled', false);
        $('.edit_guest_is_web_cam_or_not').val(0);
    })
</script>
