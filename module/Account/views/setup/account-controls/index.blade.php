@extends('layouts.master')
@section('title','Account Controls')

@section('page-header')
    <i class="fa fa-info-circle"></i> Account Controls
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush


@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Account Controls" description="Account controls grouped by account group.">
    <x-slot name="actions">
        {{-- <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
            <div class="pull-right tableTools-container" style="margin: 0 !important">
                <div class="dt-buttons btn-overlap btn-group">
                    <a href="{{request()->url()}}" class="dt-button btn btn-white btn-primary btn-bold" title="Refresh Data" data-toggle="tooltip">
                        <span>
                            <i class="fa fa-refresh bigger-110"></i>
                        </span>
                    </a>
                    @if ((hasPermission("account.controls.delete", $slugs)))
                        <a href="{{route('account-controls.create')}}" class="dt-button btn btn-white btn-info btn-bold" title="Create New" data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
                            <span>
                                <i class="fa fa-plus bigger-110"></i>
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        </div> --}}
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- LIST -->
        <div class="row" style="width: 100%; margin: 0 !important;">
            <div class="col-sm-12 px-4">
                <x-mm.table-scroll label="Account Controls">
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead>
                            <tr class="table-header-bg">
                                <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                <th class="pl-3" style="color: white !important;" width="20%">Account Group</th>
                                <th class="pl-3" style="color: white !important;">Name</th>
                                <th class="text-center" style="color: white !important;" width="15%">Status</th>
                                <th class="text-center" style="color: white !important;" width="15%">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($accountControls as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="pl-3">{{ optional($item->accountGroup)->name }}</td>
                                    <td class="pl-3">{{ $item->name }}</td>
                                    <td class="text-center">
                                        {!! $item->status == 1 ? '<span class="label label-info">Active</span>' : '<span class="label label-warning">Inactive</span>' !!}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-corner">
                                            @include('partials._user-log', ['data' => $item])

                                            {{-- @if ((hasPermission("account.controls.edit", $slugs)))
                                                <a href="{{route('account-controls.edit', $item->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i></a>
                                            @endif

                                            @if ((hasPermission("account.controls.delete", $slugs)))
                                                <a href="#" onclick="delete_item('{{ route('account-controls.destroy', $item->id) }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                            @endif
                                        --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-mm.table-scroll>
            </div>
        </div>
    </x-mm.panel>
</x-mm.page>

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


