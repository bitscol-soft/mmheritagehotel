@extends('layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @can('inventory-settings-create')
                    <div class="panel-heading-btn pull-right">
                        <a class="btn btn-success btn-sm" href="{{route('inventory-products.create')}}">Add New Product</a>
                    </div>
                    @endcan
                    <h4 class="panel-title">Inventory Products</h4>
                </div>
                <div class="panel-body">

                    <br>

                    <table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                        <tr>
                            <th  class="text-center" > Serial Number </th>
                            <th  class="text-center" > Purchase Date </th>
                            <th  class="text-center" > Pack Size </th>
                            <th  class="text-center" > Purchase Price </th>
                            <th  class="text-center" > Sell Price </th>
                            <th  class="text-center" > Purchase Quantity </th>
                            <th  class="text-center" > Sell Quantity </th>
                            <th  class="text-center" > Expiry Date </th>
                            <th  class="text-center" > Available Quantity </th>

                        </tr>
                        </thead>
                        <tbody>
                            @foreach($histories->purchaseItems??[] as $key => $histore)
                            @dd($histore->available_quantity)
                            <tr class="odd gradeX">
                                <td class="text-center" >{{ $loop->index +1}} </td>
                                <td class="text-center" >{{ $histories->created_at->format('Y-m-d') }}</td>
                                @php
                                    $totalQty       = $histore->available_quantity + $histore->sell_quantity;
                                    $purchasePrice  = $histore->unit_tp/($histore->pack_size);
                                    $sellPrice      = $histore->sales_price/($histore->pack_size);
                                @endphp
                                <td class="text-center">{{ $histories->pack_size }} p</td>
                                <td class="text-center">{{ $purchasePrice??'' }} </td>
                                <td class="text-center">{{ $sellPrice??'' }} </td>
                                <td class="text-center">{{ $totalQty }} p</td>
                                <td class="text-center">{{ $histore->sell_quantity??'' }} p</td>
                                <td class="text-center">{{ $histore->expiry_date->format('Y-m-d')}} </td>
                                <td class="text-right">{{ $histore->available_quantity??00 }} p</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right" colspan="8">Opening Quantity</td>
                            <td class="text-right" style="font-weight:bold" colspan="">
                                {{ optional($openingQuantity->openingItem)->available_quantity??00 }} p
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right"  style="font-weight:bold;color:blue" colspan="8">Total Quantity</td>
                            <td class="text-right" style="font-weight:bold;color:green"colspan="">{{ $histories->available_quantity??00 }} p</td>
                        </tr>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

@endsection
