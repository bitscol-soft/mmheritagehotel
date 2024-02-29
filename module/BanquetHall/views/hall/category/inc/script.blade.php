<script>
    $(document).ready( function(){
        $( ".currency-sign" ).each(function() {
            $(this).text(`{!! currencySign() !!}`);
        });
    });
</script>
<script>
    guestPricing = () => {
        let capacity = $('#capacity').val();
        let html = ''
        for (let index = 0; index < capacity; index++) {
            html += `<div class="col-sm-3 mb-2">
                    <div class="input-group">
                        <span class="input-group-addon">For Guest ${index + 1} <span class="currency-sign"></span></span>
                        <input type="text" name="guest_prices[]" class="form-control only-number" placeholder="Enter Price for guest ${index+1}">
                        <input type="hidden" name="guest_capacities[]" value="${index + 1}">
                    </div>
                </div>`
        }
        $('.price-entry').empty().append(html);

        $(document).ready( function(){
            $( ".currency-sign" ).each(function() {
                $(this).text(`{!! currencySign() !!}`);
            });
        });
    }

    $(document).on('click', '.guest-wise-price', function() {
        $('.guest-wise-price-div').toggle()
        guestPricing()
    })
    $(document).on('keyup', '#capacity', guestPricing)
</script>



<script type="text/javascript">
    jQuery(function($) {
        $('.category_photos').ace_file_input({
            style: 'well',
            btn_choose: 'Upload Category Photos',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small' //large | fit

        }).on('change', function() {
        });
    });
</script>
