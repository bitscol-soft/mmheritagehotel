@extends('layouts.master')
@section('title', 'Stock Ledger')
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
<x-mm.page class="mm-report mm-rst mm-rst-inventory" title="Stock ledger" description="Stock movements for the selected product and dates.">
    <x-alert-message />
    <x-mm.panel class="mm-report-filter">
        @include('bar/inventory/includes/filter')
        {{--                                @include('inventory/includes/filter')--}}
    </x-mm.panel>
    <x-mm.panel>
        <div class="json_table mt-2">
            <x-mm.table-scroll label="Stock ledger">
                @include('reports/inventory-ledger/export/excel')

                {{-- <x-paginate :data="$product_ledgers" /> --}}
            </x-mm.table-scroll>

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
