@extends('layouts.master')
@section('title', 'Supplier List')

@section('page-header')
    <i class="fa fa-bars"></i> Supplier List
@stop


@section('content')

<x-mm.styles />
<x-mm.page class="mm-hotel-setup mm-bar mm-rst mm-rst-inv" title="Suppliers" description="Bar suppliers.">
    <x-slot name="actions">
        @if (hasPermission('bar.suppliers.view', $slugs))
                <a class="mm-button" href="#modal-dialog" data-toggle="modal">
                    <i class="fa fa-plus-circle"></i> Add New Supplier
                </a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('bar.inventory.supplier.create-modal')
        @include('partials._alert_message')

        <div class="row">
            <div class="col-md-12 container">
                <x-mm.table-scroll label="Suppliers">
                    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
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
                                    <td class="text-center">

                                        <div class="btn-group btn-corner">


                                            @if (hasPermission('bar.suppliers.edit', $slugs))
                                                <a href="#modal-dialog{{ $item->id }}" data-toggle="modal"
                                                    class="btn btn-sm btn-success" title="Edit">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            @endif


                                            @if (hasPermission('bar.suppliers.destroy', $slugs))
                                                <button type="button"
                                                    onclick="delete_item(`{{ route('bar.suppliers.destroy', $item->id) }}`)"
                                                    class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif


                                        </div>
                                        @include('bar.inventory.supplier.edit-modal')
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
