@extends('layouts.master')

@section('title','Send Sms')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.custom.min.css') }}" />
@stop


@section('content')
    <x-mm.styles />
    <x-mm.page title="Send Sms Manually" description="Send an SMS message to manually entered phone numbers.">
        <x-mm.panel class="tw-p-4">
            <!-- entry form -->
            <div class="row px-1" style="width: 100%; margin: 0 !important;">
                <div class="alert-message"></div>
                <div class="col-sm-6 pr-1">
                    <label style="width: 100%">Add Numbers</label>
                    <textarea class="form-control number-area" maxlength="640" rows="5" placeholder="8801xxxxxxxxx,8801xxxxxxxxx,....."></textarea>
                </div>
                <div class="col-sm-6" style="padding: 0 !important;">
                    <label style="width: 100%">Message
                        <strong class="pull-right"><span class="total-character-count">0</span>/640</strong> <!-- character length count  -->
                        <strong class="pull-right text-center" style="width: 30px !important;">|</strong>
                        <strong class="pull-right"><span class="part-count">0</span>/4</strong> <!-- sms count -->
                    </label>
                    <textarea class="form-control message-area" maxlength="640" rows="5" placeholder="Type your message here..."></textarea>
                </div>
                <button type="button" class="btn btn-primary btn-sm pull-right send-message-btn" style="margin-top: 10px; margin-bottom: 20px">Send Message</button>
            </div>
        </x-mm.panel>
    </x-mm.page>
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

        // check submit button will be enable or disable
        function checkButtonSubmit() {
            let message_length = $('.message-area').val().length
            let number_length  = $('.number-area').val().length

            $('.send-message-btn').prop('disabled', false);
            if (message_length == 0 || number_length == 0) {
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

        // text area number type event
        $('.number-area').keyup(function () {
            // console.table($(this).val())
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
                    alert()
                }
            });
            setAlertMessage()
            $('.mobile').prop('checked', false)
            $('.message-area').val('')
            $('.number-area-area').val('')
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
            let inputNumbers = $('.number-area').val() + ""
            let data = inputNumbers.split(",")
            $.each(data, function(key, item) {
                let employeeNumber = item + ""
                employeeNumber = employeeNumber.trim()
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
            console.table(sendingNumbers)
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


