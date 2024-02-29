<div class="col-lg-5" style="margin-left: 80px">


    <!-- BOOKING DATE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Booking Date <span
                    style="color: red">*</span></span>
            <input type="text" class="form-control date-picker booking-date-picker" name="booking_date"
                value="{{ old('booking_date', today_from_system()) }}">
            <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
        </div>

    </div>




    <!-- GUEST NAME -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Guest Name <span
                    style="color: red">*</span></span>
            <select class="form-control chosen-select-100-percent bg-transparent"
                onchange="showCompany(this), editGuestInfo(this)" name="customer_id" id="customer_id"
                data-placeholder="Choose Guest">
                <option></option>
                @foreach ($guest as $id => $data)
                    <option value="{{ $data->id }}">{{ $data->name }} - {{ $data->phone_no }}</option>
                @endforeach
            </select>
            <span class="input-group-addon add_guest_info">
                <a href="#add_guest1" title="Add new guest" data-toggle="modal" role="button">
                    <i class="glyphicon glyphicon-user"></i>
                </a>
            </span>
            <span class="input-group-addon edit_guest_info" style="display: none">
                <a href="#edit_guest_info" title="Edit Guest" data-toggle="modal" role="button">
                    <i class="glyphicon glyphicon-edit"></i>
                </a>
            </span>
        </div>
    </div>



    <!-- COMPANY -->
    <div class="form-group mb-1 guest-company">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Company </span>
            <select class="form-control bg-transparent chosen-select-100-percent" data-placeholder="--Choose Company--"
                name="company_id" id="company_id">
                <option value=""></option>
                {{-- @dd($crmCompanies); --}}
                @foreach ($crmCompanies as $data)
                    <option value="{{ $data->id }}">{{ $data->org_name }}</option>
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
            <input class="form-control date-picker check-in-date" value="{{ old('check_in_date', date('Y-m-d')) }}"
                name="check_in_date" type="text" required>
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
        </div>
    </div>





    <!-- CHECK OUT DATE -->
    {{-- <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Check Out Date <span
                    style="color: red">*</span></span>
            <input class="form-control date-picker check_out check-out-date-picker"
                value="{{ old('check_out_date', $tomorrow) }}" name="check_out_date" type="text" autocomplete="off"
                required>
            <input class="form-control date-picker check_out" value="{{ old('check_out_date') }}" name="check_out_date" type="text" required autocomplete="off">
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
        </div>
    </div> --}}




    <!-- BOOKING PAX -->
    {{-- <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Booking Pax </span>
            <input type="text" class="form-control" name="booking_pax" value="{{ old('booking_pax') }}"
                placeholder="PAX">
        </div>
    </div> --}}


    <!-- Check In Now -->
    <div class="form-group mb-1">
        <label class="col-sm-3 col-xs-4 control-label" for="form-field-1-1" style="margin-left:-8px"> Booked Now
        </label>
        <div class="col-sm-4 col-xs-4">
            <label style="margin-top: 7px">
                <input name="status" class="ace ace-switch ace-switch-6" type="checkbox" value="1">
                <span class="lbl"></span>
            </label>
        </div>
        <style>
            .bulk-booking.ace.input-lg+.lbl::before {
                left: 100px !important;
                top: 6px !important;
            }
        </style>
    </div>

</div>







<div class="col-lg-5" style="margin-left: 30px; margin-right: 60px">


    <!-- REFERENCE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Reference Name </span>
            <input type="text" class="form-control" id="referenceName" name="reference"
                value="{{ old('reference') }}" placeholder="Reference Name">
        </div>
    </div>


    <!-- TYPE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Booking Platform </span>
            <select class="form-control chosen-select-100-percent bg-transparent" name="platform_id"
                data-placeholder="--Choose Booking Platform--" style="width: 100%">
                <option></option>
                @foreach ($booking_purpose->where('rule', 2) as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Emergency Contact Name -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Emrg.Cont Name</span>
            <input type="text" class="form-control" name="emergency_cont_name"
                value="{{ old('emergency_cont_name') }}" placeholder="Emergency Contact Name">
        </div>
    </div>

    <!-- Emergency Contact Phone -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Emrg.Cont Phone</span>
            <input type="text" class="form-control" id="emergency_cont_phone" name="emergency_cont_phone"
                value="{{ old('emergency_cont_phone') }}" placeholder="Emergency Contact Phone">
        </div>
    </div>


    <!-- MEMBERS -->
    {{-- <div class="form-group mb-1">
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
    </div> --}}



    {{-- <div class="form-group mb-1">
        <div class="input-group width-100">
            @if (setting('bulk_booking'))
                <span class="border-none input-group-addon width-27" style="text-align: left; margin-right: 5px;"> Bulk Booking </span>
                <label>
                    <input name="bulk_booking" value="1" type="checkbox" class="ace input-lg bulk-booking">
                    <span class="lbl">  </span>
                </label>
            @endif
        </div>
    </div> --}}

</div>
