@extends('layouts.master')

@section('title', 'Hotel Monthly Report')

@section('page-header')
    <i class="fa fa-users"></i>&nbsp;Hotel Monthly Report
@stop

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-timepicker.min.css') }}" />

    <style type="text/css">
        .pagination {
            padding-left: 0;
            margin-top: 0px;

        }

        .widget-color-grey>.widget-header {
            border-color: #dfe2cd !important;
            background: #dfe2cd !important;
        }

        .widget-color-grey {
            border-color: #dfe2cd !important;
        }

        .widget-box[class*=widget-color-]>.widget-header {
            color: #383a3f !important;
        }

        .bg-dark {
            background-color: #e6e5e5;
            width: 10%;
            vertical-align: middle;
        }


        select.required:invalid {
            height: 0px !important;
            opacity: 0 !important;
            position: absolute !important;
            display: flex !important;
        }

    </style>


    <style type="text/css">
        table td {
            width: 100px;
            height: 40px;
            text-align: center;
            vertical-align: middle;
            /* background-color:#ccc; */
            border: 1px solid #fff;
        }

        table td.highlighted {
            background-color: #999;
        }

        .bg-holiday-0 {
            background: #8acc33;

        }

        .bg-holiday-1 {
            background: #33a1cc;

        }

        .bg-holiday-2 {
            background: #6e33cc;

        }

        .bg-holiday-3 {
            background: #cc3361;

        }

        .bg-holiday-4 {
            background: #7ab475;

        }

        .bg-weakend {
            background: #cc4033;

        }

        .bg-0 {
            background: #ff0080;

        }

        .bg-1 {
            background: #0080ff;

        }

        .bg-2 {
            background: #ff8000;

        }

        .bg-3 {
            background: #a25361;
        }

        .bg-4 {
            background: #72c136;

        }

        .bg-5 {
            background: rebeccapurple;
            color: white;

        }

        .bg-6 {
            background: magenta;
            color: white;

        }

        .bg-7 {
            background: #00b8ff;
            color: white;

        }

        .bg-8 {
            background: #454354;
            color: white;

        }

        .bg-9 {
            background: #676463;
            color: white;

        }

        .bg-10 {
            background: #206769;
            color: white;
        }

        .bg-11 {
            background: #343443;
            color: white;
        }

        .bg-12 {
            background: #878965;
            color: white;
        }

        .bg-13 {
            background: #125489;
            color: white;
        }

        .bg-14 {
            background: #985747;
            color: white;
        }

        .bg-reset {
            background: white;
        }

        .bg-reset2 {
            background: #ccc;
        }

        .active-bg {
            border: 3px solid #428bca;
        }

        .d-none {
            display: none;
        }

        .room-report td {
            padding: 0 !important;
            width: 20%;
        }

        .room-report .checkout {
            background-color: #a25361;
            height: 50px;
        }

        .room-report .check-in {
            background-color: #72c136;
            height: 50px;
        }

        .room-report .arrived {
            background-color: rebeccapurple;
            height: 50px;
        }

        .room-report .booked {
            background-color: red;
            height: 50px;
        }

        .room-report .available {
            background-color: #ccc;
            height: 50px;
        }

        .guest-info {
            position: relative;
            cursor: pointer;
        }

        .guest-info label {
            color: #fff;
            background-color: #428bca;
            position: absolute;
            width: 100%;
            left: 0;
            font-size: 13px;
            top: 15px;
        }

        .guest-popup {
            position: absolute;
            background-color: #F5F7FA;
            border: 1px solid #ccc;
            height: 150px;
            width: 220px;
            border-radius: 5px;
            display: none;
            z-index: 99;
            bottom: -150px;
        }

        .gs-header .gs-title {
            font-size: 15px;
            font-weight: 700;
        }

        .gs-header {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            background-color: #428bca;
            color: #fff;
            margin-bottom: 8px;
        }

        .guest-info:hover .guest-popup {
            display: block !important;
        }

        @media print {
            @page {
                size: legal landscape;
                /* auto is the initial value */
                /* this affects the margin in the printer settings */
                margin: .5in .5in .5in .5in;
            }

            /* normal|break-all|keep-all|break-word|initial|inherit */
            .none-print {
                display: none;
            }

            .emp_name {
                width: 100% !important
            }

            .d-none {
                display: block !important;
            }

        }

    </style>
@endpush



@section('content')
    {{-- PHP --}}
    <?php

    $room = $room_data;

    ?>

    <div class="row">
        <div class="col-sm-12 none-print">

            <div class="col-sm-12 widget-container-col ui-sortable" id="widget-container-col-7">
                <div class="widget-box widget-color-grey ui-sortable-handle" id="widget-box-7">
                    <div class="widget-header widget-header-small">
                        <h5 class="widget-title smaller">
                            @yield('page-header') <span class="badge badge-primary">Total:
                                {{ request('company_id') ? $employees->total() : 0 }}</span>
                        </h5>
                        <div class="widget-toolbar">
                            <div class="btn-group">
                                {{-- <span class="btn btn-sm btn-danger" style="cursor: pointer" onclick="print()"><i class="fa fa-print"></i> Print</span> --}}
                                <a href="" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i> Excel</a>
                            </div>
                        </div>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main">

                            <form>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="bg-dark">Month<strong class="text-danger">*</strong>
                                                </th>
                                                <th class="bg-dark">Department</th>
                                                <th class="bg-dark">Designation</th>
                                                <th class="bg-dark">Employee Id</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="text" name="month" value="{{ request('month') }}"
                                                        class="form-control input-sm month-picker" required
                                                        autocomplete="off" placeholder="Select Month">
                                                </td>

                                                <td>
                                                    <select name="department_id" class="chosen-select">
                                                        <option></option>
                                                        {{-- @foreach ($departments as $key => $value)
                                                            <option
                                                                {{ request('department_id') == $key ? 'selected' : '' }}
                                                                value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach --}}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="designation_id" class="chosen-select">
                                                        <option></option>
                                                        {{-- @foreach ($designations as $key => $value)
                                                            <option
                                                                {{ request('designation_id') == $key ? 'selected' : '' }}
                                                                value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach --}}
                                                    </select>
                                                </td>

                                                <td>
                                                    <select class="chosen-select-100-percent form-control"
                                                        name="employee_full_id">
                                                        <option></option>
                                                        {{-- @foreach ($employee_full_ids ?? [] as $key => $employee)
                                                            <option value="{{ $employee->employee_full_id }}"
                                                                {{ request('employee_full_id') == $employee->employee_full_id ? 'selected' : '' }}>
                                                                {{ $employee->employee_full_id }} ->
                                                                {{ $employee->name }}</option>
                                                        @endforeach --}}
                                                    </select>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td colspan="{{ __(5) }}" class="text-right">
                                                    <div class="btn-group btn-corner">
                                                        <button type="submit" class="btn btn-xs btn-primary">
                                                            <i class="fa fa-search"></i>
                                                            Search
                                                        </button>
                                                        <a href="#" class="btn btn-xs btn-default">
                                                            <i class="fa fa-refresh"></i>
                                                            Refresh
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                            </form>

                            @if (request('month'))
                                @include('partials._alert_message')

                                <div class="space"></div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="shift-table">
                                            <tr>
                                                <td data-bg-color="bg-4" data-shift-id="5" class="bg-4 select-color"
                                                    style="color: #fff">
                                                    Check IN
                                                </td>
                                                <td data-bg-color="bg-3" data-shift-id="4" class="bg-3 select-color"
                                                    style="color: #fff">
                                                    Check Out
                                                </td>
                                                <td data-bg-color="bg-5" data-shift-id="4" class="bg-5 select-color"
                                                    style="color: #fff">
                                                    Arrived
                                                </td>
                                                <td data-bg-color="bg-5" data-shift-id="4" class="bg-0 select-color"
                                                    style="color: #fff">
                                                    Booked
                                                </td>
                                                <td data-bg-color="bg-reset" data-shift-id=""
                                                    class="bg-reset2 select-color">
                                                    Available
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @php
                                    $month_date = fdate(request('month'), 'm');
                                    $year = fdate(request('month'), 'Y');
                                    $total_days = date('t', mktime(0, 0, 0, $month_date, 1, $year));
                                @endphp

                                <form action="" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="bg-dark">Room Number</th>
                                                        @for ($i = 1; $i <= $total_days; $i++)
                                                            <th class="bg-dark">{{ $i }}</th>
                                                        @endfor
                                                    </tr>
                                                    <tr>
                                                        @for ($i = 1; $i <= $total_days; $i++)
                                                            @php
                                                                $date = fdate(request('month') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT));
                                                            @endphp
                                                            <th class="text-center" style="font-size:8px !important">
                                                                {{ fdate($date, 'D') }}</th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody id="schedule_table" class="room-report">
                                                    @foreach ($room as $data)
                                                        <tr>
                                                            <th class="text-center" style="width: 5%">
                                                                <label>{{ $data->room_number }}</label>
                                                            </th>

                                                            @foreach ($data->booking_details as $d)
                                                                <?php
                                                                $status = '';
                                                                if ($d->bookingInfo->status == 0) {
                                                                    $status = 'booked';
                                                                } elseif ($d->bookingInfo->status == 1) {
                                                                    $status = 'check-in';
                                                                } elseif ($d->bookingInfo->status == 3) {
                                                                    $status = 'checkout';
                                                                }
                                                                ?>
                                                                <td>
                                                                    <div class="{{ $status ?? '' }} guest-info">
                                                                        <label>{{ $d->bookingInfo->booking_number }}</label>
                                                                        <div class="guest-popup">
                                                                            <div class="guest-popup-body">
                                                                                <div class="gs-header">
                                                                                    <div class="gs-title"><span><i
                                                                                                class="fa fa-exclamation-circle"></i></span>&nbsp;Guest
                                                                                        Information</div>
                                                                                </div>
                                                                                <div class="gs-name">
                                                                                    {{ $d->bookingInfo->guestInfo->name }}
                                                                                </div>
                                                                                <div class="gs-phone">
                                                                                    {{ $d->bookingInfo->guestInfo->phone_no }}
                                                                                </div>
                                                                                <div class="gs-info mt-1">
                                                                                    <p>Checkin
                                                                                        Date:{{ $d->bookingInfo->check_in_date }}
                                                                                    </p>
                                                                                    <p>Checkout Date:
                                                                                        {{ $d->bookingInfo->check_out_date }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @endforeach

                                                        </tr>
                                                    @endforeach

                                                </tbody>

                                            </table>

                                            {{-- @include('partials._paginate', ['data' => $employees]) --}}

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 text-right">


                                        </div>
                                        <div class="col-sm-6 text-right">
                                            <button class="btn btn-xs btn-primary"><i class="fa fa-save"></i>
                                                Save</button>
                                            <a href="" class="btn btn-xs btn-dark"><i class="fa fa-refresh"></i> Reset</a>
                                        </div>
                                    </div>

                                </form>

                            @endif


                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

@section('js')


    <script src="{{ asset('assets/custom_js/month-picker.js') }}"></script>

    <script>
        $(".guest-info").hover(function() {
            $(this).find('.guest-popup').show();
        }, function() {
            $(this).find('.guest-popup').hide();
        });
    </script>

    <script type="text/javascript">
        let background_color_class = 'bg-0'

        $('#select_all').click(function() {
            $('input:checkbox').prop('checked', this.checked);
        });

        $(document).ready(function() {
            $('.report-title-edit-panel').hide()


            $('.input-shift-id').each(function() {
                if ($(this).val() != '') {
                    let bg_class = ($(".shift-table").find("[data-shift-id='" + $(this).val() + "']")).data(
                        'bg-color')
                    $(this).closest('td').addClass(bg_class)
                }
            })


            $('.input-holiday').each(function() {
                if ($(this).data('old-holiday') != '' && $(this).closest('td').find('.input-shift-id')
                    .val() == '') {

                    $(this).closest('td').addClass(holidaysData.length > 0 ? 'bg-holiday-0' : '')
                    $(this).val($(this).data('date'))
                }
            })

            $.each(holidaysData, function(index, value) {
                $('.date-' + value).addClass('bg-holiday-' + index)


                $('.date-' + value).each(function() {
                    $(this).find('.input-holiday').val($(this).find('.input-holiday').data('date'))
                })
            });
        })

        $('.report-title').click(function() {
            $(this).hide()
            $('.report-title-edit-panel').css({
                'display': 'block'
            })
        })

        $('.input-report-title').keyup(function() {
            $('.report-title').text($(this).val())
        })

        $('.save-title').click(function() {
            $('.report-title').show()
            $('.report-title').css({
                'display': 'block'
            })
            $('.report-title-edit-panel').hide()
        })



        $('.select-color').click(function() {
            background_color_class = $(this).data('bg-color')

            $('.select-color').removeClass('active-bg')

            shift_id = $(this).data('shift-id')

            $(this).addClass('active-bg')
        })



        $(function() {
            var isMouseDown = false,
                isHighlighted;
            $("#schedule_table td")
                .mousedown(function() {
                    isMouseDown = true;
                    $(this).removeClass('bg-reset bg-0 bg-1 bg-2 bg-3 bg-4 bg-5 bg-weakend')
                    $(this).addClass(background_color_class);

                    // isHighlighted = $(this).hasClass(background_color_class);



                    if (background_color_class == 'bg-weakend') {
                        $(this).find('.input-weakend').val($(this).find('.input-holiday').data('date'))
                        $(this).find('.input-holiday').val('')
                        $(this).find('.input-shift-id').val('')
                    } else if (background_color_class.search("bg-holiday") > (-1)) {

                        $(this).find('.input-weakend').val('')
                        $(this).find('.input-shift-id').val('')
                        $(this).find('.input-holiday').val($(this).find('.input-holiday').data('date'))

                    } else {
                        $(this).find('.input-weakend').val('')
                        $(this).find('.input-holiday').val('')
                        $(this).find('.input-shift-id').val(shift_id)
                    }

                    return false; // prevent text selection
                })
                .mouseover(function() {
                    if (isMouseDown) {

                        $(this).removeClass('bg-reset bg-0 bg-1 bg-2 bg-3 bg-4 bg-5 bg-weakend')
                        // $(this).not( ":nth-child(1st)" ).removeClass();
                        $(this).addClass(background_color_class, true);


                        if (background_color_class == 'bg-weakend') {

                            $(this).find('.input-weakend').val($(this).find('.input-holiday').data('date'))
                            $(this).find('.input-holiday').val('')
                            $(this).find('.input-shift-id').val('')

                        } else if (background_color_class.search("bg-holiday") > (-1)) {

                            $(this).find('.input-weakend').val('')
                            $(this).find('.input-shift-id').val('')
                            $(this).find('.input-holiday').val($(this).find('.input-holiday').data('date'))

                        } else {
                            $(this).find('.input-weakend').val('')
                            $(this).find('.input-holiday').val('')
                            $(this).find('.input-shift-id').val(shift_id)
                        }

                        console.log(background_color_class)
                    }

                });

            $(document)
                .mouseup(function() {
                    isMouseDown = false;
                });
        });
    </script>

@endsection
