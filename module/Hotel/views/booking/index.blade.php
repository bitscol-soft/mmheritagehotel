@extends('layouts.master')
@section('title', ' Booking List')

@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />

    <style>
        ul li {
            list-style: none;
        }

        .total-room-amount input[readonly] {
            background-color: white !important;
        }

        .total-night-count input[readonly] {
            background-color: white !important;
        }

        .apprearence-none {
            appearance: none;
        }

        table thead th {
            background-color: #4d8cb3;
            color: #fff;
        }

        .transactions p {
            margin-bottom: 0 !important;
        }

        .action-button-td {
            padding: 5px 3px !important;
        }

        .action-button {
            width: 105px;
            margin: 0 auto;
        }

        .action-button a,
        .action-button button {
            padding: 0px !important;
            border-width: 2px !important;
            margin-bottom: 3px;
            width: 22px;
            height: 22px;
            line-height: 20px;
            text-align: center;
            align-items: center;
        }

        .action-button a i,
        .action-button button i {
            font-size: 12px;
        }

        .filter-booking .guest-info {}

        .middle-col {
            position: relative;
        }

        .middle-col::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -8px;
            height: 34px;
            width: 1.7px;
            background: #ccc;
        }

        .middle-col::before {
            content: '';
            position: absolute;
            top: -5px;
            right: -7px;
            height: 34px;
            width: 1.7px;
            background: #ccc;
        }
    </style>


    <!----- INCLUDING EXTRA CHARGE MODAL CSS ----->
    @include('booking._css.extra-charge-modal-css')


@stop

@section('content')

    <div class="page-header">
        <a class="btn-xs btn-info no-border" href="{{ route('booking.create') }}" style="float: right; margin: 0 2px;">
            <i class="fa fa-plus-circle"></i> New Booking </a>
        <h1>
            <i class="fa fa-info-circle"></i> Booking List
            <span class="badge badge-info">Total: {{ $booking->total() }}</span>
        </h1>
    </div>


    <!----------------- INCLUDING SEARCH FILTER ----------------->
    @include('booking._inc._filter')


    <hr style="margin: 5px 0 10px 0 !important;">


    <!----------------- INCLUDING CUTOMER INFO FOR SEARCH ----------------->
    @if (request('reference') != null)
        @include('booking._inc._filter_guest_info')
    @endif


    {{-- <x-alert-message /> --}}

    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <!----------- INCLUDING PAY MODAL ---------->
                <x-multi-account-pay-modal-booking />

                <!----------- INCLUDING BOOKING TABLE ---------->
                @include('booking._inc._booking-table')


                <form action="" id="cancelBookingForm" method="POST">
                    @csrf

                </form>

                <x-paginate :data="$booking" />

                <x-export-button pdf="1" excel="1" />

            </div>
        </div>
    </div>


    <!-------- INCLUDING BOOKING DETAILS CHANGE IMAGE & CHECK IN MODAL -------->
    @include('booking/_modal/booking_details_new')
    @include('booking/_modal/change_guest_image_new')
    @include('booking/_modal/booking_check_in_modal')
    @include('booking/_modal/member-detail-show-modal')

    <!--------------- INCLUDING OLD MODAL --------------->
    {{-- @foreach ($booking as $key => $book)
        @include('partials.modal.booking_details')
        @include('partials.modal.change_guest_image')
        @include('partials.modal.booking_check_in_modal')
    @endforeach --}}




    <!--------------- INCLUDING EXTRA CHARGE MODAL --------------->
    @include('booking._modal.extra-charge-modal')

@endsection

@section('js')


    <script>
        $('.adjustBtn').click(function() {
            toastr.warning('You are not eligible to migrate anymore!')
        });



        $('.member_details_close').click(function() {
            $('#member-detail-show-modal').modal('hide');
        });


        // Print Member List Of a Booking
        function memberDetails(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            $('#member-detail-show-modal').modal('hide');
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print(printContents);
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>



    <script>
        let fromBookingStore = `{{ session('fromBookingStore') }}`;

        if (fromBookingStore == 'Yes') {

            let bookingId = `{{ session('bookingId') }}`;
            let baseUrl = `{{ url('/') }}`;

            let url = baseUrl + '/hotel/booking/invoice-v2/' + bookingId

            window.open(url, '_blank');

        }
    </script>



    <script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>



    <!--------------- INCLUDING EXTRA CHARGE SCRIPT -------------->
    @include('booking._script.extra-charge-modal-script')



    <script>
        function loadMemberDetail(booking_id) {
            $.get(`{{ route('get-booking-member-details') }}`, {

                booking_id: booking_id

            }, function(data) {
                $('.member-detail-body').html(data);
            });
        }



        function showGuestInfo(object) {
            $(object).closest('tr').find('.guest-details-tr').toggle()
        };



        $('.show-details-btn').on('click', function(e) {
            e.preventDefault();
            $(this).closest('tr').next().toggleClass('open');
            $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
        });
    </script>



    <script>
        $(document).on('change', '.category', function() {
            var category_id = $(this).val();
            var get_url = $('#base_url').val();
            $.ajax({
                type: 'get',
                url: '/hotel/room_by_search_category/' + category_id,
                async: true,
                beforeSend: function() {
                    $("body").css("cursor", "progress");
                },
                success: function(data) {
                    $('.room_number').html(data).trigger('chosen:updated');
                },
                complete: function(data) {
                    $("body").css("cursor", "default");
                }
            });
        });



        function cancelBooking(url) {
            $('#cancelBookingForm').attr('action', url).submit();
        }



        function dueCollection(url, e, amount = 0) {
            let _this = $(e);

            let html =
                `@foreach ($account_types as $account)<label class="choose-payment-method" style="margin: 5px 8px 5px 0px !important; cursor:pointer;"><input type="radio" id="payment_type" name="payment_type" value="{{ $account->id }}"><span style="margin-left: 2px">{{ $account->name }}</span></label>@endforeach`
            html += `<input type="hidden" id="getPaymentMethodID" value="">`;
            html += `<div style='margin: 10px 0'></div>`;
            html +=
                `<div class="input-group"><span class="input-group-addon">Due Amount<span class="currency-sign"></span></span><input type="number" class="form-control" style="font-size:22px" id="swal-amount" placeholder="Enter Amount" /></div>`;
            html += `<div class="checkbox">
                        <label class="block">
                            <input id="full-due-amount" type="checkbox" class="ace input-lg">
                            <span class="lbl bigger-120"> Full Amount</span>
                        </label>
                    </div>`;

            let confirmButtonText = 'Save';

            $('#getPaymentMethodID').val($('input[name=payment_type]:checked').val());

            Swal.fire({
                title: 'Due Collection ?',
                html: html,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                width: 400,
            }).then((result) => {

                if (result.value) {

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'POST',
                            _token: '{{ csrf_token() }}',
                            amount: $('#swal-amount').val(),
                            payment_type: $('#getPaymentMethodID').val(),
                            from_booking_due_collection: 1,
                        },
                        success: function(response) {
                            toastr.success('Due Collected Succesfully!');
                            location.reload();
                        }
                    });
                }
            })

            $('.currency-sign').text(`{{ currencySign() }}`)

            $('#full-due-amount').on('click', function() {
                if ($(this).is(':checked')) {
                    $('#swal-amount').val(Number(amount).toFixed(2))
                } else {
                    $('#swal-amount').val(0)
                }
            })

            $('input[name=payment_type]').on('click', function() {
                $('#getPaymentMethodID').val($(this).val());
            })
        }


        function barDueCollection(url, e, amount = 0) {
            let _this = $(e);

            let html =
                `@foreach ($account_types as $account)<label class="choose-payment-method" style="margin: 5px 8px 5px 0px !important; cursor:pointer;"><input type="radio" id="payment_type" name="payment_type" value="{{ $account->id }}"><span style="margin-left: 2px">{{ $account->name }}</span></label>@endforeach`
            html += `<input type="hidden" id="getPaymentMethodID" value="">`;
            html += `<div style='margin: 10px 0'></div>`;
            html +=
                `<div class="input-group"><span class="input-group-addon">Due Amount<span class="currency-sign"></span></span><input type="number" class="form-control" style="font-size:22px" id="swal-amount"
                    placeholder="Enter Amount" readonly/></div>`;
            html += `<div class="checkbox">
                        <label class="block">
                            <input id="full-due-amount" type="checkbox" class="ace input-lg">
                            <span class="lbl bigger-120"> Full Amount</span>
                        </label>
                    </div>`;

            let confirmButtonText = 'Save';

            $('#getPaymentMethodID').val($('input[name=payment_type]:checked').val());

            Swal.fire({
                title: 'Bar Due Collection ?',
                html: html,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                width: 400,
            }).then((result) => {

                if (result.value) {

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'POST',
                            _token: '{{ csrf_token() }}',
                            amount: $('#swal-amount').val(),
                            payment_type: $('#getPaymentMethodID').val(),
                            from_bar_due_collection: 1,
                        },
                        success: function(response) {
                            toastr.success('Bar Due Collected Succesfully!');
                            location.reload();
                        }
                    });
                }
            })

            $('.currency-sign').text(`{{ currencySign() }}`)

            $('#full-due-amount').on('click', function() {
                if ($(this).is(':checked')) {
                    $('#swal-amount').val(Number(amount).toFixed(2))
                } else {
                    $('#swal-amount').val(0)
                }
            })

            $('input[name=payment_type]').on('click', function() {
                $('#getPaymentMethodID').val($(this).val());
            })
        }
    </script>


    <script>
        $('.web-cam-close').on('click', function() {
            $('.webcam-modal').modal('hide');
        })
    </script>
    {{-- <script>
        console.log(`{{ currencySign() }}`);
    </script> --}}


    <!--------------- INCLUDING MODAL SCRIPT --------------->
    @include('booking._script.view-details-script')
    @include('booking._script.view-change-guest-image-script')
    @include('booking._script.check-in-script')

    <!--------------- INCLUDING EXTEND DATE SCRIPT --------------->

    @include('booking._script.extend-date-script')
    @include('booking._script.change-guest-image-script')
    @include('booking._script.mult-accout-pay-script')



@stop
