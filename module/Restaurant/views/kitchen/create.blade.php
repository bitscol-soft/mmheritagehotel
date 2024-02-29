@extends('layouts.master')

@section('title', 'Kitchen Order List')

@section('page-header')
    <i class="fa fa-gears"></i> Kitchen Order List
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop

@section('content')

    <div class="page-header">
        <h1>
            <i class="fa fa-info-circle"></i> Kitchen Order List
        </h1>
    </div>

    <x-alert-message />

    <div class="row">
        <div class="col-xs-8">
            <!-- SEARCHING -->
            <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                <table id="data-table" class="table table-striped table-bordered table-hover">
                    @foreach ($orders as $order)

                        @if ($order->order_status != 'Complete')
                            <div class="col-xs-4 widget-container-col ui-sortable">
                                <div class="widget-box">
                                    <div class="widget-header" style="background-color: #3A87AD !important;">
                                        <h6 class="widget-title" style="color: #060000;">Order No : {{ ++$loop->index }}
                                        </h6>

                                        <div class="widget-toolbar">
                                            <a href="#" data-action="collapse">
                                                <i class="1 ace-icon fa fa-chevron-up bigger-100"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <p class="alert alert-info" style="font-size: 12px">
                                                <b>Order : {{ $order->invoice_no }}</b> <br>
                                                <b>Table No : {{ $order->table_no }}</b><br>
                                                <b>Waiter No : {{ $order->waiter_no }}</b><br>
                                                @foreach ($order->order_items as $item)
                                                    <b>Item {{ ++$loop->index }} : {{ $item->product->name }} ×
                                                        {{ $item->qty }}</b><br>
                                                @endforeach
                                            </p>

                                        </div>

                                        <div class="widget-toolbox padding-4 clearfix">
                                            <button class="btn btn-xs btn-danger pull-left">
                                                <i class="ace-icon fa fa-times"></i>
                                                <span class="bigger-50">don't accept</span>
                                            </button>
                                            @if ($order->order_status == 'Pending')
                                                <form action="{{ route('kit.update-status', $order->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="type" value="Cooking">
                                                    <button class="btn btn-xs btn-success pull-right">
                                                        <span class="bigger-50">Accept</span>
                                                        <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                                    </button>
                                                </form>
                                            @elseif ($order->order_status == 'Cooking')
                                                <form action="{{ route('kit.update-status', $order->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="type" value="Ready">
                                                    <button class="btn btn-xs btn-warning pull-right">
                                                        <span class="bigger-50">Cooking</span>
                                                        <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                                    </button>
                                                </form>
                                            @elseif ($order->order_status == 'Ready')
                                                <form action="{{ route('kit.update-status', $order->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="type" value="Complete">
                                                    <button class="btn btn-xs btn-info pull-right">
                                                        <span class="bigger-50">To Serve</span>
                                                        <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="label label-sm label-danger">No New Order Found</span>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </table>
            </div>



        </div>

        <div class="col-xs-4">
            <div class="col-sm-12 render-currency-class">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title"><i class="fa fa-plus-circle"></i> Order List</h4>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main no-padding">

                            <div style="margin: 20px;">
                                <div class="table-responsive" style="border: 1px #cdd9e8 solid;">
                                    <table id="data-table" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th width="1%">Sl</th>
                                                <th width="10%" style="text-align: center">Invoice ID</th>
                                                <th width="15%" class="text-center">Date</th>
                                                <th class="text-center" width="1%">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        {{ ++$loop->index }}
                                                    </td>
                                                    <td>
                                                        {{ $order->invoice_no }}
                                                    </td>
                                                    <td>
                                                        {{ $order->date }}
                                                    </td>
                                                    <td>
                                                        {{ $order->order_status }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>


                                    </table>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                {{-- Create End --}}
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>


    @include('currency-conversions.inc.script')


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
@stop
