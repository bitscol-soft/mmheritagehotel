<script>

    //------------ CALCULATE TOTAL DUE ------------//
    function calculateTotalDue(){

        $('#invoice-list').addClass('due-tbody').removeClass('paid-tbody');
        $('.total-due-amount').show()
        $('.total-paid-amount').hide()

        let due = 0;

        $('.due-tbody').find('.itemAmount').each(function(){
            due += parseFloat($(this).closest('tr').find('.itemAmount').html());
        });

        console.log(due);
        $('.total-due-sum').html(due);

    }



    //------------ CALCULATE TOTAL PAID ------------//
    function calculateTotalPaid(){

        $('#invoice-list').addClass('paid-tbody').removeClass('due-tbody');
        $('.total-due-amount').hide()
        $('.total-paid-amount').show()

        let paid = 0;

        $('.paid-tbody').find('.itemAmount').each(function(){
            paid += parseFloat($(this).closest('tr').find('.itemAmount').html());
        });

        $('.total-paid-sum').html(paid);

    }



    //------------ CALCULATE TOTAL PAID ------------//
    function isPaidOrDueSelected(){
        if ($('.duePaymentStatus').is(':checked')){
            calculateTotalDue()
        }
        if($('.paidPaymentStatus').is(':checked')){
            calculateTotalPaid()
        }
    }

</script>
