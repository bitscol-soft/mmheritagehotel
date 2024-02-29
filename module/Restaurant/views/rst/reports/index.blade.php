@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{--<div class="panel-heading-btn pull-right">--}}
                        {{--<a class="btn btn-success btn-sm" href="">Add New Product</a>--}}

                    {{--</div>--}}
                    <h4 class="panel-title">Inventory Reports </h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="">
                                <div class="form-group col-md-5 pull-right">
                                    <label class="control-label col-md-5 col-sm-2" for="service_search">Product
                                        Name :</label>
                                    <div class="col-md-12 col-sm-12">
                                        <input class="form-control" type="text" value=""  id="search" autocomplete="off" name="service_search" placeholder="Enter at least 3 letters." />
                                        <img class="ajax-loader" style="width:100%; visibility: hidden;"
                                             src="{{asset('images/ajax-loader.gif')}}" alt="">
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <table id="datatable" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                        <tr>
                            <th width="30%">Product Name</th>
                            <th width="10%">Last QTY</th>
                            <th width="10%">Today IN</th>
                            <th width="10%">Today Sale</th>
                            <th width="10%">Available QTY</th>
                            <th width="10%">Product Value</th>
                        </tr>
                        </thead>
                        <tbody class="items">

                        @foreach($products as $product)
                            <tr class="odd gradeX items">
                                <?php
                                    $lastPurchase = $product->todayPurchaseQty;
                                    $lastSale = $product->todaySaleQty;
                                    $lastAvailable = $product->retail_quantity - $lastPurchase + $lastSale;
                                ?>

                                <td>{{$product->name}}</td>
                                <td>{{$lastAvailable}} {{$product->unit->name}}</td>
                                <td>{{ $lastPurchase }} {{$product->unit->name}}</td>
                                <td>{{ $lastSale }} {{$product->unit->name}}</td>
                                <td>{{$product->retail_quantity}}</td>
                                <td>{{number_format($product->retail_sales_price, 2)}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">
                        {{$products->links()}}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(document).on('keyup', '#search', function (e) {
            if ($('#search').val().length > 2) {
                $('.pagination').addClass('hidden')

                $( ".items" ).load(
                    "{{url('/reports/inventory')}}",
                    {
                        name: $(this).val(),
                        _token: '{{csrf_token()}}'
                    },
                    function(response, status, xhr ) {
                    if ( status == "error" ) {
                        var msg = "Sorry but there was an error: ";
                        $( ".items" ).html( msg + xhr.status + " " + xhr.statusText );
                    }
                    if ( status == "success" && response) {

                        $( ".items" ).html(
                             $.map(JSON.parse(response).data, function (data) {

                                let lastAvailable = data.retail_quantity - data.todayPurchase + data.todaySale;
                                return `
                                    <tr>
                                        <td>${data.name}</td>
                                        <td>${lastAvailable} ${data.unit_name}</td>
                                        <td>${data.todayPurchase} ${data.unit_name}</td>
                                        <td>${data.todaySale} ${data.unit_name}</td>
                                        <td>${data.retail_quantity}</td>
                                        <td>${data.retail_sales_price.toFixed(2)}</td>
                                    </tr>
                                `
                            })
                        )
                    }

                });

            }else{
                $( ".items" ).load("{{url('/reports/inventory?page=').request('page')}} .items" );
                $('.pagination').removeClass('hidden')
            }
        })
        $(document).ajaxStart(function () {
            $(".items").fadeOut(750);
            $(".ajax-loader").css('visibility', 'visible');

        });
        $(document).ajaxStop(function () {
            $(".items").fadeIn();
            $(".ajax-loader").css('visibility', 'hidden');
        });

    </script>

@endsection

