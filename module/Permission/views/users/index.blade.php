@extends('layouts.master')



@section('title', 'Permitted User List')


@push('style')
    <style>
  

        thead>tr>th {
            background: #4d8cb3;
            color: white;
            padding: 10px 5px 10px 5px !important;
        }
    </style>
@endpush



@section('content')
<div class="row">

    <div class="col-sm-12">
        <div class="widget-box">


            <!-- header -->
            <div class="widget-header">
                <h4 class="widget-title"> 
                    <i class="fa fa-info-circle"></i> User List
                </h4>

                <span class="widget-toolbar">
                    <a href="{{ route('settings.create-user') }}" title="Add New User">
                        <i class="ace-icon fa fa-plus"></i> 
                        Create New
                    </a>
                </span>
            </div>


            <div class="widget-body">
                <div class="widget-main">


                    <div class="row">
                        <div class="col-md-12" style="margin-left:auto !important; margin-right:auto !important">

                            <div class="table-responsive" >
                                <table id="data-table" class="table table-striped table-bordered table-hover" >
                                    <thead>
                                        <tr>
                                            <th style="color: white">SL</th>
                                            <th style="color: white">Employee Id</th>
                                            <th style="color: white">User Name</th>
                                            <th style="color: white">Email</th>
                                            <th style="color: white">Company</th>
                                            <th style="color: white">Department</th>
                                            <th style="color: white">Designation</th>
                                            @if(hasPermission("permission.accesses.edit", $slugs) || hasPermission("permission.accesses.delete", $slugs) || hasPermission("change.employees.password", $slugs))
                                                <th style="color: white; width: 12%">Action</th>
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
                                                                    <i class="fa fa-trash"></i>
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
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

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
