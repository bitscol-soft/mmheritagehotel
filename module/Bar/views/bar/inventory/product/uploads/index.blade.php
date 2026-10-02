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

<x-mm.styles />
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv" title="Uploaded products" description="Review the uploaded products, then move them to the confirm list.">
    <x-slot name="actions">
        <button class="mm-button mm-button-secondary" onclick="delete_item(`{{ route('bar.product.upload-list.delete') }}`)" type="button"> <i class="fa fa-trash-o"></i> Delete All From This List</button>
        <a href="{{ route('bar.products.create', ['type' => 'upload']) }}" class="mm-button mm-button-secondary"><i class="fa fa-upload"></i> Upload CSV</a>
        <a href="{{ route('bar.products.index') }}" class="mm-button"><i class="fa fa-list-ol"></i> Product List</a>
        <form action="{{ route('bar.product.add-confirm-list') }}" method="post">
            @csrf
            <button class="mm-button">
                <i class="fa fa-plus"></i>
                Add First 50 Row Confirm List
            </button>
        </form>
    </x-slot>
    <x-mm.panel class="tw-p-4">
        <p class="tw-m-0 tw-mb-3 tw-text-sm tw-text-muted">Rows waiting: <span class="badge badge-success">{{ $products->total() }}</span></p>
        @include('partials._alert_message')

        <div class="table-responsive">
            <x-mm.table-scroll label="Uploaded products">
                <table id="datatable" class="table table-striped table-bordered">

                    <thead>
                        <tr>
                            <th class="text-center" width="3%">Sl</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">Barcode</th>
                            <th class="text-center">Category</th>
                            <th class="text-center">Supplier </th>
                            <th class="text-center">Unit </th>
                            <th class="text-center">Sale Price</th>
                            <th class="text-center">Opening</th>
                            <th class="text-center">Is Bar</th>
                            <th class="text-center">Is Matrial</th>
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

                                    <div class="btn-group btn-corner">
                                        <a href="{{ route('rst.product-uploads.edit', $product->id) }}"
                                            class="btn btn-xs btn-success">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <a class="btn btn-danger btn-xs" href="#"
                                            onclick="delete_item(`{{ route('rst.product-uploads.destroy', $product->id) }}`)">
                                            <i class="fa fa-trash-o"></i>
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
            </x-mm.table-scroll>
        </div>

        {{ $products->links() }}
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
