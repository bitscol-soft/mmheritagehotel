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
<x-mm.page class="mm-report mm-bar mm-rst mm-rst-inv mm-rst-inventory" title="Product inventory" description="Stock on hand for the selected product filters.">
    <x-mm.panel class="mm-report-filter">
        @include('bar.inventory.includes.filter')
    </x-mm.panel>
    <x-mm.panel class="tw-p-4">
        <x-alert-message />

        <div class="json_table mt-2">

            <div class="row">
                <div class="col-xs-12">
                    @include('bar/reports/inventory/export/excel')

                    <x-paginate :data="$products" />

                    <x-export-button pdf="1" excel="1" />

                </div>

            </div>

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
