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
                            @include('partials._alert_message')


                            <div class="my-2">
                                @include('bar.inventory.includes.filter')
                            </div>


                            <div class="json_table mt-2">

                                <div class="row">
                                    <div class="col-xs-12">
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
                                                        <td class="text-right">
                                                            @php
                                                                $actual_qty = '';
                                                                $sold_qty = explode('.', number_format($product->sold_quantity, 2, '.', ''));
                                                                $actual_qty .= $sold_qty[0] . ' ' . optional($product->unit)->name;
                                                                
                                                                if ((float) $sold_qty[1] ?? '00' != '00') {
                                                                    $qty = $sold_qty[1] / $product->pack_size;
                                                                    $actual_qty .= ' ' . $qty . ' ' . optional($product->pack_unit)->name;
                                                                }
                                                            @endphp
                                                            {{ $actual_qty }}
                                                        </td>
                                                        <td class="text-right">{{ $product->return_quantity }}</td>
                                                        <td class="text-right">
                                                            {{ $product->available_quantity }}
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

                                    </div>
                                </div>

                            </div>
                            <input type="hidden" id="csrf" value="{{ csrf_token() }}">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace.min.js') }}"></script>


    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>



    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable({
                "iDisplayLength": 30,
                "lengthChange": false,
                "dom": 'lrtip',
                "targets": 'no-sort',
                "bSort": false,
                "order": [],
                "info": false
            });
        });
    </script>

@stop
