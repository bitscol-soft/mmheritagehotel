@extends('layouts.master')
@section('title', 'Unit List')

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
@include('inventory.units.create-modal')

<x-mm.page class="mm-hotel-setup mm-rst mm-rst-inv" title="Units" description="Measurement units for restaurant products.">
    <x-slot name="actions">
        @if (hasPermission('resturant.inventories.create', $slugs))
                <a href="#modal-dialog" data-toggle="modal" class="mm-button">
                    <i class="fa fa-plus-circle"></i> Add New Unit
                </a>
        @endif

    </x-slot>
    <x-mm.panel>
        <x-alert-message />

                <x-mm.table-scroll label="Units">
                    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th>Name</th>
                                @if (setting('mother_inventory'))
                                <th>Type</th>
                                @endif
                                <th width="10%">Status</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $item)
                                <tr class="odd gradeX">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    @if (setting('mother_inventory'))
                                    <td>{{ $item->is_bar ? 'Bar' : 'Restaurant' }}</td>
                                    @endif
                                    <td>{{ status($item->status) }}</td>
                                    <td class="text-center">

                                        <div class="btn-group btn-corner">

                                            @if (hasPermission('resturant.inventories.edit', $slugs))
                                                <a href="#modal-dialog{{ $item->id }}" data-toggle="modal"
                                                    class="btn btn-sm btn-success" title="Edit">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            @endif

                                            @if (hasPermission('resturant.inventories.delete', $slugs))
                                                <button type="button"
                                                    onclick="delete_item(`{{ route('rst.product-units.destroy', $item->id) }}`)"
                                                    class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </td>

                                </tr>
                                @include('inventory.units.edit-modal')
                            @endforeach
                        </tbody>
                    </table>
                </x-mm.table-scroll>

    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
