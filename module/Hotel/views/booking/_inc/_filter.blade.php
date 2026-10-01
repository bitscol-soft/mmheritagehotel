<form action="" method="get" class="booking-filter-panel">
    <div class="row">

        @if(url()->current() == route('booking.referred-booking'))
        <div class="col-md-1"></div>
        @endif

        <!----------- GUEST NAME ----------->
        <div class="col-md-2">
            <div class="input-group" style="width:100%">
                <select name="customer_id" class="form-control chosen-select" id="customer_id"
                    data-selected="{{ request('customer_id') }}" data-placeholder="--Choose Guest--">
                    <option></option>
                    @foreach ($guest as $id => $data)
                        <option value="{{ $data->id }}">
                            {{ $data->name }} - {{ $data->phone_no }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>



        <!----------- ROOM CATEGORY ----------->
        @if(url()->current() != route('booking.referred-booking'))
        <div class="col-md-2">
            <div class="input-group" style="width:100%">
                <select name="category_id" class="form-control chosen-select category" id="category"
                    data-selected="{{ request('category_id') }}" data-placeholder="--Choose category--">
                    <option></option>
                    @foreach ($category as $id => $name)
                        <option value="{{ $id }}">
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif



        <!----------- ROOM NUMBER ----------->
        @if(url()->current() != route('booking.referred-booking'))
        <div class="col-md-2">
            <div class="input-group" style="width:100%">
                <select name="room_id" class="form-control chosen-select room_number" id="room_id"
                    data-selected="{{ request('room_id') }}" data-placeholder="--Choose room--">
                    <option></option>

                </select>
            </div>
        </div>
        @endif



        <!----------- BOOKING DATE ----------->
        @if(url()->current() != route('booking.referred-booking'))
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control date-picker input-sm" value="{{ request('booking_from_date') }}"
                    name="booking_from_date" data-date-format="dd-mm-yyyy" placeholder="Booking From" autocomplete="off">
                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>
                <input type="text" class="form-control date-picker input-sm" value="{{ request('booking_to_date') }}"
                    name="booking_to_date" data-date-format="dd-mm-yyyy" placeholder="Booking To" autocomplete="off">
            </div>
        </div>
        @endif



        <!----------- CHECK IN DATE ------------>
        <div class="col-md-2">
            <div class="input-group">
                <input class="form-control date-picker input-sm" value="{{ request('check_in_date') }}"
                    name="check_in_date" type="text" data-date-format="dd-mm-yyyy" placeholder="Check IN Date"
                    autocomplete="off">
                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>
            </div>
        </div>



        <!----------- CHECK OUT DATE ----------->
        <div class="col-md-2" style="margin-top:{{ url()->current() != route('booking.referred-booking') ? '10px' : '0px' }};">
            <div class="input-group">
                <input class="form-control date-picker input-sm" value="{{ request('check_out_date') }}"
                    name="check_out_date" type="text" data-date-format="dd-mm-yyyy" placeholder="Check Out Date"
                    autocomplete="off">
                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>
            </div>
        </div>



        <!----------- BOOKING NUMBER ----------->
        <div class="col-md-2" style="margin-top:{{ url()->current() != route('booking.referred-booking') ? '10px' : '0px' }};">
            <div class="input-group">
                <input class="form-control input-sm" value="{{ request('booking_number') }}" name="booking_number"
                    type="text" placeholder="Booking ID / Number" autocomplete="off">
                <span class="input-group-addon">
                    <i class="fa fa-hashtag bigger-110"></i>
                </span>
            </div>
        </div>



        <!-------------- REFERENCE ------------->
        @if(url()->current() == route('booking.referred-booking'))
            <div class="col-md-2" style="margin-top:{{ url()->current() != route('booking.referred-booking') ? '10px' : '0px' }};">
                <div class="input-group">
                    <input class="form-control input-sm" value="{{ request('reference') }}" name="reference"
                        type="text" placeholder="Booking Reference" autocomplete="off">
                    <span class="input-group-addon">
                        <i class="fa fa-link bigger-110"></i>
                    </span>
                </div>
            </div>
        @endif



        <!--------------- STATUS --------------->
        @if(url()->current() != route('booking.referred-booking'))
            <div class="col-md-2" style="margin-top:10px;">
                <div class="input-group">
                    <span class="input-group-addon">
                        Status
                    </span>
                    <select name="status" class="form-control chosen-select-100-percent" data-placeholder="--Status--" data-selected="{{ request('status') }}">
                        <option value=""></option>
                        @php
                            $arr = [
                                ['name' => 'Reservation',   'code' => 0],
                                ['name' => 'Check In',      'code' => 1],
                                ['name' => 'Check Out',     'code' => 3],
                                ['name' => 'Cancelled',     'code' => 4],
                            ];
                        @endphp
                        @foreach ($arr as $item)
                            <option value="{{ $item['code'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        @if(url()->current() == route('booking.referred-booking'))
        <div class="col-md-1"></div>
        @endif

        @if(url()->current() == route('booking.referred-booking'))
        <div class="col-md-1"></div>
        @endif

        <!----------- BOOKING FROM ----------->
        @if(url()->current() == route('booking.referred-booking'))
        <div class="col-md-2">
            <div class="input-group">
                <input type="text" class="form-control date-picker input-sm" value="{{ request('booking_from') }}"
                    name="booking_from" data-date-format="dd-mm-yyyy" placeholder="From" autocomplete="off">
                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>
            </div>
        </div>
        @endif


        <!----------- BOOKING TO ----------->
        @if(url()->current() == route('booking.referred-booking'))
        <div class="col-md-2">
            <div class="input-group">
                <input type="text" class="form-control date-picker input-sm" value="{{ request('booking_to') }}"
                    name="booking_to" data-date-format="dd-mm-yyyy" placeholder="To" autocomplete="off">
                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>
            </div>
        </div>
        @endif

        @if(url()->current() == route('booking.referred-booking'))
        <div class="col-md-4"></div>
        @endif

        <!----------- ACTION BUTTONS ----------->
        <div class="col-md-2" style="height: 40px; {{ url()->current() != route('booking.referred-booking') ? 'margin-top:10px' : 'margin-top:0px; text-align: end;' }};">
            <div class="btn-group">
                <button class="btn btn-sm btn-success">
                    <i class="fa fa-search"></i> Search
                </button>
                <a href="{{ request()->url() }}" class="btn btn-sm btn-default">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
        </div>


    </div>
</form>
