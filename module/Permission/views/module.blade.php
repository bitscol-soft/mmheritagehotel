@extends('layouts.master')
@section('title',' Manage Module')
@section('css')
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup mm-perm" title="Modules" description="Switch modules on or off and add new ones.">
    <div class="mm-setup-stack">
        <x-mm.panel>
            <h2 class="mm-setup-title">Manage Module</h2>
            <form class="form-horizontal" action="{{ isset($module) ? route('modules.update', $module->id) : route('modules.store') }}" method="post">
            @csrf
                @if (isset($module))
                    @method('PUT')
                @endif
                @include('partials._alert_message')

                <div class="row">

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1-1"> Module Name </label>
                        <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                            <input type="text" class="form-control" name="name"
                                value="{{ isset($module) ? $module->name : old('name')  }}" placeholder="Module Name">

                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                        <div class="col-xs-12 col-sm-6">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-save"></i> {{ isset($module) ? 'Update' : 'Save'}}</button>
                            <button class="btn btn-gray" type="Reset"> <i class="fa fa-refresh"></i> Reset </button>
                        </div>
                    </div>
                </div>

            </form>
        </x-mm.panel>

        <x-mm.panel>
            <x-mm.table-scroll label="Modules">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover" >
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Module Name</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                         @foreach ($modules as $key => $setting)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $setting->name }}</td>
                                <td class="text-center"><a href="{{ route('active.deactive.module', $setting->id) }}" class="badge badge-{{ $setting->status == 1 ? 'success' : 'danger' }}">{{ $setting->status == 1 ? 'Active' : 'De-Active' }}</a></td>

                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        <a href="{{ route('modules.edit',$setting->id) }}" class="btn btn-sm btn-success" title="Edit">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <button type="button" onclick="delete_check({{ $setting->id }})" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>

                                    <form action="{{ route('modules.destroy',$setting->id)}}" id="deleteCheck_{{ $setting->id }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-mm.table-scroll>
            @include('partials._paginate', ['data' => $modules])
        </x-mm.panel>
    </div>
</x-mm.page>

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
        jQuery(function ($) {
            $('#dynamic-table').DataTable({
                "ordering": false,
                "bPaginate": false,
                "lengthChange": false,
                "info": false
            });
        })
    </script>

@stop
