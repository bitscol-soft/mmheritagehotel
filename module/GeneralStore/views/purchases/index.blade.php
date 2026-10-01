@extends('layouts.master')
@section('title','Purchase')
@section('page-header')
    <i class="fa fa-list"></i> Purchase List
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <style>
        .bg-dark{
            background-color: #C9DAF8;
        }
    </style>
@stop


@section('content')

    <div class="page-header">

        @if(hasPermission("purchases.create", $slugs))
            <a class="btn btn-xs btn-info" href="{{ route('purchases.create') }}" style="float: right; margin: 0 2px;"> <i class="fa fa-plus"></i> Add @yield('title') </a>
        @endif
        <h1>
            @yield('page-header')
        </h1>
    </div>

    @include('partials._alert_message')

    <div class="row">
        <form class="form-horizontal" action="{{ route('purchases.index') }}" method="get">
            @csrf
            <div class="col-sm-12">
                <table class="table table-bordered">

                    <tr>
                        <th class="bg-dark">Company</th>
                        <th class="bg-dark">From - To</th>
                        <th class="bg-dark">Purchase Number</th>
                        <th class="bg-dark">Approved Status</th>
                        <th class="bg-dark">Action</th>
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
                            <input name="purchase_number" class="form-control input-sm" placeholder="Search by purchase number" value="{{ request('purchase_number') }}">
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

                        <td colspan="2" class="text-right">
                            <div class="btn-group btn-corner">
                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                <a href="{{ route('purchases.index') }}" class="btn btn-xs btn-pink"><i class="fa fa-refresh"></i> Refresh</a>
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
                        <th>Purchase Number</th>
                        <th>{{ $systemSetting->value != null ? $systemSetting->value : "Reference" }}</th>
                        <th>Company</th>
                        <th>Required Qty</th>
                        <th>Received Qty</th>
                        <th style="width: 150px"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($purchases as $key => $purchase)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $purchase->purchase_date }}</td>
                            <td class="text-{{ $purchase->is_approved ? 'success' : 'danger' }}">{{ $purchase->is_approved ? str_replace(' ', '', $purchase->form_number) : 'Not Approved' }}</td>
                            <td>{{ $purchase->purchase_reference }}</td>
                            <td>{{ $purchase->company->name }}</td>
                            <td>{{ $purchase->purchase_details->sum('quantity') }}</td>
                            <td>{{ $purchase->purchase_details->sum('received_quantity') }}</td>

                            <td style="text-align: center">
                                <div class="btn-group btn-corner">
                                    @include('partials.user-logs', ['data' => $purchase])

                                    <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-xs btn-purple" title="View Purchase Details"><i class="fa fa-eye"></i></a>


                                    @if($purchase->is_approved == 0)
                                        @if(hasPermission("purchases.edit", $slugs))
                                            <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-xs btn-success" title="Edit Purchase">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        @if(hasPermission("purchases.approve", $slugs))
                                            <a href="{{ route('gs.approve.purchase.show', $purchase->id) }}" class="btn btn-xs btn-success" title="Approve Purchase" id="approveBtn{{ $purchase->id }}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @endif
                                    @endif


                                    @if(hasPermission("purchase.receives.create", $slugs))
                                        @if($purchase->is_approved == 1)
                                            <a href="{{ route('purchase.receives.create', $purchase->id) }}" onclick="{{ $purchase->is_approved == 0 ? 'return false':'' }}"  class="btn btn-xs btn-primary" title="Create Purchase Receive">
                                                <i class="fa fa-arrow-down"></i>
                                            </a>
                                        @endif
                                    @endif

                                    @if($purchase->is_approved != 0 && count($purchase->purchase_receives) == 0 && hasPermission("purchases.approve", $slugs))
                                        <a href="{{ route('gs.unapprove.purchase', $purchase->id) }}" class="btn btn-xs btn-success" title="Unapprove Purchase" id="approveBtn{{ $purchase->id }}">
                                            <i class="fa fa-thumbs-down"></i>
                                        </a>
                                    @endif

                                    @if(hasPermission("purchase.receives.edit", $slugs))
                                        @if(count($purchase->purchase_receives) > 0)
                                            <a href="{{ route('purchase.receive.list', $purchase->id) }}"  class="btn btn-xs btn-primary" title="Show Purchase Receive">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                    @endif

                                    @if(hasPermission("purchases.delete", $slugs) && $purchase->is_approved == 0)
                                        <button type="button" onclick="delete_check({{ $purchase->id }})" class="btn btn-xs btn-danger" title="Delete Purchase">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    @endif

                                </div>

                                <form action="{{ route('purchases.destroy',$purchase->id)}}" id="deleteCheck_{{ $purchase->id }}" method="POST">
                                    @csrf
                                    @method("DELETE")
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if (count($purchases) == 0)
                        <tr>
                            <td colspan="10" class="text-center">
                                <b class="text-danger">No data found!</b>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            @if(count($purchases) > 0)

                @include('partials._paginate', ['data' => $purchases])

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
                    
                    <input type="hidden" name="model" value="Purchase List">
                    <input type="hidden" name="company_id" value="{{ request('company_id') }}">
                    <input type="hidden" name="purchase_number" value="{{ request('purchase_number') }}">
                    <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                    <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                </form>
            @endif

        </div>
    </div>

    <input type="hidden" id="csrf" value="{{ csrf_token() }}">

@endsection

@section('js')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>

    


    <script type="text/javascript">
        $('[data-rel=popover]').popover({html:true});
    </script>

    <script type="text/javascript">

        // export excel/pdf
        function exportData(url)
        {
            $('.exportForm').attr('action', url).submit();
        }


        // delete confirm dialog
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



    <script type="text/javascript">

        // chosen select
        jQuery(function($){
            if(!ace.vars['touch']) {
                $('.chosen-select').chosen({allow_single_deselect:true});
                //resize the chosen on window resize

                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.chosen-select').each(function() {
                            var $this = $(this);
                            $this.next().css({'width': '260px'});
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if(event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width': '260px'});
                    })
                });
            }
        });



        // data picker
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format:'yyyy-mm-dd',
        });
    </script>
@stop
