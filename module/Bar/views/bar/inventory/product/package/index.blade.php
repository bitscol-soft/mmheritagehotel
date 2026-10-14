@extends('layouts.master')
@section('title', 'Product Package List')

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
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv" title="Product packages" description="Packages made of several bar products.">
    <x-slot name="actions">
        @if (hasPermission('bar.packages.create', $slugs))
                <a class="mm-button" href="{{ route('bar.packages.create') }}">
                    <i class="fa fa-plus-circle"></i> Create New Packacge
                </a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        <x-alert-message />

        <div class="row">
            <div class="col-sm-12">
                <x-mm.table-scroll label="Packages">
                    <table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="3%">Sl</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Sale Price</th>
                                <th class="text-center"  width="15%">Available Quantity</th>
                                <th width="15%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($packages as $package)
                                <tr class="odd gradeX">
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="text-center">
                                        {{ $package->name }}
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($package->price, 2, '.', '') }}
                                    </td>

                                    <td class="text-center">
                                        0
                                    </td>

                                    <td class="text-center">

                                        <div class="btn-group btn-corner">
                                            {{-- @if (hasPermission('bar.packages.edit', $slugs))
                                                <a href="{{ route('bar.packages.edit', $package->id) }}"
                                                    class="btn btn-xs btn-success">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                            @endif --}}
                                            @if (hasPermission('bar.packages.delete', $slugs))
                                                <a class="btn btn-danger btn-xs" href="javascript:void(0)"
                                                    onclick="delete_item(`{{ route('bar.packages.destroy', $package->id) }}`)">
                                                    <i class="fa fa-trash-o"></i>
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
                </x-mm.table-scroll>

                <x-paginate :data="$packages" />
            </div>
        </div>
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')
@endsection
