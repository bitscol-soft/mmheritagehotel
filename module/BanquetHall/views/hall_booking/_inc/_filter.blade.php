<form action="" method="get" class="booking-filter-panel mm-booking-filter">
    <div class="tw-grid tw-gap-4 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-items-end">

        @if(url()->current() == route('booking.referred-booking'))
        <div class="tw-min-w-0"></div>
        @endif

        <!----------- GUEST NAME ----------->
        <div class="tw-min-w-0">
            <label for="customer_id" class="tw-block tw-mb-2 tw-text-sm tw-font-semibold">Guest</label>
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
        {{-- @if(url()->current() != route('booking.referred-booking'))
        <div class="tw-min-w-0">
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
        @endif --}}



        <!----------- ROOM NUMBER ----------->
        {{-- @if(url()->current() != route('booking.referred-booking'))
        <div class="tw-min-w-0">
            <div class="input-group" style="width:100%">
                <select name="room_id" class="form-control chosen-select room_number" id="room_id"
                    data-selected="{{ request('room_id') }}" data-placeholder="--Choose category--">
                    <option></option>

                </select>
            </div>
        </div>
        @endif --}}



        <!----------- BOOKING DATE ----------->
        @if(url()->current() != route('booking.referred-booking'))
        <div class="tw-min-w-0">
            <div class="input-group">
                <input type="text" class="form-control date-picker input-sm" value="{{ request('booking_from_date') }}" aria-label="Booked from"
                    name="booking_from_date" data-date-format="dd-mm-yyyy" placeholder="Booking From" autocomplete="off">
                <span class="input-group-addon">
                    <i class="fa fa-calendar bigger-110"></i>
                </span>
                <input type="text" class="form-control date-picker input-sm" value="{{ request('booking_to_date') }}" aria-label="Booked to"
                    name="booking_to_date" data-date-format="dd-mm-yyyy" placeholder="Booking To" autocomplete="off">
            </div>
        </div>
        @endif




        <!----------- BOOKING NUMBER ----------->
        <div class="tw-min-w-0">
            <div class="input-group">
                <input class="form-control input-sm" value="{{ request('booking_number') }}" aria-label="Booking number" name="booking_number"
                    type="text" placeholder="Booking Id" autocomplete="off">
                <span class="input-group-addon">
                    <i class="fa fa-file-text-o bigger-110"></i>
                </span>
            </div>
        </div>







        <!--------------- STATUS --------------->
        @if(url()->current() != route('booking.referred-booking'))
            <div class="tw-min-w-0">
                <div class="input-group">
                    <span class="input-group-addon">
                        Status
                    </span>
                    <select aria-label="Status" name="status" class="form-control chosen-select-100-percent" data-placeholder="--Status--" data-selected="{{ request('status') }}">
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










        <!----------- ACTION BUTTONS ----------->
        <div class="tw-min-w-0">
            <div class="tw-flex tw-gap-2">
                <button type="submit" class="mm-button">
                    <i class="fa fa-search"></i> Search
                </button>
                <a href="{{ request()->url() }}" class="mm-button mm-button-secondary" aria-label="Clear booking filters">
                    <i class="fa fa-refresh"></i>
                </a>
            </div>
        </div>


    </div>
</form>
