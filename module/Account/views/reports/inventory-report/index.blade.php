@extends('layouts.master')
@section('title', 'Inventory Reports')
@section('page-header')
    <i class="fa fa-list"></i> Inventory Reports
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/custom_css/chosen-required.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
@endpush


@section('content')
    <div class="row">
        <div class="col-sm-12">

        @include('partials._alert_message')

        <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>
                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">
                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{ request()->url() }}" class="dt-button btn btn-white btn-primary btn-bold"
                                   title="Refresh Data" data-toggle="tooltip">
                                    <span>
                                        <i class="fa fa-refresh bigger-110"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space"></div>
                <div class="row">
                    <div class="col-sm-12 px-5">
                        <form action="{{ route('report.inventory-report') }}" method="get">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="30%" class="no-print ">
                                        <div class="input-group">
                                        <span class="input-group-addon input-sm">
                                            Company
                                        </span>
                                            <select name="company_id" id="company_id" class="chosen-select-100-percent" data-placeholder="- Select Company -">
                                                <option></option>
                                                @foreach ($companies as $id => $company)
                                                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>

                                    <td width="50%" class="no-print ">
                                        <div class="input-group">
                                        <span class="input-group-addon input-sm">
                                            Product
                                        </span>
                                            <select name="product_id" id="product_id" class="chosen-select-100-percent" data-placeholder="- Select Product -">
                                                <option></option>
                                                @foreach ($products as $id => $product)
                                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>


{{--                                     @if ($settings->where('title', 'Product Subcategory')->where('options', 'yes')->count() < 1)--}}
                                    <td width="20%" class="no-print">
                                        <div class="btn-group btn-corner" style="margin-top:2px;">
                                            <button class="btn btn-sm btn-primary"> <i class="fa fa-check"></i> Check </button>
                                            <button type="button" class="btn btn-sm btn-success" onclick="window.print()">
                                                <i class="fa fa-print"></i> Print
                                            </button>
                                        </div>
                                    </td>
{{--                                     @endif--}}
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                <!-- LIST -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12 px-2">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-bordered table-striped">
                                <thead>
                                <tr class="table-header-bg">
                                    <th class="text-center" style="color: white !important;" width="3%">Sl</th>
                                    <th width="15%" style="color: white !important;">Name</th>
                                    <th class="pl-2" style="color: white !important;">Opening Qty</th>
                                    <th class="pl-2" style="color: white !important;">Purchase Qty</th>
                                    <th class="pl-2" style="color: white !important;">Sale Qty</th>
                                    <th class="pl-2" style="color: white !important;">Issue Qty</th>
                                    <th class="pl-2" style="color: white !important;">Purchase Return Qty</th>
                                    <th class="pl-2" style="color: white !important;">Sale Return Qty</th>
                                    <th class="pl-2" style="color: white !important;">Transfer In Qty</th>
                                    <th class="pl-2" style="color: white !important;">Transfer Out Qty</th>
                                    <th class="pl-2" style="color: white !important;">Available Qty</th>
                                    <th width="10%" class="text-right" style="color: white !important;">Value</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php
                                    $openingQty = 0;
                                    $purchaseQty = 0;
                                    $saleQty = 0;
                                    $issueQty = 0;
                                    $purchaseReturnQty = 0;
                                    $saleReturnQty = 0;
                                    $transferInQty = 0;
                                    $transferOutQty = 0;
                                    $availableQty = 0;
                                    $value = 0;
                                    $totalValue = 0;
                                @endphp
                                @foreach($reports ?? [] as $item)
                                    @php
                                        $productInfo = \Module\Account\Models\Product::select('name', 'product_code', 'purchase_price')->whereId($item->product_id)->first();
                                        $openingQty += $item->opening_qty;
                                        $purchaseQty += $item->purchase_qty;
                                        $saleQty += $item->sale_qty;
                                        $issueQty += $item->issue_qty;
                                        $purchaseReturnQty += $item->purchase_return_qty;
                                        $saleReturnQty += $item->sale_return_qty;
                                        $transferInQty += $item->transfer_in_qty;
                                        $transferOutQty += $item->transfer_out_qty;
                                        $availableQty += $item->available_qty;
                                        $value += $productInfo->purchase_price * $availableQty;
                                        $totalValue += $value;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            <span class="popover-success"
                                                      data-rel="popover"
                                                      data-placement="top"
                                                      data-trigger="hover"
                                                      data-original-title="<i class='ace-icon fa fa-info-circle green'></i> Product Info"
                                                      data-content="<p><b>Name:</b> {{ $productInfo->name }}.</p><p><b>Code:</b> {{ $productInfo->product_code }}.</p>">
                                                {{ Str::limit($productInfo->name, 35, '..') }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $item->opening_qty }}</td>
                                        <td class="text-center">{{ $item->purchase_qty }}</td>
                                        <td class="text-center">{{ $item->sale_qty }}</td>
                                        <td class="text-center">{{ $item->issue_qty }}</td>
                                        <td class="text-center">{{ $item->purchase_return_qty }}</td>
                                        <td class="text-center">{{ $item->sale_return_qty }}</td>
                                        <td class="text-center">{{ $item->transfer_in_qty }}</td>
                                        <td class="text-center">{{ $item->transfer_out_qty }}</td>
                                        <td class="text-center">{{ $item->available_qty }}</td>
                                        <td class="text-right">{{ number_format($value, 2, '.', '') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">Total</th>
                                    <th class="text-center">{{ $openingQty }}</th>
                                    <th class="text-center">{{ $purchaseQty }}</th>
                                    <th class="text-center">{{ $saleQty }}</th>
                                    <th class="text-center">{{ $issueQty }}</th>
                                    <th class="text-center">{{ $purchaseReturnQty }}</th>
                                    <th class="text-center">{{ $saleReturnQty }}</th>
                                    <th class="text-center">{{ $transferInQty }}</th>
                                    <th class="text-center">{{ $transferOutQty }}</th>
                                    <th class="text-center">{{ $availableQty }}</th>
                                    <th class="text-right">{{ number_format($totalValue, 2, '.', '') }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        @if(count($reports) <= 0)
                            <div class="text-center">
                                <span class="text-warning">No Records Founds Yet!</span>
                            </div>
                            <br>
                        @else
                            @include('partials._paginate', ['data' => $reports])
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- delete form -->
    <form action="" id="deleteItemForm" method="POST">
        @csrf @method("DELETE")
    </form>

@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>


    <script type="text/javascript">
        // jQuery(function($) {
        //     $('#data-table').DataTable({
        //         "ordering": false,
        //         "bPaginate": true,
        //         "lengthChange": false,
        //         "info": false,
        //         "pageLength": 25
        //     });
        // })
    </script>
@endsection
