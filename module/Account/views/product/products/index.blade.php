@extends('layouts.master')
@section('title', 'Products')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}"/>
@endpush

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-acc mm-rst mm-rst-inv" title="Product List" description="Products with price and stock settings.">
    <x-slot name="actions">
        <a class="mm-button mm-button-secondary" href="{{ request()->url() }}"><i class="fa fa-refresh"></i> Refresh Data</a>
        @if ((hasPermission("account-products.create", $slugs)))
            <a class="mm-button" href="{{route('products.create')}}"><i class="fa fa-plus"></i> Create New</a>
        @endif
    </x-slot>
    <x-mm.panel class="tw-p-4">
        @include('partials._alert_message')
        <!-- LIST -->
            <x-mm.data-table :columns="[
                ['label' => 'S/L', 'align' => 'center', 'width' => '5%'],
                ['label' => 'Name'],
                ['label' => 'Category'],
                ['label' => 'Unit'],
                ['label' => 'Opening Qty', 'align' => 'right'],
                ['label' => 'Purchase Price', 'align' => 'right'],
                ['label' => 'Selling Price', 'align' => 'right'],
                ['label' => 'Actions', 'align' => 'center', 'width' => '10%'],
            ]" id="data-table" table-class="table table-bordered table-striped" label="Product List">
                @foreach($products as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="pl-3">
                            <p>{{ $item->name }}</p>
                            <p>{{ Str::words($item->description, 100, '...') }}</p>
                        </td>
                        <td class="pl-3">{{ optional($item->category)->name }}</td>
                        <td class="pl-3">{{ optional($item->unit)->name }}</td>
                        <td class="text-right">{{ $item->opening_quantity }}</td>
                        <td class="text-right">{{ $item->purchase_price }}</td>
                        <td class="text-right">{{ $item->selling_price }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-corner">
                                @include('partials._user-log', ['data' => $item])

                                @if ((hasPermission("account-products.edit", $slugs)))
                                    <a href="{{route('products.edit', $item->id)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square"></i></a>
                                @endif

                                @if ((hasPermission("account-products.delete", $slugs)))
                                    <a href="#" onclick="delete_item('{{ route('products.destroy', $item->id) }}')" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-mm.data-table>
    </x-mm.panel>
</x-mm.page>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
@endsection

