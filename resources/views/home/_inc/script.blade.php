<script>
    $(document).ready(function() {
        $(".room-info").on('click', function() {

            var booking = $(this);
            var cat_id = $(booking).find('#category_id').val();
            var room_id = $(booking).find('#room_id').val();

            var date = $('#available_date').val();

            if ($(booking).hasClass("active")) {

                if ($(this).hasClass('room-free')) {
                    $(booking).removeClass("active").addClass('today-checkout');
                } else {
                    $(booking).removeClass("active");
                }

                RemoveBooking(room_id);

            } else {
                $(booking).removeClass('today-checkout').addClass("active");
                StoreBooking(cat_id, room_id, date, booking);
            }

            updateSelectionSummary();

        });

        // round-3 UI pass: keyboard access for the room tiles (Enter/Space select)
        $(document).on('keydown', '.room-info', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).trigger('click');
            }
        });

        function updateSelectionSummary() {
            var $summary = $('.selection-summary');
            if (!$summary.length) return;
            var count = 0, total = 0;
            $('.room-info.active').each(function () {
                count++;
                var rate = parseFloat($(this).attr('data-rate'));
                if (!isNaN(rate)) total += rate;
            });
            $summary.find('.sel-count').text(count);
            $summary.find('.sel-total').text(total.toLocaleString());
            $summary.toggleClass('hidden', count === 0);
        }
        updateSelectionSummary();

        function StoreBooking(category, room, date, obj) {
            let url = '/hotel/add_booking';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: url,
                async: true,
                data: {
                    'category_id': category,
                    'room_id': room,
                    'date': date
                },
                beforeSend: function() {
                    $("body").css("cursor", "progress");
                },
                success: function(data) {

                    if (data.status) {
                        toastr.success(data.data);
                    } else {
                        toastr.warning(data.data);
                        $(obj).removeClass('active')
                    }
                },
                complete: function(data) {
                    $("body").css("cursor", "default");
                }
            });
        }

        function RemoveBooking(room_id) {

            $.ajax({

                url: '/hotel/remove_booking_next',
                method: "get",
                data: {

                    "product_id": room_id,
                },
                success: function(data) {
                    toastr.warning(data.data);
                },
                complete: function() {
                    updateSelectionSummary();
                }
            });
        }

    });



    $('.input-daterange').datepicker({
        autoclose: true
    });
    var start = moment().subtract(29, 'days');
    var end = moment();



    $(function() {

        var $stayRange = $('input[name="booking_date"]');
        if (window.MMStayRange) {
            // custom_js/stay-range.js: blocks past check-in, enforces one night, validates typed text
            $stayRange.each(function() { MMStayRange.init(this); });
            return;
        }

        $stayRange.daterangepicker({
            autoUpdateInput: true,
        });

        $stayRange.on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                'MM/DD/YYYY'));
            $('form#searchForm').submit();
        });

        $stayRange.on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

    });



    function updateStatus(id, status, e) {
        let _this = $(e);
        let url = "{{ route('update-room-status', ':id') }}";
        url = url.replace(':id', id);

        let html = `<div style='margin: 10px 0'></div>`;
        html += `<select id="swal-status" class="form-control">
                        <option value="0">Dirty</option>
                        <option value="2">Maintanence</option>
                        <option value="1">Ready</option>
                    </select>`
        html += `<textarea name="remarks" id="swal-remark" class="form-control" style="margin-top:10px"></textarea>`

        let confirmButtonText = 'Save';


        Swal.fire({
            title: 'Change Status ?',
            html: html,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
            width: 400,
        }).then((result) => {

            if (result.value) {
                let val = $("#swal-status option:selected").val();
                if (val == 0) {
                    _this.closest('.room-status-ui').find('.room-info').removeClass('inverse').removeClass(
                        'orange').addClass('inverse')
                } else if (val == 2) {
                    _this.closest('.room-status-ui').find('.room-info').removeClass('inverse').removeClass(
                        'orange').addClass('orange')
                } else {
                    _this.closest('.room-status-ui').find('.room-info').removeClass('inverse').removeClass(
                        'orange')
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'POST',
                        _token: '{{ csrf_token() }}',
                        status: $('#swal-status').val(),
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val(),
                        remarks: $('#swal-remark').val(),
                    },
                    success: function(response) {
                        if (response.status) {
                            success('toster', response.data);
                        }
                    }
                });
            }
        })

        $("#swal-status").val(status)
        InitDatePicker()
    }

    function updateKeepingStatus(id, status, e) {
        let _this = $(e);
        let url = "{{ route('update-room-status-keeping', ':id') }}";
        url = url.replace(':id', id);

        let html = `<div style='margin: 10px 0'></div>`;
        html += `<select id="swal-status" class="form-control">
                        <option value="2">Maintanence</option>
                        <option value="1">Ready</option>
                    </select>`
        html += `<textarea name="remarks" id="swal-remark" class="form-control" style="margin-top:10px"></textarea>`

        let confirmButtonText = 'Save';


        Swal.fire({
            title: 'Change Status ?',
            html: html,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
            width: 400,
        }).then((result) => {

            if (result.value) {
                let val = $("#swal-status option:selected").val();
                if (val == 0) {
                    _this.closest('.room-status-ui').find('.room-info').removeClass('inverse').removeClass(
                        'orange').addClass('inverse')
                } else if (val == 2) {
                    _this.closest('.room-status-ui').find('.room-info').removeClass('inverse').removeClass(
                        'orange').addClass('orange')
                } else {
                    _this.closest('.room-status-ui').find('.room-info').removeClass('inverse').removeClass(
                        'orange')
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'POST',
                        _token: '{{ csrf_token() }}',
                        status: $('#swal-status').val(),
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val(),
                        remarks: $('#swal-remark').val(),
                    },
                    success: function(response) {
                        if (response.status) {
                            success('toster', response.data);
                        }
                    }
                });
            }
        })

        $("#swal-status").val(status)
        InitDatePicker()
    }


    function checkOut(url, title, booking_id, e) {

        let _this = $(e);

        let confirmButtonText = 'Yes, do it';


        Swal.fire({
            title: title + '?',
            html: '<b>Are you sure to ' + title + ' ?</b>',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
            width: 400,
        }).then((result) => {

            if (result.value) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'POST',
                        _token: '{{ csrf_token() }}',
                        is_ajax: 1
                    },
                    success: function(response) {
                        if (response.status) {
                            success('toster', response.data);
                            _this.find('.booked-room-info').removeClass('inverse').removeClass(
                                'orange')

                        } else {
                            warning('toster', response.data)
                            // setInterval(() => {
                                window.location = '/hotel/booking/' + booking_id
                            // });
                        }
                    }
                });
            }
        })
    }
</script>
