@extends('layouts.master')
@section('title','Add New Account Type')
@section('page-header')
    <i class="fa fa-gears"></i> Add New Account Type
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup" title="Account types" description="Payment accounts available when collecting money.">
    @include('partials._alert_message')

    <div class="mm-setup-split">
        <x-mm.panel>
            <x-mm.table-scroll label="Account types">
                <table id="data-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($account as $key => $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $data->name }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-corner">
                                    <a href="{{ route('account-type.edit', $data->id) }}" class="btn btn-xs btn-sm btn-success" title="Edit">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <button type="button" onclick="delete_check({{ $data->id }})" class="btn btn-xs btn-sm btn-danger" title="Delete">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </div>

                                <form action="{{ route('account-type.destroy',$data->id)}}" id="deleteCheck_{{ $data->id }}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </x-mm.table-scroll>
        </x-mm.panel>

        <x-mm.panel>
            <h2 class="mm-setup-title">Add account type</h2>
            <form class="form-horizontal" id="companyForm" action="{{ route('account-type.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-sm-12">

                        <div class="form-group">
                            <label class="col-sm-4 control-label" for="form-field-1-1"> Account Type Name </label>

                            <div class="col-xs-12 col-sm-7 @error('name') has-error @enderror">
                                <input type="text" class="form-control input-sm" name="name" value="{{ old('name') }}" placeholder="Account Type Name">

                                @error('name')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-actions center" style="text-align: right !important;">
                    <button type="submit" class="mm-button">
                        <i class="ace-icon fa fa-save icon-on-right bigger-110"></i>
                        Save
                    </button>
                </div>
            </form>
        </x-mm.panel>
    </div>
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
