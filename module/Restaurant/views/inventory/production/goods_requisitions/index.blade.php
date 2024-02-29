

@extends('layouts.master')
@section('title','Goods Requisition')
@section('page-header')
    <i class="fa fa-list"></i> Goods Requisition List
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

@section('content')

    <div class="page-header">

        @if(hasPermission("rst.production.create", $slugs))
            <a class="btn btn-xs btn-info" href="{{ route('rst.production.create') }}" style="float: right; margin: 0 2px;"> <i class="fa fa-plus"></i> Create Requisition </a>
        @endif

        <h1>
            @yield('page-header')
        </h1>
    </div>

    @include('partials._alert_message')

    {{-- filter --}}

    <div class="row">
        <form class="form-horizontal" action="{{ route('rst.production.index') }}" method="get">
            @csrf
            <div class="col-sm-12">
                <table class="table table-bordered">

                    <tr>
                        <th class="bg-dark">From - To</th>
                        <th class="bg-dark">Order No</th>
                        <th class="bg-dark">Approved Status</th>
                        <th class="bg-dark text-center">Action</th>
                    </tr>
                    <tr>

                        <td>
                            <div class="input-group" style="width: 320px">
                                <input type="text" class="form-control input-sm date-picker" name="from_date" value="{{ request('from_date') }}" autocomplete="off">
                                <span class="input-group-addon">From|To</span>
                                <input type="text" class="form-control input-sm date-picker" name="to_date" value="{{ request('to_date') }}" autocomplete="off">
                            </div>
                        </td>

                        <td>
                            <input type="text" class="form-control input-sm" name="order_number" value="{{ request('order_number') }}" placeholder="Issue No">
                        </td>
                        <td>
                            <label>
                                <input type="checkbox" class="ace" name="is_approved" {{ request('is_approved') == 1 ? 'checked' : '' }} value="1">
                                <span class="lbl" style="font-weight:800"> Yes </span>
                            </label>
                            <label>
                                <input type="checkbox" class="ace" name="is_not_approved" {{ request('is_not_approved') == 1 ? 'checked' : '' }} value="1">
                                <span class="lbl" style="font-weight:800"> No </span>
                            </label>
                        </td>
                        <td class="text-right">
                            <div class="btn-group btn-corner">
                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                <a href="{{ route('rst.production.index') }}" class="btn btn-xs btn-pink"><i class="fa fa-refresh"></i> Refresh</a>
                            </div>
                        </td>
                    </tr>

                </table>
            </div>
        </form>
    </div>

    <div class="json_table">

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr style="background: #C9DAF8 !important; color:black !important">
                            <th>SL</th>
                            <th>Issue Date</th>
                            <th>Order No</th>
                            <th>Status</th>
                            <th class="text-center">Total Qty</th>
                            <th style="width: 120px !important;" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            // $productions = [];
                        @endphp
                    @foreach($productions as $key => $value)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td><span style="font-weight: bold !important;">{{ $value->date }}</span></td>
                            <td class="text-primary">{{ $value->challan_no }}</td>
                            <td>
                                @if($value->is_approved == 1)
                                    {{ 'Approved' }}
                                @else
                                    {{ 'Not Approved' }}
                                @endif

                            </td>
                            <td style="text-align: center">
                                <span>Material :{{ optional($value->metrial_details)->sum('quantity') }}</span>
                                <span>Item:{{ optional($value->fgood_details)->sum('quantity') }}</span>
                            </td>
                            <td style="min-width: 150px" class="text-center">
                                <span class="btn btn-info btn-minier btn-round popover-success"
                                      data-rel="popover"
                                      data-placement="top"
                                      data-original-title="<i class='ace-icon fa fa-info-circle green'></i> Log Information"
                                      data-content="<p>Created By: {{ $value->created_user->name }}.</p> <p> Created At : {{ $value->created_at }} </p>
                                       <hr/>
                                       <p>Updated By: {{ $value->updated_user->name }}.</p> <p> Updated At : {{ $value->updated_at }} </p>">
                                    <i class="fa fa-info-circle"></i>
                                </span>
                                <div class="btn-group btn-corner">

                                    <a  href="#goods-requisition-details{{ $value->id }}" role="button" data-toggle="modal" class="btn btn-xs btn-purple" title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    {{-- @if($value->is_approved == 0)
                                        @if(hasPermission("rst.production.edit", $slugs))
                                            <a href="{{ route('goods-requisitions.edit', $value->id) }}"  class="btn btn-xs btn-success" title="Edit Goods Requisition">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif

                                        @if(hasPermission("rst.production.approve", $slugs))
                                            <a href="{{ route('approve.goods.requisition.show', $value->id) }}" class="btn btn-xs btn-success" title="Approve">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @endif
                                    @else
                                        @if(hasPermission("rst.production.approve", $slugs))
                                            <a href="{{ route('unapprove.goods.requisition', $value->id) }}" class="btn btn-xs btn-success" title="Unapprove">
                                                <i class="fas fa-thumbs-down"></i>
                                            </a>
                                        @endif
                                    @endif --}}

                                    @if(hasPermission("rst.production.delete", $slugs))
                                        <button type="button" onclick="delete_check({{ $value->id }})" class="btn btn-xs btn-danger" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </div>

                                <form action="{{ route('rst.production.destroy',$value->id)}}" id="deleteCheck_{{ $value->id }}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if (count($productions) == 0)
                        <tr>
                            <td colspan="12" class="text-center">
                                <b class="text-danger">No records found!</b>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                @if (count($productions) != 0)
                    @include('partials._paginate', ['data' => $productions])

                    {{-- <div class="pull-left" style="margin-top:10px; margin-left:10px">
                        <span onclick="exportData('{{ url('export-gs-as-excel') }}')" style="margin-right: 5px; cursor: pointer;">
                            <img src="{{ asset('assets/images/export-icons/excel-icon.png') }}">
                        </span>
                        <span onclick="exportData('{{ url('export-gs-as-pdf') }}')" style="margin-right: 5px; cursor: pointer;">
                            <img src="{{ asset('assets/images/export-icons/pdf-icon.png') }}">
                        </span>
                    </div> --}}

                    {{-- <form class="exportForm" method="POST">
                        @csrf
                        <input type="hidden" name="model" value="Goods Requisition List">
                        <input type="hidden" name="company_id" value="{{ request('company_id') }}">
                        <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                        <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                        <input type="hidden" name="requisition_number" value="{{ request('requisition_number') }}">
                        <input type="hidden" name="gin_number" value="{{ request('gin_number') }}">
                    </form> --}}
                @endif
            </div>

        </div>
    </div>

    <input type="hidden" id="csrf" value="{{ csrf_token() }}">



    {{-- purchase_receives   modals --}}
    @foreach($productions as $key => $data)

        <div id="goods-requisition-details{{ $data->id }}" class="modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="blue bigger"><i class="fa fa-eye"></i> View Production Details</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <dl id="dt-list-1" class="dl-horizontal">
                                    <p class="text-center">Date : {{ $data->date }}</p>
                                    <p class="text-center">Order No : {{ $data->challan_no }}</p>
                                    <p class="text-center">Ref Number : {{ $data->issue_number ?? 'Not Used' }}</p>

                                    <table class="table table-bordered">
                                        <tr>
                                            <th>SL</th>
                                            <th>Metrial</th>
                                            {{-- <th class="text-center">Item Unit</th> --}}
                                            <th>Remarks</th>
                                            <th class="text-center">Issue Quantity</th>
                                        </tr>
                                        @php
                                            $total_received_amount = 0;
                                            $total_required_quantity = 0;
                                            $total_received_quantity = 0;
                                        @endphp

                                        @foreach ($data->metrial_details as  $i => $metrial)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $metrial->product->name  }}</td>
                                                <td>{{ $data->remarks  }}</td>
                                                <td class="text-center">{{ $metrial->quantity  }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3">Total</td>
                                            <td class="text-center">{{ $data->metrial_details->sum('quantity') }}</td>
                                        </tr>
                                    </table>

                                    <table class="table table-bordered">
                                        <tr>
                                            <th>SL</th>
                                            <th>Items</th>
                                            {{-- <th class="text-center">Item Unit</th> --}}
                                            <th>Remarks</th>
                                            <th class="text-center">Issue Quantity</th>
                                        </tr>
                                        @php
                                            $total_received_amount = 0;
                                            $total_required_quantity = 0;
                                            $total_received_quantity = 0;
                                        @endphp

                                        @foreach ($data->fgood_details as  $i => $metrial)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $metrial->product->name  }}</td>
                                                <td>{{ $data->remarks  }}</td>
                                                <td class="text-center">{{ $metrial->quantity  }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3">Total</td>
                                            <td class="text-center">{{ $data->fgood_details->sum('quantity') }}</td>
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

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>



    {{-- export excel/pdf --}}
    <script type="text/javascript">
        function exportData(url)
        {
            $('.exportForm').attr('action', url).submit();
        }
    </script>


    <script type="text/javascript">
        $('[data-rel=popover]').popover({html:true});
    </script>

    <!--  Chosen select -->
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
                            $this.next().css({'width': '220px'});
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if(event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width':'220px'});
                    })
                });
            }
        })
    </script>

    {{-- delete confirmation goods requisition --}}
    <script>
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

    <!--datepicker plugin-->
    <script type="text/javascript">
        jQuery(function($) {

            $('.date-picker').datepicker({
                autoclose: true,
                format:'yyyy-mm-dd',
                todayHighlight: true
            })
                .next().on(ace.click_event, function(){
                $(this).prev().focus();
            });

        })
    </script>



@stop
