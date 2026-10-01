@extends('layouts.master')

@section('title', 'Customers')


@section('page-header')
    <i class="fa fa-info-circle"></i> Customers
@stop


@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush


@section('content')
    <div class="row">
        <div class="col-sm-12">

            @include('partials._alert_message')

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar">
                        <a href="{{ route('acc-customers.index') }}" ><i class="fa fa-refresh"></i> Refresh</a>
                    </div>

                    <div class="widget-toolbar">
                        <a href="{{ route('acc-customers.create') }}" ><i class="fa fa-plus"></i> Create</a>
                    </div>

                </div>
                <div class="space"></div>

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12 px-4 mb-2">
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header-bg">
                                    <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                    <th class="pl-3" style="color: white !important;" >Name</th>
                                    <th class="pl-3" style="color: white !important;" >Mobile</th>
                                    <th class="pl-3" style="color: white !important;" >Email</th>
                                    <th class="text-center" style="color: white !important;" width="15%">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($customers as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="pl-3">{{ $item->name }}</td>
                                        <td class="pl-3">{{ $item->mobile }}</td>
                                        <td class="pl-3">{{ $item->email }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('partials._user-log', ['data' => $item])

                                                @if ((hasPermission("account-customers.edit", $slugs)))
                                                    <a href="{{route('acc-customers.edit', $item->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i></a>
                                                @endif

                                                @if ((hasPermission("acc-customers.delete", $slugs)))
                                                    <a href="#" onclick="delete_item('{{ route('acc-customers.destroy', $item->id) }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    
    
    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>


    <script type="text/javascript">
        jQuery(function($) {
            $('#data-table').DataTable({
                "ordering": false,
                "bPaginate": true,
                "lengthChange": false,
                "info": false,
                "pageLength": 25
            });
        })
    </script>
@endsection


