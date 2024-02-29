@extends('layouts.master')


@section('title', 'Add New Product')

@section('css')
    <style>
        @media (max-width:575px){
            select.chosen-select.form-control.required:invalid {
                height: 100% !important;
                opacity: 1 !important;
                position: unset !important;
                display: unset !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">

        <div class="col-sm-12">
            <div class="widget-box">



                <!-- Header -->
                <div class="widget-header">
                    <h4 class="widget-title">
                        <i class="fa fa-plus-circle"></i> Add New Product
                    </h4>

                    <span class="widget-toolbar">

                        @if (request()->filled('type'))
                            <a href="{{ route('bar.product-uploads.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i>
                                Upload List
                            </a>
                        @else
                            <a href="{{ route('bar.products.index') }}">
                                <i class="ace-icon fa fa-list-alt"></i>
                                Product List
                            </a>
                        @endif

                    </span>
                </div>






                <!-- Body -->
                <div class="widget-body">
                    <div class="widget-main">

                        @include('partials._alert_message')

                        <div class="row">
                            <div class="col-sm-11 col-sm-offset-1">

                                @if (request()->filled('type'))
                                    @include('bar.inventory.product.create.upload')
                                @else
                                    @include('bar.inventory.product.create.create')
                                @endif

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('js')

    @include('inventory/product/_inc/script')
    <script>
        $('#pack_size, #unit_cost, #sale_price').keyup(function(e) {
            let unitCost = parseFloat($('#unit_cost').val())
            let packSize = parseFloat($('#pack_size').val())
            let salesPrice = parseFloat($('#sale_price').val())
            let perPiecePrice = '';


            if (packSize <= 0) {
                alert('Enter Pack Size!');
            } else {
                $('#retail_unit_cost').val((unitCost / packSize).toFixed(2));
                $('#retail_sales_price').val((salesPrice / packSize).toFixed(2));
            }

        })
    </script>

@endsection
