@extends('layouts.master')
@section('title', 'Booking Note List')
@section('page-header')
    <i class="fa fa-info-circle"></i> Booking Note List
@stop

@section('content')
    <div class="page-header">
        {{-- <a class="btn btn-xs btn-info" href="{{ route('guests.create') }}" style="float: right; margin: 0 2px;"> <i
                class="fa fa-plus"></i> Add New Guests </a> --}}
        <h1>
            <i class="fa fa-info-circle green"></i> Booking Note List
        </h1>
    </div>

    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12">

            @include('booking-note.include.filter')
            <div>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">SL</th>
                            <th width="70%">Title</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="10%" class="center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($bookingNotes as $item)
                            <tr>
                                <td width="5%" class="center">{{ $loop->index + 1 }}</td>
                                <td width="70%">{{ strip_tags($item->title) }}</td>
                                <td width="10%" class="text-center">
                                    <x-status status="{{ $item->status }}" id="{{ $item->id }}" table="{{ $table }}" />
                                </td>
                                <td width="10%" class="center">
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-success" href="{{ route('booking-note.edit', $item->id) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
