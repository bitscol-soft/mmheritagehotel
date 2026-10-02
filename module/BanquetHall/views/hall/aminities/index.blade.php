@extends('layouts.master')
@section('title', 'Add New Aminities')
@section('page-header')
    <i class="fa fa-gears"></i> Add New Aminities
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-banquet mm-hotel-setup" title="Hall amenities" description="Amenities available for halls.">
        <x-slot name="actions"><a class="mm-button" href="{{ route('banquet.aminities.create') }}"><i class="fa fa-plus"></i> Add New Aminities</a></x-slot>
        @include('partials._alert_message')

        <x-mm.panel>
            <x-mm.table-scroll label="Hall amenities">
                <table id="data-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Icon</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $data->name }}</td>
                                <td class="text-center">
                                    <img class="img-responsive m-auto" src="{{ asset($data->aminities_icon) }}"
                                        alt="">
                                </td>
                                <td class="text-center">
                                    @if ($data->status == 1)
                                        <label class="label label-success">Active</label>
                                    @else
                                        <label class="label label-danger">InActive</label>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        <a href="{{ route('banquet.aminities.edit', $data->id) }}"
                                            class="btn btn-xs btn-sm btn-success" title="Edit">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <button type="button" onclick="delete_check({{ $data->id }})"
                                            class="btn btn-xs btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>

                                    <form action="{{ route('banquet.aminities.destroy', $data->id) }}"
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
