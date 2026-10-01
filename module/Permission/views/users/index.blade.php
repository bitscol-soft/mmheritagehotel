@extends('layouts.master')



@section('title', 'Permitted User List')





@section('content')
<x-mm.styles />
<x-mm.page class="mm-perm mm-perm-list" title="Permitted users" description="Users who can sign in, with their company, department and designation.">
    <x-slot name="actions">
        <a class="mm-button" href="{{ route('settings.create-user') }}" title="Add New User"> <i class="fa fa-plus" aria-hidden="true"></i> Create New </a>
    </x-slot>

    <x-mm.panel>
        <x-mm.table-scroll label="Permitted users">
            <table id="data-table" class="table table-striped table-bordered table-hover" >
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Employee Id</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Department</th>
                        <th>Designation</th>
                        @if(hasPermission("permission.accesses.edit", $slugs) || hasPermission("permission.accesses.delete", $slugs) || hasPermission("change.employees.password", $slugs))
                            <th style="width: 12%">Action</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ optional($user->employee)->employee_full_id ?? 'Not an Employee' }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ optional($user->company)->name }}</td>
                            <td>{{ optional($user->employee)->getDepartmentName() }}</td>
                            <td>{{ optional($user->employee)->getDesignationName() }}</td>

                            @if(hasPermission("permission.accesses.edit", $slugs) || hasPermission("permission.accesses.delete", $slugs) || hasPermission("change.employees.password", $slugs))
                                <td class="text-center" style="width: 120px">
                                    <div class="btn-group btn-corner">


                                        @if(hasPermission("change.employees.password", $slugs) && $user->credential)
                                            <span class="btn btn-inverse btn-xs popover-inverse"
                                                data-rel="popover"
                                                data-placement="top"
                                                data-trigger="click"
                                                data-original-title="<i class='ace-icon fa fa-info-circle green'></i> User Password"
                                                data-content="<p>Password: {{ optional($user->credential)->secrete }}</p>">
                                                <i class="fa fa-key"></i>
                                            </span>
                                        @endif

                                        @if(hasPermission("change.employees.password", $slugs))
                                            <a href="{{ route('admin.edit.password',$user->id) }}" class="btn btn-xs btn-info pull-center" title="Change Password">
                                                <i class="fa fa-lock"></i>
                                            </a>
                                        @endif

                                        @if($user->status == 2)
                                            <a href="{{ route('user.active.deactive',[$user->id, 1]) }}" class="btn btn-xs btn-warning pull-center" title="Active">
                                                <i class="fa fa-thumbs-up"></i>
                                            </a>
                                        @endif

                                        @if($user->status == 1)
                                            <a href="{{ route('user.active.deactive',[$user->id, 2]) }}" class="btn btn-xs btn-primary pull-center" title="De-active">
                                                <i class="fa fa-thumbs-down"></i>
                                            </a>
                                        @endif

                                        @if(hasPermission("permission.accesses.edit", $slugs))
                                            <a href="{{ route('edit.permitted.users',$user->id) }}" class="btn btn-xs btn-success pull-center" title="Edit">
                                                <i class="fa fa-pencil-square"></i>
                                            </a>
                                        @endif

                                        @if(hasPermission("permission.accesses.delete", $slugs))
                                            <button type="button" onclick="delete_check({{ $user->id }})" class="btn btn-xs btn-danger" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        @endif
                                    </div>


                                    <form action="{{ route('permitted.user.delete', $user->id)}}" id="deleteCheck_{{ $user->id }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    </form>
                                </td>
                            @endif
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

    
    <script src="{{asset('assets/custom_js/custom-datatable.js')}}"></script>



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
