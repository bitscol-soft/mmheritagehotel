<x-multi-account-pay-script-booking />

<script>
    function dueCollectMulti(url, e, amount = 0, id) {
            let _this = $(e);
            let total_amount = $('.grand_total').val(Number(amount).toFixed(2))
            let booking_id   = $('#booking_id').val(id)
            let booking      = $('#booking_id').val()
            let paid         = $('.account-paid-amount').val()

        }
</script>

<script>



    function clearInputs() {
            $("#accountTypeTable tbody").find("tr:gt(0)").remove();
            $('#account-type-modal').modal('hide');
        }
    $(document).one('click', ".save-button", $.debounce(800, saveDueDataWithPayment));
    // $(document).on('click', '.save-button', saveDueDataWithPayment)


    function saveDueDataWithPayment() {
            saleAjax()
        }

    function saleAjax() {
            $.LoadingOverlay("show")
            $.ajax({
                url: $('#due-form').attr('action'),
                method: 'post',
                data: $('#due-form').serialize(),
                success: function(data) {
                    if (data.status) {

                        clearInputs();

                        success('toster', 'Due Has been Collected.');

                        $.LoadingOverlay("hide")

                        window.location.reload();

                        // if (is_print == 1) {
                        //     setTimeout(() => {
                        //         window.open('/bar/sales-v2/' + data.data.id + '?invoice_type=' +
                        //             $(
                        //                 '.invoice-type:checked').val(), '_blank')
                        //     }, 2000);

                        // }

                    }
                },
                error:function(err) {
                    warning('toster', 'Something wrong.');
                    $.LoadingOverlay("hide");
                    console.log(err);
                }
            });
        }

</script>
