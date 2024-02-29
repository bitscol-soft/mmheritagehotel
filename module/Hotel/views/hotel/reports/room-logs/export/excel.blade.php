<table id="datatable" class="table table-striped table-bordered" width="100%">
    <thead>
        @if (request('export_type') == 'excel')
            <tr>
                <th colspan="5">Room Log {{ date('Y-m-d') }}</th>
            </tr>
        @endif
        <tr>
            <th width="3%">SL</th>
            <th>Date</th>
            <th>Room</th>
            <th>User</th>
            <th>Remark</th>
            <th>Histry</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($room_logs as $item)
            <tr class="odd gradeX">
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ $item->date }}
                </td>
                <td>
                    {{ optional($item->room)->name }} ->
                    <span class="badge badge-info">
                        <span data-rel="popover" data-placement="top" data-trigger="click"
                            data-original-title=" Room No : {{ optional($item->room)->room_number }}"
                            data-content="<p> Checking by {{ optional($item->user)->name }},<br>
                                Booking by {{ optional($item->user)->name }}</p>">
                            {{ optional($item->room)->room_number }}
                        </span>
                    </span>
                </td>
                <td>
                    {{ optional($item->user)->name }}
                </td>
                <td>
                    {{ $item->remarks }}
                </td>
                <td>
                    <span class="badge badge-info">
                        <span data-rel="popover" data-placement="top" data-trigger="click"
                            data-original-title=" Room No : {{ optional($item->room)->room_number }}"
                            data-content="<b> Payment received by {{ optional($item->received)->name }}, <br>
                                Extra Charge for {{ $item->note }} by {{ optional($item->booked)->name }}, Advance payment received by {{ optional($item->received)->name }}</b>">
                            <i class="fa fa-refresh"></i>
                        </span>
                    </span>
                </td>

            </tr>
        @empty
            @if (request('export_type') != 'excel')
                <x-no-table-record />
            @endif
        @endforelse
    </tbody>
</table>
