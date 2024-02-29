@extends('layouts.master')
@section('title', 'Product Upload List')

@section('page-header')
    <i class="fa fa-bars"></i> Product Upload List
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

        <div class="page-header">

            <button class="btn btn-xs btn-danger" onclick="delete_item(`{{ route('rst.product.upload-list.delete') }}`)"
                style="float: right; margin: 0 2px;" type="button"> <i class="fa fa-trash"></i> Delete All From This List
            </button>

            <a href="{{ route('rst.products.create', ['type' => 'upload']) }}" class="btn btn-xs btn-pink"
                style="float: right; margin: 0 2px;"><i class="fa fa-upload"></i> Upload CSV</a>

            <a href="{{ route('rst.products.index') }}" class="btn btn-xs btn-success"
                style="float: right; margin: 0 2px;"><i class="fa fa-list-ol"></i> Product List</a>

            <form action="{{ route('rst.product.add-confirm-list') }}" method="post">
                @csrf
                <button class="btn btn-xs btn-info" style="float: right; margin: 0 2px;">
                    <i class="fa fa-plus"></i>
                    Add First 50 Row Confirm List
                </button>
            </form>

            <h1>
                @yield('page-header') <span class="badge badge-success">{{ $products->total() }}</span>
            </h1>
        </div>


        <div class="col-sm-12">
            <div class="widget-box">

                <div class="widget-body">
                    <div class="widget-main">
                        @include('partials._alert_message')

                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered">

                                <thead>
                                    <tr>
                                        <th class="text-center" width="3%">Sl</th>
                                        <th class="text-center">Product</th>
                                        <th class="text-center">Batch Number</th>
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Generic </th>
                                        <th class="text-center">Medicine Type </th>
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center">Pack Size</th>
                                        <th class="text-center">Small Unit</th>
                                        <th class="text-center">Big Unit</th>
                                        <th class="text-center">Purchase Price</th>
                                        <th class="text-center">Retail Purchase Price</th>
                                        <th class="text-center">Sale Price</th>
                                        <th class="text-center">Retail Sale Price</th>
                                        <th class="text-center">Alert Qty</th>
                                        <th class="text-center">Big Qty</th>
                                        <th class="text-center">Small Qty</th>
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
                                            </td>
                                            <td class="text-center">
                                                {{ $product->batch_number }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->category }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->generic }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->medicine_type }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->supplier }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->pack_size }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->small_unit }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->big_unit }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->purchase_price }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->retail_purchase_price }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->sale_price }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->retail_sale_price }}
                                            </td>

                                            <td class="text-center">
                                                {{ $product->stock_limitation }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->big_quantity }}
                                            </td>
                                            <td class="text-center">
                                                {{ $product->small_quantity }}
                                            </td>

                                            <td class="text-center">

                                                <div class="btn-group btn-corner">
                                                    <a href="{{ route('rst.product-uploads.edit', $product->id) }}"
                                                        class="btn btn-xs btn-success">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-xs" href="#"
                                                        onclick="delete_item(`{{ route('rst.product-uploads.destroy', $product->id) }}`)">
                                                        <i class="fa fa-trash"></i>
                                                    </a>

                                                </div>

                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="30" class="text-center">
                                                <strong class="text-danger"
                                                    style="font-size: 18px; background:rgba(140, 212, 212, 0.467)">No Record
                                                    Found !</strong>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                        {{ $products->links() }}

                    </div>
                </div>
            </div>


        </div>
    </div>


@endsection

@section('js')
@endsection
