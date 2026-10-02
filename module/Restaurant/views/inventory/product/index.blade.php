@extends('layouts.master')
@section('title', 'Product List')

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

<x-mm.styles />
<x-mm.page class="mm-report mm-rst mm-rst-inventory mm-rst-inv" title="Products" description="Restaurant products with stock type, price and status.">
    <x-slot name="actions">
        @if (hasPermission('rst.products.create', $slugs))
                <a href="{{ route('rst.products.create', ['type' => 'upload']) }}" class="mm-button">
                    <i class="fa fa-upload"></i> Upload Product
                </a>
                <a href="{{ route('rst.products.create') }}" class="mm-button mm-button-secondary">
                    <i class="fa fa-plus-circle"></i> Add New Product
                </a>
        @endif

    </x-slot>
    <x-mm.panel class="mm-report-filter">
        @include('inventory.product._inc.filter')
    </x-mm.panel>

    <x-mm.panel>
        <x-alert-message />

        <div class="row">
            <x-mm.table-scroll label="Products">
                <table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%">SL</th>
                            <th class="text-center">Product ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Barcode</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Supplier</th>
                            <th class="text-center">Unit</th>
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
                                    {{ $product->productId }}
                                </td>
                                <td class="text-center">
                                    {{ $product->name }}
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
                                    {{ optional($product->supplier)->name }}
                                </td>
                                <td class="text-center">
                                    {{ optional($product->unit)->name }}
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
                                        <a href="{{ route('rst.products.edit', $product->id) }}"
                                            class="btn btn-xs btn-success">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <a class="btn btn-danger btn-xs" href="#"
                                            onclick="delete_item(`{{ route('rst.products.destroy', $product->id) }}`)">
                                            <i class="fa fa-trash-o"></i>
                                        </a>

                                    </div>

                                </td>
                            </tr>
                        @empty
                            <x-no-table-record />
                        @endforelse

                    </tbody>
                </table>
            </x-mm.table-scroll>

            <x-paginate :data="$products" />
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
