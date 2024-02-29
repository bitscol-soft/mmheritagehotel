@extends('layouts.master')
@section('title', 'System Setting')
@section('page-header')
    <i class="fa fa-empire"></i> System Setting
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-colorpicker.min.css') }}" />

    <style>
        .bg-dark {
            background-color: #ededed;
        }

        .chosen-container.chosen-container-single {
            width: 150px !important;
        }
    </style>

@stop


@section('content')


    <x-alert-message />


    <div class="row mt-5">
        <form class="form-horizontal" action="{{ route('system-setting.store') }}" method="post">
            @csrf

            <div class="col-sm-8 col-sm-offset-2">
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-dark">Title</th>
                        <th class="bg-dark">Value</th>
                    </tr>

                    @foreach ($systemSettings as $key => $systemSetting)
                        <tr>
                            <th>
                                @if ($systemSetting->key == 'general_store_reference_no_change')
                                    General Store (Reference) Title Change
                                @elseif ($systemSetting->key == 'out_work_date_picker')
                                    OutWork DatePicker All Open
                                @elseif ($systemSetting->key == 'employee_summary_gross_salary_get')
                                    Employee Summary Gross Salary Get
                                @elseif ($systemSetting->key == 'finger_id_get')
                                    Finger Print Id Get Attendance Sheet
                                @elseif ($systemSetting->key == 'employee_list_card_no')
                                    Set Employee List Card No
                                @elseif ($systemSetting->key == 'custom_employee_full_id')
                                    Custom Employee Full Id
                                @elseif ($systemSetting->key == 'employee_login_option')
                                    Admin & Employee Login Option
                                @elseif ($systemSetting->key == 'employee_attendance_chart')
                                    Employee Attendance Chart
                                @elseif ($systemSetting->key == 'line')
                                    Employee Line Number
                                @elseif ($systemSetting->key == 'line_caption')
                                    Employee Line Caption
                                @elseif ($systemSetting->key == 'employee_group')
                                    Employee Group
                                @elseif ($systemSetting->key == 'mfs')
                                    Empployee MFS
                                @elseif ($systemSetting->key == 'employee_signature')
                                    Employee Signature
                                @elseif ($systemSetting->key == 'dashboard')
                                    Would you prefer which Dashboard?
                                @elseif ($systemSetting->key == 'topbar_background_color')
                                    Topbar Background Color(color name/code)
                                @elseif ($systemSetting->key == 'topbar_text_color')
                                    Topbar Text Color(color name/code)
                                @elseif ($systemSetting->key == 'login_background_image')
                                    Login Background Image
                                @elseif ($systemSetting->key == 'default_login_for')
                                    Default Login For
                                @elseif ($systemSetting->key == 'employee_general_shift')
                                    Employee General Shift
                                @elseif ($systemSetting->key == 'leave_recommender_required')
                                    Leave Recommender Required
                                @elseif ($systemSetting->key == 'hierarchy_wise_employee_ordering')
                                    Employee Hierarchy Wise Ordering
                                @elseif ($systemSetting->key == 'late_time_count_from')
                                    Late Time Count From
                                @elseif ($systemSetting->key == 'employee_facilities')
                                    Employee Facility
                                @elseif ($systemSetting->key == 'visible_booking_ui_dashboard')
                                    Show Booking UI to Dashboard
                                @elseif ($systemSetting->key == 'room_wise_pricing_booking')
                                    Room Wise Pricing & Booking
                                    <div class="mt-1"
                                        style="background: #ffe4e4; padding: 7px; color: #7b0000; border-radius: 5px">
                                        <i class="far fa-info-circle"></i> NOTE <br>
                                        <div class="ml-0.2">
                                            <small>Create Room With Room Wise Priceing</small><br>
                                            <small>Create Booking Room Wise Priceing</small>
                                        </div>
                                    </div>
                                @elseif ($systemSetting->key == 'category_wise_booking')
                                    Only Category Wise Booking
                                    <div class="mt-0.2"
                                        style="background: #ffe4e4; padding: 5px; color: #7b0000; border-radius: 5px">
                                        <i class="far fa-info-circle"></i> NOTE <br>
                                        <div class="ml-1">
                                            <small>Only Category Select Booking</small><br>
                                            <small>Room Choose And Set After Booked</small>
                                        </div>
                                    </div>
                                @elseif ($systemSetting->key == 'bulk_booking')
                                    Bulk Booking
                                @elseif ($systemSetting->key == 'enable_account_transaction_for_hotel')
                                    Enable Account Transaction for Hotel
                                @elseif ($systemSetting->key == 'restaurant_can_sell_bar_product')
                                    Restaurant Can Sale Bar Product ?
                                @elseif ($systemSetting->key == 'date_start_end')
                                    Set your Date Start -- End
                                @elseif ($systemSetting->key == 'root_currency')
                                    Select Your Base Currency
                                @elseif ($systemSetting->key == 'bar_due_list_date')
                                    Show Bar Due List Date
                                @elseif ($systemSetting->key == 'mother_inventory')
                                    Mother Inventory
                                @elseif ($systemSetting->key == 'account_transaction_when_night_audit')
                                    Account Transaction When Night Audit
                                @elseif ($systemSetting->key == 'report_with_night_audit')
                                    Report With Night Audit
                                @elseif ($systemSetting->key == 'rst_use_kitchen_module')
                                    Restaurant Use Kitchen Module
                                @elseif ($systemSetting->key == 'use_vat_included')
                                    Is VAT Included Hotel & Restaurant?
                                    {{-- <div class="mt-0.2"
                                        style="background: #ffe4e4; padding: 5px; color: #7b0000; border-radius: 5px">
                                        <i class="far fa-info-circle"></i> NOTE <br>
                                        <div class="ml-1">
                                            <small>Demo 1</small><br>
                                            <small>Demo 2</small>
                                        </div>
                                    </div> --}}
                                @elseif ($systemSetting->key == 'enable_only_image_for_pos_print')
                                    Enable Only Image For Pos Print?
                                @elseif ($systemSetting->key == 'enable_only_company_name_for_pos_print')
                                    Enable Only Company Name For Pos Print?
                                @endif
                            </th>
                            <td>

                                @if ($systemSetting->key == 'general_store_reference_no_change')
                                    <input type="text" class="form-control" name="key[general_store_reference_no_change]"
                                        value="{{ $systemSetting->value }}">
                                @elseif ($systemSetting->key == 'out_work_date_picker')
                                    <label> <input type="radio" name="key[out_work_date_picker]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label> <input type="radio" name="key[out_work_date_picker]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'employee_summary_gross_salary_get')
                                    <label><input type="radio" name="key[employee_summary_gross_salary_get]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_summary_gross_salary_get]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'finger_id_get')
                                    <label><input type="radio" name="key[finger_id_get]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[finger_id_get]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'employee_list_card_no')
                                    <label><input type="radio" name="key[employee_list_card_no]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_list_card_no]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'custom_employee_full_id')
                                    <label><input type="radio" name="key[custom_employee_full_id]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[custom_employee_full_id]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'employee_login_option')
                                    <label><input type="radio" name="key[employee_login_option]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_login_option]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'employee_attendance_chart')
                                    <label><input type="radio" name="key[employee_attendance_chart]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_attendance_chart]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'line')
                                    <label><input type="radio" name="key[line]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[line]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'line_caption')
                                    <input type="text" class="form-control" name="key[line_caption]"
                                        value="{{ $systemSetting->value }}">
                                @elseif ($systemSetting->key == 'employee_group')
                                    <label><input type="radio" name="key[employee_group]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_group]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'mfs')
                                    <label><input type="radio" name="key[mfs]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[mfs]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'employee_signature')
                                    <label><input type="radio" name="key[employee_signature]"
                                            {{ $systemSetting->value == 1 ? 'checked' : '' }} value="1"> Yes</label>
                                    <label><input type="radio" name="key[employee_signature]"
                                            {{ $systemSetting->value == 0 ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'dashboard')
                                    <label><input type="radio" name="key[dashboard]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0">
                                        Default</label>
                                    <label><input type="radio" name="key[dashboard]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1"> Company
                                        Wise</label>
                                @elseif ($systemSetting->key == 'topbar_background_color')
                                    <input type="text" class="form-control color-picker"
                                        name="key[topbar_background_color]" value="{{ $systemSetting->value }}">
                                @elseif ($systemSetting->key == 'topbar_text_color')
                                    <input type="text" class="form-control color-picker" name="key[topbar_text_color]"
                                        value="{{ $systemSetting->value }}">
                                @elseif ($systemSetting->key == 'login_background_image')
                                    <label><input type="radio" name="key[login_background_image]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[login_background_image]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'default_login_for')
                                    <label><input type="radio" name="key[default_login_for]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Employee</label>
                                    <label><input type="radio" name="key[default_login_for]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0">
                                        Admin</label>
                                @elseif ($systemSetting->key == 'employee_general_shift')
                                    <label><input type="radio" name="key[employee_general_shift]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[employee_general_shift]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'leave_recommender_required')
                                    <label><input type="radio" name="key[leave_recommender_required]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[leave_recommender_required]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'hierarchy_wise_employee_ordering')
                                    <label><input type="radio" name="key[hierarchy_wise_employee_ordering]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[hierarchy_wise_employee_ordering]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'late_time_count_from')
                                    <label><input type="radio" name="key[late_time_count_from]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1"> In End
                                        Time</label>
                                    <label><input type="radio" name="key[late_time_count_from]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> In Start
                                        Time</label>
                                @elseif ($systemSetting->key == 'employee_facilities')
                                    <label><input type="radio" name="key[employee_facilities]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[employee_facilities]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'visible_booking_ui_dashboard')
                                    <label><input type="radio" name="key[visible_booking_ui_dashboard]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[visible_booking_ui_dashboard]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'room_wise_pricing_booking')
                                    <label><input type="radio" name="key[room_wise_pricing_booking]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[room_wise_pricing_booking]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'category_wise_booking')
                                    <label><input type="radio" name="key[category_wise_booking]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[category_wise_booking]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'bulk_booking')
                                    <label><input type="radio" name="key[bulk_booking]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[bulk_booking]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'restaurant_can_sell_bar_product')
                                    <label><input type="radio" name="key[restaurant_can_sell_bar_product]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[restaurant_can_sell_bar_product]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'enable_account_transaction_for_hotel')
                                    <label><input type="radio" name="key[enable_account_transaction_for_hotel]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[enable_account_transaction_for_hotel]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'date_start_end')
                                    <input type="hidden" name="key[date_start_end]" id="date_start_end">
                                    <div class="input-group">
                                        <input type="text" class="form-control time-picker" id="date_start"
                                            value="{{ explode('-', $systemSetting->value)[0] ?? '' }}">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" class="form-control time-picker" id="date_end"
                                            value="{{ explode('-', $systemSetting->value)[1] ?? '' }}">
                                    </div>
                                @elseif ($systemSetting->key == 'root_currency')
                                    <div class="input-group">
                                        <select name="key[root_currency]" data-selected="{{ $systemSetting->value }}"
                                            class="form-control chosen-select-100-percent"
                                            style="width: 150px !important">
                                            <option value=""></option>
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif ($systemSetting->key == 'bar_due_list_date')
                                    <label><input type="radio" name="key[bar_due_list_date]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[bar_due_list_date]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'mother_inventory')
                                    <label><input type="radio" name="key[mother_inventory]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[mother_inventory]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'account_transaction_when_night_audit')
                                    <label><input type="radio" name="key[account_transaction_when_night_audit]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[account_transaction_when_night_audit]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'report_with_night_audit')
                                    <label><input type="radio" name="key[report_with_night_audit]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[report_with_night_audit]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'rst_use_kitchen_module')
                                    <label><input type="radio" name="key[rst_use_kitchen_module]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[rst_use_kitchen_module]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>
                                @elseif ($systemSetting->key == 'use_vat_included')
                                    <label><input type="radio" name="key[use_vat_included]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes</label>
                                    <label><input type="radio" name="key[use_vat_included]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0"> No</label>

                                @elseif ($systemSetting->key == 'enable_only_image_for_pos_print')
                                    <label>
                                        <input type="radio" name="key[enable_only_image_for_pos_print]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes
                                    </label>
                                    <label>
                                    <input type="radio" name="key[enable_only_image_for_pos_print]"
                                        {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0">
                                            No
                                    </label>
                                @elseif ($systemSetting->key == 'enable_only_company_name_for_pos_print')
                                    <label>
                                        <input type="radio" name="key[enable_only_company_name_for_pos_print]"
                                            {{ $systemSetting->value == '1' ? 'checked' : '' }} value="1">
                                        Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="key[enable_only_company_name_for_pos_print]"
                                            {{ $systemSetting->value == '0' ? 'checked' : '' }} value="0">
                                            No
                                    </label>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th class="bg-dark text-center" colspan="2">
                            <button class="btn-sm btn-outline-success"><i class="fa fa-check"></i> Save</button>
                        </th>
                    </tr>

                </table>
            </div>
        </form>
    </div>


@endsection




@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap-colorpicker.min.js') }}"></script>




    <!--  Select Box Search-->
    <script type="text/javascript">
        jQuery(function($) {

            if (!ace.vars['touch']) {
                $('.chosen-select').chosen({
                    allow_single_deselect: true
                });
                //resize the chosen on window resize

                $(window)
                    .off('resize.chosen')
                    .on('resize.chosen', function() {
                        $('.chosen-select').each(function() {
                            var $this = $(this);
                            $this.next().css({
                                'width': $this.parent()
                            });
                        })
                    }).trigger('resize.chosen');
                $('.color-picker').colorpicker();
            }
        })

        $(document).on('change', '#date_start, #date_end', function() {
            let start = $('#date_start').val()
            let end = $('#date_end').val()
            let format = start + '-' + end;
            $('#date_start_end').val(format)
            console.log($('#date_start_end').val());
        })
    </script>

@stop
