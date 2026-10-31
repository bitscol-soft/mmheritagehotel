<?php

namespace Module\Account\Services;

use Module\Account\Models\Collection;

class AccCollectionService
{
    public $invoiceNumberService;

    public $transactionService;

    public $collection;

    public function __construct()
    {
        $this->invoiceNumberService = new InvoiceNumberService();

        $this->transactionService   = new AccountTransactionService();
    }


    public function store($request)
    {
        $invoice_no = $this->invoiceNumberService->getCollectionInvoiceNo($request->company_id);

        $this->collection = Collection::create([
            'customer_id'   => $request->customer_id,
            'date'          => $request->date,
            'invoice_no'    => $invoice_no,
            'transaction_no'=> $request->transaction_no,
            'remarks'       => $request->remarks,
            'amount'        => $request->amount,
        ]);

        return $this->collection;
    }


    public function update($request, Collection $collection)
    {
        $collection->transactions()->delete();

        $collection->update([
            'customer_id'   => $request->customer_id,
            'date'          => $request->date,
            'transaction_no'=> $request->transaction_no,
            'remarks'       => $request->remarks,
            'amount'        => $request->amount,
        ]);

        $this->collection = $collection;

        return $this->collection;
    }


    public function makeTransaction()
    {
        $cash_account       = $this->transactionService->getCashAccount();                 // debit
        $customer_account   = optional(optional($this->collection)->customer)->account;    // credit

        $collection         = $this->collection->refresh();
        $invoice_no         = $collection->invoice_no;
        $date               = $collection->date;

        $description        = 'Collection from ' . (optional($collection->customer)->name ?? 'Mr. Customer');

        $this->transactionService->storeTransaction($collection->company_id, $collection, $invoice_no, $cash_account,     $collection->amount, 0,                     $date, 'debit',  'Cash',    $description);   //  Money In
        $this->transactionService->storeTransaction($collection->company_id, $collection, $invoice_no, $customer_account, 0,                     $collection->amount, $date, 'credit', 'Receive', $description);   //  Due Cleared
    }
}
