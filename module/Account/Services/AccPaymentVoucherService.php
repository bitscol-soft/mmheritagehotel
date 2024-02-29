<?php


namespace Module\Account\Services;

use App\Traits\FileSaver;
use Module\Account\Models\Account;
use Module\Account\Models\Voucher;

class AccPaymentVoucherService
{

    public $invoiceNumberService;

    private $transactionService;



    public $payment;




    use FileSaver;
    


    

    /*
     |--------------------------------------------------------------------------
     | CONSTRUCTOR
     |--------------------------------------------------------------------------
    */
    public function __construct()
    {
        $this->invoiceNumberService = new InvoiceNumberService();

        $this->transactionService   = new AccountTransactionService();
    }







    


    

    /*
     |--------------------------------------------------------------------------
     | VALIDATE DATA
     |--------------------------------------------------------------------------
    */
    public function validateData($request)
    {

        $request->validate([

            'date'              => 'required',
            'description'       => 'required',
            'balance_type'      => 'in:Debit,Credit',
            'debits.*'          => 'required',
            'credits.*'         => 'required'

        ]);
    }







    


    

    /*
     |--------------------------------------------------------------------------
     | STORE RECEIVE VOUCHER
     |--------------------------------------------------------------------------
    */
    public function storePaymentVoucher($request)
    {

        $this->payment = Voucher::create([

            'invoice_no'    => $this->invoiceNumberService->getPaymentVoucherInvoiceNo($request->company_id),
            'company_id'    => $request->company_id,
            'date'          => $request->date,
            'description'   => $request->description,
            'reference'     => $request->reference,
            'amount'        => array_sum($request->debit),
            'voucher_type'  => $request->voucher_type,
            'is_approved'   => 0
        ]);


        $this->upload_file($request->attachment, $this->payment, 'attachment', 'payment-vouchers');
    }







    


    

    /*
     |--------------------------------------------------------------------------
     | STORE RECEIVE VOUCHER DETAILS
     |--------------------------------------------------------------------------
    */
    public function storePaymentVoucherDetails($request)
    {

        foreach ($request->account_ids  as $key => $account_id) {

            $debit  = $request->debit[$key];
            $credit = $request->credit[$key];

            $amount = $debit + $credit; // here add two data, because one always 0
            $type   = $debit > $credit ? 'Debit' : 'Credit';



            $this->payment->details()->create([

                'account_id'    => $account_id,
                'amount'        => $amount,
                'balance_type'  => $type,
            ]);
        } 
    }







    


    

    /*
     |--------------------------------------------------------------------------
     | APPROVE VOUCHER
     |--------------------------------------------------------------------------
    */
    public function approveVoucher()
    {
        $this->payment->update([ 'is_approved' => 1 ]);
    }







    


    

    /*
     |--------------------------------------------------------------------------
     | MAKE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function makeTransaction()
    {
        foreach ($this->payment->details ?? [] as $key => $detail) {

            $detail->update([

                'transaction_no' => $this->invoiceNumberService->getVoucherDetailTransactionNo($key, $this->payment->invoice_no)
            ]);

            
            $account = Account::find($detail->account_id);

            $debit_amount = 0;
            $credit_amount = 0;

            if($detail->balance_type == 'Debit') {

                $debit_amount = $detail->amount;

            } else {

                $credit_amount = $detail->amount;
            }

            $description = $this->payment->description;
            

            $this->transactionService->storeTransaction($this->payment->company_id, $detail, $this->payment->invoice_no, $account, $debit_amount, $credit_amount, $this->payment->date, $detail->balance_type, $detail->balance_type == 'Debit' ? 'Cash' : 'Payment', $description);
        }
    }
    
}
