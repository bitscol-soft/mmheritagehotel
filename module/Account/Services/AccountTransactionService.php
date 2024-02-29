<?php


namespace Module\Account\Services;

use Module\Account\Models\Account;
use Module\Account\Models\Transaction;

class AccountTransactionService
{

    public function storeTransaction($company_id, $model, $invoice_no, $account, $debit_amount, $credit_amount, $date, $balance_type, $transaction_item_type, $description)
    {
        if ($debit_amount + $credit_amount == 0) {

            $transection = $model->transactions()->where([

                'invoice_no'            => $invoice_no,
                'transaction_item_type' => $transaction_item_type,
                'balance_type'          => $balance_type,
                'account_id'            => $account->id,

            ])->first();

            if ($transection != null) {
                $transection->delete();
            }

        } else {

            $transection = $model->transactions()->updateOrCreate([

                'invoice_no'            => $invoice_no,
                'balance_type'          => $balance_type,
                'transaction_item_type' => $transaction_item_type,
                'account_id'            => $account->id,

            ], [

                'debit_amount'  => $debit_amount,
                'credit_amount' => $credit_amount,
                'date'          => $date,
                'company_id'    => $company_id,
                'description'   => $description
            ]);

            $transection->update([
                'batch_id' => $transection->transactionable_type . '-' . $transection->transactionable_id
            ]);
        }

        return $transection;
    }


    public function deleteTransaction($invoice_no)
    {
        Transaction::where('invoice_no', $invoice_no)->delete();
    }



    public function getAccount($accountID)
    {
        return Account::where('id', $accountID)->first();
    }



    public function getAccountById($id)
    {
        return Account::find($id);
    }















    /*
     |--------------------------------------------------------------------------
     | CASH ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getCashAccount()
    {
        return Account::find(request('account_id') ?? config('account.cash'));
    }












    /*
     |--------------------------------------------------------------------------
     | SALE ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getSaleAccount()
    {
        return Account::find(config('account.sale'));
    }








    /*
     |--------------------------------------------------------------------------
     | SERVICE REVENUE ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getServiceAccount()
    {
        return Account::find(config('account.service'));
    }





    /*
     |--------------------------------------------------------------------------
     | SALE RETURN ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getSaleReturnAccount()
    {
        return Account::find(config('account.sale_return'));
    }






    /*
     |--------------------------------------------------------------------------
     | PURCHASE RETURN ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getPurchaseReturnAccount()
    {
        return Account::find(config('account.purchase_return'));
    }






    /*
     |--------------------------------------------------------------------------
     | DAMAGE ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getDamageAccount()
    {
        return Account::find(config('account.damage'));
    }












    /*
     |--------------------------------------------------------------------------
     | PURCHASE ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getPurchaseAccount()
    {
        return Account::find(config('account.purchase'));
    }












    /*
     |--------------------------------------------------------------------------
     | PARTY OPENING ACCOUNT
     |--------------------------------------------------------------------------
    */
    public function getPartyOpeningAccount()
    {
        return Account::find(config('account.party_opening'));
    }
}
