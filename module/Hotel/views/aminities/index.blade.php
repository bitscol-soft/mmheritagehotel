@extends('layouts.master')
@section('title','Add New Aminities')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup" title="Amenities" description="Facilities attached to rooms and shown on the website.">
    <x-slot name="actions"><a class="mm-button" href="{{ route('aminities.create') }}"><i class="fa fa-plus"></i> Add New Amenities</a></x-slot>
    @include('partials._alert_message')

    <x-mm.panel>
        {{-- W3.4: converted from <x-mm.table-scroll> + raw <table> to
             <x-mm.data-table>. The data-table component renders the
             same Bootstrap-classed table (preserved via the table-class
             prop). All route names, the @foreach iteration variable
             usage, the delete_check() call, and the <form> for the
             DELETE method are byte-identical. --}}
        <x-mm.data-table label="Amenities"
            table-class="table table-striped table-bordered table-hover"
            :columns="[
                ['label' => 'SL'],
                ['label' => 'Name', 'align' => 'center'],
                ['label' => 'Icon', 'align' => 'center'],
                ['label' => 'Status', 'align' => 'center'],
                ['label' => 'Action', 'align' => 'center'],
            ]">
            @forelse ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $item->name }}</td>
                    <td class="text-center">
                        <img class="img-responsive m-auto" src="{{ asset($item->aminities_icon) }}" alt="">
                    </td>
                    <td class="text-center">
                        @if ($item->status == 1)
                            <label class="label label-success">Active</label>
                            @else
                            <label class="label label-danger">InActive</label>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-corner">
                            <a href="{{ route('aminities.edit', $item->id) }}" class="btn btn-xs btn-sm btn-success" title="Edit">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <button type="button" onclick="delete_check({{ $item->id }})" class="btn btn-xs btn-sm btn-danger" title="Delete">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </div>

                        <form action="{{ route('aminities.destroy',$item->id)}}" id="deleteCheck_{{ $item->id }}" method="POST">
                            @csrf
                            @method("DELETE")
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No amenities found.</td>
                </tr>
            @endforelse
        </x-mm.data-table>
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
