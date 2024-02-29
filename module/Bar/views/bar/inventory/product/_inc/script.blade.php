<script>
    const productPrice = $('#sale_price')



    $(document).on('keyup', '[name=vat_amount], #sale_price', function(){

        let amount      = Number(productPrice.val())
        let vat_amount  = Number($('[name=vat_amount]').val())
        let vat_percent = Number($('[name=vat_percent]').val())

        $('[name=vat_percent]').val(getPercent(amount, vat_amount).toFixed(2))

    })


    $(document).on('keyup', '[name=vat_percent], #sale_price', function(){

        let amount      = Number(productPrice.val())
        let vat_amount  = Number($('[name=vat_amount]').val())
        let vat_percent = Number($('[name=vat_percent]').val())

        $('[name=vat_amount]').val(getPercentAmount(amount, vat_percent).toFixed(2))

    })
</script>