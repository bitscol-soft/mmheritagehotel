@extends('layout.app')
@push('style')
    <style>
        span.badge.badge-default {
            width: 83px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-heading-btn pull-right">
                        <a class="btn btn-success btn-sm" href="/inventory-barcodes/create">Create New</a>
                    </div>
                    <h4 class="panel-title">Barcode Assigned Products <a href="" class="btn btn-xs
                    btn-default"></a></h4>
                </div>
                <div class="panel-body print_body">
                    <div class="col-md-12">

                        <div class="row search-samples">
                            <form method="get">
                                <div class="form-group col-md-3">
                                    <label class="control-label col-md-12">Product ID :</label>
                                    <div class="col-md-12">
                                        <input type="number" name="product_id" value="{{request('product_id')}}"
                                               class="form-control" placeholder="Product ID" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="control-label col-md-12">Barcode :</label>
                                    <div class="col-md-12">
                                        <input type="number" name="barcode" value="{{request('barcode')}}"
                                               class="form-control" placeholder="Barcode Number" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="col-md-12 no-padding" >&nbsp;</label>
                                    <div class="col-sm-12 no-padding">
                                        <button type="submit" style="margin-top: 2px;" class="btn btn-success col-md-12">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <table class="table table-striped table-hover table-bordered " id="my_table">
                            <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Product Name</th>
                                <th>Barcode Number</th>
                                <th width="30%">Barcode</th>
                                <th width="10%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sl = 1 ?>
                            @foreach ($barcodes as $key => $barcode)
                                <tr>
                                    <td class="text-center">{{$sl++}}</td>
                                    <td>{{$barcode->product->name??''}}</td>
                                    <td>{{$barcode->number}}</td>
                                    <td>
                                        {{--<img src="data:image/png;base64,{{base64_encode($generator->getBarcode--}}
                                        {{--($barcode->number, $generator::TYPE_PHARMA_CODE_TWO_TRACKS))}}" alt="">--}}
                                        <img src="{{$barcode->image}}" style="display: block; margin: 0 auto;
                                                 width:150px; height: 35px;">
                                    </td>
                                    <td>
                                        <a href="/inventory-barcodes/{{$barcode->id}}" class="btn btn-xs btn-warning">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>
                                        <a href="{{route('inventory-barcodes.destroy', $barcode->id)}}" class="btn
                                        btn-xs btn-danger deletable"><i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-right">
                            {{$barcodes->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
