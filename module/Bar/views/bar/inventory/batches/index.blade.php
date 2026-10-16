@extends('layouts.master')
@section('title', 'Product Batches')

@section('content')

<x-mm.styles />
<x-mm.page class="mm-hotel-setup mm-bar mm-rst mm-rst-inv" title="Product Batches" description="Batch or lot numbers used on bar and restaurant products.">
    <x-slot name="actions">
        @if (hasPermission('bar.inventories.create', $slugs))
            <a class="mm-button" href="#modal-dialog" data-toggle="modal">
                <i class="fa fa-plus-circle"></i> Add New Batch
            </a>
        @endif
    </x-slot>

    <x-mm.panel class="tw-p-4">
        @include('bar.inventory.batches.create-modal')
        @include('partials._alert_message')

        <div class="row">
            <div class="col-md-12 container">
                <x-mm.table-scroll label="Product Batches">
                    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th>Batch Number</th>
                                <th width="15%">Products</th>
                                <th width="20%">Created</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($batches as $item)
                                <tr class="odd gradeX">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->batch_number }}</td>
                                    <td>{{ $item->products_count ?? 0 }}</td>
                                    <td>{{ $item->created_at ? fdate($item->created_at, 'd/m/Y') : '' }}</td>
                                    <td class="text-center">

                                        <div class="btn-group btn-corner">

                                            @if (hasPermission('bar.inventories.edit', $slugs))
                                                <a href="#modal-dialog{{ $item->id }}" data-toggle="modal"
                                                    class="btn btn-sm btn-success" title="Edit">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            @endif

                                            @if (hasPermission('bar.inventories.delete', $slugs))
                                                <button type="button"
                                                    onclick="delete_item(`{{ route('bar.product-batches.destroy', $item->id) }}`)"
                                                    class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif

                                        </div>
                                        @include('bar.inventory.batches.edit-modal')
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="30" class="text-center text-danger py-3"
                                        style="background: #eaf4fa80 !important; font-size: 18px">
                                        <strong>No records found!</strong>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-mm.table-scroll>
            </div>
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
