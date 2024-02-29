@extends('frontend.layouts.master')
@section('website_header') Booking Registration |@endsection
@push('custom_css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">
    <link href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" rel="stylesheet">
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

                        <!------------------ Pickup ------------------>
                        <div class="input-field">
                            <i class="fas fa-truck-pickup"></i>
                            <input id="pickup" type="text" name="pickup" placeholder="&nbsp;" autocomplete="off" />
                            <label for="pickup">Pickup</label>
                        </div>

                        <!------------------ Drop ------------------>
                        <div class="input-field">
                            <i class="fas fa-map-marker-alt"></i>
                            <input id="drop" type="text" name="drop" placeholder="&nbsp;" autocomplete="off" />
                            <label for="drop">Drop</label>
                        </div>

                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fas fa-plane-arrival"></i>
                            <input id="pickup_flight" type="text" name="pickup_flight" placeholder="&nbsp;" autocomplete="off" />
                            <label for="pickup_flight">Pickup Flight No</label>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fas fa-plane-departure"></i>
                            <input id="drop_flight" type="text" name="drop_flight" placeholder="&nbsp;" autocomplete="off" />
                            <label for="drop_flight">Drop Flight No</label>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fas fa-user-plus"></i>
                            <input id="reference" type="text" name="reference" placeholder="&nbsp;" autocomplete="off" />
                            <label for="reference">Reference Name</label>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fas fa-hotel"></i>
                            <select name="book_type" class="form-control">
                                <option value="">Booking Type</option>
                                <option value="1">FIT</option>
                                <option value="2">Corporate</option>
                                <option value="3">Orders</option>
                            </select>
                        </div>
                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fas fa-bullseye-arrow"></i>
                            <select name="purpose" class="form-control">
                                <option value="">Booking Purpose</option>
                                <option value="1">Travel</option>
                                <option value="2">Official</option>
                            </select>
                        </div>


                    </div>

                </div>
            </form>

            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <button type="button" class="btn submit-registration-btn" id="submitBookRegister">Book Now</button>
                </div>
            </div>

        </div>
    </section>

@endsection

@push('custom_js')
    <script>

        $(document).on('click', '#submitBookRegister', function() {

            $('#registrationBookForm').submit();

        })
    </script>
@endpush
