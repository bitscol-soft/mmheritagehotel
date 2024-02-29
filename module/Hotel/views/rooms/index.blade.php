@extends('layouts.master')

@section('title', 'Room Manage')

@section('page-header')
    <i class="fa fa-gears"></i> Room Manage
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop

@section('content')
    <div class="page-header">
        <div class="page-header">
            <a class="btn btn-xs btn-info" href="{{ route('rooms.create') }}" style="float: right; margin: 0 2px;"> <i
                    class="fa fa-plus"></i> Add New Room</a>
            <h1>
                <i class="fa fa-info-circle green"></i> Room List
            </h1>
        </div>
    </div>

    <x-alert-message />



    <div class="row">
        <div class="col-xs-12">

            <!-- SEARCHING -->
            <div class="row">
                <form action="">
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">Name</span>
                                    <input type="text" name="name" class="form-control" placeholder="Room Name">
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">Room No</span>
                                    <input type="text" name="room_number" class="form-control" placeholder="Room No.">
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-addon">Card No</span>
                                    <input type="text" name="f_r_id_card" class="form-control" placeholder="Card No.">
                                </div>
                            </td>
                            <td style="width: 10%">
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-info">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a href="{{ request()->url() }}" class="btn btn-xs btn-default">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <table id="data-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">SL</th>
                            <th width="25%" class="text-center">Name</th>
                            <th width="10%" class="text-center">Room No</th>
                            @if (setting('room_wise_pricing_booking') == 1)
                                <th width="10%" class="text-center">Bed</th>
                                <th width="10%" class="text-center">Price</th>
                            @endif
                            <th width="10%" class="text-center">F R ID </th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="10%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rooms as $key => $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $data->name }}</td>
                                <td class="text-center">
                                    {{ $data->room_number }}
                                </td>
                                @if (setting('room_wise_pricing_booking') == 1)
                                    <td class="text-center">
                                        <i class="fa fa-bed"> * {{ $data->beds }}</i>
                                    </td>
                                    <td class="text-center">{{ $data->rent }} </td>
                                @endif

                                <td class="text-center">{{ $data->f_r_id_card }}</td>
                                <td class="text-center">
                                    @if ($data->status == 1)
                                        <span class="label label-sm label-success">Ready</span>
                                    @elseif ($data->status == 0)
                                        <span class="label label-sm label-danger">Dirty</span>
                                    @else
                                        <span class="label label-sm label-warning">Maintenance</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        <a href="{{ route('rooms.edit', $data->id) }}"
                                            class="btn btn-xs btn-sm btn-success " title="Edit">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <button type="button" onclick="delete_check({{ $data->id }})"
                                            class="btn btn-xs btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>

                                    <form action="{{ route('rooms.destroy', $data->id) }}"
                                        id="deleteCheck_{{ $data->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>


    @include('rooms.inc.script')


    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        function delete_check(id) {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('#deleteCheck_' + id).submit();
                }
            })

        }
    </script>

    <script type="text/javascript">
        jQuery(function($) {
            $('#data-table').DataTable({
                "ordering": false,
                "bPaginate": true,
                "lengthChange": false,
                "info": false,
                "pageLength": 25
            });
        })
    </script>
@stop
