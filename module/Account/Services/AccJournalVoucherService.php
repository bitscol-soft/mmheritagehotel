<?php


namespace Module\Account\Services;

use App\Traits\FileSaver;
use Module\Account\Models\Account;
use Module\Account\Models\Voucher;




class AccJournalVoucherService
{
    public $invoiceNumberService;

    private $transactionService;



    public $journal;




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
     | STORE JOURNAL VOUCHER
     |--------------------------------------------------------------------------
    */
    public function storeJournalVoucher($request)
    {

        $this->journal = Voucher::create([

            'invoice_no'    => $this->invoiceNumberService->getJournalVoucherInvoiceNo($request->company_id),
            'company_id'    => $request->company_id,
            'date'          => $request->date,
            'description'   => $request->description,
            'reference'     => $request->reference,
            'amount'        => array_sum($request->debit),
            'voucher_type'  => $request->voucher_type,
            'is_approved'   => 0
        ]);


        $this->upload_file($request->attachment, $this->journal, 'attachment', 'journal-vouchers');
    }












    /*
     |--------------------------------------------------------------------------
     | STORE JOURNAL VOUCHER DETAILS
     |--------------------------------------------------------------------------
    */
    public function storeJournalVoucherDetails($request)
    {

        foreach ($request->account_ids  as $key => $account_id) {

            $debit  = $request->debit[$key];
            $credit = $request->credit[$key];

            $amount = $debit + $credit; // here add two data, because one always 0
            $type   = $debit > $credit ? 'Debit' : 'Credit';



            $detail = $this->journal->details()->create([

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
        $this->journal->update(['is_approved' => 1]);
    }












    /*
     |--------------------------------------------------------------------------
     | MAKE TRANSACTION
     |--------------------------------------------------------------------------
    */
    public function makeTransaction()
    {
        foreach ($this->journal->details ?? [] as $key => $detail) {

            $detail->update([

                'transaction_no' => $this->invoiceNumberService->getVoucherDetailTransactionNo($key, $this->journal->invoice_no)
            ]);


            $debit_amount = 0;
            $credit_amount = 0;

            if($detail->balance_type == 'Debit') {

                $debit_amount = $detail->amount;

            } else {

                $credit_amount = $detail->amount;
            }

            $account = Account::find($detail->account_id);

            $description = $this->journal->description;

            $this->transactionService->storeTransaction($this->journal->company_id, $detail, $this->journal->invoice_no, $account, $debit_amount, $credit_amount, $this->journal->date, $detail->balance_type, $detail->balance_type == 'Debit' ? 'Cash' : 'Journal', $description);
        }
    }
}
