@extends('layouts.master')

@section('title', 'Hall Manage')

@section('page-header')
    <i class="fa fa-gears"></i> Hall Manage
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-banquet mm-room-inventory mm-hotel-setup" title="Halls" description="Find halls by name or hall number.">
        <x-slot name="actions">
            <a class="mm-button" href="{{ route('banquet.hall-rooms.create') }}">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New Room
            </a>
        </x-slot>

        <x-alert-message />

        <x-mm.panel class="mm-setup-filter tw-mb-4">
            <form action="" class="tw-flex tw-flex-wrap tw-gap-3 tw-items-center">
                <div class="input-group">
                    <span class="input-group-addon">Name</span>
                    <input type="text" name="name" class="form-control" placeholder="Room Name">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">Hall No</span>
                    <input type="text" name="room_number" class="form-control" placeholder="Hall No.">
                </div>
                {{-- <div class="input-group">
                    <span class="input-group-addon">Card No</span>
                    <input type="text" name="f_r_id_card" class="form-control" placeholder="Card No.">
                </div> --}}
                <div class="tw-flex tw-gap-2">
                    <button class="mm-button">
                        <i class="fa fa-search" aria-hidden="true"></i> Search
                    </button>
                    <a href="{{ request()->url() }}" class="mm-button mm-button-secondary">
                        <i class="fa fa-refresh" aria-hidden="true"></i> Clear
                    </a>
                </div>
            </form>
        </x-mm.panel>

        <x-mm.panel class="tw-p-4">
            <x-mm.table-scroll label="Hall list">
                <table id="data-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">SL</th>
                            <th width="25%" class="text-center">Name</th>
                            <th width="10%" class="text-center">Hall No</th>
                            <th width="20%" class="text-center">Category</th>
                            <th width="10%" class="text-center">Price</th>
                            <th width="10%" class="text-center">Max Guest</th>
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
                                <td class="text-center">
                                    {{ $data->category->name }}
                                </td>
                                <td class="text-center">{{ $data->price }} </td>
                                <td class="text-center">{{ $data->max_guests }} </td>

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
                                        <a href="{{ route('banquet.hall-rooms.edit', $data->id) }}"
                                            class="btn btn-xs btn-sm btn-success " title="Edit">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <button type="button" onclick="delete_check({{ $data->id }})"
                                            class="btn btn-xs btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>

                                    <form action="{{ route('banquet.hall-rooms.destroy', $data->id) }}"
                                        id="deleteCheck_{{ $data->id }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-mm.table-scroll>
        </x-mm.panel>
    </x-mm.page>
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
