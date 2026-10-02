@extends('layouts.master')
@section('title', 'Unit List')

@section('page-header')
    <i class="fa fa-bars"></i> Unit List
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
@include('bar.inventory.units.create-modal')
<x-mm.page class="mm-hotel-setup mm-bar mm-rst mm-rst-inv" title="Units" description="Measurement units for bar products.">
    <x-slot name="actions">
        @if (hasPermission('pharmacy.view', $slugs))
                <a class="mm-button" href="#modal-dialog" data-toggle="modal">
                    <i class="fa fa-plus-circle"></i> Add New Unit
                </a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        <x-alert-message />

        <div class="row">
            <div class="col-sm-12">
                <x-mm.table-scroll label="Units">
                    <table id="data-table" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th width="5%">Sl</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th width="10%">Status</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $item)
                                <tr class="odd gradeX">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->type == 'retail' ? 'Big Unit' : 'Small Unit' }}</td>
                                    <td>{{ status($item->status) }}</td>
                                    <td class="text-center">

                                        <div class="btn-group btn-corner">


                                            @if (hasPermission('pharmacy.edit', $slugs))
                                                <a href="#modal-dialog{{ $item->id }}" data-toggle="modal"
                                                    class="btn btn-sm btn-success" title="Edit">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            @endif


                                            @if (hasPermission('pharmacy.delete', $slugs))
                                                <button type="button"
                                                    onclick="delete_item(`{{ route('bar.product-units.destroy', $item->id) }}`)"
                                                    class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </td>

                                </tr>
                                @include('bar.inventory.units.edit-modal')
                            @endforeach
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
