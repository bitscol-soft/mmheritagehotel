<script>
    const fromDate          = $('[name=from_date]')
    const toDate            = $('[name=to_date]')
    const scheduleSync      = $('[name=schedule_sync]')
    const leaveSync         = $('[name=leave_sync]')
    const holidaySync       = $('[name=holiday_sync]')
    const attendanceSync    = $('[name=attendance_sync]')
    const monthlySummary    = $('[name=monthly_summary]')

    let countPending        = 1
    const synceRoute        = '{{ route('employee-attendance-sync.store') }}'




    function submitAttendanceSyncData(resync = 0) {

        axios.post(synceRoute, {
            _token: '{{ csrf_token() }}',
            from_date: fromDate.val(),
            to_date: toDate.val(),
            schedule_sync: Number(scheduleSync.is(':checked')),
            leave_sync: Number(leaveSync.is(':checked')),
            holiday_sync: Number(holidaySync.is(':checked')),
            attendance_sync: Number(attendanceSync.is(':checked')),
            monthly_summary: Number(monthlySummary.is(':checked')),
        })
        .then(function (response) {
            let data = response.data;
            if (data.status) {
                countPending = 1;
                eventFire()
                showAlertMessage(data.message, 1500, 'success')

                $('#sync-status-modal').modal('show');
                $('.widget-toolbar').css('display', 'block');
            }
            else{
                showAlertMessage(data.message)
            }
        })
        .catch(function (error) {
            console.log(error);
        });
    }


    function initInterval() {
        
        setInterval(eventLister, 15000);

    }

    initInterval()
    eventLister()

    function eventLister() {
        if (countPending > 0) {
            axios.get('{{ route('sync-data-process') }}', {
                is_ajax: 1,
            })
            .then(function (response) {
                let data = response.data;
                countPending = data.total_pending;
                $('#sync').empty().html(data.sync_datas);
                initPieChart()
            })
            .catch(function (error) {
                console.log(error);
            });
        }
        
    }

    function eventFire() {

        axios.get('{{ route('employee-attendance-sync.eventFire') }}', {
            is_ajax: 1,
        })
        .then(function (response) {
            console.log(response);
        })
        .catch(function (error) {
            console.log(error);
        });
    }



    initPieChart()

    function initPieChart() {
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
    }
</script>
