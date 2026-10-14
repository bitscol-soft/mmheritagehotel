@extends('layouts.master')
@section('title','User Permission')
@section('css')

@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-perm mm-perm-list" title="User permissions" description="Every permission the system can grant, with its slug.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('permissions.create') }}"> <i class="fa fa-plus" aria-hidden="true"></i> Create Permission </a>
    </x-slot>

    @include('partials._alert_message')

    <x-mm.panel>
        <x-mm.data-table :columns="[
            ['label' => 'SL'],
            ['label' => 'Module'],
            ['label' => 'Submodule', 'width' => '15%'],
            ['label' => 'Parent Permission'],
            ['label' => 'Permission Name'],
            ['label' => 'Slug'],
            ['label' => 'Action', 'width' => '8%', 'align' => 'center'],
        ]" table-class="table table-striped table-bordered table-hover" id="dynamic-table" label="Permissions">
            @foreach($permissions as $key => $permission)
                <tr>
                    <td>{{ $key+$permissions->firstItem() }}</td>
                    <td>{{ $permission->parent_permission->submodule->module->name }}</td>
                    <td>{{ $permission->parent_permission->submodule->name }}</td>
                    <td>{{ $permission->parent_permission->name }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->slug }}</td>
                    <td class="text-center">
                        <div class="btn-group btn-corner">
                            <a href="{{ route('permissions.edit',$permission->id) }}" class="btn btn-xs btn-sm btn-success" title="Edit">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <button type="button" onclick="delete_check({{ $permission->id }})" class="btn btn-xs btn-sm btn-danger" title="Delete">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </div>

                        <form action="{{ route('permissions.destroy',$permission->id)}}" id="deleteCheck_{{ $permission->id }}" method="POST">
                            @csrf
                            @method("DELETE")
                        </form>
                    </td>
                </tr>
            @endforeach
        </x-mm.data-table>
        @include('partials._paginate', ['data' => $permissions])
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

    <!-- inline scripts related to this page -->
    <script type="text/javascript">

        function delete_check(id)
        {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width:400,
            }).then((result) =>{
                if(result.value){
                    $('#deleteCheck_'+id).submit();
                }
            })

        }

    </script>
@stop
