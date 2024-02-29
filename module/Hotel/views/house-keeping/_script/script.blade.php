<script>
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
</script>
