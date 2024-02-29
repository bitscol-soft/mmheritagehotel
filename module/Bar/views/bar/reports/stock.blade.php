@extends('layout.app')
@push('style')
    <style>
        @media print {
            .company-info h4 {
                font-weight: bold;
                margin-bottom: 0;
            }
            .company-info p {
                margin-bottom: 2px;
            }
            .hidden{
                display: block !important;
            }
            .search-report{
                display:none !important;
            }
        }
    </style>
@endpush
@section('content')
    <div id="content" class="content min-form">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <div class="pull-right">
                                <button onclick="printPage('print_body')" class="btn btn-sm btn-success m-b-10"><i class="fa fa-print m-r-5"></i> Print</button>
                            </div>
                        </div>
                        <h5 class="panel-title text-left" > Stock Reports </h5>
                    </div>
                    <div class="panel-body" id="print_body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="print-header hidden">
                                    <div class="company-info text-center" >
                                        <h4>{{ $company->company_name }}</h4>
                                        <p>{{ $company->address }}</p>
                                        <p>{{ $company->mobile_no }}, {{ $company->email}}</p>
                                    </div>
                                    <h6 style="width: 100%;text-align: center;margin-top: 15px;"><b style="padding: 10px 20px;border-radius: 10px;color: #000;border:1px solid #ddd;">Stock Report</b></h6>
                                    <br>
                                </div>

{{--                                <div class="row search-report">--}}
{{--                                    <div class="col-md-12">--}}
{{--                                        <form method="get">--}}
{{--                                            <div class="form-group col-md-3">--}}
{{--                                                <label class="control-label" for="name">Product Name :</label>--}}
{{--                                                <input class="form-control" autocomplete="off" placeholder="Product Name" value="" required name="name" type="text">--}}
{{--                                            </div>--}}
{{--                                            <div class="form-group col-md-2">--}}
{{--                                                <label for="control-label">&nbsp;</label>--}}
{{--                                                <button type="submit" class="btn btn-warning col-md-12">Filter</button>--}}
{{--                                            </div>--}}
{{--                                        </form>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <table class="table table-condensed table-striped">
                                    <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>Product Name</td>
                                        <td>Generic Name</td>
                                        <td>Type</td>
                                        <td>Manufacturer</td>
                                        <td>Limit</td>
                                        <td>Available Quantity</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>#</td>
                                            <td>{{$product->name}}</td>
                                            <td>{{optional($product->generic)->name}}</td>
                                            <td>{{$product->medicineType->name}}</td>
                                            <td>{{$product->brand->name}}</td>
                                            <td>{{$product->stock_limitation}} {{$product->unit->name}}</td>
                                            <td>{{$product->retail_quantity}} {{$product->unit->name}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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
