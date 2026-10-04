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
<x-mm.page class="mm-hotel-service" title="New hotel service sale" description="Pick the guest, add the services sold and confirm the invoice.">
    @if (hasPermission('service.view', $slugs))
        <x-slot name="actions">
            <a href="{{ route('hotelservice.service-sales.index') }}" class="mm-button mm-button-secondary">
                <i class="fa fa-list-alt" aria-hidden="true"></i> Hotel Service Sale
            </a>
        </x-slot>
    @endif

    @include('partials._alert_message')

    <form method="POST" action="{{ route('hotelservice.service-sales.store') }}"
        class="form-horizontal" id="invForm">
        @csrf

        <!-- hidden fields -->
        <div class="form-group mm-hs-hidden">
            <input class="form-control" type="hidden" name="company_name" />
            <input type="hidden" name="invoice_no">
        </div>

        <x-mm.panel class="mm-hs-info-panel">
            <h2 class="mm-setup-title">Guest and invoice</h2>
            <!-- info -->
            <div class="mm-hs-info">
                {{-- W4.3b: the 5 top-section text inputs are converted to <x-mm.field>.
                     The id attributes are preserved (the JS hooks in the @section('js')
                     block below bind to #guest_name, #room_number, #booking_number,
                     #invoice_id, and #sale_date). The hidden hotel_guest_id /
                     hotel_room_id / hotel_booking_id fields are kept as raw
                     <input type="hidden"> (the <x-mm.field> component renders a
                     visible input, not a hidden one). --}}
                <x-mm.field label="Guest Name" id="guest_name" name="guest_name" placeholder="Guest Name" required />
                <input type="hidden" value="" name="hotel_guest_id" id="hotel_guest_id">

                {{-- Search By Room --}}
                <x-mm.field label="Room Number" id="room_number" name="room_number" placeholder="Room Number" />
                <input type="hidden" name="hotel_room_id" id="hotel_room_id">

                {{-- Search By Booking --}}
                <x-mm.field label="Booking Number" id="booking_number" name="booking_number" placeholder="Booking Number" />
                <input type="hidden" name="hotel_booking_id" id="hotel_booking_id" value="">

                {{-- Sale Invoice ID (readonly) --}}
                <x-mm.field label="Invoice ID" id="invoice_id" name="invoice_no" value="" placeholder="Invoice ID" :readonly="true" />

                {{-- Sale Date --}}
                <x-mm.field label="Date" id="sale_date" name="date" value="{{ date('Y-m-d') }}" class="date-picker" :autocomplete="'off'" />
            </div>
        </x-mm.panel>

        <!-- transition -->
        <x-mm.panel class="view_center_folwchart">
            <h2 class="mm-setup-title">Services</h2>
            <x-mm.table-scroll label="Services sold">
                <table class="table table-bordered table-hover" id="table_auto">
                    <thead>
                        <tr>
                            <th width="30%">Service Name</th>
                            <th width="15%">Service Price</th>
                            <th width="15%">Quantity</th>
                            <th width="15%">Total</th>
                            <th width="5%">
                                <button type="button" onclick="addItem()"
                                    class="btn btn-success btn-xs r-btnAdd" aria-label="Add service">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="container">

                    </tbody>
                </table>
            </x-mm.table-scroll>
        </x-mm.panel>

        <x-mm.panel class="mm-hs-totals">
            <div class="form-inlines">
                <div class="form-group aside_system">
                    <label class="control-label" for="subTotal">
                        Sub Totals:
                    </label>
                    <div class="input-group">
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
                    <label class="control-label" for="discount">
                        Discount:
                        <span id="service-discount"></span>
                    </label>
                    <input type="hidden" id="service-discount-val">
                    <div class="input-group">
                        <div class="input-group-addon currency">৳</div>
                        <input value="0" type="number" min="0" step="any"
                            class="form-control" name="discount"
                            id="discount" placeholder="Discount">
                    </div>
                </div>
                <div class="form-group aside_system">
                    <label class="control-label" for="payable_amount">
                        Total Amount:
                    </label>
                    <div class="input-group">
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
                    <label class="control-label" for="amountPaid">
                        Paid Amount:
                    </label>
                    <div class="input-group">
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
                    <label class="control-label" for="amountDue">Amount Due
                        :</label>
                    <div class="input-group">
                        <div class="input-group-addon currency">৳</div>
                        <input value="" class="form-control"
                            name="due_amount" id="amountDue"
                            placeholder="Amount Due" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" onclick="submitForm()"
                        class="mm-button mm-hs-confirm">Confirm</button>
                </div>
            </div>
        </x-mm.panel>
    </form>
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
