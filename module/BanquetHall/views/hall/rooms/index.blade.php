@extends('layouts.master')

@section('title', 'Hall Manage')

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
                <x-mm.field label="Name" id="hall-filter-name" name="name" placeholder="Room Name" />
                <x-mm.field label="Hall No" id="hall-filter-number" name="room_number" placeholder="Hall No." />
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
            <x-mm.data-table :columns="[
                ['label' => 'SL', 'width' => '5%'],
                ['label' => 'Name', 'width' => '25%', 'align' => 'center'],
                ['label' => 'Hall No', 'width' => '10%', 'align' => 'center'],
                ['label' => 'Category', 'width' => '20%', 'align' => 'center'],
                ['label' => 'Price', 'width' => '10%', 'align' => 'center'],
                ['label' => 'Max Guest', 'width' => '10%', 'align' => 'center'],
                ['label' => 'Status', 'width' => '10%', 'align' => 'center'],
                ['label' => 'Action', 'width' => '10%', 'align' => 'center'],
            ]" id="data-table" label="Hall list" table-class="table table-striped table-bordered table-hover">
                @forelse ($rooms as $key => $data)
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
                @empty
                    <x-no-table-record />
                @endforelse
            </x-mm.data-table>
        </x-mm.panel>
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
