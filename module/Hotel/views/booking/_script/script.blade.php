<script>
    let selected_input;
    let multi_dimension_index = 0;
    let dueAmountForCurrency = 0;
    let room_wise_booking = {{ setting('room_wise_pricing_booking') }};


    let day_count = daysdifference($('input[name=check_in_date]').val(), $('input[name=check_out_date]').val());

    @if (Route::is('booking.create'))
        $(document).on('ready', addRow)
    @endif

    $(document).on('click', '#addrow', addRow)
    $(document).on('click', '#addrowInEdit', addRowInEdit)
    $(document).on('click', '.ibtnDel', removeRow)
    if (room_wise_booking == 1) {
        $(document).on('change', '.category', getRooms)
    } else {
        $(document).on('change', '.category', getRoomsByCategory)
    }

    $(document).on('click', '.add-guest-into-table', addGuest)

    $(document).on('click', '.input-guest-number', showGuestEntryModal)

    $(document).on('click', '.save-guest-information', saveGuestInformation)

    $(document).on('keyup', '.net-amount', calculateDayLongAmount)

    $(document).on('keyup', '.input-guest', countNumberOfGuest)

    $(document).on('keyup', '.input-infant', countNumberOfGuest)

    // $(document).on('click', '.guest-number', showGuestEntryModal)

    const numberOfGuest = $('.number-of-guest')



    const bulkTableHead = `<tr>
                                <td width="13%">Room Category<span class="text-danger">*</span></td>
                                <td class="text-center" style="width:20%">Room</td>
                                <td class="text-center" style="width: 10%">Total Room</td>
                                <td class="text-right">Room Rate</td>
                                <td class="text-right">Amount</td>
                                <td class="text-right">Night</td>
                                <td class="text-right">Discount</td>
                                <td class="text-center">Discount Type</td>
                                <td class="text-center">Breakfast</td>
                                <td class="text-right" width="15%">T. Amount</td>
                                <td class="text-center" style="width: 5%">
                                    <button type="button" class="btn btn-xs btn-success" id="addrow">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>`

    const singleTableHead = `<tr>
                                <td width="25%">Room Category<span class="text-danger">*</span></td>
                                <td class="text-left">Room</td>
                                <td class="text-center" style="width: 10%">Guest</td>
                                <td class="text-right">Amount</td>
                                <td class="text-right">Infant</td>
                                <td class="text-right">Child</td>
                                <td class="text-right">Night</td>
                                <td class="text-right">Discount</td>
                                <td class="text-right">Discount Type</td>
                                <td class="text-center breakfast_qty">BF Qty</td>
                                <td class="text-center">Breakfast</td>
                                <td class="text-right" width="15%">T. Amount</td>
                                <td class="text-center" style="width: 5%">
                                    <button type="button" class="btn btn-xs btn-success" id="addrow">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>`



    const rowItem = `<tr>

                        <td>
                            <select name="room_category[]" class="form-control chosen-select-100-percent category" data-placeholder="--Choose Category--">
                                <option value=""></option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            {!! Form::select('room_number[]', [], null, [
                                'class' => 'form-cotnrol room_number',
                                'placeholder' => 'Select Room Number',
                            ]) !!}
                        </td>

                        <td>
                            <select name="guest[]" class="form-control text-center guest-number input-guest select select2" data-placeholder="--Select Guest--">
                            </select>

                        </td>
                        <td>
                            <input type="text" value="" class="form-control amount only-number text-right" name="room_price[]">
                        </td>
                        <td>
                            {!! Form::number('infant[]', 0, ['class' => 'form-control text-right input-infant', 'min' => 0]) !!}
                        </td>
                        <td>
                            {!! Form::number('child[]', 0, ['class' => 'form-control text-right input-child', 'min' => 0]) !!}
                        </td>
                        <td>
                            {!! Form::number('night[]', 1, ['class' => 'form-control text-right night_count', 'readonly']) !!}
                        </td>
                        <td>
                            {!! Form::number('discount[]', 0, [
                                'class' => 'form-control text-right discount',
                                'type' => 'number',
                                'min' => 0,
                            ]) !!}

                        </td>
                        <td>

                            <select name="discount_type[]" class="form-control chosen-select-100-percent discount_type">
                                <option value="0" selected>Default</option>
                                <option value="1">Complementary</option>
                            </select>

                        </td>
                        <td class="breakfast_qty">
                            <input type="text" name="breakfast_qty[]" style="font-size: 16px" class="form-control input-sm text-right " value="0">

                        </td>
                        <td>
                            <label>
                                <input name="allow_breakfast"
                                    class="ace ace-switch ace-switch-6" value="1" type="checkbox"
                                    checked>
                                <span class="lbl"></span>
                            </label>
                        </td>
                        <td>
                            <input type="text" name="amount[]" style="font-size: 16px" class="form-control input-sm text-right only-number net-amount" value="" readonly>
                            <input type="hidden" value="" class="net-amount-hidden">
                            <input type="hidden" name="room_services[]" class="room-wise-service-charge">

                        </td>
                        <td class="text-center">
                            <a class="btn btn-xs btn-danger ibtnDel"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>`




    const rowBulkItem = `<tr>
                        <td>
                            <select name="room_category[]" class="form-control chosen-select-100-percent category" data-placeholder="--Choose Category--">
                                <option value=""></option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td class="text-center">

                            <span>ALL ROOM</span>

                            <div class="show-all-room" href="#show-category-rooms" role="button" data-toggle="modal">
                                <i class="fas fa-pen-square"></i>
                            </div>

                            <div id="show-category-rooms" class="modal show-category-room-modal" data-backdrop="static" data-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="blue bigger"><i class="fa fa-eye"></i> Available Rooms </h4>
                                        </div>

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <select name="room_number[][]",
                                                            class="form-control select2 room_number room-multiple"
                                                            data-placeholder="Select Room"
                                                            multiple readonly>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-sm" data-dismiss="modal">
                                                <i class="ace-icon fas fa-save"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </td>

                        <td class="text-center">
                            <span class="total-room-count" style="font-size:16px"></span>
                        </td>

                        <td>
                            <input type="text" value="" name="room_amount[]" class="form-control room-amount only-number text-right">
                        </td>

                        <td>
                            <input type="text" value="" class="form-control amount only-number text-right" readonly>
                        </td>
                        <td>
                            <input type="text" name="night[]" value="1" class="form-control only-number text-center night_count" readonly>
                        </td>
                        <td>
                            <input type="text" name="discount[]" value="" class="form-control only-number text-right discount">

                        </td>
                        <td>
                            <select name="discount_type[]" class="form-control chosen-select-100-percent discount_type">
                                <option value="0" selected>Default</option>
                                <option value="1">Complementary</option>
                            </select>

                        </td>
                        <td>
                            <label>
                                <input name="allow_breakfast"
                                    class="ace ace-switch ace-switch-6" value="1" type="checkbox"
                                    checked>
                                <span class="lbl"></span>
                            </label>
                        </td>
                        <td>
                            <input type="text" name="amount[]" style="font-size: 16px" class="form-control input-sm text-right only-number net-amount" value="" readonly>
                            <input type="hidden" value="" class="net-amount-hidden">
                            <input type="hidden" name="room_services[]" class="room-wise-service-charge">

                        </td>
                        <td class="text-center">
                            <a class="btn btn-xs btn-danger ibtnDel"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>`








    addMemberDetialItem()

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
                        <label class="col-sm-3 control-label">Age<sub class="text-danger">*</sub></label>
                        <div class="col-xs-12 col-sm-8">
                            <input type="text" class="form-control input-sm" name="member_age[]" placeholder="Enter Age">
                        </div>
                    </div>
                </div>

                <!-- Relation -->
                <div class="col-sm-12 mb-1">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Relation</label>
                        <div class="col-xs-12 col-sm-8">
                            <input type="text" class="form-control input-sm" name="relation[]" placeholder="Enter Relation">
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









    $(document).on('change', '.bulk-booking', function() {
        if ($(this).is(':checked')) {

            Swal.fire({
                title: 'Are you sure ?',
                html: "<div style='margin: 10px 0'><b>You will modify booking table !</b></div>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('.order-list thead').html(bulkTableHead)
                    $('.order-list tbody').empty().append(rowBulkItem)

                }
            })
        } else {

            Swal.fire({
                title: 'Are you sure ?',
                html: "<div style='margin: 10px 0'><b>You will modify booking table !</b></div>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, do it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('.order-list thead').html(singleTableHead)
                    $('.order-list tbody').empty().append(rowItem)
                }
            })

        }

        chosenSelectInit()
    })









    function addRow() {
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



        calculateDays()
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




    function calculateDays() {
        var check_in_date = $('.check-in-date').val()
        var check_out_date = $('.check_out').val();

        var start_date = new Date(check_in_date);
        var end_date = new Date(check_out_date);

        diff = new Date(end_date - start_date),
            days = diff / 1000 / 60 / 60 / 24;

        $('.night_count').val(days);

        // calculateNight();
        calculateAmount();

    }



    function calculateDaysForBookingEdit() {
        var check_in_date = $('.check-in-date').val()
        var check_out_date = $('.checkOut').val();

        var start_date = new Date(check_in_date);
        var end_date = new Date(check_out_date);

        diff = new Date(end_date - start_date),
            days = diff / 1000 / 60 / 60 / 24;

        $('.night_count').val(days);

        // calculateNight();
        calculateAmount();

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




    //------ CHECKOUT FOR -> [ BOOKING CREATE ] --------//
    // $(document).on('change', '.check_out', calculateDays);


    //------- CHECKOUT FOR -> [ BOOKING EDIT ] ---------//
    $(document).on('change', '.check_out', function() {

        let check_in = $('.check-in-date').val();
        let check_out = $(this).val();

        if (check_out < check_in) {
            toastr.warning('Check out date can not less than Check in date');
        } else {
            calculateDays()
        }

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


    //------ [ ROOM WISE BOOKING ] --------//

    function getRooms() {

        let _this = $(this);
        let category_id = _this.val()
        let isBookingEdit = $('.is-booking-edit').val();
        var check_in_date = $('.check-in-date').val();
        if (isBookingEdit == 1) {
            var checkOutDate = $('.checkOut').val();
        } else {
            var checkOutDate = $('.check_out').val();
        }
        let bulk_booking = $('.bulk-booking:checked').val();

        $.ajax({
            type: 'GET',
            url: '/hotel/room_for_booking/' + category_id,
            async: true,
            data: {
                check_in_date: check_in_date,
                check_out_date: checkOutDate,
                category_id: category_id,
                bulk_booking: bulk_booking
            },
            beforeSend: function() {
                $("body").css("cursor", "progress");
                $.LoadingOverlay("show")
            },
            success: function(data) {
                // console.log(data)
                let price = 0;
                if (bulk_booking) {
                    let roomNumberLength = $('.room_number').length - 1;

                    // OPENING MODAL
                    _this.closest('tr').find('.show-all-room').attr('href',
                        `#show-category-rooms${roomNumberLength}`)
                    _this.closest('tr').find('.show-category-room-modal').attr('id',
                        `show-category-rooms${roomNumberLength}`)


                    _this.closest('tr').find('.room_number').attr('name',
                        `room_number[${roomNumberLength}][]`)
                    _this.closest('tr').find('.room_number').empty().append(data.rooms)
                    _this.closest('tr').find('.room_number option').prop('selected', true).select2();
                    // _this.closest('tr').find('.room_number option').prop('selected', true).trigger('chosen:updated');


                    _this.closest('tr').find('.total-room-count').text(data.total_room)
                    _this.closest('tr').find('.room-amount').val(data.price)
                    price = data.total_room * data.price

                    // Store room-wise prices in a data attribute for each room option
                    _this.closest('tr').find('.room_number option').each(function() {
                        let roomId = $(this).val();
                        let roomPrice = calculateRoomPrice(
                            roomId
                        ); // You need to implement this function to get the room price
                        $(this).data('room-price', roomPrice);
                    });

                    $('.room_number').select2();

                } else {
                    price = data.price
                    _this.closest('tr').find('.room_number').empty().append(data.rooms).trigger(
                        'chosen:updated');
                }

                _this.closest('tr').find('.input-guest').html(data.guests).trigger('chosen:updated');

                // Handle room and guest selection changes
                $('.room_number').on('change', function() {
                    updatePrice();
                });

                $('.input-guest').on('change', function() {
                    updatePrice();
                });
                // let totalPrice = 0
                // Function to calculate the total price based on selected room and guests
                function updatePrice() {
                    let selectedRoomId = _this.closest('tr').find('.room_number').val();
                    let selectedGuests = _this.closest('tr').find('.input-guest').val();
                    let roomPrice = calculateRoomPrice(selectedRoomId);
                    let totalPrice = roomPrice * selectedGuests;

                    _this.closest('tr').find('.net-amount').val(totalPrice ? totalPrice : 0);
                    _this.closest('tr').find('.amount').val(totalPrice ? totalPrice : 0);
                    _this.closest('tr').find('.net-amount-hidden').val(totalPrice ? totalPrice : 0);
                    calculateAmount();
                }

                function calculateRoomPrice(roomId) {
                    // You need to implement this function to retrieve the room price based on the room ID.
                    // You can use the data attribute set earlier to get the room price.
                    return parseInt($(`option[value="${roomId}"]`).data('room-price'));
                }

                // if (day_count != 0) {
                //     _this.closest('tr').find('.net-amount').val(price);
                //     _this.closest('tr').find('.amount').val(price);
                //     _this.closest('tr').find('.net-amount-hidden').val(price);
                //     calculateAmount()
                // } else {
                //     _this.closest('tr').find('.net-amount').val(price);
                //     _this.closest('tr').find('.amount').val(price);
                //     _this.closest('tr').find('.net-amount-hidden').val(price);
                // }
            },
            complete: function(data) {
                $("body").css("cursor", "default");
                $.LoadingOverlay("hide")
            }
        });
        day_count = daysdifference($('input[name=check_in_date]').val(), $('input[name=check_out_date]').val());
        chosenSelectInit()

    }

    //------ [ CATEGORY WISE BOOKING ] --------//
    function getRoomsByCategory() {

        let _this = $(this);
        let category_id = _this.val()
        let isBookingEdit = $('.is-booking-edit').val();
        var check_in_date = $('.check-in-date').val();
        if (isBookingEdit == 1) {
            var checkOutDate = $('.checkOut').val();
        } else {
            var checkOutDate = $('.check_out').val();
        }
        let bulk_booking = $('.bulk-booking:checked').val();

        $.ajax({
            type: 'GET',
            url: '/hotel/room_by_category/' + category_id,
            async: true,
            data: {
                check_in_date: check_in_date,
                check_out_date: checkOutDate,
                category_id: category_id,
                bulk_booking: bulk_booking
            },
            beforeSend: function() {
                $("body").css("cursor", "progress");
                $.LoadingOverlay("show")
            },
            success: function(data) {
                console.log(data)
                let price = 0;
                if (bulk_booking) {
                    let roomNumberLength = $('.room_number').length - 1;

                    // OPENING MODAL
                    _this.closest('tr').find('.show-all-room').attr('href',
                        `#show-category-rooms${roomNumberLength}`)
                    _this.closest('tr').find('.show-category-room-modal').attr('id',
                        `show-category-rooms${roomNumberLength}`)


                    _this.closest('tr').find('.room_number').attr('name',
                        `room_number[${roomNumberLength}][]`)
                    _this.closest('tr').find('.room_number').empty().append(data.rooms)
                    _this.closest('tr').find('.room_number option').prop('selected', true).select2();
                    // _this.closest('tr').find('.room_number option').prop('selected', true).trigger('chosen:updated');


                    _this.closest('tr').find('.total-room-count').text(data.total_room)
                    _this.closest('tr').find('.room-amount').val(data.price)
                    price = data.total_room * data.price

                    $('.room_number').select2();

                } else {
                    price = data.price
                    _this.closest('tr').find('.room_number').empty().append(data.rooms).trigger(
                        'chosen:updated');
                }

                _this.closest('tr').find('.input-guest').html(data.guests).trigger('chosen:updated');
                if (day_count != 0) {
                    _this.closest('tr').find('.net-amount').val(price);
                    _this.closest('tr').find('.amount').val(price);
                    _this.closest('tr').find('.net-amount-hidden').val(price);
                    calculateAmount()
                } else {
                    _this.closest('tr').find('.net-amount').val(price);
                    _this.closest('tr').find('.amount').val(price);
                    _this.closest('tr').find('.net-amount-hidden').val(price);
                }
            },
            complete: function(data) {
                $("body").css("cursor", "default");
                $.LoadingOverlay("hide")
            }
        });
        day_count = daysdifference($('input[name=check_in_date]').val(), $('input[name=check_out_date]').val());
        chosenSelectInit()

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
        let myr_rate = `{{ getCurrentCurrencyRate('myr') }}`
        let sr_rate = `{{ getCurrentCurrencyRate('sr') }}`

        if (_this.val() == 141) {
            dueAmountForCurrency = Number(usd_rate) * Number($('.grandtotal').val())
            currenty_rate_text = 'USD Amount ' + dueAmountForCurrency.toFixed(2);
        } else if (_this.val() == 12) {

            dueAmountForCurrency = Number(bdt_rate) * Number($('.grandtotal').val())
            currenty_rate_text = 'BDT Amount ' + dueAmountForCurrency.toFixed(2);
        } else if (_this.val() == 96) {
            dueAmountForCurrency = Number(myr_rate) * Number($('.grandtotal').val())
            currenty_rate_text = 'RM Amount ' + dueAmountForCurrency.toFixed(2);

        } else if (_this.val() == 116) {

            dueAmountForCurrency = Number(sr_rate) * Number($('.grandtotal').val())
            currenty_rate_text = 'SR Amount ' + dueAmountForCurrency.toFixed(2);

        }
        $('.show-currency-rate').text(currenty_rate_text);

        return dueAmountForCurrency;
    }


    $(document).on('keyup', '.adv-amount, .extra-charge, .discount, .discount_type', calculateAmount)





    function calculateAmount() {

        var subtotal = 0;
        var vat = Number($('#vat').val());
        let service_percent = Number(`{{ vatSetting()->room_service_charge }}`)
        let room_rate       = Number(`{{ vatSetting()->room_rate }}`)
        let roomWiseServiceCharge = 0


        $('.item-details tr').each(function() {
            let roomPrice = 0

            let amount = Number($(this).find('.net-amount-hidden').val())
            let night_count = Number($(this).find('.night_count').val())
            let discount = Number($(this).find('.discount').val())
            let discount_type = Number($(this).find('.discount_type').val())
            // console.log(complementary);
            // console.log(discount_type);

            roomPrice = (night_count * amount) - (discount * night_count)

            $(this).closest('tr').find('.net-amount').val(roomPrice.toFixed(2));

            subtotal += roomPrice;

            var rate = (subtotal / room_rate) * 100;

            // roomWiseServiceCharge = (roomPrice / 100) * service_percent
            roomWiseServiceCharge = (rate * service_percent) / 100;

            $(this).closest('tr').find('.room-wise-service-charge').val(roomWiseServiceCharge)

        });

        let vat_included = {{ setting('use_vat_included') }};

        if (vat_included == 1) {

            var rate = (subtotal / room_rate) * 100;

            var service_amount = (rate * service_percent) / 100;

            var total_without_vat = rate + service_amount;

            var calculate_vat = (total_without_vat * vat) / 100;

            var extra_charge = Number($('.extra-charge').val()) || 0;

            var grandtotal = total_without_vat + calculate_vat + extra_charge;

        } else {

            var service_amount = (subtotal / 100) * service_percent;

            var calculate_vat = ((subtotal + service_amount) * vat) / 100;

            var extra_charge = Number($('.extra-charge').val()) || 0;

            var grandtotal = subtotal + service_amount + calculate_vat + extra_charge;
        }


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




    function calculateDayLongAmount() {

        // Sum all Sub-total
        if (day_count == 0) {

            countNumberOfGuest()

            calculateAmount()
        }
    }



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




    function daysdifference(firstDate, secondDate) {
        var startDay = new Date(firstDate);
        var endDay = new Date(secondDate);

        var millisBetween = startDay.getTime() - endDay.getTime();

        var days = millisBetween / (1000 * 3600 * 24);

        return Math.round(Math.abs(days));
    }




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




    $(document).on('change', '.input-guest', function() {

        let _this = $(this).closest('tr')

        let price = Number($(this).find('option:selected').data('price'))

        if (price != 0 && price != null) {

            $(this).closest('tr').find('.amount').val(price)
            $(this).closest('tr').find('.net-amount, .net-amount-hidden').val(price)

            calculateAmount()
        }

    });


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

        let cat_wise                    = {{ setting('category_wise_booking') }};
        let haveAdvanceAmount           = $('.adv-amount').val();
        let isPaymentMethodSelected     = $('.payment-type').val();

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

$(function() {
        loadSelect2DOM()
    })

    function loadSelect2DOM() {

        loadSelect2({
            url:'{{ route('get-guest-data') }}',
            // url: "/bar/get-guest-data",
            select: '.guest-search',
            templateResult: formatGuest,
            templateSelection: formatGuestSelection
        })

        function formatGuest(guest) {
            if (guest.loading) {
                return guest.text;
            }

            return templateResult(`${guest.name} - ${guest.phone_no}`, null)
        }

        function formatGuestSelection(guest) {
            return guest.name || guest.text;
        }

    }
</script>




    <script>

    //---------------------------------------------------------------
    //            SELECT PATYMENT CARD AND INPUT FIELD SHOW
    //---------------------------------------------------------------

        $(document).on('change', '.payment-type', function() {
            var selectedPaymentMethod = $.trim($(this).find('option:selected').text());

            if (selectedPaymentMethod.toLowerCase().indexOf('card') !== -1) {
                $('.card_info').show();
            } else {
                $('.card_info').hide();
            }
        });

        // Listen for the Chosen plugin's update event
        $(document).on('chosen:updated', '.payment-type', function() {
            var selectedPaymentMethod = $.trim($(this).find('option:selected').text());

            if (selectedPaymentMethod.toLowerCase().indexOf('card') !== -1) {
                $('.card_info').show();
            } else {
                $('.card_info').hide();
            }
        });

    </script>
