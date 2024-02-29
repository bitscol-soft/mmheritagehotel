@extends('layouts.master')
@section('title', 'Products')
@section('page-header')
    <i class="fa fa-list"></i> Product List
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

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{ request()->url() }}" class="dt-button btn btn-white btn-primary btn-bold" title="Refresh Data" data-toggle="tooltip">
                                    <span>
                                        <i class="fa fa-refresh bigger-110"></i>
                                    </span>
                                </a>

                                @if ((hasPermission("account-products.create", $slugs)))
                                    <a href="{{route('products.create')}}" class="dt-button btn btn-white btn-info btn-bold" title="Create New" data-toggle="tooltip" tabindex="0" aria-controls="dynamic-table">
                                        <span>
                                            <i class="fa fa-plus bigger-110"></i>
                                        </span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space"></div>

                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12 px-4">
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header-bg">
                                    <th class="text-center" style="color: white !important;" width="5%">S/L</th>
                                    <th class="pl-3" style="color: white !important;" >Name</th>
                                    <th class="pl-3" style="color: white !important;" >Category</th>
                                    <th class="pl-3" style="color: white !important;" >Unit</th>
                                    <th class="text-right" style="color: white !important;" >Opening Qty</th>
                                    <th class="text-right" style="color: white !important;" >Purchase Price</th>
                                    <th class="text-right" style="color: white !important;" >Selling Price</th>
                                    <th class="text-center" style="color: white !important;" width="10%">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($products as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="pl-3">
                                            <p>{{ $item->name }}</p>
                                            <p>{{ Str::words($item->description, 100, '...') }}</p>
                                        </td>
                                        <td class="pl-3">{{ optional($item->category)->name }}</td>
                                        <td class="pl-3">{{ optional($item->unit)->name }}</td>
                                        <td class="text-right">{{ $item->opening_quantity }}</td>
                                        <td class="text-right">{{ $item->purchase_price }}</td>
                                        <td class="text-right">{{ $item->selling_price }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-corner">
                                                @include('partials._user-log', ['data' => $item])

                                                @if ((hasPermission("account-products.edit", $slugs)))
                                                    <a href="{{route('products.edit', $item->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i></a>
                                                @endif

                                                @if ((hasPermission("account-products.delete", $slugs)))
                                                    <a href="#" onclick="delete_item('{{ route('products.destroy', $item->id) }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
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


