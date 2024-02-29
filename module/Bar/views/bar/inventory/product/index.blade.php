@extends('layouts.master')
@section('title', 'Product List')

@section('page-header')
    <i class="fa fa-bars"></i> Product List
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .file {
            visibility: hidden;
            position: absolute;
        }
    </style>
@stop


@section('content')
    <div class="row">



        <div class="col-sm-12">
            <div class="widget-box">
                <div class="widget-header">
                    <h4 class="widget-title"> @yield('page-header')</h4>
                    @if (hasPermission('bar.products.create', $slugs))
                        <span class="widget-toolbar">
                            <a href="{{ route('bar.products.create', ['type' => 'upload']) }}">
                                <i class="fa fa-upload"></i> Upload Product
                            </a>
                            <a href="{{ route('bar.products.create') }}">
                                <i class="fa fa-plus-circle"></i> Add New Product
                            </a>
                        </span>
                    @endif

                </div>
                <div class="widget-body">
                    <div class="widget-main">

                        <x-alert-message />

                        @include('bar.inventory.product._inc.filter')

                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="3%">Sl</th>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Product ID</th>
                                            <th class="text-center">Barcode</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Pack</th>
                                            <th class="text-center">Supplier</th>
                                            <th class="text-center">Sale Price</th>
                                            <th class="text-center">Vat</th>
                                            <th class="text-center" width="3%">Status</th>
                                            <th width="7%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            <tr class="odd gradeX">
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $product->name }}
                                                    <sup>
                                                        <span
                                                            class="label label-info label-white middle">{{ optional($product->unit)->name }}</span>
                                                    </sup>
                                                </td>
                                                <td class="text-center">
                                                    {{ $product->productId }}
                                                </td>
                                                <td class="text-left">
                                                    @if ($product->barcode)
                                                        {!! DNS1D::getBarcodeHTML(123456, 'C128') !!}
                                                    @endif
                                                    <strong>{{ $product->barcode }}</strong>
                                                </td>

                                                <td class="text-center">
                                                    {{ optional($product->category)->name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $product->pack_size }}
                                                    <p class="{{ optional($product->pack_unit)->name }}"></p>
                                                </td>
                                                <td class="text-center">
                                                    {{ optional($product->supplier)->name }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $product->sale_price }}
                                                </td>
                                                <td class="text-center">
                                                    ৳ {{ number_format($product->vat_amount, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    {{ status($product->status) }}
                                                </td>

                                                <td class="text-center">

                                                    <div class="btn-group btn-corner">
                                                        @if (hasPermission('bar.products.edit', $slugs))
                                                            <a href="{{ route('bar.products.edit', $product->id) }}"
                                                                class="btn btn-xs btn-success">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>
                                                        @endif
                                                        {{-- @if (hasPermission('bar.inventories.delete', $slugs)) --}}
                                                        @if (hasPermission('bar.products.delete', $slugs))
                                                            <a class="btn btn-danger btn-xs" href="#"
                                                                onclick="delete_item(`{{ route('bar.products.destroy', $product->id) }}`)">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        @endif

                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <x-no-table-record />
                                        @endforelse
                                    </tbody>

                                </table>

                                <x-paginate :data="$products" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('js')
@endsection
