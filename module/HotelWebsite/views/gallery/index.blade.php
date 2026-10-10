@extends('layouts.master')
@section('title','Gallery List')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-inventory" title="Gallery" description="Images shown in the website gallery.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('website-core.gallery.create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add Gallery
        </a>
    </x-slot>
    <x-alert-message />

    <x-mm.panel class="tw-p-4">
        <x-mm.table-scroll label="Gallery images">
        <table id="data-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>SL</th>
                    <th class="text-center">Gallery Title</th>
                    <th class="text-center">Gallery Images</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $key => $item)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td class="text-center">{{ $item->gallery_text }}</td>
                    <td class="text-center">
                        <img height="50" src="{{ $item->name != null ? asset(str_replace("./","", $item->name)) : asset('assets/images/default.png') }}" alt="">
                    </td>
                    <td class="text-center">
                        @if ($item->status == 1)
                            <span class="label label-success">Active</span>
                            @else
                            <span class="label label-danger">IN Active</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-corner">
                            <a href="{{ route('website-core.gallery.edit',$item->id) }}" class="btn btn-xs btn-sm btn-success" title="Edit">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <button type="button" onclick="delete_item(`{{ route('website-core.gallery.destroy',$item->id)}}`)" class="btn btn-xs btn-sm btn-danger" title="Delete">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </div>

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
