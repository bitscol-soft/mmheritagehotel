<?php


namespace Module\Account\Services;

use App\Traits\FileSaver;
use Module\Account\Models\Account;
use Module\Account\Models\Voucher;




class AccReceiveVoucherService
{
    public $invoiceNumberService;

    private $transactionService;



    public $receive;




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
    public function storeReceiveVoucher($request)
    {

        $this->receive = Voucher::create([

            'invoice_no'    => $this->invoiceNumberService->getReceiveVoucherInvoiceNo($request->company_id),
            'company_id'    => $request->company_id,
            'date'          => $request->date,
            'description'   => $request->description,
            'reference'     => $request->reference,
            'amount'        => array_sum($request->debit),
            'voucher_type'  => $request->voucher_type,
            'is_approved'   => 0
        ]);


        $this->upload_file($request->attachment, $this->receive, 'attachment', 'receive-vouchers');
    }







    


    

    /*
     |--------------------------------------------------------------------------
     | STORE RECEIVE VOUCHER DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeReceiveVoucherDetails($request)
    {

        foreach ($request->account_ids  as $key => $account_id) {

            $debit  = $request->debit[$key];
            $credit = $request->credit[$key];

            $amount = $debit + $credit; // here add two data, because one always 0
            $type   = $debit > $credit ? 'Debit' : 'Credit';



            $detail = $this->receive->details()->create([

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
        $this->receive->update([ 'is_approved' => 1 ]);
    }







    


    

    /*
     |--------------------------------------------------------------------------
     | MAKE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function makeTransaction()
    {
        foreach ($this->receive->details ?? [] as $key => $detail) {

            $detail->update([

                'transaction_no' => $this->invoiceNumberService->getVoucherDetailTransactionNo($key, $this->receive->invoice_no)
            ]);

            $debit_amount = 0;
            $credit_amount = 0;

            if($detail->balance_type == 'Debit') {

                $debit_amount = $detail->amount;

            } else {

                $credit_amount = $detail->amount;
            }
            
            $account = Account::find($detail->account_id);

            $description = $this->receive->description;

            $this->transactionService->storeTransaction($this->receive->company_id, $detail, $this->receive->invoice_no, $account, $debit_amount, $credit_amount, $this->receive->date, $detail->balance_type, $detail->balance_type == 'Debit' ? 'Cash' : 'Receive', $description);
        }
    }
    
}
