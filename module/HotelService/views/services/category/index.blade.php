@extends('layouts.master')
@section('title', 'Hotel Service List')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@stop

@section('content')
<x-mm.styles />
<x-mm.page class="mm-hotel-setup mm-hs-services" title="Hotel services" description="Services that can be added to a guest's bill, with their prices.">
    @if (hasPermission('service.view', $slugs))
        <x-slot name="actions">
            <a href="#modal-dialog" data-toggle="modal" class="mm-button">
                <i class="fa fa-plus-circle" aria-hidden="true"></i> Add New Service
            </a>
        </x-slot>
    @endif

    @include('partials._alert_message')

    <x-mm.panel>
        <x-mm.data-table :columns="[
            ['label' => 'Sl', 'align' => 'center'],
            ['label' => 'Name', 'align' => 'center'],
            ['label' => 'Price', 'align' => 'center'],
            ['label' => 'Created At', 'align' => 'center'],
            ['label' => 'Updated At', 'align' => 'center'],
            ['label' => 'Action', 'align' => 'center'],
        ]" id="data-table" label="Hotel services" table-class="table table-striped table-bordered nowrap table-bg-color" :sticky="false">
            @php($sl = $services->firstItem())

            @forelse ($services as $item)
                <tr class="odd gradeX">
                    <td>{{ $sl++ }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ number_format($item->price) }}</td>
                    <td class="text-center">
                        {{ $item->created_at }}
                    </td>
                    <td class="text-center">
                        {{ $item->updated_at }}
                    </td>
                    <td class="text-center">

                        <div class="btn-group btn-corner">
                            @if (hasPermission('service.edit', $slugs))
                                <a href="#modal-dialog{{ $item->id }}" data-toggle="modal"
                                    class="btn btn-sm btn-success" title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                            @endif
                            @if (hasPermission('service.delete', $slugs))

                                <button type="button"
                                    onclick="delete_item(`{{ route('hotelservice.services.destroy', $item->id) }}`)"
                                    class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            @endif
                        </div>
                        <!-- #modal-dialog -->
                        @include('services.category.edit-modal')
                        <!-- end edit section -->
                    </td>

                </tr>
            @empty
                <x-no-table-record />
            @endforelse
        </x-mm.data-table>
    </x-mm.panel>

    @include('services.category.add-modal')
</x-mm.page>
@endsection

@section('js')
    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
@endsection
