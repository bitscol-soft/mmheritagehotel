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
                        <h5 class="panel-title text-left"> Customer Wise Sales Report </h5>
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
                                    <h6 style="width: 100%;text-align: center;margin-top: 15px;"><b style="padding: 10px 20px;border-radius: 10px;color: #000;border:1px solid #ddd;">Cash Collection Report</b></h6>
                                    <br>
                                </div>

                                <div class="row search-report">
                                    <div class="col-md-12">
                                        <form method="get">
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="Date">Product Name :</label>
                                                <input class="form-control product-name" autocomplete="off"
                                                       placeholder="Product Name" type="text">
                                                <input type="hidden" name="product_id" id="product_id" >
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="Date">Start Date :</label>
                                                <input class="form-control datepicker" autocomplete="off"
                                                       placeholder="Date" value="{{ request('from', date('d-m-Y'))
                                                       }}" required name="from" type="text">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label class="control-label"> &nbsp;</label>
                                                <input class="form-control text-center" type="text" value="TO" readonly="" >
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="Date">End Date :</label>
                                                <input class="form-control datepicker" autocomplete="off"
                                                       placeholder="Date" value="{{request('to', date('d-m-Y'))}}"
                                                       required name="to" type="text">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="control-label">&nbsp;</label>
                                                <button type="submit" class="btn btn-success col-md-12">Filter</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <table class="table table-condensed table-striped">
                                    <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>challan_id</td>
                                        <td>Manufacturer</td>
                                        <td align="center">Date</td>
                                        <td align="right">Payable Amount</td>
                                        <td align="right">Discount</td>
                                        <td align="right">Paid Amount</td>
                                        <td align="right">Due Amount</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($purchases as $key=>$item)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{$item->challan_id}}</td>
                                            <td>{{$item->manufacturer->name??''}}</td>
                                            <td align="center">{{$item->date->format('Y-m-d')}} </td>
                                            <td align="right">{{number_format($item->subtotal, 2)}} TK</td>
                                            <td align="right">{{number_format($item->discount, 2)}}% </td>
                                            <td align="right">{{ number_format($item->paid_amount, 2)}} TK</td>
                                            <td align="right">{{ number_format($item->subtotal-$item->paid_amount, 2)}} TK</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" align="center"><span class="text-danger">Product Not Sold Yet</span></td>
                                        </tr>
                                    @endforelse

                                    <tr style="border-top: 2px solid #cecece">
                                        <td align="right" colspan="7">Total = </td>
                                        <td align="right">{{number_format($amount, 2)}} TK</td>
                                    </tr>
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
    <script src="{{asset('custom_js/loadDetails.js')}}"></script>
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
    <script>
        loadDetails({
            selector: '.product-name',
            url: '{{url('/load-drug')}}',
            select: function (event, ui) {
                $('#product_id').val(ui.item.data.id)
            },
            search:function () {
                $('#product_id').val('')
            }
        })
    </script>
@endsection
