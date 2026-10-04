@extends('layouts.master')
@section('title', 'Products')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Product List" description="Products with price and stock settings.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh Data</a>
        @if ((hasPermission("account-products.create", $slugs)))
            <a class="mm-button" href="{{route('products.create')}}"><i class="fa fa-plus"></i> Create New</a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- LIST -->
        <div class="row" style="width: 100%; margin: 0 !important;">
            <div class="col-sm-12 px-4">
                <x-mm.table-scroll label="Product List">
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
                                                <a href="#" onclick="delete_item('{{ route('products.destroy', $item->id) }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
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

