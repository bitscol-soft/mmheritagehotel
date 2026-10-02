@extends('layouts.master')
@section('title','Payments')
@section('page-header')
    <i class="fa fa-list"></i> Payment Schedule
@stop
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}">

    <style type="text/css">
        .rate-entry-table td, tr {
            border: none !important;
        }

        .bg-qty {
            background: #5759604a;
        }

        .bg-value {
            background: #33712e45;
        }

        th, td {
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>

                    <div class="widget-toolbar border smaller" style="padding-right: 0 !important">

                        <div class="pull-right tableTools-container" style="margin: 0 !important">
                            <div class="dt-buttons btn-overlap btn-group">
                                <a href="{{ request()->url() }}" class="dt-button btn btn-white btn-info btn-bold"
                                   title="Refresh Page" data-toggle="tooltip" tabindex="0">
                                    <span>
                                        <i class="fa fa-refresh bigger-110"></i>
                                    </span>
                                </a>

                                <a target="_blank" href="#" id="id-btn-dialog1"
                                   class="dt-button btn btn-white btn-primary btn-bold"
                                   title="Print Data" data-toggle="tooltip">
                                    <span>
                                        <i class="fa fa-plus bigger-110"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="space"></div>


                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr class="table-header-bg">
                                <th style="width: 5%">SL</th>
                                <th style="width: 10%">End Date</th>
                                <th style="width: 10%">Amount</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 10%">Alert Threshold</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($schedules as $schedule)
                                @php
                                $threshold = \Carbon\Carbon::parse($schedule->alert_date)->diffInDays($schedule->date);
                                @endphp

                                <tr id="{{$schedule->id}}">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$schedule->date}}</td>
                                    <td>{{$schedule->amount}}</td>
                                    <td>
                                        @if($schedule->is_paid)
                                            <span class="label label-sm label-success">Paid</span>
                                        @else
                                            <span class="label label-sm label-warning">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>{{$threshold . ' ' . ($threshold > 1 ? 'days' : 'day')}}</td>
                                    <td>
                                        <div class="btn-group btn-corner">
                                            <a id="id-btn-dialog2" href="#" class="btn btn-xs btn-primary"
                                               title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <a href="#" onclick="delete_item('{{ route('smart-soft-payments.destroy', $schedule->id) }}')" class="btn btn-xs btn-danger" title="Delete">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </div>
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

    <div id="add-new" class="modal in" tabindex="-1" style="display: none; padding-right: 17px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="blue bigger"><i class="fa fa-plus-circle"></i> Add New Payment Schedule </h4>
                </div>

                <form action="{{route('smart-soft-payments.store')}}" method="post" class="form-horizontal">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                @csrf

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1-1"> End Date</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               name="date"
                                               class="form-control date-picker text-center"
                                               autocomplete="off"
                                               placeholder="YYYY-MM-DD">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1-1"> Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               name="amount"
                                               class="form-control text-center"
                                               autocomplete="off"
                                               placeholder="0">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1-1"> Alert Threshold <br>(in
                                        Days)</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               name="threshold"
                                               class="form-control text-center"
                                               autocomplete="off"
                                               placeholder="7">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-sm btn-success smp-button"><i class="fa fa-save"></i> Save</button>
                        <button class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    @foreach($schedules as $schedule)
        <div id="edit-modal-{{$schedule->id}}" class="modal in" tabindex="-1" style="display: none; padding-right: 17px;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="blue bigger"><i class="fa fa-plus-circle"></i> Edit Payment Schedule </h4>
                    </div>

                    <form action="{{route('smart-soft-payments.update', $schedule->id)}}" method="post" class="form-horizontal">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    @csrf @method('put')

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> End Date</label>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                   name="date"
                                                   class="form-control date-picker text-center"
                                                   autocomplete="off"
                                                   value="{{$schedule->date}}"
                                                   placeholder="YYYY-MM-DD">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Amount</label>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                   name="amount"
                                                   class="form-control text-center"
                                                   value="{{$schedule->amount}}"
                                                   autocomplete="off"
                                                   placeholder="0">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1-1"> Alert Threshold <br>
                                            (in Days)
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                   name="threshold"
                                                   class="form-control text-center"
                                                   value="{{\Carbon\Carbon::parse($schedule->date)->diffInDays(\Carbon\Carbon::parse($schedule->alert_date))}}"
                                                   autocomplete="off"
                                                   placeholder="7">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-sm btn-success smp-button">
                                <i class="fa fa-pencil-square"></i> Update
                            </button>

                            <button class="btn btn-sm" data-dismiss="modal">
                                <i class="ace-icon fa fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


    <form id="deleteItemForm" method="post">
        @method('delete') @csrf
    </form>
@endsection

@section('js')
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    
    <script src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("#id-btn-dialog1").on('click', function (e) {
                e.preventDefault();

                $('#add-new').modal();
            });

            $("#id-btn-dialog2").on('click', function (e) {
                e.preventDefault();

                $('#edit-modal-'+Number($(this).closest('tr').attr('id'))).modal();
            });

            $('#trash-btn').on('click', function (e) {
                e.preventDefault();

                Swal.fire
            });
        });
    </script>
@endsection
