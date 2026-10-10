
@extends('layouts.master')
@section('title','Group')
@section('css')

@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-crud-index" title="Groups">
        <x-mm.panel>
    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12">

            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Group Name</th>
                            <th>Phone </th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Logo</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($groups as $key => $group)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $group->name }}</td>
                                <td>{{ $group->phone }}</td>
                                <td>{{ $group->email }}</td>
                                <td>{{ $group->address }}</td>
                                <td>
                                    @if ($group->logo == "default.png")
                                        <dd><img src="{{ asset('uploads/'.$group->logo) }}" alt="{{ $group->name }}" style="width: 60px"></dd>
                                    @else
                                        <dd><img src="{{ asset('uploads/group/'.$group->logo) }}" alt="{{ $group->name }}" style="width: 60px"></dd>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        @if (hasPermission("groups.view", $slugs))
                                            <a href="#view-details{{ $group->id }}" role="button" data-toggle="modal" class="btn btn-sm btn-info" title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif

                                        @if (hasPermission("groups.edit", $slugs))
                                            <a href="{{ route('group.edit',$group->id) }}" class="btn btn-sm btn-success" title="Edit">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                        @endif

                                        @if (hasPermission("groups.delete", $slugs))
                                            <button type="button" onclick="delete_check({{ $group->id }})" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        @endif

                                    </div>
                                    <form action="{{ route('group.destroy',$group->id)}}" id="deleteCheck_{{ $group->id }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>
        </x-mm.panel>
    </x-mm.page>

    @foreach($groups as $group)

        <div id="view-details{{ $group->id }}" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="blue bigger"><i class="fa fa-eye"></i> View @yield('title') Details</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-12">

                                <dl id="dt-list-1" class="dl-horizontal">

                                    <dt>Group Name</dt>
                                    <dd>{{ $group->name }}</dd>

                                    <dt>Group email</dt>
                                    <dd>{{ $group->email }}</dd>

                                    <dt>Group Phone</dt>
                                    <dd>{{ $group->phone }}</dd>

                                    <dt>Group Address</dt>
                                    <dd>{{ $group->address }}</dd>

                                    <dt>Group Logo</dt>
                                    @if ($group->logo == "default.png")
                                        <dd><img src="{{ asset('uploads/'.$group->logo) }}" alt="{{ $group->name }}" style="width: 100px"></dd>
                                    @else
                                        <dd><img src="{{ asset('uploads/group/'.$group->logo) }}" alt="{{ $group->name }}" style="width: 100px"></dd>
                                    @endif


                                    <dt>Created At</dt>
                                    <dd>{{ \Carbon\Carbon::parse($group->created_at)->format('F d, Y h:i s A') }}</dd>

                                    <dt>Updated At</dt>
                                    <dd>{{ Carbon\Carbon::parse($group->updated_at)->format('F d, Y h:i s A') }}</dd>

                              </dl>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm" data-dismiss="modal">
                            <i class="ace-icon fa fa-times"></i>
                            Cancel
                        </button>

                    </div>
                </div>
            </div>

        </div>

    @endforeach

@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

    

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


    <script type="text/javascript">
        jQuery(function($) {
            $('#dynamic-table').DataTable({
                "ordering": false,
                "bPaginate": false,
                "lengthChange": false,
                "info": false
            });

        })
    </script>
@stop
