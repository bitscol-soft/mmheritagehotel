@extends('layouts.master')

@section('title', 'Room Manage')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-room-inventory" title="Rooms" description="Find rooms by name, room number or access card.">
        <x-slot name="actions">
            <a class="mm-button" href="{{ route('rooms.create') }}">
                <i class="fa fa-plus" aria-hidden="true"></i> Add New Room
            </a>
        </x-slot>

    <x-alert-message />

    <div class="row">
        <div class="col-xs-12">

            <x-mm.panel class="tw-mb-4">
                <form action="" class="tw-grid tw-gap-4 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-items-end">
                    <x-mm.field label="Name" id="room-filter-name" name="name" placeholder="Room Name" />
                    <x-mm.field label="Room number" id="room-filter-number" name="room_number" placeholder="Room No." />
                    <x-mm.field label="Card number" id="room-filter-card" name="f_r_id_card" placeholder="Card No." />
                    <div class="tw-flex tw-flex-wrap tw-gap-2">
                        <button type="submit" class="mm-button"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                        <a href="{{ request()->url() }}" class="mm-button mm-button-secondary"><i class="fa fa-refresh" aria-hidden="true"></i> Clear</a>
                    </div>
                </form>
            </x-mm.panel>

            <div class="mm-panel tw-p-4">
                <x-mm.data-table :columns="[
                    ['label' => 'SL', 'align' => 'center', 'width' => '5%'],
                    ['label' => 'Name', 'align' => 'center', 'width' => '25%'],
                    ['label' => 'Room No', 'align' => 'center', 'width' => '10%'],
                    ['label' => 'Bed', 'align' => 'center', 'width' => '10%'],
                    ['label' => 'Price', 'align' => 'center', 'width' => '10%'],
                    ['label' => 'F R ID', 'align' => 'center', 'width' => '10%'],
                    ['label' => 'Status', 'align' => 'center', 'width' => '10%'],
                    ['label' => 'Action', 'align' => 'center', 'width' => '10%'],
                ]" id="data-table" table-class="table table-striped table-bordered table-hover" label="Room inventory">
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
                            @else
                                <td></td>
                                <td></td>
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
                                        <i class="fa fa-trash-o"></i>
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
                </x-mm.data-table>
            </div>
        </div>

    </div>
    </x-mm.page>
@endsection

@section('js')

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
@stop
