@extends('layouts.master')

@section('title','Send Sms')

@section('page-header')
    <i class="fa fa-list"></i> Send Sms
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.custom.min.css') }}" />

    <style type="text/css">
        .table-border-none td, tr {
            border: none !important;
        }
        .has-mobile {
            color: red !important;
        }
    </style>
@stop


@section('content')

    <div class="row">
        <div class="col-sm-12">

            <!-- heading -->
            <div class="widget-box widget-color-white ui-sortable-handle clearfix" id="widget-box-7">
                <div class="widget-header widget-header-small">
                    <h3 class="widget-title smaller text-primary">
                        @yield('page-header')
                    </h3>
                </div>


                <!-- filter -->
                <div class="row">
                    <form class="form-horizontal" action="" method="get">
                        <div class="col-sm-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Company</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Employee Id</th>
                                    <td rowspan="2" class="text-center">
                                        <div class="btn-group btn-corner">
                                            <button class="btn btn-primary btn-mini" style="margin-top: 5px"><i class="fa fa-search"></i> Search</button>
                                            <a href="{{ route('sms-sends.create') }}" style="margin-top: 5px" class="btn btn-info btn-mini"><i class="fa fa-refresh"></i> Refresh</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <select name="company" class="chosen-select-220">
                                            <option value=""> - Select - </option>
                                            @foreach($companies as $id => $item)
                                                <option value="{{ $id }}" {{ request('company') == $id ? 'selected' : ''  }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="department" class="chosen-select-220">
                                            <option value=""> - Select - </option>
                                            @foreach($departments as $id => $item)
                                                <option value="{{ $id }}" {{ request('department') == $id ? 'selected' : ''  }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="designation" class="chosen-select-220">
                                            <option value=""> - Select - </option>
                                            @foreach($designations as $id => $item)
                                                <option value="{{ $id }}" {{ request('designation') == $id ? 'selected' : ''  }}>{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input class="form-control" name="employee"></td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>

                <div class="alert-message"></div>

                <!-- entry form -->
                <div class="row" style="width: 100%; margin: 0 !important;">
                    <div class="col-sm-12">
                        <p>
                            <strong>Selected Employee: <span class="total-selected-employee">0</span>/{{ $employees ? $employees->count() : 0  }}</strong>
                        </p>
                        <div class="col-sm-8" style="padding: 0 !important;">
                            <label style="width: 100%">Message
                                <strong class="pull-right"><span class="total-character-count">0</span>/640</strong> <!-- character length count  -->
                                <strong class="pull-right text-center" style="width: 30px !important;">|</strong>
                                <strong class="pull-right"><span class="part-count">0</span>/4</strong> <!-- sms count -->
                            </label>
                            <textarea class="form-control message-area" maxlength="640" rows="5" placeholder="Type your message here..."></textarea>
                            <button type="button" class="btn btn-primary btn-sm pull-right send-message-btn" style="margin-top: 5px">Send Message</button>
                        </div>
                        <div class="col-sm-4">
                            <table class="table table-sm table-border-none" style="font-weight: bold !important;">
                                <tr>
                                    <td class="text-right">Remaining Balance</td>
                                    <td width="10px">:</td>
                                    <td width="30px"><span class="remaining-balance">300</span></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Character Count</td>
                                    <td>:</td>
                                    <td><span class="total-character-count">0</span>/640</td>
                                </tr>
                                <tr>
                                    <td class="text-right">Sms Count</td>
                                    <td>:</td>
                                    <td><span class="part-count">0</span>/4</td>
                                </tr>
                                <tr>
                                    <td class="text-right">Current Cost</td>
                                    <td>:</td>
                                    <td><span class="total-selected-count">0</span></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Available</td>
                                    <td>:</td>
                                    <td><span class="message-available-count">500</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <table>
                            <thead>
                                <tr>
                                    <td>
                                        <label>
                                            <input type="checkbox" class="ace parentCheckBox">
                                            <span class="lbl" style="font-weight:800"> Select All Employee </span>
                                        </label>
                                    </td>
                                </tr>
                            </thead>
                            <tbody style="height: 150px !important;">
                                @foreach($employees as $key => $employee)
                                    <tr>
                                        <td style="padding-left: 20px; padding-top: 5px">
                                            <label>
                                                <input type="checkbox" data-mobile="{{ $employee->present_phone_number }}" {{ $employee->present_phone_number ? '' : 'disabled' }} class="ace childCheckBox {{ $employee->present_phone_number ? 'mobile' : '' }}" value="{{ $employee->id }}">
                                                <span data-mobile="{{ $employee->present_phone_number }}"  class="lbl {{ $employee->present_phone_number ? '' : 'has-mobile' }}"> {{ $employee->name }} </span>
                                            </label>
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


@endsection

@section('js')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.custom.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>

    


    <!-- delete confirm dialog -->
    <script type="text/javascript" src="{{ asset('assets/custom_js/confirm_delete_dialog.js') }}"></script>

    <script type="text/javascript">
        $('[data-rel=popover]').popover({html:true, container:'body'});
    </script>

    <!--  Select Box Search-->
    <script type="text/javascript" src="{{ asset('assets/custom_js/chosen-box.js') }}"></script>


    <script type="text/javascript">

        let sendingNumbers = "";

        $('.send-message-btn').prop('disabled', true);

        // employee list check event
        $('.childCheckBox').click(function () {
            calculateMessage()
        })

        // check all employee
        $('.parentCheckBox').click(function () {
            $('.mobile').prop('checked', this.checked)
            calculateMessage()
        });

        // calculate total message history
        function calculateMessage() {
            let total_selected_employee = parseInt($('.childCheckBox[type="checkbox"]:checked').length)
            $('.total-selected-employee').text(total_selected_employee)
            $('.total-selected-count').text(total_selected_employee)
            let remaining_balance =  parseInt($('.remaining-balance').text())
            $('.message-available-count').text(remaining_balance - total_selected_employee)
            checkButtonSubmit()
        }

        // check submit button will be enable or disable
        function checkButtonSubmit() {
            let total_selected_employee = parseInt($('.childCheckBox[type="checkbox"]:checked').length)
            let message_length          = $('.message-area').val().length
            let remaining_balance       =  parseInt($('.remaining-balance').text())

            $('.send-message-btn').prop('disabled', false);
            if (total_selected_employee == 0 || message_length == 0 || total_selected_employee > remaining_balance) {
                $('.send-message-btn').prop('disabled', true);
            }
        }

        // text area message type event
        $('.message-area').keyup(function () {
            let max = 640
            let part = 160 //max / 4;
            let len = $(this).val().length
            let partCount = (len/part) + 1
            if (partCount>4) {
                partCount = 4
            }
            if (len == 0) {
                partCount = 0
            }
            $('.part-count').text(parseInt(partCount))
            $('.total-character-count').text(len)
            checkButtonSubmit()
        });

        // message send / submit message
        $('.send-message-btn').click(function () {
            // get current active api
            $.ajax({
                url: '{{ url("news-events/active-sms-api") }}',
                type: 'GET',
                success: function(res) {
                    let api_info = {
                        username: res.username,
                        password: res.password,
                        sender_id: res.sender_number,
                        url: res.url,
                    }
                    sendSms(api_info)
                }
            });
        });
        // http://185.188.124.121/api/mt/SendSMS
        // user=demo
        // &password=demo123
        // &senderid=WEBSMS
        // &channel=Normal
        // &DCS=0
        // &flashsms=0
        // &number=91989xxxxxxx,91999xxxxxxx
        // &text=test message

        // send message
        function sendSms(api_info) {
            let message = $('.message-area').val()
            let base_url = api_info.url
            let sendableNumbers = getSendableNumbers()
            $.ajax({
                url: base_url,
                type: 'GET',
                data: {
                    user : api_info.username,
                    password : api_info.password,
                    senderid : api_info.sender_id,
                    channel : 'Normal',
                    DCS : 0,
                    flashsms : 0,
                    number : sendableNumbers,
                    text : message
                },
                success: function(res) {
                    console.log(res)
                }
            });
            setAlertMessage()
            $('.mobile').prop('checked', false)
            $('.message-area').val('')
            sendableNumbers = ""
        }

        function appendNumber(numberss) {
            if (numberss.length == 13) {
                if (sendingNumbers.length > 0) {
                    sendingNumbers += ","
                }
                sendingNumbers += numberss
            }
        }
        function getSendableNumbers() {
            let data = $('.childCheckBox[type="checkbox"]:checked')
            $.each(data, function(key, item) {
                let employeeNumber = $(item).data('mobile') + ""
                number = employeeNumber.substring(0, 2);
                if (number != "88") {
                    let preset = "88"
                    if (employeeNumber.substring(0, 1) != "0") {
                        preset += "0"
                    }
                    appendNumber(preset + "" + employeeNumber)
                } else {
                    if (employeeNumber.substring(2, 3) != "0") {
                        let output = [employeeNumber.slice(0, 3), "0", employeeNumber.slice(3)].join('');
                        employeeNumber = output
                    }
                    if (employeeNumber.length == 13) {
                        appendNumber(employeeNumber)
                    }
                }
            });
            return sendingNumbers
        }

        function setAlertMessage() {
            let alertMessage = ""
            alertMessage += '<div class=\"alert alert-success success\">'
                alertMessage += '<button type=\"button\" class=\"close\" data-dismiss=\"alert\">'
                    alertMessage += '<i class=\"ace-icon fa fa-times\"></i>'
                alertMessage += '</button>'

                alertMessage += '<strong>'
                    alertMessage += '<i class=\"ace-icon fa fa-check-circle\"></i>'
                    alertMessage += "Success !"
                alertMessage += '</strong>'

                alertMessage += '<span class="success-alert-message"></span>'
            alertMessage += '</div>'
            $('.alert-message').html(alertMessage)
        }
    </script>

@endsection


