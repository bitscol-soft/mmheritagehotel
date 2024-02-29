<script>


    function showExtraChargeModal(obj, bookingId, bookingNumber, extraCharge=0, reason=''){

        $('#bookingId').val(bookingId);
        $('.bookingNumber').text(bookingNumber);
        //$('#extraAmount').val(extraCharge);
        //$('#reason').text(reason);
        //$('.payment_type'+paymentMethodId).prop('checked', true);

        $('#extraChargeModal').toggleClass("md-show");
    }



    function closeExtraChargeModal(){
        $(".close-modal").removeClass("md-show")
    }

  </script>
