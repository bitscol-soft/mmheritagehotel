@extends('layouts.master')
@section('title', 'Dashboard')
@section('css')

    {{-- Room Price Design And CSS --}}

    @if ($settings->where('key', 'visible_booking_ui_dashboard')->first()->value == 1)
        @include('home._inc.style')
    @endif
@stop

@section('content')

    <x-mm.styles />
    <x-mm.page title="Hotel dashboard" description="Daily activity and room availability at a glance." class="mm-dashboard">
        <x-slot name="actions">
            <span class="mm-dashboard-date" title="Hotel business date"><i class="fa fa-calendar" aria-hidden="true"></i> {{ fdate(today_from_system(), 'd M Y') }}</span>
            @if (hasPermission('bookings.index', $slugs))
                <a class="mm-button mm-button-secondary" href="{{ route('booking.index') }}">Booking list</a>
            @endif
            @if (hasPermission('hotel.expected-arrival.index', $slugs))
                <a class="mm-button mm-button-secondary" href="{{ route('report.expected-arrival') }}">Expected arrivals</a>
            @endif
            @if (hasPermission('hotel.expected-departure.index', $slugs))
                <a class="mm-button mm-button-secondary" href="{{ route('report.expected-departure') }}">Expected departures</a>
            @endif
            @if (hasPermission('hotel.in-house-guest.index', $slugs))
                <a class="mm-button mm-button-secondary" href="{{ route('report.in-house-guest') }}">In-house guests</a>
            @endif
        </x-slot>
        @include('home._inc.dashboard-summary')

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
        <section class="mm-dashboard-board" aria-labelledby="dashboard-board-title">
            <header class="tw-mb-4">
                <h2 id="dashboard-board-title" class="tw-m-0 tw-text-xl tw-font-semibold">Room booking board</h2>
                <p class="tw-m-0 tw-mt-2 tw-text-sm tw-text-muted">Review dates and room status using the existing booking controls.</p>
            </header>
            <div class="row clearfix">
                @include('home._inc.booking_ui')
            </div>
        </section>
    @endif

    </x-mm.page>
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

    <script src="{{ asset('assets/custom_js/stay-range.js') }}"></script>
    @include('home._inc.script')
    <script src="{{ asset('assets/custom_js/room-board.js') }}"></script>

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
