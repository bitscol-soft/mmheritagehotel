<table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="5">Today In House Guest List - {{ date('Y-m-d') }}</th>
            </tr>
        @endif
        <tr>
            <th>Room No</th>
            {{-- <th>Type</th> --}}
            <th>Name</th>
            <th>Phone No</th>
            <th>Address</th>
            <th>Company Name</th>
            <th>Pax</th>
            <th>Arrival Date</th>
            <th>Departure Date</th>
            <th>NID/Passport</th>
        </tr>
    </thead>

    @foreach ($bookings as $booking)
        <tbody>
            @foreach ($booking->bookingDetails as $key => $bookingDetail)
                @php
                    $words = explode(" ", optional($bookingDetail->roomNumber)->name);
                    $roomName = "";

                    foreach ($words as $word) {
                        $roomName .= mb_substr($word, 0, 1);
                    }
                @endphp
                <tr class="odd gradeX">
                    <td>{{ optional($bookingDetail->roomNumber)->room_number }}</td>
                    {{-- <td>{{ $roomName ?? '' }}</td> --}}
                    <td>{{ $key == 0 ? optional($booking->guestInfo)->name : '' }}</td>
                    <td>{{ $key == 0 ? optional($booking->guestInfo)->phone_no : '' }}</td>
                    <td>{{ $key == 0 ? optional($booking->guestInfo)->address : '' }}</td>
                    <td>{{ $key == 0 ? optional(optional($booking->guestInfo)->company)->name : '' }}</td>
                    <td>{{ $key == 0 ? $booking->booking_pax : '' }}</td>
                    <td>{{ $key == 0 ? $booking->check_in_date : '' }}</td>
                    <td>{{ $key == 0 ? $booking->check_out_date : '' }}</td>
                    <td>{{ $key == 0 ? optional($booking->guestInfo)->nid_no : '' }}</td>
                </tr>
            @endforeach
        </tbody>
    @endforeach

    @if ($bookings->count() == 0)
        @if (request('export_type') != 'excel') <x-no-table-record /> @endif
    @endif


</table>

<div style="float: right; margin-top: 20px">
    <div>
        @foreach ($roomCategories as $category)
            @php
                $roomWords = explode(" ", $category->name);
                $room = "";

                foreach ($roomWords as $roomWord) {
                    $room .= mb_substr($roomWord, 0, 1);
                }
            @endphp

            <div><b>{{ $room }}</b> = {{ $category->name }}</div>
        @endforeach
    </div>
</div>
