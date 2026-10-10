@extends('layouts.master')
@section('title','Dashboard')
@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}"/>
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

        .fc-month-view>td,th {
            height: 20px !important;
            width: 20px !important;
        }

    </style>
@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-dashboard" title="Dashboard">
    <div class="row clearfix">

        <div class="col-sm-2">
            <div class="infobox infobox-green infobox-small infobox-dark" style="border-radius: 3px">
                <div class="infobox-icon" style="background: #708828; border-radius: 50%; text-align: center">
                    <i class="fa fa-users" style="font-size: 20px; margin-top: 10px"></i>
                </div>
                <div class="infobox-data" style="max-width: 100%">
                    <div class="infobox-content">{{ $total_employee_count }}</div>
                    <div class="infobox-content">Total Employee</div>
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="infobox infobox-blue infobox-small infobox-dark" style="border-radius: 3px">
                <div class="infobox-icon" style="background: #2a7aaf; border-radius: 50%; text-align: center">
                    <i class="fa fa-sign-in" style="font-size: 20px; margin-top: 10px"></i>
                </div>
                <div class="infobox-data" style="max-width: 100%">
                    <div class="infobox-content">{{ $today_attendance }}</div>
                    <div class="infobox-content">Present Today</div>
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="infobox infobox-grey infobox-small infobox-dark" style="border-radius: 3px">
                <div class="infobox-icon" style="background: #6b5f5f; border-radius: 50%; text-align: center">
                    <i class="fa fa-sign-out" style="font-size: 20px; margin-top: 10px"></i>
                </div>
                <div class="infobox-data" style="max-width: 100%">
                    <div class="infobox-content">{{ $total_employee_count - $today_attendance - $leave - $short_leave }}</div>
                    <div class="infobox-content">Today Absent</div>
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="infobox infobox-orrange infobox-small infobox-dark" style="border-radius: 3px; background: #ff9000">
                <div class="infobox-icon" style="background: #debb77; border-radius: 50%; text-align: center">
                    <i class="fa fa-home" style="font-size: 20px; margin-top: 10px"></i>
                </div>
                <div class="infobox-data" style="max-width: 100%">
                    <div class="infobox-content">{{ $leave }}</div>
                    <div class="infobox-content">Today Leave</div>
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="infobox infobox-orrange infobox-small infobox-dark" style="border-radius: 3px; background: #69AA46">
                <div class="infobox-icon" style="background: #a5bd98; border-radius: 50%; text-align: center">
                    <i class="fa fa-send" style="font-size: 20px; margin-top: 10px"></i>
                </div>
                <div class="infobox-data" style="max-width: 100%">
                    <div class="infobox-content">{{ $short_leave }}</div>
                    <div class="infobox-content">Short Leave</div>
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="infobox infobox-orrange infobox-small infobox-dark" style="border-radius: 3px; background: #AF4E96">
                <div class="infobox-icon" style="background: #d495c3; border-radius: 50%; text-align: center">
                    <i class="fa fa-external-link" style="font-size: 20px; margin-top: 10px"></i>
                </div>
                <div class="infobox-data" style="max-width: 100%">
                    <div class="infobox-content">{{ $out_work }}</div>
                    <div class="infobox-content">Out Of Work</div>
                </div>
            </div>
        </div>

    </div>


    <div class="row clearfix">
        <div class="col-sm-3">
            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                <div class="card-header bg-primary" style="border-top-left-radius: 5px; border-top-right-radius: 5px">
                    <h3 class="card-title" style="padding: 10px; background: #9ABC32; color: white">
                        <strong style="font-size: 14px">Login Status</strong>

                        <span class="pull-right  collapse-card1" style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#collapse-card-body1">
                            <i class="ui-icon ace-icon fa fa-plus center bigger-110"></i>
                        </span>
                    </h3>
                </div>

                <div id="collapse-card-body1" class="collapse">
                    <div class="card-body" style="padding: 0; height: 96px; overflow-y: scroll; padding-left: 5px; padding-right: 5px; font-weight: bold">
                        <ul class="list-unstyled">
                            @foreach($logged_in_users as $key => $logged_in_user)
                            <li><span>{{ $logged_in_user->user->name }} {!! $logged_in_user->user->isLoggedIn() !!}</span> <span class="pull-right" style="font-size: 9px">{{ fdate($logged_in_user->created_at, 'Y-m-d H:i a') }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer bg-primary text-center" style="padding: 3px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; background: #9ABC32; color: white">
                        <span style="font-size: 13px !important">Last Login</span>
                    </div>
                </div>
              </div>
        </div>

        <div class="col-sm-3">
            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #ff9000; color: white">
                    <h3 class="card-title" style="padding: 10px">
                        <strong style="font-size: 14px">New Employee</strong>

                        <span class="pull-right " style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#collapse-card-body4">
                            <i class="ui-icon ace-icon fa fa-plus center bigger-110"></i>
                        </span>
                    </h3>
                </div>

                <div id="collapse-card-body4" class="collapse">
                    <div class="card-body text-center" style="padding: 0; height: 96px; overflow-y: scroll; padding-left: 5px; padding-right: 5px; font-size: 18px; font-weight: bold">
                        <table class="table table-sm new-employee-table" style="font-size: 13px">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Id</th>
                                    <th>Department</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($new_employees as $key => $employee)
                                    <tr style="font-size: 10px">
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->employee_full_id }}</td>
                                        <td>{{ $employee->department->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center" style="padding: 3px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; background: #ff9000; color: white">
                        <span style="font-size: 13px !important">New Join Employees <span style="background: green; border-radius: 50%; padding: 1%;">{{ $new_employees->count() }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #6FB3E0 !important; color: white">
                    <h3 class="card-title" style="padding: 10px">
                        <strong style="font-size: 14px">Department Wise Employee</strong>

                        <span class="pull-right " style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#collapse-card-body2">
                            <i class="ui-icon ace-icon fa fa-plus center bigger-110"></i>
                        </span>
                    </h3>
                </div>

                <div id="collapse-card-body2" class="collapse">
                    <div class="card-body" style="padding: 5px; padding-left: 10px; height: 96px; overflow-y: scroll; font-size: 13px;">
                        <ul class="list-unstyled">
                            @foreach ($departments as $department)
                              <li>{{ $department->name }} <span class="pull-right" style="font-size: 12px">{{ $department->employees_count }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer text-center" style="padding: 3px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; background-color: #6FB3E0 !important; color: white">
                        <span style="font-size: 13px !important">Total Departments <span style="background: green; border-radius: 50%; padding: 1%;">{{ $departments->count() }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #69AA46 !important; color: white">
                    <h3 class="card-title top-sheet" style="padding: 10px">
                        <strong style="font-size: 14px">Top Sheet</strong>
                        <select class="form-control-sm form-control chosen-select">
                            <option value="">-Select Company-</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ $id == auth()->user()->company_id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <span class="pull-right" style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#collapse-card-body3">
                            <i class="ui-icon ace-icon fa fa-plus center bigger-110"></i>
                        </span>
                    </h3>
                </div>

                <div id="collapse-card-body3" class="collapse">
                    <div class="card-body" style="padding: 5px; height: 96px; overflow-y: scroll; padding-left: 10px; font-size: 13px;">

                        <ul class="list-unstyled">
                            @forelse($top_sheets as $key => $top_sheet)
                            <li>{{ optional($top_sheet->department)->name }} <span class="pull-right" style="font-size: 12px">{{ $top_sheet->netTotalGross->sum('net_total_gross') - $top_sheet->netTotalDeduction->sum('net_total_ded') }}</span></li>
                            @empty
                                <li>No records found!</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="card-footer text-center" style="padding: 3px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; background-color: #69AA46 !important; color: white">
                        <span style="font-size: 13px !important">Department Show <span style="background: green; border-radius: 50%; padding: 1%;">{{ $top_sheets->count() }}</span></span>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-sm-3">
            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #AF4E96 !important; color: white">
                    <h3 class="card-title dept-wise-attnd" style="padding: 10px">
                        <strong style="font-size: 14px">Dept. Wise Atnd</strong>

                        <select class="form-control-sm form-control chosen-select">
                            <option value="">-Select Company-</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ $id == auth()->user()->company_id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>

                        <span class="pull-right " style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#collapse-card-body5">
                            <i class="ui-icon ace-icon fa fa-plus center bigger-110"></i>
                        </span>
                    </h3>
                </div>

                <div id="collapse-card-body5" class="collapse">
                    <div class="card-body" style="padding: 5px; height: 96px; overflow-y: scroll; padding-left: 10px; font-size: 13px;">
                        <ul class="list-unstyled">
                            @foreach($department_wise_attndances as $key => $department)
                                <li>{{ $department->name }} <span class="pull-right" style="font-size: 12px">{{ $department->employees_count }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer text-center" style="padding: 3px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; background-color: #AF4E96 !important; color: white">
                        <span style="font-size: 13px !important">New Join Employees <i class="fa fa-arrow-right"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #999 !important; color: white">
                    <h3 class="card-title shift-wise-attnd" style="padding: 10px">
                        <strong style="font-size: 14px">Shift Wise Atnd</strong>


                        <select class="form-control-sm form-control chosen-select">
                            <option value="">-Select Company-</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ $id == auth()->user()->company_id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>

                        <span class="pull-right " style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#collapse-card-body6">
                            <i class="ui-icon ace-icon fa fa-plus center bigger-110"></i>
                        </span>
                    </h3>
                </div>

                <div id="collapse-card-body6" class="collapse">
                    <div class="card-body" style="padding: 5px; height: 96px; overflow-y: scroll; padding-left: 10px; font-size: 13px;">
                        <ul class="list-unstyled">

                            <li>Not Assign Shift <span class="pull-right" style="font-size: 12px">{{ $not_assign_shift }}</span></li>
                            @foreach($shift_wise_attendances as $key => $shift)
                            <li>{{ $shift->in_start }} - {{ $shift->out_end }} <span class="pull-right" style="font-size: 12px">{{ $shift->employees_count }}</span></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer text-center" style="padding: 3px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; background-color: #999 !important; color: white">
                        <span style="font-size: 13px !important">New Join Employees <i class="fa fa-arrow-right"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <hr style="padding-bottom: 0; margin-bottom: 0">
    <h3 style="font-weight: 800; margin-top: 6px; margin-bottom: 0">Employee Attendance</h3>


    <!-- Attendance -->
    <div class="row clearfix">

        @if($settings->where('key', 'employee_attendance_chart')->where('value', '1')->count() > 0)
            <div class="col-sm-12">

                <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                    <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #6FB3E0 !important; color: white">
                        <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #6FB3E0 !important; color: white">
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

        <div class="col-sm-6">

            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #AF4E96 !important; color: white">
                    <h3 class="card-title" style="padding: 5px; padding-top: 0;">
                        <strong style="font-size: 14px">Department Wise Emloyee Distribution</strong>

                        <span class="pull-right" style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#employee-attendance3">
                            <i class="ui-icon ace-icon fa fa-plus center bigger-110" style="font-size: 17px !important"></i>
                        </span>
                    </h3>
                </div>

                <div id="employee-attendance3">
                    <div class="widget-box">

                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="piechart-placeholder"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-sm-6">
            <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                <div class="card-header" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #AF4E96 !important; color: white">
                    <h3 class="card-title" style="padding: 5px; padding-top: 0;">
                        <strong style="font-size: 14px">Calendar</strong>

                        <span class="pull-right" style="cursor: pointer" onclick="manageIcon(this)" data-toggle="collapse" data-target="#employee-attendance3">
                            <i class="ui-icon ace-icon fa fa-plus center bigger-110" style="font-size: 17px !important"></i>
                        </span>
                    </h3>
                </div>

                <div id="employee-attendance3">
                    <div class="widget-box">

                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <br>
    <br>
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
    <script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.sparkline.index.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.flot.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.flot.pie.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.flot.resize.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function() {
            $('.collapse-card1').trigger('clicked')



            // sync attendance
            $.ajax({
                url: `{{ url('hrm/sync-attendance-by-ajax') }}`,
                type: 'GET',
                data: {
                    date : `{{ fdate(now()) }}`,
                },
                success: function(res) {
                    console.log(res)
                }
            });
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
        })
        var columnChartValues = [

            @for($i = 1; $i <= 31; $i++)

                @php

                    $da =  $i < 10 ? '0'.$i : $i;
                    $date = (date('Y-m').'-'.$da);
                    $employeeDateCount = 0;
                    if(file_exists(base_path() . '/module/HRM/routes/web_hrm.php')) {
                        $employeeDateCount = Module\HRM\Models\Attendance\Attendance::where('date', $date)->count();
                    }


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


    <script type="text/javascript">
        jQuery(function($) {
            $('.easy-pie-chart.percentage').each(function(){
                var $box = $(this).closest('.infobox');
                var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
                var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
                var size = parseInt($(this).data('size')) || 50;
                $(this).easyPieChart({
                    barColor: barColor,
                    trackColor: trackColor,
                    scaleColor: false,
                    lineCap: 'butt',
                    lineWidth: parseInt(size/10),
                    animate: ace.vars['old_ie'] ? false : 1000,
                    size: size
                });
            })

            $('.sparkline').each(function(){
                var $box = $(this).closest('.infobox');
                var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
                $(this).sparkline('html',
                                {
                                    tagValuesAttribute:'data-values',
                                    type: 'bar',
                                    barColor: barColor ,
                                    chartRangeMin:$(this).data('min') || 0
                                });
            });


        //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
        //but sometimes it brings up errors with normal resize event handlers
        $.resize.throttleWindow = false;

        var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
        var data = [
            { label: "Office",  data: 58.7, color: "#68BC31"},
            { label: "Others",  data: 24.5, color: "#2091CF"},
            { label: "Ofice Noyamati",  data: 8.2, color: "#AF4E96"},
            { label: "Wash",  data: 18.6, color: "#DA5430"},
            { label: "Sweing",  data: 10, color: "#FEE074"}
        ]
        function drawPieChart(placeholder, data, position) {
            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        tilt:0.8,
                        highlight: {
                            opacity: 0.25
                        },
                        stroke: {
                            color: '#fff',
                            width: 2
                        },
                        startAngle: 2
                    }
                },
                legend: {
                    show: true,
                    position: position || "ne",
                    labelBoxBorderColor: null,
                    margin:[-30,15]
                }
                ,
                grid: {
                    hoverable: true,
                    clickable: true
                }
            })
        }
        drawPieChart(placeholder, data);

        /**
         we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
        so that's not needed actually.
        */
        placeholder.data('chart', data);
        placeholder.data('draw', drawPieChart);

            $('.legend').find('table').css("display", "none")
        })
    </script>

@stop
