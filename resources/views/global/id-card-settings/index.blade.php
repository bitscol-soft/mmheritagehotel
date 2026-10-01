
@extends('layouts.master')
@section('title', 'Id Card Setting')
@section('page-header')
    <i class="fa fa-list"></i> Id Card Setting
@stop
@section('css')

@stop


@section('content')

    <div class="page-header">

        @if (hasPermission("id.card.settings.create", $slugs))
            <a class="btn btn-xs btn-info" href="{{ route('id-card-settings.create') }}" style="float: right; margin: 0 2px;">
                <i class="fa fa-plus"></i> Add New
            </a>
        @endif

        <h1>
            @yield('page-header')
        </h1>
    </div>

    @include('partials._alert_message')

    <div class="row">
        <div class="col-xs-12">

            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <table id="dynamic-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Company Name</th>
                            <th>Address</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Logo</th>
                            <th>Signature</th>
                            <th width="12%" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($idCardSettings as $key => $setting)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ optional($setting->company)->name }}</td>
                                <td>{{ $setting->address }}</td>
                                <td>{{ $setting->mobile }}</td>
                                <td>{{ $setting->email }}</td>
                                <td class="text-center">
                                    @if(file_exists($setting->logo))
                                        <img src="{{ $setting->logo }}" alt="{{ optional($setting->company)->name }}" style="width: 60px">
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(file_exists($setting->signature))
                                        <img src="{{ $setting->signature }}" alt="{{ optional($setting->company)->name }}" style="width: 60px">
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">

                                        @if (hasPermission("id.card.settings.edit", $slugs))
                                            <a href="{{ route('id-card-settings.edit', $setting->id) }}" class="btn btn-sm btn-success"
                                            title="Edit">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                        @endif

                                        {{-- @if (hasPermission("company.infos.delete", $slugs))
                                            <button type="button" onclick="delete_check({{ $setting->id }})"
                                                    class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        @endif --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>


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
