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
    <div id="content" class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="widget-box">
                    <div class="widget-header">

                        <h4 class="widget-title"><i class="fa fa-info-circle"></i> Product Inventory </h4>

                    </div>

                    <div class="widget-body">
                        <div class="widget-main">
                            <x-alert-message />

                            <div class="my-2">
                                @include('bar.inventory.includes.filter')
                            </div>


                            <div class="json_table mt-2">

                                <div class="row">
                                    <div class="col-xs-12">
                                        @include('bar/reports/inventory/export/excel')

                                        <x-paginate :data="$products" />

                                        <x-export-button pdf="1" excel="1" />

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

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
