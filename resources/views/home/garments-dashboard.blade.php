

@extends('layouts.master')
@section('title','Dashboard')
@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
    <link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>

    <script src="{{ asset('assets/chart/js/jquery.js') }}"></script>

    <script>
        
        $(document).ready(function() {

            var examplePieChartData = {
                "dataset": {
                    "values": [
                        <?php echo $totalShipped['knit_percent']; ?>, <?php echo $totalShipped['sweater_percent']; ?>, <?php echo $totalShipped['woven_percent']; ?>
                    ],
                    "labels": [
                        "Knit", 
                        "Sweater", 
                        "Woven",
                    ],
                },
                "title": "Example Pie Chart",
                "height": "300px",
                "width": "500px",
                "background": "#FFFFFF",
                "shadowDepth": "1"
            };

            MaterialCharts.pie("#pie-chart-example", examplePieChartData)
        });
    </script>

    <link rel="stylesheet" href="{{ asset('assets/chart/css/material-charts.css') }}">
    <script src="{{ asset('assets/chart/js/material-charts.js') }}"></script>

    <style>
        .infobox-small {
            width: 100% !important;
        }
        .new-employee-table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 4px;
        }
        .chosen-container>.chosen-single, [class*=chosen-container]>.chosen-single {
            line-height: 24px !important;
            height: 25px !important;
        }
        .chosen-container-single {
            width: 164px !important;
        }
        .top-sheet>.chosen-container-single .chosen-single {
            background: #a3cc8d !important;
        }
        .dept-wise-attnd>.chosen-container-single .chosen-single {
            background: #d495c3 !important;
        }
        .shift-wise-attnd>.chosen-container-single .chosen-single {
            background: #bdb0b0 !important;
        }

        .widget-body, .widget-main, .flot-overlay {
            background-color: transparent !important;
        }

        .panel-heading {
            /* background: #af4e96 !important */
            /* background: #9ABC32 !important */
            /* background: #d495c3 !important */
            /* background: #2491cf94 !important */
            /* background: #b3b9906e !important */
            background: #b2b34fd4 !important
        }

        .personal-details>p {
            margin: 0;
        }
        #order_type_chart {
            height: 100% !important;
            background: transparent !important;
        }
        .chart {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            /* grid-template-rows: repeat(100, 1fr); */
            grid-column-gap: 0px;
            height: 120px;
            width: 37vw;
            /* padding: 5px 10px;

            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: repeat(100, 1fr);
            grid-column-gap: 5px;
            height: 160px;
            width: 37vw;
            padding: 5px 10px; */
        }

        [class*=bar] {
            margin: 0;
            padding: 0;
            border-radius: 5px 5px 0 0;
            transition: all 0.6s ease;
            grid-row-end: 102;
        }

        .bar-1 {
            grid-row-start: <?php echo (100 - round($salePositions['knit_order_percent'])); ?>;
            background: #68bc31;
            margin-right: 10px;
        }

        .bar-2 {
            grid-row-start: <?php echo (100 - round($salePositions['sweater_order_percent'])); ?>;
            background: #2491cf;
            margin-right: 10px;
        }

        .bar-3 {
            grid-row-start: <?php echo (100 - round($salePositions['woven_order_percent'])); ?>;
            background: #af4e96;
            margin-right: 10px;
        }
  
        .legend>div {
            background: transparent !important;
        }
        .legend>table {
            right: -18px !important;
            top: 99px !important;
        }
        .legendLabel {
            height: 18px !important;
        }
       
        .easy-pie-chart canvas, .easyPieChart canvas {
            position: absolute;
            top: -20px !important;
            width: 90px !important;
            left: -17px !important;
        }

        #piechart-placeholder {
            min-height: 125px !important;
            width: 70% !important;
        }
        #piechart-placeholder canvas {
            width: 130px !important;
            height: 150px !important;
        }

        #piechart-placeholder canvas:first-child {
            top: -25px !important;
        }
        .easy-pie-chart{
            line-height: 24px!important;
        }

        .list-heading {
            font-size: 18px;
            font-family: 'Trebuchet MS', sans-serif;
        }

        .sub-list-item {
            /* text-decoration: underline; */
        }
        .nav-tabs>.active>a {
            background: #122e71f5 !important;
        }

        .nav-tabs>li>a {
            padding: 6px 15px !important;
        }

        /* .badge-info, .badge.badge-info, .label-info, .label.label-info {
            background-color: #bcbc60 !important;
        }
        .label-info.arrowed-in:before {
            border-color: #bcbc60 #bcbc60 #bcbc60 transparent !important;
        }
        .label-info.arrowed-in-right:after {
            border-color: #bcbc60 transparent #bcbc60 #bcbc60 !important;
        } */
        /* .badge-info, .badge.badge-info, .label-info, .label.label-info {
            background-color: #4d6fbf !important;
        }
        .label-info.arrowed-in:before {
            border-color: #4d6fbf #4d6fbf #4d6fbf transparent !important;
        }
        .label-info.arrowed-in-right:after {
            border-color: #4d6fbf transparent #4d6fbf #4d6fbf !important;
        } */
    </style>


@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-dashboard" title="Dashboard">
    <div class="row">
        <div class="col-sm-9">
            <div class="row clearfix dinamic-content">
        
                @if($settings->where('key', 'employee_attendance_chart')->where('value', '1')->count() > 0)
                    <div class="col-sm-12">
            
                        <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                            <div class="card-header panel-heading" style="border-top-left-radius: 5px; border-top-right-radius: 5px; color: white">
                                <div class="card-header panel-heading" style="border-top-left-radius: 5px; border-top-right-radius: 5px; color: white">
                                    <h3 class="card-title" style="padding: 5px; padding-top: 0;">
                                        <strong style="font-size: 14px">Employee Attendance Chart for - ({{ date('F, Y') }})</strong>
        
                                        <span class="pull-right" style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#employee-attendance3">
                                            <i class="ui-icon ace-icon fa fa-plus center bigger-110" style="font-size: 17px !important"></i>
                                        </span>
                                    </h3>
                                </div>
                                <div id="columnChart" style="height: 360px; width: 100%;"></div>
                            </div>
                        </div>
        
                    </div>
                @endif
                
                <div class="col-sm-12">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">

                            <li class="active">
                                <a data-toggle="tab" style="line-height: 40px; font-size: 15px; color: white; background: #4d6fbf; padding: 6px 5px !important" href="#tab_calendar" aria-expanded="true">
                                    <i class="ace-icon fa fa-calendar bigger-120"></i>
                                    Calendar
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" style="line-height: 40px; font-size: 15px; color: white; background: #4d6fbf; padding: 6px 5px !important" href="#tab_hrm" aria-expanded="false">
                                    <i class="ace-icon fa fa-home bigger-120"></i>
                                    HRM
                                </a>
                            </li>

                            <li>
                                <a data-toggle="tab" id="merchandising-tab" style="line-height: 40px; font-size: 15px; color: white; background: #4d6fbf; padding: 6px 5px !important" href="#tab_merchandising" aria-expanded="true">
                                    <i class="ace-icon fa fa-laptop bigger-120" style="color: 8341bf"></i>
                                    Merchandising
                                </a>
                            </li>

                            <li>
                                <a data-toggle="tab" style="line-height: 40px; font-size: 15px; color: white; background: #4d6fbf; padding: 6px 5px !important" href="#tab_commercial" aria-expanded="true">
                                    <i class="ace-icon fa fa-briefcase bigger-120"></i>
                                    Commercial
                                </a>
                            </li>

                            <li>
                                <a data-toggle="tab" style="line-height: 40px; font-size: 15px; color: white; background: #4d6fbf; padding: 6px 5px !important" href="#tab_inventory" aria-expanded="true">
                                    <i class="ace-icon fa fa-tags bigger-120"></i>
                                    Inventory
                                </a>
                            </li>

                            <li>
                                <a data-toggle="tab" style="line-height: 40px; font-size: 15px; color: white; background: #4d6fbf; padding: 6px 5px !important" href="#tab_payment" aria-expanded="true">
                                    <i class="ace-icon fa fa-paypal bigger-120"></i>
                                    Payment
                                </a>
                            </li>

                            <li>
                                <a data-toggle="tab" style="line-height: 40px; font-size: 15px; color: white; background: #4d6fbf; padding: 6px 5px !important" href="#tab_general_store" aria-expanded="true">
                                    <i class="ace-icon fa fa-shopping-cart bigger-120" style="color: 9fbb68"></i>
                                    General Store
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" style="min-height: 370px; max-height: 380px">
                            <div id="tab_hrm" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-12" style="margin-bottom: 30px">
                                        <h4 style="">Personal / Pending Information</h4>
                                        <div style="background: #bbbb68; height: 2px">&nbsp;</div>
                                    </div>

                                    <div class="col-sm-6">

                                        <ol style="list-style: none">
                                            <li>
                                                <span class="list-heading"><strong>1. Leave Details</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Availed Cl- {{ $leaves['availed_cl'] }} / SL- {{ $leaves['availed_sl'] }}</span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Pending Cl- {{ $leaves['pending_cl'] }} / SL- {{ $leaves['pending_sl'] }}</span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Balance Cl- {{ $leaves['balance_cl'] }} / SL- {{ $leaves['balance_sl'] }}</span></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <span class="list-heading"><strong>2. Out Work</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Performed Days - {{ $ow_perform_days }} days</span></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <span class="list-heading"><strong>3. Short Leave</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Availed Days - {{ $short_leaves['adjusted'] }}</span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Adjusted Days - {{ $short_leaves['availed'] }}</span></li>
                                                </ul>
                                            </li>
                                        </ol>
                                    </div>

                                    <div class="col-sm-6">
                                        <ol style="list-style: none">
                                        
                                            <li>
                                                <span class="list-heading"><strong>4. Today Attendance</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> In-{{ optional($totday_attendance)->check_in_time ? fdate(optional($totday_attendance)->check_in_time, 'h:i A') : '' }} / Out-{{ optional($totday_attendance)->check_out_time ? fdate(optional($totday_attendance)->check_out_time, 'h:i A') : '' }}</span></li>
                                                    @if(optional($totday_attendance)->check_in_time)
                                                        @php 

                                                            $inTimeEnd      = \Carbon\Carbon::parse($shift->in_end);
                                                            $checkIn        = \Carbon\Carbon::parse(optional($totday_attendance)->check_in_time);
                                                            $latediffTime   = \Carbon\Carbon::parse($checkIn->diffInSeconds($inTimeEnd))->format('H:i:s');
                                                            $existCheckIn  = optional($totday_attendance)->check_in_time;
                                                        @endphp
                                                        <li>
                                                            <span class="sub-list-item"><strong>*</strong>
                                                                 Late - 
                                                                @if ($existCheckIn && $inTimeEnd < $checkIn)
                                                                    {{ $latediffTime }}
                                                                @else 
                                                                    00
                                                                @endif
                                                            </span>
                                                        </li>
                                                    @else
                                                    <li><span class="sub-list-item"><strong>*</strong> Late - 0.00</span></li>
                                                    @endif
                                                </ul>
                                            </li>
                                            <li>
                                                <span class="list-heading"><strong>5. Pay slip</strong> -</span>

                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> For the month of <a href="{{ route('get.employee.current.month.payslip') }}?month={{ optional($last_payslip)->month }}" target="_blank">{{ $last_payslip ? fdate($last_payslip->month, 'F/y') : '' }}</a></span></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <span class="list-heading"><strong>6. Loan Details</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Adjusted Amount- {{ number_format($loans['paid_amount']) }}</span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Balance Amount - {{ number_format($loans['total_amount'] - $loans['paid_amount']) }}</span></li>
                                                </ul>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div id="tab_general_store" class="tab-pane fade">
                                
                                <div class="row">
                                    <div class="col-sm-12" style="margin-bottom: 30px">
                                        <h4 style="">Pending / Stock Details</h4>
                                        <div style="background: #bbbb68; height: 2px">&nbsp;</div>
                                    </div>


                                    <div class="col-sm-6">

                                        <ol style="list-style: none">
                                            <li>
                                                <span class="list-heading"><strong>1. Purchase Information</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Total Purchase Order Created - {{ $purchase_informations['total_purchase_count'] }}</span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Pending For Approval - {{ $purchase_informations['total_pending_count'] }}</span></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <span class="list-heading"><strong>2. Purchase Receiving</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Pending For Receive - {{ $purchase_informations['purchase_receive_pending_count'] }}</span></li>
                                                </ul>
                                            </li>
                                        </ol>
                                    </div>


                                    <div class="col-sm-6">

                                        <ol style="list-style: none">
                                            <li>
                                                <span class="list-heading"><strong>3. Requisition Details</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Total Requisition Created - {{ $purchase_informations['total_requisition_count'] }}</span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Pending For Approval - {{ $purchase_informations['total_pending_requisition_count'] }}</span></li>
                                                </ul>
                                            </li>
                                            <li>
                                                <span class="list-heading"><strong>4. Purchase Suggestion</strong></span>
                                                
                                                <div style="width: 100%; overflow-y:scroll; max-height: 160px">
                                                    <table class="table table-sm table-bordered">
                                                        <thead style="position: sticky">
                                                            <tr>
                                                                <th>Sl</th>
                                                                <th>Unit</th>
                                                                <th class="text-center">No. of Item</th>
                                                            </tr>
                                                        </thead>
    
                                                        <tbody> 
                                                            @foreach($purchase_informations['item_stocks'] as $key => $unit)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $unit->name }}</td>
                                                                    <td class="text-center">
                                                                        <a href="{{ route('items_stock') }}?item_type=1&unit_id={{ $unit->id }}" title="View item list" target="_blank">{{ $unit->items_count }}</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                                    
                            </div>

                            <div id="tab_merchandising" class="tab-pane fade">
                                
                                <div class="row">
                                    <div class="col-sm-12" style="margin-bottom: 30px">
                                        <h4 style="">Orders Summary  / Pending Issues </h4>
                                        <div style="background: #bbbb68; height: 2px">&nbsp;</div>
                                    </div>

                                    <div class="col-sm-6">

                                        <ol style="list-style: none">
                                            <li><span class="list-heading"><strong>1. Monthly Budget</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Month - {{ Date('m/y') }} [DUE] 

                                                        @if($upcomming_shipments['is_alertable'])
                                                            /<a target="_blank" href="{{ route('monthly-order-budgets.create') }}">Create</a></span>
                                                        @endif
                                                    </li>
                                                    <li><span class="sub-list-item"><strong>*</strong> </span>

                                                        <span class="popover-info"
                                                                    data-rel="popover"
                                                                    data-placement="bottom"
                                                                    data-original-title="<i class='ace-icon fa fa-info-circle blue'></i> Created Users Information"
                                                                    data-content="
                                                                        @foreach ($upcomming_shipments['monthly_budget_creators'] as $shipment)

                                                                            <p><a target='_blank' title='View Budget Detail' href='{{ route('monthly-order-budgets.show', $shipment->id) }}'>{{ optional($shipment->created_user)->name }}</a></p>

                                                                        @endforeach

                                                                        " >
                                                            <span class="text-primary" style="cursor: pointer"><i class="fa fa-users"></i> Created Users</span>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><span class="list-heading"><strong>2. Costing</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Not Done : <a target="_blank" href="/mrcd/order?order_type=&buyer=&style_num=&year=&season=&costing=0">{{ $order_costings['not_done'] ?? 0 }}</a></span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Pending Approve : <a target="_blank" href="/mrcd/order?order_type=&buyer=&style_num=&year=&season=&costing=1">{{ $order_costings['pending_approve'] ?? 0 }}</a></span></li>
                                                </ul>
                                            </li>
                                            <li><span class="list-heading"><strong>3. Sales ID</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Total SID Created - {{ $sales_id_infos['total_sales_id'] }}</span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Pending SID Adjustment - {{ $sales_id_infos['pending_sid_adjustment'] }}</span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Value Mismatch With PI - {{ $sales_id_infos['pi_value_mismatch'] }}</span></li>
                                                </ul>
                                            </li>
                                        </ol>
                                    </div>

                                    <div class="col-sm-6">
                                        <ol style="list-style: none">
                                        
                                            <li><span class="list-heading"><strong>4. Export PI</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Total PI Created - {{ $export_pis['total_pis'] }} </span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Pending PI Approval - {{ $export_pis['total_approved_pis'] }} </span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Pending PI For MID - {{ $export_pis['pending_mid_pis'] }} </span></li>
                                                </ul>
                                            </li>
                                            <li><span class="list-heading"><strong>5. Style Information</strong> -</span>

                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Total Order Created - {{ $style_informations['total_order_created'] }} </span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Total Order Deleted - <a target="_blank" href="/mrcd/order-deleted-list?order_type=&buyer=&style_num=&year=2021&season=">{{ $style_informations['total_order_deleted'] }}</a> </span></li>
                                                </ul>
                                            </li>
                                            <li><span class="list-heading"><strong>6. Ship Information</strong> -</span>
                                                <ul style="margin-bottom: 14px; padding-left: 30px; list-style: none;">
                                                    <li><span class="sub-list-item"><strong>*</strong> Total Short Shipped - {{ $shipping_informations['short_ship_qty'] }} </span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> To Be Shipped - {{ $shipping_informations['excess_ship_qty'] }} </span></li>
                                                    <li><span class="sub-list-item"><strong>*</strong> Total Excess Shipped - {{ $shipping_informations['to_be_shipped'] }} </span></li>
                                                </ul>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            <div id="tab_commercial" class="tab-pane fade">
                                <p class="text-center">Commercial</p>
                            </div>

                            <div id="tab_inventory" class="tab-pane fade">
                                <p class="text-center">Inventory</p>
                            </div>

                            <div id="tab_payment" class="tab-pane fade">
                                <p class="text-center">Payment</p>
                            </div>


                            <div id="tab_calendar" class="tab-pane fade active in">
                                <div class="row">
                                    <div class="widget-body col-sm-5">
                                        <div class="widget-main">
                                            <div id="calendar"></div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                        
                    <div class="row">

                        <div class="col-sm-5">
                            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e4e45159;">
                                <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; color: white">
                                    <h3 class="card-title panel-heading" style="padding: 5px; padding-top: 0; margin-bottom: 0; margin-top: 5px">
                                        <strong style="font-size: 16px">Sales Position</strong>
                
                                        {{--
                                        <span class="pull-right" style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#employee-attendance39">
                                            <i class="ui-icon ace-icon fa fa-plus center bigger-110" style="font-size: 17px !important"></i>
                                        </span>
                                        --}}
                                    </h3>
                                </div>
                
                                <div style="background: transparent; height: 157px">
                                    <div class="row">

                                        <div class="col-sm-5" style="padding-left: 20px !important">
                                            <div class="chart">
                                                <div class="bar-1"></div>
                                                <div class="bar-2"></div>
                                                <div class="bar-3"></div>
                                            </div>
                                            <div class="caption">
                                                <span style="display: inline-block; text-align: center; width: 38px">K</span>
                                                <span style="display: inline-block; text-align: center; width: 38px">S</span>
                                                <span style="display: inline-block; text-align: center; width: 38px">W</span>
                                            </div>
                                            <div class="caption" style="margin-top: -2px;font-size: 12px;">
                                                <span style="display: inline-block;text-align: center; width: 38px">{{ round($salePositions['knit_order_percent']) }}%</span>
                                                <span style="display: inline-block;text-align: center; width: 38px">{{ round($salePositions['sweater_order_percent']) }}%</span>
                                                <span style="display: inline-block;text-align: center; width: 38px">{{ round($salePositions['woven_order_percent']) }}%</span>
                                            </div>
                                        </div>

                                        <div class="col-sm-7">
                                            <table class="table table-sm table-bordered" style="color: white; text-align:center; font-size: 10px; margin-top: -1px; margin-bottom: 0 !important;">
                                                <tbody style="font-size: 14px !important">
                                                    <tr style="background: #58586c;">
                                                        <td style="padding: 9px 0px; width: 15% !important"></td>
                                                        <td style="padding: 9px 0px; width: 40% !important">Qty</td>
                                                        <td style="padding: 9px 0px; width: 40% !important">Value</td>
                                                    </tr>
                                                    <tr style="background: #68bc31;">
                                                        <td style="padding: 9px 0px;">K</td>
                                                        <td class="text-center">{{ $salePositions['knit_order_qty'] }}</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ number_format($salePositions['knit_order_value']) }}</td>
                                                    </tr>
                                                    <tr style="background: #2491cf;">
                                                        <td style="padding: 9px 0px;">S</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ $salePositions['sweater_order_qty'] }}</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ number_format($salePositions['sweater_order_value']) }}</td>
                                                    </tr>
                                                    <tr style="background: #af4e96;">
                                                        <td style="padding: 9px 0px;">W</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ $salePositions['woven_order_qty'] }}</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ number_format($salePositions['woven_order_value']) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-sm-3">
                            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e4e45159;">
                                <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; color: white">
                                    <h3 class="card-title panel-heading" style="padding: 5px; padding-top: 0; margin-bottom: 0; margin-top: 5px">
                                        <strong style="font-size: 14px">Upcoming Shipment (Budget)</strong>
                
                                        {{--
                                            <span class="pull-right" style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#employee-attendance39">
                                                <i class="ui-icon ace-icon fa fa-plus center bigger-110" style="font-size: 17px !important"></i>
                                            </span>
                                        --}}
                                    </h3>
                                </div>
                
                                <div style="background: transparent; height: 157px;" class="upcomming-shipping-chart-panel">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-sm table-bordered" style="color: white; text-align:center; font-size: 10px; margin-top: -1px; margin-bottom: 0 !important;">
                                    

                                                <tbody style="font-size: 14px !important">
                                                    <tr style="background: #58586c;">
                                                        <td style="padding: 9px 0px; width: 15% !important"></td>
                                                        <td style="padding: 9px 0px; width: 40% !important">Quantity</td>
                                                        <td style="padding: 9px 0px; width: 40% !important">Value</td>
                                                    </tr>
                                                    <tr style="background: #68bc31;">
                                                        <td style="padding: 9px 0px;">K</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ $upcomming_shipments['total_knit_ship_qty'] }}</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ $upcomming_shipments['total_knit_ship_amount'] }}</td>
                                                    </tr>
                                                    <tr style="background: #2491cf;">
                                                        <td style="padding: 9px 0px;">S</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ $upcomming_shipments['total_sweater_ship_qty'] }}</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ $upcomming_shipments['total_sweater_ship_amount'] }}</td>
                                                    </tr>
                                                    <tr style="background: #af4e96;">
                                                        <td style="padding: 9px 0px;">W</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ $upcomming_shipments['total_woven_ship_qty'] }}</td>
                                                        <td style="padding: 9px 0px;" class="text-center">{{ $upcomming_shipments['total_woven_ship_amount'] }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div style="background: transparent; height: 107px;" class="upcomming-shipping-alert-panel">
                                    <h5 style="margin-top: 50px" class="text-center"><strong>DUE - Please <span class="text-primary" style="cursor: pointer; color: #d15b47; font-size: 24px;" onclick="showMerchandisingPanel()">Generate Budget</span></strong></h5>
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-4">
                            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e4e45159;">
                                <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; color: white">
                                    <h3 class="card-title panel-heading" style="padding: 5px; padding-top: 0; margin-bottom: 0; margin-top: 5px">
                                        <strong style="font-size: 16px">Total Shipped</strong>
                
                                    {{--
                                        <span class="pull-right" style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#employee-attendance39">
                                            <i class="ui-icon ace-icon fa fa-plus center bigger-110" style="font-size: 17px !important"></i>
                                        </span>
                                    --}}
                                    </h3>
                                </div>
                                <div style="background: transparent; height: 157px; padding-left: 25px;">
                                    <div class="row">
                                        

                                        <div class="col-sm-12 mt-3">
                                            <div style="width: 40%; float: left">
                                                <div class="example-chart">
                                                    <div id="pie-chart-example"></div>
                                                </div>
                                            </div>


                                            <div style="width: 60%; float: right">
                                                <table class="table table-sm table-bordered" style="color: white; text-align:center; font-size: 10px; margin-top: -31px; margin-bottom: 0 !important;">
                                                    
                                                    <tbody style="font-size: 14px !important">
                                                        <tr style="background: #58586c;">
                                                            <td style="padding: 9px 0px; width: 15% !important"></td>
                                                            <td style="padding: 9px 0px; width: 40% !important">Quantity</td>
                                                            <td style="padding: 9px 0px; width: 40% !important">Value</td>
                                                        </tr>
                                                        <tr style="background: #68bc31;">
                                                            <td style="padding: 9px 0px;">K</td>
                                                            <td style="padding: 9px 0px;" class="text-center">{{ $totalShipped['knit'] }}</td>
                                                            <td style="padding: 9px 0px;" class="text-center">{{ round($totalShipped['knit_shipped_value']) }}</td>
                                                        </tr>
                                                        <tr style="background: #2491cf;">
                                                            <td style="padding: 9px 0px;">S</td>
                                                            <td style="padding: 9px 0px;" class="text-center">{{ $totalShipped['sweater'] }}</td>
                                                            <td style="padding: 9px 0px;" class="text-center">{{ round($totalShipped['sweater_shipped_value']) }}</td>
                                                        </tr>
                                                        <tr style="background: #af4e96;">
                                                            <td style="padding: 9px 0px;">W</td>
                                                            <td style="padding: 9px 0px;" class="text-center">{{ $totalShipped['woven'] }}</td>
                                                            <td style="padding: 9px 0px;" class="text-center">{{ round($totalShipped['woven_shipped_value']) }}</td>
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
                    
                </div>
        
        
            </div>
                 
        </div>
        
        <div class="col-sm-3">
            <div class="row">
                <!-- Digital Clock -->
                <div class="col-sm-12">
                    <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e4e45159;">
                        <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; color: white">
                            <h3 class="card-title panel-heading" style="padding: 5px; padding-top: 0; margin-top: 0; margin-bottom: 0">
                                <strong style="font-size: 14px"><i class="fa fa-clock-o bigger-110"></i> Date & Time</strong>
                            </h3>
                        </div>

                        <div id="collapse-card-body1">
                            <div class="card-body text-center" style="padding: 0; color: #545406; height: 96px; padding-left: 5px; padding-right: 5px; font-weight: bold">
                                <p style="margin-top: 10px; font-size: 15px; font-weight: 800">{{ fdate(now(), 'l, d F, Y') }}</p>

                                <p id="current-time" style="font-size: 40px; font-weight: 800; margin-top: -8px; font-family: 'Orbitron', sans-serif;">{{ fdate(now(), 'H:i:sa') }}</p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Personal Details -->
                <div class="col-sm-12">
                    <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e4e45159;">
                        <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; color: white">
                            <h3 class="card-title panel-heading" style="padding: 5px; padding-top: 0; margin-bottom: 0">
                                <strong style="font-size: 14px"><i class="fa fa-user bigger-110"></i> Personal Details</strong>
                            </h3>
                        </div>

                        <div id="collapse-card-body1">
                            <div class="card-body  personal-details" style="padding: 0; height: 96px; padding-left: 15px; padding-right: 5px; font-weight: bold">
                                <p>Name: {{ optional(auth()->user()->employee)->name }}({{ optional(auth()->user()->employee)->employee_full_id }})</p>
                                <p>Company: {{ optional(optional(auth()->user()->employee)->company)->name }}</p>
                                <p>Department: {{ optional(optional(auth()->user()->employee)->department)->name }}</p>
                                <p>Designation: {{ optional(optional(auth()->user()->employee)->designation)->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Login Status -->
                <div class="col-sm-12">
                    <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e4e45159;">
                        <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; color: white">
                            <h3 class="card-title panel-heading" style="padding: 5px; padding-top: 0; margin-bottom: 0">
                                <strong style="font-size: 14px">Login Status</strong>

                            </h3>
                        </div>

                        <div id="collapse-card-body1">
                            <div class="card-body" style="padding: 0; height: 96px; overflow-y: scroll; padding-left: 15px; padding-right: 5px; font-weight: bold">
                                <ul class="list-unstyled">
                                    @foreach($logged_in_users as $key => $logged_in_user)
                                    <li><span>{{ $logged_in_user->user->name }} {!! $logged_in_user->user->isLoggedIn() !!}</span> <span class="pull-right" style="font-size: 9px">{{ fdate($logged_in_user->created_at, 'Y-m-d H:i a') }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notice -->
                <div class="col-sm-12" style="margin-top: 0; padding-top: 0">
                    <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e4e45159;">
                        <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; color: white">
                            <h3 class="card-title panel-heading" style="padding: 5px; padding-top: 0; margin-top: 14px; margin-bottom: 0">
                                <strong style="font-size: 14px">Notice</strong>

                            </h3>
                        </div>

                        <div id="collapse-card-body1">
                            <div class="card-body" style="padding: 0; height: 157px; overflow-y: scroll; padding-left: 5px; padding-right: 5px; font-weight: bold">
                                <ul style="list-style: none">
                                    @forelse($notices as $key => $notice)
                                    <li>{{ $key + 1 }}.<a href="{{ route('notices.show', $notice->id) }}" title="View Notice" target="_blank">{{ $notice->title }}</a></li>
                                    @empty 
                                        <li><strong> No latest notice found</strong></li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        
        </div>

    </div>
    
    </div>
    </x-mm.page>


@endsection

@section('js')

    <script type="text/javascript" src="{{ asset('assets/custom_js/canvasjs.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/custom_js/canvasjs.js') }}"></script>

    

    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <script src="{{ asset('assets/js/jquery-ui.custom.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.ui.touch-punch.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.sparkline.index.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/jquery.flot.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.flot.pie.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.flot.resize.min.js') }}"></script> --}}

    <script>
        $('.upcomming-shipping-alert-panel').hide()

        var myVar = setInterval(myTimer, 1000);
        
        function myTimer() {
          var d = new Date();
          document.getElementById("current-time").innerHTML = d.toLocaleTimeString();
        }
    </script>


    <script type="text/javascript">


        $(document).ready(function() {
            $('.collapse-card1').trigger('clicked')
        })
        function manageIcon(object)
        {
            if($(object).closest('.card').find('.card-body').is(":visible")) {
                $(object).find('i').addClass("fa-minus").removeClass("fa-plus")
            } else {
                $(object).find('i').addClass("fa-plus").removeClass("fa-minus")
            }
        }
        
    </script>


    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        // $('.').trigger("click")
        jQuery(function($) {

            /* initialize the external events
                -----------------------------------------------------------------*/

            $('#external-events div.external-event').each(function() {

                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                };

                // store the Event Object in the DOM element so we can get to it later
                $(this).data('eventObject', eventObject);

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
                });

            });




            /* initialize the calendar
            -----------------------------------------------------------------*/

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();


            var calendar = $('#calendar').fullCalendar({

                events: [

                        @foreach($holidays as $holiday)
                        @if($holiday->day_type == 3)
                        {
                            title:"{{ $holiday->title }}",
                            dow:[{{ $holiday->start }}],
                            rendering: 'background',
                            backgroundColor:'rgba(255,58,74,0.38)'
                        },

                        @elseif($holiday->day_type == 2)
                        {
                            @php
                                $end = \Carbon\Carbon::parse($holiday->end);
                            @endphp
                            title:"{{ $holiday->title }}",
                            start:'{{ $holiday->start }}',
                            end:'{{ $end->addDay() }}',
                            className: '{{ $holiday->type == 1 ? 'label-important' : 'label-warning' }}',
                        },

                        @elseif($holiday->day_type == 1)

                        {
                            title: '{{ $holiday->title }}',
                            start: '{{ $holiday->start }}',
                            className:'{{ $holiday->type == 1 ? 'label-important' : 'label-warning' }}',

                        },

                    @endif
                    @endforeach
                ],
                dayRender: function (date, cell) {
                    var today = moment();
                    if (date.isSame(today, "day")) {
                        cell.css("background-color", "skyblue");
                    }
                },

                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                drop: function(date) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject');
                    var $extraEventClass = $(this).attr('data-class');


                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    copiedEventObject.start = date;
                    copiedEventObject.allDay = false;
                    if($extraEventClass) copiedEventObject['className'] = [$extraEventClass];

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }

                }
                ,
                selectable: false,
                selectHelper: false,
                select: function(start, end, allDay) {

                    bootbox.prompt("New Event Title:", function(title) {
                        if (title !== null) {
                            calendar.fullCalendar('renderEvent',
                                {
                                    title: title,
                                    start: start,
                                    end: end,
                                    allDay: allDay,
                                    className: 'label-info'
                                },
                                true // make the event "stick"
                            );
                        }
                    });
                    calendar.fullCalendar('unselect');
                }
            });
        })
    </script>

    <script type="text/javascript">

        $(document).ready(function() {
            $('.canvasjs-chart-credit').css("display", "none")



            // sync attendance
            $.ajax({
                url: `{{ route('attendance-sync-by-ajax') }}`,
                type: 'GET',
                data: {
                    date : `{{ fdate(now()) }}`,
                },
                success: function(res) {
                    console.log(res)
                }
            });
        })
        var columnChartValues = [

            @for($i = 1; $i <= 31; $i++)

                @php

                    $da =  $i < 10 ? '0'.$i : $i;
                    $date = (date('Y-m').'-'.$da);

                    $employeeDateCount = \Module\HRM\Models\Attendance\Attendance::where('date', $date)->count();

                @endphp

                {
                    y: {{ $employeeDateCount }},

                    label: {{ $i }},

                    @if ($i % 2 == 0) 
                        color: "#D6487E" // red
                    @else
                        color: "#3A87AD"
                    @endif

                },
            @endfor
        ];

        renderColumnChart(columnChartValues);

        function renderColumnChart(values) {

            var chart = new CanvasJS.Chart("columnChart", {
                backgroundColor: "white",
                colorSet: "colorSet3",
                title: {
                    // text: "Employee Attendance Chart for - ({{ date('F, Y') }})",
                    text: "",
                    fontFamily: "Arial",
                    fontSize: 25,
                    fontWeight: "normal",
                },
                animationEnabled: true,
                legend: {
                    verticalAlign: "bottom",
                    horizontalAlign: "center"
                },
                theme: "theme2",
                data: [

                    {
                        indexLabelFontSize: 15,
                        indexLabelFontFamily: "Monospace",
                        indexLabelFontColor: "darkgrey",
                        indexLabelLineColor: "darkgrey",
                        indexLabelPlacement: "outside",
                        type: "column",
                        showInLegend: false,
                        legendMarkerColor: "grey",
                        dataPoints: values
                    }
                ]
            });

            chart.render();
        }
    </script>

    <!-- order type bar chart -->
    <script type="text/javascript">

        $(document).ready(function() {
            $('.canvasjs-chart-credit').css("display", "none")
        })
        var columnChartValues = [

            @for($i = 1; $i <= 3; $i++)

                @php

                    $da =  $i < 10 ? '0'.$i : $i;
                    $date = (date('Y-m').'-'.$da);

                    $employeeDateCount = \Module\HRM\Models\Attendance\Attendance::where('date', $date)->count();

                @endphp

                {
                    y: {{ $employeeDateCount }},

                    label: {{ $i }},

                    @if ($i == 1) 
                        color: "#D6487E" // red
                    @elseif($i == 2)
                        color: "#69AA46"
                    @else
                        color: "#2491cf"
                    @endif

                },
            @endfor
        ];

        renderColumnChart(columnChartValues);

        function renderColumnChart(values) {

            var chart = new CanvasJS.Chart("order_type_chart", {
                height: "196",
                backgroundColor: "white",
                colorSet: "colorSet3",
                title: {
                    text: "",
                    fontFamily: "Arial",
                    fontSize: 25,
                    fontWeight: "normal",
                },
                animationEnabled: true,
                legend: {
                    verticalAlign: "bottom",
                    horizontalAlign: "center"
                },
                theme: "theme2",
                data: [

                    {
                        indexLabelFontSize: 15,
                        indexLabelFontFamily: "Monospace",
                        indexLabelFontColor: "darkgrey",
                        indexLabelLineColor: "darkgrey",
                        indexLabelPlacement: "outside",
                        type: "column",
                        showInLegend: false,
                        legendMarkerColor: "grey",
                        dataPoints: values
                    }
                ]
            });

            chart.render();
        }
    </script>

    
    <script type="text/javascript">
        
        
        //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
        //but sometimes it brings up errors with normal resize event handlers
        // $.resize.throttleWindow = false;
        
        // var knit_order_qty      = `{{ $salePositions['knit_order_qty'] }}`
        // var woven_order_qty     = `{{ $salePositions['woven_order_qty'] }}`
        // var sweater_order_qty   = `{{ $salePositions['sweater_order_qty'] }}`

        // var knit_order_percent      = `{{ $salePositions['knit_order_percent'] }}`
        // var woven_order_percent     = `{{ $salePositions['woven_order_percent'] }}`
        // var sweater_order_percent   = `{{ $salePositions['sweater_order_percent'] }}`
        

        // var placeholder = $('#piechart-placeholder').css({'width':'50%' , 'min-height':'150px'});
        // var data = [
        //     { label: "K " + knit_order_percent      + '%', data: knit_order_percent,    color: "#68BC31"},
        //     { label: "S " + sweater_order_percent   + '%', data: sweater_order_percent, color: "#2091CF"},
        //     { label: "W " + woven_order_percent     + '%', data: woven_order_percent,   color: "#AF4E96"},
        // ]



        function showMerchandisingPanel()
        {
            $('#merchandising-tab').trigger('click')
        }
    </script>

    @if($upcomming_shipments['is_alertable'])
        <script>
            var shipment_interval = setInterval(shipmentTimer, 5000);
            
            function shipmentTimer() {
                if($(".upcomming-shipping-alert-panel").is(":visible")) {

                    $('.upcomming-shipping-alert-panel').hide()
                    $('.upcomming-shipping-chart-panel').show()
                } else  {

                    $('.upcomming-shipping-alert-panel').show()
                    $('.upcomming-shipping-chart-panel').hide()
                }
            }
            
        </script>
    @endif


    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
{{-- 
    <script>
        $(document).ready(function() {
            var exampleBarChartData = {
                "datasets": {
                    "values": [5, 10, 30, 50, 20],
                    "labels": [
                        "Apples", 
                        "Oranges", 
                        "Berries", 
                        "Peaches", 
                        "Bananas"
                    ],
                    "color": "blue"
                },
                "title": "Example Bar Chart",
                "noY": true,
                "height": "300px",
                "width": "500px",
                "background": "#FFFFFF",
                "shadowDepth": "1"
            };

            MaterialCharts.bar("#bar-chart-example", exampleBarChartData)

            var examplePieChartData = {
                "dataset": {
                    "values": [5, 30, 5, 20, 40],
                    "labels": [
                        "Apples", 
                        "Oranges", 
                        "Berries", 
                        "Peaches", 
                        "Bananas"
                    ],
                },
                "title": "Example Pie Chart",
                "height": "300px",
                "width": "500px",
                "background": "#FFFFFF",
                "shadowDepth": "1"
            };

            MaterialCharts.pie("#pie-chart-example", examplePieChartData)
        }); 
    </script> --}}
@stop
