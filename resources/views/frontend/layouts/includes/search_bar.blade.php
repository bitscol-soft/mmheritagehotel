

@php
    $category = roomCategory();
    $date = date('Y-m-d');
    $date1 = str_replace('-', '/', $date);
    $tomorrow = date('m/d/Y',strtotime($date1 . "+1 days"));
    $check_segment = last(request()->segments());

@endphp

<div id="availability-agileits">
    <div class="col-md-3 book-form-left-w3layouts">
        <h2>BOOKING DATE</h2>
    </div>
<div class="col-md-9 book-form">
    <form action="{{ route('view.category') }}" method="GET">
        <div class="fields-w3ls form-left-agileits-w3layouts ">
            <p>Room Type</p>
            <select class="form-control" name="room_category" id="room_category" required>
                <option value="all">Select All</option>
                @foreach ($category as $item)
                    <option value="{{ $item->id }}"
                         @if(request('room_category') == $item->id) selected @elseif ($check_segment == $item->url_slug) selected  @endif>
                         {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="fields-w3ls form-date-w3-agileits">
            <p>Arrival Date</p>
                <input class="check_in"  id="datepicker1" name="check_in" type="text" value="{{ request('check_in') ?? date('m/d/Y') }}" placeholder="Select A Date" required="" autocomplete="off">
        </div>
        <div class="fields-w3ls form-date-w3-agileits">
            <p>Departure Date</p>
                <input class="check_out"  id="datepicker2" name="check_out" type="text" value="{{ request('check_out') ?? $tomorrow }}" placeholder="Select A Date"  required autocomplete="off">
        </div>

        <div class=" form-left-agileits-submit">
                <input type="submit" value="Check Availability">
        </div>
    </form>
</div>
<div class="clearfix"> </div>
</div>
