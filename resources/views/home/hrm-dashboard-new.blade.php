@extends('layouts.master')
@section('title', 'Dashboard')
@section('css')

    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <style>
        .infobox-small {
            width: 100% !important;
        }

        /* .new-employee-table>tbody>tr>td,
            .table>tbody>tr>th,
            .table>tfoot>tr>td,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>thead>tr>th {
                padding: 4px;
            } */

        .chosen-container>.chosen-single,
        [class*=chosen-container]>.chosen-single {
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

        .fc-month-view>td,
        th {
            height: 20px !important;
            width: 20px !important;
        }

        /* custom header design */


        .dash-header {
            height: 40px;
            background: #5a91d6;
            position: relative;
            clear: both;
            border-radius: 10px;
        }

        .panel-default {
            background: #e3e0e0
        }

        .panel-color {
            background: #32b4f2;
            margin-bottom: 0 !important;
        }

        .panel-body {
            padding: 5px !important;
        }

        .card {
            padding: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: auto;
            /* border-radius: 10px; */
            background: #fff;
            margin-bottom: 5px;

        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .card-header {
            text-align: center;
            color: #32b4f2;
            font-weight: bold;
            font-size: 18px
        }

        .card-body {
            text-align: center;
        }

        .card-title {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
        }

        /* The heart of the matter */

        .horizontal-scrollable>.row {
            overflow-x: auto;
            white-space: nowrap;
        }

        .horizontal-scrollable>.row>.col-xs-3 {
            display: inline-block;
            float: none;
            margin-left: -20px;
        }
    </style>
@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-dashboard" title="Dashboard">
    {{-- <div class="dash-header">
        <strong>Managenent <span class="text-center"><strong>HR|Admin|Accounts|IT</strong></span></strong>
    </div> --}}

    <div class="row" style="margin-top:10px">
        <div class="col-sm-9">
            @php
                $color = ['32b4f2', '29cfcc', '00b856', '4c7dbc', '3A8CBB'];
            @endphp
            <div class="horizontal-scrollable">
                <div class="row">
                    @foreach (collect($companies ?? []) as $key => $item)
                        <div class="col-xs-3">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="panel" style="background: #{{ $color[$key] ?? '4c7dbc' }}">
                                        <div class="panel-body" style="margin-bottom: 15px !important">
                                            <div class="card" style="border-radius: 10px">
                                                <div class="card-header">
                                                    <p style="word-wrap: break-word;">{{ $item }} </p>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Total Employee</span>
                                                    <div class="card-title"><a
                                                            href="{{ route('employee.index', ['company_id' => $company_ids[$key] ?? 1]) }}"
                                                            target="_blank"> {{ $total_employees[$key] ?? 0 }}</a> </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Today Attended</span>
                                                    <div class="card-title"><a
                                                            href="{{ url('/hrm/attendance/today') }}?company_id={{ $company_ids[$key] ?? 1 }}&date={{ date('Y-m-d') }}"
                                                            target="_blank"> {{ $today_attendance[$key] ?? 0 }}</a></div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Today Leave</span>
                                                    <div class="card-title"><a
                                                            href="{{ url('/hrm/leave/application') }}?company_id={{ $company_ids[$key] ?? 1 }}&from={{ date('Y-m-d') }}&to={{ $today_to_leave[$key] ?? date('Y-m-d') }}"
                                                            target="_blank"> {{ $today_leave[$key] ?? 0 }}</a></div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Today Outwork</span>
                                                    <div class="card-title"><a
                                                            href="{{ url('/hrm/attendance/out-work') }}?company_id={{ $company_ids[$key] ?? 1 }}&from_date={{ date('Y-m-d') }}&to_date={{ date('Y-m-d') }}"
                                                            target="_blank"> {{ $out_work[$key] ?? 0 }}</a></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row">

                <hr style="padding-bottom: 0; margin-bottom: 0">
                <h3 style="font-weight: 800; margin-top: 6px; margin-bottom: 0">Employee Attendance</h3>


                <!-- Attendance -->
                <div class="row clearfix">

                    @if ($settings->where('key', 'employee_attendance_chart')->where('value', '1')->count() > 0)
                        <div class="col-sm-12">

                            <div class="card"
                                style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                                <div class="card-header"
                                    style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #6FB3E0 !important; color: white">
                                    <div class="card-header"
                                        style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #6FB3E0 !important; color: white">
                                        <h3 class="card-title" style="padding: 5px; padding-top: 0;">
                                            <strong style="font-size: 14px">Employee Attendance Chart for -
                                                ({{ date('F, Y') }})</strong>

                                        </h3>
                                    </div>
                                    <div id="columnChart" style="height: 360px; width: 100%;"></div>
                                </div>
                            </div>

                        </div>
                    @endif
                </div>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="cards" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                <div id="employee-attendance3">
                    <div class="widget-boxx">

                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="margin-top: 10px"></div>
            <div class="panel panel-info"
                style="background: #ffffff;border-radius:10px;height:350px;border: 2px solid #4b7dbc">
                <div class="panel-body">
                    <strong style="color: black;border-bottom:1px solid black">Latest News:</strong>
                    <div style="overflow-y:scroll;height: 120px">
                        @foreach ($notice as $key => $title)
                            <p> <a href="{{ url('/hrm/news-events/notices', $key) }}" target="_blank"
                                    style="color: red">{{ Str::limit($title, 50, '...') }}</a></p>
                        @endforeach
                    </div>

                    <strong style="color: black; ">New Joiner:</strong>
                    <div style="overflow-y:scroll; height: 170px">
                        @foreach ($new_employees as $item)
                            <div class="card" style="margin-right: 10px">
                                <div class="card-body">
                                    <p class="text-left">
                                        <strong>Name: </strong><span>{{ $item->name }}</span><br>
                                        <strong>Id: </strong><span>{{ $item->employee_full_id }}</span><br>
                                        <strong>Department:
                                        </strong><span>{{ $item->department->name }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
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








    <!-- Attendance Auto Sync -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('.collapse-card1').trigger('clicked')



            // sync attendance
            // $.ajax({
            //     url: `{{ route('attendance-sync-by-ajax') }}`,
            //     type: 'GET',
            //     data: {
            //         date : `{{ fdate(now()) }}`,
            //     },
            //     success: function(res) {
            //         console.log(res)
            //     }
            // });
        })
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
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
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

                    @foreach ($holidays ?? [] as $holiday)
                        @if ($holiday->day_type == 3)
                            {
                                title: "{{ $holiday->title }}",
                                dow: [{{ $holiday->start }}],
                                rendering: 'background',
                                backgroundColor: 'rgba(255,58,74,0.38)'
                            },
                        @elseif ($holiday->day_type == 2) {
                                @php
                                    $end = \Carbon\Carbon::parse($holiday->end);
                                @endphp
                                title: "{{ $holiday->title }}",
                                    start: '{{ $holiday->start }}',
                                    end: '{{ $end->addDay() }}',
                                    className:
                                    '{{ $holiday->type == 1 ? 'label-important' : 'label-warning' }}',
                            },
                        @elseif ($holiday->day_type == 1)

                            {
                                title: '{{ $holiday->title }}',
                                start: '{{ $holiday->start }}',
                                className: '{{ $holiday->type == 1 ? 'label-important' : 'label-warning' }}',

                            },
                        @endif
                    @endforeach
                ],
                dayRender: function(date, cell) {
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
                    if ($extraEventClass) copiedEventObject['className'] = [$extraEventClass];

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }

                },
                selectable: false,
                selectHelper: false,
                select: function(start, end, allDay) {

                    bootbox.prompt("New Event Title:", function(title) {
                        if (title !== null) {
                            calendar.fullCalendar('renderEvent', {
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

            @for ($i = 1; $i <= 31; $i++)

                @php
                    
                    $da = $i < 10 ? '0' . $i : $i;
                    $date = date('Y-m') . '-' . $da;
                    
                    $employeeDateCount = $monthly_attendance
                        ->where('date', $date)
                        ->whereIn('company_id', $company_ids ?? [])
                        ->count();
                    
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


    {{-- <script type="text/javascript">
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
    </script> --}}

@stop
