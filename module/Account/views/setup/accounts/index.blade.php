@extends('layouts.master')

@section('title', 'Accounts')

@section('page-header')
    <i class="fa fa-info-circle"></i> Chart Of Accounts
@stop


@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush


@section('content')
    <div class="row">
        <div class="col-sm-12">

        @include('partials._alert_message')

            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">


                <!-- heading -->
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller">
                        <a href="{{request()->url()}}">
                            <i class="fa fa-refresh bigger-110"></i> Refresh
                        </a>
                    </div>

                    <div class="widget-toolbar border smaller">
                        @if(hasPermission('account.create', $slugs))
                            <a href="{{ route('accounts.create') }}" >
                                <i class="fa fa-plus bigger-110"></i> Add New
                            </a>
                        @endif
                    </div>
                </div>



                <div class="space"></div>

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12">
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header-bg">
                                    <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                    <th class="pl-3" style="color: white !important;" width="20%">Account Group</th>
                                    <th class="pl-3" style="color: white !important;" width="20%">Account Control</th>
                                    <th class="pl-3" style="color: white !important;" width="20%">Account Subsidiary</th>
                                    <th class="pl-3" style="color: white !important;" >Account Name</th>
                                    <th class="pl-3" style="color: white !important;" >Opening</th>
                                    <th class="text-center" style="color: white !important;" width="15%">Status</th>
                                    <th class="text-center" style="color: white !important;" width="12%">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($accounts as $item)

                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="pl-3">{{ optional($item->accountGroup)->name }}</td>
                                        <td class="pl-3">{{ optional($item->accountControl)->name }}</td>
                                        <td class="pl-3">{{ optional($item->accountSubsidiary)->name }}</td>
                                        <td class="pl-3">{{ $item->name }}</td>
                                        <td class="pl-3">{{ $item->opening_balance }}</td>
                                        <td class="text-center">
                                            {!! $item->status == 1 ? '<span class="label label-info">Active</span>' : '<span class="label label-warning">Inactive</span>' !!}
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('partials._user-log', ['data' => $item])

                                                @if($item->id > 85 && $item->accountType == null)

                                                    @if ($item->id == 1000 || $item->id == 1001 || $item->id == 1002 || $item->id == 1003)

                                                    @else
                                                        @if(hasPermission('account.edit', $slugs))
                                                            <a href="{{route('accounts.edit', $item->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i></a>
                                                        @endif

                                                        @if(hasPermission('account.delete', $slugs))
                                                            <a href="#" onclick="delete_item(`{{ route('accounts.destroy', $item->id) }}`)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                                        @endif
                                                    @endif

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


