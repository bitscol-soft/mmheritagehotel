jQuery(function ($) {
    var dateToday = new Date();


    $('.date-picker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true
    }).next().on(ace.click_event, function () {
        $(this).prev().focus();
    });

    $('.date-picker-disable-past').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        startDate: dateToday,
    }).next().on(ace.click_event, function () {
        $(this).prev().focus();
    });

    $('.month-picker').datepicker({
        autoclose: true,
        format: 'yyyy-mm',
        viewMode: "months",
        minViewMode: "months",
    });


    $('.year-picker').datepicker({
        autoclose: true,
        format: 'yyyy',
        viewMode: "years",
        minViewMode: "years",
    });


    function loadTimePicker(elem) {
        $(elem).timepicker({
            minuteStep: 1,
            showMeridian: true,
            defaultTime: '',
            icons: {
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down'
            }
        })
    }


    InitTimePicker();
    InitDatePicker();
})

function InitTimePicker(elem) {
    $('.time-picker').timepicker({
        minuteStep: 1,
        showMeridian: true,
        defaultTime: '',
        icons: {
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down'
        }
    })
}


function InitDatePicker() {
    $('.date-picker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        todayHighlight: true
    }).next().on(ace.click_event, function () {
        $(this).prev().focus();
    });
}
