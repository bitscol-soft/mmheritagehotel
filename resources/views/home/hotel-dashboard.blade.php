@extends('layouts.master')
@section('title', 'Dashboard')
@section('page-header')
    <i class="fa fa-tachometer"></i> Dashboard
@stop
@section('css')

    <style>
        .infobox {
            /* height: fit-content !important; */
            height: 90px !important;
            width: fit-content !important;
        }

        .infobox-content {
            white-space: nowrap;
        }

        .infobox-small {
            width: 100% !important;
        }

        .new-employee-table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 4px;
        }

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
    </style>


    {{-- Room Price Design And CSS --}}
    <style>
        .room-price .label.label-xs.arrowed {
            padding: 12px 8px;
        }

        .room-price .label.label-xs.arrowed i {
            margin-top: -12px;
        }

        .room-price span.label.label-xs.arrowed::before,
        .room-price span.label.label-xs.arrowed::after {
            border-width: 12px 6px !important;
        }
    </style>

    @if ($settings->where('key', 'visible_booking_ui_dashboard')->first()->value == 1)
        @include('home._inc.style')
    @endif
@stop


@section('content')


    <div class="row clearfix">

        <div class="col-sm-3">
            <div class="infobox infobox-green infobox-small infobox-dark" style="border-radius: 3px">
                <div class="infobox-icon" style="background: #708828; border-radius: 50%; text-align: center">
                    <i class="fa fa-hospital-o" style="font-size: 20px; margin-top: 10px"></i>
                </div>
                <div class="infobox-data " style="max-width: 70%">
                    <div class="infobox-content">Booking</div>
                    <div class="infobox-content">Today: {{ $today_booking ?? '0' }}</div>
                    <div class="infobox-content">Last Day: {{ $yesterday_booking ?? '0' }}</div>
                    <div class="infobox-content">Last 7 Days: {{ $last_7_days_booking ?? '0' }}</div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="infobox infobox-blue infobox-small infobox-dark" style="border-radius: 3px">
                <div class="infobox-icon" style="background: #2a7aaf; border-radius: 50%; text-align: center">
                    <i class="fa fa-hospital-o" style="font-size: 20px; margin-top: 10px"></i>
                </div>
                <div class="infobox-data" style="max-width: 70%">
                    <div class="infobox-content">Check IN</div>
                    <div class="infobox-content">Today: {{ $today_checkin ?? 0 }}</div>
                    <div class="infobox-content">Last Day: {{ $yesterday_checkin ?? '0' }}</div>
                    <div class="infobox-content">Last 7 Days: {{ $last_7_days_checkin ?? 0 }}</div>
                    {{-- <div class="infobox-content">Total: {{ $total_checkin ?? 0 }}</div> --}}
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="infobox infobox-grey infobox-small infobox-dark" style="border-radius: 3px">
                <div class="infobox-icon" style="background: #6b5f5f; border-radius: 50%; text-align: center">
                    <i class="fa fa-hospital-o" style="font-size: 20px; margin-top: 10px"></i>
                </div>
                <div class="infobox-data" style="max-width: 100%">
                    <div class="infobox-content">Check Out</div>
                    <div class="infobox-content">Today: {{ $today_checkout ?? 0 }}</div>
                    <div class="infobox-content">Last Day: {{ $yesterday_checkout ?? 0 }}</div>
                    <div class="infobox-content">Last 7 Days: {{ $last_7_days_checkout ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="infobox infobox-purple infobox-small infobox-dark" style="border-radius: 3px; background: #d277de">
                <div class="infobox-icon" style="background: #c2b3c4; border-radius: 50%; text-align: center">
                    <i class="fa fa-hospital-o" style="font-size: 20px; margin-top: 10px"></i>
                </div>
                <div class="infobox-data" style="max-width: 100%">
                    <div class="infobox-content">Total Room ({{ $total_room }})</div>
                    <div class="infobox-content">Booked: {{ $today_room_booked ?? 0 }}</div>
                    {{-- <div class="infobox-content">Ready Room: {{ $today_room_ready ?? 0 }}</div> --}}
                    <!-- will be use in future if you calculate properly -->
                    <div class="infobox-content">Ready Room: {{ $total_room - $today_room_booked ?? 0 }}</div>
                </div>
            </div>
        </div>

    </div>








    <!-- Attendance -->
    <div class="row clearfix" style="display: none">

        @if ($settings->where('key', 'employee_attendance_chart')->where('value', '1')->count() > 0)
            <hr style="padding-bottom: 0; margin-bottom: 0">
            <h3 style="font-weight: 800; margin-top: 6px; margin-bottom: 0;margin-left:15px">Attendance</h3>

            <div class="col-sm-12">

                <div class="card" style="border-top-left-radius: 5px; border-top-right-radius: 5px; background: #e6e6e6;">
                    <div class="card-header"
                        style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #6FB3E0 !important; color: white">
                        <div class="card-header"
                            style="border-top-left-radius: 5px; border-top-right-radius: 5px; background-color: #6FB3E0 !important; color: white">
                            <h3 class="card-title" style="padding: 5px; padding-top: 0;">
                                <strong style="font-size: 14px">Employee Attendance Chart for -
                                    ({{ date('F, Y') }})
                                </strong>

                                <span class="pull-right" style="cursor: pointer" onclick="manageIcon(this)"
                                    data-toggle="collapse" data-target="#employee-attendance3">
                                    <i class="ui-icon ace-icon fa fa-plus center bigger-110"
                                        style="font-size: 17px !important"></i>
                                </span>
                            </h3>
                        </div>
                        <div id="columnChart" style="height: 360px; width: 100%;"></div>
                    </div>
                </div>

            </div>
        @endif

    </div>



    <!-- BOOKING UI IF VISIBLE ON DASHBOARD -->
    @if ($settings->where('key', 'visible_booking_ui_dashboard')->first()->value == 1)
        <hr style="padding-bottom: 0; margin-bottom: 0">


        <div class="row clearfix">
            @include('home._inc.booking_ui')
        </div>
    @endif

@endsection

@section('js')

    <script type="text/javascript" src="{{ asset('assets/custom_js/canvasjs.js') }}"></script>
    {{-- <script type="text/javascript" src="{{ asset('assets/custom_js/canvasjs.js') }}"></script> --}}



    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>


    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/custom_js/date-picker.js') }}"></script>



    @include('home._inc.script')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.collapse-card1').trigger('clicked')



            // sync attendance
            // $.ajax({
            //     url: `{{ url('hrm/sync-attendance-by-ajax') }}`,
            //     type: 'GET',
            //     data: {
            //         date: `{{ fdate(now()) }}`,
            //     },
            //     success: function(res) {
            //         console.log(res)
            //     }
            // });
        })


        function manageIcon(object) {
            if ($(object).closest('.card').find('.card-body').is(":visible")) {
                $(object).find('i').addClass("fa-minus").removeClass("fa-plus")
            } else {
                $(object).find('i').addClass("fa-plus").removeClass("fa-minus")
            }
        }
    </script>


@stop
