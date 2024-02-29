@extends('layouts.master')
@section('title', 'Dashboard')
@section('page-header')
    <i class="fa fa-tachometer"></i> Dashboard
@stop
@section('css')
    <link rel="stylesheet" href="/assets/css/bootstrap-datepicker3.min.css" />
    <link rel="stylesheet" href="/assets/css/bootstrap-timepicker.min.css" />
    <link rel="stylesheet" href="/assets/css/daterangepicker.min.css" />
    <link rel="stylesheet" href="/assets/css/bootstrap-datetimepicker.min.css" />
@stop


@section('content')


    <div class="row">
        <div class="col-xs-12">

            @include('partials._alert_message')

            <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->

        <br>

        <div class="col-sm-2">
            <div class="well well-lg">
                <h2><i class="fa fa-users green"></i> &nbsp; {{ $totalEmployee }}</h2>
                <strong class="text-center">Total Employee</strong>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="well well-lg">
                <h2><i class="fa fa-sign-in blue"></i> &nbsp; {{ $todayAttendance }}</h2>
                <strong class="text-center">Attendance</strong>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="well well-lg">
                <h2><i class="fa fa-sign-out red"></i> &nbsp; {{ $totalEmployee - $todayAttendance }}</h2>
                <strong class="text-center">Today Absent</strong>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="well well-lg">
                <h2><i class="fa fa-home orange"></i> &nbsp; {{ $leave }}</h2>
                <strong class="text-center">Today Leave</strong>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="well well-lg">
                <h2><i class="fa fa-send green"></i> &nbsp; {{ $short_leave }}</h2>
                <strong class="text-center">Short Leave</strong>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="well well-lg">
                <h2><i class="fa fa-external-link green"></i> &nbsp; {{ $out_work }}</h2>
                <strong class="text-center">Out Of Work</strong>
            </div>
        </div>

    </div>

    <br>
    <br>

    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <tr>
                    <td>
                        <div id="columnChart" style="height: 360px; width: 100%;">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

@endsection

@section('js')

    <script type="text/javascript" src="{{ asset('assets/custom_js/canvasjs.js') }}"></script>




    <script type="text/javascript">
        $(document).ready(function() {
            $('.canvasjs-chart-credit').css("display", "none")



            // sync attendance
            $.ajax({
                url: `{{ route('attendance-sync-by-ajax') }}`,
                type: 'GET',
                data: {
                    date: `{{ fdate(now()) }}`,
                },
                success: function(res) {
                    console.log(res)
                }
            });
        })


        var columnChartValues = [

            @for ($i = 1; $i <= 31; $i++)

                @php
                    
                    $da = $i < 10 ? '0' . $i : $i;
                    $date = date('Y-m') . '-' . $da;
                    
                    $employeeDateCount = \App\Models\Attend\Attendance::where('date', $date)->count();
                    
                @endphp

                {
                    {{-- y: {{ rand(100, 600) }}, --}}
                    y: {{ $employeeDateCount }},

                        label: {{ $i }},

                        @if ($i % 2 == 0)

                            color: "#676f6e",
                        @else

                            color: "#b4bfbe",
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
                    text: "Employee Attendance Chart for - ({{ date('F, Y') }})",
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


@stop
