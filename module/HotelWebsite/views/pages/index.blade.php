@extends('layouts.master')
@section('title','Page List')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-web mm-room-inventory" title="Pages" description="Extra content pages of the website.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('website-core.pages.create') }}">
            <i class="fa fa-plus" aria-hidden="true"></i> New Page
        </a>
    </x-slot>
    @include('partials._alert_message')

    <x-mm.panel class="tw-p-4">
        <x-mm.table-scroll label="Website pages">
        <table id="data-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>SL</th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Subtitle</th>
                    <th class="text-center">Short Description</th>
                    <th class="text-center">Image</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($pages as $key => $page)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td class="text-center">{{ $page->title }}</td>
                    <td class="text-center">{{ $page->sub_title }}</td>
                    <td class="text-center">{!! $page->short_description !!}</td>
                    <td class="text-center"><img height="50" src="{{ asset($page->image) }}" alt=""></td>
                    <td class="text-center">
                        @if ($page->status == 1)
                            <span class="label label-success">Active</span>
                            @else
                            <span class="label label-danger">IN Active</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-corner">
                            <a href="{{ route('website-core.pages.edit',$page->id) }}" class="btn btn-xs btn-sm btn-success" title="Edit">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <button type="button" onclick="delete_item(`{{ route('website-core.pages.destroy', $page->id) }}`)" class="btn btn-xs btn-sm btn-danger" title="Delete">
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
