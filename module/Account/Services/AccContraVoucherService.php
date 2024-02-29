<?php


namespace Module\Account\Services;

use App\Traits\FileSaver;
use Module\Account\Models\Account;
use Module\Account\Models\Voucher;




class AccContraVoucherService
{

    public $invoiceNumberService;

    private $transactionService;



    public $contra;




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
     | STORE CONTRA VOUCHER
     |--------------------------------------------------------------------------
    */
    public function storeContraVoucher($request)
    {

        $this->contra = Voucher::create([

            'invoice_no'    => $this->invoiceNumberService->getContraVoucherInvoiceNo($request->company_id),
            'company_id'    => $request->company_id,
            'date'          => $request->date,
            'description'   => $request->description,
            'reference'     => $request->reference,
            'amount'        => array_sum($request->debit),
            'voucher_type'  => $request->voucher_type,
            'is_approved'   => 0
        ]);


        $this->upload_file($request->attachment, $this->contra, 'attachment', 'contra-vouchers');
    }












    /*
     |--------------------------------------------------------------------------
     | STORE CONTRA VOUCHER DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeContraVoucherDetails($request)
    {

        foreach ($request->account_ids  as $key => $account_id) {

            $debit  = $request->debit[$key];
            $credit = $request->credit[$key];

            $amount = $debit + $credit; // here add two data, because one always 0
            $type   = $debit > $credit ? 'Debit' : 'Credit';



            $detail = $this->contra->details()->create([

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
        $this->contra->update(['is_approved' => 1]);
    }












    /*
     |--------------------------------------------------------------------------
     | MAKE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function makeTransaction()
    {
        foreach ($this->contra->details ?? [] as $key => $detail) {

            $detail->update([

                'transaction_no' => $this->invoiceNumberService->getVoucherDetailTransactionNo($key, $this->contra->invoice_no)
            ]);

            $debit_amount = 0;
            $credit_amount = 0;

            if($detail->balance_type == 'Debit') {

                $debit_amount = $detail->amount;

            } else {

                $credit_amount = $detail->amount;
            }

            $account = Account::find($detail->account_id);

            $description = $this->contra->description;
            
            $this->transactionService->storeTransaction($this->contra->company_id, $detail, $this->contra->invoice_no, $account, $debit_amount, $credit_amount, $this->contra->date, $detail->balance_type, $detail->balance_type == 'Debit' ? 'Contra In' : 'Contra Out', $description);
        }
    }
}
