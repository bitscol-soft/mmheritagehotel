@extends('layouts.master')
@section('title','Service List')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-inventory" title="Service boxes" description="Service boxes shown in the Our Services section.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('website-core.our_service_list.create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add Service Box
        </a>
    </x-slot>
    @include('partials._alert_message')

    <x-mm.panel class="tw-p-4">
        <x-mm.data-table :columns="[
            ['label' => 'SL'],
            ['label' => 'Heading', 'align' => 'center'],
            ['label' => 'Description', 'align' => 'center'],
            ['label' => 'List', 'align' => 'center'],
            ['label' => 'Icon', 'align' => 'center'],
            ['label' => 'Status', 'align' => 'center'],
            ['label' => 'Action', 'align' => 'center'],
        ]" id="data-table" label="Service boxes" table-class="table table-striped table-bordered table-hover">
            @forelse ($service as $key => $item)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td style="width: 20%">{{ $item->service_title }}</td>
                    <td>{{ $item->service_description }}</td>
                    <td>{{ $item->service_list }}</td>
                    <td class="text-center" style="width: 10%"><i class="{{ $item->service_icon }} fa-2x"></i></td>
                    <td class="text-center">
                        @if ($item->status == 1)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-danger">IN Active</span>
                        @endif
                    </td>
                    <td class="text-center" style="width: 10%">
                        <div class="btn-group btn-corner">
                            <a href="{{ route('website-core.our_service_list.edit',$item->id) }}" class="btn btn-xs btn-sm btn-success" title="Edit">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <button type="button" onclick="delete_check({{ $item->id }})" class="btn btn-xs btn-sm btn-danger" title="Delete">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </div>

                        <form action="{{ route('website-core.our_service_list.destroy',$item->id)}}" id="deleteCheck_{{ $item->id }}" method="POST">
                            @csrf
                            @method("DELETE")
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
