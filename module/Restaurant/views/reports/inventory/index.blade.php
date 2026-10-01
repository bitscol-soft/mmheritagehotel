@extends('layouts.master')
@section('title', 'Product Inventory')
@section('page-header')
    <i class="fa fa-list"></i> Product Inventory
@stop
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
<x-mm.page class="mm-report mm-rst mm-rst-inventory" title="Product inventory" description="Stock on hand for the selected product filters.">
    @include('partials._alert_message')
    <x-mm.panel class="mm-report-filter">
        @include('bar/inventory/includes/filter')
    </x-mm.panel>
    <x-mm.panel>
        <div class="json_table mt-2">
            <x-mm.table-scroll label="Product inventory">
                @include('reports/inventory/export/excel')
            </x-mm.table-scroll>

            <x-paginate :data="$products" />

            <x-export-button pdf="1" excel="1" />
        </div>
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
