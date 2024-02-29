@extends('layout.app')
@push('style')
    <style>
        @media print {

            /*.barcode{*/
                /*max-height: 80px;*/
            /*}*/
            .barcode-area{
                float: left !important;
                margin: 5px !important;
                width: 50% !important;
                border: 1px solid #eee !important;
                min-height: 75px !important;
                max-height: 85px; !important;
                padding: 7px;
            }

            @page
            {
                size: auto;
                height: auto;
                width: auto;
                /* this affects the margin in the printer settings */
                margin: 0px;
            }
        }
        /*.barcode-area{*/
            /*float: left !important;*/
            /*margin: 5px !important;*/
            /*width: 50% !important;*/
            /*border: 1px solid #eee !important;*/
            /*min-height: 75px !important;*/
            /*max-height: 85px; !important;*/
            /*padding: 7px;*/
        /*}*/
    </style>
@endpush
@section('content')
    <div id="content" class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-heading-btn pull-right">
                            <a href="javascript:;" onclick="printPage('print_body')" class="printbtn btn btn-sm
                            btn-success m-b-10"><i class="fa fa-print m-r-5"></i> Print</a>
                            <a class="btn btn-info btn-sm" href="{{route('inventory-barcodes.index')}}">View All</a>

                        </div>
                        <h4 class="panel-title">Print Product Barcode</h4>
                    </div>
                    <div class="panel-body">
                        <div class="row search-voucher">
                            <form method="get">
                                <div class="form-group col-md-3">
                                    <label class="control-label col-md-12" for="party_id">Product Name :</label>
                                    <div class="col-md-12">
                                        {{$barcode->product->name}}
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="control-label col-md-12" for="service">Code :</label>
                                    <div class="col-md-12">
                                        {{$barcode->number}}
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="control-label col-md-12" for="service">Code :</label>
                                    <div class="col-md-12">
                                        <input type="number" name="quantity" class="form-control" value="{{request
                                        ('quantity') ?? 60}}">
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="col-md-12 no-padding" for="">&nbsp;</label>
                                    <div class="col-sm-12 no-padding">
                                        <button type="submit" class="btn btn-warning col-md-12">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="print_body">
                            @php
                                $i = 1;
                                $qty = request('quantity') ?? 60;
                            @endphp
                            <div class="row">
                            @while ($i <= $qty)
                                <div class="col-md-3">
                                    <div style="float: left; margin:5px; border: 1px solid #eee;
                                height: auto; max-height:100px; padding: 7px;">
                                        <div class="barcode-img">
                                            {{--<img class="barcode-image" src="data:image/png;base64,--}}
                                            {{--{{base64_encode($generator->getBarcode ($barcode->number,--}}
                                            {{--$generator::TYPE_CODE_128))}}" alt="{{$barcode->number}}">--}}
                                            <img class="barcode-image"  src="{{$barcode->image}}" style="display: block; margin: 0 auto;
                                                 width:100%; height: 35px;">
                                            <p class="text-center" style="margin:-2px; ">{{$barcode->number}}</p>
                                            <p class="text-center" style="margin-bottom:0;margin-top: -3px;
                                            width: 130px; line-height: 11px">
                                                {{$barcode->product->name}}</p>
                                        </div>
                                        <br>
                                    </div>
                                </div>


                                @php($i++)
                            @endwhile
                            </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{url('custom_js/printThis.js')}}"></script>
    <script type="text/javascript">
        function printPage(id) {
            $('#' + id).printThis({
                importStyle: true
            });
        };
        // window.onload = $('#print_body').printThis({
        //     importStyle: true
        // });

    </script>
@endsection