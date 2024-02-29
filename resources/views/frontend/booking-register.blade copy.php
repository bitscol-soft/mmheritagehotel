@extends('frontend.layouts.master')
@section('website_header') Booking Registration |@endsection
@push('custom_css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">
@endpush


@section('frontend-content')

    <section class="guest-registration">
        <div class="container">

            <h4 class="reg-title">Booking Registration</h4>
            <p class="reg-note">Fill Up For Booking</p>

            <form action="{{ route('submit-booking-registration') }}" method="post" id="registrationBookForm">
                @csrf
                <div class="row registration-row">

                    <div class="col-lg-6 col-md-6 col-sm-6">

                        <!-- ROOM --->
                        <input type="hidden" name="room_category" value="{{ $request->room_category }}">
                        <input type="hidden" name="room_id" value="{{ $request->room_id }}">
                        <input type="hidden" name="check_in" value="{{ $request->check_in }}">
                        <input type="hidden" name="check_out" value="{{ $request->check_out }}">

                        <!-- GUEST --->
                        <input type="hidden" name="name" value="{{ $request->name }}">
                        <input type="hidden" name="email" value="{{ $request->email }}">
                        <input type="hidden" name="phone_no" value="{{ $request->phone_no }}">
                        <input type="hidden" name="address" value="{{ $request->address }}">
                        <input type="hidden" name="nid_no" value="{{ $request->nid_no }}">
                        <input type="hidden" name="spouse_name" value="{{ $request->spouse_name }}">


                        <!------------------ NAME ------------------>
                        <div class="input-field">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <input id="name" type="text" name="name" placeholder="&nbsp;" autocomplete="off" />
                            <label for="name">Pickup</label>
                        </div>

                        <!------------------ EMAIL ------------------>
                        <div class="input-field">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <input id="email" type="email" name="email" placeholder="&nbsp;" autocomplete="off" />
                            <label for="email">Drop</label>
                        </div>

                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <input id="phone_no" type="number" name="phone_no" placeholder="&nbsp;" autocomplete="off" />
                            <label for="phone_no">Pickup Flight No</label>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <input id="phone_no" type="number" name="phone_no" placeholder="&nbsp;" autocomplete="off" />
                            <label for="phone_no">Drop Flight No</label>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <input id="phone_no" type="number" name="phone_no" placeholder="&nbsp;" autocomplete="off" />
                            <label for="phone_no">Reference Name</label>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <input id="phone_no" type="number" name="phone_no" placeholder="&nbsp;" autocomplete="off" />
                            <label for="phone_no">Purpose</label>
                        </div>

                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6">

                        <!------------------ Pickup ------------------>
                        <div class="input-field">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <input id="pickup" type="text" name="pickup" placeholder="&nbsp;" autocomplete="off" />
                            <label for="pickup">Pickup</label>
                        </div>

                        <!------------------ Drop ------------------>
                        <div class="input-field">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <input id="drop" type="text" name="drop" placeholder="&nbsp;" autocomplete="off" />
                            <label for="drop">Drop</label>
                        </div>

                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <input id="pickup_flight" type="text" name="pickup_flight" placeholder="&nbsp;" autocomplete="off" />
                            <label for="pickup_flight">Pickup Flight No</label>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <input id="drop_flight" type="text" name="drop_flight" placeholder="&nbsp;" autocomplete="off" />
                            <label for="drop_flight">Drop Flight No</label>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <input id="reference" type="text" name="reference" placeholder="&nbsp;" autocomplete="off" />
                            <label for="reference">Reference Name</label>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <select name="book_type" class="form-control">
                                <option value="">Booking Type</option>
                                <option value="1">FIT</option>
                                <option value="2">Corporate</option>
                                <option value="3">Orders</option>
                            </select>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <select name="purpose" class="form-control">
                                <option value="">Booking Purpose</option>
                                <option value="1">Travel</option>
                                <option value="2">Official</option>
                            </select>
                        </div>


                    </div>

                </div>
            </form>

            <button type="button" class="btn submit-registration-btn" id="submitBookRegister">Book Now</button>

        </div>
    </section>

@endsection

@push('custom_js')
    <script>

        $(document).on('click', '#submitBookRegister', function() {

            let name  = $('input[name=name]').val();
            let phone = $('input[name=phone_no]').val();

            if (name == '') {
                toastr.error('Please enter Customer Name!');
                return;
            }
            if (phone == '') {
                toastr.error('Please enter Phone Number!');
                return;
            }

            $('#registrationBookForm').submit();

        })
    </script>
@endpush
