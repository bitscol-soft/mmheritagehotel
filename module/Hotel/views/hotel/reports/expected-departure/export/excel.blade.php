<table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="5">Expected Departure List - {{ date('Y-m-d') }}</th>
            </tr>
        @endif
        <tr>
            <th>SL</th>
            <th>Booking No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>NID / Passport</th>
            <th>Drop Address</th>
            <th>Drop Flight No</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($bookings as $booking)

            <tr class="odd gradeX">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $booking->booking_number }}</td>
                <td>{{ optional($booking->guestInfo)->name }}</td>
                <td>{{ optional($booking->guestInfo)->email }}</td>
                <td>{{ optional($booking->guestInfo)->phone_no }}</td>
                <td>{{ optional($booking->guestInfo)->nid_no }}</td>
                <td>{{ $booking->drop }}</td>
                <td>{{ $booking->drop_flight }}</td>
            </tr>
        @empty
            @if (request('export_type') != 'excel') <x-no-table-record /> @endif
        @endforelse
    </tbody>
</table>
