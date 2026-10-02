<div class="col-lg-5" style="margin-left: 80px">


    <!-- BOOKING DATE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Booking Date <span
                    style="color: red">*</span></span>
            <input class="form-control booking-date-picker" value="{{ $booking->booking_date }}" name="booking_date"
                type="text" readonly>
            <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
        </div>
    </div>



    <!-- GUEST NAME -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Guest Name <span
                    style="color: red">*</span></span>
            <select name="customer_id" class="form-control select select2" onchange="showCompany(this)" id="customer_id"
                data-selected="{{ $booking->customer_id }}" data-placeholder="--Choose Customer--" readonly>
                <option value="{{ $booking->customer_id }}">{{ optional($booking->customer)->name }}</option>
                {{-- @foreach ($guests as $guest)
                    <option value="{{ $guest->id }}">{{ $guest->name }}</option>
                @endforeach --}}
            </select>
            <span class="input-group-addon">
                <a href="#editHotelGuestModal" title="Edit guest" onclick="showCustomerModal()" data-toggle="modal"
                    role="button">
                    <i class="glyphicon glyphicon-user"></i>
                </a>
            </span>
        </div>
    </div>



    <!-- COMPANY -->
    <div class="form-group mb-1 guest-company">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Company </span>
            <select class="form-control bg-transparent chosen-select-100-percent" name="company_id" id="company_id"
                data-placeholder="--Choose Company--">
                <option value=""></option>
                @foreach ($crmCompanies as $id => $data)
                    <option value="{{ $data->id }}"
                        {{ $data->id == optional($booking->customer)->company_id ? 'selected' : '' }}>
                        {{ $data->org_name }}</option>
                @endforeach
            </select>
        </div>
    </div>



    <!-- BOOKING PURPOSE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Purpose </span>
            <select class="form-control chosen-select-100-percent bg-transparent" name="purpose_id" id="purpose"
                data-placeholder="--Choose Purpose--" style="width: 100%">
                <option></option>
                @foreach ($booking_purpose->where('rule', 1) as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>





    <!-- CHECK IN DATE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Check In Date <span
                    style="color: red">*</span></span>
            <input class="form-control date-picker check-in-date" value="{{ old('check_in_date', $booking->check_in_date) }}"
                data-business-date="{{ today_from_system() }}" data-allow-past="1" autocomplete="off"
                name="check_in_date" type="text" required>
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
        </div>
    </div>






    <!-- CHECK OUT DATE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Check Out Date <span
                    style="color: red">*</span></span>
            <input class="form-control checkOut check-out-date-picker" value="{{ $booking->check_out_date }}"
                name="check_out" type="text" data-date-format="yyyy-mm-dd" autocomplete="off">
            <input type="hidden" class="expectedCheckoutDate"
                value="{{ date('Y-m-d', strtotime('+1 day', strtotime($booking->check_out_date))) }}">
            <input type="hidden" class="previousCheckoutDate" value="{{ $booking->check_out_date }}">
            <input type="hidden" class="isRoomAvailable" value="0">
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
        </div>
        @error('check_out_date')
            <span class="text-danger">
                {{ $message }}
            </span>
        @enderror
    </div>


    <!-- BOOKING PAX -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Booking Pax </span>
            <input type="text" class="form-control" name="booking_pax" value="{{ $booking->booking_pax }}"
                placeholder="PAX">
        </div>
    </div>

    <!-- Emergency Contact Name -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Emrg.Cont Name</span>
            <input type="text" class="form-control" name="emergency_cont_name"
                value="{{ $booking->emergency_cont_name }}" placeholder="Emergency Contact Name">
        </div>
    </div>


</div>





<div class="col-lg-5" style="margin-left: 30px; margin-right: 60px">

    <!-- PICKUP -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Pickup </span>
            <input type="text" class="form-control" name="pickup" value="{{ $booking->pickup }}"
                placeholder="Pickup Address">
        </div>

        @error('pickup')
            <span class="text-danger">
                {{ $message }}
            </span>
        @enderror
    </div>



    <!-- DROP -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Drop </span>
            <input type="text" class="form-control" name="drop" value="{{ $booking->drop }}"
                placeholder="Drop Address">
        </div>

        @error('drop')
            <span class="text-danger">
                {{ $message }}
            </span>
        @enderror
    </div>


    <!-- PICKUP FLIGHT No -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Pickup Flight No </span>
            <input type="text" class="form-control" name="pickup_flight" value="{{ $booking->pickup_flight }}"
                placeholder="Pickup Flight No">
        </div>

        @error('pickup_flight')
            <span class="text-danger">
                {{ $message }}
            </span>
        @enderror
    </div>




    <!-- DROP FLIGHT -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Drop Flight No </span>
            <input type="text" class="form-control" name="drop_flight" value="{{ $booking->drop_flight }}"
                placeholder="Drop Flight No">
        </div>

        @error('drop_flight')
            <span class="text-danger">
                {{ $message }}
            </span>
        @enderror
    </div>



    <!-- REFERENCE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Reference Name </span>
            <input type="text" class="form-control" name="reference" value="{{ $booking->reference }}"
                placeholder="Reference Name">
        </div>

        @error('reference')
            <span class="text-danger">
                {{ $message }}
            </span>
        @enderror
    </div>


    <!-- BOOKING TYPE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Booking Platform </span>
            {{-- <select class="form-control chosen-select-100-percent" name="book_type" data-selected="{{ $booking->type }}">
                <option></option>
                <option value="1">FIT</option>
                <option value="2">Corporate</option>
                <option value="3">Orders</option>
            </select> --}}
            <select class="form-control chosen-select-100-percent" name="platform_id">
                <option></option>
                @foreach ($booking_purpose->where('rule', 2) as $item)
                    <option value="{{ $item->id }}" {{ $booking->platform_id == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}</option>
                @endforeach
            </select>

            @error('type')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>

    <!-- Emergency Contact Phone -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Emrg.Cont Phone</span>
            <input type="text" class="form-control" id="emergency_cont_phone" name="emergency_cont_phone"
                value="{{ $booking->emergency_cont_phone }}" placeholder="Emergency Contact Phone">
        </div>
    </div>

    <!-- MEMBERS -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left; margin-right: 5px;"> Members
            </span>
            <strong>
                <a href="#member-detail-modal" data-toggle="modal" role="button"
                    style="line-height: 35px; margin-right: 5px;">
                    Click
                </a>
            </strong>
            To Add Your member details
        </div>
    </div>
</div>
