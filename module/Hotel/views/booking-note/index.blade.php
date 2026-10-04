@extends('layouts.master')
@section('title', 'Booking Note List')

@section('content')
    <x-mm.styles />
    <x-mm.page title="Booking notes" description="Review existing note titles and their active status." class="mm-booking-setup mm-booking-notes">
    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12">

            @include('booking-note.include.filter')
            <div>
                {{-- W3.3: converted from <x-mm.table-scroll> + raw <table>
                     to <x-mm.data-table>. The data-table component renders
                     the same Bootstrap-classed table (preserved via the
                     table-class prop) and applies mm-data-table styling.
                     All cell widths, class names, route names, and the
                     <x-status> component usage are byte-identical. --}}
                <x-mm.data-table label="Booking notes"
                    table-class="table table-striped table-bordered table-hover"
                    :columns="[
                        ['label' => 'SL', 'width' => '5%', 'align' => 'center'],
                        ['label' => 'Title', 'width' => '70%'],
                        ['label' => 'Status', 'width' => '10%', 'align' => 'center'],
                        ['label' => 'Action', 'width' => '10%', 'align' => 'center'],
                    ]">
                    @forelse ($bookingNotes as $item)
                        <tr>
                            <td class="center">{{ $loop->index + 1 }}</td>
                            <td>{{ strip_tags($item->title) }}</td>
                            <td class="text-center">
                                <x-status status="{{ $item->status }}" id="{{ $item->id }}" table="{{ $table }}" />
                            </td>
                            <td class="center">
                                <div class="btn-group">
                                    <a aria-label="Edit booking note" class="btn btn-sm btn-success" href="{{ route('booking-note.edit', $item->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No booking notes found.</td>
                        </tr>
                    @endforelse
                </x-mm.data-table>
            </div>
        </div>
    </div>
    </x-mm.page>
@endsection
@section('script')
    <script>
        $(document).on("click", ".updateStatus", function() {

            let status = $(this).children("i").attr("status");
            let item_id = $(this).attr("item-id");
            let url = $(this).attr("item-url");
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: '{!! csrf_token() !!}',
                    status: status,
                    item_id: item_id
                },
                success: function(resp) {
                    if (resp['status'] == 0) {
                        $("#id-" + item_id).html(
                            "<i class='fa fa-toggle-off text-danger status-icon' status='Inactive' style='font-size: 20px'></i>"
                        );
                        swal.fire({
                            icon: 'success',
                            title: "Status Inactive Successfully",
                            type: "success",
                            timer: 1500
                        });
                    } else if (resp['status'] == 1) {
                        $("#id-" + item_id).html(
                            "<i class='fa fa-toggle-on text-success status-icon' status='Active' style='font-size: 20px'></i>"
                        );
                        swal.fire({
                            icon: 'success',
                            title: "Status Active Successfully",
                            type: "success",
                            timer: 1500
                        });
                    }
                },
                error: function() {
                    alert("Error");
                }
            });
        });
    </script>
@endsection
