@extends('frontend.layouts.master')
@section('website_header') Guest Registration |@endsection
@push('custom_css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">
@endpush


@section('frontend-content')

    <section class="guest-registration">
        <div class="container">
            <div class="row registration-row">
                <div class="col-lg-6 col-md-6 col-sm-6">

                    <h4 class="reg-title">Guest Registration</h4>
                    <p class="reg-note">Sign Up For Booking</p>

                    <form action="{{ route('submit-guest-registration') }}" method="get" id="registrationForm">
                        @csrf

                        <input type="hidden" name="room_category" value="{{ $request->room_category }}">
                        <input type="hidden" name="room_id" value="{{ $request->room_id }}">
                        <input type="hidden" name="check_in" value="{{ $request->check_in }}">
                        <input type="hidden" name="check_out" value="{{ $request->check_out }}">

                        <!------------------ NAME ------------------>
                        <div class="input-field">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <input id="name" type="text" name="name" placeholder="&nbsp;" autocomplete="off" value="{{ old('name') }}"/>
                            <label for="name">Name</label>
                        </div>

                        <!------------------ EMAIL ------------------>
                        <div class="input-field">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <input id="email" type="email" name="email" placeholder="&nbsp;" autocomplete="off" value="{{ old('email') }}"/>
                            <label for="email">Email</label>
                        </div>

                        <!------------------ PHONE ------------------>
                        <div class="input-field">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <input id="phone_no" type="number" name="phone_no" placeholder="&nbsp;" autocomplete="off" value="{{ old('phone_no') }}"/>
                            <label for="phone_no">Phone</label>
                        </div>

                        <!------------------ NID/PASSPORT ------------------>
                        <div class="input-field">
                            <i class="fa fa-book" aria-hidden="true"></i>
                            <input id="nid_no" type="text" name="nid_no" placeholder="&nbsp;" autocomplete="off" value="{{ old('nid_no') }}"/>
                            <label for="nid_no">MyKad/Passport</label>
                        </div>

                        <!------------------ SPOUSE NAME ------------------>
                        <div class="input-field">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <input id="spouse_name" type="text" name="spouse_name" placeholder="&nbsp;" autocomplete="off" value="{{ old('spouse_name') }}"/>
                            <label for="spouse_name">Spouse Name</label>
                        </div>

                        <!----------------- ADDRESS ----------------->
                        <div class="control-group form-group">
                            <div class="controls">
                                <label class="contact-p1">Address:</label>
                                <textarea id="address" name="address" class="form-control address-textarea" cols="30" rows="5" placeholder="Enter your address..."></textarea>
                            </div>
                        </div>


                    </form>

                    <button type="button" class="btn submit-registration-btn" id="submitRegister">Next</button>

                </div>
            </div>
        </div>
    </section>

@endsection

@push('custom_js')
    <script>

        @if (session()->has('alreadyBookingError'))
            Swal.fire({
                type: 'error',
                title: '<h4>You already have a booking on this number</h4>',
                timer: 2000,
                showConfirmButton: false
            })
        @endif

        $(document).on('click', '#submitRegister', function() {

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

            $('#registrationForm').submit();

        })
    </script>
@endpush
