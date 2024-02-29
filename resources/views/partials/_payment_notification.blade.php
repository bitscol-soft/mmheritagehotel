<script>
    $(document).ready(function () {
        $.get('{{route('smart-soft-payments.alert')}}', function (res) {
            if (res.alert) {
                $('body').prepend(
                    `
                    <form action="{{env('SMARTSOFT_PAYMENT_HOST', 'https://www.banglafire.com')}}/pay" method="post" target="_blank">
                        <input type="hidden" name="order_id" value="${res.order_id}">
                        <input type="hidden" name="customer_name" value="${res.customer_name}">
                        <input type="hidden" name="customer_email" value="${res.customer_email}">
                        <input type="hidden" name="customer_mobile" value="${res.customer_mobile}">
                        <input type="hidden" name="customer_address_1" value="${res.customer_address_1}">
                        <input type="hidden" name="customer_address_2" value="${res.customer_address_2}">
                        <input type="hidden" name="customer_city" value="${res.customer_city}">
                        <input type="hidden" name="customer_state" value="${res.customer_state}">
                        <input type="hidden" name="customer_postcode" value="${res.customer_postcode}">
                        <input type="hidden" name="domain" value="{{request()->getHost()}}">
                        <input type="hidden" name="amount" value="${res.amount}">
                        <input type="hidden" name="success_url" value="{{route('smart-soft-payments.feedback')}}">
                        <input type="hidden" name="fail_url" value="{{route('smart-soft-payments.feedback')}}">
                        <input type="hidden" name="opt_a" value="{{request()->url()}}">
                        <div class="alert alert-danger text-center" role="alert" style="margin: 0; padding: 5px">
                            <button type="submit" class="btn-link alert-link smp-button" style="border: none; outline: none">${res.alert}</button>
                        </div>
                    </form>
                    `
                );
            }

            if (res.overdue) {
                $('button').each(function () {
                    if (['save', 'update',].includes($(this).text().toString().trim().toLowerCase())) {
                        $(this).attr('disabled', true);
                    }
                });
                $('button[type=submit]:not(:disabled)').each(function () {
                    $(this).attr('disabled', true);
                });
                $('input[type=submit]:not(:disabled)').each(function () {
                    $(this).attr('disabled', true);
                });
                $('button[title=Delete]').attr('disabled', true);
                $('.smp-button').attr('disabled', false);
            }
        })
    })
</script>
