@extends('layouts.master')
@section('title', 'Categories')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Category List" description="Product categories.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh Data</a>
        @if ((hasPermission("account-categories.create", $slugs)))
            <a class="mm-button" href="{{route('categories.create')}}"><i class="fa fa-plus"></i> Create New</a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- LIST -->
        <div class="row" style="width: 100%; margin: 0 !important;">
            <div class="col-sm-12 px-4">
                <x-mm.table-scroll label="Category List">
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead>
                            <tr class="table-header-bg">
                                <th class="text-center" style="color: white !important;" width="8%">Sl</th>
                                <th class="pl-3" style="color: white !important;" >Name</th>
                                <th class="text-center" style="color: white !important;" width="15%">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($categories as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="pl-3">{{ $item->name }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-corner">
                                            @include('partials._user-log', ['data' => $item])

                                            @if ((hasPermission("account-categories.edit", $slugs)))
                                                <a href="{{route('categories.edit', $item->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i></a>
                                            @endif

                                            @if ((hasPermission("account-categories.delete", $slugs)))
                                                <a href="#" onclick="delete_item('{{ route('categories.destroy', $item->id) }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                            @endif
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

