
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>


    //-----------------------------------------------------------//
    //                   SUBMIT ROOM STORE FORM                  //
    //-----------------------------------------------------------//
    function submitRoomStoreForm(obj)
    {
        if ( $('#currencyId').val() == '' ) {
            toastr.error('Please choose a Currency!');
            return;
        }
        if ( $('#rate').val() == '' ) {
            toastr.error('Please choose a Rate!');
            return;
        }
        if ( $('#effectedDate').val() == '' ) {
            toastr.error('Please choose a Effected Date!');
            return;
        }

        $('.createCurrencyConversionForm').submit();
    }



</script>
