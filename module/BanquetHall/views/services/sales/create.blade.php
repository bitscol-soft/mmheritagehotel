@extends('layouts.master')
@section('title', 'Add New Hotel Service Sale')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }

    </style>
@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-sale-form" title="New Hotel Service Sale">
        <x-slot name="actions">
            @if (hasPermission('service.view', $slugs))
                <a href="{{ route('hotelservice.service-sales.index') }}" class="btn btn-sm btn-default">
                    <i class="fa fa-list-alt"></i> Hotel Service Sale
                </a>
            @endif
        </x-slot>

        <x-mm.panel>
                        @include('partials._alert_message')

                        <div class="row">
                            <div class="panel-body">
                                <form method="POST" action="{{ route('hotelservice.service-sales.store') }}"
                                    class="form-horizontal" id="invForm">
                                    @csrf

                                    <!-- hidden fields -->
                                    <div class="form-group">
                                        <input class="form-control" type="hidden" name="company_name" />
                                        <input type="hidden" name="invoice_no">
                                    </div>

                                    <div>



                                        <!-- info -->
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Guest Name :</label>

                                                        <input class="form-control" type="text" id="guest_name" name="guest_name" placeholder="Guest Name" autocomplete="off"
                                                        required />
                                                        <input type="hidden" value="" name="hotel_guest_id" id="hotel_guest_id">
                                                    </div>
                                                </div>


                                                 {{-- Search By Room --}}
                                                <div class="col-md-2 ml-1">
                                                    <div class="form-group">
                                                        <label class="control-label">Room Number :</label>

                                                        <input type="hidden" name="hotel_room_id" id="hotel_room_id">

                                                        <input type="text" name="room_number" id="room_number"
                                                            placeholder="Room Number" class="form-control">
                                                    </div>
                                                </div>


                                                {{-- Search By Booking --}}
                                                <div class="col-md-2 ml-1">
                                                    <div class="form-group">
                                                        <label class="control-label">Booking Number :</label>

                                                        <input type="hidden" name="hotel_booking_id" id="hotel_booking_id" value="">

                                                        <input type="text" name="booking_number" id="booking_number"
                                                            placeholder="Booking Number" class="form-control">
                                                        {{-- <p class="text-danger text-center">Not Found!</p> --}}
                                                    </div>
                                                </div>

                                                 <!-- Sale Invoice ID -->
                                                <div class="col-md-2 ml-1">
                                                    <div class="form-group">
                                                        <label class="control-label">Invoice ID #</label>
                                                        <input type="text" tabindex="-1" class="form-control" id="invoice_id"
                                                            placeholder="Invoice ID" name="invoice_no" value=""
                                                            readonly>
                                                    </div>
                                                </div>


                                                <!-- Sale Date -->
                                                <div class="col-md-2 ml-1">
                                                    <div class="form-group">
                                                        <label class="control-label">Date :</label>
                                                        <input type="text" name="date" value="{{ date('Y-m-d') }}"
                                                            class="form-control date-picker" autocomplete="off">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- transition -->
                                        <div class="view_center_folwchart">
                                            <div class='row'>
                                                <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'>
                                                    <table class="table table-bordered table-hover" id="table_auto">
                                                        <thead>
                                                            <tr>
                                                                <th width="30%">Service Name</th>
                                                                <th width="15%">Service Price</th>
                                                                <th width="15%">Quantity</th>
                                                                <th width="15%">Total</th>
                                                                <th width="5%">
                                                                    <button type="button" onclick="addItem()"
                                                                        class="btn btn-success btn-xs r-btnAdd">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>

                                                        <tbody class="container">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <br>
                                            <div class="row">


                                                <div class="col-sm-6 col-sm-offset-6">
                                                    <div class="row">
                                                        <div class='col-xs-12 col-sm-8 col-md-8 col-lg-8 col-sm-offset-3'>
                                                            <div class="form-inlines">
                                                                <div class="form-group aside_system">
                                                                    <label class="col-md-4 control-label">
                                                                        Sub Totals:
                                                                    </label>
                                                                    <div class="input-group col-md-8">
                                                                        <div class="input-group-addon currency">৳</div>
                                                                        <input value="" type="number" min="0" step="any"
                                                                            class="form-control" name="subtotal"
                                                                            id="subTotal" placeholder="Subtotal"
                                                                            onkeypress="return IsNumeric(event);"
                                                                            ondrop="return false;" onpaste="return false;"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group aside_system">
                                                                    <label class="col-md-4 control-label">
                                                                        Discount:
                                                                        <span id="service-discount"></span>
                                                                    </label>
                                                                    <input type="hidden" id="service-discount-val">
                                                                    <div class="input-group col-md-8">
                                                                        <div class="input-group-addon currency">৳</div>
                                                                        <input value="0" type="number" min="0" step="any"
                                                                            class="form-control" name="discount"
                                                                            id="discount" placeholder="Discount">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group aside_system">
                                                                    <label class="col-md-4 control-label">
                                                                        Total Amount:
                                                                    </label>
                                                                    <div class="input-group col-md-8">
                                                                        <div class="input-group-addon currency">৳</div>
                                                                        <input value="" type="number" min="0" step="any"
                                                                            class="form-control" name="payable_amount"
                                                                            id="payable_amount" placeholder="Payable Amount"
                                                                            onkeypress="return IsNumeric(event);"
                                                                            ondrop="return false;" onpaste="return false;"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group aside_system">
                                                                    <label class="col-md-4 control-label">
                                                                        Paid Amount:
                                                                    </label>
                                                                    <div class="input-group col-md-8">
                                                                        <div class="input-group-addon currency">৳</div>
                                                                        <input value="0" type="number" min="0" step="any"
                                                                            class="form-control" autocomplete="off"
                                                                            name="paid_amount" id="amountPaid"
                                                                            placeholder="Paid Amount"
                                                                            onkeypress="return IsNumeric(event);"
                                                                            ondrop="return false;" onpaste="return false;">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group aside_system">
                                                                    <label class="col-md-4 control-label">Amount Due
                                                                        :</label>
                                                                    <div class="input-group col-md-8">
                                                                        <div class="input-group-addon currency">৳</div>
                                                                        <input value="" class="form-control"
                                                                            name="due_amount" id="amountDue"
                                                                            placeholder="Amount Due" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-md-4"></label>
                                                                    <div class="col-md-8 col-sm-8 no-padding">
                                                                        <button type="button" onclick="submitForm()"
                                                                            class="btn btn-primary col-md-8"
                                                                            style="width: 100%;">Confirm</button>
                                                                    </div>
                                                                    <label class="control-label col-md-2 col-sm-2"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
        </x-mm.panel>
    </x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/jquery.form-repeater.js') }}"></script>
    <script src="{{ asset('assets/custom_js/loadDetails.js') }}"></script>
    <script src="{{ asset('assets/custom_js/guest_filter.js') }}"></script>
    <script src="{{ asset('assets/custom_js/reference_filter.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.reference').hide()
            $('.internal-doctor').show()
        })
        $('.checked-reference').click(function() {

            loadStaff($(this).val())

        })

        $('#discount, #amountPaid').keyup(function() {
            totalAmount()
        })

        function submitForm() {
            let is_submit = true;


            if ($('#guest_name').val() == '') {
                is_submit = false;
                warning('toster', 'Please select Guest Type!')

            } else if ($('.today').val() == '') {
                is_submit = false;

                warning('toster', 'Please select date !')

            } else {
                $('.service-ids').each(function() {
                    console.log($(this).val());
                    if ($(this).val() == '') {
                        is_submit = false;

                        warning('toster', 'Please choose service !')
                    }
                })
            }

            if (is_submit == true) {
                $('#invForm').submit();
            }
        }

        function totalAmount() {
            let total = 0;
            let discount = $('#discount').val() ? parseFloat($('#discount').val()) : 0
            let amountPaid = $('#amountPaid').val() ? parseFloat($('#amountPaid').val()) : 0
            $('.service-total').each(function(i, price) {
                let p = $(price).text();
                total += p ? parseFloat(p) : 0;
            });
            let discount_amount = Math.ceil((total * discount) / 100);

            let payableAmount = total - discount;

            $('#subTotal').val(total);
            $('#payable_amount').val(payableAmount);
            // $('#discount').val(discount_amount).attr('readonly', true);
            $('#amountDue').val(payableAmount - amountPaid)

        }
    </script>


    {{-- Get Room Number --}}
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


    {{-- Get Booking Number --}}
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

    {{-- Get Info By Booking --}}

    <script>
        function getInfoByBooking(booking_id) {

            $.ajax({
                type:'get',
                url: '/hotelservice/guest-by-booking/'+booking_id,
                async :true,

                beforeSend: function () {
                    $("body").css("cursor", "progress");
                },
                success: function (data) {
                    $('#guest_name').val(data['guest_name']);
                    $('#room_number').val(data['room_number']);
                    $('#hotel_booking_id').val(booking_id);
                    $('#hotel_guest_id').val(data['guest_id']);
                },
                complete: function (data) {
                    $("body").css("cursor", "default");
                }
            });
        }
    </script>

    {{-- Get Info By Room --}}
    <script>
        function GetInfoRoom(rooms_id) {

            $.ajax({
                type:'get',
                url: '/hotelservice/guest-by-rooms/'+rooms_id,
                async :true,

                beforeSend: function () {
                    $("body").css("cursor", "progress");
                },
                success: function (data) {
                    $('#guest_name').val(data['guest_name']);
                    $('#booking_number').val(data['booking_number']);
                    $('#hotel_booking_id').val(data['booking_id']);
                    $('#hotel_guest_id').val(data['guest_id']);

                },
                complete: function (data) {
                    $("body").css("cursor", "default");
                }
            });
        }
    </script>

    <script>
        addItem()

        function addItem(e) {
            let html = `<tr class="repeat-group">
                            <td>
                                <input type="hidden" class="service-ids" name="service_id[]" >
                                <input class="form-control service-names" type="text" name="name[]" required />
                            </td>
                            <td>
                                <input class="form-control service-prices" type="text" name="price[]" id="service_0_price" readonly/>
                            </td>
                            <td>
                                <input class="form-control service-quantity" onkeyup=itemTotal(this) type="number" name="quantity[]"/>
                            </td>
                            <td>
                                <strong class="service-total"></strong>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-xs r-btnRemove" onclick="deleteRow(this)" ><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </td>
                        </tr>`;

            $('.container').append(html);
        }


        function deleteRow(obj) {
            $(obj).parents('.repeat-group').remove();
        }


        function itemTotal(object) {
            let price = parseFloat($(object).closest('tr').find('.service-prices').val());
            let qty = parseInt($(object).val());

            let total = parseFloat(price * qty);
            $(object).closest('tr').find('.service-total').text(total)

            totalAmount();

        }
    </script>

    <script>
        $(document).on('focus', '.service-names', function() {
            $(this).autocomplete({
                source: function(request, response) {
                    $.getJSON("{{ url('hotelservice/get-h-service') }}", {
                        name: request.term
                    }, function(data) {
                        response($.map(data, function(item) {
                            return {
                                value: item.name,
                                label: item.name,
                                data: item,
                            }
                        }))
                    })
                },
                select: function(event, ui) {
                    var item = $(event.target);
                    let check = true
                    $('.service-ids').map(function() {
                        if ($(this).val() == ui.item.data.id) {
                            check = false
                            warning('toster', 'This service already added !')
                            $(this).val().val('')
                        }
                    })


                    if (check == true) {
                        item.parents('tr').find('.service-prices').val(ui.item.data.price);
                        item.parents('tr').find('.service-ids').val(ui.item.data.id);
                        // totalAmount();
                    }

                },
            });
        })



        function requestUrl(urlParts) {
            urlParts = $.extend({
                basePath: '{{ url('/') }}/',
                path: '',
                param: ''
            }, urlParts);
            return urlParts.basePath + urlParts.path + urlParts.param;
        }


        function patientFilter() {
            selfFilter('service');

        }

        function referencePersonFilter() {
            referenceFilter();
        }
    </script>


@endsection
