<script>
    let selected_input;
    let multi_dimension_index = 0;
    let dueAmountForCurrency = 0;
    let room_wise_booking = {{ setting('room_wise_pricing_booking') }};


    let day_count = daysdifference($('input[name=check_in_date]').val(), $('input[name=check_out_date]').val());

    @if (Route::is('banquet.booking.create'))
        $(document).on('ready', addRow)
    @endif

    // CLICK TO ADD ROW METHOD CALL //
    $(document).on('click', '#addrow', addRow)
    $(document).on('click', '#addItem', addItem)
    $(document).on('click', '#addInformation', addInformation)


    $(document).on('click', '#addrowInEdit', addRowInEdit)
    $(document).on('click', '.ibtnDel', removeRow)

    $(document).on('change', '.room_number', getRoomInfo)
    $(document).on('change', '.product_id', getItemInfo)
    // $(document).on('keyup', '.rest_item_qty', getItemInfo)

    $(document).on('click', '.add-guest-into-table', addGuest)

    $(document).on('click', '.input-guest-number', showGuestEntryModal)

    $(document).on('click', '.save-guest-information', saveGuestInformation)

    $(document).on('keyup', '.net-amount', calculateDayLongAmount)

    // $(document).on('keyup', '.input-guest', countNumberOfGuest)

    // $(document).on('keyup', '.input-infant', countNumberOfGuest)

    // $(document).on('click', '.guest-number', showGuestEntryModal)

    // const numberOfGuest = $('.number-of-guest')



    const bulkTableHead = `<tr>
                                <td class="text-center" style="width:20%">Room</td>
                                <td class="text-center" style="width: 10%">Total Room</td>
                                <td class="text-right">Room Rate</td>
                                <td class="text-right">Amount</td>
                                <td class="text-right">Booked</td>
                                <td class="text-right">Discount</td>
                                <td class="text-right" width="15%">T. Amount</td>
                                <td class="text-center" style="width: 5%">
                                    <button type="button" class="btn btn-xs btn-success" id="addrow">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>`

    const singleTableHead = `<tr>
                                <td class="text-left">Room</td>
                                <td class="text-center" style="width: 10%">Guest</td>
                                <td class="text-right">Amount</td>
                                <td class="text-right">Infant</td>
                                <td class="text-right">Booked</td>
                                <td class="text-right">Discount</td>
                                <td class="text-right" width="15%">T. Amount</td>
                                <td class="text-center" style="width: 5%">
                                    <button type="button" class="btn btn-xs btn-success" id="addrow">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>`



    const rowItem = `<tr>

                        <td>
                            <select name="room_number[]" class="form-control chosen-select-100-percent room_number" data-placeholder="--Choose Hall--">
                                <option value=""></option>
                                @foreach ($rooms as $item)
                                    <option value="{{ $item->id }}" data-room-price="{{ $item->price }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>

                        </td>

                        <td>
                            <input type="text" value="" name="guest[]" class="form-control text-center guest-number input-guest">
                        </td>
                        <td>
                            <input type="text" value="" class="form-control amount only-number text-right" name="room_price[]">
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="hidden" name="booked_start_end" id="booked_start_end">

                                <input type="text" class="form-control time-picker" id="date_start"
                                    value="">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control time-picker" id="date_end"
                                    value="">
                            </div>
                        </td>
                        <td>
                            {!! Form::number('discount[]', 0, [
                                'class' => 'form-control text-right discount',
                                'type' => 'number',
                                'min' => 0,
                            ]) !!}

                        </td>
                        <td>
                            <input type="text" name="room_amount[]" style="font-size: 16px" class="form-control input-sm text-right only-number net-amount" value="" readonly>
                            <input type="hidden" value="" class="net-amount-hidden">
                            <input type="hidden" name="room_services[]" class="room-wise-service-charge">

                        </td>
                        <td class="text-center">
                            <a class="btn btn-xs btn-danger ibtnDel"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>`


    const rowRestaurantItem = `<tr>
                        <td>
                            <select name="product_id[]" class="form-control chosen-select-100-percent product_id" data-placeholder="--Choose Restaurant Item--">
                                <option value=""></option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->sale_price }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </td>


                        <td>
                            <input type="text" value="1" name="rest_item_qty[]" class="form-control text-center rest_item_qty input-qty">
                        </td>
                        <td>
                            <input type="text" value="" class="form-control amount only-number text-right" name="rest_item_price[]">
                        </td>


                        <td>
                            {!! Form::number('rest_item_discount[]', 0, [
                                'class' => 'form-control text-right discount',
                                'type' => 'number',
                                'min' => 0,
                            ]) !!}

                        </td>
                        <td>
                            <input type="text" name="rest_item_product[]" style="font-size: 16px" class="form-control input-sm text-right only-number net-amount" value="" readonly>
                            <input type="hidden" value="" class="net-amount-hidden">
                            <input type="hidden" name="product_room_services[]" class="room-wise-service-charge">

                        </td>
                        <td class="text-center">
                            <a class="btn btn-xs btn-danger ibtnDel"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>`


    const rowInfoItem = `<tr>
                        <td>
                            <input type="text" value="" name="input_item_name[]" class="form-control text-left input_item_name input-item-name">
                        </td>

                        </td>

                        <td>
                            <input type="text" value="" name="input_item_qty[]" class="form-control text-center input_item_qty input-qty">
                        </td>
                        <td>
                            <input type="text" value="" class="form-control amount only-number text-right" name="input_item_price[]">
                        </td>


                        <td>
                            {!! Form::number('input_item_discount[]', 0, [
                                'class' => 'form-control text-right discount',
                                'type' => 'number',
                                'min' => 0,
                            ]) !!}

                        </td>
                        <td>
                            <input type="text" name="input item_amount[]" style="font-size: 16px" class="form-control input-sm text-right only-number net-amount" value="" readonly>
                            <input type="hidden" value="" class="net-amount-hidden">
                            <input type="hidden" name="input_item_room_services[]" class="room-wise-service-charge">

                        </td>
                        <td class="text-center">
                            <a class="btn btn-xs btn-danger ibtnDel"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>`













    function addRow() {

        $(document).find(".room-details-tbody").append(rowItem);

        $('.time-picker').timepicker({
            minuteStep: 1,
            showMeridian: true,
            defaultTime: '',
            icons: {
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down'
            }
        })



        calculateAmount()
        chosenSelectInit()

    }


    // Add Item
    function addItem() {

            $(document).find(".item-details-tbody").append(rowRestaurantItem)
            calculateAmount()
            chosenSelectInit()

    }

    function addInformation() {

            $(document).find(".info-details-tbody").append(rowInfoItem)
            calculateAmount()
            chosenSelectInit()

    }


    function addRowInEdit() {
        if ($('.bulk-booking').is(':checked')) {
            $(document).find(".room-details-tbody").append(rowBulkItem)

            let category_ids = [];

            $('.category option:selected').each(function() {
                category_ids.push($(this).val());
            })

            $('.room-details-tbody tr').find('.category option').each(function(index) {
                if (category_ids.includes($(this).val())) {
                    // $(this).prop("disabled", "disabled");
                    $(this).css("style", "pointer-events: none").select2();
                    // $(this).parent().find('.chosen-container').css({'pointer-events': 'none'});
                }
            })

        } else {
            $(document).find(".room-details-tbody").append(rowItem)
        }

    }




    function removeRow() {
        $(this).closest("tr").remove()
        serial()
        calculateAmount()
    }





    function serial() {
        $('.serial').each(function(index) {
            $(this).text(index + 1)
        })
        // multi_dimension_index = Number($('.serial').text()) - 1;
    }







    //-------------------------------------//
    //    DISABLE PREVIOUS CHECKOUT DATE   //
    //-------------------------------------//
    var expectedCheckoutDate = $('.expectedCheckoutDate').val();

    $('.checkOut').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        startDate: expectedCheckoutDate,
    }).next().on(ace.click_event, function() {
        $(this).prev().focus();
    });




    //------- CHECKOUT FOR -> [ BOOKING EDIT ] ---------//
    $(document).on('change', '.checkOut', function() {

        // let check_in    = $('.previousCheckoutDate').val();
        let check_in = $('.check-in-date').val();
        let check_out = $(this).val();
        let actualCheckOut = $('.previousCheckoutDate').val();
        let booking_id = $('#bookingId').val();

        var promises = [];

        $(".room_number").each(function() {
            let room_id = $(this).find('option:selected').val();
            let room_name = $(this).find('option:selected').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var request = $.ajax({
                type: 'GET',
                url: '{{ route('check-room-availability') }}',
                data: {
                    booking_id: booking_id,
                    room_id: room_id,
                    check_in_date: check_in,
                    check_out_date: check_out
                },
                success: function(response) {

                    if (response != null && response != '') {

                        if (response.is_booked > 0 || response.is_reservation > 0) {

                            $('.isRoomAvailable').val(1);

                            $('.checkOut').val(actualCheckOut);
                            toastr.error('Room no ' + room_name + ' not available!');

                        }

                    } else {
                        $('.checkOut').val(actualCheckOut);
                        toastr.error('Not working, Please try again!');
                    }


                },
                error: function() {
                    $('.checkOut').val(actualCheckOut);
                    toastr.error('Something Went Wrong!');
                }
            });
            promises.push(request);
        });


        $.when.apply(null, promises).done(function() {
            if ($('.isRoomAvailable').val() == 0) {

                calculateDaysForBookingEdit();

                toastr.success('Room are available!');

            }
        })


    });




    $(document).on('keyup', '.vat-amount', function() {

        let total = 0;
        $('.net-amount').each(function() {
            total += Number($(this).val());
        })

        let vat_amount = $(this).val();
        let service_amount = $('input[name=service_amount]').val();

        total = Number(vat_amount) + Number(total) + Number(service_amount);

        $('input[name=sub_total]').val(total);

    })


    $(document).on("keyup", ".amount", function() {

        $(this).closest('tr').find('.net-amount, .net-amount-hidden').val($(this).val())

        // calculateNight()
        calculateAmount()
        // calculateDiscount()
    });


    function getRoomInfo() {

            let _this = $(this);
            let price = $(this).find('option:selected').data('room-price');
            _this.closest('tr').find('.amount').val(price ? price : 0);
            calculateAmount();

    }


    function getItemInfo() {

            let _this       = $(this);
            let price       = $(this).find('option:selected').data('price');
            let item_qty    = $(this).closest('tr').find('.rest_item_qty').val()

            // let total = price * item_qty
            // console.log(item_qty , price);

            _this.closest('tr').find('.amount').val(price ? price : 0);

            calculateAmount();



    }



    function showGuestEntryModal() {


        selected_input = this

        $('.modal-number-of-guest').val($(this).val())

        let input_guest_table = $(selected_input).closest('.guest-information-input').find('table').clone()

        $('.guest-information-modal-body').empty().append(input_guest_table)

        $('#guest-information-modal').modal('show');
    }




    function loadGuestInformationTable() {
        return `<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody class="guest-entry-table-body">

                    </tbody>
                </table>`
    }




    const guestEntryTableRow = `<tr>
            <td><span class="guest-table-serial"></span></td>
            <td>
                <input type="text" class="form-control input-sm" name="guest_names[${multi_dimension_index}][]" placeholder="Guest Name">
            </td>
            <td>
                <input type="number" class="form-control input-sm" name="guest_phones[${multi_dimension_index}][]" placeholder="Enter Phone no" required>
            </td>
            <td>
                <input type="text" class="form-control input-sm" name="guest_address[${multi_dimension_index}][]" placeholder="Enter Address">
            </td>
        </tr>`






    function addGuest() {
        let modal_table_body = $('.guest-information-modal-body').find('.guest-entry-table-body')

        modal_table_body.empty()
        for (let index = 0; index < $('.modal-number-of-guest').val(); index++) {
            modal_table_body.append(guestEntryTableRow)
        }
        guestTableSerial()
    }

    function guestTableSerial() {
        $('.guest-information-modal-body').find('.guest-table-serial').each(function(index) {
            $(this).text(index + 1)
        })
    }



    function saveGuestInformation() {

        var modalBody = $(".guest-information-modal-body")


        let modal_table = modalBody.find('table').clone()


        $(selected_input).val($('.modal-number-of-guest').val())
        $(selected_input).closest('.guest-information-input').find('.guest-details').empty().append(modal_table)

        $('.guest-information-modal-body').empty()
        $("#guest-information-modal").modal('hide')

    }






    $('.display_show').change(function() {
        $('.show-div').show();
    })




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


    $(document).on('keyup', '.adv-amount, .extra-charge, .discount, .discount_type, .rest_item_qty', calculateAmount)





    function calculateAmount() {

        var subtotal = 0;
        var vat = Number($('#vat').val());
        let service_percent = Number(`{{ vatSetting()->room_service_charge }}`)
        let roomWiseServiceCharge = 0

        $('.item-details tr').each(function() {

            let roomPrice = 0

            // let amount          = Number($(this).find('.net-amount-hidden').val())
            let amount          = Number($(this).find('.amount').val())
            let night_count     = Number($(this).find('.night_count').val())
            let rest_item_qty   = Number($(this).find('.rest_item_qty').val())
            let discount        = Number($(this).find('.discount').val())
            let discount_type   = Number($(this).find('.discount_type').val())

            roomPrice = amount - discount

            $(this).closest('tr').find('.net-amount').val(roomPrice.toFixed(2));

            subtotal += roomPrice;
            roomWiseServiceCharge = (roomPrice / 100) * service_percent

            $(this).closest('tr').find('.room-wise-service-charge').val(roomWiseServiceCharge)

        });

        var service_amount  = (subtotal / 100) * service_percent;

        var calculate_vat   = ((subtotal + service_amount) / 100) * vat;

        var extra_charge    = Number($('.extra-charge').val() | 0);

        let grandtotal      = subtotal + service_amount + calculate_vat + extra_charge


        let currentCurrency = `{{ setting('root_currency') }}`;
        let advAmount = Number($('.adv-amount').val());



        $(".grandtotal").val(grandtotal.toFixed(2));

        let due_amount = currencyConversion() - advAmount;

        $("#line_total, .subtotal-amount").val(subtotal.toFixed(2));
        $(".vat-amount").val(calculate_vat.toFixed(2));
        $(".service_amount").val(service_amount.toFixed(2));
        $(".due-amount").val(due_amount.toFixed(2));
    }




    function calculateDayLongAmount() {

        // Sum all Sub-total
        if (day_count == 0) {

            // countNumberOfGuest()

            calculateAmount()
        }
    }



    // function countNumberOfGuest() {
    //     if (day_count == 0) {

    //         let guest   = $(this).closest('tr').find('.input-guest').val();
    //         let infant  = $(this).closest('tr').find('.input-infant').val();
    //         let amount  = $(this).closest('tr').find('.amount').val();

    //         let totalAmount = (Number(guest) * Number(amount)) + (Number(infant) * (Number(amount) / 2));

    //         $(this).closest('tr').find('.net-amount').val(totalAmount)
    //         $(this).closest('tr').find('.net-amount-hidden').val(totalAmount)
    //         calculateAmount()
    //     }
    // }




    function daysdifference(firstDate, secondDate) {
        var startDay    = new Date(firstDate);
        var endDay      = new Date(secondDate);

        var millisBetween = startDay.getTime() - endDay.getTime();

        var days          = millisBetween / (1000 * 3600 * 24);

        return Math.round(Math.abs(days));
    }




    $(document).on('click', '#save-btn', function() {
        let name    = $('input[name=guest_name]').val();
        let phone   = $('input[name=phone_no]').val();

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




    // $(document).on('change', '.input-guest', function() {

    //     let _this = $(this).closest('tr')

    //     let price = Number($(this).find('option:selected').data('price'))

    //     if (price != 0 && price != null) {

    //         $(this).closest('tr').find('.amount').val(price)
    //         $(this).closest('tr').find('.net-amount, .net-amount-hidden').val(price)

    //         calculateAmount()
    //     }

    // });


    /*
    | INPUT/SELECT CONTROL ON CHANGE
    */
    $(document).on('change', '#customer_id', function() {
        $('#purpose').focus().trigger('chosen:updated');
    })




    $(document).on('change', '[name=status]', function() {
        $('.check-in-note').toggle()
    })


    $(document).on('change', '.room-multiple', function() {

        let selectedTotalRoom = $(this).find('option:selected').length;
        let _this = $(this);

        _this.closest('tr').find('.total-room-count').text(selectedTotalRoom);

        let room_amount = Number(_this.closest('tr').find('.room-amount').val());
        let total_amount = room_amount * selectedTotalRoom;

        _this.closest('tr').find('.amount').val(total_amount);
        _this.closest('tr').find('.net-amount-hidden').val(total_amount);

        calculateAmount()
    })

    $(document).on('keyup', '.room-amount', function() {
        let _this = $(this);
        let selectedTotalRoom = Number(_this.closest('tr').find('.total-room-count').text());
        let room_amount = Number(_this.closest('tr').find('.room-amount').val());
        let total_amount = room_amount * selectedTotalRoom;

        _this.closest('tr').find('.amount, .net-amount, .net-amount-hidden').val(total_amount);
        calculateAmount()
    })



    //---------------------------------------------------------------//
    //                      SUBMIT BOOKING FORM                      //
    //---------------------------------------------------------------//
    function submitBookingForm() {

        let cat_wise = {{ setting('category_wise_booking') }};
        // console.log(cat_wise);
        // return false
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

        // if ($('.room_number').val() == '') {
        //     toastr.error('Please select a room');
        //     return;
        // }

        if (cat_wise == 0) {
            if ($('.room_number').val() == '') {
                toastr.error('Please select a room');
                return;
            }
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





    // $(document).on('change', '.check-in-date', function(){
    //     let date = $(this).val();

    //     $.ajax({
    //         url:'{{ route('check-night-audit') }}',
    //         method:GET,
    //         data:{
    //             check_in_date: date,
    //         },
    //         success:function(res){
    //             if (res.status) {
    //                 alert('You can not booking this date');
    //                 $('.order-list tbody').empty()
    //             }
    //         }
    //     })
    // })
</script>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
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
                    // console.log(response);
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

<script>
    $(document).on('change', '#date_start, #date_end', function() {
        let start = $('#date_start').val()
        let end = $('#date_end').val()
        let format = start + '-' + end;
        $('#booked_start_end').val(format)
        console.log($('#booked_start_end').val());
    })
</script>
