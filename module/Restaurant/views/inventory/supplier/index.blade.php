@extends('layouts.master')
@section('title', 'Supplier List')

@section('content')

<x-mm.styles />
<x-mm.page class="mm-hotel-setup mm-rst mm-rst-inv" title="Suppliers" description="Restaurant suppliers.">
    <x-slot name="actions">
        @if (hasPermission('rst.suppliers.view', $slugs))
                <a href="#modal-dialog" data-toggle="modal" class="mm-button">
                    <i class="fa fa-plus-circle"></i> Add New Supplier
                </a>
        @endif

    </x-slot>
    <x-mm.panel>
        @include('inventory.supplier.create-modal')
        @include('partials._alert_message')

                <x-mm.table-scroll label="Suppliers">
                    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                @if (setting('mother_inventory'))
                                    <th>Type</th>
                                @endif
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($suppliers as $item)
                                <tr class="odd gradeX">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->email }}</td>
                                    @if (setting('mother_inventory'))
                                        <td>{{ $item->is_bar ? 'Bar' : 'Restaurant' }}</td>
                                    @endif
                                    <td class="text-center">

                                        <div class="btn-group btn-corner">

                                            @if (hasPermission('rst.suppliers.edit', $slugs))
                                                <a href="#modal-dialog{{ $item->id }}" data-toggle="modal"
                                                    class="btn btn-sm btn-success" title="Edit">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            @endif

                                            @if (hasPermission('rst.suppliers.destroy', $slugs))
                                                <button type="button"
                                                    onclick="delete_item(`{{ route('rst.suppliers.destroy', $item->id) }}`)"
                                                    class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif

                                        </div>
                                        @include('inventory.supplier.edit-modal')
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

    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
