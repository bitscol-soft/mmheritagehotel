@extends('layouts.master')
@section('title','Feature List')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-inventory" title="Homepage features" description="Feature boxes shown on the website home page.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('website-core.feature_list.create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add Feature List
        </a>
    </x-slot>
    <x-alert-message />

    <x-mm.panel class="tw-p-4">
        <x-mm.data-table :columns="[
            ['label' => 'SL'],
            ['label' => 'Feature Title', 'align' => 'center'],
            ['label' => 'Feature Subtitle', 'align' => 'center'],
            ['label' => 'Feature Icon', 'align' => 'center'],
            ['label' => 'Status', 'align' => 'center'],
            ['label' => 'Action', 'align' => 'center'],
        ]" id="data-table" label="Homepage features" table-class="table table-striped table-bordered table-hover">
            @forelse ($feature_list as $key => $data)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td class="text-center">{{ $data->title }}</td>
                    <td class="text-center">{{ $data->sub_title }}</td>
                    <td class="text-center">{{ $data->feature_icon }}</td>
                    <td class="text-center">
                        @if ($data->status == 1)
                            <span class="label label-success">Active</span>
                        @else
                            <span class="label label-danger">IN Active</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-corner">
                            <a href="{{ route('website-core.feature_list.edit',$data->id) }}" class="btn btn-xs btn-sm btn-success" title="Edit">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <button type="button" onclick="delete_item(`{{ route('website-core.feature_list.destroy', $data->id) }}`)" class="btn btn-xs btn-sm btn-danger" title="Delete">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </div>

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
