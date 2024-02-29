@extends('layouts.master')
@section('title','Item List')
@section('page-header')
    <i class="fa fa-list"></i> Items List
@stop
@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />

    <style>
        .bg-dark{
            background-color: #ededed;
        }
    </style>

@stop


@section('content')

    <div class="page-header">

        @if(hasPermission("items.create", $slugs))
            <a class="btn btn-xs btn-info" href="{{ route('items.create') }}" style="float: right; margin: 0 2px;"> <i class="fa fa-plus"></i> Add @yield('title') </a>
        @endif

        @if(hasPermission("items.upload", $slugs))
            <a href="{{ route('gs.item.upload') }}" class="btn btn-xs btn-pink" style="float: right; margin: 0 2px;"><i class="fa fa-upload"></i> Upload CSV</a>
            <a href="{{ route('gs.item.export') }}" class="btn btn-xs btn-primary" style="float: right; margin: 0 2px;"><i class="fa fa-download"></i> Export Items</a>
        @endif
        <h1>
            @yield('page-header')&nbsp;
            <span style="font-size: 15px;">(<b>{{ $items->total() }} </b>Records Found, page <b>{{ request('page') ?? 1 }}</b> of <b>{{ $items->lastPage() }}</b>, Data Show per page <b>{{ $items->perPage() }}</b> ) </span>
        </h1>
    </div>

    @include('partials._alert_message')

    <div class="row">
        <form class="form-horizontal" action="" method="get">
            <div class="col-sm-12">
                <table class="table table-bordered">

                    <tr>
                        <th class="bg-dark">Company</th>
                        <th class="bg-dark">From - To</th>
                        <th class="bg-dark">Item Name</th>
                        <th class="bg-dark">Action</th>
                    </tr>

                    <tr>
                        <td>
                                <select name="company_id" class="form-control chosen-select">
                                    <option selected value="">- select Company -</option>
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
                                <select name="item_id" class="form-control chosen-select">
                                    <option selected value="">- select Items -</option>
                                    @foreach($item_ids as $id => $name)
                                        <option value="{{ $id }}" {{ request('item_id') == $id ? 'selected':'' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                        </td>


                        <td colspan="2" class="text-right">
                            <div class="btn-group btn-corner">
                                <button class="btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                                <a href="{{ route('items.index') }}" class="btn btn-xs btn-pink"><i class="fa fa-refresh"></i> Refresh</a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>


    <div class="row">
        <div class="col-xs-12">
                <table class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr style="background: #C9DAF8 !important; color:black !important">
                            <th>SL</th>
                            <th>Company</th>
                            <th>Item Name</th>
                            <th>Unit </th>
                            <th>Rate</th>
                            <th>Opening</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($items as $key => $item)
                            <tr>
                                <td>{{ $key+$items->firstItem() }}</td>
                                <td>{{ $item->company->name }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->item_unit->name }}</td>
                                <td>{{ $item->rate }}</td>
                                <td>{{ $item->opening_balance }}</td>
                                <td >
                                    <p title="Create Time : {{ $item->created_at }}">{{ $item->created_user->name }}</p>
                                    <p style="margin-top:-10px !important; font-size: 10px !important;">{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</p>
                                </td>
                                <td>
                                    <p title="Update Time : {{ $item->updated_at }}">{{ $item->updated_user->name }}</p>
                                    <p style="margin-top:-10px !important; font-size: 10px !important;">{{ \Carbon\Carbon::parse($item->updated_at)->format('Y-m-d') }}</p>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-corner">

                                        @if(hasPermission("items.edit", $slugs))
                                            <a href="{{ route('items.edit',$item->id) }}" class="btn btn-sm btn-success" title="Edit">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                        @endif
                                        @if(hasPermission("items.delete", $slugs))
                                            @if($item->purchase_detail_count == 0 && $item->goods_requisition_count == 0)

                                            <button type="button" onclick="delete_check({{ $item->id }})" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            @endif
                                        @endif
                                    </div>

                                    <form action="{{ route('items.destroy',$item->id)}}" id="deleteCheck_{{ $item->id }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                    </form>

                                </td>
                            </tr>
                        @endforeach

                        @if (count($items) == 0)
                            <tr>
                                <td colspan="9" class="text-center">
                                    <b class="text-danger">No records found!</b>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                @if (count($items) != 0)
                    @include('partials._paginate', ['data' => $items])
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
                        <input type="hidden" name="model" value="Item List">
                        <input type="hidden" name="company_id" value="{{ request('company_id') }}">
                        <input type="hidden" name="name" value="{{ request('name') }}">
                        <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                        <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                    </form>
                @endif
            </div>

        </div>
    </div>


@endsection

@section('js')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    

    <script type="text/javascript">
        function exportData(url)
        {
            $('.exportForm').attr('action', url).submit();
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
                            $this.next().css({'width': '220px'});
                        })
                    }).trigger('resize.chosen');
                //resize chosen on sidebar collapse/expand
                $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                    if(event_name != 'sidebar_collapsed') return;
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width': '220px'});
                    })
                });
            }
        })
    </script>

    <!-- inline scripts related to this page -->
    <script type="text/javascript">

        function delete_check(id)
        {
            Swal.fire({
                title: 'Are you sure ?',
                html: "<b>You want to delete permanently !</b>",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                width:400,
            }).then((result) =>{
                if(result.value){
                    $('#deleteCheck_'+id).submit();
                }
            })

        }

    </script>


    <script type="text/javascript">

        // data picker
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format:'yyyy-mm-dd',
        });
    </script>
@stop
