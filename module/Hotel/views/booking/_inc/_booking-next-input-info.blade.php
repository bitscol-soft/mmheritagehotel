

<div class="col-lg-5" style="margin-left: 80px">


    <!-- BOOKING DATE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Booking Date <span style="color: red">*</span></span>
            <input class="form-control date-picker"
                   value="{{ old('booking_date', today_from_system()) }}"
                   {{-- value="{{ old('booking_date', \Carbon\Carbon::parse($date[0])->format('Y-m-d')) }}" --}}
                   name="booking_date" type="text" data-date-format="dd-mm-yyyy">
            <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span>
        </div>

    </div>



    <!-- GUEST NAME -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Guest Name <span style="color: red">*</span></span>
            <select name="customer_id" id="customer_id" class="form-control chosen-select-100-percent" onchange="showCompany(this), editGuestInfo(this)" data-selected="{{ old('customer_id') }}" data-placeholder="Choose Guest" required>
                <option></option>
                @foreach ($guest as $id => $data)
                    <option value="{{ $data->id }}">
                        {{ $data->name }} -> {{ $data->phone_no }}
                    </option>
                @endforeach
            </select>
            <span class="input-group-addon add_guest_info">
                <a href="#add_guest1" title="Add new guest" data-toggle="modal"
                    role="button">
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
            <select class="form-control bg-transparent chosen-select-100-percent" data-placeholder="--Choose Company--" name="company_id" id="company_id">
                <option value=""></option>
                @foreach ($crmCompanies as $id => $data)
                    <option value="{{ $data->id }}">{{ $data->org_name }}</option>
                @endforeach
            </select>
        </div>
    </div>



    <!-- BOOKING PURPOSE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Purpose </span>
            <select class="form-control chosen-select-100-percent" name="purpose" id="purpose" style="width: 100%" data-placeholder="--Choose Purpose--">
                <option></option>
                @foreach ($booking_purpose->where('rule', 1) as $item)
                <option value="{{ $item->id }}" >{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>




    <!-- CHECK IN DATE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Check In Date <span style="color: red">*</span></span>
            <input class="form-control" value="{{ old('check_in_date', $date[0] ?? '') }}" name="check_in_date" type="text" readonly>
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
        </div>
    </div>





    <!-- CHECK OUT DATE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Check Out Date <span style="color: red">*</span></span>
            <input class="form-control" value="{{ old('check_out_date', trim($date[1] ?? '')) }}" name="check_out_date" type="text" readonly>
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
        </div>
    </div>

    <!-- Emergency Contact Name -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Emrg.Cont Name</span>
            <input type="text" class="form-control" name="emergency_cont_name" value="{{ old('emergency_cont_name') }}" placeholder="Emergency Contact Name">
        </div>
    </div>



    <div class="form-group mb-1 check-in-note" style="display: none">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Check In Note </span>
            <textarea name="check_in_note" class="form-control input-sm">{{ old('check_in_note') }}</textarea>
        </div>
    </div>





    <!-- BOOKING PAX -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Booking Pax </span>
            <input type="text" class="form-control" name="booking_pax" value="{{ old('booking_pax') }}" placeholder="PAX">
        </div>
    </div>

    <!-- CHILD PAX -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Child In Pax </span>
            <input type="text" class="form-control" name="child_pax" value="{{ old('child_pax') }}"
                placeholder="CHILD_PAX">
        </div>
    </div>
   <!-- Check In Now -->
   @if (request('submit') == 'book')
   <div class="form-group mb-1">
       <label class="col-sm-3 col-xs-4 control-label" for="form-field-1-1" style="margin-left: -8px"> Check In Now </label>
       <div class="col-sm-8 col-xs-8">
           <label style="margin-top: 7px">
               <input name="status" class="ace ace-switch ace-switch-6" value="1" type="checkbox">
               <span class="lbl"></span>
           </label>
       </div>
   </div>
@endif

<input type="hidden" name="type" value="{{ request('submit') }}">

</div>





<div class="col-lg-5" style="margin-left: 30px; margin-right: 60px">

    <!-- PICKUP -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Pickup </span>
            <input type="text" class="form-control" name="pickup" value="{{ old('pickup') }}" placeholder="Pickup">
        </div>

    </div>



    <!-- DROP -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Drop </span>
            <input type="text" class="form-control" name="drop" value="{{ old('drop') }}" placeholder="Drop">
        </div>

    </div>


    <!-- PICKUP FLIGHT No -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Pickup Flight No </span>
            <input type="text" class="form-control" name="pickup_flight" value="{{ old('pickup_flight') }}" placeholder="Pickup Flight No">
        </div>
    </div>




    <!-- DROP FLIGHT -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Drop Flight No </span>
            <input type="text" class="form-control" name="drop_flight" value="{{ old('drop_flight') }}" placeholder="Drop Flight No">
        </div>
    </div>



    <!-- REFERENCE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left"> Reference Name </span>
            <input type="text" class="form-control" name="reference" value="{{ old('reference') }}" placeholder="Reference Name">
        </div>
    </div>


    <!-- TYPE -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Booking Type </span>
            <select class="form-control chosen-select-100-percent bg-transparent" name="book_type" data-placeholder="--Choose Booking Type--" style="width: 100%">
                <option></option>
                @foreach ($booking_purpose->where('rule', 2) as $item)
                <option value="{{ $item->id }}" >{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Emergency Contact Phone -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Emrg.Cont Phone</span>
            <input type="text" class="form-control" id="emergency_cont_phone" name="emergency_cont_phone" value="{{ old('emergency_cont_phone') }}" placeholder="Emergency Contact Phone">
        </div>
    </div>

    <!-- ADULT PAX -->
    <div class="form-group mb-1">
        <div class="input-group width-100">
            <span class="border-none input-group-addon width-27" style="text-align: left">Adult In Pax </span>
            <input type="text" class="form-control" name="adult_pax" value="{{ old('adult_pax') }}"
                placeholder="ADULT_PAX">
        </div>
    </div>

    <!-- MEMBERS -->
    <div class="form-group mb-1">
        <div class="input-group width-100" style="">
            <span class="input-group-addon border-none width-27" style="text-align: left; margin-right: 5px;"> Members </span>
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
