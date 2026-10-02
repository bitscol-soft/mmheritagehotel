@extends('layouts.master')
@section('title', 'Product Inventory')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <style>
        .bg-dark {
            background-color: #C9DAF8;
        }
    </style>

@stop

@section('content')

<x-mm.styles />
<x-mm.page class="mm-report mm-rst mm-rst-inventory mm-rst-inv" title="Product inventory" description="Stock on hand for the selected product filters.">
    <x-mm.panel class="mm-report-filter">
        @include('inventory.includes.filter')
    </x-mm.panel>

    <x-mm.panel>
        @include('partials._alert_message')

        <div class="json_table mt-2">

                    <x-mm.table-scroll label="Product inventory">
                        <table id="datatable" class="table table-striped table-bordered table-hover mb-2">
                            <thead>
                                <tr style="background: #C9DAF8 !important; color:black !important">
                                    <th>SL</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th class="text-right">Opening Qty</th>
                                    <th class="text-right">Purchase Qty</th>
                                    <th class="text-right">Sold Qty</th>
                                    <th class="text-right">Return Qty</th>
                                    <th class="text-right">Available Qty</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($products as $key => $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ optional($product->category)->name }}</td>
                                        <td class="text-right">{{ $product->opening_quantity }}</td>
                                        <td class="text-right">{{ $product->purchased_quantity }}
                                        </td>
                                        <td class="text-right">{{ $product->sold_quantity }}</td>
                                        <td class="text-right">{{ $product->return_quantity }}</td>
                                        <td class="text-right">{{ $product->available_quantity }}
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="30" class="text-center">
                                            <b class="text-danger">No records found!</b>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </x-mm.table-scroll>

        </div>
        <input type="hidden" id="csrf" value="{{ csrf_token() }}">
    </x-mm.panel>
</x-mm.page>

@endsection

@section('js')

    <script>
        loadSelect2({
            url: "/bar/inventory/get-products",
            select: '#product-search',
            templateResult: formatProduct,
            templateSelection: formatSelection
        })
    </script>

@stop
