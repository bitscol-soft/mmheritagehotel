@extends('layouts.master')
@section('title','GRN List')
@section('page-header')
    <i class="fa fa-list"></i> GRN List
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <style>
        .bg-dark{
            background-color: #C9DAF8;
        }

    </style>
@stop

{{--@dd($purchase_receives)--}}
@section('content')

    <div class="page-header">

        @if(hasPermission("purchases.create", $slugs))
            <a class="btn btn-xs btn-info" href="{{ route('purchases.create') }}" style="float: right; margin: 0 2px;"> <i class="fa fa-plus"></i> Add @yield('title') </a>
        @endif

        @if(hasPermission("purchases.create", $slugs))
            <a class="btn btn-xs btn-success" href="{{ route('purchases.index') }}" style="float: right; margin: 0 2px;"> <i class="fa fa-list"></i> Purchase List </a>
        @endif
        <h1>@yield('page-header')</h1>
    </div>

    @include('partials._alert_message')
    <div class="row">
        <form class="form-horizontal" action="{{ route('grn.list') }}" method="get">
            @csrf
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-dark">Company</th>
                        <th class="bg-dark">From - To</th>
                        <th class="bg-dark">GRN No</th>
                        <th class="bg-dark">Purchase No</th>
                        <th class="bg-dark text-center">Action</th>
                    </tr>
                    <tr>
                        <td>
                            <select name="company_id" class="form-control chosen-select">
                                <option selected value="">- Select Company -</option>
                                @foreach($companies as $id => $name)
                                    <option value="{{ $id }}" {{ request()->company_id == $id ? 'selected':'' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm date-picker" name="from_date" value="{{ request('from_date') }}" autocomplete="off">
                                <span class="input-group-addon">From|To</span>
                                <input type="text" class="form-control input-sm date-picker" name="to_date" value="{{ request('to_date') }}" autocomplete="off">
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control input-sm" name="grn_no" value="{{ request('grn_no') }}" style="max-width: 145px !important;" placeholder="GRN No">
                        </td>
                        <td>
                            <input type="text" class="form-control input-sm" name="purchase_number" value="{{ request('purchase_number') }}" style="max-width: 145px !important;" placeholder="Purchase No">
                        </td>
                        <td class="text-right">
                            <div class="btn-group btn-corner">
                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                <a href="{{ route('grn.list') }}" class="btn btn-xs btn-pink"><i class="fa fa-refresh"></i> Refresh</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr style="background: #C9DAF8 !important; color:black !important">
                        <th>SL</th>
                        <th>Date</th>
                        <th>GRN No.</th>
                        <th>Date</th>
                        <th>Purchase Number</th>
                        <th>Company</th>
                        <th>Required Qty</th>
                        <th>Received Qty</th>
                        <th>Challan Number</th>
                        <th>Received By</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($purchase_receives as $key => $purchase_receive)
                        @php
                            $total_received_quantity = 0;
                            $total_required_quantity = 0;
                            foreach ($purchase_receive->purchase_receive_details as $i => $purchase) {
                                $total_received_quantity += $purchase->quantity;
                                $total_required_quantity += $purchase_receive->purchase->purchase_details[$i]->quantity;
                            }
                        @endphp
                        <tr>
                            <td>{{ $key+$purchase_receives->firstItem() }}</td>
                            <td style="min-width: 90px">{{ $purchase_receive->purchase_receive_date }}</td>
                            <td class="text-success" style="font-weight:bold !important">{{ $purchase_receive->form_number }}</td>
                            <td style="min-width: 90px">{{ $purchase_receive->purchase->purchase_date }}</td>
                            <td>{{ $purchase_receive->purchase->form_number }}</td>
                            <td>{{ $purchase_receive->company->name }}</td>
                            <td>{{ $total_required_quantity }}</td>
                            <td>{{ number_format($total_received_quantity, 2) }}</td>
                            <td>{{ $purchase_receive->purchase_challan_number }}</td>
                            <td>
                                <p title="Update Time : {{ $purchase_receive->updated_at }}">{{ $purchase_receive->updated_user->name }}</p>
                                <p style="margin-top:-10px !important; font-size: 10px !important;">{{ \Carbon\Carbon::parse($purchase_receive->updated_at)->format('Y-m-d') }}</p>
                            </td>
                            <td>
                                <div class="btn-group btn-corner" style="min-width: 50px">

                                    <a href="{{ route('print.purchase-receive', $purchase_receive->id) }}" role="button" target="__blank" class="btn btn-xs btn-info" title="Print">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    <a href="#purchase-receive-details{{ $purchase_receive->id }}" role="button" data-toggle="modal" class="btn btn-xs btn-purple" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    @php
                                        $count = 0;
                                        foreach ($purchase_receive->purchase_receive_details as $details ){
                                            $count += count($details->is_in_stock);
                                        }
                                    @endphp

                                    @if(hasPermission('purchase.receives.delete', $slugs) && $count == 0)
                                    <button type="button" onclick="delete_check({{ $purchase_receive->id }})" class="btn btn-xs btn-danger" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    @endif
                                </div>

                                <form action="{{ route('purchase.receives.destroy',$purchase_receive->id)}}" id="deleteCheck_{{ $purchase_receive->id }}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                </form>

                            </td>
                        </tr>
                    @endforeach

                    @if (count($purchase_receives) == 0)
                        <tr>
                            <td colspan="11" class="text-center">
                                <b class="text-danger">No data found!</b>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            @if (count($purchase_receives) > 0)
                @include('partials._paginate', ['data' => $purchase_receives])

                <div class="pull-left" style="margin-top:10px; margin-left:10px">
                    <span onclick="exportData('{{ url('export-gs-as-excel') }}')" style="margin-right: 5px; cursor: pointer;">
                        <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                    </span>
                    <span onclick="exportData('{{ url('export-gs-as-pdf') }}')" style="margin-right: 5px; cursor: pointer;">
                        <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}">
                    </span>
                </div>

                <form class="exportForm" method="POST">
                    @csrf
                    <input type="hidden" name="model" value="GRN List">
                    <input type="hidden" name="company_id" value="{{ request('company_id') }}">
                    <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                    <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                    <input type="hidden" name="grn_no" value="{{ request('grn_no') }}">
                    <input type="hidden" name="purchase_number" value="{{ request('purchase_number') }}">
                </form>
            @endif


        </div>
    </div>



    {{-- purchase_receives detail modals --}}
    @foreach($purchase_receives as $purchase_receive)

        <div id="purchase-receive-details{{ $purchase_receive->id }}" class="modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="blue bigger"><i class="fa fa-eye"></i> View Purchase Receive Details</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <dl id="dt-list-1" class="dl-horizontal">
                                    <p class="text-center">Date : {{ $purchase_receive->purchase_receive_date }}</p>
                                    <p class="text-center">Purchase Number : {{ $purchase_receive->purchase->form_number }}</p>
                                    <p class="text-center">GRN Number : {{ $purchase_receive->form_number }}</p>
                                    {{--                                <p class="text-center">Company : {{ $purchase_receive->company->name }}</p>--}}

                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Items</th>
                                            <th>Item Unit</th>
                                            <th>Vendor</th>
                                            <th>Remarks</th>
                                            <th>Required Qty</th>
                                            <th>Received Qty</th>
                                            <th>Rate</th>
                                            <th>Total</th>
                                        </tr>
                                        @php
                                            $total_received_amount = 0;
                                            $total_required_quantity = 0;
                                            $total_received_quantity = 0;
                                        @endphp
                                        @foreach($purchase_receive->purchase->purchase_details as $key => $purchase)
                                            @php
                                                $total_received_amount += ($purchase_receive->purchase_receive_details[$key]->rate * $purchase_receive->purchase_receive_details[$key]->quantity);
                                                $total_required_quantity += $purchase->quantity;
                                                $total_received_quantity += $purchase_receive->purchase_receive_details[$key]->quantity;
                                            @endphp
                                            <tr>
                                                <td>{{ $purchase->item->name  }}</td>
                                                <td>{{ $purchase->item->item_unit->name   }}</td>
                                                <td>{{ $purchase_receive->purchase_receive_details[$key]->supplier->name }}</td>
                                                <td>{{ $purchase_receive->remarks }}</td>
                                                <td>{{ $purchase->quantity }}</td>
                                                <td>{{ number_format($purchase_receive->purchase_receive_details[$key]->quantity, 2) }}</td>
                                                <td>{{ $purchase_receive->purchase_receive_details[$key]->rate }}</td>
                                                <td class="text-right">{{ $purchase_receive->purchase_receive_details[$key]->rate * $purchase_receive->purchase_receive_details[$key]->quantity }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4">Total</td>
                                            <td>{{ $total_required_quantity }}</td>
                                            <td colspan="2">{{ number_format($total_received_quantity, 2) }}</td>
                                            <td class="text-right">{{ $total_received_amount }}</td>
                                        </tr>
                                    </table>

                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm" data-dismiss="modal">
                            <i class="ace-icon fa fa-times"></i>
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('js')

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    

    <!-- printThis -->
    <script src="{{ asset('assets/js/printThis.js') }}"></script>
    <script type="text/javascript">
        function printReceive (receiveNumber) {
            $('#purchase-receive-details'+receiveNumber).printThis({
                importCSS: true
            });
            // $('#purchase-receive-details'+receiveNumber).printThis({
            //     importCSS: true,
            // });
        }
    </script>

    <script type="text/javascript">
        function exportData(url)
        {
            $('.exportForm').attr('action', url).submit();
        }
    </script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        function delete_check(id) {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width: 400,
            }).then((result) => {
                if (result.value) {
                    $('#deleteCheck_' + id).submit();
                }
            })

        }
    </script>

    <!--  Select Box Search-->
    <script type="text/javascript">

        jQuery(function($){

            if(!ace.vars['touch']) {
                $('.chosen-select').chosen({allow_single_deselect:true});
                //resize the chosen on window resize

                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.chosen-select').each(function() {
                            var $this = $(this);
                            $this.next().css({'width': '200px'});
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if(event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width':'200px'});
                    })
                });
            }

        })
    </script>

    <!--datepicker plugin-->
    <script type="text/javascript">
        jQuery(function($) {

            $('.date-picker').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd',
                todayHighlight: true
            })
                //show datepicker when clicking on the icon
                .next().on(ace.click_event, function(){
                $(this).prev().focus();
            });

        })
    </script>
@stop
