@extends('layouts.master')
@section('title',' Manage Parent Permission')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup mm-perm" title="Parent permissions" description="Group related permissions under a sub module.">
    <div class="mm-setup-stack">
        <x-mm.panel>
            <h2 class="mm-setup-title">Manage Parent Permission</h2>
            <form class="form-horizontal" action="{{ isset($parentPermission) ? route('parent-permissions.update', $parentPermission->id) : route('parent-permissions.store') }}" method="post">
            @csrf
                @if (isset($parentPermission))
                    @method('PUT')
                @endif
                @include('partials._alert_message')

                <div class="row">

                    <div class="form-group col-sm-12">
                        <label class="col-sm-3 control-label" for="form-field-1-1"> Submodule </label>
                        <div class="col-xs-12 col-sm-8 @error('submodule_id') has-error @enderror">
                            <select name="submodule_id" id="form-field-select-3" data-placeholder="Select" class="form-control chosen-select">
                                <option value=""> - Select - </option>
                                @if (isset($parentPermission))
                                    @foreach($submodules as $id => $submodule)
                                        <option value="{{ $id }}" {{ $id == $parentPermission->submodule_id ? 'selected' : '' }}>{{ $submodule }}</option>
                                    @endforeach
                                @else 
                                    @foreach($submodules as $id => $submodule)
                                        <option value="{{ $id }}" {{ $id == old('submodule_id') ? 'selected' : '' }}>{{ $submodule }}</option>
                                    @endforeach
                                @endif

                            </select>

                            @error('submodule_id')
                            <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="form-field-1-1"> Parent Permission Name </label>
                        <div class="col-xs-12 col-sm-8 @error('name') has-error @enderror">
                            <input type="text" class="form-control" name="name"
                                value="{{ isset($parentPermission) ? $parentPermission->name : old('name')  }}" placeholder="Parent Permission Name">

                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputError" class="col-xs-12 col-sm-3 col-md-3 control-label"></label>
                        <div class="col-xs-12 col-sm-6">
                            <button type="submit" class="btn btn-success btn-sm"> <i class="fa fa-save"></i> {{ isset($parentPermission) ? 'Update' : 'Save'}}</button>
                            <button class="btn btn-gray btn-sm" type="Reset"> <i class="fa fa-refresh"></i> Reset </button>
                        </div>
                    </div>
                </div>

            </form>
        </x-mm.panel>

        <x-mm.panel>
            <x-mm.table-scroll label="Parent permissions">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Submodule</th>
                            <th>Parent Permission</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                         @foreach ($parentPermissions as $key => $parentPermission)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $parentPermission->submodule->name }}</td>
                                <td>{{ $parentPermission->name }}</td>

                                <td class="text-center">
                                    <div class="btn-group btn-corner">
                                        <a href="{{ route('parent-permissions.edit',$parentPermission->id) }}" class="btn btn-xs btn-success" title="Edit">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <button type="button" onclick="delete_check({{ $parentPermission->id }})" class="btn btn-xs btn-danger" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>

                                    <form action="{{ route('parent-permissions.destroy',$parentPermission->id)}}" id="deleteCheck_{{ $parentPermission->id }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-mm.table-scroll>
            @include('partials._paginate', ['data' => $parentPermissions])
        </x-mm.panel>
    </div>
</x-mm.page>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

    

    <!--  Select Box Search-->
    <script type="text/javascript">
        jQuery(function($){
            if(!ace.vars['touch']) {
                $('.chosen-select').chosen({allow_single_deselect:true});
            }
        });

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