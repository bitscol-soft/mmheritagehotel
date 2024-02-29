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
                    {{ optional($item->room)->name }} -> <span class="badge badge-info">{{ optional($item->room)->room_number }}</span>
                <td>
                    {{ optional($item->user)->name }}
                </td>
                <td>
                    {{ $item->remarks }}
                </td>

            </tr>
        @empty
            @if (request('export_type') != 'excel') <x-no-table-record /> @endif
        @endforelse
    </tbody>
</table>
