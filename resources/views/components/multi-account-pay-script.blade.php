

<script>

    let addTableRow = `<tr>
                        <th width="30%">
                            <input type="hidden" name="transaction_ledger_ids[]" value="">
                            <select name="modal_account_types[]" style="width: 100%" class="form-control select2 select-account-type"
                                data-placeholder="- Select Type -" aria-hidden="true" required>
                                <option value=""></option>
                                @foreach (account_types() as $id => $name)
                                    <option value="{{ $id }}">
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </th>

                        <th style="display: flex">
                            <input name="modal_account_paid_amounts[]" type="text" placeholder="Enter Amount"
                                class="form-control account-way-paid-amount only-number" required />
                        </th>

                        <th width="7%">
                            <button type="button" class="remove-row"
                                style="background-color: transparent;border: none;" title="Remove">
                                <i class="fa fa-times-circle fa-lg text-danger"></i>
                            </button>
                        </th>
                    </tr>`

addRow = () => {
    $('#accountTypeTable tbody').append(addTableRow)
    $('.select-account-type').select2()
}


calculateAccountTotal = () => {

    let totalAmount = 0;

    $('.account-way-paid-amount').map(function(){

        totalAmount += Number($(this).val());

        let payble = Number($('.grand_total').val());

        if(totalAmount > payble ){
            $(this).val(payble)
            warning('toster', 'You can\'t pay more than Payable amount.');
            return
        }

    })


    $('.account-paid-amount, .total_amount, #paid_amount').val(totalAmount)

    let modalTotalPayable = Number($('.grand_total').val());
    let accountPaidAmount = Number($('.account-paid-amount').val());

        if(accountPaidAmount > modalTotalPayable ){
        $('.account-paid-amount').val(modalTotalPayable);
        return
        }
    let changeAmount = 0;
    if(accountPaidAmount > modalTotalPayable){
        changeAmount = accountPaidAmount - modalTotalPayable;
    }
    let modalDueAmount = 0;
    if(accountPaidAmount < modalTotalPayable){
        modalDueAmount = modalTotalPayable - accountPaidAmount;
    }

    $('.change_amount').val(changeAmount.toFixed(2))
    $('.due_amount').val(modalDueAmount.toFixed(2))
}




$(document).on('click', '#addrow', addRow)

$(document).on('click', '.remove-row', function(){
    $(this).closest('tr').remove();
    calculateAccountTotal()
})

$(document).on('keyup', '.account-way-paid-amount', calculateAccountTotal)

$(document).on('change', '.select-account-type', function(){

let selectedPaymentMethod = $.trim($(this).find('option:selected').text());

if (selectedPaymentMethod == 'Card') {
    $('#card_info').toggle();
}
})
</script>
