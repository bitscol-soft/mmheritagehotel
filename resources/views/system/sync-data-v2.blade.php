@extends('layouts.master')

@section('title', 'Sync Data')

@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />

@stop


@section('content')
    <x-mm.styles />
    <x-mm.page title="Sync Data" description="Synchronize attendance, schedule, leave, holiday, and master data.">
        <x-slot name="actions">
            <a href="#" type="button" data-toggle="modal" data-target="#sync-status-modal" class="btn btn-sm btn-primary">
                <i class="ace-icon fa fa-list-alt"></i> Sync Status
            </a>
        </x-slot>

        @include('partials._alert_message')

        @include('system._inc.sync-status-modal')

        <x-mm.panel class="tw-p-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-8 col-sm-6 col-sm-offset-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <b>Sync Attendance</b>
                                        </span>
                                        <div class="input-group">
                                            <input type="text" class="form-control date-picker attnd-from-date text-center" required
                                                placeholder="From Date" autocomplete="off" name="from_date" value="">
                                            <label class="input-group-addon"><i class="fa fa-exchange"></i></label>
                                            <input type="text" class="form-control date-picker attnd-to-date text-center"
                                                required placeholder="To Date" autocomplete="off" name="to_date"
                                                value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-8 col-sm-6 col-sm-offset-2">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="block">
                                                <input name="schedule_sync" value="1" type="checkbox" class="ace input-lg">
                                                <span class="lbl bigger-120"> Schedule Sync</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="block">
                                                <input name="leave_sync" value="1" type="checkbox" class="ace input-lg">
                                                <span class="lbl bigger-120"> Leave Sync</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="block">
                                                <input name="holiday_sync" value="1" type="checkbox" class="ace input-lg">
                                                <span class="lbl bigger-120"> Holiday Sync</span>
                                            </label>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="block">
                                                <input name="attendance_sync" value="1" type="checkbox" class="ace input-lg" disabled checked>
                                                <span class="lbl bigger-120"> Attendance Sync</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>






                        <div class="form-group">
                            <div class="col-sm-6 col-sm-offset-4">
                                <div class="btn-group">
                                    <button class="btn btn-success btn-sm" type="button" onclick="submitAttendanceSyncData()">
                                        <i class="fa fa-refresh"></i> Sync
                                    </button>
                                    <button class="btn btn-danger btn-sm" type="reset" onclick="submitAttendanceSyncData(1)">
                                        <i class="fa fa-refresh"></i> Fresh Sync
                                    </button>
                                </div>
                            </div>

                        </div>
                        <br>
                        <br>
                    </div>

                    <div class="widget-main">


                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-8 col-sm-6 col-sm-offset-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <b>Summary</b>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <form action="{{ route('sync-monthly-summery') }}" method="GET">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-8 col-sm-6 col-sm-offset-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <b>Select Month</b>
                                            </span>
                                            <input type="text" class="form-control month-picker text-center"
                                                placeholder="Select Month" autocomplete="off" name="month" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-4" style="padding-left:50px">
                                    <div class="btn-group">
                                        <button class="btn btn-success btn-sm">
                                            <i class="fa fa-refresh"></i> Sync
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </form>
                        <br>
                        <br>
                    </div>


                    <div class="widget-main">


                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-8 col-sm-6 col-sm-offset-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <b>Attendance & Summery Sync</b>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <form id="attdAndSummaryResyncForm" action="{{ route('sync-selected-employee-attendance') }}"
                            method="GET">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-8 col-sm-6 col-sm-offset-2">
                                        <div class="input-group mb-2">
                                            <span class="input-group-addon">
                                                <b>Select Employee</b>
                                            </span>
                                            <select name="employee_ids[]" id="" class="chosen-select-100-percent form-control" multiple>
                                                <option></option>
                                                @foreach ($employees as $key => $employee)
                                                    <option value="{{ $employee->id }}">
                                                        {{ $employee->name }} -> {{ $employee->employee_full_id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <b>Select Month</b>
                                            </span>
                                            <input type="text" class="form-control month-picker text-center"
                                                placeholder="Select Month" autocomplete="off" name="month" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-4">
                                    <div class="btn-group">
                                        <button class="btn btn-success btn-sm">
                                            <i class="fa fa-refresh"></i> Sync
                                        </button>
                                        <button class="btn btn-danger btn-sm" type="Reset">
                                            <i class="fa fa-refresh"></i> Fresh Sync
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </form>
        </x-mm.panel>
    </x-mm.page>
@endsection




@section('js')

    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>

    <script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    @include('system._inc.script-v2')
@stop
